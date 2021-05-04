<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url().'adm-assets/vendors/js/forms/select/select2.full.min.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'adm-assets/js/scripts/forms/select/form-select2.min.js'?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/forms/selects/select2.min.css'?>">



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

<?php
     
    $region_options=array();
    foreach ($region_list as $row) 
    {
        $region_options[$row['id']]=$row['name'];
    }


    /*$region_id_array = str_replace(array('}','{'),'',$regional_manager_data['region_id_list']);

    $region_id = explode(',',$region_id_array);*/


?>
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
	<div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("pramaan/bd_regional_manager","Regional Managers");?></li>
				<li class="breadcrumb-item active">Edit Regional Manager</li>
			</ol>
		</div>
	</div>
	<section id="basic-form-layouts">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="bordered-layout-basic-form">Regional Manager Info</h4>
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
							<form class="form form-horizontal form-bordered" id="regional_manager_form" method="post">
								<div class="form-body">
									<div class="form-group row">
										<label for="region_name" class="col-sm-3 label-control">Region Name<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<?php echo form_dropdown('region_id[]',$region_options,'','id="region_id" class="form-control select2" placeholder="Select Region" multiple required');?>
											<span class="error_label"></span>
										</div>
									</div>

									<script>

										var varIdList = '<?= isset($regional_manager_data['region_id_list']) ? $regional_manager_data['region_id_list'] : '' ?>';
										var varIdList = varIdList.replace(/[{()}]/g, '');
										var varIdArray = varIdList.split(',');
										$('#region_id').val(varIdArray);

										$('#region_id').select2();

									</script>


									<div class="form-group row">
										<input type="hidden" id="parent_id" name="parent_id" value="<?= $parent_id;?>"/>
										<input type="hidden" id="user_id" name="user_id" value="<?= $user_id;?>"/>
										<input type="hidden" id="user_id" name="id" value="<?= $user_id;?>"/>
										<!-- <input type="hidden" id="department_id" name="department_id" value="<?= $department_id;?>"/> -->
										<!--  <input type="hidden" id="bd_admin_id" name="id" value="0"/> -->
										<input type="hidden" id="user_group_id" name="user_group_id" value="<?php echo $user_group_id;?>"/>
										<label for="pname" class="col-sm-3 label-control">Name<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="pname" value="<?php echo $regional_manager_data['name']; ?>" id="pname">
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="phone" class="col-sm-3 label-control">Phone<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="phone" value="<?php echo $regional_manager_data['phone']; ?>" id="phone" maxlength="<?= PHONE_MAX?>">
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="email" class="col-sm-3 label-control">Email<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="email" value="<?php echo $regional_manager_data['email']; ?>" id="email" maxlength="<?= EMAIL_MAX?>">
											<span class="error_label"></span>
										</div>
									</div>
								</div>

								<div class="form-actions">
									<button type="reset" class="btn btn-warning mr-1"><i class="icon-cross2"></i> Reset</button>
									<button type="submit" class="btn btn-primary"  value="update" name="submit"><i class="icon-check2"></i>Save</button>
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
	$("#regional_manager_form").validate({
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
				required: "Please enter your name",
			},
			phone: 
			{
				required: "Please enter your phone",
			},
			email: 
			{
				required: "Please enter your email",
			},
			region_id:
			{
				required: "Please select a region",
			}
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
	     region_id:{
	     	required:true,
	     }
	 },
	 submitHandler: function (form) 
	 {
	 	var form="#regional_manager_form";
	      $.ajax({
	         type: "POST",
	         url: base_url+"pramaan/save_bd_regional_manager",
	         data: $('#regional_manager_form').serialize(),
	         dataType:'json',
	         success: function (data) 
	         {
	         //alert(data.msg_info);
	         	var form="#regional_manager_form";

	         		if(data.status==true)
               		{
                      //$(form)[0].reset();
                    swal({
                            title: "",
                             
                            text: data.msg_info + "!",
                            confirmButtonColor: "#5cb85c",
                            confirmButtonText: 'OK'
                        },
                        // window.location.href = base_url + 'pramaan/bd_heads/'+"<?=$parent_id ?>";
                        function (confirmed) {
                            window.location.href = base_url+'pramaan/bd_regional_manager';
                        });
                   
                    }

                else
                {
                    $.each(data.errors, function(key, val) 
                    {
                        $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
                    });
                    $("#regional_manager_form").valid();
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