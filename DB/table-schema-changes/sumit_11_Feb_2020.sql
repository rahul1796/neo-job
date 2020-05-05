ALTER TABLE neo_customer.customer_branches
  ADD COLUMN same_as_main BOOLEAN DEFAULT FALSE;
  
  ALTER TABLE neo_customer.customer_branches
  ALTER COLUMN same_as_main SET NOT NULL;