<style type="text/css">
	/**
	 * @author  George Martin <george.s@navriti.com>
	 * @desc  	Instructions
	 * @date  	March 2017
	*/
	select.input-sm
	{
	 	line-height: 10px;
	}
	.error_label, .validmark{color: red;}
</style>

<?php
//print "Part: " . $PartId . "<br><br>";
$SerialNo = 0;
?>

<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
	<div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("ist/dashboard","Dashboard");?></li>
				<li class="breadcrumb-item active">Instructions</li>
			</ol>
		</div>
	</div>
	<section id="basic-form-layouts">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="bordered-layout-basic-form">Instructions</h4>
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
							<form class="form form-horizontal form-bordered" id="frmEntry" method="post" enctype="multipart/form-data">
								<div class="form-body" style="margin-top: -25px;">
									<div id="divListAssessmentType" class="form-group row" style="display: block;">
										<label for="listAssessmentType" class="col-sm-3 label-control" stype="margin-top:10px;">Assessment Type<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<select name="listAssessmentType" id="listAssessmentType" required class="form-control">
												<?php
													$selected = 'selected="selected"';
													//echo '<option value="0" ' . $selected . '>General Instructions</option>';
													if ($AssessmentTypeList)
													{
														foreach ($AssessmentTypeList AS $AssessmentType)
														{
															$selected = (intval($AssessmentType['assessment_type_id']) == $AssessmentTypeId) ? 'selected="selected"' : '';
															echo '<option value="' . $AssessmentType['assessment_type_id'] . '" ' . $selected . '>' . $AssessmentType['assessment_type_name'] . '</option>';
														}
													}
												?>
											</select>
											<label id="lblAssessmentTypeError" style="color: red; display: none;">* Assessment Type is a required field!</label>
										</div>
									</div>

									<div id="divListPart" class="form-group row" style="display: block;">
										<label for="listPart" class="col-sm-3 label-control" stype="margin-top:10px;">Part<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<select name="listPart" id="listPart" required class="form-control">
												<?php
												$selected = ($PartId == "0") ? 'selected="selected"' : '';
												echo '<option value="0" ' . $selected . '>General Instructions</option>';
												if ($PartList)
												{
													foreach ($PartList AS $Part)
													{
														$selected = (intval($Part['part_id']) == $PartId) ? 'selected="selected"' : '';
														echo '<option value="' . $Part['part_id'] . '" ' . $selected . '>' . $Part['part_name'] . '</option>';
													}
												}
												?>
											</select>
											<label id="lblPartError" style="color: red; display: none;">* Part is a required field!</label>
										</div>
									</div>

									<div id="divListLanguage" class="form-group row" style="display: block;">
										<label for="listLanguage" class="col-sm-3 label-control" stype="margin-top:10px;">Language Version<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<select name="listLanguage" id="listLanguage" required class="form-control">
												<?php
												$LanguageCode = "en";
												$LanguageName = "";
												$selected = ($LanguageId == "0") ? 'selected="selected"' : '';
												echo '<option value="0" ' . $selected . '>Select Language Version</option>';
												if ($LanguageList)
												{
													$lCount = 0;
												    foreach ($LanguageList AS $Language)
													{
														$lCount++;
														$selected = "";
														if (($Language['language_id'] == $LanguageId))
														{
															$LanguageCode = $Language['language_code'];
															$LanguageName = $Language['language_name'];
															$selected     = 'selected="selected"';
														}

														echo '<option value="' . $Language['language_id'] . '" ' . $selected . '>' . $Language['language_name'] . '</option>';
													}
												}
												?>
											</select>
											<label id="lblLanguageError" style="color: red; display: none;">* Language is a required field!</label>
										</div>
									</div>
								</div>

								<div style="clear: both"></div>

								<div style="clear: both;"></div>

								<?php
								echo '<a class="btn btn-success btn-min-width mr-1 mb-1" onclick="ShowAddInstructionPopup()" style="color: white;margin-top:20px;margin-bottom:0px;"><i class="icon-android-add"></i> Add Instruction</a>';

								$ControlList = "";
								if (count($InstructionList) > 0)
								{
									$SerialNo = 0;
									foreach ($InstructionList as $Instruction)
									{
										$SerialNo = $Instruction['serial_no'];
										if ($SerialNo > 1) $ControlList .= ', ';
										$ControlName = "txtInstruction" . $SerialNo;
										$ControlVersionName = "txtInstructionVersion" . $SerialNo;
										$ControlErrorName = "lblInstructionError" . $SerialNo;
										$ControlList .= '"' . $ControlVersionName . '"';
								?>

										<div class="panel panel-default" style="margin-top: <?= $SerialNo == 1 ? "10px" : "40px" ?>;text-align: left;padding-left: 0px;">
											<div class="panel-heading">
												<h6 class="panel-title"><i class="icon-table"></i> Instruction. <?= $SerialNo ?>
													<a class="btn btn-danger" onclick="DeleteInstruction(<?= $SerialNo ?>)" style="float: right;cursor: pointer;padding:2px 2px 0px 2px;color:white;margin-left: 2px;" title="Delete Instruction <?= $SerialNo ?>">
														<i class="icon-android-close"></i>
													</a>
													<a class="btn btn-primary" onclick="ShowChangeInstructionPositionPopup(<?= $SerialNo ?>)" style="float: right;cursor: pointer;padding:2px 2px 0px 2px;color:white;margin-left: 2px;" title="Change Instruction <?= $SerialNo ?>'s Position">
														<i class="icon-ios-shuffle-strong"></i>
													</a>
												</h6>

											</div>
											<div class="panel-body">
												<div class="col-sm-12 col-md-12" style="border-style: solid;border-width: 1px;border-color: lightgrey;">
													<div class="form-group row">
														<label class="col-sm-3 label-control">English</label>
														<div class="col-sm-9">
															<textarea class="form-control" id="<?= $ControlName ?>" name="<?=$ControlName?>" style="height: 100px;"><?= $Instruction['instruction'] ?></textarea>
															<label id="<?= $ControlErrorName ?>" style="color: red; display: none;"></label>
														</div>
													</div>

													<div class="form-group row" style="display: <?=$LanguageId > 1 ? "block" : "none;" ?>">
														<label class="col-sm-3 label-control"><?= $LanguageName ?><span class='validmark'>*</span></label>
														<div class="col-sm-9">
															<div>
																<textarea class="form-control" id="<?= $ControlVersionName ?>" name="<?= $ControlVersionName ?>" style="height: 100px;"><?= $Instruction['version_instruction'] ?></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div style="clear: both;"></div>
								<?php

									}
								}
								?>

								<div style="clear: both"></div>

								<div id="divActions" class="form-actions" style="display: <?= count($InstructionList) > 0 ? "block" : "none" ?>;">
									<button id="btnSaveInstructions" type="submit" class="btn btn-primary" style="margin-top: 10px;"><i class="icon-check2"></i> Save Instructions</button>
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

