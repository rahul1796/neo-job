<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function dashboard_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17];
}

function calender_view_event_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];
}

function calender_add_event_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];
}

function lead_add_roles() {
  return [0,1,2,3,4,5,7,8,14];
}

function lead_update_roles() {
  return [0,1,2,3,4,5,7,8,14];
}

function lead_assignment_roles() {
  return [0,1,2];
}

function lead_view_roles() {
  return [0,1,2,3,4,5,7,14,17];
}

function lead_commercial_view_roles() {
  return [0,1,2,3,4,5,7,8,14,17];
}

function lead_commercial_update_roles() {
    return [0,1,2,3,4,5,7,8,14];
}

function lead_commercial_approve_roles() {
    return [0,1,17];
}

function lead_status_update_roles() {
  return [0,1,2,3,4,5,7,8,14];
}

function customer_view_roles() {
  return [0,1,2,3,4,5,7,8,14,15];
}

function customer_commercial_view_roles() {
  return [0,1,2,3,4,5,7,8,14];
}

function customer_spoc_view_roles() {
  return [0,1,2,3,4,5,7,8,14];
}

function customer_job_view_roles() {
  return [0,1,2,3,4,5,7,8,14,15];
}

function customer_update_roles() {
  return [0,1,2,3,4,5,7,8,14];
}

function candidate_add_roles() {
  return [0,1,2,3,4,6,7,9,10,11,12,13,14];
}

function candidate_update_roles() {
  return [0,1,2,3,4,6,7,9,10,11,12,13,14];
}

function candidate_view_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];
}

function candidate_bulk_upload_roles() {
  return [0,1,2,3,4,6,7,9,10,11,12,13,14];
}

function candidate_view_profile_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];
}

function candidate_add_profile_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];
}

function candidate_edit_profile_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];
}

function job_add_roles() {
  return [0,1,2,3,4,5,6,7,8,14];
}

function job_edit_roles() {
  return [0,1,2,3,4,5,6,7,8,14];
}

function job_view_roles() {
  return [0,1,2,3,4,5,6,7,8,14];
}

function job_status_change_roles() {
  return [0,1,2,3,4,5,6,7,8];
}
function job_board_view_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];
}

function job_board_recommanded_roles() {
  return [0,1,2,3,5,6,7,8,9,10,11,12,13,14];
}

function job_board_all_candidates_roles() {
  return [0,1,2,3,5,6,7,8,9,10,11,12,13,14];
}

function job_board_applied_roles() {
  return [0,1,2,3,5,6,7,8,9,10,11,12,13,14];
}

function job_board_joined_roles() {
  return [0,1,2,3,5,6,7,8,9,10,11,12,13,14];
}

function job_board_cloned_roles() {
  return [0,1,2,3,4,5,6,7,8,10,12,13,14];
}

function job_board_candidate_applied_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];
}

function job_board_candidate_status_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];
}

function application_tracker_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];

}

function application_tracker_qp_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];

}

function application_tracker_customer_roles() {
  return [0,1,2,3,4,5,6,7,8,11,14];
}

function application_tracker_region_roles() {
  return [0,1,2,3,4,5,6,7,8,11,14];
}

function address_view_roles() {
  return [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];
}

function batch_view_roles() {
  return [0,1,2,3,4,8,9,10,12,13];
}

function bulk_upload_batch_roles() {
  return [];
}

function bulk_upload_clcs_roles() {
  return [];
}

function add_edit_view_user_roles() {
  return [0,1];
}

function add_edit_view_center_roles() {
  return [0,1];
}

function center_active_deactive_roles() {
  return [0,1,2,3,8,4,1,2];
}

function candidate_employments_delete_edit_roles() {
  return [0,1];
}

function reports() {
  return [0,1,2,3,5,6,7,8,9,10,11,14,15];
}

function admin_only_reports() {
  return [0,1,15];
}
