CREATE OR REPLACE FUNCTION neo_user.fn_get_recursive_team_data_with_role (
  i_user_id integer
)
RETURNS TABLE (
  level integer,
  user_id integer,
  user_name text,
  user_role text,
  user_role_id integer
) AS
$body$
DECLARE

BEGIN
	RETURN QUERY
     	WITH RECURSIVE REC AS
        (
            SELECT 		U.id AS user_id,
                        U.name AS user_name,
            			UR.name AS user_role,
                        1 AS level,
                        U.user_role_id AS user_role_id
            FROM 		neo_user.users AS U
            LEFT JOIN   neo_user.user_roles AS UR ON UR.id = U.user_role_id
            WHERE 		U.id = i_user_id
            UNION ALL
            SELECT  	A.id AS user_id,
                        A.name AS user_name,
            			URR.name AS user_role,
                        (R.level + 1) AS level,
						A.user_role_id AS user_role_id
            FROM  		neo_user.users AS A
        	LEFT JOIN   neo_user.user_roles AS URR ON URR.id = A.user_role_id
            INNER JOIN 	REC AS R ON R.user_id = A.reporting_manager_id
        )
        SELECT   	DISTINCT
                    REC.level,
                    REC.user_id,
                    REC.user_name,
                    REC.user_role,
                    REC.user_role_id
        FROM   		REC
        ORDER BY 	REC.level,
                    REC.user_name;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION neo_user.fn_get_recursive_team_data_with_role (i_user_id integer)
  OWNER TO postgres;


CREATE OR REPLACE FUNCTION neo_user.fn_get_current_coo_reportees (
  i_logged_in_user_id integer = '-1'::integer,
  i_role_id integer = '-1'::integer
)
RETURNS TABLE (
  level integer,
  user_id integer,
  user_name text,
  user_role text,
  user_role_id integer
) AS
$body$
DECLARE
	 manager_id INTEGER = -1;
BEGIN
	WITH RECURSIVE CTE AS (

      SELECT  U1.id,
              U1.name,
              UR1.name as user_role,
              U1.reporting_manager_id,
              U1.reporting_manager_role_id,
              U1.user_role_id
      FROM neo_user.users AS U1
      LEFT JOIN neo_user.user_roles AS UR1 ON UR1.id = U1.user_role_id
      WHERE U1.id=i_logged_in_user_id

      UNION ALL

      SELECT  U2.id,
              U2.name,
              UR2.name as user_role,
              U2.reporting_manager_id,
              U2.reporting_manager_role_id,
              U2.user_role_id
      FROM neo_user.users AS U2
      LEFT JOIN neo_user.user_roles AS UR2 ON UR2.id = U2.user_role_id
      INNER JOIN CTE AS R ON R.reporting_manager_id=U2.id
	)

    SELECT CTE.id into manager_id FROM CTE WHERE CTE.user_role_id=i_role_id;

    RAISE NOTICE 'manager_id=%',manager_id;

	RETURN QUERY
    SELECT  X.level,
  			X.user_id,
  			X.user_name,
            X.user_role,
            X.user_role_id
    FROM neo_user.fn_get_recursive_team_data_with_role(manager_id) AS X;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION neo_user.fn_get_current_coo_reportees (i_logged_in_user_id integer, i_role_id integer)
  OWNER TO postgres;
