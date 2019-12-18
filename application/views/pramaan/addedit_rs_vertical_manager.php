<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url().'adm-assets/vendors/js/forms/select/select2.full.min.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'adm-assets/js/scripts/forms/select/form-select2.min.js'?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/forms/selects/select2.min.css'?>">
<style type="text/css">
	/**
	 * @author  George Martin <george.s@navriti.com>
	 * @desc  	Add / Edit Recruitment Support Head
	 * @date  	March 2017
	*/
	select.input-sm
	{
	 	line-height: 10px;
	}
	.error_label, .validmark{color: red;}
</style>

<?php
/*$VerticalList=array();
foreach ($vertical_list as $row)
{
	$VerticalList[$row['vertical_id']]=$row['vertical_name'];
}*/
?>
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
	<div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></li>
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
						<h4 class="card-title" id="bordered-layout-basic-form"><?= (($id > 0) ? "Edit " : "Add ") . $title ?> Info</h4>
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
								<input type="hidden" id="hidRsVerticalManagerId" name="hidRsVerticalManagerId" value="<?= isset($ResponseData[0]['rs_vertical_manager_id']) ? $ResponseData[0]['rs_vertical_manager_id'] : "0" ?>"/>
								<input type="hidden" id="hidVerticalIdList" name="hidVerticalIdList" value="<?= isset($ResponseData[0]['vertical_id_list']) ? $ResponseData[0]['vertical_id_list'] : "" ?>"/>
								<div class="form-group row">
									<label for="txtRsVerticalManagerName" class="col-sm-3 label-control">Name<span class='validmark'>*</span></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="txtRsVerticalManagerName" name="txtRsVerticalManagerName" value="<?= isset($ResponseData[0]['rs_vertical_manager_name']) ? $ResponseData[0]['rs_vertical_manager_name'] : "" ?>" placeholder="Enter RS Vretical Manager Name"/>
										<span class="error_label"></span>
									</div>
								</div>

								<div class="form-group row">
									<label for="txtRsVerticalManagerPhone" class="col-sm-3 label-control">Phone<span class='validmark'>*</span></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="txtRsVerticalManagerPhone" name="txtRsVerticalManagerPhone" maxlength="<?= PHONE_MAX?>" value="<?= isset($ResponseData[0]['rs_vertical_manager_phone']) ? $ResponseData[0]['rs_vertical_manager_phone'] : "" ?>" placeholder="Enter RS Vretical Manager Phone"/>
										<span class="error_label"></span>
									</div>
								</div>

								<div class="form-group row">
									<label for="txtRsVerticalManagerEmail" class="col-sm-3 label-control">Email<span class='validmark'>*</span></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="txtRsVerticalManagerEmail" name="txtRsVerticalManagerEmail" maxlength="<?= EMAIL_MAX?>" value="<?= isset($ResponseData[0]['rs_vertical_manager_email']) ? $ResponseData[0]['rs_vertical_manager_email'] : "" ?>" placeholder="Enter RS Vretical Manager Email"/>
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

								<div class="form-group row">
									<label for="listVertical" class="col-sm-3 label-control" stype="margin-top:10px;">Verticals<span class="validmark">*</span></label>
									<div class="col-sm-9">
										<select name="VerticalList[]" id="listVertical" multiple="multiple" placeholder="Select Verticals" class="select2" required style="width:100%;">
											<?php
											if (isset($vertical_list))
												foreach ($vertical_list as $row) echo "<option value=" . $row['vertical_id'] . ">" . $row['vertical_name'] . "</option>\n";
											?>
										</select>
										<span class="error_label"></span>
									</div>
								</div>

								<script>

									var varIdList = '<?= isset($ResponseData[0]['vertical_id_list']) ? $ResponseData[0]['vertical_id_list'] : '' ?>';
									var varIdArray = varIdList.split(',');
									$('#listVertical').val(varIdArray);

								</script>


								<div class="form-actions">
									<button type="reset" class="btn btn-warning mr-1"><i class="icon-cross2"></i> Reset</button>
									<button type="submit" class="btn btn-primary" name="submit"><i class="icon-check2"></i>Save</button>
								</div>

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
			txtRsVerticalManagerName:
			{
				required: "Please enter name",
			},
			txtRsVerticalManagerPhone:
			{
				required: "Please enter phone",
			},
			txtRsVerticalManagerEmail:
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
			}
		},
	 	rules: {
			txtRsVerticalManagerName: {
				 required: true,
				 minlength: 3
			 },
			txtRsVerticalManagerPhone: {
				 required: true,
				 minlength: 10
			 },
			txtRsVerticalManagerEmail: {
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
			 }
	 	},
submitHandler: function (form) 
	  {   

    var id = $("#hidRsVerticalManagerId").val();
	var name = $("#txtRsVerticalManagerName").val();
	var email = $("#txtRsVerticalManagerEmail").val();
	var phone = $("#txtRsVerticalManagerPhone").val();
	var password = $("#txtPassword").val();
	var VerticalList = $("#listVertical").val();

        var form="#frmEntry";

        $.ajax({
		type: "POST",
		url: base_url + "pramaan/save_rs_vertical_manager_detail",
		data:
		{
			'id' : id,
			'name' : name,
			'email' : email,
			'phone' : phone,
			'password' : password,
			'VerticalList' : VerticalList
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
						window.location.href = base_url + "pramaan/rs_vertical_managers/";
					});
			}
			else
			{
				/*swal({
					title: "",
					type: "error",
					text: data.message + "!",
					confirmButtonColor: "#d9534f",
					confirmButtonText: 'OK'
				});*/

				$.each(data.errors, function(key, val) 
                    {
                    	if(key=="name")
                    		 key = "txtRsVerticalManagerName";
                        else if(key=="email")
                             key = "txtRsVerticalManagerEmail";
                        else if(key=="phone")
                             key = "txtRsVerticalManagerPhone";
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

	/*var varVerticalListText = $("#hidVerticalIdList").val();
	var selectArray = varVerticalListText.split(",");
	$('#listVertical').val(selectArray);*/
});

$('#listVertical').select2();
</script>
