<style type="text/css">
p.justify
{
    text-align: justify-all;
}
.center-div
{
	position: absolute;
	margin: auto;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	width: 100px;
	height: 100px;
	background-color: #ccc;
	border-radius: 3px;
}
.error{
    color:red;
}
</style>
<div class="content-body" style="overflow-x: hidden !important;">
    <!--<a class="btn btn-success btn-min-width mr-1 mb-1" href="<?php /*echo base_url("pramaan/add_address_book/$parent_id")*/?>" style="margin-left: 50px;"><i class="icon-android-add"></i>Add Contact</a>-->

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Change Password
                </li>
            </ol>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Password</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                <li><a data-action="reload" onclick="reload_table()"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body collapse in" style="font-size:0.90rem;">
                        <div class="card-block card-dashboard">
                          <div class="row justify-content-center">
                                    <div class="col-6">

                                        <?php echo form_open('pramaan/update_user_password', array('id' => 'passwordForm'))?>
                                            <div class="form-group">
                                                <input type="password" name="oldpass" id="oldpass" class="form-control" placeholder="Old Password" value="<?= set_value('oldpass');?>"/>
                                                <?php echo form_error('oldpass', '<div class="error">', '</div>')?>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="newpass" id="newpass" class="form-control" placeholder="New Password" value="<?= set_value('newpass');?>"/>
                                                <?php echo form_error('newpass', '<div class="error">', '</div>')?>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="passconf" id="passconf" class="form-control" placeholder="Confirm Password" value="<?= set_value('passconf');?>"/>
                                                <?php echo form_error('passconf', '<div class="error">', '</div>')?>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success">Change Password</button>
                                            </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- File export table -->
</div>
