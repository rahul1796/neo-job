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
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/rs_sectors","Sectors");?></li>
                <li class="breadcrumb-item active"><?= (($id > 0) ? "Edit " : "Add ") . $title ?></li>
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
                                <?php
                                $VerticalManagerId = "0";
                                if (isset($ResponseData[0]['rs_vertical_manager_id']))
                                    $VerticalManagerId = $ResponseData[0]['rs_vertical_manager_id'];
                                ?>
                                <input type="hidden" id="hidRsSectorManagerId" name="hidRsSectorManagerId" value="<?= isset($ResponseData[0]['rs_sector_manager_id']) ? $ResponseData[0]['rs_sector_manager_id'] : "0" ?>"/>
                                <input type="hidden" id="hidSectorIdList" name="hidSectorIdList" value="<?= isset($ResponseData[0]['sector_id_list']) ? $ResponseData[0]['sector_id_list'] : "" ?>"/>
                                <div class="form-body">
                                    <div class="form-group row" style="display:<?= (intval($user_group_id) == 14 ? "block" : "none") ?>">
                                        <label for="listVerticalManager" class="col-sm-3 label-control">RS Vertical Manager<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <select name="listVerticalManager" id="listVerticalManager" class="form-control"  placeholder="" required style="width:100%;">
                                                <option value="" <?php $VerticalManagerId == '0' ? ' selected="selected"' : '' ?> >-Select RS Vertical Manager-</option>
                                                <?php
                                                if (isset($vertical_manager_list)) {
                                                    foreach ($vertical_manager_list as $row) {
                                                        echo '<option value="' . $row['rs_vertical_manager_id'] . '"' . ($VerticalManagerId == intval($row['rs_vertical_manager_id']) ? ' selected="selected"' : '') . '>' . $row['rs_vertical_manager_name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="txtRsSectorManagerName" class="col-sm-3 label-control">Name<span class='validmark'>*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="txtRsSectorManagerName" name="txtRsSectorManagerName" value="<?= isset($ResponseData[0]['rs_sector_manager_name']) ? $ResponseData[0]['rs_sector_manager_name'] : "" ?>" placeholder="Enter RS Sector Manager Name"/>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="txtRsSectorManagerPhone" class="col-sm-3 label-control">Phone<span class='validmark'>*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="txtRsSectorManagerPhone" name="txtRsSectorManagerPhone" maxlength="<?= PHONE_MAX?>" value="<?= isset($ResponseData[0]['rs_sector_manager_phone']) ? $ResponseData[0]['rs_sector_manager_phone'] : "" ?>" placeholder="Enter RS Sector Manager Phone"/>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="txtRsSectorManagerEmail" class="col-sm-3 label-control">Email<span class='validmark'>*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="txtRsSectorManagerEmail" name="txtRsSectorManagerEmail" maxlength="<?= EMAIL_MAX?>" value="<?= isset($ResponseData[0]['rs_sector_manager_email']) ? $ResponseData[0]['rs_sector_manager_email'] : "" ?>" placeholder="Enter RS Sector Manager Email"/>
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
                                        <label for="listSector" class="col-sm-3 label-control">Sectors<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <select name="SectorList[]" id="listSector" multiple="multiple" placeholder="" class="form-control select2" required style="width:100%;">
                                                <?php
                                                if (isset($sector_list))
                                                    foreach ($sector_list as $row) echo "<option value=" . $row['sector_id'] . ">" . $row['sector_name'] . "</option>\n";
                                                ?>
                                            </select>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <script>
                                        var varIdList = '<?= isset($ResponseData[0]['sector_id_list']) ? $ResponseData[0]['sector_id_list'] : '' ?>';
                                        var varIdArray = varIdList.split(',');
                                        $('#listSector').val(varIdArray);
                                    </script>

                                </div>

                                <div class="form-actions">
                                    <button type="reset" class="btn btn-warning mr-1"><i class="icon-cross2"></i> Reset</button>
                                    <button type="submit" class="btn btn-primary"  value="add" name="submit"><i class="icon-check2"></i>Save</button>
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
			
            listVerticalManager:
            {
               required: "Please select vertical manager",
            },
            txtRsSectorManagerName:
			{
				required: "Please enter name",
			},
			txtRsSectorManagerPhone:
			{
				required: "Please enter phone",
			},
			txtRsSectorManagerEmail:
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
	 	rules:
        {
            listVerticalManager:
            {
               required: true, 
            },
			txtRsSectorManagerName: {
				 required: true,
				 minlength: 3
			 },
			txtRsSectorManagerPhone: {
				 required: true,
				 minlength: 10
			 },
			txtRsSectorManagerEmail: {
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
            var id = $("#hidRsSectorManagerId").val();
            var name = $("#txtRsSectorManagerName").val();
            var email = $("#txtRsSectorManagerEmail").val();
            var phone = $("#txtRsSectorManagerPhone").val();
            var password = $("#txtPassword").val();
            var SectorList = $("#listSector").val();
            var VerticalManagerId = $("#listVerticalManager").val();

            var form="#frmEntry";

            $.ajax({
                type: "POST",
                url: base_url + "pramaan/save_rs_sector_manager_detail",
                data:
                {
                    'id' : id,
                    'name' : name,
                    'email' : email,
                    'phone' : phone,
                    'password' : password,
                    'SectorList' : SectorList,
                    'VerticalManagerId' : VerticalManagerId
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
                            window.location.href = base_url + "pramaan/rs_sector_managers/";
                        });
                    }
                    else
                    {
                        $.each(data.errors, function(key, val)
                        {
                            switch (key)
                            {
                                case "name":
                                    key = "txtRsSectorManagerName";
                                    break;

                                case "email":
                                    key = "txtRsSectorManagerEmail";
                                    break;

                                case "phone":
                                    key = "txtRsSectorManagerPhone";
                                    break;

                                case "password":
                                    key = "txtPassword";
                                    break;
                            }

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

            return false;
        }
	});
});


$('#listSector').select2();

</script>
