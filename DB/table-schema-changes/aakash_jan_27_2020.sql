
CREATE TABLE neo_customer.opportunities_audit (
  id SERIAL,
  opportunity_id INTEGER,
  company_id INTEGER,
  managed_by TEXT,
  lead_status_id INTEGER,
  business_vertical_id INTEGER,
  functional_area_id INTEGER,
  industry_id INTEGER,
  labournet_entity_id INTEGER,
  opportunity_code TEXT,
  contract_id TEXT,
  active BOOLEAN,
  is_paid BOOLEAN,
  has_documents BOOLEAN,
  has_commercial BOOLEAN,
  legally_verified BOOLEAN,
  opportunity_created_by INTEGER,
  opportunity_created_at TIMESTAMP,
  opportunity_updated_by INTEGER,
  opportunity_updated_at TIMESTAMP,
  is_contract BOOLEAN,
  created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL,
  CONSTRAINT opportunities_audit_pkey PRIMARY KEY(id)
);

ALTER TABLE neo_customer.opportunities_audit
  OWNER TO postgres;



CREATE OR REPLACE FUNCTION neo_customer.opportunities_audit_trigger_fn() RETURNS TRIGGER AS $body$
BEGIN
INSERT INTO neo_customer.opportunities_audit (
  opportunity_id,
  company_id,
  managed_by,
  lead_status_id,
  business_vertical_id,
  functional_area_id,
  industry_id,
  labournet_entity_id,
  opportunity_code,
  contract_id,
  active,
  is_paid,
  has_documents,
  has_commercial,
  legally_verified,
  opportunity_created_by,
  opportunity_created_at,
  opportunity_updated_by,
  opportunity_updated_at,
  is_contract
)
VALUES (
  OLD.id,
  OLD.company_id,
  OLD.managed_by,
  OLD.lead_status_id,
  OLD.business_vertical_id,
  OLD.functional_area_id,
  OLD.industry_id,
  OLD.labournet_entity_id,
  OLD.opportunity_code,
  OLD.contract_id,
  OLD.active,
  OLD.is_paid,
  OLD.has_documents,
  OLD.has_commercial,
  OLD.legally_verified,
  OLD.created_by,
  OLD.created_at,
  OLD.updated_by,
  OLD.updated_at,
  OLD.is_contract
);
RETURN OLD;
END
$body$ LANGUAGE plpgsql;

CREATE TRIGGER opportunities_audit_trigger AFTER UPDATE ON neo_customer.opportunities
    FOR EACH ROW EXECUTE PROCEDURE neo_customer.opportunities_audit_trigger_fn();


---------------comapny audit table and trigger------------------------

CREATE TABLE neo_customer.companies_audit (
  id SERIAL,
  company_id INTEGER,
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
  company_created_at TIMESTAMP,
  company_created_by INTEGER,
  company_updated_at TIMESTAMP,
  company_updated_by INTEGER,
  created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL,
  CONSTRAINT companies_audit_pkey PRIMARY KEY(id)
) ;

ALTER TABLE neo_customer.companies_audit
  OWNER TO postgres;



  --------------------company trigger---------------------------


  CREATE OR REPLACE FUNCTION neo_customer.companies_audit_trigger_fn() RETURNS TRIGGER AS $body$
  BEGIN
  INSERT INTO neo_customer.companies_audit (
    company_id,
    company_name,
    lead_type_id,
    lead_source_id,
    company_description,
    industry_id,
    functional_area_id,
    hr_name,
    hr_email,
    hr_phone,
    hr_designation,
    landline,
    fax_number,
    skype_id,
    annual_revenue,
    website,
    target_employers,
    remarks,
    company_created_at,
    company_created_by,
    company_updated_at,
    company_updated_by
  )
  VALUES (
    OLD.id,
    OLD.company_name,
    OLD.lead_type_id,
    OLD.lead_source_id,
    OLD.company_description,
    OLD.industry_id,
    OLD.functional_area_id,
    OLD.hr_name,
    OLD.hr_email,
    OLD.hr_phone,
    OLD.hr_designation,
    OLD.landline,
    OLD.fax_number,
    OLD.skype_id,
    OLD.annual_revenue,
    OLD.website,
    OLD.target_employers,
    OLD.remarks,
    OLD.created_at,
    OLD.created_by,
    OLD.updated_at,
    OLD.updated_by
  );
  RETURN OLD;
  END
  $body$ LANGUAGE plpgsql;

  CREATE TRIGGER companies_audit_trigger AFTER UPDATE ON neo_customer.companies
      FOR EACH ROW EXECUTE PROCEDURE neo_customer.companies_audit_trigger_fn();



      --------------- SQL ---------------

ALTER TABLE neo_job.jobs
  ADD COLUMN opportunity_id INTEGER;
