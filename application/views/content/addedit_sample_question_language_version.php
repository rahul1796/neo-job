

<style type="text/css">
	/**
	 * @author  George Martin <george.s@navriti.com>
	 * @desc  	Add / Edit  sample questions of language version
	 * @date  	March 2017
	*/
	select.input-sm
	{
	 	line-height: 10px;
	}
	.error_label, .validmark{color: red;}
</style>
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
	<?php
	$QuestionImageUrl = base_url(QUESTION_IMAGES);

	$QuestionId = isset($ResponseData[0]['question_id']) ? intval($ResponseData[0]['question_id']) : 0;
	$LanguageCode = isset($ResponseData[0]['language_code']) ? $ResponseData[0]['language_code'] : 'en';
	$LanguageName = isset($ResponseData[0]['language_name']) ? $ResponseData[0]['language_name'] : 'English';
	$PartId = isset($ResponseData[0]['part_id']) ? intval($ResponseData[0]['part_id']) : 0;
	$SectionCode = isset($ResponseData[0]['section_code']) ? $ResponseData[0]['section_code'] : '-';
	$QuestionTypeId = isset($ResponseData[0]['question_type_id']) ? intval($ResponseData[0]['question_type_id']) : 0;
	$QuestionText = isset($ResponseData[0]['question_text']) ? $ResponseData[0]['question_text'] : "";
	$Option1Text = isset($ResponseData[0]['option1_text']) ? $ResponseData[0]['option1_text'] : "";
	$Option2Text = isset($ResponseData[0]['option2_text']) ? $ResponseData[0]['option2_text'] : "";
	$Option3Text = isset($ResponseData[0]['option3_text']) ? $ResponseData[0]['option3_text'] : "";
	$Option4Text = isset($ResponseData[0]['option4_text']) ? $ResponseData[0]['option4_text'] : "";
	$Option5Text = isset($ResponseData[0]['option5_text']) ? $ResponseData[0]['option5_text'] : "";

	$QuestionText = str_replace("<br>","\n",$QuestionText);
	$Option1Text = str_replace("<br>","\n",$Option1Text);
	$Option2Text = str_replace("<br>","\n",$Option2Text);
	$Option3Text = str_replace("<br>","\n",$Option3Text);
	$Option4Text = str_replace("<br>","\n",$Option4Text);
	$Option5Text = str_replace("<br>","\n",$Option5Text);
	?>
	<div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("ist/dashboard","Dashboard");?></li>
				<li class="breadcrumb-item"><?php echo anchor("content/sample_questions/", "Sample Questions");?></li>
				<li class="breadcrumb-item active">Add / Edit Sample Question Language Version</li>

			</ol>
		</div>
	</div>
	<section id="basic-form-layouts">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="bordered-layout-basic-form"><?= (($QuestionId > 0) ? "Edit " : "Add ") . $title ?> Info</h4>
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
								<input type="hidden" id="hidQuestionId" name="hidQuestionId" value="<?= $QuestionId ?>"/>

								<div class="form-body" style="margin-top: -25px;">
									<div id="divQuestionPaperTitle"  class="form-group row">
										<label for="txtQuestionPaperTitle" class="col-sm-3 label-control">Question Paper</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="txtQuestionPaperTitle" name="txtQuestionPaperTitle" value="Sample Question Paper" onkeydown="return false;" disabled="disabled"/>
										</div>
									</div>

									<div id="divPartName"  class="form-group row" style="display:block;">
										<label for="txtPartName" class="col-sm-3 label-control">Part</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="txtPartName" name="txtPartName" value="<?= isset($ResponseData[0]['part_name']) ? $ResponseData[0]['part_name'] : "" ?>" onkeydown="return false;" disabled="disabled"/>
										</div>
									</div>

									<div id="divSectionName"  class="form-group row" style="display:block;">
										<label for="txtSectionName" class="col-sm-3 label-control">Section</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="txtSectionName" name="txtSectionName" value="<?= isset($ResponseData[0]['section_name']) ? $ResponseData[0]['section_name'] : "" ?>" onkeydown="return false;" disabled="disabled"/>
										</div>
									</div>

									<div id="divQuestionTypeName"  class="form-group row" style="display:block;">
										<label for="txtQuestionTypeName" class="col-sm-3 label-control">Question Type</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="txtQuestionTypeName" name="txtQuestionTypeName" value="<?= isset($ResponseData[0]['question_type_name']) ? $ResponseData[0]['question_type_name'] : "" ?>" onkeydown="return false;" disabled="disabled"/>
										</div>
									</div>

									<div id="divListLanguage" class="form-group row" style="display: block;">
										<label for="listLanguage" class="col-sm-3 label-control" stype="margin-top:10px;">Language Version<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<select name="listLanguage" id="listLanguage" required class="form-control" onchange="javascript:listLanguage_OnChange()">
												<?php
												echo '<option value="-" selected="selected">Select Language Version</option>';
												if ($LanguageList) {
													foreach ($LanguageList AS $Language) {
														if ($Language['language_id'] == 1) continue;
														echo '<option value="' . $Language['language_code'] . '" >' . $Language['language_name'] . '</option>';
													}
												}
												?>
											</select>
											<label id="lblLanguageError" style="color: red; display: none;"></label>
										</div>
									</div>
								</div>

								<div style="clear: both"></div>

								<div id="divQuestion" class="panel panel-default" style="margin-top: 30px;text-align: left;padding-left: 0px;">
									<div class="panel-heading">
										<h6 class="panel-title"><i class="icon-table"></i> Question</h6>
									</div>
									<div class="panel-body">
										<div class="col-sm-12 col-md-12" style="border-style: solid;border-width: 1px;border-color: lightgrey;">
											<div class="form-group row">
												<label for="txtQuestionText" class="col-sm-3 label-control">English</label>
												<div class="col-sm-9">
													<textarea class="form-control" id="txtQuestionText" name="txtQuestionText" style="height: 100px;" onkeydown="return false;" disabled="disabled"><?= $QuestionText ?></textarea>
													<label id="lblQuestionTextError" style="color: red; display: none;"></label>
												</div>
											</div>

											<div id="divQuestionVersionText" class="form-group row" style="display: none;">
												<label id="lblQuestionVersionText" class="col-sm-3 label-control">Version<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<div>
														<textarea class="form-control techprevue-google-transliterate-API" contentEditable="true" id="txtQuestionVersionText" name="txtQuestionVersionText" style="height: 100px;"></textarea>
													</div>
													<label id="lblQuestionVersionTextError" style="color: red; display: none;"></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div style="clear: both"></div>

								<div id="divOption1" class="panel panel-default" style="margin-top: 30px;text-align: left;padding-left: 0px;">
									<div class="panel-heading">
										<h6 class="panel-title"><i class="icon-table"></i> Option 1</h6>
									</div>
									<div class="panel-body">
										<div class="col-sm-12 col-md-12" style="border-style: solid;border-width: 1px;border-color: lightgrey;">
											<div class="form-group row">
												<label for="txtOption1Text" class="col-sm-3 label-control">English</label>
												<div class="col-sm-9">
													<textarea class="form-control" id="txtOption1Text" name="txtOption1Text" style="height: 50px;" onkeydown="return false;" disabled="disabled"><?= $Option1Text ?></textarea>
													<label id="lblOption1TextError" style="color: red; display: none;"></label>
												</div>
											</div>

											<div id="divOption1VersionText" class="form-group row" style="display: none;">
												<label id="lblOption1VersionText" class="col-sm-3 label-control">Version<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<textarea class="form-control techprevue-google-transliterate-API" contentEditable="true" id="txtOption1VersionText" name="txtOption1VersionText" style="height: 50px;"></textarea>
													<label id="lbltxtOption1VersionTextError" style="color: red; display: none;"></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div style="clear: both"></div>

								<div id="divOption2" class="panel panel-default" style="margin-top: 30px;text-align: left;padding-left: 0px;">
									<div class="panel-heading">
										<h6 class="panel-title"><i class="icon-table"></i> Option 2</h6>
									</div>
									<div class="panel-body">
										<div class="col-sm-12 col-md-12" style="border-style: solid;border-width: 1px;border-color: lightgrey;">
											<div class="form-group row">
												<label for="txtOption2Text" class="col-sm-3 label-control">English</label>
												<div class="col-sm-9">
													<textarea class="form-control techprevue-google-transliterate-API" id="txtOption2Text" name="txtOption2Text" style="height: 50px;" onkeydown="return false;" disabled="disabled"><?= $Option2Text ?></textarea>
													<label id="lblOption2TextError" style="color: red; display: none;"></label>
												</div>
											</div>

											<div id="divOption2VersionText" class="form-group row" style="display: none;">
												<label id="lblOption2VersionText" class="col-sm-3 label-control">Version<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<textarea class="form-control techprevue-google-transliterate-API" contentEditable="true" id="txtOption2VersionText" name="txtOption2VersionText" style="height: 50px;"></textarea>
													<label id="lbltxtOption2VersionTextError" style="color: red; display: none;"></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div style="clear: both"></div>

								<div id="divOption3" class="panel panel-default" style="margin-top: 30px;text-align: left;padding-left: 0px;">
									<div class="panel-heading">
										<h6 class="panel-title"><i class="icon-table"></i> Option 3</h6>
									</div>
									<div class="panel-body">
										<div class="col-sm-12 col-md-12" style="border-style: solid;border-width: 1px;border-color: lightgrey;">
											<div class="form-group row">
												<label for="txtOption3Text" class="col-sm-3 label-control">English</label>
												<div class="col-sm-9">
													<textarea class="form-control" id="txtOption3Text" name="txtOption3Text" style="height: 50px;" onkeydown="return false;" disabled="disabled"><?= $Option3Text ?></textarea>
													<label id="lblOption3TextError" style="color: red; display: none;"></label>
												</div>
											</div>

											<div id="divOption3VersionText" class="form-group row" style="display: none;">
												<label id="lblOption3VersionText" class="col-sm-3 label-control">Version<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<textarea class="form-control techprevue-google-transliterate-API" contentEditable="true" id="txtOption3VersionText" name="txtOption3VersionText" style="height: 50px;"></textarea>
													<label id="lbltxtOption3VersionTextError" style="color: red; display: none;"></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div style="clear: both"></div>

								<div id="divOption4" class="panel panel-default" style="margin-top: 30px;text-align: left;padding-left: 0px;">
									<div class="panel-heading">
										<h6 class="panel-title"><i class="icon-table"></i> Option 4</h6>
									</div>
									<div class="panel-body">
										<div class="col-sm-12 col-md-12" style="border-style: solid;border-width: 1px;border-color: lightgrey;">
											<div class="form-group row">
												<label for="txtOption4Text" class="col-sm-3 label-control">English</label>
												<div class="col-sm-9">
													<textarea class="form-control" id="txtOption4Text" name="txtOption4Text" style="height: 50px;" onkeydown="return false;" disabled="disabled"><?= $Option4Text ?></textarea>
													<label id="lblOption4TextError" style="color: red; display: none;"></label>
												</div>
											</div>

											<div id="divOption4VersionText" class="form-group row" style="display: none;">
												<label id="lblOption4VersionText" class="col-sm-3 label-control">Version<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<textarea class="form-control techprevue-google-transliterate-API" contentEditable="true" id="txtOption4VersionText" name="txtOption4VersionText" style="height: 50px;"></textarea>
													<label id="lbltxtOption4VersionTextError" style="color: red; display: none;"></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div style="clear: both"></div>

								<div id="divOption5" class="panel panel-default" style="margin-top: 30px;text-align: left;padding-left: 0px;<?= $QuestionTypeId == 2 ? "display:none;" : "" ?>">
									<div class="panel-heading">
										<h6 class="panel-title"><i class="icon-table"></i> Option 5</h6>
									</div>
									<div class="panel-body">
										<div class="col-sm-12 col-md-12" style="border-style: solid;border-width: 1px;border-color: lightgrey;">
											<div class="form-group row">
												<label for="txtOption5Text" class="col-sm-3 label-control">English</label>
												<div class="col-sm-9">
													<textarea class="form-control" id="txtOption5Text" name="txtOption4Text" style="height: 50px;" onkeydown="return false;" disabled="disabled"><?= $Option5Text ?></textarea>
													<label id="lblOption5TextError" style="color: red; display: none;"></label>
												</div>
											</div>

											<div id="divOption5VersionText" class="form-group row" style="display: none;">
												<label id="lblOption5VersionText" class="col-sm-3 label-control">Version<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<textarea class="form-control techprevue-google-transliterate-API" contentEditable="true" id="txtOption5VersionText" name="txtOption5VersionText" style="height: 50px;"></textarea>
													<label id="lbltxtOption5VersionTextError" style="color: red; display: none;"></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div style="clear: both"></div>

								<div id="divActions" class="form-actions" style="margin-left: 25%; display: none;">
								  <button id="btnSubmit" type="submit" class="btn btn-primary" style="margin-top: 10px;"><i class="icon-check2"></i>Save</button>
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

