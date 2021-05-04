<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  add sourcing admin list
 * @date  Nov_2016
*/
select.input-sm 
{
 line-height: 10px; 
}
.error_label, .validmark{color: red;}
</style>
<?php

$sourcing_head_options=array('' => '-Select Sourcing Head-');
foreach ($sourcing_head_list as $row)
{
	$sourcing_head_options[$row['user_id']]=$row['name'];

}


?>
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
	<div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("pramaan/sourcing_admins_all","Sourcing Admin");?></li>
				<li class="breadcrumb-item active">Add Sourcing Admin</li>
			</ol>
		</div>
	</div>
	<section id="basic-form-layouts">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="bordered-layout-basic-form">Add User Info</h4>
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
							<form class="form form-horizontal form-bordered" id="sourcing_admin_form" method="post">
								<div class="form-body">
									<div class="form-group row">
										<label for="pname" class="col-sm-3 label-control">Name<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="hidden" id="parent_id" name="parent_id" value="<?= $parent_id;?>"/>
											<input type="hidden" id="id" name="id" value="0"/>
											<input type="hidden" id="user_group_id" name="user_group_id" value="<?= $user_group_id;?>"/>
											<input type="text" class="form-control" name="pname" id="pname">
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-3 label-control" for="email">E-mail<span class='validmark'>*</span></label>
										<div class="col-md-9">
											<input type="text" class="form-control" name="email" id="email" maxlength="<?= EMAIL_MAX?>">
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-3 label-control" for="phone">Contact Number<span class='validmark'>*</span></label>
										<div class="col-md-9">
											<input type="text" class="form-control" name="phone" id="phone" maxlength="<?= PHONE_MAX?>">
											<span class="error_label"></span>
										</div>
									</div>


									<div class="form-group row">
										<label class="col-md-3 label-control" for="country">Sourcing Head<span class='validmark'>*</span></label>
										<div class="col-md-9">
											<?php echo form_dropdown('sourcing_head',$sourcing_head_options,'','id="sourcing_head" class="form-control"');?>
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-3 label-control" for="password">Password<span class='validmark'>*</span></label>
										<div class="col-md-9">
											<input type="password" class="form-control" name="password" id="mainpassword" maxlength="<?= PASSWORD_MAX?>">
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-3 label-control" for="cpassword">Retype Password<span class='validmark'>*</span></label>
										<div class="col-md-9">
											<input type="password" class="form-control" name="cpassword" id="cpassword" maxlength="<?= PASSWORD_MAX?>">
											<span class="error_label"></span>
										</div>
									</div>


									<div class="form-group row">
										<label class="col-md-3 label-control">Select profile Image</label>
										<div class="col-md-9">
											<label id="projectinput8" class="file center-block">
												<input type="file" id="file">
												<span class="file-custom"></span>
											</label>
										</div>
									</div>
								</div>

								<div class="form-actions">
									<button type="reset" class="btn btn-warning mr-1"><i class="icon-cross2"></i> Reset</button>
									<button type="submit" class="btn btn-primary"  value="add" name="submit"><i class="icon-check2"></i>Save</button>
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
	//------validation for the candidate form
	$("#sourcing_admin_form").validate({
	 ignore: ":hidden",
		errorPlacement: function(error, element) 
		{
		// name attrib of the field
		$(element).closest('.form-group').find('.error_label').html(error);
		},
		messages: 
		{
			pname: 
			{
				required: "Please enter first name",
			},
			sourcing_head:
			{
				required: "Please select sourcing head",
			},
			phone: 
			{
				required: "Please enter your phone",
			},
			email: 
			{
				required: "Please enter your email",
			},
			password: 
			{
				required: "Please enter password",
			},
			cpassword: 
			{
				required: "Retype password is not same",
			},
		},
	 rules: {
	     pname: {
	         required: true,
	         minlength: 3
	     },
	     sourcing_head:
			{
             required: true 
		  },		
	     phone: {
	         required: true,
	         minlength: 10
	     },
	     email: {
	            required: true,
                email: true
	     },
	     password: {
	         required: true,
	         minlength: 5
	     },
		cpassword : {
		        minlength : 5,
		        equalTo : "#mainpassword"
		    }
	 },
	 submitHandler: function (form) 
	 {
	      $.ajax({
	         type: "POST",
	         url: base_url+"pramaan/add_sourcing_admin_superadmin_new",
	         data: $('#sourcing_admin_form').serialize(),
	         dataType:'json',
	         success: function (data) 
	         {
	         	
	         	if (data.status == true) 
				{
					swal({
					title: "",
					type: "success",
					text: data.msg_info + "!",
					confirmButtonColor: "#5cb85c",
					confirmButtonText: 'OK'
						},
						function (confirmed) {
							window.location.href = base_url + "pramaan/sourcing_admins_all/";
						});
					//window.location.href = base_url+'pramaan/sourcing_admins_all/';
				}
				else
				{	
					

					$.each(data.errors, function(key, val) 
					{
						$('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
					});
					$("#sourcing_head_form").valid();
				}
	         }
	     });
	     return false; // required to block normal submit since you used ajax
	 }

	});
});
</script>