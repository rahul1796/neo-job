<?php defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('session_cache_limiter','private');
    session_cache_limiter(FALSE);
/**
 * Content :: Content Controller
 * @author by george.s@navriti.com
**/
include APPPATH.'/controllers/Pramaan.php';

class Content extends Pramaan
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Content_model", "content");
	}

	//BEGIN: QUESTION PAPERS - By George
	public function question_papers()
	{
		$user                 = $this->pramaan->_check_module_task_auth(true);
		$data['page']         = 'question_papers';
		$data['module']       = "content";
		$data['title']        = 'Question Papers';
		$data['user_role_id'] = $user['user_group_id'];
		$data['user_id']      = $user['id'];
		$data['LanguageList'] = $this->content->get_language_list_data();
		$this->load->view('index', $data);
	}

	public function get_question_paper_data()
	{
		error_reporting(E_ALL);
		$requestData = $_REQUEST;
		$resp_data   = $this->content->get_question_paper_data($requestData);
		echo json_encode($resp_data);
	}

	public function addedit_question_paper($question_paper_id = 0)
	{
		$user = $this->pramaan->_check_module_task_auth(true);
		$data = array(
			'page' 					=> 'addedit_question_paper',
			'parent_page' 			=> "content/question_papers",
			'parent_page_title' 	=> "Question Papers",
			'title' 				=> "Question Paper",
			'module' 				=> "content",
			'user_role_id' 			=> $user['user_group_id'],
			'user_id' 				=> $user['id'],
			'question_paper_id' 	=> $question_paper_id,
			'ResponseData' 			=> $this->content->get_question_paper_detail($question_paper_id)
		);

		$this->load->view('index', $data);
	}

	public function get_question_paper_detail_bak()
	{
		$user            = $this->pramaan->_check_module_task_auth(true);
		$QuestionPaperId = isset($_REQUEST['question_paper_id']) ? $_REQUEST['question_paper_id'] : 0;
		$resp_data       = array(
			'LanguageList' => $this->content->get_language_list($QuestionPaperId),
			'ResponseData' => $this->content->get_question_paper_detail($QuestionPaperId)
		);

		echo json_encode($resp_data);
	}

	public function get_question_paper_detail($QuestionPaperId = 0)
	{
		$user            = $this->pramaan->_check_module_task_auth(true);
		$QuestionPaperId = isset($_REQUEST['question_paper_id']) ? $_REQUEST['question_paper_id'] : 0;
		$ResponseData = $this->content->get_question_paper_detail($QuestionPaperId);
		return $ResponseData;
	}

	public function get_language_list()
	{
		error_reporting(E_ALL);
		$QuestionPaperId = isset($_REQUEST['question_paper_id']) ? $_REQUEST['question_paper_id'] : 0;

		$resp_data = $this->content->get_language_list($QuestionPaperId);
		echo json_encode($resp_data);
	}

	public function get_language_list_data()
	{
		error_reporting(E_ALL);
		$resp_data = $this->content->get_language_list_data();
		echo json_encode($resp_data);
	}

	public function change_question_paper_active_status()
	{
		$user        = $this->pramaan->_check_module_task_auth(true);
		$RequestData = array(
			'id' 			=> $this->input->post('id'),
			'active_status' => $this->input->post('active_status')
		);

		$Response = $this->content->change_question_paper_active_status($RequestData);
		echo json_encode($Response);
	}

	public function save_question_paper_data()
	{
		error_reporting(E_ALL);
		$user = $this->pramaan->_check_module_task_auth(true);

		$userfile = array();

		$RequestData = array(
			'id' 					=> $this->input->post('id'),
			'assessment_type_id' 	=> $this->input->post('assessment_type_id'),
			'language_id' 			=> $this->input->post('language_id'),
			'user_id' 				=> $user['id']
		);

		$Response = $this->content->save_question_paper_data($RequestData);
		if ($Response['status']) {
			$QuestionPaperId = $Response['question_paper_id'];
		}

		echo json_encode($Response);
	}

	public function SaveQuestionPaperData()
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$ErrorDesc = "";

		$QuestionId = 0;
		$Extension  = "";

		$ResponseArray = array();
		$files         = $_FILES;

		$this->load->library('upload');
		$this->upload->initialize($this->set_question_paper_upload_options());

		//If Single File
		$ExcelFileName  = "";
		$ExcelDataArray = array();

		if (isset($_FILES['questionexcel']['name'])) {
			$ErrorResponse = "";

			$OriginalExcelFileName = $_FILES['questionexcel']['name'];

			$Extension     = pathinfo($_FILES['questionexcel']['name'], PATHINFO_EXTENSION);
			$ExcelFileName = str_replace("." . $Extension, "", $_FILES['questionexcel']['name']) . date("_Ymdhis.") . $Extension;

			$_FILES['questionexcel']['name'] = $ExcelFileName;
			$this->upload->do_upload('questionexcel');
			$Response[] = $this->upload->data();

			$ExcelFilePath = QUESTION_PAPER_DATA . $ExcelFileName;

			print "Question Paper Data File '$OriginalExcelFileName' has been uploaded successfully!\n\n";

			$this->load->library('Excel');
			try {
				$inputFileType  = PHPExcel_IOFactory::identify($ExcelFilePath);
				$objExcelReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel    = $objExcelReader->load($ExcelFilePath);
			}
			catch (Exception $e) {
				die('Error loading file "' . pathinfo($ExcelFilePath, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			}

			$QuestionPaperId = intval($this->input->post('hidQuestionPaperId'));
			$LanguageId      = $QuestionPaperId > 0 ? $this->input->post('hidLanguageId') : $this->input->post('listLanguage');

			$Delimiter         = "[@]";
			$PartCodeList      = "";
			$SectionCodeList   = "";
			$QuestionTextList  = "";
			$QuestionTypeList  = "";
			$Option1TextList   = "";
			$Option2TextList   = "";
			$Option3TextList   = "";
			$Option4TextList   = "";
			$Option5TextList   = "";
			$CorrectOptionList = "";
			$MarkList          = "";
			$ReverseValueList  = "";
			$QuestionImageList = "";
			$Option1ImageList  = "";
			$Option2ImageList  = "";
			$Option3ImageList  = "";
			$Option4ImageList  = "";
			$Option5ImageList  = "";

			$objPHPExcel->setActiveSheetIndex(0);                    //ALWAYS READ DATA FROM FIRST SHEET
			$objExcelWorkSheet = $objPHPExcel->getActiveSheet();    //GET FIRST SHEET

			$ColumnIndex = -1;
			for ($RowIndex = 2; $RowIndex <= $objExcelWorkSheet->getHighestRow(); $RowIndex++) {
				$ColumnIndex = -1;
				if ($RowIndex > 2) {
					$PartCodeList .= $Delimiter;
					$SectionCodeList .= $Delimiter;
					$QuestionTextList .= $Delimiter;
					$QuestionTypeList .= $Delimiter;
					$Option1TextList .= $Delimiter;
					$Option2TextList .= $Delimiter;
					$Option3TextList .= $Delimiter;
					$Option4TextList .= $Delimiter;
					$Option5TextList .= $Delimiter;
					$CorrectOptionList .= $Delimiter;
					$MarkList .= $Delimiter;
					$ReverseValueList .= $Delimiter;
					$QuestionImageList .= $Delimiter;
					$Option1ImageList .= $Delimiter;
					$Option2ImageList .= $Delimiter;
					$Option3ImageList .= $Delimiter;
					$Option4ImageList .= $Delimiter;
					$Option5ImageList .= $Delimiter;
				}

				$ErrorDesc = "";

				//PART CODE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "") {
					$CellText = "";
					$ErrorDesc .= "Part,";
				}
				$PartCodeList .= $CellText;

				//SECTION CODE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "") {
					$CellText = "";
					$ErrorDesc .= "Section Code,";
				}
				$SectionCodeList .= $CellText;

				//QUESTION SNO
				++$ColumnIndex;

				//QUESTION TEXT
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "") {
					$CellText = "";
					$ErrorDesc .= "Question Text,";
				}
				$QuestionTextList .= $CellText;

				//QUESTION TYPE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "") {
					$CellText = "";
					$ErrorDesc .= "Question Type,";
				}
				$QuestionType = strtoupper(trim($CellText));
				$QuestionTypeList .= $QuestionType;

				//OPTION 1 TEXT
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "") {
					$CellText = "";
					$ErrorDesc .= "Option 1 Text,";
				}
				$Option1TextList .= $CellText;

				//OPTION 2 TEXT
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "") {
					$CellText = "";
					$ErrorDesc .= "Option 2 Text,";
				}
				$Option2TextList .= $CellText;

				//OPTION 3 TEXT
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "") {
					$CellText = "";
					$ErrorDesc .= "Option 3 Text,";
				}
				$Option3TextList .= $CellText;

				//OPTION 4 TEXT
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "") {
					$CellText = "";
					$ErrorDesc .= "Option 4 Text,";
				}
				$Option4TextList .= $CellText;

				//OPTION 5 TEXT
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "") {
					$CellText = "";
					if ($QuestionType == "PMQ") $ErrorDesc .= "Option 5 Text,";
				}
				$Option5TextList .= $CellText;

				//CORRECT OPTION
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "") {
					$CellText = "0";
					if ($QuestionType == "MCQ") $ErrorDesc .= "Correct Option,";
				}
				$CorrectOptionList .= $CellText;

				//MARKS
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "") {
					$CellText = "";
					if ($QuestionType == "MCQ") $CellText = "1";
				}
				$MarkList .= $CellText;

				//REVERSE VALUE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "" || $QuestionType == "MCQ") $CellText = "FALSE";
				$ReverseValueList .= $CellText;

				//QUESTION IMAGE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
					$CellText = "";
				else {
					$ImgIndex = -1;
					if (!is_null($_FILES['questionimages']['name']))
						$ImgIndex = array_search(trim($CellText), $_FILES['questionimages']['name']);
					if ($ImgIndex < 0)
						$ErrorDesc .= "Question Image,";
				}
				$QuestionImageList .= $CellText;

				//OPTION 1 IMAGE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
					$CellText = "";
				else {
					$ImgIndex = -1;
					if (!is_null($_FILES['questionimages']['name']))
						$ImgIndex = array_search(trim($CellText), $_FILES['questionimages']['name']);
					if ($ImgIndex < 0)
						$ErrorDesc .= "Option 1 Image,";
				}
				$Option1ImageList .= $CellText;

				//OPTION 2 IMAGE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
					$CellText = "";
				else {
					$ImgIndex = -1;
					if (!is_null($_FILES['questionimages']['name']))
						$ImgIndex = array_search(trim($CellText), $_FILES['questionimages']['name']);
					if ($ImgIndex < 0)
						$ErrorDesc .= "Option 2 Image,";
				}
				$Option2ImageList .= $CellText;

				//OPTION 3 IMAGE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
					$CellText = "";
				else {
					$ImgIndex = -1;
					if (!is_null($_FILES['questionimages']['name']))
						$ImgIndex = array_search(trim($CellText), $_FILES['questionimages']['name']);
					if ($ImgIndex < 0)
						$ErrorDesc .= "Option 3 Image,";
				}
				$Option3ImageList .= $CellText;

				//OPTION 4 IMAGE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
					$CellText = "";
				else {
					$ImgIndex = -1;
					if (!is_null($_FILES['questionimages']['name']))
						$ImgIndex = array_search(trim($CellText), $_FILES['questionimages']['name']);
					if ($ImgIndex < 0)
						$ErrorDesc .= "Option 4 Image,";
				}
				$Option4ImageList .= $CellText;

				//OPTION 5 IMAGE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(++$ColumnIndex, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
					$CellText = "";
				else {
					$ImgIndex = -1;
					if (!is_null($_FILES['questionimages']['name']))
						$ImgIndex = array_search(trim($CellText), $_FILES['questionimages']['name']);
					if ($ImgIndex < 0)
						$ErrorDesc .= "Option 5 Image,";
				}
				$Option5ImageList .= $CellText;

				if (strlen($ErrorDesc) > 0) {
					$ErrorDesc = substr($ErrorDesc, 0, -1);
					$ErrorDesc = str_replace(",", ", ", $ErrorDesc);
					$ErrorDesc = "Row " . ($RowIndex - 1) . ": Missing " . $ErrorDesc . ".\n";
				}

				$ErrorResponse .= $ErrorDesc;
			}

			if (trim($ErrorResponse) != "") {
				print "The following errors have been found in the uploaded question data file.\n";
				print $ErrorResponse;
				print "\nPlease correct these errors in the file and upload again!";
				return;
			}
		}
		else {
			print "Question Upload Data Not Found!";
			return;
		}

		if (trim($ErrorResponse) == "") {
			$QuestionTextList = str_replace("'", "`", $QuestionTextList);
			$Option1TextList  = str_replace("'", "`", $Option1TextList);
			$Option2TextList  = str_replace("'", "`", $Option2TextList);
			$Option3TextList  = str_replace("'", "`", $Option3TextList);
			$Option4TextList  = str_replace("'", "`", $Option4TextList);
			$Option5TextList  = str_replace("'", "`", $Option5TextList);

			$SaveRequestData = array(
				'QuestionPaperId' 	=> $QuestionPaperId,
				'LanguageId' 		=> $LanguageId,
				'PartCodeList' 		=> $PartCodeList,
				'SectionCodeList' 	=> $SectionCodeList,
				'QuestionTextList' 	=> $QuestionTextList,
				'QuestionTypeList' 	=> $QuestionTypeList,
				'Option1TextList' 	=> $Option1TextList,
				'Option2TextList' 	=> $Option2TextList,
				'Option3TextList' 	=> $Option3TextList,
				'Option4TextList' 	=> $Option4TextList,
				'Option5TextList' 	=> $Option5TextList,
				'CorrectOptionList' => $CorrectOptionList,
				'MarkList' 			=> $MarkList,
				'ReverseValueList' 	=> $ReverseValueList,
				'QuestionImageList' => $QuestionImageList,
				'Option1ImageList' 	=> $Option1ImageList,
				'Option2ImageList' 	=> $Option2ImageList,
				'Option3ImageList' 	=> $Option3ImageList,
				'Option4ImageList' 	=> $Option4ImageList,
				'Option5ImageList' 	=> $Option5ImageList,
				'user_id' 			=> $user['id']
			);

			$SaveResponseData = $this->content->save_question_paper_data($SaveRequestData);
			if (count($SaveResponseData) > 0) {
				print "Question Paper Data has been uploaded to the database!\n\n";

				if (strtoupper($SaveResponseData[0]['o_message']) == "SUCCESS") {
					$SrcImageArray  = explode('[@]', $SaveResponseData[0]['o_src_img_list']);
					$DestImageArray = explode('[@]', $SaveResponseData[0]['o_dest_img_list']);

					$ImgIndex               = -1;
					$QuestionImageFileCount = isset($_FILES['questionimages']['name']) ? count($_FILES['questionimages']['name']) : 0;
					if ($QuestionImageFileCount > 0) {
						$this->upload->initialize($this->set_question_image_upload_options());

						$Files    = $_FILES;
						$ImgIndex = -1;
						for ($i = 0; $i < $QuestionImageFileCount; $i++) {
							if ($SrcImageArray) {
								$ImgIndex = array_search($Files['questionimages']['name'][$i], $SrcImageArray);
								if ($ImgIndex >= 0) {
									$_FILES['questionimages']['name']     = $DestImageArray[$ImgIndex];
									$_FILES['questionimages']['type']     = $Files['questionimages']['type'][$i];
									$_FILES['questionimages']['tmp_name'] = $Files['questionimages']['tmp_name'][$i];
									$_FILES['questionimages']['error']    = $Files['questionimages']['error'][$i];
									$_FILES['questionimages']['size']     = $Files['questionimages']['size'][$i];
									$this->upload->do_upload('questionimages');

									$this->ScaleQuestionImageWithAspectRatioForHeight(QUESTION_IMAGES, $DestImageArray[$ImgIndex]);
								}
							}
						}

						print "Question Paper Images have been uploaded successfully!\n\n";
					}

					print "Question Paper has been created successfully!\n\n";
					return;
				}
				else {
					print $SaveResponseData[0]['o_message'];
					return;
				}
			}
			else {
				print "Error while creating Question Paper";
				return;
			}
		}
	}

	public function UploadQuestionPaperData()
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		print "\nQuestion Paper data upload started...";

		$ErrorDesc = "";

		$QuestionId = 0;
		$Extension  = "";

		$ResponseArray = array();
		$Files         = $_FILES;

		$this->load->library('upload');
		$this->upload->initialize($this->set_question_paper_upload_options());

		//If Single File
		$ExcelFileName  = "";
		$ExcelDataArray = array();

		if (isset($_FILES['questionexcel']['name'])) {
			$ErrorResponse = "";

			$OriginalExcelFileName = $_FILES['questionexcel']['name'];

			$Extension     = pathinfo($_FILES['questionexcel']['name'], PATHINFO_EXTENSION);
			$ExcelFileName = "QuestionPaperBulkUploadData_" . date("Ymdhis.") . $Extension;

			$_FILES['questionexcel']['name'] = $ExcelFileName;
			$this->upload->do_upload('questionexcel');
			$Response[] = $this->upload->data();

			$ExcelFilePath = QUESTION_PAPER_DATA . $ExcelFileName;
			print "\n\nQuestion Paper Data File '$OriginalExcelFileName' has been uploaded...\n\n";

			$this->load->library('Excel');
			try
			{
				$inputFileType  = PHPExcel_IOFactory::identify($ExcelFilePath);
				$objExcelReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel    = $objExcelReader->load($ExcelFilePath);
			}
			catch (Exception $e)
			{
				die('Error loading file "' . pathinfo($ExcelFilePath, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			}

			$QuestionPaperId = intval($this->input->post('hidQuestionPaperId'));

			$Delimiter         = "[@]";
			$PartCodeList      = "";
			$SectionCodeList   = "";
			$QuestionTextList  = "";
			$QuestionTypeList  = "";
			$Option1TextList   = "";
			$Option2TextList   = "";
			$Option3TextList   = "";
			$Option4TextList   = "";
			$Option5TextList   = "";
			$CorrectOptionList = "";
			$MarkList          = "";
			$ReverseValueList  = "";
			$QuestionImageList = "";
			$Option1ImageList  = "";
			$Option2ImageList  = "";
			$Option3ImageList  = "";
			$Option4ImageList  = "";
			$Option5ImageList  = "";

			$objPHPExcel->setActiveSheetIndex(0);                    //ALWAYS READ DATA FROM FIRST SHEET
			$objExcelWorkSheet = $objPHPExcel->getActiveSheet();    //GET FIRST SHEET

			$ColumnIndex = -1;
			for ($RowIndex = 2; $RowIndex <= $objExcelWorkSheet->getHighestRow(); $RowIndex++)
			{
				$ColumnIndex = -1;
				if ($RowIndex > 2)
				{
					$PartCodeList 		.= $Delimiter;
					$SectionCodeList 	.= $Delimiter;
					$QuestionTextList 	.= $Delimiter;
					$QuestionTypeList 	.= $Delimiter;
					$Option1TextList 	.= $Delimiter;
					$Option2TextList 	.= $Delimiter;
					$Option3TextList 	.= $Delimiter;
					$Option4TextList 	.= $Delimiter;
					$Option5TextList 	.= $Delimiter;
					$CorrectOptionList 	.= $Delimiter;
					$MarkList 			.= $Delimiter;
					$ReverseValueList 	.= $Delimiter;
					$QuestionImageList 	.= $Delimiter;
					$Option1ImageList 	.= $Delimiter;
					$Option2ImageList 	.= $Delimiter;
					$Option3ImageList 	.= $Delimiter;
					$Option4ImageList 	.= $Delimiter;
					$Option5ImageList 	.= $Delimiter;
				}

				$ErrorDesc = "";

				//PART CODE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(0, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
				{
					$CellText = "";
					$ErrorDesc .= "Part,";
				}
				$PartCodeList .= $CellText;

				//SECTION CODE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(1, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
				{
					$CellText = "";
					$ErrorDesc .= "Section Code,";
				}
				$SectionCodeList .= $CellText;

				//QUESTION TEXT
				$QuestionText = $objExcelWorkSheet->getCellByColumnAndRow(3, $RowIndex)->getCalculatedValue();
				if (is_null($QuestionText) || trim($QuestionText) == "")
				{
					$QuestionText = "";
					$ErrorDesc .= "Question Text,";
				}
				$QuestionTextList .= trim($QuestionText);

				//QUESTION TYPE
				$QuestionTypeText = $objExcelWorkSheet->getCellByColumnAndRow(4, $RowIndex)->getCalculatedValue();
				if (is_null($QuestionTypeText) || trim($QuestionTypeText) == "")
				{
					$QuestionTypeText = "";
					$ErrorDesc .= "Question Type,";
				}
				$QuestionType = strtoupper(trim($QuestionTypeText));
				$QuestionTypeList .= $QuestionType;

				//CORRECT OPTION
				$CorrectOption = $objExcelWorkSheet->getCellByColumnAndRow(10, $RowIndex)->getCalculatedValue();
				if (is_null($CorrectOption) || trim($CorrectOption) == "")
				{
					$CorrectOption = "0";
					if ($QuestionType == "MCQ") $ErrorDesc .= "Correct Option,";
				}
				$CorrectOptionList .= $CorrectOption;

				//MARKS
				$Marks = "0";
				if ($QuestionType == "MCQ" || $QuestionType == "TFQ") $Marks = "1";
				$MarkList .= $Marks;

				//REVERSE VALUE
				$ReverseValue = $objExcelWorkSheet->getCellByColumnAndRow(12, $RowIndex)->getCalculatedValue();
				if (is_null($ReverseValue) || trim($ReverseValue) == "" || $QuestionType == "MCQ") $ReverseValue = "NO";
				$ReverseValueList .= strtoupper(trim($ReverseValue));

				//QUESTION IMAGE
				$QuestionImage = $objExcelWorkSheet->getCellByColumnAndRow(13, $RowIndex)->getCalculatedValue();
				if (is_null($QuestionImage) || trim($QuestionImage) == "") $QuestionImage = "";
				$QuestionImageList .= $QuestionImage;

				//OPTION 1 TEXT
				$Option1Text = $objExcelWorkSheet->getCellByColumnAndRow(5, $RowIndex)->getCalculatedValue();
				if (is_null($Option1Text) || trim($Option1Text) == "") $Option1Text = "";
				$Option1TextList .= trim($Option1Text);

				//OPTION 2 TEXT
				$Option2Text = $objExcelWorkSheet->getCellByColumnAndRow(6, $RowIndex)->getCalculatedValue();
				if (is_null($Option2Text) || trim($Option2Text) == "") $Option2Text = "";
				$Option2TextList .= trim($Option2Text);

				//OPTION 3 TEXT
				$Option3Text = $objExcelWorkSheet->getCellByColumnAndRow(7, $RowIndex)->getCalculatedValue();
				if (is_null($Option3Text) || trim($Option3Text) == "") $Option3Text = "";
				$Option3TextList .= trim($Option3Text);

				//OPTION 4 TEXT
				$Option4Text = $objExcelWorkSheet->getCellByColumnAndRow(8, $RowIndex)->getCalculatedValue();
				if (is_null($Option4Text) || trim($Option4Text) == "") $Option4Text = "";
				$Option4TextList .= trim($Option4Text);

				//OPTION 5 TEXT
				$Option5Text = $objExcelWorkSheet->getCellByColumnAndRow(9, $RowIndex)->getCalculatedValue();
				if (is_null($Option5Text) || trim($Option5Text) == "") $Option5Text = "";
				$Option5TextList .= trim($Option5Text);

				//OPTION 1 IMAGE
				$Option1Image = $objExcelWorkSheet->getCellByColumnAndRow(14, $RowIndex)->getCalculatedValue();
				if (is_null($Option1Image) || trim($Option1Image) == "") $Option1Image = "";
				$Option1ImageList .= strtoupper(trim($Option1Image));

				//OPTION 2 IMAGE
				$Option2Image = $objExcelWorkSheet->getCellByColumnAndRow(15, $RowIndex)->getCalculatedValue();
				if (is_null($Option2Image) || trim($Option2Image) == "") $Option2Image = "";
				$Option2ImageList .= strtoupper(trim($Option2Image));

				//OPTION 3 IMAGE
				$Option3Image = $objExcelWorkSheet->getCellByColumnAndRow(16, $RowIndex)->getCalculatedValue();
				if (is_null($Option3Image) || trim($Option3Image) == "") $Option3Image = "";
				$Option3ImageList .= strtoupper(trim($Option3Image));

				//OPTION 4 IMAGE
				$Option4Image = $objExcelWorkSheet->getCellByColumnAndRow(17, $RowIndex)->getCalculatedValue();
				if (is_null($Option4Image) || trim($Option4Image) == "") $Option4Image = "";
				$Option4ImageList .= strtoupper(trim($Option4Image));

				//OPTION 5 IMAGE
				$Option5Image = $objExcelWorkSheet->getCellByColumnAndRow(18, $RowIndex)->getCalculatedValue();
				if (is_null($Option5Image) || trim($Option5Image) == "") $Option5Image = "";
				$Option5ImageList .= strtoupper(trim($Option5Image));

				if (trim($Option1Text) == "" && trim($Option1Image) == "") $ErrorDesc .= "Option 1 Text and Image,";
				if (trim($Option2Text) == "" && trim($Option2Image) == "") $ErrorDesc .= "Option 2 Text and Image,";
				if (($QuestionType == "PMQ" || $QuestionType == "MCQ") && trim($Option3Text) == "" && trim($Option3Image) == "") $ErrorDesc .= "Option 3 Text and Image,";
				if (($QuestionType == "PMQ" || $QuestionType == "MCQ") && trim($Option4Text) == "" && trim($Option4Image) == "") $ErrorDesc .= "Option 4 Text and Image,";
				if (($QuestionType == "PMQ") && trim($Option5Text) == "" && trim($Option5Image) == "") $ErrorDesc .= "Option 5 Text and Image,";

				if (strlen($ErrorDesc) > 0)
				{
					$ErrorDesc = substr($ErrorDesc, 0, -1);
					$ErrorDesc = str_replace(",", ", ", $ErrorDesc);
					$ErrorDesc = "Row " . ($RowIndex - 1) . ": Missing " . $ErrorDesc . ".\n";
				}

				$ErrorResponse .= $ErrorDesc;
			}

			if (trim($ErrorResponse) != "")
			{
				print "\n\nThe following errors have been found in the uploaded question data file.";
				print $ErrorResponse;
				print "\nPlease correct these errors in the file and upload again!";
				return;
			}
		}
		else
		{
			print "\n\nQuestion Upload Data Not Found!";
			return;
		}

		if (trim($ErrorResponse) == "")
		{
			$QuestionTextList = str_replace("'", "`", $QuestionTextList);
			$Option1TextList  = str_replace("'", "`", $Option1TextList);
			$Option2TextList  = str_replace("'", "`", $Option2TextList);
			$Option3TextList  = str_replace("'", "`", $Option3TextList);
			$Option4TextList  = str_replace("'", "`", $Option4TextList);
			$Option5TextList  = str_replace("'", "`", $Option5TextList);

			$QuestionTextList = str_replace("\n", "<br>", $QuestionTextList);
			$Option1TextList  = str_replace("\n", "<br>", $Option1TextList);
			$Option2TextList  = str_replace("\n", "<br>", $Option2TextList);
			$Option3TextList  = str_replace("\n", "<br>", $Option3TextList);
			$Option4TextList  = str_replace("\n", "<br>", $Option4TextList);
			$Option5TextList  = str_replace("\n", "<br>", $Option5TextList);

			$SaveRequestData = array(
				'QuestionPaperId' 	=> $QuestionPaperId,
				'PartCodeList' 		=> $PartCodeList,
				'SectionCodeList' 	=> $SectionCodeList,
				'QuestionTextList' 	=> $QuestionTextList,
				'QuestionTypeList' 	=> $QuestionTypeList,
				'Option1TextList' 	=> $Option1TextList,
				'Option2TextList' 	=> $Option2TextList,
				'Option3TextList' 	=> $Option3TextList,
				'Option4TextList' 	=> $Option4TextList,
				'Option5TextList' 	=> $Option5TextList,
				'CorrectOptionList' => $CorrectOptionList,
				'MarkList' 			=> $MarkList,
				'ReverseValueList' 	=> $ReverseValueList,
				'QuestionImageList' => $QuestionImageList,
				'Option1ImageList' 	=> $Option1ImageList,
				'Option2ImageList' 	=> $Option2ImageList,
				'Option3ImageList' 	=> $Option3ImageList,
				'Option4ImageList' 	=> $Option4ImageList,
				'Option5ImageList' 	=> $Option5ImageList,
				'user_id' 			=> $user['id']
			);

			//print_r($SaveRequestData);
			//return;

			$SaveResponseData = $this->content->upload_question_paper_data($SaveRequestData);
			if (count($SaveResponseData) > 0)
			{
				print "\nQuestion Paper Data has been uploaded to the database...";

				if (strtoupper($SaveResponseData[0]['o_message']) == "SUCCESS")
				{
					$SrcImageArray  = explode('[@]', $SaveResponseData[0]['o_src_img_list']);
					$DestImageArray = explode('[@]', $SaveResponseData[0]['o_dest_img_list']);

					/*print "Uploaded Images: ";
					print_r($Files['questionimages']['name']);
					print "SrcImageArray: ";
					print_r($SrcImageArray);
					print "DestinationImageArray: ";
					print_r($DestImageArray);
					return;*/

					$ImgIndex               = -1;
					$QuestionImageFileCount = isset($_FILES['questionimages']['name']) ? count($_FILES['questionimages']['name']) : 0;
					if ($QuestionImageFileCount > 0)
					{
						$this->upload->initialize($this->set_question_image_upload_options());

						//print "\n Destination Image Array: ";
						//print_r($DestImageArray);

						print "\n";
						$Files    = $_FILES;
						$ImgIndex = -1;
						for ($i = 0; $i < $QuestionImageFileCount; $i++) {
							if ($SrcImageArray) {
								$ImgIndex = array_search(strtoupper($Files['questionimages']['name'][$i]), $SrcImageArray);
								if ($ImgIndex >= 0) {
									$_FILES['questionimages']['name']     = $DestImageArray[$ImgIndex];
									$_FILES['questionimages']['type']     = $Files['questionimages']['type'][$i];
									$_FILES['questionimages']['tmp_name'] = $Files['questionimages']['tmp_name'][$i];
									$_FILES['questionimages']['error']    = $Files['questionimages']['error'][$i];
									$_FILES['questionimages']['size']     = $Files['questionimages']['size'][$i];
									$this->upload->do_upload('questionimages');

									$this->ScaleQuestionImageWithAspectRatioForHeight(QUESTION_IMAGES, $DestImageArray[$ImgIndex]);
								}
							}
						}

						print "\nQuestion Paper Images have been uploaded...";
						print "\nUploaded Images:";
						foreach($Files['questionimages']['name'] as $UploadedImage) print "\n $UploadedImage";
					}

					print "\n\nQuestion Paper upload completed successfully!";
					return;
				}
				else
				{
					print $SaveResponseData[0]['o_message'];
					return;
				}
			}
			else
			{
				print "\n\nError while creating Question Paper!\n\n";
				return;
			}
		}
	}

	public function UploadQuestionPaperVersionData()
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		print "Question Paper Version data upload started...";

		$QuestionId = 0;
		$Extension  = "";

		$ResponseArray = array();
		$files         = $_FILES;

		$this->load->library('upload');
		$this->upload->initialize($this->set_question_paper_upload_options());

		//If Single File
		$ExcelFileName  = "";
		$ExcelDataArray = array();

		if (isset($_FILES['questionexcel']['name'])) {
			$ErrorResponse = "";

			$OriginalExcelFileName = $_FILES['questionexcel']['name'];

			$Extension     = pathinfo($_FILES['questionexcel']['name'], PATHINFO_EXTENSION);
			$ExcelFileName = str_replace("." . $Extension, "", $_FILES['questionexcel']['name']) . date("_Ymdhis.") . $Extension;

			$_FILES['questionexcel']['name'] = $ExcelFileName;
			$this->upload->do_upload('questionexcel');
			$Response[] = $this->upload->data();

			$ExcelFilePath = QUESTION_PAPER_DATA . $ExcelFileName;

			print "\n\nQuestion Paper Version Data File '$OriginalExcelFileName' has been uploaded...\n\n";

			$this->load->library('Excel');
			try
			{
				$inputFileType  = PHPExcel_IOFactory::identify($ExcelFilePath);
				$objExcelReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel    = $objExcelReader->load($ExcelFilePath);
			}
			catch (Exception $e)
			{
				die('Error loading file "' . pathinfo($ExcelFilePath, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			}

			$QuestionPaperId = intval($this->input->post('hidQuestionPaperId'));

			$objPHPExcel->setActiveSheetIndex(0);                    //ALWAYS READ DATA FROM FIRST SHEET
			$objExcelWorkSheet = $objPHPExcel->getActiveSheet();    //GET FIRST SHEET

			$ValidLanguageNames = array();
			$LanguageList = $this->content->get_version_language_list_data();
			if (count($LanguageList) > 0)
			{
				foreach ($LanguageList as $Language)
				{
					$ValidLanguageNames[] = strtoupper($Language['language_name']);
				}
			}

			/*print_r($ValidLanguageNames);
			die;*/

			$QuestionType = "";
			$LanguageName = "";
			$LangIndex = -1;
			$ColumnIndex = -1;
			$ValidCount = 0;

			$Delimiter         		= "[@]";
			$QuestionIdList      	= "";
			$RowIndexList      		= "";
			$LanguageNameList   	= "";
			$QuestionTextList  		= "";
			$Option1TextList   		= "";
			$Option2TextList   		= "";
			$Option3TextList   		= "";
			$Option4TextList   		= "";
			$Option5TextList   		= "";

			for ($RowIndex = 2; $RowIndex <= $objExcelWorkSheet->getHighestRow(); $RowIndex++)
			{
				//ROW INDEX
				$RIndex = $RowIndex - 1;

				$LanguageName = $objExcelWorkSheet->getCellByColumnAndRow(5, $RowIndex)->getCalculatedValue();
				print "identified Language: $LanguageName\n";

				$strLanguageName = strtoupper(trim($LanguageName));
				if ($strLanguageName == 'ENGLISH') continue;

				$LangIndex = array_search($strLanguageName, $ValidLanguageNames);
				if ($LangIndex < 0)
				{
					$ErrorResponse .= "Row $RIndex: Missing or Invalid Language. Skipping the row...\n";
					continue;
				}

					//QUESTION TYPE
					$QuestionType = $objExcelWorkSheet->getCellByColumnAndRow(3, $RowIndex)->getCalculatedValue();

					//QUESTION ID
					$QuestionId = $objExcelWorkSheet->getCellByColumnAndRow(4, $RowIndex)->getCalculatedValue();
					if (is_null($QuestionId) || trim($QuestionId) == "")
					{
						print "ROW $RIndex: Question Id not found. Skipping the row...\n";
						continue;
					}

					//QUESTION TEXT
					$QuestionText = $objExcelWorkSheet->getCellByColumnAndRow(6, $RowIndex)->getCalculatedValue();
					if (is_null($QuestionText) || trim($QuestionText) == "")
					{
						print "ROW $RIndex: Question Text not found. Skipping the row...\n";
						continue;
					}

					//OPTION 1 TEXT
					$Option1Text = $objExcelWorkSheet->getCellByColumnAndRow(7, $RowIndex)->getCalculatedValue();
					if (is_null($Option1Text) || trim($Option1Text) == "")  $Option1Text = "";

					//OPTION 2 TEXT
					$Option2Text = $objExcelWorkSheet->getCellByColumnAndRow(8, $RowIndex)->getCalculatedValue();
					if (is_null($Option2Text) || trim($Option2Text) == "")  $Option2Text = "";

					//OPTION 3 TEXT
					$Option3Text = $objExcelWorkSheet->getCellByColumnAndRow(9, $RowIndex)->getCalculatedValue();
					if (is_null($Option3Text) || trim($Option3Text) == "")  $Option3Text = "";

					//OPTION 4 TEXT
					$Option4Text = $objExcelWorkSheet->getCellByColumnAndRow(10, $RowIndex)->getCalculatedValue();
					if (is_null($Option4Text) || trim($Option4Text) == "")  $Option4Text = "";

					//OPTION 5 TEXT
					$Option5Text = "";
					if (strtoupper($QuestionType) == "PMQ")
					{
						$Option5Text = $objExcelWorkSheet->getCellByColumnAndRow(11, $RowIndex)->getCalculatedValue();
						if (is_null($Option5Text) || trim($Option5Text) == "") $Option5Text = "";
					}

				print "ROW $RIndex: Necessary field data found for '$strLanguageName'. Added to version upload process...\n";

					$ValidCount++;
					if ($ValidCount > 1)
					{
						$QuestionIdList 	.= $Delimiter;
						$RowIndexList 		.= $Delimiter;
						$LanguageNameList 	.= $Delimiter;
						$QuestionTextList 	.= $Delimiter;
						$Option1TextList 	.= $Delimiter;
						$Option2TextList 	.= $Delimiter;
						$Option3TextList 	.= $Delimiter;
						$Option4TextList 	.= $Delimiter;
						$Option5TextList 	.= $Delimiter;
					}

					$QuestionIdList 	.= $QuestionId;
					$RowIndexList 		.= $RIndex;
					$LanguageNameList 	.= $LanguageName;
					$QuestionTextList 	.= $QuestionText;
					$Option1TextList 	.= $Option1Text;
					$Option2TextList 	.= $Option2Text;
					$Option3TextList 	.= $Option3Text;
					$Option4TextList 	.= $Option4Text;
					$Option5TextList 	.= $Option5Text;
				}
		}
		else
		{
			print "\n\nQuestion Upload Data Not Found!";
			return;
		}

		print "\n\nProceeding to upload the selected data...\n";

		$QuestionTextList = str_replace("'", "`", $QuestionTextList);
		$Option1TextList  = str_replace("'", "`", $Option1TextList);
		$Option2TextList  = str_replace("'", "`", $Option2TextList);
		$Option3TextList  = str_replace("'", "`", $Option3TextList);
		$Option4TextList  = str_replace("'", "`", $Option4TextList);
		$Option5TextList  = str_replace("'", "`", $Option5TextList);

		$QuestionTextList = str_replace("\n", "<br>", $QuestionTextList);
		$Option1TextList  = str_replace("\n", "<br>", $Option1TextList);
		$Option2TextList  = str_replace("\n", "<br>", $Option2TextList);
		$Option3TextList  = str_replace("\n", "<br>", $Option3TextList);
		$Option4TextList  = str_replace("\n", "<br>", $Option4TextList);
		$Option5TextList  = str_replace("\n", "<br>", $Option5TextList);

		$SaveRequestData = array(
			'QuestionIdList' 	=> $QuestionIdList,
			'RowIndexList' 		=> $RowIndexList,
			'LanguageNameList'	=> $LanguageNameList,
			'QuestionTextList' 	=> $QuestionTextList,
			'Option1TextList' 	=> $Option1TextList,
			'Option2TextList' 	=> $Option2TextList,
			'Option3TextList' 	=> $Option3TextList,
			'Option4TextList' 	=> $Option4TextList,
			'Option5TextList' 	=> $Option5TextList,
			'user_id' 			=> $user['id']
		);

		$ResponseMessage = $this->content->upload_question_paper_version_data($SaveRequestData);
		$ResponseMessage = str_replace("[@]", "\n", $ResponseMessage);
		print $ResponseMessage;
	}

	public function ScaleQuestionImage($Imagepath, $ImageName = "", $Width = OPTION_IMAGE_SCALE_VALUE, $Height = OPTION_IMAGE_SCALE_VALUE)
	{
		error_reporting(0);
		if (trim($ImageName) == "") return false;

		$SourceImageFolder      = $Imagepath;
		$DestinationImageFolder = $SourceImageFolder . "/scaled/";

		$SourceImageFilePath      = $SourceImageFolder . $ImageName;
		$DestinationImageFilePath = $DestinationImageFolder . $ImageName;

		if (!file_exists($SourceImageFilePath)) return false;

		if (!file_exists($DestinationImageFilePath)) {
			$SourceFileNameParts = explode(".", $ImageName);
			$SourceExtension     = end($SourceFileNameParts);

			$SourceImage = null;

			switch (strtoupper($SourceExtension)) {
				case "PNG":
					$SourceImage = imagecreatefrompng($SourceImageFilePath);
					break;

				case "JPG":
				case "JPEG":
					$SourceImage = imagecreatefromjpeg($SourceImageFilePath);
					break;

				case "GIF":

					$SourceImage = imagecreatefromgif($SourceImageFilePath);
					break;
			}

			if ($SourceImage) {
				$SourceImageWidth  = imagesx($SourceImage);
				$SourceImageHeight = imagesy($SourceImage);

				$DestinationImage = imagecreatetruecolor($Width, $Height);
				imagecopyresampled($DestinationImage, $SourceImage, 0, 0, 0, 0, $Width, $Height, $SourceImageWidth, $SourceImageHeight);

				switch (strtoupper($SourceExtension)) {
					case "PNG":
						imagepng($DestinationImage, $DestinationImageFilePath);
						break;

					case "JPG":
					case "JPEG":
						imagejpeg($DestinationImage, $DestinationImageFilePath);
						break;

					case "GIF":
						imagegif($DestinationImage, $DestinationImageFilePath);
						break;
				}

				imagedestroy($SourceImage);
				imagedestroy($DestinationImage);
			}
		}

		return true;
	}

	function ScaleQuestionImageWithAspectRatioForHeight($ImagePath, $ImageName)
	{
		$ImageName = trim($ImageName);
		if ($ImageName == "") return false;
		$Height = (strpos($ImageName, "_") < 0) ? QUESTION_IMAGE_SCALE_VALUE : OPTION_IMAGE_SCALE_VALUE;

		$Width            = $Height;
		$ImageSize        = getimagesize(realpath($ImagePath) . "/" . $ImageName);
		$HeightWidthRatio = $ImageSize[1] / $ImageSize[0];
		$Width            = ($HeightWidthRatio > 1) ? ($Height * $HeightWidthRatio) : ($Height / $HeightWidthRatio);
		$this->ScaleQuestionImage($ImagePath, $ImageName, $Width, $Height);
		return $ImageName;
	}

	function ScaleQuestionImageWithAspectRatioForWidth($ImagePath, $ImageName)
	{
		$ImageName = trim($ImageName);
		if ($ImageName == "") return false;
		$Width = (strpos($ImageName, "_") < 0) ? QUESTION_IMAGE_SCALE_VALUE : OPTION_IMAGE_SCALE_VALUE;

		$Height           = $Width;
		$ImageSize        = getimagesize(realpath($ImagePath) . "/" . $ImageName);
		$WidthHeightRatio = $ImageSize[0] / $ImageSize[1];
		$Height           = ($WidthHeightRatio > 1) ? ($Width / $WidthHeightRatio) : ($Width * $WidthHeightRatio);
		$this->ScaleQuestionImage($ImagePath, $ImageName, $Width, $Height);
		return $ImageName;
	}

	public function ScaleAllQuestionImages()
	{
		//DELETE ALL FILES IN DESTINATION FOLDER
		array_map('unlink', glob(realpath(QUESTION_IMAGES_SCALED) . "/*"));
		$QuestionImageArray = scandir(realpath(QUESTION_IMAGES));

		$ImageFile = "";
		foreach ($QuestionImageArray as $Image) {
			if (trim($Image) == "." || trim($Image) == "..") continue;
			if (is_dir(realpath(QUESTION_IMAGES) . "/" . $Image)) continue;
			$this->ScaleQuestionImageWithAspectRatioForHeight(QUESTION_IMAGES, $Image);
		}
	}

	public function ScaleAllSampleQuestionImages()
	{
		//DELETE ALL FILES IN DESTINATION FOLDER
		array_map('unlink', glob(realpath(SAMPLE_QUESTION_IMAGES_SCALED) . "/*"));
		$QuestionImageArray = scandir(realpath(SAMPLE_QUESTION_IMAGES));

		$ImageFile = "";
		foreach ($QuestionImageArray as $Image) {
			if (trim($Image) == "." || trim($Image) == "..") continue;
			if (is_dir(realpath(SAMPLE_QUESTION_IMAGES) . "/" . $Image)) continue;
			$this->ScaleQuestionImageWithAspectRatioForHeight(SAMPLE_QUESTION_IMAGES, $Image);
		}
	}

	private function set_question_paper_upload_options()
	{
		$config                  = array();
		$config['upload_path']   = QUESTION_PAPER_DATA;
		$config['allowed_types'] = 'xls|xlsx';
		$config['max_size']      = '0'; // 0 = no file size limit
		$config['max_width']     = '0';
		$config['max_height']    = '0';
		$config['overwrite']     = TRUE;
		return $config;
	}

	private function set_question_image_upload_options()
	{
		$config                  = array();
		$config['upload_path']   = QUESTION_IMAGES;
		$config['allowed_types'] = 'jpg|png|gif|jpeg|JPG|PNG|GIF|JPEG';
		$config['max_size']      = '0';
		$config['max_width']     = '0';
		$config['max_height']    = '0';
		$config['overwrite']     = TRUE;
		return $config;
	}

	public function get_preview_question_paper_data($QuestionPaperId = 0)
	{
		$user         = $this->pramaan->_check_module_task_auth(true);
		$ResponseData = $this->content->get_preview_question_paper_data($QuestionPaperId);
		echo json_encode($ResponseData);
	}

	public function preview_question_paper($QuestionPaperId = 0, $LanguageId = 1)
	{
		$user = $this->pramaan->_check_module_task_auth(true);
		$data = array(
			'page' 				=> 'preview_question_paper',
			'parent_page' 		=> "content/question_papers",
			'parent_page_title' => "Question Papers",
			'title' 			=> "Question Paper Preview",
			'module' 			=> "content",
			'user_role_id' 		=> $user['user_group_id'],
			'user_id' 			=> $user['id'],
			'question_paper_id' => $QuestionPaperId,
			'language_id'		  	=> $LanguageId,
			'language_list'		  	=> $this->content->get_language_list_data(),
			'question_paper_data' 	=> $this->content->get_question_paper_detail($QuestionPaperId),
			'ResponseData' 			=> $this->content->get_preview_question_paper_data($QuestionPaperId, $LanguageId)
		);

		/*print_r($data);
		die;*/

		$this->load->view('index', $data);
	}

	public function save_question_paper_detail()
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$RequestData = array(
			"question_paper_id" 	=> $this->input->post('question_paper_id'),
			"question_paper_title"  => $this->input->post('question_paper_title'),
			"duration_minutes"  	=> $this->input->post('duration_minutes'),
			"user_id"				=> $user['id']
		);

		$resp_data = $this->content->save_question_paper_detail($RequestData);
		echo json_encode($resp_data);
	}

	public function download_version_bulk_upload_template($QuestionPaperId = 0, $LanguageId = 0)
	{
		//ini_set("display_errors", "on");
		//error_reporting(E_ALL);
		
		$user = $this->pramaan->_check_module_task_auth(true);

		$SNO 			= "A";
		$PART 			= "B";
		$SECTION 		= "C";
		$TYPE 			= "D";
		$QUESTION_ID 	= "E";
		$LANGUAGE 		= "F";
		$QUESTION 		= "G";
		$OPTION1 		= "H";
		$OPTION2 		= "I";
		$OPTION3 		= "J";
		$OPTION4 		= "K";
		$OPTION5 		= "L";

		$HeaderFontStyleArray = array(
			'bold'  => true,
			'color' => array('rgb' => 'FFFFFF'),
			'size'  => 12
		);

		$HeaderStyleArray = array(
			'font' => $HeaderFontStyleArray
		);

		$this->load->library('Excel');
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Question Bulk Upload');

		$RowIndex = 0;
		$ActiveSheet = $this->excel->getActiveSheet();

		$ActiveSheet->setCellValue($SNO."1", 			'SNO');
		$ActiveSheet->setCellValue($PART."1", 			'PART');
		$ActiveSheet->setCellValue($SECTION."1", 		'SECTION');
		$ActiveSheet->setCellValue($TYPE."1", 			'TYPE');
		$ActiveSheet->setCellValue($QUESTION_ID."1", 	'QUESTION ID');
		$ActiveSheet->setCellValue($LANGUAGE."1", 		'LANGUAGE');
		$ActiveSheet->setCellValue($QUESTION."1", 		'QUESTION');
		$ActiveSheet->setCellValue($OPTION1."1", 		'OPTION1');
		$ActiveSheet->setCellValue($OPTION2."1", 		'OPTION2');
		$ActiveSheet->setCellValue($OPTION3."1", 		'OPTION3');
		$ActiveSheet->setCellValue($OPTION4."1", 		'OPTION4');
		$ActiveSheet->setCellValue($OPTION5."1", 		'OPTION5');

		$LanguageName = "";
		$LanguageData = $this->content->get_language_detail_for_id($LanguageId);
		if (count($LanguageData) > 0) $LanguageName = $LanguageData[0]['name'];

		$QuestionData = $this->content->get_language_version_question_data($QuestionPaperId, $LanguageId);
		$RowIndex = 1;
		$StartRowIndex = 2;
		$CellRange = "";

		$PreviousQuestionId = 0;
		$CurrentQuestionId = 0;
		$BorderSectionCount = 0;
		$BorderSectionFillColor = "";

		foreach ($QuestionData as $Question)
		{
			$RowIndex++;
			$CurrentQuestionId = $Question['question_id'];
			if ($LanguageName == "") $LanguageName = $Question['language_name'];

			$ActiveSheet->setCellValue($SNO.$RowIndex, 			($RowIndex - 1)."");
			$ActiveSheet->setCellValue($PART.$RowIndex, 		$Question['part_name']);
			$ActiveSheet->setCellValue($SECTION.$RowIndex, 		$Question['section_name']);
			$ActiveSheet->setCellValue($TYPE.$RowIndex, 		$Question['question_type_code']);
			$ActiveSheet->setCellValue($QUESTION_ID.$RowIndex, 	$Question['question_id']."");
			$ActiveSheet->setCellValue($LANGUAGE.$RowIndex, 	$Question['language_name']);
			$ActiveSheet->setCellValue($QUESTION.$RowIndex, 	str_replace("<br>", "\n", $Question['question_text']));
			$ActiveSheet->setCellValue($OPTION1.$RowIndex, 		str_replace("<br>", "\n", $Question['option1_text']));
			$ActiveSheet->setCellValue($OPTION2.$RowIndex, 		str_replace("<br>", "\n", $Question['option2_text']));
			$ActiveSheet->setCellValue($OPTION3.$RowIndex, 		str_replace("<br>", "\n", $Question['option3_text']));
			$ActiveSheet->setCellValue($OPTION4.$RowIndex, 		str_replace("<br>", "\n", $Question['option4_text']));
			$ActiveSheet->setCellValue($OPTION5.$RowIndex, 		str_replace("<br>", "\n", $Question['option5_text']));

			if ($RowIndex < 3)
			{
				$PreviousQuestionId = $Question['question_id'];
				$ActiveSheet->getStyle("A" . $StartRowIndex . ":L" . $RowIndex)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))), false);
				continue;
			}

			if ($CurrentQuestionId != $PreviousQuestionId)
			{
				$CellRange = "A" . $StartRowIndex . ":L" . ($RowIndex - 1);
				$ActiveSheet->getStyle("A" . $StartRowIndex . ":L" . $RowIndex)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'B2B4B3'))), false);
				$ActiveSheet->getStyle($CellRange)->getBorders()->applyFromArray(array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))), false);

				$BorderSectionCount++;
				$BorderSectionFillColor = (fmod($BorderSectionCount, 2) == 0) ? "FFFFFF" : "F5F5F5";
				$ActiveSheet->getStyle($CellRange)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($BorderSectionFillColor);

				$StartRowIndex = $RowIndex;
			}
			else
			{
				if ($RowIndex > count($QuestionData))
				{
					$CellRange = "A" . $StartRowIndex . ":L" . ($RowIndex);
					$ActiveSheet->getStyle("A" . $StartRowIndex . ":L" . $RowIndex)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'B2B4B3'))), false);
					$ActiveSheet->getStyle($CellRange)->getBorders()->applyFromArray(array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))), false);
				}
			}

			$PreviousQuestionId = $Question['question_id'];
		}

		$ActiveSheet->getColumnDimension($SNO)->setWidth("7");
		$ActiveSheet->getColumnDimension($PART)->setWidth("23");
		$ActiveSheet->getColumnDimension($SECTION)->setWidth("23");
		$ActiveSheet->getColumnDimension($TYPE)->setWidth("7");
		$ActiveSheet->getColumnDimension($QUESTION_ID)->setWidth("13");
		$ActiveSheet->getColumnDimension($LANGUAGE)->setWidth("25");
		$ActiveSheet->getColumnDimension($QUESTION)->setWidth("75");
		$ActiveSheet->getColumnDimension($OPTION1)->setWidth("30");
		$ActiveSheet->getColumnDimension($OPTION2)->setWidth("30");
		$ActiveSheet->getColumnDimension($OPTION3)->setWidth("30");
		$ActiveSheet->getColumnDimension($OPTION4)->setWidth("30");
		$ActiveSheet->getColumnDimension($OPTION5)->setWidth("30");

		$ActiveSheet->getStyle('A1:L1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('9A12B3');
		$ActiveSheet->getStyle('A1:L1')->applyFromArray($HeaderStyleArray);

		$ActiveSheet->getStyle($SNO)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ActiveSheet->getStyle($QUESTION_ID)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$ActiveSheet->getStyle('A2:L'.$RowIndex)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$ActiveSheet->getStyle('A2:L'.$RowIndex)->getAlignment()->setWrapText(true);
		$ActiveSheet->getStyle('A2:L'.$RowIndex)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

		$OutputFileName = "Template_" . $LanguageName . "_QuestionBulkUpload_" . date("Ymdhis") . ".xlsx";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $OutputFileName . '"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		$objWriter->save('php://output');
	}
	//END: QUESTION PAPERS - By George

	//BEGIN: SAMPLE QUESTION PAPERS - By George
	public function preview_sample_question_paper($LanguageId = 1)
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$data = array(
			'page' 					=> 'preview_sample_question_paper',
			'parent_page' 			=> "content/question_papers",
			'parent_page_title' 	=> "Question Papers",
			'title' 				=> "Sample Question Paper Preview",
			'module' 				=> "content",
			'user_role_id' 			=> $user['user_group_id'],
			'user_id' 				=> $user['id'],
			'language_id'		  	=> $LanguageId,
			'language_list'		  	=> $this->content->get_language_list_data(),
			'question_count' 		=> $this->content->get_sample_question_paper_detail(),
			'ResponseData' 			=> $this->content->get_preview_sample_question_paper_data($LanguageId)
		);

		$this->load->view('index', $data);
	}

	public function sample_questions($id = 0)
	{
		$user= $this->pramaan->_check_module_task_auth(true);
		$data['page']         	= 'sample_questions';
		$data['module']        	= 'content';
		$data['title']         	= 'Sample Questions';
		$data['user_role_id'] 	= $user['user_group_id'];
		$data['user_id']       	= $user['id'];
		$data['LanguageList'] 	= $this->content->get_language_list_data();
		//print_r($data);
		//die;
		$this->load->view('index', $data);
	}
	public function get_sample_question_data()
	{
		error_reporting(E_ALL);
		$requestData = $_REQUEST;
		$resp_data   = $this->content->get_sample_question_data($requestData);
		echo json_encode($resp_data);
	}

	public function addedit_sample_question($id = 0)
	{
		$user = $this->pramaan->_check_module_task_auth(true);
		$data = array(
			'page' 				=> 'addedit_sample_question',
			'parent_page' 		=> "content/sample_questions",
			'parent_page_title' => "Sample Questions",
			'title' 			=> "Sample Question",
			'module' 			=> "content",
			'user_role_id' 		=> $user['user_group_id'],
			'user_id' 			=> $user['id'],
			'ResponseData' 		=> $this->content->get_sample_question_detail($id),
			'PartList' 			=> $this->content->get_part_list(),
			'SectionList' 		=> $this->content->get_sample_section_list_for_question_id($id),
			'QuestionTypeList' 	=> $this->content->get_question_type_list(),
			'LanguageList' 		=> $this->content->get_language_list_data(),
		);

		$this->load->view('index', $data);
	}

	public function SaveSampleQuestionData()
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$ResponseArray = array();
		$this->load->library('upload');
		$this->upload->initialize($this->set_sample_question_image_upload_options());

		$ReverseValues = strtoupper($this->input->post('hidQuestionImg')) == 'ON' ? "1" : "0";
		$CorrectOption = ($this->input->post('rdoCorrectOption') != '') ? $this->input->post('rdoCorrectOption') : "0";

		strtoupper($this->input->post('hidQuestionImg')) == 'ON' ? "1" : "0";

		$QuestionImg = $this->input->post('hidQuestionImg');
		if (isset($_FILES['questionimage']['name']))
			if ($_FILES['questionimage']['name'] != '')
				$QuestionImg = $_FILES['questionimage']['name'];

		$Option1Img = $this->input->post('hidOption1Img');
		if (isset($_FILES['optionimage1']['name']))
			if ($_FILES['optionimage1']['name'] != '')
				$Option1Img = $_FILES['optionimage1']['name'];

		$Option2Img = $this->input->post('hidOption2Img');
		if (isset($_FILES['optionimage2']['name']))
			if ($_FILES['optionimage2']['name'] != '')
				$Option2Img = $_FILES['optionimage2']['name'];

		$Option3Img = $this->input->post('hidOption3Img');
		if (isset($_FILES['optionimage3']['name']))
			if ($_FILES['optionimage3']['name'] != '')
				$Option3Img = $_FILES['optionimage3']['name'];

		$Option4Img = $this->input->post('hidOption4Img');
		if (isset($_FILES['optionimage4']['name']))
			if ($_FILES['optionimage4']['name'] != '')
				$Option3Img = $_FILES['optionimage4']['name'];

		$Option5Img = $this->input->post('hidOption5Img');
		if (isset($_FILES['optionimage5']['name']))
			if ($_FILES['optionimage5']['name'] != '')
				$Option3Img = $_FILES['optionimage5']['name'];

		$SaveRequestData = array(
			'question_id' 		=> $this->input->post('hidQuestionId'),
			'part_id' 			=> $this->input->post('listPart'),
			'section_code' 		=> $this->input->post('listSection'),
			'question_type_id' 	=> $this->input->post('listQuestionType'),
			'question_text' 	=> str_replace("\n", "<br>", $this->input->post('txtQuestionText')),
			'option1_text' 		=> str_replace("\n", "<br>", $this->input->post('txtOption1Text')),
			'option2_text' 		=> str_replace("\n", "<br>", $this->input->post('txtOption2Text')),
			'option3_text' 		=> str_replace("\n", "<br>", $this->input->post('txtOption3Text')),
			'option4_text' 		=> str_replace("\n", "<br>", $this->input->post('txtOption4Text')),
			'option5_text' 		=> str_replace("\n", "<br>", $this->input->post('txtOption5Text')),
			'correct_option' 	=> $CorrectOption,
			'marks' 			=> $this->input->post('txtMarks'),
			'reverse_values' 	=> $ReverseValues,
			'question_img' 		=> $QuestionImg,
			'option1_img' 		=> $Option1Img,
			'option2_img' 		=> $Option2Img,
			'option3_img' 		=> $Option3Img,
			'option4_img' 		=> $Option4Img,
			'option5_img' 		=> $Option5Img
		);

		$SaveResponseData = $this->content->SaveSampleQuestionData($SaveRequestData);
		if (count($SaveResponseData) > 0) {
			$ResponseQuestionId = intval($SaveResponseData[0]['o_question_id']);
			if ($ResponseQuestionId > 0) {
				//Upload Question Image
				if (isset($_FILES['questionimage']['name'])) {
					$Extension   = pathinfo($_FILES['questionimage']['name'], PATHINFO_EXTENSION);
					$ImgFileName = $ResponseQuestionId . "." . $Extension;

					$_FILES['questionimage']['name'] = $ImgFileName;
					$this->upload->do_upload('questionimage');
					$Response[] = $this->upload->data();
				}

				//Upload Option 1 Image
				if (isset($_FILES['optionimage1']['name'])) {
					$Extension   = pathinfo($_FILES['optionimage1']['name'], PATHINFO_EXTENSION);
					$ImgFileName = $ResponseQuestionId . "_1." . $Extension;

					$_FILES['optionimage1']['name'] = $ImgFileName;
					$this->upload->do_upload('optionimage1');
					$Response[] = $this->upload->data();
				}

				//Upload Option 2 Image
				if (isset($_FILES['optionimage2']['name'])) {
					$Extension   = pathinfo($_FILES['optionimage2']['name'], PATHINFO_EXTENSION);
					$ImgFileName = $ResponseQuestionId . "_2." . $Extension;

					$_FILES['optionimage2']['name'] = $ImgFileName;
					$this->upload->do_upload('optionimage2');
					$Response[] = $this->upload->data();
				}

				//Upload Option 3 Image
				if (isset($_FILES['optionimage3']['name'])) {
					$Extension   = pathinfo($_FILES['optionimage3']['name'], PATHINFO_EXTENSION);
					$ImgFileName = $ResponseQuestionId . "_3." . $Extension;

					$_FILES['optionimage3']['name'] = $ImgFileName;
					$this->upload->do_upload('optionimage3');
					$Response[] = $this->upload->data();
				}

				//Upload Option 4 Image
				if (isset($_FILES['optionimage4']['name'])) {
					$Extension   = pathinfo($_FILES['optionimage4']['name'], PATHINFO_EXTENSION);
					$ImgFileName = $ResponseQuestionId . "_4." . $Extension;

					$_FILES['optionimage4']['name'] = $ImgFileName;
					$this->upload->do_upload('optionimage4');
					$Response[] = $this->upload->data();
				}

				//Upload Option 5 Image
				if (isset($_FILES['optionimage5']['name'])) {
					$Extension   = pathinfo($_FILES['optionimage5']['name'], PATHINFO_EXTENSION);
					$ImgFileName = $ResponseQuestionId . "_5." . $Extension;

					$_FILES['optionimage5']['name'] = $ImgFileName;
					$this->upload->do_upload('optionimage5');
					$Response[] = $this->upload->data();
				}
			}
		}
		else
			$SaveResponseData = array();

		echo json_encode($SaveResponseData);
	}

	private function set_sample_question_image_upload_options()
	{
		$config                  = array();
		$config['upload_path']   = SAMPLE_QUESTION_IMAGES;
		$config['allowed_types'] = 'jpg|png|gif|jpeg|JPG|PNG|GIF|JPEG';
		$config['max_size']      = '0';
		$config['max_width']     = '0';
		$config['max_height']    = '0';
		$config['overwrite']     = TRUE;
		return $config;
	}

	public function download_sample_version_bulk_upload_template($LanguageId = 0)
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$SNO 			= "A";
		$PART 			= "B";
		$SECTION 		= "C";
		$TYPE 			= "D";
		$QUESTION_ID 	= "E";
		$LANGUAGE 		= "F";
		$QUESTION 		= "G";
		$OPTION1 		= "H";
		$OPTION2 		= "I";
		$OPTION3 		= "J";
		$OPTION4 		= "K";
		$OPTION5 		= "L";

		$HeaderFontStyleArray = array(
			'bold'  => true,
			'color' => array('rgb' => 'FFFFFF'),
			'size'  => 12
		);

		$HeaderStyleArray = array(
			'font' => $HeaderFontStyleArray
		);

		$this->load->library('Excel');
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Question Bulk Upload');

		$RowIndex = 0;
		$ActiveSheet = $this->excel->getActiveSheet();

		$ActiveSheet->setCellValue($SNO."1", 			'SNO');
		$ActiveSheet->setCellValue($PART."1", 			'PART');
		$ActiveSheet->setCellValue($SECTION."1", 		'SECTION');
		$ActiveSheet->setCellValue($TYPE."1", 			'TYPE');
		$ActiveSheet->setCellValue($QUESTION_ID."1", 	'QUESTION ID');
		$ActiveSheet->setCellValue($LANGUAGE."1", 		'LANGUAGE');
		$ActiveSheet->setCellValue($QUESTION."1", 		'QUESTION');
		$ActiveSheet->setCellValue($OPTION1."1", 		'OPTION1');
		$ActiveSheet->setCellValue($OPTION2."1", 		'OPTION2');
		$ActiveSheet->setCellValue($OPTION3."1", 		'OPTION3');
		$ActiveSheet->setCellValue($OPTION4."1", 		'OPTION4');
		$ActiveSheet->setCellValue($OPTION5."1", 		'OPTION5');

		$LanguageName = "";
		$LanguageData = $this->content->get_language_detail_for_id($LanguageId);
		if (count($LanguageData) > 0) $LanguageName = $LanguageData[0]['name'];

		$QuestionData = $this->content->get_language_version_sample_question_data($LanguageId);
		$RowIndex = 1;
		$StartRowIndex = 2;
		$CellRange = "";

		$PreviousQuestionId = 0;
		$CurrentQuestionId = 0;
		$BorderSectionCount = 0;
		$BorderSectionFillColor = "";

		foreach ($QuestionData as $Question)
		{
			$RowIndex++;
			$CurrentQuestionId = $Question['question_id'];
			if ($LanguageName == "") $LanguageName = $Question['language_name'];

			$ActiveSheet->setCellValue($SNO.$RowIndex, 			($RowIndex - 1)."");
			$ActiveSheet->setCellValue($PART.$RowIndex, 		$Question['part_name']);
			$ActiveSheet->setCellValue($SECTION.$RowIndex, 		$Question['section_name']);
			$ActiveSheet->setCellValue($TYPE.$RowIndex, 		$Question['question_type_code']);
			$ActiveSheet->setCellValue($QUESTION_ID.$RowIndex, 	$Question['question_id']."");
			$ActiveSheet->setCellValue($LANGUAGE.$RowIndex, 	$Question['language_name']);
			$ActiveSheet->setCellValue($QUESTION.$RowIndex, 	str_replace("<br>", "\n", $Question['question_text']));
			$ActiveSheet->setCellValue($OPTION1.$RowIndex, 		str_replace("<br>", "\n", $Question['option1_text']));
			$ActiveSheet->setCellValue($OPTION2.$RowIndex, 		str_replace("<br>", "\n", $Question['option2_text']));
			$ActiveSheet->setCellValue($OPTION3.$RowIndex, 		str_replace("<br>", "\n", $Question['option3_text']));
			$ActiveSheet->setCellValue($OPTION4.$RowIndex, 		str_replace("<br>", "\n", $Question['option4_text']));
			$ActiveSheet->setCellValue($OPTION5.$RowIndex, 		str_replace("<br>", "\n", $Question['option5_text']));

			if ($RowIndex < 3)
			{
				$PreviousQuestionId = $Question['question_id'];
				$ActiveSheet->getStyle("A" . $StartRowIndex . ":L" . $RowIndex)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))), false);
				continue;
			}

			if ($CurrentQuestionId != $PreviousQuestionId)
			{
				$CellRange = "A" . $StartRowIndex . ":L" . ($RowIndex - 1);
				$ActiveSheet->getStyle("A" . $StartRowIndex . ":L" . $RowIndex)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'B2B4B3'))), false);
				$ActiveSheet->getStyle($CellRange)->getBorders()->applyFromArray(array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))), false);

				$BorderSectionCount++;
				$BorderSectionFillColor = (fmod($BorderSectionCount, 2) == 0) ? "FFFFFF" : "F5F5F5";
				$ActiveSheet->getStyle($CellRange)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($BorderSectionFillColor);

				$StartRowIndex = $RowIndex;
			}
			else
			{
				if ($RowIndex > count($QuestionData))
				{
					$CellRange = "A" . $StartRowIndex . ":L" . ($RowIndex);
					$ActiveSheet->getStyle("A" . $StartRowIndex . ":L" . $RowIndex)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'B2B4B3'))), false);
					$ActiveSheet->getStyle($CellRange)->getBorders()->applyFromArray(array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))), false);
				}
			}

			$PreviousQuestionId = $Question['question_id'];
		}

		$ActiveSheet->getColumnDimension($SNO)->setWidth("7");
		$ActiveSheet->getColumnDimension($PART)->setWidth("23");
		$ActiveSheet->getColumnDimension($SECTION)->setWidth("23");
		$ActiveSheet->getColumnDimension($TYPE)->setWidth("7");
		$ActiveSheet->getColumnDimension($QUESTION_ID)->setWidth("13");
		$ActiveSheet->getColumnDimension($LANGUAGE)->setWidth("25");
		$ActiveSheet->getColumnDimension($QUESTION)->setWidth("75");
		$ActiveSheet->getColumnDimension($OPTION1)->setWidth("30");
		$ActiveSheet->getColumnDimension($OPTION2)->setWidth("30");
		$ActiveSheet->getColumnDimension($OPTION3)->setWidth("30");
		$ActiveSheet->getColumnDimension($OPTION4)->setWidth("30");
		$ActiveSheet->getColumnDimension($OPTION5)->setWidth("30");

		$ActiveSheet->getStyle('A1:L1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('9A12B3');
		$ActiveSheet->getStyle('A1:L1')->applyFromArray($HeaderStyleArray);

		$ActiveSheet->getStyle($SNO)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ActiveSheet->getStyle($QUESTION_ID)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$ActiveSheet->getStyle('A2:L'.$RowIndex)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$ActiveSheet->getStyle('A2:L'.$RowIndex)->getAlignment()->setWrapText(true);
		$ActiveSheet->getStyle('A2:L'.$RowIndex)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

		$OutputFileName = "Template_" . $LanguageName . "_QuestionBulkUpload_" . date("Ymdhis") . ".xlsx";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $OutputFileName . '"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		$objWriter->save('php://output');
	}

	public function UploadSampleQuestionPaperData()
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		print "\nSample Question Paper data upload started...";

		$ErrorDesc = "";

		$QuestionId = 0;
		$Extension  = "";

		$ResponseArray = array();
		$files         = $_FILES;

		$this->load->library('upload');
		$this->upload->initialize($this->set_question_paper_upload_options());

		//If Single File
		$ExcelFileName  = "";
		$ExcelDataArray = array();

		if (isset($_FILES['questionexcel']['name']))
		{
			$ErrorResponse = "";

			$OriginalExcelFileName = $_FILES['questionexcel']['name'];

			$Extension     = pathinfo($_FILES['questionexcel']['name'], PATHINFO_EXTENSION);
			//$ExcelFileName = str_replace("." . $Extension, "", $_FILES['questionexcel']['name']) . '_Sample_' . date("_Ymdhis.") . $Extension;
			$ExcelFileName = 'SampleQuestionPaperUploadData' . date("_Ymdhis.") . $Extension;

			$_FILES['questionexcel']['name'] = $ExcelFileName;
			$this->upload->do_upload('questionexcel');
			$Response[] = $this->upload->data();

			$ExcelFilePath = QUESTION_PAPER_DATA . $ExcelFileName;

			print "\n\nQuestion Paper Data File '$OriginalExcelFileName' has been uploaded...\n\n";

			$this->load->library('Excel');
			try
			{
				$inputFileType  = PHPExcel_IOFactory::identify($ExcelFilePath);
				$objExcelReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel    = $objExcelReader->load($ExcelFilePath);
			}
			catch (Exception $e)
			{
				die('Error loading file "' . pathinfo($ExcelFilePath, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			}

			$Delimiter         = "[@]";
			$PartCodeList      = "";
			$SectionCodeList   = "";
			$QuestionTextList  = "";
			$QuestionTypeList  = "";
			$Option1TextList   = "";
			$Option2TextList   = "";
			$Option3TextList   = "";
			$Option4TextList   = "";
			$Option5TextList   = "";
			$CorrectOptionList = "";
			$MarkList          = "";
			$ReverseValueList  = "";
			$QuestionImageList = "";
			$Option1ImageList  = "";
			$Option2ImageList  = "";
			$Option3ImageList  = "";
			$Option4ImageList  = "";
			$Option5ImageList  = "";

			$objPHPExcel->setActiveSheetIndex(0);                    //ALWAYS READ DATA FROM FIRST SHEET
			$objExcelWorkSheet = $objPHPExcel->getActiveSheet();    //GET FIRST SHEET

			$ColumnIndex = -1;
			for ($RowIndex = 2; $RowIndex <= $objExcelWorkSheet->getHighestRow(); $RowIndex++)
			{
				$ColumnIndex = -1;
				if ($RowIndex > 2)
				{
					$PartCodeList 		.= $Delimiter;
					$SectionCodeList 	.= $Delimiter;
					$QuestionTextList 	.= $Delimiter;
					$QuestionTypeList 	.= $Delimiter;
					$Option1TextList 	.= $Delimiter;
					$Option2TextList 	.= $Delimiter;
					$Option3TextList 	.= $Delimiter;
					$Option4TextList 	.= $Delimiter;
					$Option5TextList 	.= $Delimiter;
					$CorrectOptionList 	.= $Delimiter;
					$MarkList 			.= $Delimiter;
					$ReverseValueList 	.= $Delimiter;
					$QuestionImageList 	.= $Delimiter;
					$Option1ImageList 	.= $Delimiter;
					$Option2ImageList 	.= $Delimiter;
					$Option3ImageList 	.= $Delimiter;
					$Option4ImageList 	.= $Delimiter;
					$Option5ImageList 	.= $Delimiter;
				}

				$ErrorDesc = "";

				//PART CODE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(0, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
				{
					$CellText = "";
					$ErrorDesc .= "Part,";
				}
				$PartCodeList .= $CellText;

				//SECTION CODE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(1, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
				{
					$CellText = "";
					$ErrorDesc .= "Section Code,";
				}
				$SectionCodeList .= $CellText;

				//QUESTION TEXT
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(3, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
				{
					$CellText = "";
					$ErrorDesc .= "Question Text,";
				}
				$QuestionTextList .= $CellText;

				//QUESTION TYPE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(4, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
				{
					$CellText = "";
					$ErrorDesc .= "Question Type,";
				}
				$QuestionType = strtoupper(trim($CellText));
				$QuestionTypeList .= $QuestionType;

				//OPTION 1 TEXT
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(5, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
				{
					$CellText = "";
					$ErrorDesc .= "Option 1 Text,";
				}
				$Option1TextList .= $CellText;

				//OPTION 2 TEXT
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(6, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
				{
					$CellText = "";
					$ErrorDesc .= "Option 2 Text,";
				}
				$Option2TextList .= $CellText;

				//OPTION 3 TEXT
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(7, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
				{
					$CellText = "";
					$ErrorDesc .= "Option 3 Text,";
				}
				$Option3TextList .= $CellText;

				//OPTION 4 TEXT
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(8, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
				{
					$CellText = "";
					$ErrorDesc .= "Option 4 Text,";
				}
				$Option4TextList .= $CellText;

				//OPTION 5 TEXT
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(9, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
				{
					$CellText = "";
					if ($QuestionType == "PMQ") $ErrorDesc .= "Option 5 Text,";
				}
				$Option5TextList .= $CellText;

				//CORRECT OPTION
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(10, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "") {
					$CellText = "0";
					if ($QuestionType == "MCQ") $ErrorDesc .= "Correct Option,";
				}
				$CorrectOptionList .= $CellText;

				//MARKS
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(11, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
				{
					$CellText = "";
					if ($QuestionType == "MCQ") $CellText = "1";
				}
				$MarkList .= $CellText;

				//REVERSE VALUE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(12, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "" || $QuestionType == "MCQ") $CellText = "NO";
				$ReverseValueList .= $CellText;

				//QUESTION IMAGE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(13, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
					$CellText = "";
				else
				{
					$ImgIndex = -1;
					if (!is_null($_FILES['questionimages']['name']))
						$ImgIndex = array_search(trim($CellText), $_FILES['questionimages']['name']);
					if ($ImgIndex < 0)
						$ErrorDesc .= "Question Image,";
				}
				$QuestionImageList .= $CellText;

				//OPTION 1 IMAGE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(14, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
					$CellText = "";
				else
				{
					$ImgIndex = -1;
					if (!is_null($_FILES['questionimages']['name']))
						$ImgIndex = array_search(trim($CellText), $_FILES['questionimages']['name']);
					if ($ImgIndex < 0)
						$ErrorDesc .= "Option 1 Image,";
				}
				$Option1ImageList .= $CellText;

				//OPTION 2 IMAGE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(15, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
					$CellText = "";
				else
				{
					$ImgIndex = -1;
					if (!is_null($_FILES['questionimages']['name']))
						$ImgIndex = array_search(trim($CellText), $_FILES['questionimages']['name']);
					if ($ImgIndex < 0)
						$ErrorDesc .= "Option 2 Image,";
				}
				$Option2ImageList .= $CellText;

				//OPTION 3 IMAGE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(16, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
					$CellText = "";
				else
				{
					$ImgIndex = -1;
					if (!is_null($_FILES['questionimages']['name']))
						$ImgIndex = array_search(trim($CellText), $_FILES['questionimages']['name']);
					if ($ImgIndex < 0)
						$ErrorDesc .= "Option 3 Image,";
				}
				$Option3ImageList .= $CellText;

				//OPTION 4 IMAGE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(17, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
					$CellText = "";
				else
				{
					$ImgIndex = -1;
					if (!is_null($_FILES['questionimages']['name']))
						$ImgIndex = array_search(trim($CellText), $_FILES['questionimages']['name']);
					if ($ImgIndex < 0)
						$ErrorDesc .= "Option 4 Image,";
				}
				$Option4ImageList .= $CellText;

				//OPTION 5 IMAGE
				$CellText = $objExcelWorkSheet->getCellByColumnAndRow(18, $RowIndex)->getCalculatedValue();
				if (is_null($CellText) || trim($CellText) == "")
					$CellText = "";
				else
				{
					$ImgIndex = -1;
					if (!is_null($_FILES['questionimages']['name']))
						$ImgIndex = array_search(trim($CellText), $_FILES['questionimages']['name']);
					if ($ImgIndex < 0)
						$ErrorDesc .= "Option 5 Image,";
				}
				$Option5ImageList .= $CellText;

				if (strlen($ErrorDesc) > 0)
				{
					$ErrorDesc = substr($ErrorDesc, 0, -1);
					$ErrorDesc = str_replace(",", ", ", $ErrorDesc);
					$ErrorDesc = "Row " . ($RowIndex - 1) . ": Missing " . $ErrorDesc . ".\n";
				}

				$ErrorResponse .= $ErrorDesc;
			}

			if (trim($ErrorResponse) != "")
			{
				print "\n\nThe following errors have been found in the uploaded question data file.";
				print $ErrorResponse;
				print "\nPlease correct these errors in the file and upload again!";
				return;
			}
		}
		else
		{
			print "\n\nQuestion Upload Data Not Found!";
			return;
		}

		if (trim($ErrorResponse) == "")
		{
			$QuestionTextList = str_replace("'", "`", $QuestionTextList);
			$Option1TextList  = str_replace("'", "`", $Option1TextList);
			$Option2TextList  = str_replace("'", "`", $Option2TextList);
			$Option3TextList  = str_replace("'", "`", $Option3TextList);
			$Option4TextList  = str_replace("'", "`", $Option4TextList);
			$Option5TextList  = str_replace("'", "`", $Option5TextList);

			$QuestionTextList = str_replace("\n", "<br>", $QuestionTextList);
			$Option1TextList  = str_replace("\n", "<br>", $Option1TextList);
			$Option2TextList  = str_replace("\n", "<br>", $Option2TextList);
			$Option3TextList  = str_replace("\n", "<br>", $Option3TextList);
			$Option4TextList  = str_replace("\n", "<br>", $Option4TextList);
			$Option5TextList  = str_replace("\n", "<br>", $Option5TextList);

			$SaveRequestData = array(
				'PartCodeList' 		=> $PartCodeList,
				'SectionCodeList' 	=> $SectionCodeList,
				'QuestionTextList' 	=> $QuestionTextList,
				'QuestionTypeList' 	=> $QuestionTypeList,
				'Option1TextList' 	=> $Option1TextList,
				'Option2TextList' 	=> $Option2TextList,
				'Option3TextList' 	=> $Option3TextList,
				'Option4TextList' 	=> $Option4TextList,
				'Option5TextList' 	=> $Option5TextList,
				'CorrectOptionList' => $CorrectOptionList,
				'MarkList' 			=> $MarkList,
				'ReverseValueList' 	=> $ReverseValueList,
				'QuestionImageList' => $QuestionImageList,
				'Option1ImageList' 	=> $Option1ImageList,
				'Option2ImageList' 	=> $Option2ImageList,
				'Option3ImageList' 	=> $Option3ImageList,
				'Option4ImageList' 	=> $Option4ImageList,
				'Option5ImageList' 	=> $Option5ImageList,
				'user_id' 			=> $user['id']
			);

			$SaveResponseData = $this->content->upload_sample_question_paper_data($SaveRequestData);
			if (count($SaveResponseData) > 0)
			{
				print "\nSample Question Paper Data has been uploaded to the database...";

				if (strtoupper($SaveResponseData[0]['o_message']) == "SUCCESS")
				{
					$SrcImageArray  = explode('[@]', $SaveResponseData[0]['o_src_img_list']);
					$DestImageArray = explode('[@]', $SaveResponseData[0]['o_dest_img_list']);

					$ImgIndex               = -1;
					$QuestionImageFileCount = isset($_FILES['questionimages']['name']) ? count($_FILES['questionimages']['name']) : 0;
					if ($QuestionImageFileCount > 0)
					{
						$this->upload->initialize($this->set_sample_question_image_upload_options());

						$Files    = $_FILES;
						$ImgIndex = -1;
						for ($i = 0; $i < $QuestionImageFileCount; $i++)
						{
							if ($SrcImageArray)
							{
								$ImgIndex = array_search($Files['questionimages']['name'][$i], $SrcImageArray);
								if ($ImgIndex >= 0) {
									$_FILES['questionimages']['name']     = $DestImageArray[$ImgIndex];
									$_FILES['questionimages']['type']     = $Files['questionimages']['type'][$i];
									$_FILES['questionimages']['tmp_name'] = $Files['questionimages']['tmp_name'][$i];
									$_FILES['questionimages']['error']    = $Files['questionimages']['error'][$i];
									$_FILES['questionimages']['size']     = $Files['questionimages']['size'][$i];
									$this->upload->do_upload('questionimages');

									$this->ScaleQuestionImageWithAspectRatioForHeight(SAMPLE_QUESTION_IMAGES, $DestImageArray[$ImgIndex]);
								}
							}
						}

						print "\nQuestion Paper Images have been uploaded...";
					}

					print "\n\nSample Questions have been uploaded successfully!";
					return;
				}
				else
				{
					print $SaveResponseData[0]['o_message'];
					return;
				}
			}
			else
			{
				print "\n\nError while uploading Sample Questions!\n\n";
				return;
			}
		}
	}

	public function UploadSampleQuestionPaperVersionData()
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		print "Sample Question Paper Version data upload started...";

		$QuestionId = 0;
		$Extension  = "";

		$ResponseArray = array();
		$files         = $_FILES;

		$this->load->library('upload');
		$this->upload->initialize($this->set_question_paper_upload_options());

		//If Single File
		$ExcelFileName  = "";
		$ExcelDataArray = array();

		if (isset($_FILES['questionexcel']['name'])) {
			$ErrorResponse = "";

			$OriginalExcelFileName = $_FILES['questionexcel']['name'];

			$Extension     = pathinfo($_FILES['questionexcel']['name'], PATHINFO_EXTENSION);
			//$ExcelFileName = str_replace("." . $Extension, "", $_FILES['questionexcel']['name']) . date("_Ymdhis.") . $Extension;
			$ExcelFileName = "SampleQuestionPaperVersionUploadData" . date("_Ymdhis.") . $Extension;

			$_FILES['questionexcel']['name'] = $ExcelFileName;
			$this->upload->do_upload('questionexcel');
			$Response[] = $this->upload->data();

			$ExcelFilePath = QUESTION_PAPER_DATA . $ExcelFileName;
			print "\n\nQuestion Paper Version Data File '$OriginalExcelFileName' has been uploaded...\n\n";

			$this->load->library('Excel');
			try
			{
				$inputFileType  = PHPExcel_IOFactory::identify($ExcelFilePath);
				$objExcelReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel    = $objExcelReader->load($ExcelFilePath);
			}
			catch (Exception $e)
			{
				die('Error loading file "' . pathinfo($ExcelFilePath, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			}

			$QuestionPaperId = intval($this->input->post('hidQuestionPaperId'));

			$objPHPExcel->setActiveSheetIndex(0);                    //ALWAYS READ DATA FROM FIRST SHEET
			$objExcelWorkSheet = $objPHPExcel->getActiveSheet();    //GET FIRST SHEET

			$ValidLanguageNames = array();
			$LanguageList = $this->content->get_version_language_list_data();
			if (count($LanguageList) > 0)
			{
				foreach ($LanguageList as $Language)
				{
					$ValidLanguageNames[] = strtoupper($Language['language_name']);
				}
			}

			/*print_r($ValidLanguageNames);
			die;*/

			$QuestionType = "";
			$LanguageName = "";
			$LangIndex = -1;
			$ColumnIndex = -1;
			$ValidCount = 0;

			$Delimiter         		= "[@]";
			$QuestionIdList      	= "";
			$RowIndexList      		= "";
			$LanguageNameList   	= "";
			$QuestionTextList  		= "";
			$Option1TextList   		= "";
			$Option2TextList   		= "";
			$Option3TextList   		= "";
			$Option4TextList   		= "";
			$Option5TextList   		= "";

			for ($RowIndex = 2; $RowIndex <= $objExcelWorkSheet->getHighestRow(); $RowIndex++)
			{
				//ROW INDEX
				$RIndex = $RowIndex - 1;

				$LanguageName = $objExcelWorkSheet->getCellByColumnAndRow(5, $RowIndex)->getCalculatedValue();
				print "identified Language: $LanguageName\n";

				$strLanguageName = strtoupper(trim($LanguageName));
				if ($strLanguageName == 'ENGLISH') continue;

				$LangIndex = array_search($strLanguageName, $ValidLanguageNames);
				if ($LangIndex < 0)
				{
					$ErrorResponse .= "Row $RIndex: Missing or Invalid Language. Skipping the row...\n";
					continue;
				}

				//QUESTION TYPE
				$QuestionType = $objExcelWorkSheet->getCellByColumnAndRow(3, $RowIndex)->getCalculatedValue();

				//QUESTION ID
				$QuestionId = $objExcelWorkSheet->getCellByColumnAndRow(4, $RowIndex)->getCalculatedValue();
				if (is_null($QuestionId) || trim($QuestionId) == "")
				{
					print "ROW $RIndex: Question Id not found. Skipping the row...\n";
					continue;
				}

				//QUESTION TEXT
				$QuestionText = $objExcelWorkSheet->getCellByColumnAndRow(6, $RowIndex)->getCalculatedValue();
				if (is_null($QuestionText) || trim($QuestionText) == "")
				{
					print "ROW $RIndex: Question Text not found. Skipping the row...\n";
					continue;
				}

				//OPTION 1 TEXT
				$Option1Text = $objExcelWorkSheet->getCellByColumnAndRow(7, $RowIndex)->getCalculatedValue();
				if (is_null($Option1Text) || trim($Option1Text) == "")  $Option1Text = "";

				//OPTION 2 TEXT
				$Option2Text = $objExcelWorkSheet->getCellByColumnAndRow(8, $RowIndex)->getCalculatedValue();
				if (is_null($Option2Text) || trim($Option2Text) == "")  $Option2Text = "";

				//OPTION 3 TEXT
				$Option3Text = $objExcelWorkSheet->getCellByColumnAndRow(9, $RowIndex)->getCalculatedValue();
				if (is_null($Option3Text) || trim($Option3Text) == "")  $Option3Text = "";

				//OPTION 4 TEXT
				$Option4Text = $objExcelWorkSheet->getCellByColumnAndRow(10, $RowIndex)->getCalculatedValue();
				if (is_null($Option4Text) || trim($Option4Text) == "")  $Option4Text = "";

				//OPTION 5 TEXT
				$Option5Text = "";
				if (strtoupper($QuestionType) == "PMQ")
				{
					$Option5Text = $objExcelWorkSheet->getCellByColumnAndRow(11, $RowIndex)->getCalculatedValue();
					if (is_null($Option5Text) || trim($Option5Text) == "") $Option5Text = "";
				}

				print "ROW $RIndex: Necessary field data found for '$strLanguageName'. Added to version upload process...\n";

				$ValidCount++;
				if ($ValidCount > 1)
				{
					$QuestionIdList 	.= $Delimiter;
					$RowIndexList 		.= $Delimiter;
					$LanguageNameList 	.= $Delimiter;
					$QuestionTextList 	.= $Delimiter;
					$Option1TextList 	.= $Delimiter;
					$Option2TextList 	.= $Delimiter;
					$Option3TextList 	.= $Delimiter;
					$Option4TextList 	.= $Delimiter;
					$Option5TextList 	.= $Delimiter;
				}

				$QuestionIdList 	.= $QuestionId;
				$RowIndexList 		.= $RIndex;
				$LanguageNameList 	.= $LanguageName;
				$QuestionTextList 	.= $QuestionText;
				$Option1TextList 	.= $Option1Text;
				$Option2TextList 	.= $Option2Text;
				$Option3TextList 	.= $Option3Text;
				$Option4TextList 	.= $Option4Text;
				$Option5TextList 	.= $Option5Text;
			}
		}
		else
		{
			print "\n\nQuestion Upload Data Not Found!";
			return;
		}

		print "\n\nProceeding to upload the selected data...\n";

		$QuestionTextList = str_replace("'", "`", $QuestionTextList);
		$Option1TextList  = str_replace("'", "`", $Option1TextList);
		$Option2TextList  = str_replace("'", "`", $Option2TextList);
		$Option3TextList  = str_replace("'", "`", $Option3TextList);
		$Option4TextList  = str_replace("'", "`", $Option4TextList);
		$Option5TextList  = str_replace("'", "`", $Option5TextList);

		$QuestionTextList = str_replace("\n", "<br>", $QuestionTextList);
		$Option1TextList  = str_replace("\n", "<br>", $Option1TextList);
		$Option2TextList  = str_replace("\n", "<br>", $Option2TextList);
		$Option3TextList  = str_replace("\n", "<br>", $Option3TextList);
		$Option4TextList  = str_replace("\n", "<br>", $Option4TextList);
		$Option5TextList  = str_replace("\n", "<br>", $Option5TextList);

		$SaveRequestData = array(
			'QuestionIdList' 	=> $QuestionIdList,
			'RowIndexList' 		=> $RowIndexList,
			'LanguageNameList'	=> $LanguageNameList,
			'QuestionTextList' 	=> $QuestionTextList,
			'Option1TextList' 	=> $Option1TextList,
			'Option2TextList' 	=> $Option2TextList,
			'Option3TextList' 	=> $Option3TextList,
			'Option4TextList' 	=> $Option4TextList,
			'Option5TextList' 	=> $Option5TextList,
			'user_id' 			=> $user['id']
		);

		$ResponseMessage = $this->content->upload_sample_question_paper_version_data($SaveRequestData);
		$ResponseMessage = str_replace("[@]", "\n", $ResponseMessage);
		print $ResponseMessage;
	}

	public function addedit_sample_question_language_version($id = 0)
	{
		$user = $this->pramaan->_check_module_task_auth(true);
		$data = array(
			'page' 				=> 'addedit_sample_question_language_version',
			'parent_page' 		=> "content/sample_questions",
			'parent_page_title' => "Sample Questions",
			'title' 			=> "Sample Question Language Version",
			'module' 			=> "content",
			'user_role_id' 		=> $user['user_group_id'],
			'user_id' 			=> $user['id'],
			'ResponseData' 		=> $this->content->get_sample_question_detail($id),
			'PartList' 			=> $this->content->get_part_list(),
			'SectionList' 		=> $this->content->get_section_list_for_sample_question_id($id),
			'QuestionTypeList' 	=> $this->content->get_question_type_list(),
			'LanguageList' 		=> $this->content->get_language_list_data(),
		);

		$this->load->view('index', $data);
	}

	public function save_sample_question_language_version_data()
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$RequestData = array(
			"question_id" => $this->input->post('question_id'),
			"language_code"  => $this->input->post('language_code'),
			"question"  => $this->input->post('question'),
			"option1"  => $this->input->post('option1'),
			"option2"  => $this->input->post('option2'),
			"option3"  => $this->input->post('option3'),
			"option4"  => $this->input->post('option4'),
			"option5"  => $this->input->post('option5')
		);

		$resp_data = $this->content->save_sample_question_language_version_data($RequestData);
		echo json_encode($resp_data);
	}

	public function get_sample_question_detail_for_language($QuestionId = 0, $LanguageCode = '')
	{
		error_reporting(E_ALL);
		$ResponseData   = $this->content->get_sample_question_detail_for_language($QuestionId, $LanguageCode);
		echo json_encode($ResponseData);
	}
	//END: SAMPLE QUESTION PAPERS - By George

	//BEGIN: QUESTIONS - By George
	public function questions($id = 0)
	{
		$user                         = $this->pramaan->_check_module_task_auth(true);
		$data['page']                 = 'questions';
		$data['module']               = 'content';
		$data['title']                = 'Questions';
		$data['user_role_id']         = $user['user_group_id'];
		$data['user_id']              = $user['id'];
		$data['question_paper_id']    = $id;
		$data['question_paper_title'] = $this->content->get_question_paper_title($id);

		//print_r($data);
		//die;

		$this->load->view('index', $data);
	}

	public function get_question_data($id = 0)
	{
		error_reporting(E_ALL);
		$requestData = $_REQUEST;
		$resp_data   = $this->content->get_question_data($requestData, $id);
		echo json_encode($resp_data);
	}

	public function addedit_question($question_paper_id = 0, $id = 0)
	{
		$user = $this->pramaan->_check_module_task_auth(true);
		$data = array(
			'page' 				=> 'addedit_question',
			'parent_page' 		=> "content/questions",
			'parent_page_title' => "Questions",
			'title' 			=> "Question",
			'module' 			=> "content",
			'user_role_id' 		=> $user['user_group_id'],
			'user_id' 			=> $user['id'],
			'question_paper_id' => $question_paper_id,
			'ResponseData' 		=> $this->content->get_question_detail($id),
			'PartList' 			=> $this->content->get_part_list(),
			'SectionList' 		=> $this->content->get_section_list_for_question_id($id),
			'QuestionTypeList' 	=> $this->content->get_question_type_list(),
			'LanguageList' 		=> $this->content->get_language_list_data(),
		);

		$this->load->view('index', $data);
	}

	public function addedit_question_language_version($question_paper_id = 0, $id = 0)
	{
		$user = $this->pramaan->_check_module_task_auth(true);
		$data = array(
			'page' 				=> 'addedit_question_language_version',
			'parent_page' 		=> "content/questions",
			'parent_page_title' => "Questions",
			'title' 			=> "Question Language Version",
			'module' 			=> "content",
			'user_role_id' 		=> $user['user_group_id'],
			'user_id' 			=> $user['id'],
			'question_paper_id' => $question_paper_id,
			'ResponseData' 		=> $this->content->get_question_detail($id),
			'PartList' 			=> $this->content->get_part_list(),
			'SectionList' 		=> $this->content->get_section_list_for_question_id($id),
			'QuestionTypeList' 	=> $this->content->get_question_type_list(),
			'LanguageList' 		=> $this->content->get_language_list_data(),
		);

		$this->load->view('index', $data);
	}

	public function get_question_detail_for_language($QuestionId = 0, $LanguageCode = '')
	{
		error_reporting(E_ALL);
		$ResponseData   = $this->content->get_question_detail_for_language($QuestionId, $LanguageCode);
		echo json_encode($ResponseData);
	}

	public function get_question_paper_list()
	{
		error_reporting(E_ALL);
		$ResponseData   = $this->content->get_question_paper_list();
		echo json_encode($ResponseData);
	}

	function get_question_paper_list_for_batch_assignment($BatchId = 0)
	{
		error_reporting(E_ALL);
		$BatchId = isset($_REQUEST['batch_id']) ? $_REQUEST['batch_id'] : $BatchId;
		$ResponseData   = $this->content->get_question_paper_list_for_batch_assignment($BatchId);
		echo json_encode($ResponseData);
	}

	function get_question_paper_list_for_candidate_assignment($CandidateId = 0)
	{
		error_reporting(E_ALL);
		$CandidateId = isset($_REQUEST['candidate_id']) ? $_REQUEST['candidate_id'] : $CandidateId;
		$ResponseData   = $this->content->get_question_paper_list_for_candidate_assignment($CandidateId);
		echo json_encode($ResponseData);
	}

	public function save_question_language_version_data()
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$RequestData = array(
			"question_id" => $this->input->post('question_id'),
			"language_code"  => $this->input->post('language_code'),
			"question"  => $this->input->post('question'),
			"option1"  => $this->input->post('option1'),
			"option2"  => $this->input->post('option2'),
			"option3"  => $this->input->post('option3'),
			"option4"  => $this->input->post('option4'),
			"option5"  => $this->input->post('option5')
		);

		$resp_data = $this->content->save_question_language_version_data($RequestData);
		echo json_encode($resp_data);
	}

	public function get_section_list_for_part_id()
	{
		$user      = $this->pramaan->_check_module_task_auth(true);
		$PartId    = $this->input->post('part_id');
		$resp_data = $this->content->get_section_list_for_part_id($PartId);
		echo json_encode($resp_data);
	}

	public function SaveQuestionData()
	{
		$User = $this->pramaan->_check_module_task_auth(true);

		$ResponseArray = array();
		$this->load->library('upload');
		$this->upload->initialize($this->set_question_image_upload_options());

		$ReverseValues = strtoupper($this->input->post('chkReverseValues')) == 'ON' ? "1" : "0";
		$CorrectOption = ($this->input->post('rdoCorrectOption') != '') ? $this->input->post('rdoCorrectOption') : "0";

		$QuestionImg = $this->input->post('hidQuestionImg');
		if (isset($_FILES['questionimage']['name']))
			if ($_FILES['questionimage']['name'] != '')
				$QuestionImg = $_FILES['questionimage']['name'];

		$Option1Img = $this->input->post('hidOption1Img');
		if (isset($_FILES['optionimage1']['name']))
			if ($_FILES['optionimage1']['name'] != '')
				$Option1Img = $_FILES['optionimage1']['name'];

		$Option2Img = $this->input->post('hidOption2Img');
		if (isset($_FILES['optionimage2']['name']))
			if ($_FILES['optionimage2']['name'] != '')
				$Option2Img = $_FILES['optionimage2']['name'];

		$Option3Img = $this->input->post('hidOption3Img');
		if (isset($_FILES['optionimage3']['name']))
			if ($_FILES['optionimage3']['name'] != '')
				$Option3Img = $_FILES['optionimage3']['name'];

		$Option4Img = $this->input->post('hidOption4Img');
		if (isset($_FILES['optionimage4']['name']))
			if ($_FILES['optionimage4']['name'] != '')
				$Option3Img = $_FILES['optionimage4']['name'];

		$Option5Img = $this->input->post('hidOption5Img');
		if (isset($_FILES['optionimage5']['name']))
			if ($_FILES['optionimage5']['name'] != '')
				$Option3Img = $_FILES['optionimage5']['name'];

		$SaveRequestData = array(
			'question_paper_id' => $this->input->post('hidQuestionPaperId'),
			'question_id' 		=> $this->input->post('hidQuestionId'),
			'part_id' 			=> $this->input->post('listPart'),
			'section_code' 		=> $this->input->post('listSection'),
			'question_type_id' 	=> $this->input->post('listQuestionType'),
			'question_text' 	=> str_replace("\n", "<br>", $this->input->post('txtQuestionText')),
			'option1_text' 		=> str_replace("\n", "<br>", $this->input->post('txtOption1Text')),
			'option2_text' 		=> str_replace("\n", "<br>", $this->input->post('txtOption2Text')),
			'option3_text' 		=> str_replace("\n", "<br>", $this->input->post('txtOption3Text')),
			'option4_text' 		=> str_replace("\n", "<br>", $this->input->post('txtOption4Text')),
			'option5_text' 		=> str_replace("\n", "<br>", $this->input->post('txtOption5Text')),
			'correct_option' 	=> $CorrectOption,
			'marks' 			=> $this->input->post('txtMarks'),
			'reverse_values' 	=> $ReverseValues,
			'question_img' 		=> $QuestionImg,
			'option1_img' 		=> $Option1Img,
			'option2_img' 		=> $Option2Img,
			'option3_img' 		=> $Option3Img,
			'option4_img' 		=> $Option4Img,
			'option5_img' 		=> $Option5Img
		);

		$SaveResponseData = $this->content->SaveQuestionData($SaveRequestData);
		if (count($SaveResponseData) > 0) {
			$ResponseQuestionId = intval($SaveResponseData[0]['o_question_id']);
			if ($ResponseQuestionId > 0) {
				//Upload Question Image
				if (isset($_FILES['questionimage']['name'])) {
					$Extension   = pathinfo($_FILES['questionimage']['name'], PATHINFO_EXTENSION);
					$ImgFileName = $ResponseQuestionId . "." . $Extension;

					$_FILES['questionimage']['name'] = $ImgFileName;
					$this->upload->do_upload('questionimage');
					$Response[] = $this->upload->data();
				}

				//Upload Option 1 Image
				if (isset($_FILES['optionimage1']['name'])) {
					$Extension   = pathinfo($_FILES['optionimage1']['name'], PATHINFO_EXTENSION);
					$ImgFileName = $ResponseQuestionId . "_1." . $Extension;

					$_FILES['optionimage1']['name'] = $ImgFileName;
					$this->upload->do_upload('optionimage1');
					$Response[] = $this->upload->data();
				}

				//Upload Option 2 Image
				if (isset($_FILES['optionimage2']['name'])) {
					$Extension   = pathinfo($_FILES['optionimage2']['name'], PATHINFO_EXTENSION);
					$ImgFileName = $ResponseQuestionId . "_2." . $Extension;

					$_FILES['optionimage2']['name'] = $ImgFileName;
					$this->upload->do_upload('optionimage2');
					$Response[] = $this->upload->data();
				}

				//Upload Option 3 Image
				if (isset($_FILES['optionimage3']['name'])) {
					$Extension   = pathinfo($_FILES['optionimage3']['name'], PATHINFO_EXTENSION);
					$ImgFileName = $ResponseQuestionId . "_3." . $Extension;

					$_FILES['optionimage3']['name'] = $ImgFileName;
					$this->upload->do_upload('optionimage3');
					$Response[] = $this->upload->data();
				}

				//Upload Option 4 Image
				if (isset($_FILES['optionimage4']['name'])) {
					$Extension   = pathinfo($_FILES['optionimage4']['name'], PATHINFO_EXTENSION);
					$ImgFileName = $ResponseQuestionId . "_4." . $Extension;

					$_FILES['optionimage4']['name'] = $ImgFileName;
					$this->upload->do_upload('optionimage4');
					$Response[] = $this->upload->data();
				}

				//Upload Option 5 Image
				if (isset($_FILES['optionimage5']['name'])) {
					$Extension   = pathinfo($_FILES['optionimage5']['name'], PATHINFO_EXTENSION);
					$ImgFileName = $ResponseQuestionId . "_5." . $Extension;

					$_FILES['optionimage5']['name'] = $ImgFileName;
					$this->upload->do_upload('optionimage5');
					$Response[] = $this->upload->data();
				}
			}
		}
		else
			$SaveResponseData = array();

		echo json_encode($SaveResponseData);
	}

	public function download_pdf()
	{
		$this->load->library('Pdf');

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('My Title');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');

		$pdf->AddPage();

		$pdf->Write(5, 'Some sample text');
		$pdf->Output('download.pdf', 'I');
	}

	public function download1_question_paper_preview_in_pdf($QuestionPaperId = 0, $LanguageId = 1)
	{
		set_time_limit(100);
		$user              = $this->pramaan->_check_module_task_auth(true);
		$QuestionPaperData = $this->content->get_preview_question_paper_data($QuestionPaperId, $LanguageId);

		$QuestionPaperHtml = "";
		if (count($QuestionPaperData) > 0) {
			$this->load->library('Pdf');
			$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Navriti Technologies');

			$PdfHeaderTitle = 'Question Paper';
			$PdfHeaderText  = "IST Question Paper";

			if (count($QuestionPaperData['question_paper_data']) > 0) {
				$pdf->SetTitle($QuestionPaperData['question_paper_data'][0]['question_paper_title']);
				$pdf->SetSubject($QuestionPaperData['question_paper_data'][0]['question_paper_title']);

				$PdfHeaderTitle = $QuestionPaperData['question_paper_data'][0]['question_paper_title'];
				$PdfHeaderText  = $QuestionPaperData['question_paper_data'][0]['language_name'];
			}

			$HeaderLogo = 'via_logo.png';

			$pdf->SetHeaderData($HeaderLogo, 30, $PdfHeaderTitle, $PdfHeaderText, array(0, 64, 255), array(0, 64, 128));
			$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->setFontSubsetting(true);
			$pdf->setFont('freeserif');
			$pdf->AddPage();
			$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

			$QuestionPaperHtml .= '<div id="divPageContainer" class="container-fluid">';
			if (count($QuestionPaperData['part_data']) > 0) {
				foreach ($QuestionPaperData['part_data'] AS $PartData) {
					$QuestionCount = 0;
					foreach ($PartData['section_data'] AS $SectionData)
						$QuestionCount += count($SectionData['question_data']);

					if ($QuestionCount > 0) {
						$QuestionIndex = 0;

						$QuestionPaperHtml .= '	<div class="form-group">';
						$QuestionPaperHtml .= '		<label class="col-sm-12 control-label" style="margin-top:-8px;text-align: center; font-size: 18px; font-weight: bold; color: #9913B2;">' . strtoupper($PartData['part_name']) . '</label>';
						$QuestionPaperHtml .= '	</div>';

						foreach ($PartData['section_data'] AS $SectionData) {
							if (count($SectionData['question_data']) > 0) {
								if (intval($PartData['part_id']) == 2) {
									$QuestionPaperHtml .= '	<div class="form-group" style="padding-left: 12px;margin-top: 20px;">';
									$QuestionPaperHtml .= '		<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 16px;font-weight: bold;color: midnightblue;">SECTION: </label>';
									$QuestionPaperHtml .= '		<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 16px;font-weight: bold;color: midnightblue;">' . strtoupper($SectionData['section_name']) . '</label>';
									$QuestionPaperHtml .= '	</div>';
								}

								foreach ($SectionData['question_data'] AS $QuestionData) {
									$QuestionIndex++;

									//QUESTION TEXT AND IMAGE
									$QuestionPaperHtml .= '	<div class="form-group" style="padding-left: 12px;margin-top: 20px;">';
									$QuestionPaperHtml .= '		<div style="width:100%">';
									$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 14px;color: steelblue;width: 25px;">' . $QuestionIndex . '. </label>';
									$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 14px;color: steelblue;">' . $QuestionData['question_text'] . '</label>';

									if (trim($QuestionData['question_img']) != "") {
										$QuestionPaperHtml .= '		<div style="width:100%; padding-left: 25px;margin-top: 10px;margin-bottom: 10px;">';
										$QuestionPaperHtml .= '			<img src="' . trim($QuestionData['question_img']) . '" style="height:100px;"></img>';
										$QuestionPaperHtml .= '		</div>';
									}
									$QuestionPaperHtml .= '		</div>';


									//OPTION 1 TEXT AND IMAGE
									$QuestionPaperHtml .= '		<div style="width:100%; padding-left: 25px;">';
									$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;">a. </label>';

									if (trim($QuestionData['option1_img']) != "") {
										$QuestionPaperHtml .= '		<div style="display: inline-block;margin-top:10px;">';
										$QuestionPaperHtml .= '			<img src="' . trim($QuestionData['option1_img']) . '" style="height:100px;"></img>';
										$QuestionPaperHtml .= '		</div>';
									}

									if (trim($QuestionData['option1_text']) != "")
										$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;">' . $QuestionData['option1_text'] . '</label>';
									$QuestionPaperHtml .= '		</div>';


									//OPTION 2 TEXT AND IMAGE
									$QuestionPaperHtml .= '		<div style="width:100%; padding-left: 25px;">';
									$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;">b. </label>';

									if (trim($QuestionData['option2_img']) != "") {
										$QuestionPaperHtml .= '		<div style="display: inline-block;margin-top:10px;">';
										$QuestionPaperHtml .= '			<img src="' . trim($QuestionData['option2_img']) . '" style="height:100px;"></img>';
										$QuestionPaperHtml .= '		</div>';
									}

									if (trim($QuestionData['option2_text']) != "")
										$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;">' . $QuestionData['option2_text'] . '</label>';
									$QuestionPaperHtml .= '		</div>';


									//OPTION 3 TEXT AND IMAGE
									$QuestionPaperHtml .= '		<div style="width:100%; padding-left: 25px;">';
									$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;">c. </label>';

									if (trim($QuestionData['option3_img']) != "") {
										$QuestionPaperHtml .= '		<div style="display: inline-block;margin-top:10px;">';
										$QuestionPaperHtml .= '			<img src="' . trim($QuestionData['option3_img']) . '" style="height:100px;"></img>';
										$QuestionPaperHtml .= '		</div>';
									}

									if (trim($QuestionData['option3_text']) != "")
										$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;">' . $QuestionData['option3_text'] . '</label>';
									$QuestionPaperHtml .= '		</div>';


									//OPTION 4 TEXT AND IMAGE
									$QuestionPaperHtml .= '		<div style="width:100%; padding-left: 25px;">';
									$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;">d. </label>';

									if (trim($QuestionData['option4_img']) != "") {
										$QuestionPaperHtml .= '		<div style="display: inline-block;margin-top:10px;">';
										$QuestionPaperHtml .= '			<img src="' . trim($QuestionData['option4_img']) . '" style="height:100px;"></img>';
										$QuestionPaperHtml .= '		</div>';
									}

									if (trim($QuestionData['option4_text']) != "")
										$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;">' . $QuestionData['option4_text'] . '</label>';
									$QuestionPaperHtml .= '		</div>';


									//OPTION 5 TEXT AND IMAGE
									if (intval($QuestionData['question_type_id']) != 2) {
										$QuestionPaperHtml .= '		<div style="width:100%; padding-left: 25px;">';
										$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;">e. </label>';

										if (trim($QuestionData['option4_img']) != "") {
											$QuestionPaperHtml .= '		<div style="display: inline-block;margin-top:10px;">';
											$QuestionPaperHtml .= '			<img src="' . trim($QuestionData['option5_img']) . '" style="height:100px;"></img>';
											$QuestionPaperHtml .= '		</div>';
										}

										if (trim($QuestionData['option5_text']) != "")
											$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;">' . $QuestionData['option5_text'] . '</label>';
										$QuestionPaperHtml .= '		</div>';

									}
									$QuestionPaperHtml .= '	</div>';
								}

								if (intval($PartData['part_id']) == 2)
									$QuestionPaperHtml .= '<hr>';
							}
						}

						if (intval($PartData['part_id']) == 1)
							$QuestionPaperHtml .= '<hr>';
					}
				}
			}

			$QuestionPaperHtml .= '</div>';

			$pdf->writeHTMLCell(0, 0, '', '', $QuestionPaperHtml, 0, 1, 0, true, '', true);

			$OutputPdfFileName = "IST_QuestionPaper_" . $PdfHeaderTitle . ".pdf";
			$pdf->Output($OutputPdfFileName, 'I');
		}
	}

	public function download_question_paper_preview_in_pdf($QuestionPaperId = 0, $LanguageId = 1)
	{
		set_time_limit(100);
		error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
		$user              = $this->pramaan->_check_module_task_auth(true);
		$QuestionPaperData = $this->content->get_preview_question_paper_data($QuestionPaperId, $LanguageId);

		$QuestionPaperHtml = "<style>
		@page { sheet-size: A3-L; }
 		@page bigger { sheet-size: 420mm 370mm; }
		@page toc { sheet-size: A4; }
		.header_title
		{
			margin-top:-50px;
			margin-left:20px;
			display:inline-block;
		}
		.header_logo
		{
			margin-top:-50px;
			margin-left:20px;
			display:inline-block;
		}
		</style>";
		if (count($QuestionPaperData) > 0) 
		{
			$this->load->library('M_pdf');
			//download it D save to disk F.
			$this->m_pdf->pdf->allow_charset_conversion=true;  // Set by default to TRUE
			$this->m_pdf->pdf->charset_in='UTF-8';
			$this->m_pdf->pdf->autoLangToFont = true;
			$this->m_pdf->pdf->autoScriptToLang = true;

			/*$this->load->library('Pdf');
			$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			
*/
		/*	$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Navriti Technologies');*/

			$this->m_pdf->pdf->SetAuthor('Navriti Technologies');
			$this->m_pdf->pdf->SetCreator('Navriti Technologies');

			$PdfHeaderTitle = 'Question Paper';
			$PdfHeaderText  = "IST Question Paper";

			if (count($QuestionPaperData['question_paper_data']) > 0) {
				//$pdf->SetTitle($QuestionPaperData['question_paper_data'][0]['question_paper_title']);
				$this->m_pdf->pdf->SetTitle($QuestionPaperData['question_paper_data'][0]['question_paper_title']);

				//$pdf->SetSubject($QuestionPaperData['question_paper_data'][0]['question_paper_title']);
				$this->m_pdf->pdf->SetSubject($QuestionPaperData['question_paper_data'][0]['question_paper_title']);

				$PdfHeaderTitle = $QuestionPaperData['question_paper_data'][0]['question_paper_title'];
				$PdfHeaderText  = $QuestionPaperData['question_paper_data'][0]['language_name'];
			}

			$HeaderLogo = '<img src="' . base_url() . 'nist-assets/images/logo/via_logo.png"/>';

			$header = array ('L' => array (
						        'content' =>'hihihi'),
						     'C' => array (
						        'content' => ''),
						     'R' => array (
						        'content' =>''),
						     'line' => 1);
			/*$pdf->SetHeaderData($HeaderLogo, 30, $PdfHeaderTitle, $PdfHeaderText, array(0, 64, 255), array(0, 64, 128));
			$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));*/
			
			$this->m_pdf->pdf->SetHeader ($header , '', true);
			$this->m_pdf->pdf->SetHTMLHeader('<div style="width:100%;margin-top:-5px;"><img height=40 src="' . base_url() . 'nist-assets/images/logo/via_logo.png"/><div style="text-align: center;margin-top:-30px; font-size:18px;">'.$PdfHeaderTitle.'</span><br><hr style="border-top: 1px solid blue; background: transparent;"></div>');
			$this->m_pdf->pdf->setFooter("Page {PAGENO} of {nb}");
		/*	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
*/			
			//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		/*	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->setFontSubsetting(true);
			$pdf->setFont('freeserif');*/
			//$pdf->AddPage();

			$this->m_pdf->pdf->AddPage('P','','','','',20,10,30,10,10,10);
			//$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

			$QuestionPaperHtml .= '<div id="divPageContainer" class="container-fluid">';
			if (count($QuestionPaperData['part_data']) > 0) {
				foreach ($QuestionPaperData['part_data'] AS $PartData) {
					$QuestionCount = 0;
					foreach ($PartData['section_data'] AS $SectionData)
						$QuestionCount += count($SectionData['question_data']);

					if ($QuestionCount > 0) {
						$QuestionIndex = 0;

						$QuestionPaperHtml .= '	<div class="form-group">';
						$QuestionPaperHtml .= '		<label class="col-sm-12 control-label" style="margin-top:-8px;text-align: center; font-size: 18px; font-weight: bold; color: #9913B2;">' . strtoupper($PartData['part_name']) . '</label>';
						$QuestionPaperHtml .= '	</div>';

						foreach ($PartData['section_data'] AS $SectionData) {
							if (count($SectionData['question_data']) > 0) {
								if (intval($PartData['part_id']) == 2) {
									$QuestionPaperHtml .= '	<div class="form-group" style="padding-left: 12px;margin-top: 20px;">';
									$QuestionPaperHtml .= '		<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 16px;font-weight: bold;color: midnightblue;">SECTION: </label>';
									$QuestionPaperHtml .= '		<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 16px;font-weight: bold;color: midnightblue;">' . strtoupper($SectionData['section_name']) . '</label>';
									$QuestionPaperHtml .= '	</div>';
								}

								foreach ($SectionData['question_data'] AS $QuestionData) {
									$QuestionIndex++;

									//QUESTION TEXT AND IMAGE
									$QuestionPaperHtml .= '	<div class="form-group" style="padding-left: 12px;margin-top: 20px;">';
									$QuestionPaperHtml .= '		<div style="width:100%">';
									$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 14px;color: steelblue;width: 25px;">' . $QuestionIndex . '. </label>';
									$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 14px;color: steelblue;">' . $QuestionData['question_text'] . '</label>';

									if (trim($QuestionData['question_img']) != "") {
										$QuestionPaperHtml .= '		<div style="width:100%; padding-left: 25px;margin-top: 10px;margin-bottom: 10px;">';
										$QuestionPaperHtml .= '			<img src="' . trim($QuestionData['question_img']) . '" style="height:100px;"></img>';
										$QuestionPaperHtml .= '		</div>';
									}
									$QuestionPaperHtml .= '		</div>';


									//OPTION 1 TEXT AND IMAGE
									$QuestionPaperHtml .= '		<div style="width:100%; padding-left: 25px;">';
									$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;">a. </label>';

									if (trim($QuestionData['option1_img']) != "") {
										$QuestionPaperHtml .= '		<div style="display: inline-block;margin-top:10px;">';
										$QuestionPaperHtml .= '			<img src="' . trim($QuestionData['option1_img']) . '" style="height:100px;"></img>';
										$QuestionPaperHtml .= '		</div>';
									}

									if (trim($QuestionData['option1_text']) != "")
										$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;">' . $QuestionData['option1_text'] . '</label>';
									$QuestionPaperHtml .= '		</div>';


									//OPTION 2 TEXT AND IMAGE
									$QuestionPaperHtml .= '		<div style="width:100%; padding-left: 25px;">';
									$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;">b. </label>';

									if (trim($QuestionData['option2_img']) != "") {
										$QuestionPaperHtml .= '		<div style="display: inline-block;margin-top:10px;">';
										$QuestionPaperHtml .= '			<img src="' . trim($QuestionData['option2_img']) . '" style="height:100px;"></img>';
										$QuestionPaperHtml .= '		</div>';
									}

									if (trim($QuestionData['option2_text']) != "")
										$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;">' . $QuestionData['option2_text'] . '</label>';
									$QuestionPaperHtml .= '		</div>';


									//OPTION 3 TEXT AND IMAGE
									$QuestionPaperHtml .= '		<div style="width:100%; padding-left: 25px;">';
									$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;">c. </label>';

									if (trim($QuestionData['option3_img']) != "") {
										$QuestionPaperHtml .= '		<div style="display: inline-block;margin-top:10px;">';
										$QuestionPaperHtml .= '			<img src="' . trim($QuestionData['option3_img']) . '" style="height:100px;"></img>';
										$QuestionPaperHtml .= '		</div>';
									}

									if (trim($QuestionData['option3_text']) != "")
										$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;">' . $QuestionData['option3_text'] . '</label>';
									$QuestionPaperHtml .= '		</div>';


									//OPTION 4 TEXT AND IMAGE
									$QuestionPaperHtml .= '		<div style="width:100%; padding-left: 25px;">';
									$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;">d. </label>';

									if (trim($QuestionData['option4_img']) != "") {
										$QuestionPaperHtml .= '		<div style="display: inline-block;margin-top:10px;">';
										$QuestionPaperHtml .= '			<img src="' . trim($QuestionData['option4_img']) . '" style="height:100px;"></img>';
										$QuestionPaperHtml .= '		</div>';
									}

									if (trim($QuestionData['option4_text']) != "")
										$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;">' . $QuestionData['option4_text'] . '</label>';
									$QuestionPaperHtml .= '		</div>';


									//OPTION 5 TEXT AND IMAGE
									if (intval($QuestionData['question_type_id']) != 2) {
										$QuestionPaperHtml .= '		<div style="width:100%; padding-left: 25px;">';
										$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;width: 25px;">e. </label>';

										if (trim($QuestionData['option4_img']) != "") {
											$QuestionPaperHtml .= '		<div style="display: inline-block;margin-top:10px;">';
											$QuestionPaperHtml .= '			<img src="' . trim($QuestionData['option5_img']) . '" style="height:100px;"></img>';
											$QuestionPaperHtml .= '		</div>';
										}

										if (trim($QuestionData['option5_text']) != "")
											$QuestionPaperHtml .= '			<label class="control-label" style="margin-top:-8px;text-align: left; font-size: 12px;">' . $QuestionData['option5_text'] . '</label>';
										$QuestionPaperHtml .= '		</div>';

									}
									$QuestionPaperHtml .= '	</div>';
								}

								if (intval($PartData['part_id']) == 2)
									$QuestionPaperHtml .= '<hr>';
							}
						}

						if (intval($PartData['part_id']) == 1)
							$QuestionPaperHtml .= '<hr>';
					}
				}
			}

			$QuestionPaperHtml .= '</div>';
			$OutputPdfFileName = "IST_QuestionPaper_" . $PdfHeaderTitle . ".pdf";
/*
			$pdf->writeHTMLCell(0, 0, '', '', $QuestionPaperHtml, 0, 1, 0, true, '', true);

			
			$pdf->Output($OutputPdfFileName, 'I');*/
			$this->m_pdf->pdf->WriteHTML($QuestionPaperHtml);
			$this->m_pdf->pdf->Output($OutputPdfFileName , 'D');
		}
	}
	public function pdf_kan()
	{
		set_time_limit(100);
		error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
	/*	$this->load->library('Pdf');
			$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->SetFont('lohitkannada', '', 10);

			$pdf->AddPage();
*/

			$html = '<!DOCTYPE html>
			<html>

			<head>
			<meta charset="UTF-8">
			<style>
				body
				{
					font-family:Lohit-Kannada;
					font-size: 8pt;
				}
			</style>
			</head>

			<body>
			<h1>Kannada fonts</h1>
			<p>I like driving. ನನಗೆ ವಾಹನ ಚಾಲನೆ ಮಾಡುವುದು ಇಷ್ಟ</p>
			Devanagari (<span lang="hi">&#x928;&#x92e;&#x938;&#x94d;&#x924;&#x947;</span>), Gujarati (<span lang="gu">&#xaa8;&#xaae;&#xab8;&#xacd;&#xaa4;&#xac7;</span>), Punjabi (<span lang="pa">&#xa38;&#xa24;&#xa3f; &#xa38;&#xa4d;&#xa30;&#xa40; &#xa05;&#xa15;&#xa3e;&#xa32;</span>),<br />
Kannada (<span lang="kn">&#xca8;&#xcae;&#xcb8;&#xccd;&#xca4;&#xcc6;</span>)
			

			<div style="font-family: arialunicodems; font-size: 36pt; font-feature-settings:\'dist\' 0;">
&#xc95;&#xccd;&#xcb0;&#xccc; Kannada
</div>
			<div>
			I like driving. Tamil <br> <span class="version-color">எனக்கு வாகனம் ஓட்டப்பிடிக்கும்</span></label>
			</div>
			strongly Agree<span> पूरी तरह सहमत</span>
			</body>

			</html>';
			/*$params = TCPDF_STATIC::serializeTCPDFtagParameters(array('CODE 39', 'C39', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
			$html .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';

			$params = TCPDF_STATIC::serializeTCPDFtagParameters(array('CODE 128', 'C128', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
			$html .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';

			$html .= '<tcpdf method="AddPage" /><h2>Graphic Functions</h2>';

			$params = TCPDF_STATIC::serializeTCPDFtagParameters(array(0));
			$html .= '<tcpdf method="SetDrawColor" params="'.$params.'" />';

			$params = TCPDF_STATIC::serializeTCPDFtagParameters(array(50, 50, 40, 10, 'DF', array(), array(0,128,255)));
			$html .= '<tcpdf method="Rect" params="'.$params.'" />';*/
		/*	$pdf->writeHTML($html, true, 0, true, 0);

			$pdf->lastPage();
			$pdf->Output('example_049.pdf', 'I');*/
			
			//mpdf
/*			$pdfFilePath = base_url("resources/file1.pdf");*/
			$pdfFilePath = FCPATH."/downloads/filename.pdf";
		/*	$this->load->library('cpdf');
			$pdf = $this->cpdf->load();
			$pdf->WriteHTML($html); // write the HTML into the PDF

			$pdf->Output($pdfFilePath, 'F'); // save to file because we can
	
			$html = $this->load->view('unpaid_voucher',$data,true);*/
			// unpaid_voucher is unpaid_voucher.php file in view directory and $data variable has infor mation that you want to render on view.

			$this->load->library('M_pdf');
			//download it D save to disk F.
			$this->m_pdf->pdf->allow_charset_conversion=true;  // Set by default to TRUE
			$this->m_pdf->pdf->charset_in='UTF-8';
			$this->m_pdf->pdf->autoLangToFont = true;
			$this->m_pdf->pdf->autoScriptToLang = true;
			$this->m_pdf->pdf->WriteHTML($html);
			//$this->m_pdf->pdf->Output($pdfFilePath, 'F');
			$this->m_pdf->pdf->Output('filename.pdf', 'D');
	}
	public function pdf_chart()
	{
		set_time_limit(100);
		error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
	/*	$this->load->library('Pdf');
			$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->SetFont('lohitkannada', '', 10);

			$pdf->AddPage();
*/

			$html = '<!DOCTYPE html>
			<html lang="en-US">
			<body>

			<h1>My Web Page</h1>

			<div id="piechart"></div>

			<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

			<script type="text/javascript">
			// Load google charts
			google.charts.load("current", {"packages":["corechart"]});
			google.charts.setOnLoadCallback(drawChart);

			// Draw the chart and set the chart values
			function drawChart() {
			  var data = google.visualization.arrayToDataTable([
			  ["Task", "Hours per Day"],
			  ["Work", 8],
			  ["Eat", 2],
			  ["TV", 4],
			  ["Gym", 2],
			  ["Sleep", 8]
			]);

			  // Optional; add a title and set the width and height of the chart
			  var options = {"title":"My Average Day", "width":550, "height":400};

			  // Display the chart inside the <div> element with id="piechart"
			  var chart = new google.visualization.PieChart(document.getElementById("piechart"));
			  chart.draw(data, options);
			}
			</script>

			</body>
			</html>';
			/*$params = TCPDF_STATIC::serializeTCPDFtagParameters(array('CODE 39', 'C39', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
			$html .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';

			$params = TCPDF_STATIC::serializeTCPDFtagParameters(array('CODE 128', 'C128', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
			$html .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';

			$html .= '<tcpdf method="AddPage" /><h2>Graphic Functions</h2>';

			$params = TCPDF_STATIC::serializeTCPDFtagParameters(array(0));
			$html .= '<tcpdf method="SetDrawColor" params="'.$params.'" />';

			$params = TCPDF_STATIC::serializeTCPDFtagParameters(array(50, 50, 40, 10, 'DF', array(), array(0,128,255)));
			$html .= '<tcpdf method="Rect" params="'.$params.'" />';*/
		/*	$pdf->writeHTML($html, true, 0, true, 0);

			$pdf->lastPage();
			$pdf->Output('example_049.pdf', 'I');*/
			
			//mpdf
/*			$pdfFilePath = base_url("resources/file1.pdf");*/
			$pdfFilePath = FCPATH."/downloads/filename.pdf";
		/*	$this->load->library('cpdf');
			$pdf = $this->cpdf->load();
			$pdf->WriteHTML($html); // write the HTML into the PDF

			$pdf->Output($pdfFilePath, 'F'); // save to file because we can
	
			$html = $this->load->view('unpaid_voucher',$data,true);*/
			// unpaid_voucher is unpaid_voucher.php file in view directory and $data variable has infor mation that you want to render on view.

			$this->load->library('M_pdf');
			//download it D save to disk F.
			$this->m_pdf->pdf->allow_charset_conversion=true;  // Set by default to TRUE
			$this->m_pdf->pdf->charset_in='UTF-8';
			$this->m_pdf->pdf->autoLangToFont = true;
			$this->m_pdf->pdf->autoScriptToLang = true;
			$this->m_pdf->pdf->WriteHTML($html);
			//$this->m_pdf->pdf->Output($pdfFilePath, 'F');
			$this->m_pdf->pdf->Output('filename.pdf', 'D');
	}

	public function get_candidate_questions($CandidateId = 0, $LanguageId = 1)
	{
		$user = $this->pramaan->_check_module_task_auth(true);
		if ($CandidateId == 0)  $CandidateId = $user['id'];

		$this->ScaleAllQuestionImages();

		$ResponseData = array();

		$CandidateQuestionArray = $this->content->get_candidate_questions($CandidateId, $LanguageId);

		$QuestionImgBase64String = "";
		$Option1ImgBase64String  = "";
		$Option2ImgBase64String  = "";
		$Option3ImgBase64String  = "";
		$Option4ImgBase64String  = "";
		$Option5ImgBase64String  = "";
		$QuestionImagePath       = realpath(QUESTION_IMAGES_SCALED) . "/";

		foreach ($CandidateQuestionArray as $CandidateQuestion)
		{
			$QuestionImgBase64String = "";
			if (!is_null($CandidateQuestion['question_img']))
				if (trim($CandidateQuestion['question_img']) != '')
					$QuestionImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($CandidateQuestion['question_img']));

			$Option1ImgBase64String = "";
			if (!is_null($CandidateQuestion['option1_img']))
				if (trim($CandidateQuestion['option1_img']) != '')
					$Option1ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($CandidateQuestion['option1_img']));

			$Option2ImgBase64String = "";
			if (!is_null($CandidateQuestion['option2_img']))
				if (trim($CandidateQuestion['option2_img']) != '')
					$Option2ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($CandidateQuestion['option2_img']));

			$Option3ImgBase64String = "";
			if (!is_null($CandidateQuestion['option3_img']))
				if (trim($CandidateQuestion['option3_img']) != '')
					$Option3ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($CandidateQuestion['option3_img']));

			$Option4ImgBase64String = "";
			if (!is_null($CandidateQuestion['option4_img']))
				if (trim($CandidateQuestion['option4_img']) != '')
					$Option4ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($CandidateQuestion['option4_img']));

			$Option5ImgBase64String = "";
			if (!is_null($CandidateQuestion['option5_img']))
				if (trim($CandidateQuestion['option5_img']) != '')
					$Option5ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($CandidateQuestion['option5_img']));

			$ResponseData[] = array(
				'candidate_id' 				=> $CandidateQuestion['candidate_id'],
				'assessment_id' 			=> $CandidateQuestion['assessment_id'],
				'completed_part_id' 		=> $CandidateQuestion['completed_part_id'],
				'current_question_id' 		=> $CandidateQuestion['current_question_id'],
				'current_assessment_time' 	=> $CandidateQuestion['current_assessment_time'],
				'status_id' 				=> $CandidateQuestion['status_id'],
				'status_name' 				=> $CandidateQuestion['status_name'],
				'question_paper_id' 		=> $CandidateQuestion['question_paper_id'],
				'question_paper_title' 		=> $CandidateQuestion['question_paper_title'],
				'part_id' 					=> $CandidateQuestion['part_id'],
				'part_code' 				=> $CandidateQuestion['part_code'],
				'part_name' 				=> $CandidateQuestion['part_name'],
				'section_id' 				=> $CandidateQuestion['section_id'],
				'section_code' 				=> $CandidateQuestion['section_code'],
				'section_name' 				=> $CandidateQuestion['section_name'],
				'question_sno' 				=> $CandidateQuestion['question_sno'],
				'question_id' 				=> $CandidateQuestion['question_id'],
				'question_text' 			=> $CandidateQuestion['question_text'],
				'question_type_id' 			=> $CandidateQuestion['question_type_id'],
				'question_type_name' 		=> $CandidateQuestion['question_type_name'],
				'option1_text' 				=> $CandidateQuestion['option1_text'],
				'option2_text' 				=> $CandidateQuestion['option2_text'],
				'option3_text' 				=> $CandidateQuestion['option3_text'],
				'option4_text' 				=> $CandidateQuestion['option4_text'],
				'option5_text' 				=> $CandidateQuestion['option5_text'],
				'candidate_response' 		=> $CandidateQuestion['candidate_response'],
				'reverse_values' 			=> $CandidateQuestion['reverse_values'],
				'question_img' 				=> $CandidateQuestion['question_img'],
				'option1_img' 				=> $CandidateQuestion['option1_img'],
				'option2_img' 				=> $CandidateQuestion['option2_img'],
				'option3_img' 				=> $CandidateQuestion['option3_img'],
				'option4_img' 				=> $CandidateQuestion['option4_img'],
				'option5_img' 				=> $CandidateQuestion['option5_img'],
				'question_img_base64' 		=> $QuestionImgBase64String,
				'option1_img_base64' 		=> $Option1ImgBase64String,
				'option2_img_base64' 		=> $Option2ImgBase64String,
				'option3_img_base64' 		=> $Option3ImgBase64String,
				'option4_img_base64' 		=> $Option4ImgBase64String,
				'option5_img_base64' 		=> $Option5ImgBase64String,
				'created_by' 				=> $CandidateQuestion['created_by'],
				'created_on' 				=> $CandidateQuestion['created_on']
			);
		}

		echo json_encode($ResponseData);
	}

	public function get_candidate_sample_questions_bak($CandidateId = 0, $LanguageId = 1)
	{
		$user = $this->pramaan->_check_module_task_auth(true);
		if ($CandidateId == 0)  $CandidateId = $user['id'];

		$ResponseData = array();

		$CandidateQuestionArray = $this->content->do_get_candidate_sample_questions();
		//print_r($CandidateQuestionArray);

		$QuestionImgBase64String = "";
		$Option1ImgBase64String  = "";
		$Option2ImgBase64String  = "";
		$Option3ImgBase64String  = "";
		$Option4ImgBase64String  = "";
		$Option5ImgBase64String  = "";
		$QuestionImagePath       = realpath(QUESTION_IMAGES_SCALED) . "/";

		foreach ($CandidateQuestionArray as $CandidateQuestion) {
			$QuestionImgBase64String = "";
			if (!is_null($CandidateQuestion['question_img']))
				if (trim($CandidateQuestion['question_img']) != '')
				{

					$QuestionImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($CandidateQuestion['question_img']));
					//echo json_encode($QuestionImgBase64String);
				}

			$Option1ImgBase64String = "";
			if (!is_null($CandidateQuestion['option1_img']))
				if (trim($CandidateQuestion['option1_img']) != '')
					$Option1ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($CandidateQuestion['option1_img']));

			$Option2ImgBase64String = "";
			if (!is_null($CandidateQuestion['option2_img']))
				if (trim($CandidateQuestion['option2_img']) != '')
					$Option2ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($CandidateQuestion['option2_img']));

			$Option3ImgBase64String = "";
			if (!is_null($CandidateQuestion['option3_img']))
				if (trim($CandidateQuestion['option3_img']) != '')
					$Option3ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($CandidateQuestion['option3_img']));

			$Option4ImgBase64String = "";
			if (!is_null($CandidateQuestion['option4_img']))
				if (trim($CandidateQuestion['option4_img']) != '')
					$Option4ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($CandidateQuestion['option4_img']));

			$Option5ImgBase64String = "";
			if (!is_null($CandidateQuestion['option5_img']))
				if (trim($CandidateQuestion['option5_img']) != '')
					$Option5ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($CandidateQuestion['option5_img']));

			$ResponseData[] = array(
				'question_id' 				=> $CandidateQuestion['question_id'],
				'question_text' 			=> $CandidateQuestion['question_text'],
				'question_type_id' 			=> $CandidateQuestion['question_type_id'],
				'option1_text' 				=> $CandidateQuestion['option1_text'],
				'option2_text' 				=> $CandidateQuestion['option2_text'],
				'option3_text' 				=> $CandidateQuestion['option3_text'],
				'option4_text' 				=> $CandidateQuestion['option4_text'],
				'option5_text' 				=> $CandidateQuestion['option5_text'],
				'question_img' 				=> $CandidateQuestion['question_img'],
				'option1_img' 				=> $CandidateQuestion['option1_img'],
				'option2_img' 				=> $CandidateQuestion['option2_img'],
				'option3_img' 				=> $CandidateQuestion['option3_img'],
				'option4_img' 				=> $CandidateQuestion['option4_img'],
				'option5_img' 				=> $CandidateQuestion['option5_img'],
				'question_img_base64' 		=> $QuestionImgBase64String,
				'option1_img_base64' 		=> $Option1ImgBase64String,
				'option2_img_base64' 		=> $Option2ImgBase64String,
				'option3_img_base64' 		=> $Option3ImgBase64String,
				'option4_img_base64' 		=> $Option4ImgBase64String,
				'option5_img_base64' 		=> $Option5ImgBase64String,
			);
		}

		//print_r($ResponseData);
		echo json_encode($ResponseData);
	}

	public function get_candidate_sample_questions($LanguageId = 1)
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$this->ScaleAllSampleQuestionImages();

		$ResponseData = array();

		$SampleQuestionArray = $this->content->get_candidate_sample_questions();
		//print_r($CandidateQuestionArray);

		$QuestionImgBase64String = "";
		$Option1ImgBase64String  = "";
		$Option2ImgBase64String  = "";
		$Option3ImgBase64String  = "";
		$Option4ImgBase64String  = "";
		$Option5ImgBase64String  = "";
		$QuestionImagePath       = realpath(QUESTION_IMAGES_SCALED) . "/";

		foreach ($SampleQuestionArray as $SampleQuestion)
		{
			$QuestionImgBase64String = "";
			if (!is_null($SampleQuestion['question_img']))
				if (trim($SampleQuestion['question_img']) != '')
					$QuestionImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($SampleQuestion['question_img']));

			$Option1ImgBase64String = "";
			if (!is_null($SampleQuestion['option1_img']))
				if (trim($SampleQuestion['option1_img']) != '')
					$Option1ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($SampleQuestion['option1_img']));

			$Option2ImgBase64String = "";
			if (!is_null($SampleQuestion['option2_img']))
				if (trim($SampleQuestion['option2_img']) != '')
					$Option2ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($SampleQuestion['option2_img']));

			$Option3ImgBase64String = "";
			if (!is_null($SampleQuestion['option3_img']))
				if (trim($SampleQuestion['option3_img']) != '')
					$Option3ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($SampleQuestion['option3_img']));

			$Option4ImgBase64String = "";
			if (!is_null($SampleQuestion['option4_img']))
				if (trim($SampleQuestion['option4_img']) != '')
					$Option4ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($SampleQuestion['option4_img']));

			$Option5ImgBase64String = "";
			if (!is_null($SampleQuestion['option5_img']))
				if (trim($SampleQuestion['option5_img']) != '')
					$Option5ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($SampleQuestion['option5_img']));

			$ResponseData[] = array(
				'question_paper_title' 		=> $SampleQuestion['question_paper_title'],
				'question_language_id' 		=> $SampleQuestion['question_language_id'],
				'question_language_name' 	=> $SampleQuestion['question_language_name'],
				'question_sno' 				=> $SampleQuestion['question_sno'],
				'question_id' 				=> $SampleQuestion['question_id'],
				'question_text' 			=> $SampleQuestion['question_text'],
				'question_type_id' 			=> $SampleQuestion['question_type_id'],
				'option1_text' 				=> $SampleQuestion['option1_text'],
				'option2_text' 				=> $SampleQuestion['option2_text'],
				'option3_text' 				=> $SampleQuestion['option3_text'],
				'option4_text' 				=> $SampleQuestion['option4_text'],
				'option5_text' 				=> $SampleQuestion['option5_text'],
				'question_img' 				=> $SampleQuestion['question_img'],
				'option1_img' 				=> $SampleQuestion['option1_img'],
				'option2_img' 				=> $SampleQuestion['option2_img'],
				'option3_img' 				=> $SampleQuestion['option3_img'],
				'option4_img' 				=> $SampleQuestion['option4_img'],
				'option5_img' 				=> $SampleQuestion['option5_img'],
				'question_img_base64' 		=> $QuestionImgBase64String,
				'option1_img_base64' 		=> $Option1ImgBase64String,
				'option2_img_base64' 		=> $Option2ImgBase64String,
				'option3_img_base64' 		=> $Option3ImgBase64String,
				'option4_img_base64' 		=> $Option4ImgBase64String,
				'option5_img_base64' 		=> $Option5ImgBase64String,
			);
		}
		echo json_encode($ResponseData);
	}

	public function get_image_base64_string($imagePath)
	{
		$strImgData = "";
		if (file_exists($imagePath))
		{
			$type       = get_mime_by_extension($imagePath);
			//$strImgData = 'data:' . $type . ';base64,' . base64_encode(file_get_contents($imagePath));
			$strImgData = base64_encode(file_get_contents($imagePath));
			//echo '<img src="'.$strImgData.'" alt=""><br>';
			//$img_data = '<img src="'.$strImgData.'" alt=""><br>';
		}
		return $strImgData;
	}

	public function get_instructions($assessment_type_id = -1, $part_id = -1, $language_id = 1)
	{
		$part_id = isset($_REQUEST['part_id']) ?  $_REQUEST['part_id'] : $part_id;
		$language_id = isset($_REQUEST['language_id']) ?  $_REQUEST['language_id'] : $language_id;
		//$ResponseData = $this->content->get_instructions($part_id, $language_id);
		$ResponseData = $this->content->get_assessment_instructions($assessment_type_id, $part_id, $language_id);
		echo json_encode($ResponseData);
	}

	public function get_sample_instructions($language_id = 1)
	{
		$language_id = isset($_REQUEST['language_id']) ?  $_REQUEST['language_id'] : $language_id;
		$ResponseData = $this->content->get_sample_instructions($language_id);
		echo json_encode($ResponseData);
	}

	//GET SAMPLE QUESTION FROM HERE
	public function get_sample_questions($LanguageId = 1)
	{
		$LanguageId = isset($_REQUEST['language_id']) ?  $_REQUEST['language_id'] : $LanguageId;
		$QuestionData = $this->content->get_sample_questions($LanguageId);

		$ResponseData = array();

		$QuestionImgBase64String = "";
		$Option1ImgBase64String  = "";
		$Option2ImgBase64String  = "";
		$Option3ImgBase64String  = "";
		$Option4ImgBase64String  = "";
		$Option5ImgBase64String  = "";
		$QuestionImagePath       = realpath(SAMPLE_QUESTION_IMAGES_SCALED) . "/";

		foreach ($QuestionData as $Question) {
			$QuestionImgBase64String = "";
			if (!is_null($Question['question_img']))
				if (trim($Question['question_img']) != '')
					$QuestionImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($Question['question_img']));

			$Option1ImgBase64String = "";
			if (!is_null($Question['option1_img']))
				if (trim($Question['option1_img']) != '')
					$Option1ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($Question['option1_img']));

			$Option2ImgBase64String = "";
			if (!is_null($Question['option2_img']))
				if (trim($Question['option2_img']) != '')
					$Option2ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($Question['option2_img']));

			$Option3ImgBase64String = "";
			if (!is_null($Question['option3_img']))
				if (trim($Question['option3_img']) != '')
					$Option3ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($Question['option3_img']));

			$Option4ImgBase64String = "";
			if (!is_null($Question['option4_img']))
				if (trim($Question['option4_img']) != '')
					$Option4ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($Question['option4_img']));

			$Option5ImgBase64String = "";
			if (!is_null($Question['option5_img']))
				if (trim($Question['option5_img']) != '')
					$Option5ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($Question['option5_img']));

			
			$ResponseData[] = array(
				'question_paper_title' 		=> $Question['question_paper_title'],
				'part_id' 					=> $Question['part_id'],
				'part_code' 				=> $Question['part_code'],
				'part_name' 				=> $Question['part_name'],
				'section_id' 				=> $Question['section_id'],
				'section_code' 				=> $Question['section_code'],
				'section_name' 				=> $Question['section_name'],
				'question_sno' 				=> $Question['question_sno'],
				'question_id' 				=> $Question['question_id'],
				'question_text' 			=> $Question['question_text'],
				'question_type_id' 			=> $Question['question_type_id'],
				'question_type_name' 		=> $Question['question_type_name'],
				'option1_text' 				=> $Question['option1_text'],
				'option2_text' 				=> $Question['option2_text'],
				'option3_text' 				=> $Question['option3_text'],
				'option4_text' 				=> $Question['option4_text'],
				'option5_text' 				=> $Question['option5_text'],
				'question_img' 				=> $Question['question_img'],
				'option1_img' 				=> $Question['option1_img'],
				'option2_img' 				=> $Question['option2_img'],
				'option3_img' 				=> $Question['option3_img'],
				'option4_img' 				=> $Question['option4_img'],
				'option5_img' 				=> $Question['option5_img'],
				'question_img_base64' 		=> $QuestionImgBase64String,
				'option1_img_base64' 		=> $Option1ImgBase64String,
				'option2_img_base64' 		=> $Option2ImgBase64String,
				'option3_img_base64' 		=> $Option3ImgBase64String,
				'option4_img_base64' 		=> $Option4ImgBase64String,
				'option5_img_base64' 		=> $Option5ImgBase64String
			);
		}

		echo json_encode($ResponseData);
	}

	public function get_resource_list($LanguageId = 1, $ResourceCode = '')
	{
		$ResponseData = array();
		$LanguageId = isset($_REQUEST['language_id']) ?  $_REQUEST['language_id'] : $LanguageId;
		$ResourceCode = isset($_REQUEST['code']) ?  $_REQUEST['code'] : $ResourceCode;
		$ResourceList = $this->content->get_resource_list($LanguageId, $ResourceCode);
		foreach ($ResourceList as $Resource)
			$ResponseData[$Resource['resource_code']] = $Resource['resource_text'];

		echo json_encode($ResponseData);
	}
	//END: QUESTIONS - By George

	public function instructions($AssessmentTypeId = 1, $PartId = 0, $LanguageId = 1)
	{
		$user                 		= $this->pramaan->_check_module_task_auth(true);
		$data['page']         		= 'instructions';
		$data['module']       		= "content";
		$data['title']        		= 'Instructions';
		$data['user_role_id'] 		= $user['user_group_id'];
		$data['user_id']      		= $user['id'];
		$data['AssessmentTypeId']	= $AssessmentTypeId;
		$data['AssessmentTypeList'] = $this->content->get_assessment_type_list();
		$data['PartId']				= $PartId;
		$data['PartList'] 			= $this->content->get_instruction_part_list();
		$data['LanguageId']			= $LanguageId;
		$data['LanguageList'] 		= $this->content->get_language_list_data();
		$data['InstructionList'] 	= $this->content->get_instruction_list_data($AssessmentTypeId, $PartId, $LanguageId);
		$this->load->view('index', $data);
	}

	public function delete_instruction($PartId = -111, $Sno = -1)
	{
		$RequestArray = array(
			"part_id" 		=> (isset($_REQUEST['part_id']) ?  $_REQUEST['part_id'] : $PartId),
			"sno"			=> (isset($_REQUEST['sno']) ?  $_REQUEST['sno'] : $Sno)
		);
		
		$ResponseData = $this->content->delete_instruction($RequestArray);
		echo $ResponseData;
	}

	public function add_instruction($AssessmentTypeId = -111, $PartId = -111, $Instruction = '', $Sno = '-1')
	{
		$Sno = isset($_REQUEST['sno']) ?  $_REQUEST['sno'] : $Sno;
		if (trim($Sno) == '') $Sno = "-1";

		$RequestArray = array(
			"assessment_type_id" 	=> (isset($_REQUEST['assessment_type_id']) ?  $_REQUEST['assessment_type_id'] : $AssessmentTypeId),
			"part_id" 				=> (isset($_REQUEST['part_id']) ?  $_REQUEST['part_id'] : $PartId),
			"sno"					=> $Sno,
			"instruction"			=> (isset($_REQUEST['instruction']) ?  $_REQUEST['instruction'] : $Instruction)
		);

		$ResponseData = $this->content->add_instruction($RequestArray);
		echo $ResponseData;
	}

	public function save_instructions($AssessmentTypeId = -111, $PartId = -111, $LanguageId = -1, $InstructionList = array(), $InstructionVersionList = array())
	{
		$AssessmentTypeId = isset($_REQUEST['assessment_type_id']) ?  $_REQUEST['assessment_type_id'] : $AssessmentTypeId;
		$PartId = isset($_REQUEST['part_id']) ?  $_REQUEST['part_id'] : $PartId;
		$LanguageId = isset($_REQUEST['language_id']) ?  $_REQUEST['language_id'] : $LanguageId;
		$InstructionList = isset($_REQUEST['instruction_list']) ?  $_REQUEST['instruction_list'] : $InstructionList;
		$InstructionVersionList = isset($_REQUEST['instruction_version_list']) ?  $_REQUEST['instruction_version_list'] : $InstructionVersionList;

		$RequestArray = array();
		for($iSno = 0; $iSno < Count($InstructionList); $iSno++)
		{
			$RequestArray = array(
				"assessment_type_id"	=> $AssessmentTypeId,
				"part_id" 				=> $PartId,
				"sno"					=> ($iSno + 1),
				"eng_instruction"		=> $InstructionList[$iSno],
				"Language_id"			=> $LanguageId,
				"lang_instruction"		=> $InstructionVersionList[$iSno]
			);

			$ResponseArray[] = $this->content->save_instruction($RequestArray);
		}

		echo json_encode($ResponseArray);
	}

	public function change_instruction_position($PartId = -111, $PositionFrom = -1, $PositionTo = -1)
	{
		$ResponseData = array();
		$RequestArray = array(
			"part_id" 			=> (isset($_REQUEST['part_id']) ?  $_REQUEST['part_id'] : $PartId),
			"position_from"		=> (isset($_REQUEST['position_from']) ?  $_REQUEST['position_from'] : $PositionFrom),
			"position_to"		=> (isset($_REQUEST['position_to']) ?  $_REQUEST['position_to'] : $PositionTo)
		);

		$ResponseData = $this->content->change_instruction_position($RequestArray);
		echo json_encode($ResponseData);
	}
	public function template_bulk_download()
	{
		$this->load->helper('download');
		force_download(TEMPLATEBULKUPLOAD, NULL);
	}
	public function template_sample_question_download()
	{
		$this->load->helper('download');
		force_download(TEMPLATEBULKUPLOAD, NULL);
	}
}