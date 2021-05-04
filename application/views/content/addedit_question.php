<style type="text/css">
	/**
	 * @author  George Martin <george.s@navriti.com>
	 * @desc  	Add/Edit Question
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
	$QuestionImagePath = base_url(QUESTION_IMAGES);

	$QuestionPaperId = $question_paper_id;
	$QuestionId = isset($ResponseData[0]['question_id']) ? intval($ResponseData[0]['question_id']) : 0;
	$LanguageCode = isset($ResponseData[0]['language_code']) ? $ResponseData[0]['language_code'] : 'en';
	$LanguageName = isset($ResponseData[0]['language_name']) ? $ResponseData[0]['language_name'] : 'English';
	$PartId = isset($ResponseData[0]['part_id']) ? intval($ResponseData[0]['part_id']) : 0;
	$SectionCode = isset($ResponseData[0]['section_code']) ? $ResponseData[0]['section_code'] : '-';
	$QuestionTypeId = isset($ResponseData[0]['question_type_id']) ? intval($ResponseData[0]['question_type_id']) : 0;
	$QuestionText = isset($ResponseData[0]['question_text']) ? $ResponseData[0]['question_text'] : "";
	$QuestionText = str_replace("<br>","\n",$QuestionText);

	$Option1Text = isset($ResponseData[0]['option1_text']) ? $ResponseData[0]['option1_text'] : "";
	$Option1Text = str_replace("<br>","\n",$Option1Text);

	$Option2Text = isset($ResponseData[0]['option2_text']) ? $ResponseData[0]['option2_text'] : "";
	$Option2Text = str_replace("<br>","\n",$Option2Text);

	$Option3Text = isset($ResponseData[0]['option3_text']) ? $ResponseData[0]['option3_text'] : "";
	$Option3Text = str_replace("<br>","\n",$Option3Text);

	$Option4Text = isset($ResponseData[0]['option4_text']) ? $ResponseData[0]['option4_text'] : "";
	$Option4Text = str_replace("<br>","\n",$Option4Text);

	$Option5Text = isset($ResponseData[0]['option5_text']) ? $ResponseData[0]['option5_text'] : "";
	$Option5Text = str_replace("<br>","\n",$Option5Text);

	$CorrectOption = isset($ResponseData[0]['correct_option']) ? $ResponseData[0]['correct_option'] : "0";
	$ReverseValues = isset($ResponseData[0]['reverse_values']) ? intval($ResponseData[0]['reverse_values']) : 0;
	$Marks = isset($ResponseData[0]['marks']) ? $ResponseData[0]['marks'] : "1";
	if ($QuestionTypeId == 1) $Marks = "0";

	$QuestionImg = isset($ResponseData[0]['question_img']) ? $ResponseData[0]['question_img'] : '';
	$QuestionImgUrl = (trim($QuestionImg) != '') ? $QuestionImagePath . $QuestionImg : '';

	$OptionImg1 = isset($ResponseData[0]['option1_img']) ? $ResponseData[0]['option1_img'] : '';
	$OptionImg1Url = (trim($OptionImg1) != '') ? $QuestionImagePath . $OptionImg1 : '';

	$OptionImg2 = isset($ResponseData[0]['option2_img']) ? $ResponseData[0]['option2_img'] : '';
	$OptionImg2Url = (trim($OptionImg2) != '') ? $QuestionImagePath . $OptionImg2 : '';

	$OptionImg3 = isset($ResponseData[0]['option3_img']) ? $ResponseData[0]['option3_img'] : '';
	$OptionImg3Url = (trim($OptionImg3) != '') ? $QuestionImagePath . $OptionImg3 : '';

	$OptionImg4 = isset($ResponseData[0]['option4_img']) ? $ResponseData[0]['option4_img'] : '';
	$OptionImg4Url = (trim($OptionImg4) != '') ? $QuestionImagePath . $OptionImg4 : '';

	$OptionImg5 = isset($ResponseData[0]['option5_img']) ? $ResponseData[0]['option5_img'] : '';
	$OptionImg5Url = (trim($OptionImg5) != '') ? $QuestionImagePath . $OptionImg5 : '';

	echo '<a class="btn btn-success btn-min-width mr-1 mb-1" href="' . base_url('content/preview_question_paper/') . $QuestionPaperId . '" style="color: white; float:right;"><i class="icon-eye"></i> Preview Question Paper</a>';
	?>

	<div class=" breadcrumbs-top col-md-8 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("ist/dashboard","Dashboard");?></li>
				<li class="breadcrumb-item"><?php echo anchor("content/questions/" . $QuestionPaperId, "Questions");?></li>
				<li class="breadcrumb-item active"><?=(($QuestionId > 0) ? "Edit " : "Add ") . $title ?></li>
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
								<input type="hidden" id="hidQuestionPaperId" name="hidQuestionPaperId" value="<?= $QuestionPaperId ?>"/>
								<input type="hidden" id="hidQuestionId" name="hidQuestionId" value="<?= $QuestionId ?>"/>

								<div class="form-body" style="margin-top: -25px;">
									<div id="divQuestionPaperTitle"  class="form-group row">
										<label for="txtQuestionPaperTitle" class="col-sm-3 label-control">Question Paper</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="txtQuestionPaperTitle" name="txtQuestionPaperTitle" value="<?= isset($ResponseData[0]['question_paper_title']) ? $ResponseData[0]['question_paper_title'] : "" ?>" onkeydown="return false;"/>
										</div>
									</div>

									<div id="divLanguage"  class="form-group row" style="display:block;">
										<label for="txtLanguage" class="col-sm-3 label-control">Language</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="txtLanguage" name="txtLanguage" value="<?= isset($ResponseData[0]['language_name']) ? $ResponseData[0]['language_name'] : "" ?>" onkeydown="return false;"/>
										</div>
									</div>

									<div id="divListPart" class="form-group row" >
										<label for="listPart" class="col-sm-3 label-control" stype="margin-top:10px;">Part<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<select name="listPart" id="listPart" required class="form-control">
												<?php
												echo '<option value="-" ' . ($PartId == 0 ? 'selected="selected"' : '') . '>Select Part</option>';
												if ($PartList) {
													foreach ($PartList AS $Part) {
														echo '<option value="' . $Part['part_id'] . '" ' . ($PartId == $Part['part_id'] ? 'selected="selected"' : '') . '>' . $Part['part_name'] . '</option>';
													}
												}
												?>
											</select>
											<label id="lblPartError" style="color: red; display: none;"></label>
										</div>
									</div>

									<div id="divListSection" class="form-group row">
										<label for="listSection" class="col-sm-3 label-control" stype="margin-top:10px;">Section<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<select name="listSection" id="listSection" required class="form-control">
												<?php

												echo '<option value="-" ' . (trim($SectionCode) == '-' ? 'selected="selected"' : '') . '>Select Section</option>';
												if ($SectionList)
												{
													foreach ($SectionList AS $Section) {
														echo '<option value="' . $Section['section_code'] . '" ' . ($SectionCode == $Section['section_code'] ? 'selected="selected"' : '') . '>' . $Section['section_name'] . ' (' . $Section['section_code'] . ')</option>';
													}
												}
												?>
											</select>
											<label id="lblSectionError" style="color: red; display: none;"></label>
										</div>
									</div>

									<div id="divQuestionType" class="form-group row">
										<label for="listQuestionType" class="col-sm-3 label-control" stype="margin-top:10px;">Question Type<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<select name="listQuestionType" id="listQuestionType" required class="form-control">
												<?php
												echo '<option value="0" ' . ($QuestionTypeId < 1 ? 'selected="selected"' : '') . '>Select Question Type</option>';
												if ($QuestionTypeList) {
													foreach ($QuestionTypeList AS $QuestionType) {
														echo '<option value="' . $QuestionType['question_type_id'] . '" ' . ($QuestionTypeId == $QuestionType['question_type_id'] ? 'selected="selected"' : '') . '>' . $QuestionType['question_type_name'] . ' (' . $QuestionType['question_type_code'] . ')</option>';
													}
												}
												?>
											</select>
											<label id="lblQuestionTypeError" style="color: red; display: none;"></label>
										</div>
									</div>

									<div id="divListLanguage" class="form-group row" style="display: none;">
										<label for="listLanguage" class="col-sm-3 label-control" stype="margin-top:10px;">Language<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<select name="listLanguage" id="listLanguage" required class="form-control">
												<?php
												echo '<option value="0" ' . ($PartId == 0 ? 'selected="selected"' : '') . '>Select Language</option>';
												if ($LanguageList) {
													foreach ($LanguageList AS $Language) {
														echo '<option value="' . $Language['language_id'] . '" ' . ($Language['language_id'] == 1 ? 'selected="selected"' : '') . '>' . $Language['language_name'] . '</option>';
													}
												}
												?>
											</select>
											<label id="lblLanguageError" style="color: red; display: none;"></label>
										</div>
									</div>

									<div id="divQuestionText" class="form-group row">
										<label for="txtQuestionText" class="col-sm-3 label-control">Question Text<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<div id='translQuestionText' style="display:<?= strtoupper($LanguageCode) == 'EN' ? 'none' : 'block'; ?>">
												<input type="checkbox" id="chkTranslQuestionText" onclick="javascript:chkTranslQuestionText_ClickHandler()" style="cursor: pointer;"/>
												Type in <select id="listQuestionTextLanguage" onchange="javascript:listQuestionTextLanguage_ChangeHandler()"></select>&nbsp;&nbsp;( Ctrl+g to toggle between English and <?= $LanguageName ?> )
											</div>
											<div class="techprevue-google-transliterate-API" contentEditable="true">
												<textarea class="form-control" id="txtQuestionText" name="txtQuestionText" style="height: 100px;"><?= $QuestionText ?></textarea>
											</div>
											<label id="lblQuestionTextError" style="color: red; display: none;"></label>
										</div>
									</div>

									<div id="divQuestionImage" class="form-group row">
										<label for="imgQuestion" class="col-sm-3 label-control">Question Image<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<a id="spanImgQuestion" style="display:none;float:right;padding:2px 2px 0px 2px;color:white;" class="btn btn-danger" onclick="ClearImage('imgQuestion',this.id,'hidQuestionImg')" title="Clear Question Image"><i class="icon-android-close"></i></a>
											<div style="border-color:lightgray;border-width:1px;border-style:solid;width:104px;height:104px;padding:1px 1px 1px 1px;border-color: lightgray;cursor:pointer;color:white;background-color:lightgray;" onclick="$('#fileQuestionimage').click();">
												<img id="imgQuestion" style="height: 100px;width:100px;cursor: pointer; background-colorQuestion: lightgray;" alt="&nbsp;&nbsp;Select Image"  border="2" onLoad="ImageOnLoad('imgQuestion','spanImgQuestion')"/>
												<input type="hidden" id="hidQuestionImg" name="hidQuestionImg" value="<?= $QuestionImg ?>"/>
											</div>
											<input type="file" id="fileQuestionimage" name="questionimage" class="form-control" style="display: none;" accept="image/*" onchange="PreviewImageFile('fileQuestionimage', 'imgQuestion')"/>
											<label id="lblQuestionImageError" style="color: red; display: none;"></label>
										</div>
									</div>

									<div id="divMarks" class="form-group row" style="display: <?= $QuestionTypeId == 2 ? "block;" : "none;" ?>">
										<label for="txtMarks" class="col-sm-3 label-control">Marks<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<input type="text" id="txtMarks" name="txtMarks" class="form-control" style="width:50px;" value="<?= ($Marks < 1 ? 1 : $Marks) ?>">
											<label id="lblMarksError" style="float:left; color: red; display: none;"></label>
										</div>
									</div>

									<div id="divReverseValues" class="form-group row" style="display: <?= $QuestionTypeId == 1 ? "block;" : "none;" ?>">
										<label for="chkReverseValues" class="col-sm-3 label-control" style="margin-top: -8px;">Reverse Values?<span class='validmark'>*</span></label>
										<div class="col-sm-9">
											<div>
												<input type="checkbox" class="checkbox" id="chkReverseValues" name="chkReverseValues" style="cursor: pointer;" <?= $ReverseValues == 1 ? 'checked="checked"' : ''?>>
											</div>
											<span class="error_label"></span>
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
												<label for="txtOption1Text" class="col-sm-3 label-control">Option Text<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<div id='translOption1Text' style="display:<?= strtoupper($LanguageCode) == 'EN' ? 'none' : 'block'; ?>">
														<input type="checkbox" id="chkTranslOption1Text" onclick="javascript:chkTranslOption1Text_ClickHandler()"/>
														Type in <select id="listOption1TextLanguage" onchange="javascript:listOption1TextLanguage_ChangeHandler()"></select>&nbsp;&nbsp;( Ctrl+g to toggle between English and <?= $LanguageName ?> )
													</div>
													<div class="techprevue-google-transliterate-API" contentEditable="true">
														<textarea class="form-control" id="txtOption1Text" name="txtOption1Text" style="height: 50px;"><?= $Option1Text ?></textarea>
													</div>
													<label id="lblOption1TextError" style="color: red; display: none;"></label>
												</div>
											</div>

											<div class="form-group row">
												<label for="imgOption1" class="col-sm-3 label-control" style="margin-top: -8px;">Option Image</label>
												<div class="col-sm-9">
													<div>
														<a id="spanImgOption1" style="display:none;float:right;padding:2px 2px 0px 2px;color:white;" class="btn btn-danger" onclick="ClearImage('imgOption1',this.id,'hidOption1Img')" title="Clear Option 1 Image"><i class="icon-android-close"></i></a>			
														<div style="border-color:lightgray;border-width:1px;border-style:solid;width:104px;height:104px;padding:1px 1px 1px 1px;border-color: lightgray;cursor:pointer;color:white;background-color:lightgray;" onclick="$('#fileOptionImage1').click();">
															<img id="imgOption1" style="height: 100px;width:100px;cursor: pointer; background-color: lightgray;" alt="&nbsp;&nbsp;Select Image" onLoad="ImageOnLoad('imgOption1','spanImgOption1')"/>
															<input type="hidden" id="hidOption1Img" name="hidOption1Img" value="<?= $OptionImg1 ?>"/>
														</div>
														<input type="file" id="fileOptionImage1" name="optionimage1" class="form-control" style="display: none;" accept="image/*"  onchange="PreviewImageFile('fileOptionImage1', 'imgOption1')"/>
													</div>
													<span class="error_label"></span>
												</div>
											</div>

											<div id="divCorrectOption1" class="form-group row" style="display: <?= $QuestionTypeId == 2 || $QuestionTypeId == 3 ? "block;" : "none;" ?>">
												<label for="rdoOption1" class="col-sm-3 label-control" style="margin-top: -8px;">Correct Option?<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<div>
														<input type="radio" class="option-input radio" id="rdoOption1" name="rdoCorrectOption" value="1" style="cursor: pointer;" <?= $CorrectOption == 1 ? 'checked="checked"' : '' ?>/>
													</div>
													<label id="lblCorrectOption1Error" style="color: red; display: none;"></label>
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
												<label for="txtOption2Text" class="col-sm-3 label-control">Option Text<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<div id='translOption2Text' style="display:<?= strtoupper($LanguageCode) == 'EN' ? 'none' : 'block'; ?>">
														<input type="checkbox" id="chkTranslOption2Text" onclick="javascript:chkTranslOption2Text_ClickHandler()"/>
														Type in <select id="listOption2TextLanguage" onchange="javascript:listOption2TextLanguage_ChangeHandler()"></select>&nbsp;&nbsp;( Ctrl+g to toggle between English and <?= $LanguageName ?> )
													</div>
													<div class="techprevue-google-transliterate-API" contentEditable="true">
														<textarea class="form-control" id="txtOption2Text" name="txtOption2Text" style="height: 50px;"><?= $Option2Text ?></textarea>
													</div>
													<label id="lblOption2TextError" style="color: red; display: none;"></label>
												</div>
											</div>

											<div class="form-group row">
												<label for="imgOption2" class="col-sm-3 label-control" style="margin-top: -8px;">Option Image</label>
												<div class="col-sm-9">
													<div>						
														<a id="spanImgOption2" style="display:none;float:right;padding:2px 2px 0px 2px;color:white;" class="btn btn-danger"  onclick="ClearImage('imgOption2',this.id,'hidOption2Img')" title="Clear Option 2 Image"><i class="icon-android-close"></i></a>								
														<div style="border-color:lightgray;border-width:1px;border-style:solid;width:104px;height:104px;padding:1px 1px 1px 1px;border-color: lightgray;cursor:pointer;color:white;background-color:lightgray;" onclick="$('#fileOptionImage2').click();">															
															<img id="imgOption2" style="height: 100px;width:100px;cursor: pointer; background-color: lightgray;" alt="&nbsp;&nbsp;Select Image"  onLoad="ImageOnLoad('imgOption2','spanImgOption2')"/>
															<input type="hidden" id="hidOption2Img" name="hidOption2Img" value="<?= $OptionImg2 ?>"/>
														</div>
														<input type="file" id="fileOptionImage2" name="optionimage2" class="form-control" style="display: none;" accept="image/*" onchange="PreviewImageFile('fileOptionImage2','imgOption2')"/>
													</div>
													<span class="error_label"></span>
												</div>
											</div>

											<div id="divCorrectOption2" class="form-group row" style="display: <?= $QuestionTypeId == 2 || $QuestionTypeId == 3 ? "block;" : "none;" ?>">
												<label for="rdoOption2" class="col-sm-3 label-control" style="margin-top: -8px;">Correct Option?<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<div>
														<input type="radio" class="option-input radio" name="rdoCorrectOption" value="2" style3QuestionTypeId == 2"cursor: pointer;" <?= $CorrectOption == 2 ? 'checked="checked"' : '' ?>/>
													</div>
													<label id="lblCorrectOption2Error" style="color: red; display: none;"></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div style="clear: both"></div>

								<div id="divOption3" class="panel panel-default" style="margin-top: 30px;text-align: left;padding-left: 0px;display: <?= $QuestionTypeId == 1 || $QuestionTypeId == 2  ? "block;" : "none;" ?>">
									<div class="panel-heading">
										<h6 class="panel-title"><i class="icon-table"></i> Option 3</h6>
									</div>
									<div class="panel-body">
										<div class="col-sm-12 col-md-12" style="border-style: solid;border-width: 1px;border-color: lightgrey;">
											<div class="form-group row">
												<label for="txtOption3Text" class="col-sm-3 label-control">Option Text<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<div id='translOption3Text' style="display:<?= strtoupper($LanguageCode) == 'EN' ? 'none' : 'block'; ?>">
														<input type="checkbox" id="chkTranslOption3Text" onclick="javascript:chkTranslOption3Text_ClickHandler()"/>
														Type in <select id="listOption3TextLanguage" onchange="javascript:listOption3TextLanguage_ChangeHandler()"></select>&nbsp;&nbsp;( Ctrl+g to toggle between English and <?= $LanguageName ?> )
													</div>
													<div class="techprevue-google-transliterate-API" contentEditable="true">
														<textarea class="form-control" id="txtOption3Text" name="txtOption3Text" style="height: 50px;"><?= $Option3Text ?></textarea>
													</div>
													<label id="lblOption3TextError" style="color: red; display: none;"></label>
												</div>
											</div>

											<div class="form-group row">
												<label for="imgOption3" class="col-sm-3 label-control" style="margin-top: -8px;">Option Image</label>
												<div class="col-sm-9">
													<div>
														<a id="spanImgOption3" style="display:none;float:right;padding:2px 2px 0px 2px;color:white;" class="btn btn-danger"  onclick="ClearImage('imgOption3',this.id,'hidOption3Img')" title="Clear Option 3 Image"><i class="icon-android-close"></i></a>							
														<div style="border-color:lightgray;border-width:1px;border-style:solid;width:104px;height:104px;padding:1px 1px 1px 1px;border-color: lightgray;cursor:pointer;color:white;background-color:lightgray;" onclick="$('#fileOptionImage3').click();">
															<img id="imgOption3" style="height: 100px;width:100px;cursor: pointer; background-color: lightgray;" alt="&nbsp;&nbsp;Select Image" onLoad="ImageOnLoad('imgOption3','spanImgOption3')"/>
															<input type="hidden" id="hidOption3Img" name="hidOption3Img" value="<?= $OptionImg3 ?>"/>
														</div>
														<input type="file" id="fileOptionImage3" name="optionimage3" class="form-control" style="display: none;" accept="image/*" onchange="PreviewImageFile('fileOptionImage3', 'imgOption3')"/>
													</div>													
												</div>
											</div>

											<div id="divCorrectOption3" class="form-group row" style="display: <?= $QuestionTypeId == 2 ? "block;" : "none;" ?>">
												<label for="rdoOption3" class="col-sm-3 label-control" style="margin-top: -8px;">Correct Option?<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<div>
														<input type="radio" class="option-input radio" name="rdoCorrectOption" value="3" style="cursor: pointer;" <?= $CorrectOption == 3 ? 'checked="checked"' : '' ?>/>
													</div>
													<label id="lblCorrectOption3Error" style="color: red; display: none;"></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div style="clear: both"></div>

								<div id="divOption4" class="panel panel-default" style="margin-top: 30px;text-align: left;padding-left: 0px;display: <?= $QuestionTypeId == 1 || $QuestionTypeId == 2 ? "block;" : "none;" ?>">
									<div class="panel-heading">
										<h6 class="panel-title"><i class="icon-table"></i> Option 4</h6>
									</div>
									<div class="panel-body">
										<div class="col-sm-12 col-md-12" style="border-style: solid;border-width: 1px;border-color: lightgrey;">
											<div class="form-group row">
												<label for="txtOption4Text" class="col-sm-3 label-control">Option Text<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<div id='translOption4Text' style="display:<?= strtoupper($LanguageCode) == 'EN' ? 'none' : 'block'; ?>">
														<input type="checkbox" id="chkTranslOption4Text" onclick="javascript:chkTranslOption4Text_ClickHandler()"/>
														Type in <select id="listOption4TextLanguage" onchange="javascript:listOption4TextLanguage_ChangeHandler()"></select>&nbsp;&nbsp;( Ctrl+g to toggle between English and <?= $LanguageName ?> )
													</div>
													<div class="techprevue-google-transliterate-API" contentEditable="true">
														<textarea class="form-control" id="txtOption4Text" name="txtOption4Text" style="height: 50px;"><?= $Option4Text ?></textarea>
													</div>
													<label id="lblOption4TextError" style="color: red; display: none;"></label>
												</div>
											</div>

											<div class="form-group row" >
												<label for="imgOption4" class="col-sm-3 label-control" style="margin-top: -8px;">Option Image</label>
												<div class="col-sm-9">
													<div>
														<a id="spanImgOption4" style="display:none;float:right;padding:2px 2px 0px 2px;color:white;" class="btn btn-danger"  onclick="ClearImage('imgOption4',this.id,'hidOption4Img')" title="Clear Option 4 Image"><i class="icon-android-close"></i></a>								
														<div style="border-color:lightgray;border-width:1px;border-style:solid;width:104px;height:104px;padding:1px 1px 1px 1px;border-color: lightgray;cursor:pointer;color:white;background-color:lightgray;" onclick="$('#fileOptionImage4').click();">
															<img id="imgOption4" style="height: 100px;width:100px;cursor: pointer; background-color: lightgray;" alt="&nbsp;&nbsp;Select Image" onLoad="ImageOnLoad('imgOption4','spanImgOption4')"/>
															<input type="hidden" id="hidOption4Img" name="hidOption4Img" value="<?= $OptionImg4 ?>"/>
														</div>
														<input type="file" id="fileOptionImage4" name="optionimage4" class="form-control" style="display: none;" accept="image/*" onchange="PreviewImageFile('fileOptionImage4', 'imgOption4')"/>
													</div>
													<span class="error_label"></span>
												</div>
											</div>

											<div id="divCorrectOption4" class="form-group row" style="display: <?= $QuestionTypeId == 2 ? "block;" : "none;" ?>">
												<label for="rdoOption4" class="col-sm-3 label-control" style="margin-top: -8px;">Correct Option?<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<div>
														<input type="radio" class="option-input radio" name="rdoCorrectOption" value="4" style="cursor: pointer;" <?= $CorrectOption == 4 ? 'checked="checked"' : '' ?>/>
													</div>
													<label id="lblCorrectOption4Error" style="color: red; display: none;"></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div style="clear: both"></div>

								<div id="divOption5" class="panel panel-default" style="display: <?= $QuestionTypeId == 1 ? "block;" : "none;" ?> margin-top: 30px;text-align: left;padding-left: 0px;">
									<div class="panel-heading">
										<h6 class="panel-title"><i class="icon-table"></i> 	Option 5</h6>
									</div>
									<div class="panel-body">
										<div class="col-sm-12 col-md-12" style="border-style: solid;border-width: 1px;border-color: lightgrey;">
											<div class="form-group row">
												<label for="txtOption5Text" class="col-sm-3 label-control">Option Text<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<div id='translOption5Text' style="display:<?= strtoupper($LanguageCode) == 'EN' ? 'none' : 'block'; ?>">
														<input type="checkbox" id="chkTranslOption5Text" onclick="javascript:chkTranslOption5Text_ClickHandler()"/>
														Type in <select id="listOption5TextLanguage" onchange="javascript:listOption5TextLanguage_ChangeHandler()"></select>&nbsp;&nbsp;( Ctrl+g to toggle between English and <?= $LanguageName ?> )
													</div>
													<div class="techprevue-google-transliterate-API" contentEditable="true">
														<textarea class="form-control" id="txtOption5Text" name="txtOption5Text" style="height: 50px;"><?= $Option5Text ?></textarea>
													</div>
													<label id="lblOption5TextError" style="color: red; display: none;"></label>
												</div>
											</div>

											<div class="form-group row">
												<label for="imgOption5" class="col-sm-3 label-control" style="margin-top: -8px;">Option Image</label>
												<div class="col-sm-9">
													<div>
														<a id="spanImgOption5" style="display:none;float:right;padding:2px 2px 0px 2px;color:white;" class="btn btn-danger"  onclick="ClearImage('imgOption5',this.id,'hidOption5Img')" title="Clear Option 5 Image"><i class="icon-android-close"></i></a>							
														<div style="border-color:lightgray;border-width:1px;border-style:solid;width:104px;height:104px;padding:1px 1px 1px 1px;border-color: lightgray;cursor:pointer;color:white;background-color:lightgray;" onclick="$('#fileOptionImage5').click();">
															<img id="imgOption5" style="height: 100px;width:100px;cursor: pointer; background-color: lightgray;" alt="&nbsp;&nbsp;Select Image" onLoad="ImageOnLoad('imgOption5','spanImgOption5')"/>
															<input type="hidden" id="hidOption5Img" name="hidOption5Img" value="<?= $OptionImg5 ?>"/>
														</div>
														<input type="file" id="fileOptionImage5" name="optionimage5" class="form-control" style="display: none;" accept="image/*" onchange="PreviewImageFile('fileOptionImage5', 'imgOption5')"/>
													</div>
													<span class="error_label"></span>
												</div>
											</div>

											<div id="divCorrectOption5" class="form-group row" style="display: <?= $QuestionTypeId == 2 ? "block;" : "none;" ?>">
												<label for="rdoOption5" class="col-sm-3 label-control" style="margin-top: -8px;">Correct Option?<span class='validmark'>*</span></label>
												<div class="col-sm-9">
													<div>
														<input type="radio" class="option-input radio" name="rdoCorrectOption" value="5" style="cursor: pointer;" <?= $CorrectOption == 5 ? 'checked="checked"' : '' ?>/>
													</div>
													<label id="lblCorrectOption5Error" style="color: red; display: none;"></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div style="clear:both;" ></div>

								<div id="divCorrectOptionError" style="display: none;">
									<label class="col-sm-3 label-control">&nbsp;</label>
									<div class="col-sm-9"  style="margin-top:10px;">
										<label id="lblCorrectOption5Error" style="color: red;">* Select Correct Option</label>
									</div>
								</div>

								<div style="clear:both;" ></div>

								<div style="display: block;">
									<label class="col-sm-3 label-control">&nbsp;</label>
									<div class="col-sm-9" style="margin-top:10px;">
										<button type="submit" class="btn btn-primary" style="margin-top: 5px;"><i class="icon-check2"></i>&nbsp;Save</button>
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



<!--BEGIN: 	GOOGLE MULTI LANGUAGE INPUT TOOL IMPLEMENTATION-->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript">
	var language_code = '<?= $LanguageCode ?>';
	var language_name = '<?= $LanguageName ?>';

	var langArray = [ language_code ];
	google.load("elements", "1", { packages: "transliteration" });

	var transliterationControl;
	function onLoad() {
		var options = {
			sourceLanguage: 'en',	
			destinationLanguage: langArray,
			transliterationEnabled: true,
			shortcutKey: 'ctrl+g'
		};

		//QUESTION TEXT
		translControlQuestionText = new google.elements.transliteration.TransliterationControl(options);
		translControlQuestionText.makeTransliteratable([ "txtQuestionText" ]);
		translControlQuestionText.addEventListener(google.elements.transliteration.TransliterationControl.EventType.STATE_CHANGED, translControlQuestionText_StateChangeHandler);
		document.getElementById('chkTranslQuestionText').checked = translControlQuestionText.isTransliterationEnabled();
		$("#listQuestionTextLanguage").append(new Option(language_name, language_code));

		//OPTION 1 TEXT
		translControlOption1Text = new google.elements.transliteration.TransliterationControl(options);
		translControlOption1Text.makeTransliteratable([ "txtOption1Text" ]);
		translControlOption1Text.addEventListener(google.elements.transliteration.TransliterationControl.EventType.STATE_CHANGED, translControlOption1Text_StateChangeHandler);
		document.getElementById('chkTranslOption1Text').checked = translControlOption1Text.isTransliterationEnabled();
		$("#listOption1TextLanguage").append(new Option(language_name, language_code));

		//OPTION 2 TEXT
		translControlOption2Text = new google.elements.transliteration.TransliterationControl(options);
		translControlOption2Text.makeTransliteratable([ "txtOption2Text" ]);
		translControlOption2Text.addEventListener(google.elements.transliteration.TransliterationControl.EventType.STATE_CHANGED, translControlOption2Text_StateChangeHandler);
		document.getElementById('chkTranslOption2Text').checked = translControlOption2Text.isTransliterationEnabled();
		$("#listOption2TextLanguage").append(new Option(language_name, language_code));

		//OPTION 3 TEXT
		translControlOption3Text = new google.elements.transliteration.TransliterationControl(options);
		translControlOption3Text.makeTransliteratable([ "txtOption3Text" ]);
		translControlOption3Text.addEventListener(google.elements.transliteration.TransliterationControl.EventType.STATE_CHANGED, translControlOption3Text_StateChangeHandler);
		document.getElementById('chkTranslOption3Text').checked = translControlOption3Text.isTransliterationEnabled();
		$("#listOption3TextLanguage").append(new Option(language_name, language_code));

		//OPTION 4 TEXT
		translControlOption4Text = new google.elements.transliteration.TransliterationControl(options);
		translControlOption4Text.makeTransliteratable([ "txtOption4Text" ]);
		translControlOption4Text.addEventListener(google.elements.transliteration.TransliterationControl.EventType.STATE_CHANGED, translControlOption4Text_StateChangeHandler);
		document.getElementById('chkTranslOption4Text').checked = translControlOption4Text.isTransliterationEnabled();
		$("#listOption4TextLanguage").append(new Option(language_name, language_code));

		//OPTION 5 TEXT
		translControlOption5Text = new google.elements.transliteration.TransliterationControl(options);
		translControlOption5Text.makeTransliteratable([ "txtOption5Text" ]);
		translControlOption5Text.addEventListener(google.elements.transliteration.TransliterationControl.EventType.STATE_CHANGED, translControlOption5Text_StateChangeHandler);
		document.getElementById('chkTranslOption5Text').checked = translControlOption5Text.isTransliterationEnabled();
		$("#listOption5TextLanguage").append(new Option(language_name, language_code));

	}

	//QUESTION TEXT
	function translControlQuestionText_StateChangeHandler(e) {
		document.getElementById('chkTranslQuestionText').checked = e.transliterationEnabled;
	}
	function chkTranslQuestionText_ClickHandler() {
		translControlQuestionText.toggleTransliteration();
	}
	function listQuestionTextLanguage_ChangeHandler() {
		var dropdown = document.getElementById('listQuestionTextLanguage');
		translControlQuestionText.setLanguagePair(google.elements.transliteration.LanguageCode.ENGLISH, dropdown.options[dropdown.selectedIndex].value);
	}

	//OPTION 1 TEXT
	function translControlOption1Text_StateChangeHandler(e) {
		document.getElementById('chkTranslOption1Text').checked = e.transliterationEnabled;
	}
	function chkTranslOption1Text_ClickHandler() {
		translControlOption1Text.toggleTransliteration();
	}
	function listOption1TextLanguage_ChangeHandler() {
		var dropdown = document.getElementById('listOption1TextLanguage');
		translControlOption1Text.setLanguagePair(google.elements.transliteration.LanguageCode.ENGLISH, dropdown.options[dropdown.selectedIndex].value);
	}

	//OPTION 2 TEXT
	function translControlOption2Text_StateChangeHandler(e) {
		document.getElementById('chkTranslOption2Text').checked = e.transliterationEnabled;
	}
	function chkTranslOption2Text_ClickHandler() {
		translControlOption2Text.toggleTransliteration();
	}
	function listOption2TextLanguage_ChangeHandler() {
		var dropdown = document.getElementById('listOption2TextLanguage');
		translControlOption2Text.setLanguagePair(google.elements.transliteration.LanguageCode.ENGLISH, dropdown.options[dropdown.selectedIndex].value);
	}

	//OPTION 3 TEXT
	function translControlOption3Text_StateChangeHandler(e) {
		document.getElementById('chkTranslOption3Text').checked = e.transliterationEnabled;
	}
	function chkTranslOption3Text_ClickHandler() {
		translControlOption3Text.toggleTransliteration();
	}
	function listOption3TextLanguage_ChangeHandler() {
		var dropdown = document.getElementById('listOption3TextLanguage');
		translControlOption3Text.setLanguagePair(google.elements.transliteration.LanguageCode.ENGLISH, dropdown.options[dropdown.selectedIndex].value);
	}

	//OPTION 4 TEXT
	function translControlOption4Text_StateChangeHandler(e) {
		document.getElementById('chkTranslOption4Text').checked = e.transliterationEnabled;
	}
	function chkTranslOption4Text_ClickHandler() {
		translControlOption4Text.toggleTransliteration();
	}
	function listOption4TextLanguage_ChangeHandler() {
		var dropdown = document.getElementById('listOption4TextLanguage');
		translControlOption4Text.setLanguagePair(google.elements.transliteration.LanguageCode.ENGLISH, dropdown.options[dropdown.selectedIndex].value);
	}

	//OPTION 5 TEXT
	function translControlOption5Text_StateChangeHandler(e) {
		document.getElementById('chkTranslOption5Text').checked = e.transliterationEnabled;
	}
	function chkTranslOption5Text_ClickHandler() {
		translControlOption5Text.toggleTransliteration();
	}
	function listOption5TextLanguage_ChangeHandler() {
		var dropdown = document.getElementById('listOption5TextLanguage');
		translControlOption5Text.setLanguagePair(google.elements.transliteration.LanguageCode.ENGLISH, dropdown.options[dropdown.selectedIndex].value);
	}

	google.setOnLoadCallback(onLoad);
</script>
<!--END: 	GOOGLE MULTI LANGUAGE INPUT TOOL IMPLEMENTATION-->

<script>
$(document).ready(function()
{
	var varImgQuestionUrl = '<?=$QuestionImgUrl ?>',
		varImgOption1Url = '<?=$OptionImg1Url ?>',
		varImgOption2Url = '<?=$OptionImg2Url ?>',
		varImgOption3Url = '<?=$OptionImg3Url ?>',
		varImgOption4Url = '<?=$OptionImg4Url ?>',
		varImgOption5Url = '<?=$OptionImg5Url ?>';

    if (varImgQuestionUrl.trim() != '') document.getElementById('imgQuestion').src = varImgQuestionUrl;
	if (varImgOption1Url.trim() != '') document.getElementById('imgOption1').src = varImgOption1Url;
	if (varImgOption2Url.trim() != '') document.getElementById('imgOption2').src = varImgOption2Url;
	if (varImgOption3Url.trim() != '') document.getElementById('imgOption3').src = varImgOption3Url;
	if (varImgOption4Url.trim() != '') document.getElementById('imgOption4').src = varImgOption4Url;
	if (varImgOption5Url.trim() != '') document.getElementById('imgOption5').src = varImgOption5Url;

	$("#frmEntry").validate({
	 	ignore: ":hidden",
		errorPlacement: function(error, element) 
		{
			 $(element).closest('.form-group').find('.error_label').html(error);
		},
		messages: 
		{
			txtRsHeadName:
			{
				required: "Please enter name",
			},
				txtRsHeadPhone:
			{
				required: "Please enter phone",
			},
			txtRsHeadEmail:
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
		},
	 	rules: {
			 txtRsHeadName: {
				 required: true,
				 minlength: 3
			 },
			 txtRsHeadPhone: {
				 required: true,
				 minlength: 10
			 },
			 txtRsHeadEmail: {
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
		highlight: function(element)
		{
			$(element).addClass('error_label');
		},
		unhighlight: function(element)
		{
			$(element).removeClass('error_label');
		}
	});

	$("#listPart").on('change', function() {
		//alert(this.value);

		var part_id = this.value;

		$.ajax({
			url: base_url + "content/get_section_list_for_part_id",
			data:
			{
				'part_id' : part_id
			},
			type: "POST",
			dataType:'json',
			success: function (result)
			{
				$('#listSection').empty();
				$('#listSection').append(new Option('Select Section', '0'));

				var varSectionFullName = '';
				for (var i = 0; i < result.length; i++)
				{
					varSectionFullName = result[i].section_name + " (" + result[i].section_code + ")";
					$('#listSection').append(new Option(varSectionFullName, result[i].section_code));
				}
			},
			error: function ()
			{
				alert("Error Occurred");
			}
		});
	});

	$("#listQuestionType").on('change', function() {
		//alert(this.value);
		$("#divCorrectOption1").hide();
		$("#divCorrectOption2").hide();
		$("#divCorrectOption3").hide();
		$("#divCorrectOption4").hide();
		$("#divCorrectOption5").hide();
		$("#divReverseValues").hide();
		$("#divMarks").hide();

		$("#divOption3").hide();
		$("#divOption4").hide();
		$("#divOption5").hide();

		switch (this.value) 
		{
			case "1":	 //Psychometric Question (PMQ)	
				if ($("#txtOption5Text").val() == 'NA') $("#txtOption5Text").val(''); 		
				$("#divReverseValues").show();
				$("#divOption3").show();
				$("#divOption4").show();
				$("#divOption5").show();
				break;

			case "2":	 //Multiple Choice Question (MCQ)	
				$("#divMarks").show();
				$("#divCorrectOption1").show();
				$("#divCorrectOption2").show();
				$("#divCorrectOption3").show();
				$("#divCorrectOption4").show();		
				$("#divOption3").show();
				$("#divOption4").show();
				break;

			case "3": //TRUE/FALSE Question (TFQ)	
				$("#txtOption1Text").val('True');
				$("#txtOption2Text").val('False');
				$("#divMarks").show();
				$("#divCorrectOption1").show();
				$("#divCorrectOption2").show();	
				break;
		
			default:
				break;
		}
	});
});

function PreviewImageFile(FileControl, PreviewImage)
{
	var preview = document.getElementById(PreviewImage);
	var file = document.getElementById(FileControl).files[0];

	var reader = new FileReader();
	reader.onloadend = function ()
	{
		var uploadedFileSize = (file.size / 1024 / 1024).toFixed(3);
		if (parseFloat(uploadedFileSize) > 1)
		{
			$('[name="'+ FileControl +'"]', form).closest('.form-group').find('.error_label').html("Image File Size: " + uploadedFileSize + " MB.   File Size cannot exceed 1 MB!");
			preview.src = "";
			preview.title='&nbsp;&nbsp;';
			return false;
		}
		else
		{
			preview.src = reader.result;
			preview.title= file.name;
		}
	}

	if (file)
	{
		reader.readAsDataURL(file);
	}
	else
	{
		preview.src = "";
		preview.title='&nbsp;&nbsp;';
	}
}

$("#frmEntry").on('submit',(function(e)
{
	e.preventDefault();

	if (!ValidateInputs()) return;
	var varQuestionPaperId = <?= $QuestionPaperId ?>;
	$.ajax({
		url: base_url + "content/SaveQuestionData",
		type: "POST",
		data: new FormData(this),
		contentType: false,
		cache: false,
		dataType:'json',
		processData: false,
		success: function (result)
		{
			swal({
				title: "",
				type: "success",
				text: result[0].o_message + "!",
				confirmButtonColor: "#5cb85c",
				confirmButtonText: 'OK',
				closeOnConfirm: true
			},
			function (confirmed)
			{
				if (parseInt(result[0].o_question_id) > 0)
					window.location.href = base_url + "content/questions/" + varQuestionPaperId;
			});
		},
		error: function ()
		{
			alert("Error Occurred");
		}
	});
}));

function ValidateInputs() {
	var varReturnValue = true;
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
	$("#divCorrectOptionError").hide();
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
	if ($("#txtQuestionText").val().trim() == '') 
	{
		$("#lblQuestionTextError").text('* Question Text is required!');
		$("#lblQuestionTextError").show();
		varReturnValue = false;
		if (!varFocus) {
			$("#txtQuestionText").focus();
			varFocus = true;
		}
	}

	//MARKS
	if (varQuestionTypeId == 2 || varQuestionTypeId == 3) 
	{
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
	if ($("#txtOption1Text").val().trim() == '') 
	{
		if (!$("#fileOptionImage1").val() && document.getElementById('imgOption1').naturalHeight <= 0)
		{
			$("#lblOption1TextError").text('* Option Text Or Option Image is required!');
			$("#lblOption1TextError").show();
			varReturnValue = false;
			if (!varFocus) {
				$("#txtOption1Text").focus();
				varFocus = true;
			}
		}
	}

	//OPTION 2 TEXT
	if ($("#txtOption2Text").val().trim() == '') 
	{
		if (!$("#fileOptionImage2").val() && document.getElementById('imgOption2').naturalHeight <= 0)
		{
			$("#lblOption2TextError").text('* Option Text Or Option Image is required!');
			$("#lblOption2TextError").show();
			varReturnValue = false;
			if (!varFocus) {
				$("#txtOption2Text").focus();
				varFocus = true;
			}
		}
	}

	//OPTION 3 TEXT
	if (varQuestionTypeId == 1 || varQuestionTypeId == 2)
	{
		if ($("#txtOption3Text").val().trim() == '')
		{
			if (!$("#fileOptionImage3").val() && document.getElementById('imgOption3').naturalHeight <= 0)
			{
				$("#lblOption3TextError").text('* Option Text or Option Image is required!');
				$("#lblOption3TextError").show();
				varReturnValue = false;
				if (!varFocus) {
					$("#txtOption3Text").focus();
					varFocus = true;
				}
			}
		}
	}

	//OPTION 4 TEXT
	if (varQuestionTypeId == 1 || varQuestionTypeId == 2)
	{
		if ($("#txtOption4Text").val().trim() == '') 
		{
			if (!$("#fileOptionImage4").val() && document.getElementById('imgOption4').naturalHeight <= 0)
			{
				$("#lblOption4TextError").text('* Option Text Or Option Image is required!');
				$("#lblOption4TextError").show();
				varReturnValue = false;
				if (!varFocus) {
					$("#txtOption4Text").focus();
					varFocus = true;
				}
			}
		}
	}

	//OPTION 5 TEXT
	if (varQuestionTypeId == 1) 
	{
		if ($("#txtOption5Text").val().trim() == '') 
		{
			if (!$("#fileOptionImage5").val() && document.getElementById('imgOption5').naturalHeight <= 0)
			{
				$("#lblOption5TextError").text('* Option Text Or Option Image is required!');
				$("#lblOption5TextError").show();
				varReturnValue = false;
				if (!varFocus) {
					$("#txtOption5Text").focus();
					varFocus = true;
				}
			}
		}
	}

	//CORRECT OPTION
	if (varQuestionTypeId == 2 || varQuestionTypeId == 3) {
		var varCorrectOption = $("input:radio[name='rdoCorrectOption']:checked").val();
		if (varCorrectOption == undefined || (parseInt(varQuestionTypeId == 2 && (varCorrectOption < 1 || varCorrectOption > 4)) || (varQuestionTypeId == 3 && (varCorrectOption < 1 || varCorrectOption > 2)))) {
			$("#divCorrectOptionError").show();
			varReturnValue = false;
			if (!varFocus) {
				$("#rdoOption1").focus();
				varFocus = true;
			}
		}
	}

	return varReturnValue;
}

function ClearImage(ImgId,SpanId,HiddenInputId)
{
	var varImg = document.getElementById(ImgId);
	varImg.src = "";
	$("#" + HiddenInputId).val('');
	$("#" + SpanId).hide();
}

function ImageOnLoad(ImgId,SpanId)
{
	$("#" + SpanId).show();
}
</script>
