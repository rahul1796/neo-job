<style type="text/css">
select.input-sm 
{
 line-height: 10px; 
}
.error_label, .validmark, .error
{color: red;}
</style>
<?php
$options_roles=array(''=>'-Select Partner Type-');
foreach ($role_list as $row)
{
	$options_roles[$row['id']]=$row['name'];
}

if($partner_id>0)
{
	$sourcing_partner_name = $sourcing_partner_data['name'];

	$sourcing_partner_phone = $sourcing_partner_data['phone'];

	$sourcing_partner_email = $sourcing_partner_data['email'];

}

else{
	$sourcing_partner_name = "";

	$sourcing_partner_phone = "";

	$sourcing_partner_email = "";
}


?>
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
	<script type="text/javascript">

		function ddlPartnerType_onchange(){
			var varPartnerTypeId = $("#partner_type_id").val();
			if (varPartnerTypeId == "2")
			{
				$("#lblPhone").text("SPOC Phone");
				$("#lblPhone").append("<span class='validmark'>*</span>");
				$("#lblEmail").text("SPOC Email");
				$("#lblEmail").append("<span class='validmark'>*</span>");
			}
			else
			{
				$("#lblPhone").text("Phone");
				$("#lblPhone").append("<span class='validmark'>*</span>");
				$("#lblEmail").text("Email");
				$("#lblEmail").append("<span class='validmark'>*</span>");
			}


		}

	</script>
	<div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("pramaan/sourcing_partner","Sourcing Partner");?></li>
				<?php
				if($partner_id>0)
				{
					?> <li class="breadcrumb-item active">Edit Sourcing Partner</li>
					<?php
				}
				else{

					?>
					<li class="breadcrumb-item active">Add Sourcing Partner</li>
				<?php } ?>
			</ol>
		</div>
	</div>
	<section id="basic-form-layouts">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="bordered-layout-basic-form">Souring Partner Info</h4>
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
							<form class="form form-horizontal form-bordered" id="sourcing_partner_form" method="post">
								<div class="form-body">
									<div class="form-group row">
										<input type="hidden" id="hdnPartnerId" name="hdnPartnerId" value="<?php echo $partner_id?>"/>
										<input type="hidden" id="hdnPartnerId" name="id" value="<?php echo $partner_id?>"/>
										<input type="hidden" id="coordinator_id" name="coordinator_id" value="<?php echo $coordinator_id?>"/>


										<?php
										if($partner_id==0)
										{

											?>
											<label for="partner_type_id" class="col-sm-3 label-control">Partner Type<span class='validmark'>*</span></label>
											<div class="col-sm-9">
												<?php echo form_dropdown('partner_type_id',$options_roles,'', 'class="form-control" id="partner_type_id" onchange="ddlPartnerType_onchange()" ');?>
												<span class="error_label"></span>
											</div>
											<?php
										}
										?>

									</div>
									<div class="form-group row">
										<label for="pname" class="col-sm-3 label-control">Partner Name<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="pname" value="<?php echo  $sourcing_partner_name; ?>" id="pname" maxlength="50">
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row" >
										<label for="phone" id = "lblPhone" class="col-sm-3 label-control">Phone<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="phone" value="<?php echo  $sourcing_partner_phone; ?>"  id="phone" maxlength="<?= PHONE_MAX?>">
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="email" id = "lblEmail" class="col-sm-3 label-control">Email<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="email" value="<?php echo  $sourcing_partner_email; ?>"  id="email" maxlength="<?= EMAIL_MAX?>">
											<span class="error_label"></span>
										</div>
									</div>

									<?php

									if($partner_id==0)
									{

										?>
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

										<?php
									}

									?>
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
   /* $('#recruitment').on('click',function()
    {
       $('.alert').show();
    }) */

	//------validation for the candidate form
	$("#sourcing_partner_form").validate({
	 ignore: ":hidden",
		errorPlacement: function(error, element) 
		{
		// name attrib of the field
		 $(element).closest('.form-group').find('.error_label').html(error);
		},
		messages: 
		{
			partner_type_id: 
			{
				required: "Please Select partner type",
			},
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

	 	 partner_type_id: {
		         required: true
	     		},
	     pname: {
		         required: true,
		         minlength: 3
	     		},
	     phone: {
			         required: true,
			         number:true,
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
	         url: base_url+"/pramaan/save_sourcing_partner",
	         data: $('#sourcing_partner_form').serialize(),
	         dataType:'json',
	         success: function (data) 
	         {   //alert(data.msg_info);
				 var form="#sourcing_partner_form";

				 if (data.status == true)
				 {
					 swal({
							 title: "",

							 text: data.msg_info + "!",
							 confirmButtonColor: "#5cb85c",
							 confirmButtonText: 'OK'
						 },
						 function (confirmed) {
							 window.location.href = base_url+'pramaan/sourcing_partner/';
						 });



				 }
				 else
				 {

					 $.each(data.errors, function(key, val)
					 {
						 $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
					 });
					 $("#sourcing_partner_form").valid();
				 }

	         },
	          error:function()
             {
             	alert('Error!!');
             }
	     });
	     return false; // required to block normal submit since you used ajax
	 }

	});
});
</script>