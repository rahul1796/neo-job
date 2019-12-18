<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url().'adm-assets/vendors/js/forms/select/select2.full.min.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'adm-assets/js/scripts/forms/select/form-select2.min.js'?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/forms/selects/select2.min.css'?>">
<?php
    $sector_options=array('' => '-Select Sector-');
    foreach ($sector_list as $row) 
    {
        $sector_options[$row['id']]=$row['name'];

    }

 ?> 
<?php
    $interest_type_options=array('' => '-Select Interest Type-');
    foreach ($interest_type_list as $row) 
    {
        $interest_type_options[$row['value']]=$row['name'];

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
				<li class="breadcrumb-item"><?php echo anchor("pramaan/qualification_pack","Qualification Pack");?></li>
				<li class="breadcrumb-item active">Edit Qualification Pack</li>
			</ol>
		</div>
	</div>
	<section id="basic-form-layouts">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="bordered-layout-basic-form">Edit Qualification pack Info</h4>
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
										<!-- 	<input type="hidden" id="parent_id" name="parent_id" value="<?= $parent_id;?>"/> -->
										<input type="hidden" id="id" name="id" value="<?= $id;?>"/>
										<input type="hidden" id="qualification_name" name="qualification_name" value="<?= $qualification_name;?>"/>
									</div>
									<div class="form-group row">
										<label for="quali_name" class="col-sm-3 label-control">Qualification Name<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="qualification_name" id="quali_name"  value="<?php echo $qualification_name;?>" disabled>
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="sector" class="col-sm-3 label-control">Sector<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<?php echo form_dropdown('sector',$sector_options,$sector_id,'id="sector" class="form-control" ');?>
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label for="interest_type" class="col-sm-3 label-control">Interest Type<span class="validmark">*</span></label>
										<div class="col-sm-9">

											<?php echo form_dropdown('interest_type',$interest_type_options,$interest_type_code,'id="interest_type" class="form-control" ');?>
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
			sector:
			{
				required: "Please Select a Sector",
			},
			interest_type:
			{
				required: "Please Select an interest"
			}
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
		    },
		sector:
			{
             required: true 
		  }, 
		  interest_type:
			{
				required: true
			}   
	 },
	 submitHandler: function (form) 
	 {
	      $.ajax({
	         type: "POST",
	         url: base_url+"pramaan/save_qualification_pack",
	         data: $('#sourcing_head_form').serialize(),
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
							 window.location.href = base_url+'pramaan/qualification_pack';
						 });

				 }
				 else
				 {
					 /*alert("ERROR!!");*/

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
