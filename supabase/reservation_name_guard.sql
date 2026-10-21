-- Reservation name guard
-- Run once in Supabase Dashboard -> SQL Editor.
--
-- Panel revision: "reservation detection for name". Blocks a second active
-- reservation for the same person and sacrament. The website checks first
-- (App\Services\ReservationNameGuard); this trigger enforces the same rule
-- for the Flutter app, which inserts into Supabase directly. Rules:
--   - The person is who receives the sacrament: the baptism child, the
--     wedding groom and bride, the funeral deceased, the person being
--     confirmed for a kumpil (added with the Kumpil event; re-run this file).
--   - Names match only when the whole normalized full name matches
--     (first + middle + last + suffix; case, accents, dots and extra spaces
--     ignored), so a different surname is never blocked.
--   - Only pending/approved/booked reservations of the same sacrament count.

create or replace function public.normalize_person_name(p text)
returns text
language sql
immutable
as $$
  select trim(regexp_replace(
           regexp_replace(
             translate(lower(coalesce(p, '')), 'ñáàâäéèêëíìîïóòôöúùûü', 'naaaaeeeeiiiioooouuuu'),
             '[.,]', ' ', 'g'),
           '\s+', ' ', 'g'));
$$;

-- Normalized names of everyone receiving the sacrament. Reads the website's
-- nested details (details->'baptism'->>'child_first') and the app's flat
-- keys (details->>'groom_first'). The app's flat first_name/last_name is the
-- person reserving, so it is never used.
create or replace function public.reservation_subject_names(p_event_type text, d jsonb)
returns text[]
language plpgsql
immutable
as $$
declare
  t     text  := lower(trim(coalesce(p_event_type, '')));
  b     jsonb := coalesce(d -> t, '{}'::jsonb);
  names text[] := '{}';
  n     text;
begin
  if jsonb_typeof(b) <> 'object' then
    b := '{}'::jsonb;
  end if;

  if t = 'baptism' then
    n := public.normalize_person_name(concat_ws(' ',
           coalesce(b->>'child_first',  d->>'child_first'),  coalesce(b->>'child_middle', d->>'child_middle'),
           coalesce(b->>'child_last',   d->>'child_last'),   coalesce(b->>'child_suffix', d->>'child_suffix')));
    if n <> '' then names := names || n; end if;

  elsif t = 'wedding' then
    n := public.normalize_person_name(concat_ws(' ',
           coalesce(b->>'groom_first',  d->>'groom_first'),  coalesce(b->>'groom_middle', d->>'groom_middle'),
           coalesce(b->>'groom_last',   d->>'groom_last'),   coalesce(b->>'groom_suffix', d->>'groom_suffix')));
    if n <> '' then names := names || n; end if;
    n := public.normalize_person_name(concat_ws(' ',
           coalesce(b->>'bride_first',  d->>'bride_first'),  coalesce(b->>'bride_middle', d->>'bride_middle'),
           coalesce(b->>'bride_last',   d->>'bride_last'),   coalesce(b->>'bride_suffix', d->>'bride_suffix')));
    if n <> '' then names := names || n; end if;

  elsif t = 'kumpil' then
    n := public.normalize_person_name(concat_ws(' ',
           b->>'confirmand_first', b->>'confirmand_middle', b->>'confirmand_last', b->>'confirmand_suffix'));
    if n <> '' then names := names || n; end if;

  elsif t = 'funeral' then
    n := public.normalize_person_name(concat_ws(' ',
           coalesce(b->>'deceased_first',  d->>'deceased_first'),  coalesce(b->>'deceased_middle', d->>'deceased_middle'),
           coalesce(b->>'deceased_last',   d->>'deceased_last'),   coalesce(b->>'deceased_suffix', d->>'deceased_suffix')));
    if n = '' then
      n := public.normalize_person_name(coalesce(b->>'deceased_name', d->>'deceased_name'));
    end if;
    if n <> '' then names := names || n; end if;
  end if;

  return names;
end;
$$;

create or replace function public.guard_reservation_name()
returns trigger
language plpgsql
security definer          -- must see every user's reservations, not just RLS-visible ones
set search_path = public
as $$
declare
  v_type  text   := lower(trim(coalesce(new.event_type, '')));
  v_names text[] := public.reservation_subject_names(new.event_type, coalesce(new.details, '{}'::jsonb));
  v_name  text;
  v_hit   record;
begin
  if lower(coalesce(new.status, 'pending')) not in ('pending', 'approved', 'booked')
     or coalesce(array_length(v_names, 1), 0) = 0 then
    return new;
  end if;

  -- Serialize inserts for the same person so two same-instant submissions
  -- cannot both pass. Sorted to keep lock order stable.
  for v_name in select unnest(v_names) order by 1 loop
    perform pg_advisory_xact_lock(hashtext('reservation_name:' || v_type || '|' || v_name));
  end loop;

  select r.reservation_date, lower(r.status) as status
    into v_hit
    from public.reservations r
   where lower(trim(r.event_type)) = v_type
     and lower(r.status) in ('pending', 'approved', 'booked')
     and public.reservation_subject_names(r.event_type, coalesce(r.details, '{}'::jsonb)) && v_names
   limit 1;

  if found then
    raise exception 'DUPLICATE_NAME: This person already has an active % reservation (%, %). Each person can only have one active % reservation. Please contact the parish office if this is a mistake.',
      v_type, v_hit.status, to_char(v_hit.reservation_date, 'Mon FMDD, YYYY'), v_type;
  end if;

  return new;
end;
$$;

drop trigger if exists reservations_name_guard on public.reservations;
create trigger reservations_name_guard
  before insert on public.reservations
  for each row execute function public.guard_reservation_name();
