<style type="text/css">
	/**
	 * @author  George Martin <george.s@navriti.com>
	 * @desc  	Add / Edit question paper
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
	$QuestionPaperId = $question_paper_id;
	$QuestionPaperTitle = isset($ResponseData[0]['question_paper_title']) ? $ResponseData[0]['question_paper_title'] : '';
	$DurationMinutes = isset($ResponseData[0]['duration_minutes']) ? intval($ResponseData[0]['duration_minutes']) : -1;
	?>

	<div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("ist/dashboard","Dashboard");?></li>
				<li class="breadcrumb-item"><?php echo anchor("content/question_papers/", "Question Papers");?></li>
				<li class="breadcrumb-item active"><?=(($QuestionPaperId > 0) ? "Edit " : "Add ") . $title ?></li>
			</ol>
		</div>
	</div>

	<section id="basic-form-layouts">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="bordered-layout-basic-form"><?= (($QuestionPaperId > 0) ? "Edit " : "Add ") . $title ?> Info</h4>
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

								<div class="form-body">
									<div id="divQuestionPaperTitle"  class="form-group row">
										<label for="txtQuestionPaperTitle" class="col-sm-3 label-control">Question Paper Title</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="txtQuestionPaperTitle" name="txtQuestionPaperTitle" value="<?= $QuestionPaperTitle ?>"/>
											<label id="lblQuestionPaperTitleError" style="color: red;display: none;">* Question Paper Title is a required field!</label>
										</div>
									</div>

									<div id="divDurationMinutes"  class="form-group row">
										<label for="txtDurationMinutes" class="col-sm-3 label-control">Duration (Minutes)</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="txtDurationMinutes" name="txtDurationMinutes" value="<?= $DurationMinutes > 0 ? $DurationMinutes : '' ?>"/>
										</div>
									</div>
								</div>

								<div id="divActions" class="form-actions" style="margin-left: 25%; ">
								  <button type="submit" class="btn btn-primary" onclick="SaveQuestionPaperDetails()" style="margin-top: 10px;"><i class="icon-check2"></i> Save</button>
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

<!--END: 	GOOGLE MULTI LANGUAGE INPUT TOOL IMPLEMENTATION-->

<script>
	$(document).ready(function () {
		$("#txtDurationMinutes").keypress(function (event) {
			return /\d/.test(String.fromCharCode(event.keyCode));
		});
	});

	function SaveQuestionPaperDetails()
	{

	}

$("#frmEntry").on('submit',(function(e)
{
	e.preventDefault();

	if (!ValidateInputs()) return;

	var question_paper_id = 	<?= $QuestionPaperId ?>;
	var question_paper_title = 	$("#txtQuestionPaperTitle").val();

	var duration_minutes = '-1';
	if ($("#txtDurationMinutes").val().trim() != '')
		duration_minutes = $("#txtDurationMinutes").val();

	$.ajax({
		url: base_url + "content/save_question_paper_detail",
		type: "POST",
		data: {
			'question_paper_id' : question_paper_id,
			'question_paper_title' : question_paper_title,
			'duration_minutes' : duration_minutes
		},
		dataType:'json',
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
			 	if (parseInt(result[0].o_status) > 0)
			 	window.location.href = base_url + "content/question_papers";
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

	$("#lblQuestionPaperTitleError").hide();

	//QUESTION PAPER TITLE
	if ($("#txtQuestionPaperTitle").val().trim() == '') {
		$("#lblQuestionPaperTitleError").text('* Question Paper Title is a required field!');
		$("#lblQuestionPaperTitleError").show();
		varReturnValue = false;
		if (!varFocus) {
			$("#txtQuestionPaperTitle").focus();
			varFocus = true;
		}
	}

	return varReturnValue;
}
</script>
