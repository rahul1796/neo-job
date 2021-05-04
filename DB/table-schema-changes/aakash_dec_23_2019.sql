--------------- SQL ---------------

ALTER TABLE neo_customer.lead_logs
  ADD COLUMN potential_order_value_per_month INTEGER;

--------------- SQL ---------------

ALTER TABLE neo_customer.lead_logs
  ADD COLUMN potential_number INTEGER;