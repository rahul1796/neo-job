<style type="text/css">
</style>

<?php
$user=$this->pramaan->_check_module_task_auth(true);
?>


<!-- main menu-->
<div data-scroll-to-active="true" class="main-menu menu-light menu-fixed menu-shadow menu-bordered"><!-- id="style-3" style="overflow: scroll"-->
    <!-- main menu header-->
    <!-- / main menu header-->
    <!-- main menu content-->
    <?php
    if($user['user_group_id']==1)       //ADMINISTRATOR
    {

    ?>
    <div class="main-menu-content">
        <ul id="nav main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
            <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
            <li class=" nav-item"><a href="#"><i class="icon-sitemap"></i><span data-i18n="nav.templates.main" class="menu-title">Departments</span></a>
                <ul class="menu-content">
                    <li><a href="#" data-i18n="nav.templates.vert.main" class="menu-item">Sourcing</a>
                        <ul class="menu-content">
                            <li><a href="<?php echo base_url('pramaan/sourcing_heads')?>">Heads</a></li>
                            <li><a href="<?php echo base_url('pramaan/sourcing_admins_all')?>">Admins</a></li>
                            <li><a href="#" data-i18n="nav.page_layouts.3_columns.main" class="menu-item">Team</a>
                                <ul class="menu-content">
                                    <li class="dropdown-submenu">
                                        <a href="<?php echo base_url('pramaan/regional_managers')?>">Regional Managers</a>
                                        <a href="<?php echo base_url('pramaan/show_state_managers')?>">State Managers</a>
                                        <a href="<?php echo base_url('pramaan/show_district_coordinators')?>">District Coordinators</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#" data-i18n="nav.templates.horz.main" class="menu-item">Recruitment</a>
                        <ul class="menu-content">
                            <li><a href="<?php echo base_url('pramaan/bd_heads')?>">Heads</a></li>
                            <li><a href="<?php echo base_url('pramaan/bd_admins_all')?>">Admins</a></li>
                            <li><a href="#" data-i18n="nav.page_layouts.3_columns.main" class="menu-item">Team</a>
                                <ul class="menu-content">
                                    <li class="dropdown-submenu">
                                        <a href="<?php echo base_url('pramaan/show_bd_managers')?>">Regional Managers</a>
                                        <a href="<?php echo base_url('pramaan/show_bd_coordinators')?>">District Coordinators</a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </li>
                    <li><a href="#" data-i18n="nav.templates.horz.main" class="menu-item">Placement</a>
                        <ul class="menu-content">
                            <li><a href="<?php echo base_url('pramaan/rs_heads')?>">Heads</a></li>
                            <li><a href="<?php echo base_url('pramaan/rs_admins')?>">Admins</a></li>
                            <li><a href="#" data-i18n="nav.page_layouts.3_columns.main" class="menu-item">Team</a>
                                <ul class="menu-content">
                                    <li class="dropdown-submenu">
                                        <a href="<?php echo base_url('pramaan/rs_vertical_managers')?>">Vertical Managers</a>
                                        <a href="<?php echo base_url('pramaan/rs_sector_managers')?>">Sector Managers</a>
                                        <a href="<?php echo base_url('pramaan/rs_coordinators')?>">Coordinators</a>
                                        <a href="<?php echo base_url('pramaan/rs_executives')?>">Executives</a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </li>

                </ul>
            </li>
            <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i>
            </li>
            <li class=" nav-item"><a href="<?php echo base_url('pramaan/rs_sectors')?>"><i class="icon-stack-2"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Sectors</span></a></li>
            <li class=" nav-item"><a href="<?php echo base_url('pramaan/qualification_pack')?>"><i class="icon-navicon2"></i><span data-i18n="nav.navbars.main" class="menu-title">Qualification Packs</span></a></li>
            <li class=" nav-item"><a href="<?php echo base_url('pramaan/application_tracker')?>"><i class="icon-android-list"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Application Tracker (QP)</span></a></li>
            <li class=" nav-item"><a href="<?php echo base_url('partner/employers_tracker')?>"><i class="icon-android-list"></i><span data-i18n="nav.navbars.main" class="menu-title">Application Tracker (Employer)</span></a></li>
            <li class=" nav-item"> <a href="<?php echo base_url('pramaan/pramaan_candidates')?>"><i class="icon-ios-people"></i><span data-i18n="nav.vertical_nav.main" class="menu-title">Candidates</span></a> </li>
            <li class=" nav-item"><a href="<?php echo base_url('employers')?>"><i class="icon-android-people"></i><span data-i18n="nav.navbars.main" class="menu-title">Customers</span></a></li>
            <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_jobs')?>"><i class="icon-eye"></i><span data-i18n="nav.vertical_nav.main" class="menu-title">Jobs</span></a> </li>

        </ul>
    </div>

        <?php
    }

    ?>

    <?php
    if($user['user_group_id']==9)       //SOURCING ADMIN
    {
        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/regions');?>"><i class="icon-map"></i><span data-i18n="nav.dash.main" class="menu-title">Regions</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/states');?>"><i class="icon-android-map"></i><span data-i18n="nav.dash.main" class="menu-title">States</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/districts');?>"><i class="icon-map"></i><span data-i18n="nav.dash.main" class="menu-title">Districts</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/regional_managers');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">Team</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/application_tracker');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_candidates');?>"><i class="icon-ios-people"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>
            </ul>
        </div>
        <?php
    }

    ?>

    <?php
    if($user['user_group_id']==10)       //SOURCING HEAD
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <!-- <li class=" nav-item"><a href="<?php echo base_url('pramaan/sourcing_admins_all');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">Sourcing Admin</span></a></li> -->
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/regional_managers');?>"><i class="icon-ios-people"></i><span data-i18n="nav.dash.main" class="menu-title">Regional Manager</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/show_state_managers');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">State Manager</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/show_district_coordinators');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">District Coordinator</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/qualification_pack');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Qualification Packs</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/application_tracker');?>"><i class="icon-ios-people"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_candidates');?>"><i class="icon-android-map"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>
            </ul>
        </div>
        <?php
    }

    ?>

    <?php
    if($user['user_group_id']==6)       //SOURCING STATE MANAGER
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/sourcing_coordinators');?>"><i class="icon-android-person"></i><span data-i18n="nav.dash.main" class="menu-title">Sourcing Coordinator</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/qualification_pack');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Qualification Packs</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_candidates');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>
            </ul>
        </div>
        <?php
    }

    ?>

    <?php
    if($user['user_group_id']==4)       //SOURCING STATE COORDINATOR
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/sourcing_partner');?>"><i class="icon-android-person"></i><span data-i18n="nav.dash.main" class="menu-title">Sourcing Partner</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/qualification_pack');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Qualification Packs</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_candidates');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>
            </ul>
        </div>
        <?php
    }

    ?>

    <?php
    if($user['user_group_id']==22)       //DISTRICT COORDINATOR
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/sourcing_partner');?>"><i class="icon-android-person"></i><span data-i18n="nav.dash.main" class="menu-title">Sourcing Partner</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/qualification_pack');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Qualification packs</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/application_tracker');?>"><i class="icon-map"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_candidates');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>
            </ul>
        </div>
        <?php
    }
    ?>


    <?php
    if($user['user_group_id']==3)       //SOURCING PARTNER (ORGANIZATION)
    {
        if ($user['partner_type_id'] == 2) {
            ?>
                <div class="main-menu-content">
                    <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                        <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                        <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                        <li class=" nav-item"><a href="<?php echo base_url('partner/center');?>"><i class="icon-android-locate"></i><span data-i18n="nav.dash.main" class="menu-title">Centers</span></a></li>
                        <li class=" nav-item"><a href="<?php echo base_url('partner/associates');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">Center Managers</span></a></li>
                    </ul>
                </div>
            <?php
        }
        else if ($user['partner_type_id'] == 1) {
            ?>
            <div class="main-menu-content">
                <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                    <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                    <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                    <li class=" nav-item"><a href="<?php echo base_url('partner/candidates');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>
                    <li class=" nav-item"><a href="<?php echo base_url('partner/job_board');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Job Board</span></a></li>
                    <li class=" nav-item"><a href="<?php echo base_url('partner/application_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker</span></a></li>
                </ul>
            </div>
            <?php
        }
    }

    ?>


    <?php
    if($user['user_group_id']==5)       ////SOURCING ASSOCIATE
    {
        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('Events/index/'.date('Y').'/'.date('m').'/'.date('d'));?>"><i class="fa fa-calendar"></i><span data-i18n="nav.dash.main" class="menu-title">Calendar</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/candidates');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/job_board');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Job Board</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/application_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker (QP)</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/address_book')?>"><i class="icon-address-book"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Address Book</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('qualificationpackscontroller/csv_form/');?>"><i class="fa fa-certificate"></i><span data-i18n="nav.dash.main" class="menu-title">Batch</span></a></li>
            </ul>
        </div>
        <?php
    }
    ?>

    <?php
    if($user['user_group_id']==2)       ////EMPLOYER
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('employer/employer_jobs');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Jobs</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('employer/application_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker</span></a></li>
            </ul>
        </div>
        <?php
    }
    ?>

    <?php
    if($user['user_group_id']==13)       ////BD ADMIN
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/bd_regional_manager');?>"><i class="icon-ios-person"></i><span data-i18n="nav.dash.main" class="menu-title">Regional Managers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/employers');?>"><i class="icon-android-person"></i><span data-i18n="nav.dash.main" class="menu-title">Customers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/employers_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker (Employer)</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_jobs');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Jobs</span></a></li>
            </ul>
        </div>
        <?php
    }
    ?>

    <?php
    if($user['user_group_id']==12)       ////BD HEAD
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/show_bd_managers');?>"><i class="icon-ios-person"></i><span data-i18n="nav.dash.main" class="menu-title">Regional Managers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/show_bd_admins');?>"><i class="icon-ios-people"></i><span data-i18n="nav.dash.main" class="menu-title">Admins</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/show_bd_coordinators');?>"><i class="icon-android-person"></i><span data-i18n="nav.dash.main" class="menu-title">Coordinators</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/employers');?>"><i class="icon-ios-people"></i><span data-i18n="nav.dash.main" class="menu-title">Customers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/employers_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker (Employer)</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_jobs');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Jobs</span></a></li>
            </ul>
        </div>
        <?php
    }
    ?>


    <?php
    if($user['user_group_id']==11)       ////BD MANAGERS
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/bd_coordinators');?>"><i class="icon-android-person"></i><span data-i18n="nav.dash.main" class="menu-title">Coordinators</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/employers');?>"><i class="icon-ios-people"></i><span data-i18n="nav.dash.main" class="menu-title">All Customers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/employers_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker(Customers)</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_jobs');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Jobs</span></a></li>
            </ul>
        </div>
        <?php
    }
    ?>

    <?php
    if($user['user_group_id']==8)       ////BD COORDINATOR
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/bd_executives');?>"><i class="icon-android-person"></i><span data-i18n="nav.dash.main" class="menu-title">Executives</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/employers');?>"><i class="icon-ios-people"></i><span data-i18n="nav.dash.main" class="menu-title">Customers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/employers_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker(Customers)</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_jobs');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Jobs</span></a></li>
            </ul>
        </div>
        <?php
    }
    ?>

    <?php
    if($user['user_group_id']==18)  //BD EXECUTIVE equivalant to recruitment
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('Events/index/'.date('Y').'/'.date('m').'/'.date('d'));?>"><i class="fa fa-calendar"></i><span data-i18n="nav.dash.main" class="menu-title">Calendar</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i  class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('leads/index/1');?>"><i class="icon-line-chart"></i><span data-i18n="nav.dash.main" class="menu-title">Leads</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/customers');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">Customers</span></a></li>
                 <li class=" nav-item"><a href="<?php echo base_url('partner/candidates');?>"><i class="fa fa-group"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_jobs');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Jobs</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/job_board');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Job Board</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/application_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker (QP)</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/employers_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker (Customer)</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/address_book')?>"><i class="icon-address-book"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Address Book</span></a></li>
