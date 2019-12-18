<style type="text/css">
	/**
	 * @author  George Martin <george.s@navriti.com>
	 * @desc  	Add / Edit Academic Major
	 * @date  	March 2017
	*/
	select.input-sm
	{
	 	line-height: 10px;
	}
	.error_label, .validmark{color: red;}
</style>

<div class="inner">
	<?php
	$strInterestTypeCode = '';
	if (isset($ResponseData[0]['interest_type_code']))
		$strInterestTypeCode = $ResponseData[0]['interest_type_code'];

	$intAcademicMajorId = 0;
	if (isset($ResponseData[0]['academic_major_id']))
		$intAcademicMajorId = intval($ResponseData[0]['academic_major_id']);
	?>

	<h4><?= (($intAcademicMajorId > 0) ? "Edit " : "Add ") . $title . ((($intAcademicMajorId > 0) ? (isset($ResponseData[0]['academic_major_name']) ? (" - " . $ResponseData[0]['academic_major_name']) : "")  : "")) ?></h4>

	<small>
	  <ul class="breadcrumb">
		  <li><?php echo anchor("pramaan/dashboard","Dashboard");?> </li>
		  <li><?php echo anchor($parent_page, $parent_page_title);?></li>
		  <li class="active"><?=(($intAcademicMajorId > 0) ? "Edit " : "Add ") . $title ?></li>
	  </ul>
	</small>

	<hr/>
    <form id="frmEntry" method="post" class="form-horizontal" style="padding-top:10px;">
		<input type="hidden" id="hidAcademicMajorId" name="hidAcademicMajorId" value="<?= $intAcademicMajorId ?>"/>
		<div class="row form-box">
		   	<div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="txtAcademicMajorName" class="col-sm-4 control-label">Name<span class='validmark'>*</span></label>
                    <div class="col-sm-8">
						<input type="text" class="form-control" id="txtAcademicMajorName" name="txtAcademicMajorName" value="<?= isset($ResponseData[0]['academic_major_name']) ? $ResponseData[0]['academic_major_name'] : "" ?>" placeholder="Enter Academic Major Name"/>
						<span class="error_label"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="txtAcademicMajorCode" class="col-sm-4 control-label">Code<span class='validmark'>*</span></label>
                    <div class="col-sm-8">
                    	<input type="text" class="form-control" id="txtAcademicMajorCode" name="txtAcademicMajorCode" maxlength="<?= PHONE_MAX?>" value="<?= isset($ResponseData[0]['academic_major_code']) ? $ResponseData[0]['academic_major_code'] : "" ?>" placeholder="Enter Academic Major Code"/>
						<span class="error_label"></span>
                    </div>
                </div>

				<div class="form-group">
					<label for="listInterestType" class="col-sm-4 control-label">Interest Type<span class='validmark'>*</span></label>
					<div class="col-sm-8">
						<select id="listInterestType" name="listInterestType" class="form-control">
							<option value="" <?= $strInterestTypeCode == '' ? 'selected="selected"' : '' ?> >Select Interest Type</option>
							<?php
							if (isset($InterestTypeList))
							{
								foreach ($InterestTypeList AS $row)
								{
									echo '<option value="' . $row['interest_type_code'] . '"' . ($strInterestTypeCode == $row['interest_type_code'] ? 'selected="selected"' : '') . '>' . $row['interest_type_name'] . '</option>';
								}
							}
							?>
						</select>
						<span class="error_label"></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-4 control-label"></label>
					<div class="col-sm-8" style="margin-top:25px;">
						<button class="btn btn-success" type="submit">Save</button>
						<button class="btn btn-primary" type="reset">Reset</button>
					</div>
				</div>
			</div>
        </div>
	</form>
</div><!-- inner -->

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
				txtAcademicMajorName:
				{
					required: "Please enter name",
				},
				txtAcademicMajorCode:
				{
					required: "Please enter code",
				}
			},
			rules: {
				txtAcademicMajorName: {
					required: true,
					minlength: 3
				},
				txtAcademicMajorCode: {
					required: true,
					minlength: 3
				}
			},
			highlight: function(element)
			{
				$(element).addClass('error_label');
			},
			unhighlight: function(element)
			{
				$(element).removeClass('error_label');
			},
			submitHandler: function (form)
			{
				var id = $("#hidAcademicMajorId").val();
				var name = $("#txtAcademicMajorName").val();
				var code = $("#txtAcademicMajorCode").val();
				var interest_type_code = $("#listInterestType").val();

				var form="#frmEntry";

				$.ajax({
					type: "POST",
					url: base_url + "pramaan/save_academic_major_detail",
					data:
					{
						'id' : id,
						'name' : name,
						'code' : code,
						'interest_type_code' : interest_type_code
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
									window.location.href = base_url + "pramaan/academic_majors/";
								});
						}
						else
						{
							$.each(data.errors, function(key, val)
							{
								if(key=="name")
									key = "txtAcademicMajorName";
								else if(key=="code")
									key = "txtAcademicMajorCode";

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
