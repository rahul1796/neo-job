<style type="text/css">
p.justify
{
    text-align: justify-all;
}
</style>
<?php
/*$decrypttext = rawurlencode($this->encrypt->decode($_GET['q']));
echo $decrypttext;
*/?>
<div class="content-body" style="padding: 10px;">
	<section id="description" class="card">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<label class="card-title" for="color"><h4>Contact us</h4></label>
				</div>
				<div class="card-body">
					<div class="card-block">
						<p><strong>LabourNet Services India Pvt. Ltd </strong>
							<br>24/1-4, 19th ‘A’ Main,
							<br>9th Cross, JP Nagar 2nd Phase,
							<br>Bengaluru – 560078,
							<br>Karnataka, India.
							<br>Phone: 080 4450 4450, 080 4450 4459
							<br>Email: contactus@labournet.in

						</p>
						<p>
						<div id="msgContactus"> </div>
						<form method="post" id="contact-form">
							<div class="form-group">
								<input type="text" name="user_name" id="user_name" class="form-control" placeholder="Your name"  maxlength="75" title="Name"  required>
							</div>

							<div class="form-group">
								<input type="text" name="mobile" id="mobile" class="form-control" placeholder="Your Mobile"  maxlength="12" title="Mobile number"  required>
							</div>

							<div class="form-group">
								<input type="text" name="email" class="form-control" placeholder="Your Email"  maxlength="50" title="Email">
							</div>

							<div class="form-group">
								<textarea class="form-control" name="message" placeholder="Your Message" maxlength="255" title="Your Message"></textarea>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-success btn-block" name="btn_send" id="btn_send">Send</button>

							</div>
						</form>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<label class="card-title" for="color"><h4>Location</h4></label>
				</div>
				<div class="card-body">
					<div class="card-block">

						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3888.957008520827!2d77.58848383733549!3d12.910484728364748!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bae150d8876505f%3A0x331067b5401d0b5a!2sLabourNet!5e0!3m2!1sen!2sin!4v1560408248498!5m2!1sen!2sin" width="600" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
$(document).ready(function()
{
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