<div id="divAddInstructionPopup" class="modal fade bs-example-modal-lg" id="modal_form" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content"  style="width:120%;margin-top:20%;" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 id="hdrPopupTitle" class="modal-title">Add Instruction</h3>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label for="txtInstructionPopup" class="col-sm-1 label-control" style="margin-top: 5px;">Instruction</label>
					<div class="col-sm-11">
						<textarea class="form-control" id="txtInstructionPopup" name="txtInstructionPopup" style="height: 100px;"></textarea>
						<label id="txtInstructionPopupError" style="color: red; display: none;"></label>
					</div>
				</div>

				<div class="form-group row">
					<label for="txtPosition" class="col-sm-1 label-control" style="margin-top: 5px;">Position</label>
					<div class="col-sm-11">
						<input class="form-control" id="txtPosition" name="txtPosition"/>
					</div>
				</div>

				<div style="clear: both;"></div>
			</div>

			<div class="modal-footer">
				<a class="btn btn-success" onclick="AddInstruction();" title="Add Instruction" style="color:white;">Add</a>
				<button id="btnCancel" class="btn btn-danger" data-dismiss="modal">Exit</button>
			</div>
		</div>
	</div>
</div>

<div id="divInstructionPositionPopup" class="modal fade bs-example-modal-lg" id="modal_form" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content"  style="width:100%;margin-top:10%;" >
			<input type="hidden" id="hidPositionFrom" name="hidPositionFrom" value=""/>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Change Instruction Position</h3>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label class="col-sm-3 label-control" style="margin-top: 5px;">Instruction</label>
					<div class="col-sm-9">
						<textarea class="form-control" id="txtInstructionPosition" name="txtInstructionPosition" style="height: 100px;" disabled="disabled" onkeydown="return false;"></textarea>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 label-control" style="margin-top: 5px;">Valid Positions</label>
					<div class="col-sm-9">
						<input class="form-control" id="txtValidPositions" name="txtValidPositions" value="<?= $SerialNo > 1 ? "1 to " . $SerialNo : "1" ?>" disabled="disabled" onkeydown="return false;"/>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 label-control" style="margin-top: 5px;">Change Position To</label>
					<div class="col-sm-9">
						<input class="form-control" id="txtPositionTo" name="txtPositionTo"/>
						<label id="lblPositionToError" style="color: red; display: none;"></label>
					</div>
				</div>

				<div style="clear: both;"></div>
			</div>

			<div class="modal-footer">
				<a class="btn btn-success" onclick="ChangeInstructionPosition();" title="Change Instruction Position" style="color:white;">Change Position</a>
				<button id="btnPositionChangeExit" class="btn btn-danger" data-dismiss="modal">Exit</button>
			</div>
		</div>
	</div>
