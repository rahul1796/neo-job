<style type="text/css">
	/**
	 * @author  George Martin <george.s@navriti.com>
	 * @desc  	Add / Edit Recruitment Support Admin
	 * @date  	March 2017
	*/
	select.input-sm
	{
	 	line-height: 10px;
	}
	.error_label, .validmark{color: red;}
</style>
<?php
$strRsHeadId = '0';
if (isset($ResponseData[0]['rs_head_id']))
	$strRsHeadId = $ResponseData[0]['rs_head_id'];
?>
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
	<div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?> </li>
				<li class="breadcrumb-item"><?php echo anchor($parent_page, $parent_page_title);?></li>
				<li class="breadcrumb-item active">Add <?=$title ?></li>
			</ol>
		</div>
	</div>
	<section id="basic-form-layouts">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="bordered-layout-basic-form"><?= (($id > 0) ? "Edit " : "Add ") . $title ?></h4>
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
							<form class="form form-horizontal form-bordered" id="frmEntry" method="post">
								<div class="form-body">
									<div class="form-group row">
										<input type="hidden" id="hidRsAdminId" name="hidRsAdminId" value="<?= isset($ResponseData[0]['rs_admin_id']) ? $ResponseData[0]['rs_admin_id'] : "0" ?>"/>
										<label class="col-md-3 label-control" for="pname">Name<span class='validmark'>*</span></label>
										<div class="col-md-9">
											<input type="text" class="form-control" id="txtRsAdminName" name="txtRsAdminName" value="<?= isset($ResponseData[0]['rs_admin_name']) ? $ResponseData[0]['rs_admin_name'] : "" ?>" placeholder="Enter RS Admin Name"/>
											<span class="error_label"></span>
										</div>

									</div>
									<div class="form-group row">
										<label class="col-md-3 label-control" for="email">E-mail<span class='validmark'>*</span></label>
										<div class="col-md-9">
											<input type="text" class="form-control" id="txtRsAdminEmail" name="txtRsAdminEmail" maxlength="<?= EMAIL_MAX?>" value="<?= isset($ResponseData[0]['rs_admin_email']) ? $ResponseData[0]['rs_admin_email'] : "" ?>" placeholder="Enter RS Admin Email"/>
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-3 label-control" for="phone">Contact Number<span class='validmark'>*</span></label>
										<div class="col-md-9">
											<input type="text" class="form-control" id="txtRsAdminPhone" name="txtRsAdminPhone" maxlength="<?= PHONE_MAX?>" value="<?= isset($ResponseData[0]['rs_admin_phone']) ? $ResponseData[0]['rs_admin_phone'] : "" ?>" placeholder="Enter RS Admin Phone"/>
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row" style="display:<?= (($id > 0) ? "none" : "block")?>;">
										<label for="txtPassword" class="col-sm-3 label-control">Password<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="password" class="form-control" id="txtPassword" name="txtPassword" maxlength="<?= PASSWORD_MAX?>" placeholder="Enter Password">
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row" style="display:<?= (($id > 0) ? "none" : "block")?>;">
										<label for="txtRetypePassword" class="col-sm-3 label-control">Retype Password<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="password" class="form-control" id="txtRetypePassword" name="txtRetypePassword" maxlength="<?= PASSWORD_MAX?>" placeholder="Retype Password">
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row" style="display:<?= (($id > 0) ? "none" : "block")?>;">
										<label for="RShead" class="col-sm-3 label-control">RS Head<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<select id="listRsHead" name="listRsHead" class="form-control">
												<option value="0" <?= $strRsHeadId == '0' ? 'selected="selected"' : '' ?> >Select RS Head</option>
												<?php
												if (isset($RsHeadList))
												{
													foreach ($RsHeadList AS $row)
													{
														echo '<option value="' . $row['rs_head_id'] . '"' . ($strRsHeadId == $row['rs_head_id'] ? 'selected="selected"' : '') . '>' . $row['rs_head_name'] . '</option>';
													}
												}
												?>
											</select>
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-3 label-control">Select profile Image</label>
										<div class="col-md-9">
											<label id="projectinput8" class="file center-block">
												<input type="file" id="file">
												<span class="file-custom"></span>
											</label>
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
	$("#frmEntry").validate({
	 	ignore: ":hidden",
		errorPlacement: function(error, element) 
		{
			 $(element).closest('.form-group').find('.error_label').html(error);
		},
		messages: 
		{
			txtRsAdminName:
			{
				required: "Please enter name",
			},
			txtRsAdminPhone:
			{
				required: "Please enter phone",
			},
			txtRsAdminEmail:
			{
				required: "Please enter your email",
			},
			txtPassword:
			{
				required: "Please enter password",
			},
			txtRetypePassword:
			{
				required: "Retype password is not same",
			},
			listRsHead:
			{
				required: "Please select RS-Head",
			}
		},
	 	rules: {
			txtRsAdminName: {
				required: true,
				minlength: 3
			},
			txtRsAdminPhone: {
				required: true,
				minlength: 10
			},
			txtRsAdminEmail: {
				required: true,
				email: true
			},
			txtPassword: {
				required: true,
				minlength: 5
			},
			txtRetypePassword : {
				minlength : 5,
				equalTo : "#txtPassword"
			},
			listRsHead: {
				required: true
			}
	 	},


		submitHandler: function (form) 
	  {   

    var id = $("#hidRsAdminId").val();
	var name = $("#txtRsAdminName").val();
	var email = $("#txtRsAdminEmail").val();
	var phone = $("#txtRsAdminPhone").val();
	var password = $("#txtPassword").val();
	var listRsHead = $("#listRsHead").val();

        var form="#frmEntry";

        $.ajax({
		type: "POST",
		url: base_url + "pramaan/save_rs_admin_detail",
		data:
		{
			'id' : id,
			'name' : name,
			'email' : email,
			'phone' : phone,
			'password' : password,
			'listRsHead' : listRsHead
		},
		dataType:'json',
		success: function (data)
		{
			if (data.status)
			{
				swal({
						title: "",
						type: "success",
						text: data.message + "!",
						confirmButtonColor: "#5cb85c",
						confirmButtonText: 'OK'
					},
					function (confirmed) {
						window.location.href = base_url + "pramaan/rs_admins/";
					});
			}
			else
			{
			
				$.each(data.errors, function(key, val) 
                    {
                    	if(key=="name")
                    		 key = "txtRsAdminName";
                        else if(key=="email")
                             key = "txtRsAdminEmail";
                        else if(key=="phone")
                             key = "txtRsAdminPhone";
                        else if(key=="password")
                             key = "txtPassword";      

                        $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
                    });

                    $("#frmEntry").valid();
			}
		},
		error: function()
		{
			alert("Error Occurred");
		}
     });

      return false; // required to block normal submit since you used ajax
	 
	 }	
   
	});
});

</script>
