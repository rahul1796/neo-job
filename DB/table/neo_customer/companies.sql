--------------- SQL ---------------

CREATE TABLE neo_customer.companies (
  id SERIAL,
  company_name TEXT,
  lead_type_id INTEGER,
  lead_source_id INTEGER,
  company_description TEXT,
  industry_id INTEGER,
  functional_area_id INTEGER,
  hr_name TEXT,
  hr_email TEXT,
  hr_phone TEXT,
  hr_designation TEXT,
  landline TEXT,
  fax_number TEXT,
  skype_id TEXT,
  annual_revenue INTEGER,
  website TEXT,
  target_employers TEXT,
  remarks TEXT,
  created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL,
  created_by INTEGER DEFAULT 1 NOT NULL,
  updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL,
  updated_by INTEGER DEFAULT 1 NOT NULL,
  PRIMARY KEY(id)
) ;
