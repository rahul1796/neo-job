--------------- SQL ---------------

ALTER TABLE neo_customer.customer_branches
  DROP COLUMN location;

  --------------- SQL ---------------

ALTER TABLE neo_customer.customer_branches
  ADD COLUMN updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT now() NOT NULL;

  --------------- SQL ---------------

ALTER TABLE neo_customer.customer_branches
  ADD COLUMN updated_by INTEGER DEFAULT 1 NOT NULL;

  --------------- SQL ---------------

ALTER TABLE neo_customer.customer_branches
  ADD COLUMN opportunity_id INTEGER;
