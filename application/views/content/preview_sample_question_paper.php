<style type="text/css">
	/**
	 * @author  George Martin <george.s@navriti.com>
	 * @desc  	preview sample question paper
	 * @date  	March 2017
	*/
	select.input-sm
	{
	 	line-height: 10px;
	}
	.error_label, .validmark{color: red;}

	.version-color 
	{ 
		color: maroon;
	}
</style>
<div class="content-body" style="overflow-x: hidden !important;">

	<?php
	$QuestionImageUrl = base_url(QUESTION_IMAGES);
	$LanguageId = $language_id;

	$PageTitle = "Preview Question Paper";

	?>

	<!-- File export table -->
	<div class=" breadcrumbs-top col-md-10 col-xs-12">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("ist/dashboard","Dashboard");?></li>
				<li class="breadcrumb-item"><?php echo anchor("content/question_papers", "Question Papers");?></li>
				<li class="breadcrumb-item"><?php echo anchor("content/sample_questions", "Sample Question Paper");?></li>
				<li class="breadcrumb-item active">Preview Questions
				</li>
			</ol>
		</div>
	</div>
	<?php
	if (intval($user_role_id) == 1)
		echo '<a class="btn btn-success btn-sm" title="Download Question Paper In PDF Format" onclick="DownloadInPdf()" style="color:white;" ><i class="icon-archive"></i> Download PDF</a>';
	?>
	<section id="configuration" style="margin-top: 15px;">
		<div class="row">
			<div class="col-xs-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Available <?= $PageTitle ?></h4>
						<a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
						<div class="heading-elements">
							<ul class="list-inline mb-0">
								<li><a data-action="collapse"><i class="icon-m	inus4"></i></a></li>
								<li><a data-action="reload"><i class="icon-reload"></i></a></li>
								<li><a data-action="expand"><i class="icon-expand2"></i></a></li>
							</ul>
						</div>
					</div>
					<form id="frmEntry" method="post" enctype="multipart/form-data" class="form-horizontal" style="padding-top:10px;">
						<?php
						echo '<div id="divPageContainer" class="container-fluid" style="padding-left:50px;">';
						echo '	<div class="form-group">';
						echo '		<label class="col-sm-3 control-label" style="margin-top: 6px;text-align: left;font-size: 18px;">Question Paper Title :</label>';
						echo '		<div class="col-sm-9" style="margin-bottom: 10px;">';
						echo ' 			<input type="text" class="form-control" value="Sample Question Paper" onkeydown="return false;" disabled="disabled"/>';
						echo '		</div>';
						echo '	</div>';
						echo '	<div style="clear: both;"></div>';
						echo '	<div class="form-group" style="margin-top: -10px;">';
						echo '		<label class="col-sm-3 control-label" style="margin-top: 6px;text-align: left;font-size: 18px;">Question Count :</label>';
						echo '		<div class="col-sm-9" style="margin-bottom: 10px;">';
						echo ' 			<input type="text" class="form-control" value="' . $question_count . '" onkeydown="return false;" disabled="disabled"/>';
						echo '		</div>';
						echo '	</div>';
						echo '	<div style="clear: both;"></div>';
						echo '	<div class="form-group" style="margin-top: -10px;">';
						echo '		<label class="col-sm-3 control-label" style="margin-top: 6px;text-align: left;font-size: 18px;">Language Version :</label>';
						echo '		<div class="col-sm-9" style="margin-bottom: 10px;">';
						echo '			<select name="listLanguage" id="listLanguage" required class="form-control" onchange="javascript:listLanguage_OnChange(this.value)">';
						if (count($language_list) > 0)
						{
							foreach ($language_list AS $Language)
							{
								echo '<option value="' . $Language['language_id'] . '" '. ($LanguageId == $Language['language_id'] ? 'Selected="Selected"' : '') . ' >' . $Language['language_name'] . '</option>';
							}
						}
						echo '			</select>';
						echo '		</div>';
						echo '	</div>';
						echo '	<div style="clear: both;"></div>';
						echo '	<hr>';
						echo '	<div style="clear: both;"></div>';
						echo '	<br>';

						if (count($ResponseData) > 0)
						{
							$QuestionIndex = 0;
							foreach ($ResponseData AS $QuestionData)
							{
								$QuestionIndex++;

								//QUESTION TEXT AND IMAGE
								echo '	<div class="form-group row" style="padding-left: 12px;margin-top: 20px;">';
								echo '		<div style="width:100%">';
								echo '			<a href="' . base_url('content/addedit_sample_question/') . $QuestionData['question_id'] . '" style="cursor: pointer;" title="Edit Question">';
								echo '				<label class="label-control" style="margin-top:-8px;text-align: left; font-size: 14px;color: midnightblue;width: 25px;vertical-align: top;cursor: pointer;">' . $QuestionIndex . '. </label>';
								echo '				<label class="label-control" style="margin-top:-8px;text-align: left; font-size: 14px;color: midnightblue;margin-bottom:20px;vertical-align: top;cursor: pointer;">' . $QuestionData['question_text'] . '</label>';

								if (trim($QuestionData['question_img']) != "")
								{
									echo '		<div style="width:100%; padding-left: 25px;margin-top: 10px;margin-bottom: 10px;cursor: pointer;">';
									echo '			<img src="' . trim($QuestionData['question_img']) . '" style="height:100px;margin-bottom:20px;"></img>';
									echo '		</div>';
								}

								echo '			</a>';
								echo '		</div>';


								//OPTION 1 TEXT AND IMAGE
								echo '		<div style="width:100%; padding-left: 25px;">';
								echo '			<label class="label-control" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;vertical-align: top;color:midnightblue;">a. </label>';

								if (trim($QuestionData['option1_img']) != "")
								{
									echo '		<div style="display: inline-block;margin-top:10px;">';
									echo '			<img src="' . trim($QuestionData['option1_img']) . '" style="height:100px;"></img>';
									echo '		</div>';
								}

								if (trim($QuestionData['option1_text']) != "")
								{
									echo '			<label class="label-control" style="margin-top:-8px;text-align: left; font-size: 12px;margin-bottom:10px;vertical-align: top;">' . $QuestionData['option1_text'] . '</label>';
								}
								echo '		</div>';


								//OPTION 2 TEXT AND IMAGE
								echo '		<div style="width:100%; padding-left: 25px;">';
								echo '			<label class="label-control" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;vertical-align: top;color:midnightblue;">b. </label>';

								if (trim($QuestionData['option2_img']) != "")
								{
									echo '		<div style="display: inline-block;margin-top:10px;">';
									echo '			<img src="' . trim($QuestionData['option2_img']) . '" style="height:100px;"></img>';
									echo '		</div>';
								}

								if (trim($QuestionData['option2_text']) != "")
								{
									echo '			<label class="label-control" style="margin-top:-8px;text-align: left; font-size: 12px;margin-bottom:10px;vertical-align: top;">' . $QuestionData['option2_text'] . '</label>';
								}
								echo '		</div>';


								//OPTION 3 TEXT AND IMAGE
								echo '		<div style="width:100%; padding-left: 25px;">';
								echo '			<label class="label-control" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;vertical-align: top;color:midnightblue;">c. </label>';

								if (trim($QuestionData['option3_img']) != "")
								{
									echo '		<div style="display: inline-block;margin-top:10px;">';
									echo '			<img src="' . trim($QuestionData['option3_img']) . '" style="height:100px;"></img>';
									echo '		</div>';
								}

								if (trim($QuestionData['option3_text']) != "")
								{
									echo '			<label class="label-control" style="margin-top:-8px;text-align: left; font-size: 12px;margin-bottom:10px;vertical-align: top;">' . $QuestionData['option3_text'] . '</label>';
								}
								echo '		</div>';


								//OPTION 4 TEXT AND IMAGE
								echo '		<div style="width:100%; padding-left: 25px;">';
								echo '			<label class="label-control" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;vertical-align: top;color:midnightblue;">d. </label>';

								if (trim($QuestionData['option4_img']) != "")
								{
									echo '		<div style="display: inline-block;margin-top:10px;">';
									echo '			<img src="' . trim($QuestionData['option4_img']) . '" style="height:100px;"></img>';
									echo '		</div>';
								}

								if (trim($QuestionData['option4_text']) != "")
								{
									echo '			<label class="label-control" style="margin-top:-8px;text-align: left; font-size: 12px;margin-bottom:10px;vertical-align: top;">' . $QuestionData['option4_text'] . '</label>';
								}
								echo '		</div>';


								//OPTION 5 TEXT AND IMAGE
								if (intval($QuestionData['question_type_id']) != 2)
								{
									echo '		<div style="width:100%; padding-left: 25px;">';
									echo '			<label class="label-control" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;vertical-align: top;color:midnightblue;">e. </label>';

									if (trim($QuestionData['option4_img']) != "")
									{
										echo '		<div style="display: inline-block;margin-top:10px;">';
										echo '			<img src="' . trim($QuestionData['option5_img']) . '" style="height:100px;"></img>';
										echo '		</div>';
									}

									if (trim($QuestionData['option5_text']) != "")
									{
										echo '			<label class="label-control" style="margin-top:-8px;text-align: left; font-size: 12px;margin-bottom:10px;vertical-align: top;">' . $QuestionData['option5_text'] . '</label>';
									}
									echo '		</div>';

								}

								echo '	</div>';
							}
						}
						?>
					</form>
				</div>
			</div>
		</div>
	</section>
	<!-- File export table -->

</div>


<script>
	function DownloadInPdf()
	{
		document.location.href = base_url + "content/download_question_paper_preview_in_pdf";
	}

	function listLanguage_OnChange(LanguageId)
	{
		document.location.href = base_url + "content/preview_sample_question_paper/" + LanguageId;
	}
</script>