<!--BEGIN: 	GOOGLE MULTI LANGUAGE INPUT TOOL IMPLEMENTATION-->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript">
	var langArray = [ 'gu', 'hi', 'kn', 'ml', 'ta', 'te', 'mr', 'bn' ];
	google.load("elements", "1", { packages: "transliteration" });

	var translControl;
	function onLoad()
	{
		var options = {
			sourceLanguage: 'en',	
			destinationLanguage: langArray,
			transliterationEnabled: true,
			shortcutKey: 'ctrl+g'
		};

		translControl = new google.elements.transliteration.TransliterationControl(options);
		translControl.makeTransliteratable([ "txtQuestionVersionText", "txtOption1VersionText", "txtOption2VersionText", "txtOption3VersionText", "txtOption4VersionText", "txtOption5VersionText" ]);
	}

	function listLanguage_OnChange()
	{
		$("#divQuestionVersionText").hide();
		$("#divOption1VersionText").hide();
		$("#divOption2VersionText").hide();
		$("#divOption3VersionText").hide();
		$("#divOption4VersionText").hide();
		$("#divOption5VersionText").hide();
		$("#divActions").hide();

		var dropdown = document.getElementById('listLanguage');
		var varSelectedIndex = $("#listLanguage option:selected").index();
		if (varSelectedIndex > 0)
		{
			if (translControl == undefined) onLoad();

			var varSelectedText = dropdown.options[dropdown.selectedIndex].text;
			var varSelectedValue = dropdown.options[dropdown.selectedIndex].value;
			translControl.setLanguagePair(google.elements.transliteration.LanguageCode.ENGLISH, varSelectedValue);

			var varVersionLabel = dropdown.options[dropdown.selectedIndex].text + ' <span class="validmark">*</span>';

			$("#lblQuestionVersionText").html(varVersionLabel);
			$("#lblOption1VersionText").html(varVersionLabel);
			$("#lblOption2VersionText").html(varVersionLabel);
			$("#lblOption3VersionText").html(varVersionLabel);
			$("#lblOption4VersionText").html(varVersionLabel);
			$("#lblOption5VersionText").html(varVersionLabel);

			$("#txtQuestionVersionText").val("");
			$("#txtOption1VersionText").val("");
			$("#txtOption2VersionText").val("");
			$("#txtOption3VersionText").val("");
			$("#txtOption4VersionText").val("");
			$("#txtOption5VersionText").val("");

			$("#divQuestionVersionText").show();
			$("#divOption1VersionText").show();
			$("#divOption2VersionText").show();
			$("#divOption3VersionText").show();
			$("#divOption4VersionText").show();
			$("#divOption5VersionText").show();

			$.ajax({
				url: base_url + "content/get_sample_question_detail_for_language/<?= $QuestionId ?>/" + varSelectedValue,
				type: "POST",
				data: {},
				dataType:'json',
				success: function (result)
				{
					if (result.length > 0)
					{
						$("#txtQuestionVersionText").val(result[0]['question_text']);
						$("#txtOption1VersionText").val(result[0]['option1_text']);
						$("#txtOption2VersionText").val(result[0]['option2_text']);
						$("#txtOption3VersionText").val(result[0]['option3_text']);
						$("#txtOption4VersionText").val(result[0]['option4_text']);
						$("#txtOption5VersionText").val(result[0]['option5_text']);
					}

					$("#divActions").show();
					$("#txtQuestionVersionText").focus();
				},
				error: function ()
				{
					alert("Error Occurred");
				}
			});
		}
	}

	google.setOnLoadCallback(onLoad);
