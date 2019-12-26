INSERT INTO neo_user.user_roles (id, name, reporting_manager_role_id) VALUES (17, "Legal User", 2);

INSERT INTO neo_master.lead_statuses (id, name, customer_stage_id, sort_order, value, notification_status, is_active)
            VALUES (20, 'Approved By Legal User', 7, 199, 20, 0, FALSE)

INSERT INTO neo_master.lead_statuses (id, name, customer_stage_id, sort_order, value, notification_status, is_active)
            VALUES (21, 'Rejected By Legal User', 7, 199, 20, 0, FALSE)            
