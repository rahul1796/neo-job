<style>
	.alert-success
	{
		position: absolute;
		top: 50%;
		width: 50%;
		margin-left:25%;
		margin-right:25%;
		padding: 10px 25px;
		font-weight: 120%;
		letter-spacing: 1.5px;
		z-index: 9999;
		text-align: center;
	}
</style>
<?php
/*if($this->session->flashdata('notify_msg'))
{
	echo '<div class="alert alert-success" role="alert">'.$this->session->flashdata('notify_msg').'</div>';
}
*/?>
<main class="content-wrapper">

	<?php
	switch($page)
	{
		case 'home_page':
			$this->load->view(PRAMAAN_VIEW_PAGES.'home_page');
			break;

		case 'change_password':
			$this->load->view(PRAMAAN_VIEW_PAGES.'change_password');
			break;

		case 'login_page':
			$this->load->view(PRAMAAN_VIEW_PAGES.'login');
			break;

		case 'dashboard':
			$this->load->view(PRAMAAN_VIEW_PAGES.'dashboard', $data);
			break;

		case 'sourcing_admins':
			$this->load->view(PRAMAAN_VIEW_PAGES.'sourcing_admins');
			break;

		case 'add_sourcing_admin':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_sourcing_admin');
			break;

		case 'sourcing_heads':
			$this->load->view(PRAMAAN_VIEW_PAGES.'sourcing_heads');
			break;

		case 'add_sourcing_head':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_sourcing_head');
			break;

		case 'sourcing_managers':
			$this->load->view(PRAMAAN_VIEW_PAGES.'sourcing_managers');
			break;

		case 'add_sourcing_manager':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_sourcing_manager');
			break;

		case 'add_sourcing_coordinator':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_sourcing_coordinator');
			break;

		case 'add_recruitment_partner':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_recruitment_partner');
			break;

		case 'recruitment_partner':
			$this->load->view(PRAMAAN_VIEW_PAGES.'recruitment_partner');
			break;



		case 'add_sourcing_partner':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_sourcing_partner');
			break;

		case 'sourcing_coordinators':
			$this->load->view(PRAMAAN_VIEW_PAGES.'sourcing_coordinators');
			break;
		case 'bd_admins':
			$this->load->view(PRAMAAN_VIEW_PAGES.'bd_admins');
			break;
		case 'bd_heads':
			$this->load->view(PRAMAAN_VIEW_PAGES.'bd_heads');
			break;

		case 'bd_managers':
			$this->load->view(PRAMAAN_VIEW_PAGES.'bd_managers');
			break;

		case 'bd_coordinators':
			$this->load->view(PRAMAAN_VIEW_PAGES.'bd_coordinators');
			break;

		case 'bd_executives':
			$this->load->view(PRAMAAN_VIEW_PAGES.'bd_executives');
			break;

		case 'rs_admins':
			$this->load->view(PRAMAAN_VIEW_PAGES.'rs_admins');
			break;

		case 'rs_heads':
			$this->load->view(PRAMAAN_VIEW_PAGES.'rs_heads');
			break;

		case 'rs_managers':
			$this->load->view(PRAMAAN_VIEW_PAGES.'rs_managers');
			break;

		case 'rs_coordinators':
			$this->load->view(PRAMAAN_VIEW_PAGES.'rs_coordinators');
			break;

		case 'rs_executives':
			$this->load->view(PRAMAAN_VIEW_PAGES.'rs_executives');
			break;

		case 'assigned_employers':
			$this->load->view(PRAMAAN_VIEW_PAGES.'assigned_employers');
			break;

		case 'assigned_jobs':
			$this->load->view(PRAMAAN_VIEW_PAGES.'assigned_jobs');
			break;

		case 'sourcing_partner':
			$this->load->view(PRAMAAN_VIEW_PAGES.'sourcing_partner');
			break;

		case 'qualification_pack':
			$this->load->view(PRAMAAN_VIEW_PAGES.'qualification_pack');
			break;
		/*case 'dashboard':
			$this->load->view(_VIEW_PAGES.'dashboard');
			break;*/
		case 'pramaan_catagory_list':
			$this->load->view(PRAMAAN_VIEW_PAGES.'category_list');
			break;

		case 'add_pramaan_source_feed':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_pramaan_source_feed');
			break;

		case 'pramaan_candidates':
			$this->load->view(PRAMAAN_VIEW_PAGES.'pramaan_candidates');
			break;
		case 'pramaan_employers':
			$this->load->view(PRAMAAN_VIEW_PAGES.'pramaan_employers');
			break;
		case 'job_assignment':
			$this->load->view(PRAMAAN_VIEW_PAGES.'job_assignment');
			break;
		case 'executive_assignment':
			$this->load->view(PRAMAAN_VIEW_PAGES.'executive_assignment');
			break;
		case 'pramaan_jobs':
			$this->load->view(PRAMAAN_VIEW_PAGES.'pramaan_jobs');
			break;
		case 'regions':
			$this->load->view(PRAMAAN_VIEW_PAGES.'regions');
			break;
		case 'add_region':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_region');
			break;
		case 'states':
			$this->load->view(PRAMAAN_VIEW_PAGES.'states');
			break;
		case 'add_state':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_state');
			break;
		case 'districts':
			$this->load->view(PRAMAAN_VIEW_PAGES.'districts');
			break;
		case 'add_district':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_district');
			break;
		case 'regional_managers':
			$this->load->view(PRAMAAN_VIEW_PAGES.'regional_managers');
			break;
		case 'add_regional_manager':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_regional_manager');
			break;
		case 'state_managers':
			$this->load->view(PRAMAAN_VIEW_PAGES.'state_managers');
			break;
		case 'add_state_manager':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_state_manager');
			break;


		case 'sourcing_admins':
			$this->load->view(PRAMAAN_VIEW_PAGES.'sourcing_admins');
			break;

		case 'add_sourcing_admin':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_sourcing_admin');
			break;


		case 'sourcing_heads':
			$this->load->view(PRAMAAN_VIEW_PAGES.'sourcing_heads');
			break;
		case 'add_country':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_country');
			break;
		case 'add_new_country':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_new_country');
			break;

		case 'add_sourcing_head':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_sourcing_head');
			break;
		case 'edit_sourcing_admin':
			$this->load->view(PRAMAAN_VIEW_PAGES.'edit_sourcing_admin');
			break;
		case 'application_tracker':
			$this->load->view(PRAMAAN_VIEW_PAGES.'application_tracker');
			break;
		case 'edit_sourcing_head':
			$this->load->view(PRAMAAN_VIEW_PAGES.'edit_sourcing_head');
			break;
		case 'edit_district_coordinator':
			$this->load->view(PRAMAAN_VIEW_PAGES.'edit_district_coordinator');
			break;
		case 'edit_qualification_pack':
			$this->load->view(PRAMAAN_VIEW_PAGES.'edit_qualification_pack');
			break;
		case 'state_managers_view':
			$this->load->view(PRAMAAN_VIEW_PAGES.'state_managers_view');
			break;
		case 'regional_managers_view':
			$this->load->view(PRAMAAN_VIEW_PAGES.'regional_managers_view');
			break;
		case 'district_coordinators_view':
			$this->load->view(PRAMAAN_VIEW_PAGES.'district_coordinators_view');
			break;
		case 'district_coordinators':
			$this->load->view(PRAMAAN_VIEW_PAGES.'district_coordinators');
			break;
		case 'add_district_coordinators':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_district_coordinators');
			break;
		case 'add_sourcing_admin_superadmin':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_sourcing_admin_superadmin');
			break;
		case 'sourcing_admins_view':
			$this->load->view(PRAMAAN_VIEW_PAGES.'sourcing_admins_view');
			break;
		//sourcing_admins_all
		case 'sourcing_admins_all':
			$this->load->view(PRAMAAN_VIEW_PAGES.'sourcing_admins_all');
			break;
		//regional_managers_view_mode
		case 'regional_managers_view_mode':
			$this->load->view(PRAMAAN_VIEW_PAGES.'regional_managers_view_mode');
			break;
		case 'state_managers_view_mode':
			$this->load->view(PRAMAAN_VIEW_PAGES.'state_managers_view_mode');
			break;
		case 'district_managers_view_mode':
			$this->load->view(PRAMAAN_VIEW_PAGES.'district_managers_view_mode');
			break;

		case 'edit_bd_head':
			$this->load->view(PRAMAAN_VIEW_PAGES.'edit_bd_head');
			break;

		case 'rs_verticals':
			$this->load->view(PRAMAAN_VIEW_PAGES.'rs_verticals');
			break;

		case 'add_rs_vertical':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_rs_vertical');
			break;

		case 'rs_sectors':
			$this->load->view(PRAMAAN_VIEW_PAGES.'rs_sectors');
			break;

		case 'add_rs_sector':
			$this->load->view(PRAMAAN_VIEW_PAGES.'add_rs_sector');
			break;


		case 'address_book':
			$this->load->view(PRAMAAN_VIEW_PAGES.'address_book');
			break;

		case 'candidate_joined_customerwise':
			$this->load->view(PRAMAAN_VIEW_PAGES.'candidate_joined_customerwise');
			break;

		case 'candidate_joined_jobwise':
			$this->load->view(PRAMAAN_VIEW_PAGES.'candidate_joined_jobwise');
			break;

		case 'customers':
			$this->load->view(PRAMAAN_VIEW_PAGES.'customers');
			break;

		case 'job_applicants':
			$this->load->view(PRAMAAN_VIEW_PAGES.'job_applicants');
			break;
                    
                case 'application_tracker_region_state_center':
                    $this->load->view(PRAMAAN_VIEW_PAGES.'application_tracker_region_state_center');
                    break;
                
                case 'reports':
			$this->load->view(PRAMAAN_VIEW_PAGES.'reports');
			break;

		default:
			$this->load->view($module.'/'.$page);
	}
	?>
</main>
<!--<script>
	/** After windod Load Flash alert for codeigniter setflash*/
	$(document).ready(function()
	{
		set_alert();
	});

	// Flash Alert for ajax success
	function flashAlert(msg_info)
	{
		$('.content-wrapper').before('<div class="alert alert-success" role="alert">'+msg_info+'</div>');
		set_alert();
	}

	function set_alert()
	{
		window.setTimeout(function()
		{
			$(".alert-success").slideDown(350, 0).slideUp(350, function()
			{
				$(this).remove();
			});
		}, 3000);
	}
</script>-->
