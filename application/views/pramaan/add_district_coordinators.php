<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url().'adm-assets/vendors/js/forms/select/select2.full.min.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'adm-assets/js/scripts/forms/select/form-select2.min.js'?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/forms/selects/select2.min.css'?>">
<?php
    $district_options=array();
    foreach ($district_list as $row) 
    {
        $district_options[$row['id']]=$row['name'];

    }

 ?> 
<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  edit associates list
 * @date  Nov_2016
*/
select.input-sm 
{
 line-height: 10px; 
}
.error_label, .validmark{color: red;}
.fstElement { font-size: 1.2em; }
.fstToggleBtn { min-width: 16.5em; }

.submitBtn { display: none; }

.fstMultipleMode { display: block; }
.fstMultipleMode .fstControls { width: 100%; }
</style>
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
	<div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("pramaan/district_coordinators","District Coordinators");?></li>
				<li class="breadcrumb-item active">Add District Coordinators</li>
			</ol>
		</div>
	</div>
	<section id="basic-form-layouts">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="bordered-layout-basic-form">District Coordinators Info</h4>
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
							<form class="form form-horizontal form-bordered" id="sourcing_head_form" method="post">
								<div class="form-body">
									<div class="form-group row">
										<input type="hidden" id="parent_id" name="parent_id" value="<?= $parent_id;?>"/>
										<input type="hidden" id="user_group_id" name="user_group_id" value="<?= $user_group_id;?>"/>

										<label for="pname" class="col-sm-3 label-control">Name<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="pname" id="pname">
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label for="phone" class="col-sm-3 label-control">Phone<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="phone" id="phone" maxlength="<?= PHONE_MAX?>">
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="email" class="col-sm-3 label-control">Email<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="email" id="email" maxlength="<?= EMAIL_MAX?>">
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="district" class="col-sm-3 label-control">District<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<?php echo form_dropdown('district[]',$district_options,'','id="district" class="form-control select2" placeholder="Select Districts" multiple required');?>
											<span class="error_label"></span>
										</div>
									</div>

									<script>
										$('#district').select2({
										  language: {
										    noResults: function (params) {
										      return "All Districts Are Assigned ";
										    }
										  }
										});
									</script>

									<div class="form-group row">
										<label for="password" class="col-sm-3 label-control">Password<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="password" class="form-control" name="password" id="mainpassword" maxlength="<?= PASSWORD_MAX?>">
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="cpassword" class="col-sm-3 label-control">Retype Password<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="password" class="form-control" name="cpassword" id="cpassword" maxlength="<?= PASSWORD_MAX?>">
											<span class="error_label"></span>
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
	$("#sourcing_head_form").validate({
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
	         url: base_url+"pramaan/save_district_coordinator",
	         data: $('#sourcing_head_form').serialize(),
	         dataType:'json',
	         success: function (data) 
	         {
				 if(data.status==true)
				 {
					 swal({
							 title: "",

							 text: data.msg_info + "!",
							 confirmButtonColor: "#5cb85c",
							 confirmButtonText: 'OK'
						 },
						 // window.location.href = base_url + 'pramaan/bd_heads/'+"<?=$parent_id ?>";
						 function (confirmed) {
							 window.location.href = base_url+'pramaan/district_coordinators/'+"<?=$parent_id ?>";
						 });

				 }
				 else
				 {


					 $.each(data.errors, function(key, val)
					 {
						 $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
					 });
					 $("#sourcing_head_form").valid();
				 }

	         },
	         error: function()
	         {
	         	//alert("ERROR11!!");
	         }
	     });
	     return false; // required to block normal submit since you used ajax
	 }

	});
});
</script>