</script>
<!--END: 	GOOGLE MULTI LANGUAGE INPUT TOOL IMPLEMENTATION-->

<script>
$(document).ready(function()
{
	$("#frmEntry").on('submit',(function(e)
	{
		e.preventDefault();

		var dropdown = document.getElementById('listLanguage');

		var varSelectedIndex = $("#listLanguage option:selected").index();
		if (varSelectedIndex > 0)
		{
			var question_id = <?= $QuestionId ?>;
			var language_code = dropdown.options[dropdown.selectedIndex].value;
			var language_name = dropdown.options[dropdown.selectedIndex].text;
			var question = $("#txtQuestionVersionText").val();
			var option1 = $("#txtOption1VersionText").val();
			var option2 = $("#txtOption2VersionText").val();
			var option3 = $("#txtOption3VersionText").val();
			var option4 = $("#txtOption4VersionText").val();
			var option5 = $("#txtOption5VersionText").val();

			$.ajax({
				url: base_url + "content/save_sample_question_language_version_data",
				type: "POST",
				data: {
					'question_id'	: question_id,
					'language_code' : language_code,
					'question'		: question,
					'option1'		: option1,
					'option2'		: option2,
					'option3'		: option3,
					'option4'		: option4,
					'option5'		: option5
				},
				dataType:'json',
				success: function (result)
				{
					var varLangQuestionId = parseInt(result['language_question_id']);
					if (varLangQuestionId > 0)
					{
						swal({
								title: "",
								text: language_name + " Version for this sample question has been saved successfully!",
								confirmButtonColor: "#5cb85c",
								confirmButtonText: 'OK',
								closeOnConfirm: true,
								closeOnCancel: true
							},
							function(confirmed){
								$("#listLanguage").focus();
							});
					}
				},
				error: function ()
				{
					alert("Error Occurred");
				}
			});
		}
	}));
});

