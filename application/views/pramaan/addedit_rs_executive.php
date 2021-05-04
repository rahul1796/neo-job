<style type="text/css">
	/**
	 * @author  George Martin <george.s@navriti.com>
	 * @desc  	Add / Edit Recruitment Support Executive
	 * @date  	March 2017
	*/
	select.input-sm
	{
	 	line-height: 10px;
	}
	.error_label, .validmark{color: red;}
</style>
<?php
	$options_coordinators=array(''=>'-Select Coordinator-');
	foreach ($rs_coordinators as $row) 
	{
		$options_coordinators[$row['id']]=$row['name'];
	}
?>
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
	<div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("pramaan/rs_executives","RS Executives");?></li>
				<li class="breadcrumb-item active"><?= (($id > 0) ? "Edit " : "Add ") . $title ?></li>
			</ol>
		</div>
	</div>
	<section id="basic-form-layouts">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="bordered-layout-basic-form"><?= (($id > 0) ? "Edit " : "Add ") . $title ?> Info</h4>
						<a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
						 <div class="heading-elements">
                             <ul class="list-inline mb-0">
                                 <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                 <li><a data-action="reload"><i class="icon-reload"></i></a></li>
                                 <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                             </ul>
                         </div>
					</div>
					<div class="card-body collapse in">
						<div class="card-block">
							<form class="form form-horizontal form-bordered" id="frmEntry" method="post">

								<div class="form-body">
									<?php
									if($user_group_id==24)
									{?>
										<div class="form-group row">
											<label for="coordinator" class="col-sm-3 label-control">Co-ordinator Name<span class='validmark'>*</span></label>
											<div class="col-sm-9">
												<?php echo form_dropdown('coordinator',$options_coordinators,'','id="coordinator" class="form-control"') ?>
												<span class="error_label"></span>
											</div>
										</div>
									<?php }?>
									<div class="form-group row">
										<label for="txtRsExecutiveName" class="col-sm-3 label-control">Name<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="hidden" class="form-control" id="hidRsExecutiveId" name="id" value="<?= isset($id) ? $id : "" ?>"/>
											<input type="text" class="form-control" id="txtRsExecutiveName" name="txtRsExecutiveName" value="<?= isset($ResponseData[0]['rs_executive_name']) ? $ResponseData[0]['rs_executive_name'] : "" ?>" placeholder="Enter RS Executive Name"/>
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label for="txtRsExecutivePhone" class="col-sm-3 label-control">Phone<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="txtRsExecutivePhone" name="txtRsExecutivePhone" maxlength="<?= PHONE_MAX?>" value="<?= isset($ResponseData[0]['rs_executive_phone']) ? $ResponseData[0]['rs_executive_phone'] : "" ?>" placeholder="Enter RS Executive Phone"/>
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label for="txtRsExecutiveEmail" class="col-sm-3 label-control">Email<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="txtRsExecutiveEmail" name="txtRsExecutiveEmail" maxlength="<?= EMAIL_MAX?>" value="<?= isset($ResponseData[0]['rs_executive_email']) ? $ResponseData[0]['rs_executive_email'] : "" ?>" placeholder="Enter RS Executive Email"/>
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row" style="display:<?= (($id > 0) ? "none" : "block")?>;">
										<label for="txtPassword" class="col-sm-3 label-control">Password<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="password" class="form-control" id="txtPassword" name="txtPassword" maxlength="<?= PASSWORD_MAX?>" placeholder="Enter Password">
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row" style="display:<?= (($id > 0) ? "none" : "block")?>;">
										<label for="txtRetypePassword" class="col-sm-3 label-control">Retype Password<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="password" class="form-control" id="txtRetypePassword" name="txtRetypePassword" maxlength="<?= PASSWORD_MAX?>" placeholder="Retype Password">
											<span class="error_label"></span>
										</div>
									</div>

								</div>

								<div class="form-actions">
									<button type="reset" class="btn btn-warning mr-1"><i class="icon-cross2"></i> Reset</button>
									<button type="submit" class="btn btn-primary"  name="submit"><i class="icon-check2"></i>Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>
	<!-- // Basic form layout section end -->
</div>

<script>
$(document).ready(function()
{
	$("#frmEntry").validate({
	 	ignore: ":hidden",
		errorPlacement: function(error, element) 
		{
			 $(element).closest('.form-group').find('.error_label').html(error);
		},
		messages: 
		{
			coordinator:
	 		{
	 			required: "Please Select coordinator",
	 		},
			txtRsExecutiveName:
			{
				required: "Please enter name",
			},
			txtRsExecutivePhone:
			{
				required: "Please enter phone",
			},
			txtRsExecutiveEmail:
			{
				required: "Please enter your email",
			},
			txtPassword:
			{
				required: "Please enter password",
			},
			txtRetypePassword:
			{
				required: "Retype password is not same",
			},
		},
	 	rules: {

	 		coordinator:
	 		{
	 			 required: true
	 		},
			txtRsExecutiveName: {
				 required: true,
				 minlength: 3
			 },
			txtRsExecutivePhone: {
				 required: true,
				 minlength: 10
			 },
			txtRsExecutiveEmail: {
				required: true,
				email: true
			 },
			 txtPassword: {
				 required: true,
				 minlength: 5
			 },
			 txtRetypePassword : {
					minlength : 5,
					equalTo : "#txtPassword"
			 }
	 	},


	submitHandler: function (form) 
	{   
    var id = $("#hidRsExecutiveId").val();
	var name = $("#txtRsExecutiveName").val();
	var email = $("#txtRsExecutiveEmail").val();
	var phone = $("#txtRsExecutivePhone").val();
	var password = $("#txtPassword").val();
	var coordinator = $("#coordinator").val();
    var form="#frmEntry";      
	$.ajax({
		type: "POST",
		url: base_url + "pramaan/save_rs_executive_detail",
		data:
		{
			'id' : id,
			'name' : name,
			'email' : email,
			'phone' : phone,
			'password' : password,
			'coordinator' : coordinator,
		},
		dataType:'json',
		success: function (data)
		{
			if (data.status)
			{
				swal({
						title: "",
						type: "success",
						text: data.message + "!",
						confirmButtonColor: "#5cb85c",
						confirmButtonText: 'OK'
					},
					function (confirmed) {
						window.location.href = base_url + "pramaan/rs_executives/";
					});
			}
			else
			{
				/*swal({
					title: "",
					type: "error",
					text: data.message + "!",
					confirmButtonColor: "#d9534f",
					confirmButtonText: 'OK'
				});*/

				$.each(data.errors, function(key, val) 
                    {
                    	if(key=="name")
                    		 key = "txtRsExecutiveName";
                        else if(key=="email")
                             key = "txtRsExecutiveEmail";
                        else if(key=="phone")
                             key = "txtRsExecutivePhone";
                        else if(key=="password")
                             key = "txtPassword";      

                        $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
                    });

                    $("#frmEntry").valid();
			}
		},
		error: function()
		{
			alert("Error Occurred");
		}
	});

      return false; // required to block normal submit since you used ajax
	 
	 }	
	});
});

</script>
