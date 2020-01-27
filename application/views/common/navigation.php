<?php $user=$this->pramaan->_check_module_task_auth(true); ?>

<div data-scroll-to-active="true" class="main-menu menu-light menu-fixed menu-shadow menu-bordered"><!-- id="style-3" style="overflow: scroll"-->
  <div class="main-menu-content">
      <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
        <?php if (in_array($user['user_group_id'], dashboard_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
        <?php endif; ?>

        <?php if (in_array($user['user_group_id'], add_edit_view_center_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('centerscontroller/index/');?>"><i class="icon-office"></i><span data-i18n="nav.dash.main" class="menu-title">Centers</span></a></li>
        <?php endif; ?>

        <?php if (in_array($user['user_group_id'], add_edit_view_user_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('userscontroller/index/');?>"><i class="icon-user"></i><span data-i18n="nav.dash.main" class="menu-title">Users</span></a></li>
        <?php endif; ?>

        <?php if (in_array($user['user_group_id'], calender_view_event_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('Events/index/'.date('Y').'/'.date('m').'/'.date('d'));?>"><i class="fa fa-calendar"></i><span data-i18n="nav.dash.main" class="menu-title">Calendar</span></a></li>
        <?php endif; ?>

        <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i  class="icon-ellipsis"></i></li>

        <?php if (in_array($user['user_group_id'], lead_view_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('leads/index/1');?>"><i class="icon-line-chart"></i><span data-i18n="nav.dash.main" class="menu-title">Leads</span></a></li>
        <?php endif; ?>

        <?php if (in_array($user['user_group_id'], lead_view_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('companiescontroller/index');?>"><i class="fa fa-building"></i><span data-i18n="nav.dash.main" class="menu-title">Companies</span></a></li>
        <?php endif; ?>

        <?php if (in_array($user['user_group_id'], lead_view_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('opportunitiescontroller/index');?>"><i class="icon-industry"></i><span data-i18n="nav.dash.main" class="menu-title">Opportunities</span></a></li>
        <?php endif; ?>

        <?php if (in_array($user['user_group_id'], customer_view_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('pramaan/customers');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">Customers</span></a></li>
        <?php endif; ?>

        <?php if (in_array($user['user_group_id'], candidate_view_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('partner/candidates');?>"><i class="fa fa-group"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>
        <?php endif; ?>

        <?php if (in_array($user['user_group_id'], job_view_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_jobs');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Jobs</span></a></li>
        <?php endif; ?>

        <?php if (in_array($user['user_group_id'], job_board_view_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('partner/job_board');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Job Board</span></a></li>
        <?php endif; ?>

        <?php if (in_array($user['user_group_id'], application_tracker_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('partner/application_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker</span></a></li>
        <?php endif; ?>


        <?php if (in_array($user['user_group_id'], address_view_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('pramaan/address_book')?>"><i class="icon-address-book"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Address Book</span></a></li>
        <?php endif; ?>

        <?php if (in_array($user['user_group_id'], batch_view_roles())): ?>
          <li class=" nav-item"><a href="<?php echo base_url('qualificationpackscontroller/csv_form/');?>"><i class="fa fa-certificate"></i><span data-i18n="nav.dash.main" class="menu-title">Batch</span></a></li>
        <?php endif; ?>

        <?php if (in_array($user['user_group_id'], reports_falcon_user())): ?>
                <li class=" nav-item" style="margin-bottom: 50px;"><a href="#"><i class="fa fa-file"></i><span data-i18n="nav.dash.main" class="menu-title">Reports</span></a>
                        <?php if (in_array($user['user_group_id'], admin_only_reports())): ?>
                  <ul><li><a href="<?= base_url('reports?slug=getUserLogInReport');?>"><i class="fa fa-file-o"></i><span data-i18n="nav.dash.main" class="menu-title">User Login Activity Report</span></a></li></ul>
                  <ul><li><a href="<?= base_url('reports?slug=getUsabilityReport');?>"><i class="fa fa-file-o"></i><span data-i18n="nav.dash.main" class="menu-title">User Usability Report</span></a></li></ul>
                  <?php endif; ?>
                  <?php if (in_array($user['user_group_id'], reports())): ?>
                  <ul><li><a href="<?= base_url('reports?slug=getLeadDetailsReport');?>"><i class="fa fa-file-o"></i><span data-i18n="nav.dash.main" class="menu-title">Lead Details Report</span></a></li></ul>
                  <ul><li><a href="<?= base_url('reports?slug=getClientTrackerReport');?>"><i class="fa fa-file-o"></i><span data-i18n="nav.dash.main" class="menu-title">Client Tracker Report</span></a></li></ul>
                  <ul><li><a href="<?= base_url('reports?slug=getJobDetailedReport');?>"><i class="fa fa-file-o"></i><span data-i18n="nav.dash.main" class="menu-title">Job Detailed Report</span></a></li></ul>
                  <?php endif; ?>
                  <ul><li><a href="<?= base_url('reports?slug=getPlacementDetailReport');?>"><i class="fa fa-file-o"></i><span data-i18n="nav.dash.main" class="menu-title">Placement Detail Report</span></a></li></ul>
                  <ul><li><a href="<?= base_url('reports?slug=getSelfEmployedCandidatesReport');?>"><i class="fa fa-file-o"></i><span data-i18n="nav.dash.main" class="menu-title">Self Employed Detail Report</span></a></li></ul>

                            </li>
              <?php endif; ?>


      </ul>
  </div>
</div>
