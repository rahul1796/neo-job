<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link href="<?php echo base_url().'assets/fastselect/build.min.css'?>" rel="stylesheet">
<script src=""<?php echo base_url().'assets/fastselect/build.min.js'?>" >
</script>
<link href="<?php echo base_url().'assets/fastselect/fastselect.min.css'?>" rel="stylesheet">
<script src="<?php echo base_url().'assets/fastselect/fastselect.standalone.js'?>"></script>

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
<div class="inner">
<h4>Edit Sourcing Head</h4>
<small>
  <ul class="breadcrumb" style="padding:0px;">
    <li><?php echo anchor("partner/spoc_list/$parent_id","Sourcing partner admin");?></li>
    <li class="active">Edit Sourcing Partner Admin</li>
  </ul>
</small>
<hr/>
    <form id="sourcing_head_form" method="post" class="form-horizontal" style="padding-top:10px;">
	<div class="row form-box">
		   	<div class="col-sm-6 col-md-6">
                <div class="form-group">
                	<input type="hidden" id="parent_id" name="parent_id" value="<?= $parent_id;?>"/>
                	<input type="hidden" id="user_id" name="user_id" value="<?= $user_id;?>"/>
                	<!-- <input type="hidden" id="id" name="id" value="<?= $user_id;?>"/> -->
                	
                	<input type="hidden" id="user_group_id" name="user_group_id" value="<?= $user_group_id;?>"/>
                
                	
                    <label for="pname" class="col-sm-4 control-label">Name<span class='validmark'>*</span></label>
                    <div class="col-sm-8">
                   	<input type="text" class="form-control" name="pname" id="pname" value="<?php echo $sourcing_partner_admin_info[0]['name'];?>">
				  	<span class="error_label"></span>
                    </div>
                </div>
				
                <div class="form-group">
                    <label for="phone" class="col-sm-4 control-label">Phone<span class='validmark'>*</span></label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="phone" id="phone" maxlength="<?= PHONE_MAX?>" value="<?php echo $sourcing_partner_admin_info[0]['phone'];?>">
				  	<span class="error_label"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Email<span class='validmark'>*</span></label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="email" id="email" maxlength="<?= EMAIL_MAX?> " value="<?php echo $sourcing_partner_admin_info[0]['email'];?>">
				 		<span class="error_label"></span>
                    </div>
                </div>
               
            </div>
            <div class="form-group">
				<div class="col-sm-offset-2 col-sm-8 form-actions" style="margin-top:25px;">
					<button class="btn btn-primary" type="reset">Reset</button>
					<button class="btn btn-success" type="submit" value="edit" name="submit">Edit</button>
				</div>
			</div>    
        </div>
					
	</form>
</div><!-- inner -->

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
	         url: base_url+"partner/edit_sourcing_partner_admin_update",
	         data: $('#sourcing_head_form').serialize(),
	         dataType:'json',
	         success: function (data) 
	         {
	         	
				if (data.status == true) 
				{
					alert(data.msg_info); //show success message
					window.location.href = base_url+'partner/spoc_list/'+"<?=$parent_id ?>";
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