function ValidateInputs() {
	/*var varReturnValue = true;
	var varFocus = false;

	$("#lblPartError").hide();
	$("#lblSectionError").hide();
	$("#lblQuestionTypeError").hide();
	$("#lblQuestionTextError").hide();
	$("#lblOption1TextError").hide();
	$("#lblOption2TextError").hide();
	$("#lblOption3TextError").hide();
	$("#lblOption4TextError").hide();
	$("#lblOption5TextError").hide();
	$("#lblCorrectOptionError").hide();
	$("#lblMarksError").hide();

	var varQuestionTypeId = parseInt($("#listQuestionType").val());

	//PART
	if (document.getElementById("listPart").selectedIndex < 1) {
		$("#lblPartError").text('* Part is a required field!');
		$("#lblPartError").show();
		varReturnValue = false;
		if (!varFocus) {
			$("#listPart").focus();
			varFocus = true;
		}
	}

	//SECTION
	if (document.getElementById("listSection").selectedIndex < 1) {
		$("#lblSectionError").text('* Section is a required field!');
		$("#lblSectionError").show();
		varReturnValue = false;
		if (!varFocus) {
			$("#listSection").focus();
			varFocus = true;
		}
	}

	//QUESTION TYPE
	if (document.getElementById("listQuestionType").selectedIndex < 1) {
		$("#lblQuestionTypeError").text('* Question Type is a required field!');
		$("#lblQuestionTypeError").show();
		varReturnValue = false;
		if (!varFocus) {
			$("#listQuestionType").focus();
			varFocus = true;
		}
	}

	//QUESTION TEXT
	if ($("#txtQuestionText").val().trim() == '') {
		$("#lblQuestionTextError").text('* Question Text is a required field!');
		$("#lblQuestionTextError").show();
		varReturnValue = false;
		if (!varFocus) {
			$("#txtQuestionText").focus();
			varFocus = true;
		}
	}

	//MARKS
	if ($("#listQuestionType").val() == "2") {
		var varMarksStatus = true;
		if ($("#txtMarks").val().trim() == '') {
			$("#lblMarksError").text('* Marks is a required field!');
			varMarksStatus = false;
		}
		else {
			if (parseInt($("#txtMarks").val()) < 1) {
				$("#lblMarksError").text('* Marks must be greater than zero!');
				varMarksStatus = false;
			}
		}

		if (!varMarksStatus) {
			$("#lblMarksError").show();
			varReturnValue = false;
			if (!varFocus) {
				$("#txtMarks").focus();
				varFocus = true;
			}
		}
	}

	//OPTION 1 TEXT
	if ($("#txtOption1Text").val().trim() == '') {
		$("#lblOption1TextError").text('* Option 1 Text is a required field!');
		$("#lblOption1TextError").show();
		varReturnValue = false;
		if (!varFocus) {
			$("#txtOption1Text").focus();
			varFocus = true;
		}
	}

	//OPTION 2 TEXT
	if ($("#txtOption2Text").val().trim() == '') {
		$("#lblOption2TextError").text('* Option 2 Text is a required field!');
		$("#lblOption2TextError").show();
		varReturnValue = false;
		if (!varFocus) {
			$("#txtOption2Text").focus();
			varFocus = true;
		}
	}

	//OPTION 3 TEXT
	if ($("#txtOption3Text").val().trim() == '') {
		$("#lblOption3TextError").text('* Option 3 Text is a required field!');
		$("#lblOption3TextError").show();
		varReturnValue = false;
		if (!varFocus) {
			$("#txtOption3Text").focus();
			varFocus = true;
		}
	}

	//OPTION 4 TEXT
	if ($("#txtOption4Text").val().trim() == '') {
		$("#lblOption4TextError").text('* Option 4 Text is a required field!');
		$("#lblOption4TextError").show();
		varReturnValue = false;
		if (!varFocus) {
			$("#txtOption4Text").focus();
			varFocus = true;
		}
	}

	//OPTION 5 TEXT
	if (varQuestionTypeId == 1) {
		if ($("#txtOption5Text").val().trim() == '') {
			$("#lblOption5TextError").text('* Option 5 Text is a required field!');
			$("#lblOption5TextError").show();
			varReturnValue = false;
			if (!varFocus) {
				$("#txtOption5Text").focus();
				varFocus = true;
			}
		}
	}

	//CORRECT OPTION
	if ($("#listQuestionType").val() == "2") {
		if ($("input:radio[name='rdoCorrectOption']:checked").val() == undefined) {
			$("#lblCorrectOptionError").text('* Select Correct Option!');
			$("#lblCorrectOptionError").show();
			varReturnValue = false;
			if (!varFocus) {
				$("#rdoOption1").focus();
				varFocus = true;
			}
		}
	}*/

	return varReturnValue;
}
</script>
