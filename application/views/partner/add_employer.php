<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  add employer
 * @date  Nov_2016
*/
</style>
<?php
$options_sectors=array(''=>'-Select Sector-');
    foreach ($sector_list as $row) 
    {
        $options_sectors[$row['id']]=$row['name'];
    }
$options_orgTypes=array(''=>'-Select Org type-');
    foreach ($org_list as $row) 
    {
        $options_orgTypes[$row['value']]=$row['name'];
    }
?>
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
	<div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("partner/employers","Employers");?></li>
				<li class="breadcrumb-item active">Add Employer</li>
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
							<form class="form form-horizontal form-bordered" id="add_Employer_form" method="post">
								<div class="form-body">
									<input type="hidden" name="bd_exec_id" value="<?php echo $bd_exec_id?>" size="1" />
									<div class="form-group row">
										<label for="employer_name" class="col-sm-3 label-control">Employer Name<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="employer_name" id="employer_name" maxlength="50" placeholder="Employer Name" />
											<input type="hidden" name="id" size="1" />
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="phone" class="col-sm-3 label-control">Employer Phone<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="phone" id="phone" maxlength="<?= PHONE_MAX?>" placeholder="Phone"/>
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<label for="contact_name" class="col-sm-3 label-control">SPOC Name<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" value="abc" name="contact_name" id="contact_name" maxlength="50" placeholder="Contact Person Name" />
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<label for="phone" class="col-sm-3 label-control">SPOC Phone<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" value="123" name="spoc_phone" id="spoc_phone" maxlength="<?= PHONE_MAX?>" placeholder="Phone"/>
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="email" class="col-sm-3 label-control">Employer Email<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control"   name="email" id="email" maxlength="<?= EMAIL_MAX?>"  placeholder="Email"/>
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<label for="password" class="col-sm-3 label-control">Password<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<input type="password" class="form-control" value="salt" name="password" id="mainpassword" maxlength="<?= PASSWORD_MAX?>"  placeholder="Password" />
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<label for="cpassword" class="col-sm-3 label-control">Retype Password</label>
										<div class="col-sm-9">
											<input type="password" class="form-control" value="salt" name="cpassword" id="cpassword" maxlength="<?= PASSWORD_MAX?>" placeholder="Retype Password"/>
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="sector" class="col-sm-3 label-control">Sector<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<?php echo form_dropdown('sector',$options_sectors, '', 'class="form-control" id="sector"');?>
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="org_type" class="col-sm-3 label-control">Type of Organisation<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<?php echo form_dropdown('org_type',$options_orgTypes, '', 'class="form-control" id="org_type"');?>
											<span class="error_label"></span>
										</div>
									</div>
								</div>

								<div class="form-actions">
									<button type="reset" class="btn btn-warning mr-1"><i class="icon-cross2"></i> Reset</button>
									<button type="submit" class="btn btn-primary" name="submit"><i class="icon-check2"></i>Save</button>
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
	$("#add_Employer_form").validate({
	 ignore: ":hidden",
		errorPlacement: function(error, element) 
		{
		// name attrib of the field
		 $(element).closest('.form-group').find('.error_label').html(error);
		},
		messages: 
		{
			employer_name: 
			{
				required: "Please enter Employer name",
			},
			contact_name: 
			{
				required: "Please enter Contact person name",
			},
			phone: 
			{
				required: "Please enter your phone",
			},
			spoc_phone:
			{
				required: "Please enter spoc phone",
			},
			email: 
			{
				required: "Please enter spoc email",
			},
			password: 
			{
				required: "Please enter password",
			},
			cpassword: 
			{
				required: "Retype password is not same",
			},
			org_type:
			{
				required:"Please select Organisation type",
			},
			sector:
			{
				required:"Please select sector",
			}
		},
	 rules: {
	     employer_name: {
	         required: true,
	         minlength: 3
	     },
		contact_name: {
	         required: true,
	         minlength: 3
	     },
	     phone: {
	         required: true,
	         number: true,
	         minlength: 10
	     },
	      spoc_phone: {
	         required: true,
	         number: true,
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
		org_type: {
            		 required: true,
    	 	},
    	sector: {
            		 required: true,
    	 	}
	 },
	 submitHandler: function (form) 
	 {
	      $.ajax({
	         type: "POST",
	         url: base_url+"/partner/save_employer",
	         data: $('#add_Employer_form').serialize(),
	         dataType:'json',
	         success: function (data) 
	         {
	         	var form="#add_Employer_form";

				if (data.status == true)
				{
					swal({
							title: "",

							text: data.msg_info + "!",
							confirmButtonColor: "#5cb85c",
							confirmButtonText: 'OK'
						},
						
						function (confirmed) {
							window.location.href = base_url+'partner/employers/'+"<?php echo $bd_exec_id?>";
						});

				}
				else
				{
					$.each(data.errors, function(key, val)
					{
						$('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
					});
				}
	         }
	     });
	     return false; // required to block normal submit since you used ajax
	 }

	});
});
</script>