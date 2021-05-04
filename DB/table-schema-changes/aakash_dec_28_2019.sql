-----------------------------customer document audit table--------------------------------
CREATE TABLE neo_customer.customer_documents_audit (
  id SERIAL,
  customer_document_id INTEGER NOT NULL,
  file_name TEXT NOT NULL,
  customer_id INTEGER NOT NULL,
  customer_document_created_at TIMESTAMP  NOT NULL,
  customer_document_created_by INTEGER NOT NULL,
  created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL
)
WITH (oids = false);

ALTER TABLE neo_customer.customer_documents_audit
  OWNER TO postgres;

-----------------------------customer commercial audit table--------------------------------
  CREATE TABLE neo_customer.customer_commercials_audit (
  id SERIAL,
  customer_commercials_id INTEGER NOT NULL,
  customer_id INTEGER NOT NULL,
  title TEXT ,
  value INTEGER ,
  fee_type INTEGER ,
  customer_commercials_created_at TIMESTAMP  NOT NULL,
  customer_commercials_created_by INTEGER  NOT NULL,
  remarks TEXT,
  option_remarks TEXT,
  created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL
)
WITH (oids = false);

ALTER TABLE neo_customer.customer_commercials_audit
  OWNER TO postgres;


  -------------------------------------trigger customer commercial--------------------------

CREATE OR REPLACE FUNCTION neo_customer.customer_commercials_audit_trigger_fn() RETURNS TRIGGER AS $body$
BEGIN
INSERT INTO neo_customer.customer_commercials_audit
	(
        customer_commercials_id,
        customer_id,
        title,
        value,
        fee_type,
        customer_commercials_created_at,
        customer_commercials_created_by,
        remarks,
        option_remarks
  	)
    VALUES
    (
    	OLD.id,
        OLD.customer_id,
        OLD.title,
        OLD.value,
        OLD.fee_type,
        OLD.created_at,
        OLD.created_by,
        OLD.remarks,
        OLD.option_remarks
    );
RETURN OLD;
END
$body$ LANGUAGE plpgsql;

CREATE TRIGGER customer_commercials_trigger AFTER DELETE ON neo_customer.customer_commercials
    FOR EACH ROW EXECUTE PROCEDURE neo_customer.customer_commercials_audit_trigger_fn();


    -------------------------------------- trigger customer document ------------------------------------
    CREATE OR REPLACE FUNCTION neo_customer.customer_documents_audit_trigger_fn() RETURNS TRIGGER AS $body$
BEGIN
INSERT INTO neo_customer.customer_documents_audit
	(
    	customer_document_id,
        file_name,
        customer_id,
        customer_document_created_at,
        customer_document_created_by
  	)
    VALUES
    (
    	OLD.id,
  		OLD.file_name,
	    OLD.customer_id,
	    OLD.created_at,
	    OLD.created_by
    );
RETURN OLD;
END
$body$ LANGUAGE plpgsql;

CREATE TRIGGER customer_documents_trigger AFTER DELETE ON neo_customer.customer_documents
    FOR EACH ROW EXECUTE PROCEDURE neo_customer.customer_documents_audit_trigger_fn();  
