<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url().'adm-assets/vendors/js/forms/select/select2.full.min.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'adm-assets/js/scripts/forms/select/form-select2.min.js'?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/forms/selects/select2.min.css'?>">
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
				<li class="breadcrumb-item"><?php echo anchor("partner/employers","Employers");?></li>
				<li class="breadcrumb-item active">Edit Employer</li>
			</ol>
		</div>
	</div>
	<section id="basic-form-layouts">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="bordered-layout-basic-form">Employer Info</h4>
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
										<input type="hidden" id="emp_id" name="emp_id" value="<?= $emp_id;?>"/>
										<input type="hidden" id="emp_id" name="id" value="<?= $emp_id;?>"/>


										<label for="pname" class="col-sm-3 label-control">Name<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="pname" id="pname" value="<?php echo $emp_name;?>">
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row" style="display: none;">
										<label for="contact_name" class="col-sm-3 label-control">SPOC Name<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" value="abc" name="contact_name" id="contact_name" value="<?php echo $spoc_name;?>" maxlength="50" placeholder="Contact Person Name" />
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label for="phone" class="col-sm-3 label-control">Phone<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="phone" id="phone" maxlength="<?= PHONE_MAX?>" value="<?php echo $emp_phone;?>">
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<label for="phone" class="col-sm-3 label-control">SPOC Phone<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" value="123" name="spoc_phone" id="spoc_phone" maxlength="<?= PHONE_MAX?>" value="<?php echo $spoc_phone;?>">
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row" >
										<label for="email" class="col-sm-3 label-control">Employer Email<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control"  name="email" id="email" maxlength="<?= EMAIL_MAX?> " value="<?php echo $emp_email;?>">
											<span class="error_label"></span>
										</div>
									</div>
								</div>

								<div class="form-actions">
									<button type="reset" class="btn btn-warning mr-1"><i class="icon-cross2"></i> Reset</button>
									<button type="submit" class="btn btn-primary"  value="edit" name="submit"><i class="icon-check2"></i>Save</button>
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
			contact_name:
			{
             required: "Please enter your spoc name",
			},

			phone: 
			{
				required: "Please enter your phone",
			},
			spoc_phone:
			{
              required: "Please enter your spoc phone",
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
	     contact_name:{
           required: true,
	         minlength: 3
	     },
	     phone: {
	         required: true,
	         minlength: 10
	     },
	     spoc_phone:{
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
	highlight: function(element) 
	{
		// add a class "has_error" to the element 
		$(element).addClass('error_label');
	},
	unhighlight: function(element) 
	{
		// remove the class "has_error" from the element 
		$(element).removeClass('error_label');
	},
	 submitHandler: function (form) 
	 {
	      $.ajax({
	         type: "POST",
	         url: base_url+"partner/edit_employer_update",
	         data: $('#sourcing_head_form').serialize(),
	         dataType:'json',
	         success: function (data) 
	         {
				 if (data.status == true)
				 {
					 swal({
							 title: "",

							 text: data.msg_info + "!",
							 confirmButtonColor: "#5cb85c",
							 confirmButtonText: 'OK'
						 },
						 // window.location.href = base_url + 'pramaan/bd_heads/'+"<?=$parent_id ?>";
						 function (confirmed) {
							 window.location.href = base_url+'partner/employers';
						 });


				 }
				 else
				 {
					 //alert("ERROR!!");

					 $.each(data.errors, function(key, val)
					 {
						 $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
					 });
					 $("#sourcing_head_form").valid();
				 }

	         },
	         error: function()
	         {
	         	alert("ERROR11!!");
	         }
	     });
	     return false; // required to block normal submit since you used ajax
	 }

	});
});
</script>