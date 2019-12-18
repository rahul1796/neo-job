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
<div class="inner">
<small>
<h4> Add Employer </h4>
  <ul class="breadcrumb">
    <li><?php echo anchor("partner/employers","Employers");?></li>
    <li class="active"> Add Employer </li>
  </ul>
</small>
<hr/>
    <form id="add_Employer_form" method="post" class="form-horizontal" style="padding-top:10px;">
	<div class="form-box">
		   	<div class="col-sm-6 col-md-6">
           
                <div class="form-group">
                    <label for="employer_name" class="col-sm-4 control-label">Employer Name<span class="validmark">*</span></label>
                    <div class="col-sm-8">
                   	<input type="text" class="form-control" name="employer_name" id="employer_name" maxlength="50" placeholder="Employer Name" />
				  	<span class="error_label"></span>
                    </div>
                </div>
 				<div class="form-group">
                    <label for="contact_name" class="col-sm-4 control-label">Contact<span class="validmark">*</span></label>
                    <div class="col-sm-8">
                   	<input type="text" class="form-control" name="contact_name" id="contact_name" maxlength="50" placeholder="Contact Person Name" />
				  	<span class="error_label"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone" class="col-sm-4 control-label">Phone<span class="validmark">*</span></label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="phone" id="phone" maxlength="<?= PHONE_MAX?>" placeholder="Phone"/>
				  	<span class="error_label"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Email<span class="validmark">*</span></label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="email" id="email" maxlength="<?= EMAIL_MAX?>"  placeholder="Email"/>
				 		<span class="error_label"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">Password<span class="validmark">*</span></label>
                    <div class="col-sm-8">
                 	<input type="password" class="form-control" name="password" id="mainpassword" maxlength="<?= PASSWORD_MAX?>"  placeholder="Password" />
				  	<span class="error_label"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cpassword" class="col-sm-4 control-label">Retype Password</label>
                    <div class="col-sm-8">
                       <input type="password" class="form-control" name="cpassword" id="cpassword" maxlength="<?= PASSWORD_MAX?>" placeholder="Retype Password"/>
                       <span class="error_label"></span>
                        </div>
                </div>
				<div class="form-group">
                    <label for="sector" class="col-sm-4 control-label">Sector<span class="validmark">*</span></label>
                    <div class="col-sm-8">
						<?php echo form_dropdown('sector',$options_sectors, '', 'class="form-control" id="sector"');?>
			  			<span class="error_label"></span>
                    </div>
                </div>     
				<div class="form-group">
                    <label for="org_type" class="col-sm-4 control-label">Type of Organisation<span class="validmark">*</span></label>
                    <div class="col-sm-8">
						<?php echo form_dropdown('org_type',$options_orgTypes, '', 'class="form-control" id="org_type"');?>
			  			<span class="error_label"></span>
                    </div>
                </div>     

                <div class="form-group">
					<div class="col-sm-offset-4 col-sm-8 form-actions" style="margin-top:25px;">
						<button class="btn btn-primary" type="reset">Reset</button>
						<button class="btn btn-success" type="submit">Save changes</button>
					</div>
                </div>

        </div>
				
	</div>
	</form>
</div><!-- inner -->

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
			
			phone: 
			{
				required: "Please enter your phone",
			},
			email: 
			{
				required: "Please enter your email",
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
		
	     phone: {
	         required: true,
	         number: true,
	         minlength: 10
	     },
	     email: {
	            required: true,
                email: true
	     },
	     
		org_type: {
            		 required: true,
    	 	},
    	sector: {
            		 required: true,
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
	         url: base_url+"/partner/save_employer",
	         data: $('#add_Employer_form').serialize(),
	         dataType:'json',
	         success: function (data) 
	         {
	         	var form="#add_Employer_form";

				if (data.status == true) 
				{
					$('#add_Employer_form')[0].reset();
					flashAlert(data.msg_info);
					window.location.href = base_url+'partner/employers/';
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
