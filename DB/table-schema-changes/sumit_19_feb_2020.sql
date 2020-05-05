CREATE TABLE neo_job.candidates_placements_audit (
  id SERIAL,
  placement_id INTEGER,
  candidate_id INTEGER,
  job_id INTEGER,
  employment_type TEXT,
  employer_name TEXT,
  employer_contact_phone TEXT,
  employer_location TEXT,
  placement_location TEXT,
  ctc TEXT,
  date_of_join DATE,
  offer_letter_date_of_join DATE,
  offer_letter_file TEXT,
  offer_letter_uploaded_on TIMESTAMP WITHOUT TIME ZONE,
  candidate_number TEXT,
  clcs_job_id INTEGER,
  placement_created_on TIMESTAMP WITHOUT TIME ZONE,
  placement_created_by INTEGER,
  employment_type_id INTEGER,
  offered_remarks TEXT,
  resigned_date DATE,
  reason_to_leave TEXT,
  offered_ctc TEXT,
  created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  CONSTRAINT candidates_placement_audit_pkey PRIMARY KEY(id)
)
WITH (oids = false);

-------------------------------------------------------

CREATE OR REPLACE FUNCTION neo_job.candidates_placements_audit_trigger_fn() RETURNS TRIGGER AS $body$
BEGIN
INSERT INTO neo_job.candidates_placements_audit (
placement_id,
candidate_id,
job_id,
employment_type,
employer_name,
employer_contact_phone,
employer_location,
placement_location,
ctc,
date_of_join,
offer_letter_date_of_join,
offer_letter_file,
offer_letter_uploaded_on,
candidate_number,
clcs_job_id,
placement_created_on,
placement_created_by,
employment_type_id,
offered_remarks,
resigned_date,
reason_to_leave,
offered_ctc)

VALUES (OLD.id,
OLD.candidate_id,
OLD.job_id,
OLD.employment_type,
OLD.employer_name,
OLD.employer_contact_phone,
OLD.employer_location,
OLD.placement_location,
OLD.ctc,
OLD.date_of_join,
OLD.offer_letter_date_of_join,
OLD.offer_letter_file,
OLD.offer_letter_uploaded_on,
OLD.candidate_number,
OLD.clcs_job_id,
OLD.created_on,
OLD.created_by,
OLD.employment_type_id,
OLD.offered_remarks,
OLD.resigned_date,
OLD.reason_to_leave,
OLD.offered_ctc);
RETURN OLD;
END
$body$ LANGUAGE plpgsql;

CREATE TRIGGER candidates_placements_audit_trigger AFTER UPDATE ON neo_job.candidate_placement
FOR EACH ROW EXECUTE PROCEDURE neo_job.candidates_placements_audit_trigger_fn();


ALTER FUNCTION neo_job.candidates_placements_audit_trigger_fn ()
  OWNER TO postgres;