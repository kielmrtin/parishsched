-- Donation payment methods (GCash and cheque)
-- Run once in Supabase Dashboard -> SQL Editor.
--
-- Panel revision: "GCash inclusion" and "include GCash or online donation
-- check (tseke)". Every column is added with a default, so existing
-- donations keep working and read as cash that was received.
--
--   payment_method : cash | gcash | cheque
--   payment_status : received (cash)
--                    pending -> verified | rejected   (GCash, checked by staff)
--                    uncleared -> cleared | rejected  (cheque)
--   source         : admin (recorded by staff) | online (customer "Give online")
-- Only received / verified / cleared donations count toward the totals.

alter table public.donations
  add column if not exists payment_method text not null default 'cash',
  add column if not exists payment_status text not null default 'received',
  add column if not exists gcash_reference text,
  add column if not exists receipt_url text,
  add column if not exists cheque_number text,
  add column if not exists cheque_bank text,
  add column if not exists cheque_date date,
  add column if not exists reservation_id bigint references public.reservations(id) on delete set null,
  add column if not exists customer_id bigint references public.customers(id) on delete set null,
  add column if not exists source text not null default 'admin';

alter table public.donations drop constraint if exists donations_payment_method_check;
alter table public.donations add constraint donations_payment_method_check
  check (payment_method in ('cash', 'gcash', 'cheque'));

alter table public.donations drop constraint if exists donations_payment_status_check;
alter table public.donations add constraint donations_payment_status_check
  check (payment_status in ('received', 'pending', 'verified', 'rejected', 'uncleared', 'cleared'));

alter table public.donations drop constraint if exists donations_source_check;
alter table public.donations add constraint donations_source_check
  check (source in ('admin', 'online'));

-- The same GCash reference number cannot be submitted twice (a rejected
-- entry does not block a corrected resubmission).
create unique index if not exists donations_gcash_reference_key
  on public.donations (gcash_reference)
  where gcash_reference is not null and payment_status <> 'rejected';

create index if not exists donations_reservation_id_idx on public.donations (reservation_id);
create index if not exists donations_customer_id_idx on public.donations (customer_id);
