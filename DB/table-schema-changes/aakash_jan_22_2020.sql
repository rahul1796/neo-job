--------------- SQL ---------------

ALTER TABLE neo_customer.opportunities
  ADD COLUMN is_contract BOOLEAN DEFAULT FALSE NOT NULL;