<!--                <li class=" nav-item"><a href="<?php echo base_url('master/index')?>"><i class="fa fa-map-pin"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Location</span></a></li>-->
            </ul>
        </div>
        <?php
    }
    ?>
    
    <?php
    if($user['user_group_id']==7)  //SUPER ADMIN
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('Events/index/'.date('Y').'/'.date('m').'/'.date('d'));?>"><i class="fa fa-calendar"></i><span data-i18n="nav.dash.main" class="menu-title">Calendar</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i  class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('leads/index/1');?>"><i class="icon-line-chart"></i><span data-i18n="nav.dash.main" class="menu-title">Leads</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/customers');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">Customers</span></a></li>
                 <li class=" nav-item"><a href="<?php echo base_url('partner/candidates');?>"><i class="fa fa-group"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_jobs');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Jobs</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/job_board');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Job Board</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/application_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker (QP)</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/employers_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker (Customer)</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/address_book')?>"><i class="icon-address-book"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Address Book</span></a></li>
<!--                <li class=" nav-item"><a href="<?php echo base_url('master/index')?>"><i class="fa fa-map-pin"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Location</span></a></li>-->
                 <li class=" nav-item"><a href="<?php echo base_url('qualificationpackscontroller/csv_form/');?>"><i class="fa fa-certificate"></i><span data-i18n="nav.dash.main" class="menu-title">Batch</span></a></li>
            </ul>
        </div>
        <?php
    }
    ?>
    
    
    
    <?php
    if($user['user_group_id']==14)  //RS ADMIN
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/rs_verticals');?>"><i class="icon-map"></i><span data-i18n="nav.dash.main" class="menu-title">Verticals</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/rs_sectors');?>"><i class="icon-map"></i><span data-i18n="nav.dash.main" class="menu-title">Sectors</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/rs_vertical_managers');?>"><i class="icon-ios-person"></i><span data-i18n="nav.dash.main" class="menu-title">Vertical Managers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/rs_sector_managers');?>"><i class="icon-person"></i><span data-i18n="nav.dash.main" class="menu-title">Sector Managers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/assigned_employers');?>"><i class="icon-person-stalker"></i><span data-i18n="nav.dash.main" class="menu-title">Customers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/job_assignment');?>"><i class="icon-ios-redo"></i><span data-i18n="nav.dash.main" class="menu-title">Job Assignment</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/assigned_jobs');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Jobs</span></a></li>
            </ul>
        </div>
        <?php
    }
    ?>

    <?php
    if($user['user_group_id']==15)  //RS HEAD
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>

                        <li><a href="#" data-i18n="nav.templates.vert.main" class="menu-item">Team</a>
                            <ul class="menu-content">
                                <li><a href="<?php echo base_url('pramaan/rs_admins')?>">Placement Admin</a></li>
                                <li><a href="<?php echo base_url('pramaan/rs_vertical_managers')?>">Vertical Managers</a></li>
                                <li><a href="<?php echo base_url('pramaan/rs_sector_managers')?>">Sector Manager</a></li>
                                <li><a href="<?php echo base_url('pramaan/rs_coordinators')?>">Coordinator</a></li>
                                <li><a href="<?php echo base_url('pramaan/rs_executives')?>">Executives</a></li>

                            </ul>
                        </li>

                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i>
                </li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/assigned_employers')?>"><i class="icon-stack-2"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Customers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/job_assignment')?>"><i class="icon-navicon2"></i><span data-i18n="nav.navbars.main" class="menu-title">Job Assignment</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/assigned_jobs')?>"><i class="icon-android-list"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Jobs</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/candidate_list')?>"><i class="icon-android-list"></i><span data-i18n="nav.navbars.main" class="menu-title">Candidates</span></a></li>

            </ul>
        </div>

        <?php
    }

    ?>


    <?php
    if($user['user_group_id']==20)  //SOURCING REGIONAL MANAGER
    {
        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/state_managers');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">State Managers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/application_tracker');?>"><i class="icon-android-options"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_candidates');?>"><i class="icon-android-person"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>

            </ul>
        </div>
        <?php
    }
    ?>

    <?php
    if($user['user_group_id']==16)  //RS VERTICAL MANAGER
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" nav-item"><a href="#"><i class="icon-note"></i><span data-i18n="nav.templates.main" class="menu-title">Team</span></a>
                    <ul class="menu-content">
                        <li><a href="<?php echo base_url('pramaan/rs_sector_managers')?>">Sector Managers</a></li>
                        <li><a href="<?php echo base_url('pramaan/rs_coordinators')?>">Coordinators</a></li>
                        <li><a href="<?php echo base_url('pramaan/rs_executives')?>">Executives</a></li>
                    </ul>
                </li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i>
                </li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/assigned_employers')?>"><i class="icon-ios-personadd"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Customers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/assigned_jobs')?>"><i class="icon-android-list"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Jobs</span></a></li>


            </ul>
        </div>
        <?php
    }
    ?>

    <?php
    if($user['user_group_id']==24)  //RS SECTOR MANAGER
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" nav-item"><a href="#"><i class="icon-note"></i><span data-i18n="nav.templates.main" class="menu-title">Team</span></a>
                    <ul class="menu-content">
						<li><a href="<?php echo base_url('pramaan/rs_coordinators')?>">Coordinator</a></li>
						<li><a href="<?php echo base_url('pramaan/rs_executives')?>">Executives</a></li>

					</ul>
                </li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i>
                </li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/assigned_employers')?>"><i class="icon-ios-personadd"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Customers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/assigned_jobs')?>"><i class="icon-android-list"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Jobs</span></a></li>


            </ul>
        </div>
        <?php
    }
    ?>

    <?php
    if($user['user_group_id']==17)  //RS COORDINATOR
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" nav-item"><a href="#"><i class="icon-note"></i><span data-i18n="nav.templates.main" class="menu-title">Team</span></a>

                        <ul class="menu-content">
                            <li><a href="<?php echo base_url('pramaan/rs_executives')?>">Executives</a></li>

                        </ul>

                </li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i>
                </li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/assigned_employers')?>"><i class="icon-ios-personadd"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Customers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/assigned_jobs')?>"><i class="icon-android-list"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Jobs</span></a></li>


            </ul>
        </div>
        <?php
    }
    ?>


    <?php
    if($user['user_group_id']==19)  //RS EXECUTIVE
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('Events/index/'.date('Y').'/'.date('m').'/'.date('d'));?>"><i class="fa fa-calendar"></i><span data-i18n="nav.dash.main" class="menu-title">Calendar</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/candidates');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_jobs');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Jobs</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/job_board');?>"><i class="icon-android-list"></i><span data-i18n="nav.dash.main" class="menu-title">Job Board</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/application_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker (QP)</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/employers_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker (Customer)</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/job_applicants')?>"><i class="fa fa-briefcase"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Job Applicants</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/address_book')?>"><i class="icon-address-book"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Address Book</span></a></li>
<!--                <li class=" nav-item"><a href="<?php echo base_url('master/index')?>"><i class="fa fa-map-pin"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Location</span></a></li>-->
            </ul>
        </div>
        <?php
    }
    ?>


    <?php
    if($user['user_group_id']==21)  //STATE MANAGER
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/district_coordinators');?>"><i class="icon-android-people"></i><span data-i18n="nav.dash.main" class="menu-title">District Coordinators</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/application_tracker');?>"><i class="icon-android-navigate"></i><span data-i18n="nav.dash.main" class="menu-title">Application Tracker</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/pramaan_candidates');?>"><i class="icon-android-person"></i><span data-i18n="nav.dash.main" class="menu-title">Candidates</span></a></li>

            </ul>
        </div>
        <?php
    }
    ?>

    <?php
    if($user['user_group_id']==23)  //SPOC ADMIN
    {

        ?>
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="<?php echo base_url('pramaan/dashboard/');?>"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a></li>
                <li class=" navigation-header"><span data-i18n="nav.category.layouts">Content</span><i data-toggle="tooltip" data-placement="right" data-original-title="Layouts" class="icon-ellipsis icon-ellipsis"></i></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/spoc_center');?>"><i class="icon-android-locate"></i><span data-i18n="nav.dash.main" class="menu-title">Centers</span></a></li>
                <li class=" nav-item"><a href="<?php echo base_url('partner/spoc_associates');?>"><i class="icon-android-contact"></i><span data-i18n="nav.dash.main" class="menu-title">Center Managers</span></a></li>

            </ul>
        </div>
        <?php
    }
    ?>


    <!-- /main menu content-->

</div>
<!-- / main menu-->
