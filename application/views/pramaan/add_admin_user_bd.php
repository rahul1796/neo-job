<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  add user admin
 * @date  Nov_2016
*/
select.input-sm 
{
 line-height: 10px; 
}
.error_label, .validmark{color: red;}
</style>
<div class="inner">
<h4>Add <?=$title ?></h4>
<small>
  <ul class="breadcrumb" style="padding: 0px;">
    <li><?php echo anchor($parent_page,$parent_page_title);?></li>
    <li class="active">Add <?=$title ?></li>
  </ul>
</small>
<hr/>
    <form id="bd_admin_user" method="post" class="form-horizontal" style="padding-top:10px;">
	<div class="row form-box">
		   	<div class="col-sm-6 col-md-6">
                <div class="form-group">
                	<input type="hidden" id="parent_id" name="parent_id" value="<?= $parent_id;?>"/>
                	<input type="hidden" id="department_id" name="department_id" value="<?= $department_id;?>"/>
                    <input type="hidden" id="bd_admin_id" name="id" value="0"/>
                     <input type="hidden" id="user_group_id" name="user_group_id" value="<?php echo $user_group_id;?>"/>
                    <label for="pname" class="col-sm-4 control-label">Name<span class='validmark'>*</span></label>
                    <div class="col-sm-8">
                   	<input type="text" class="form-control" name="pname" id="pname">
				  	<span class="error_label"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone" class="col-sm-4 control-label">Phone<span class='validmark'>*</span></label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="phone" id="phone" maxlength="<?= PHONE_MAX?>">
				  	<span class="error_label"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Email<span class='validmark'>*</span></label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="email" id="email" maxlength="<?= EMAIL_MAX?>">
				 		<span class="error_label"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">Password<span class='validmark'>*</span></label>
                    <div class="col-sm-8">
                 	<input type="password" class="form-control" name="password" id="mainpassword" maxlength="<?= PASSWORD_MAX?>">
				  	<span class="error_label"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cpassword" class="col-sm-4 control-label">Retype Password<span class='validmark'>*</span></label>
                    <div class="col-sm-8">
                       <input type="password" class="form-control" name="cpassword" id="cpassword" maxlength="<?= PASSWORD_MAX?>">
                       <span class="error_label"></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
				<div class="col-sm-offset-2 col-sm-8 form-actions" style="margin-top:25px;">
					<button class="btn btn-primary" type="reset">Reset</button>
					<button class="btn btn-success" type="submit" value="add" name="submit">Save</button>
				</div>
			</div>    
        </div>
					
	</form>
</div><!-- inner -->

<script>
$(document).ready(function()
{
	//------validation for the candidate form
	$("#bd_admin_user").validate({
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
	         url: base_url+"pramaan/save_user_admin_bd",
	         data: $('#bd_admin_user').serialize(),
	         dataType:'json',
	         success: function (data) 
	         {
	         	var form="#bd_admin_user";

				if (data.status == true) 
				{
					alert(data.msg_info); //show success message
					window.location.href = base_url+"<?=$parent_page ?>";

				}
				else
				{

					$.each(data.errors, function(key, val) 
					{
						$('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
					});
					$("#bd_admin_user").valid();
				}
	         }
	     });
	     return false; // required to block normal submit since you used ajax
	 }

	});
});
</script>