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
</style>
<div class="content-body" style="padding: 10px;">
	<section id="description" class="card">
		<div class="col-md-3 ">
			<div class="card">


			</div>
		</div>
		<div class="col-md-6 ">
			<div class="card">
				<div class="card-header">
					<label class="card-title" for="color"><h4>Reset Password</h4></label>
				</div>
				<div class="card-body">
					<div class="card-block">

						<p>
						<div id="change_password"> </div>
						<form method="post" id="contact-form" action="<?= base_url('UsersController/changePassword'); ?>">
							<div class="form-group">
								<input type="password" name="new_password" id="new_password" class="form-control f-pass" placeholder="New Password"  maxlength="75" title="New Password"  required>
							</div>
              <input type="hidden" name="email" value="<?= $email ?>">
							<div class="form-group">
								<input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control f-pass" placeholder="Confirm New Password"  maxlength="12" title="Confirm New Password"  required>
                <span class="text text-danger hide" id='password-error'></span>
              </div>

							<div class="form-group">
								<button type="submit" class="btn btn-success btn-block" name="btn_save" id="btn_save" disabled>Save</button>

							</div>
						</form>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 ">

		</div>

	</section>
</div>
<script>
$(document).ready(function()
{
  $('#confirm_new_password').keyup(function() {
    if($('#new_password').val() != $('#confirm_new_password').val()) {
      $('#password-error').removeClass('hide');
      $('#btn_save').attr('disabled', true);
      $('#password-error').text('Password is not matching');
    } else {
      $('#password-error').addClass('hide');
      $('#password-error').text('');
      $('#btn_save').attr('disabled', false);
    }
  });

  $("button#btn_send").click(function()
  {
    event.preventDefault();
    /* the email & the password values from the index page are assigned to varialbes */
    /* check and make sure the user didn't submit blank values.
    This is where you can add more validation checks which i left open for expantion */
    var user_name = $( "#user_name" ).val();
    var mobile = $( "#mobile" ).val();
    /* check and make sure the user didn't submit blank values.
    This is where you can add more validation checks which i left open for expantion */
    var storedmobile = sessionStorage.getItem("mobile");

    if(user_name =="" || mobile =="" )
    {
      var message="Name or mobile number cannot be empty";
    $("#msgContactus").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>');
    }
    else
    {
      $.ajax({
	      type: "POST",
	      url: base_url+"pramaan/send_contactus", /* to validate the user input with database */
	      data:$('#contact-form').serialize(),
	      dataType:'json',
	      async: true,
	      success: function(msg)
	      {
	        if(msg.status == true)
	        {
	          $("#msgContactus").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a><span>'+msg.msg_info+'</span></div>'); /* Display login failer message in the div tag id=msgContactus */
	        }
	        else
	        {
	          $("#msgContactus").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a><span>'+msg.msg_info+'</span></div>'); /* Display login failer message in the div tag id=msgContactus */
	        }

	      },
	      error: function()
	      {
	        var message="Please try some time later!!!!";
	        $("#msgContactus").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>');

	      }
      });
    }
  return false;
  });

});
</script>
