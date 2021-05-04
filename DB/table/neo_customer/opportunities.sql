--------------- SQL ---------------

CREATE TABLE neo_customer.opportunities (
  id SERIAL,
  company_id INTEGER,
  managed_by TEXT,
  lead_status_id INTEGER,
  business_vertical_id INTEGER,
  functional_area_id INTEGER,
  industry_id INTEGER,
  labournet_entity_id INTEGER,
  opportunity_code TEXT,
  contract_id TEXT,
  active BOOLEAN DEFAULT true NOT NULL,
  is_paid BOOLEAN DEFAULT false NOT NULL,
  has_documents BOOLEAN DEFAULT false NOT NULL,
  has_commercial BOOLEAN DEFAULT false NOT NULL,
  legally_verified BOOLEAN DEFAULT false NOT NULL,
  created_by INTEGER DEFAULT 1 NOT NULL,
  created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL,
  updated_by INTEGER DEFAULT 1 NOT NULL,
  updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now(),
  PRIMARY KEY(id)
) ;
