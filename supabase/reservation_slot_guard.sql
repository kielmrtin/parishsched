-- Reservation slot guard
-- Run once in Supabase Dashboard -> SQL Editor.
--
-- The website and the Flutter app both check for slot conflicts before
-- inserting, but two submissions at the same instant could both pass that
-- check. This trigger re-checks inside the database while holding a lock on
-- the date+time slot, so the second insert waits for the first and then
-- sees it. Rules match ReservationController::store():
--   - Wedding/Funeral: one reservation per slot.
--   - Baptism: up to 5 per slot, and never shared with a Wedding/Funeral.
-- Only pending/approved/booked reservations take up a slot.

create or replace function public.guard_reservation_slot()
returns trigger
language plpgsql
security definer          -- must see every user's reservations, not just RLS-visible ones
set search_path = public
as $$
declare
  v_time     text := lower(trim(replace(coalesce(new.reservation_time, ''), '–', '-')));
  v_type     text := lower(coalesce(new.event_type, ''));
  v_baptisms int;
  v_others   int;
begin
  if new.reservation_date is null
     or lower(coalesce(new.status, 'pending')) not in ('pending', 'approved', 'booked') then
    return new;
  end if;

  -- Serialize inserts for this exact slot until the transaction ends.
  perform pg_advisory_xact_lock(
    hashtext('reservation_slot:' || new.reservation_date::text || '|' || v_time)
  );

  select count(*) filter (where lower(r.event_type) = 'baptism'),
         count(*) filter (where lower(r.event_type) <> 'baptism')
    into v_baptisms, v_others
    from public.reservations r
   where r.reservation_date = new.reservation_date
     and lower(r.status) in ('pending', 'approved', 'booked')
     and lower(trim(replace(coalesce(r.reservation_time, ''), '–', '-'))) = v_time;

  if v_type = 'baptism' then
    if v_others > 0 then
      raise exception 'SLOT_CONFLICT: This time slot is already reserved for another event. Please choose a different time.';
    end if;
    if v_baptisms >= 5 then
      raise exception 'SLOT_CONFLICT: Baptism slots for this time are full (max 5). Please choose another date.';
    end if;
  elsif v_baptisms + v_others > 0 then
    raise exception 'SLOT_CONFLICT: This time slot was just reserved by someone else. Please choose another available time.';
  end if;

  return new;
end;
$$;

drop trigger if exists reservations_slot_guard on public.reservations;
create trigger reservations_slot_guard
  before insert on public.reservations
  for each row execute function public.guard_reservation_slot();
