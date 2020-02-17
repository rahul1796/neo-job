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
            WHERE 		U.id = i_user_id AND U.is_active=TRUE
            UNION ALL
            SELECT  	A.id AS user_id,
                        A.name AS user_name,
            			URR.name AS user_role,
                        (R.level + 1) AS level,
						A.user_role_id AS user_role_id
            FROM  		neo_user.users AS A
        	LEFT JOIN   neo_user.user_roles AS URR ON URR.id = A.user_role_id
            INNER JOIN 	REC AS R ON R.user_id = A.reporting_manager_id
            WHERE  A.is_active=TRUE
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