</div>

<!--BEGIN: 	GOOGLE MULTI LANGUAGE INPUT TOOL IMPLEMENTATION-->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript">
	var translControl;
	var varLanguageCode = '<?= $LanguageCode ?>';
	function onLoad()
	{
		var options = {
			sourceLanguage: 'en',
			destinationLanguage: [ varLanguageCode ],
			transliterationEnabled: true,
			shortcutKey: 'ctrl+g'
		};

		if (varLanguageCode != 'en')
		{
			translControl = new google.elements.transliteration.TransliterationControl(options);
			translControl.makeTransliteratable( <?= "[" . $ControlList . "]" ?> );
		}
	}

	google.setOnLoadCallback(onLoad);
</script>
<!--END: 	GOOGLE MULTI LANGUAGE INPUT TOOL IMPLEMENTATION-->

<script>
	$(document).ready(function()
	{
		$("#txtPositionTo").keypress(function (event) {
			return /\d/.test(String.fromCharCode(event.keyCode));
		});

		$("#txtPosition").keypress(function (event) {
			return /\d/.test(String.fromCharCode(event.keyCode));
		});

		$("#listAssessmentType").change(function() {
			document.location.href = base_url + "content/instructions/" + this.value + "/" + $("#listPart").val() + "/" + $("#listLanguage").val();
		});

		$("#listPart").change(function() {
			document.location.href = base_url + "content/instructions/" + $("#listAssessmentType").val() + "/" + this.value + "/" + $("#listLanguage").val();
		});

		$("#listLanguage").change(function() {
			document.location.href = base_url + "content/instructions/" + $("#listAssessmentType").val() + "/" + $("#listPart").val() + "/" + this.value;
		});

		$("#btnSaveInstructions").click(function(e)
		{
			e.preventDefault();
			var assessment_type_id = $("#listAssessmentType").val();
			var part_id = $("#listPart").val();
			var language_id = $("#listLanguage").val();
			var instruction_list = [];
			var instruction_version_list = [];

			for(var i = 1; i <= <?= $SerialNo ?>; i++)
			{
				instruction_list.push($("#txtInstruction" + i).val());
				instruction_version_list.push($("#txtInstructionVersion" + i).val());
			}

			$.ajax({
				type: "POST",
				url: base_url + "content/save_instructions",
				data:
				{
					'assessment_type_id': assessment_type_id,
					'part_id': part_id,
					'language_id': language_id,
					'instruction_list[]' : instruction_list,
					'instruction_version_list[]' : instruction_version_list
				},
				dataType: 'json',
				success: function (result_data)
				{
					swal(
						{
							title: "",
							text: "Instructions saved successfully!",
							confirmButtonColor: "#d9534f",
							confirmButtonText: 'OK',
							closeOnConfirm: true,
							closeOnCancel: true
						},
						function(confirmed)
						{
							document.location.href = base_url + "content/instructions/" + assessment_type_id + "/" + part_id + "/" + language_id;
						}
					);
				},
				error: function ()
				{
					alert("Error Occurred");
				}
			});
		});
	});

	function ValidateSaveInputs()
	{
		var varReturnValue = true;
		var varFocus = false;

		if ($("#listPart option:selected").index() < 1)
		{
			$("#lblList").text('* Select a language to download its template!');
			$("#lblLanguageError").show();
			$("#listLanguage").focus();
			return;
		}

		for(var i = 1; i <= <?= $SerialNo ?>; i++)
		{
			instruction_list.push($("#txtInstruction" + i).val());
			instruction_version_list.push($("#txtInstructionVersion" + i).val());
		}
	}
	
	function LoadInstructions()
	{
		document.location.href = base_url + "content/instructions/" + $("#listAssessmentType").val() + "/" + $("#listPart").val() + "/" + $("#listLanguage").val();
	}

	function ShowAddInstructionPopup()
	{
		var varTitle = "Add Instruction";
		switch($("#listPart").val())
		{
			case "0":
				varTitle = "Add General Instruction";
				break;

			case "1":
				varTitle = "Add Part A Instruction";
				break;

			case "2":
				varTitle = "Add Part B Instruction";
				break;

			case "3":
				varTitle = "Add Part C Instruction";
				break;

			case "-1":
				varTitle = "Add Sample Instruction";
				break;
		}

		$("#hdrPopupTitle").html(varTitle);
		$("#txtInstructionPopup").val('');
		$("#txtPosition").val('');
		$("#divAddInstructionPopup").modal({ show: true });
	}

	function DeleteInstruction(sno)
	{
		var assessment_type_id = $("#listAssessmentType").val();
		var part_id = $("#listPart").val();
		var language_id = $("#listLanguage").val();

		swal(
			{
				title: "",
				text: "Are you sure, you want to delete this instruction?",
				showCancelButton: true,
				confirmButtonColor: "#d9534f",
				confirmButtonText: "Yes, delete!",
				cancelButtonText: "No, Cancel!",
				closeOnConfirm: true,
				closeOnCancel: true
			},
			function(isConfirm)
			{
				if (isConfirm)
				{
					$.ajax({
						type: "POST",
						url: base_url + "content/delete_instruction",
						data:
						{
							'assessment_type_id': assessment_type_id,
							'part_id': part_id,
							'sno': sno
						},
						dataType: 'json',
						success: function (result_data)
						{
							var varMsg = parseInt(result_data) > 0 ? "Instruction deleted successfully!" : "Instruction could not be deleted!";
							swal(
								{
									title: "",
									text: varMsg,
									confirmButtonColor: "#d9534f",
									confirmButtonText: 'OK',
									closeOnConfirm: true,
									closeOnCancel: true
								},
								function(confirmed)
								{
									document.location.href = base_url + "content/instructions/" + assessment_type_id + "/" + part_id + "/" + language_id;
								}
							);
						},
						error: function ()
						{
							alert("Error Occurred");
						}
					});
				}
			}
		);
	}

	function AddInstruction()
	{
		var assessment_type_id = $("#listAssessmentType").val();
		var part_id = $("#listPart").val();
		var instruction = $("#txtInstructionPopup").val();
		var sno = $("#txtPosition").val();
		var language_id = $("#listLanguage").val();

		$.ajax({
			type: "POST",
			url: base_url + "content/add_instruction",
			data:
			{
				'assessment_type_id': assessment_type_id,
				'part_id': part_id,
				'instruction': instruction,
				'sno': sno
			},
			dataType: 'json',
			success: function (result_data)
			{
				var varMsg = parseInt(result_data) > 0 ? "Instruction added successfully!" : "Instruction could not be added!";
				swal(
					{
						title: "",
						text: varMsg,
						confirmButtonColor: "#d9534f",
						confirmButtonText: 'OK',
						closeOnConfirm: true,
						closeOnCancel: true
					},
					function(confirmed)
					{
						document.location.href = base_url + "content/instructions/" + assessment_type_id + "/" + part_id + "/" + language_id;
					}
				);
			},
			error: function ()
			{
				alert("Error Occurred");
			}
		});
	}

	function ShowChangeInstructionPositionPopup(Sno)
	{
		$("#hidPositionFrom").val(Sno);
		$("#txtInstructionPosition").text($("#txtInstruction" + Sno).val());
		$("#txtPositionTo").val('');
		$("#divInstructionPositionPopup").modal({ show: true });
	}

	function ChangeInstructionPosition()
	{
		var InstructionCount = <?= $SerialNo ?>;
		var assessment_type_id = $("#listAssessmentType").val();
		var part_id = $("#listPart").val();
		var position_from = $("#hidPositionFrom").val();
		var position_to = $("#txtPositionTo").val();
		var language_id = $("#listLanguage").val();
		$("#lblPositionToError").hide();

		if (parseInt(position_to) < 1 || parseInt(position_to) > InstructionCount)
		{
			$("#lblPositionToError").text('* Invalid Position!');
			$("#lblPositionToError").show();
			$("#txtPositionTo").focus();
			return;
		}

		swal(
			{
				title: "",
				text: "Are you sure, you want to change the position of this instruction?",
				showCancelButton: true,
				confirmButtonColor: "#d9534f",
				confirmButtonText: "Yes, change!",
				cancelButtonText: "No, Cancel!",
				closeOnConfirm: true,
				closeOnCancel: true
			},
			function(isConfirm)
			{
				if (isConfirm)
				{
					$.ajax({
						type: "POST",
						url: base_url + "content/change_instruction_position",
						data:
						{
							'assessment_type_id': assessment_type_id,
							'part_id': part_id,
							'position_from': position_from,
							'position_to': position_to
						},
						dataType: 'json',
						success: function (result_data)
						{
							var varStatus = parseInt(result_data['status']);
							swal(
								{
									title: "",
									text: result_data['message'],
									confirmButtonColor: "#d9534f",
									confirmButtonText: 'OK',
									closeOnConfirm: true,
									closeOnCancel: true
								},
								function(confirmed)
								{
									if (varStatus > 0)
										document.location.href = base_url + "content/instructions/" + assessment_type_id + "/" + part_id + "/" + language_id;
								}
							);
						},
						error: function ()
						{
							alert("Error Occurred");
						}
					});
				}
				else
				{
					$("#btnPositionChangeExit").click();
				}
			}
		);
	}
</script>
