CREATE OR REPLACE FUNCTION neo_user.fn_check_password_validity (
  i_user_id integer,
  i_password text
)
RETURNS integer AS
$body$
DECLARE
	d_counter1 integer = 0;
    d_counter2 integer = 0;
  
BEGIN
	WITH RES AS
    (
        SELECT 		UA.pwd,
                  	MIN(id) AS id
        FROM 		neo_user.users_audit AS UA
        WHERE		UA.user_id=i_user_id
        AND			UA.pwd NOT IN (SELECT UAP.pwd from neo_user.users as UAP where UAP.id = i_user_id)
        GROUP BY	UA.pwd
        ORDER BY	id DESC
        LIMIT		2       
    )
    SELECT 	COUNT(RES.id) AS counter
    INTO	d_counter1
    FROM 	RES
    WHERE 	RES.pwd=i_password;    
    
    SELECT 	COUNT(UAP.id)
    INTO	d_counter2
    FROM 	neo_user.users AS UAP
    WHERE 	UAP.id=i_user_id
    AND		UAP.pwd=i_password;    
    
    RETURN (d_counter1 + d_counter2);
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
PARALLEL UNSAFE
COST 100;

ALTER FUNCTION neo_user.fn_check_password_validity (i_user_id integer, i_password text)
  OWNER TO postgres;