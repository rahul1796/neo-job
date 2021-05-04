<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model :: Assessment Model
 * @author george.s@navriti.com
**/
class Content_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	//BEGIN: QUESTION PAPERS- By George
	function get_question_paper_data($PageRequestData = array())
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$strOrderBy = "";
		$SearchCondition = "";
		$Data = array();

		$arrColumnsToBeSearched = array("QP.question_paper_title", "QP.duration_minutes");
		$arrSortByColumns = array(
			0 => null,
			1 => 'QP.question_paper_title',
			2 => 'QP.duration_minutes',
			3 => null,
			4 => null
		);

		$strCondition = "";

		$strQuery = "SELECT COUNT(QP.id)::INTEGER AS total_record_count 
					 FROM 	content.question_papers AS QP
					 WHERE	TRUE ";

		$strQuery .= $strCondition;

		$strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
		$intTotalRecordCount = $strTotalRecordCount * 1;

		$intTotalFilteredCount = $intTotalRecordCount;

		if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '')
			$strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";

		$StartIndex = $PageRequestData['start'];
		$PageLength = $PageRequestData['length'];
		if ($PageLength < 0) $PageLength = 'all';

		if (!$intTotalRecordCount)
			return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
		else
		{
			$SearchCondition = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '')
			{
				$SearchCondition = " AND (";
				for ($i = 0; $i < count($arrColumnsToBeSearched); $i++)
					$SearchCondition .= $arrColumnsToBeSearched[$i] . " LIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";

				$SearchCondition = substr_replace($SearchCondition, "", -3);
				$SearchCondition .= ')';
			}

			$strQuery = "SELECT COUNT(QP.id)::INTEGER AS total_filtered_count 
						 FROM	content.question_papers AS QP
						 WHERE	TRUE ";
			$strQuery .= $strCondition;
			$strQuery .= $SearchCondition;
			$intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

			$strQuery = "SELECT 	QP.id AS question_paper_id,
									QP.question_paper_title,
									QP.duration_minutes,
									(SELECT COUNT(id) FROM content.questions WHERE question_paper_id = QP.id) AS question_count,
									QP.active_status
						 FROM		content.question_papers AS QP 
						 WHERE		TRUE ";
			$strQuery .= $strCondition;
			$strQuery .= $SearchCondition;
			$strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

			$QueryData = $this->db->query($strQuery);

			$SerialNumber = $StartIndex;
			$intActiveStatus = 1;
			$ActionButtons = "";

			$DurationMinutes = MAX_ASSESSMENT_TIME / 60;
			$Duration = $DurationMinutes;

			foreach ($QueryData->result() as $QueryRow)
			{
				$intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
				$Duration = ($QueryRow->duration_minutes > 0) ? $QueryRow->duration_minutes : $DurationMinutes;
				$ResponseRow = array();
				$SerialNumber++;
				$ResponseRow[] = $SerialNumber;
				$ResponseRow[] = $QueryRow->question_paper_title;
				$ResponseRow[] = '<center>' .  $Duration . '</center>';

				if ($QueryRow->question_count > 0)
					$ResponseRow[] = '<center><a class="btn btn-sm btn-primary" title="View Questions" href="javascript:void(0);" onclick="ShowQuestions(' . $QueryRow->question_paper_id . ')" style="margin-left:2px;">' . $QueryRow->question_count . '</a></center>';
				else
					$ResponseRow[] = '<center><a class="btn btn-sm" title="View Questions" href="javascript:void(0);" style="margin-left:2px;cursor: default;background-color: gray;color:white;">' . $QueryRow->question_count . '</a></center>';

				$ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->question_paper_id . "'" . ',' . $intActiveStatus . ')" style="width:90%;color:white;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';
				$ActionButtons = '<a class="btn btn-sm btn-primary" title="Edit Question Paper" href="javascript:void(0);" onclick="EditQuestionPaper(' . $QueryRow->question_paper_id . ')"><i class="icon-compose"></i></a>';
				$ActionButtons .= '<a class="btn btn-sm btn-primary" title="Preview Question Paper" href="javascript:void(0);" onclick="PreviewQuestionPaper(' . $QueryRow->question_paper_id . ')" style="margin-left:2px;"><i class="icon-eye"></i></a>';
				$ActionButtons .= '<a class="btn btn-sm btn-primary" title="Download In PDF" href="javascript:void(0);" onclick="DownloadQuestionPaperInPdf(' . $QueryRow->question_paper_id . ')" style="margin-left:2px;">pdf</a>';
				$ActionButtons .= '<a class="btn btn-sm btn-primary" title="Download Version Bulk Upload Template" href="javascript:void(0);" onclick="ShowVersionBulkUploadTemplatePopup(' . $QueryRow->question_paper_id . ',\'' . $QueryRow->question_paper_title . '\',' . $Duration . ')" style="margin-left:2px;"><i class="icon-arrow-down-a"></i></a>';

				if ($QueryRow->question_count < 1)
					$ActionButtons .= '<a class="btn btn-sm btn-primary" title="Upload Questions" href="javascript:void(0);" onclick="UploadQuestions(' . $QueryRow->question_paper_id . ',\'' . $QueryRow->question_paper_title . '\',' . $Duration . ')" style="margin-left:2px;"><i class="icon-android-upload"></i></a>';
				else
					$ActionButtons .= '<a class="btn btn-sm btn-primary" title="Upload Versions" href="javascript:void(0);" onclick="UploadVersions(' . $QueryRow->question_paper_id . ',\'' . $QueryRow->question_paper_title . '\',' . $Duration . ')" style="margin-left:2px;"><i class="icon-arrow-up-a"></i></a>';

				$ResponseRow[] = $ActionButtons;
				$Data[] = $ResponseRow;
			}

			$ResponseData = array(
				"draw" 				=> intval($PageRequestData['draw']),
				"recordsTotal" 		=> intval($intTotalRecordCount),
				"recordsFiltered" 	=> intval($intTotalFilteredCount),
				"data" 				=> $Data
			);

			return $ResponseData;
		}
	}

	function get_question_paper_list()
	{
		$Duration = MAX_ASSESSMENT_TIME;
		$strQuery = "SELECT 	QP.id AS question_paper_id,
								QP.question_paper_title,
								(CASE WHEN COALESCE(QP.duration_minutes, 0) < 0 THEN $Duration ELSE QP.duration_minutes END) AS duration_minutes
					 FROM		content.question_papers AS QP
					 WHERE		QP.active_status
					 ORDER BY	QP.question_paper_title";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_question_paper_list_for_batch_assignment($BatchId = 0)
	{
		$Duration = MAX_ASSESSMENT_TIME;
		$strQuery = "SELECT 	QP.id AS question_paper_id,
								QP.question_paper_title,
								(CASE WHEN COALESCE(QP.duration_minutes, 0) < 0 THEN $Duration ELSE QP.duration_minutes END) AS duration_minutes
					 FROM		content.question_papers AS QP
                     LEFT JOIN	assessment.batches AS B ON B.id = $BatchId 
					 WHERE		(QP.id = B.question_paper_id OR QP.active_status)
					 ORDER BY	QP.question_paper_title";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_question_paper_list_for_candidate_assignment($CandidateId = 0)
	{
		$Duration = MAX_ASSESSMENT_TIME;
		$strQuery = "SELECT 	QP.id AS question_paper_id,
								QP.question_paper_title,
								(CASE WHEN COALESCE(QP.duration_minutes, 0) < 0 THEN 90 ELSE QP.duration_minutes END) AS duration_minutes
					 FROM		content.question_papers AS QP
                     LEFT JOIN	assessment.assessments AS A ON A.candidate_id = $CandidateId
					 WHERE		(QP.id = A.question_paper_id OR QP.active_status)
					 ORDER BY	QP.question_paper_title";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_question_paper_detail($QuestionPaperId = 0, $LanguageId = 1)
	{
		$Duration = MAX_ASSESSMENT_TIME;

		$ResponseData = array();
		if ($QuestionPaperId > 0)
		{
			$strQuery = "SELECT	QP.id AS question_paper_id,
								QP.question_paper_title,
								(CASE WHEN COALESCE(QP.duration_minutes, 0) < 0 THEN $Duration ELSE QP.duration_minutes END) AS duration_minutes,
								(SELECT COUNT(id) FROM content.questions WHERE question_paper_id = QP.id) AS question_count,
								(SELECT name FROM master.list WHERE code = 'L0009' AND value = '$LanguageId') AS language_name								
						FROM	content.question_papers AS QP
						WHERE	QP.id = $QuestionPaperId";
			$ResponseData = $this->db->query($strQuery)->result_array();
		}

		return $ResponseData;
	}

	function save_question_paper_detail($RequestData = array())
	{
		$Parameters = array(
			0 => $RequestData['question_paper_id'],
			1 => $RequestData['question_paper_title'],
			2 => $RequestData['duration_minutes'],
			3 => $RequestData['user_id']
		);

		$ResponseData = array();
		$strQuery = "SELECT	* FROM content.fn_save_question_paper_detail(?,?,?,?)";
		$ResponseData = $this->db->query($strQuery, $Parameters)->result_array();
		return $ResponseData;
	}

	function change_question_paper_active_status($RequestData = array())
	{
		$ResponseData = array();
		if (intval($RequestData['id']) > 0)
		{
			$strStatus = 'TRUE';
			if (intval($RequestData['active_status']) > 0) $strStatus = 'FALSE';
			$strQuery = "UPDATE content.question_papers";
			$strQuery .= " SET active_status=" . $strStatus;
			$strQuery .= " WHERE id=" . $RequestData['id'];
			$this->db->query($strQuery);
			if ($this->db->affected_rows())
			{
				if ($strStatus == "FALSE")
					$ResponseData["message"] = "Question Paper Has Been Deactivated!";
				else
					$ResponseData["message"] = "Question Paper Has Been Activated!";
			}
		}

		return $ResponseData;
	}

	function save_question_paper_data_bak($PageRequestData)
	{
		$Parameters = array(
			$PageRequestData['LanguageId'],
			$PageRequestData['PartCodeList'],
			$PageRequestData['SectionCodeList'],
			$PageRequestData['QuestionTextList'],
			$PageRequestData['QuestionTypeList'],
			$PageRequestData['Option1TextList'],
			$PageRequestData['Option2TextList'],
			$PageRequestData['Option3TextList'],
			$PageRequestData['Option4TextList'],
			$PageRequestData['Option5TextList'],
			$PageRequestData['CorrectOptionList'],
			$PageRequestData['MarkList'],
			$PageRequestData['ReverseValueList'],
			$PageRequestData['QuestionImageList'],
			$PageRequestData['Option1ImageList'],
			$PageRequestData['Option2ImageList'],
			$PageRequestData['Option3ImageList'],
			$PageRequestData['Option4ImageList'],
			$PageRequestData['Option5ImageList'],
			$PageRequestData['user_id']
		);

		$strQuery = "SELECT * FROM content.fn_upload_bulk_questions(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$ResponseData = $this->db->query($strQuery, $Parameters)->result_array();

		return $ResponseData;
	}

	function upload_question_paper_data($PageRequestData)
	{
		$Parameters = array(
			$PageRequestData['QuestionPaperId'],
			$PageRequestData['PartCodeList'],
			$PageRequestData['SectionCodeList'],
			$PageRequestData['QuestionTextList'],
			$PageRequestData['QuestionTypeList'],
			$PageRequestData['Option1TextList'],
			$PageRequestData['Option2TextList'],
			$PageRequestData['Option3TextList'],
			$PageRequestData['Option4TextList'],
			$PageRequestData['Option5TextList'],
			$PageRequestData['CorrectOptionList'],
			$PageRequestData['MarkList'],
			$PageRequestData['ReverseValueList'],
			$PageRequestData['QuestionImageList'],
			$PageRequestData['Option1ImageList'],
			$PageRequestData['Option2ImageList'],
			$PageRequestData['Option3ImageList'],
			$PageRequestData['Option4ImageList'],
			$PageRequestData['Option5ImageList'],
			$PageRequestData['user_id']
		);

		$strQuery = "SELECT * FROM content.fn_upload_question_paper_data(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$ResponseData = $this->db->query($strQuery, $Parameters)->result_array();
		return $ResponseData;
	}

	function upload_question_paper_version_data($PageRequestData)
	{
		$Parameters = array(
			$PageRequestData['QuestionIdList'],
			$PageRequestData['RowIndexList'],
			$PageRequestData['LanguageNameList'],
			$PageRequestData['QuestionTextList'],
			$PageRequestData['Option1TextList'],
			$PageRequestData['Option2TextList'],
			$PageRequestData['Option3TextList'],
			$PageRequestData['Option4TextList'],
			$PageRequestData['Option5TextList'],
			$PageRequestData['user_id']
		);

		$strQuery = "SELECT * FROM content.fn_upload_question_paper_version_data(?,?,?,?,?,?,?,?,?,?)";
		$ResponseMessage = $this->db->query($strQuery, $Parameters)->row()->fn_upload_question_paper_version_data;

		return $ResponseMessage;
	}
	
	function get_language_version_question_data($QuestionPaperId = 0, $LanguageId = 0)
	{
		$Parameters = array( $QuestionPaperId, $LanguageId );
		$strQuery = "SELECT * FROM content.vw_question_language_versions WHERE question_paper_id = ? AND language_id IN (1,?)";
		$ResponseData = $this->db->query($strQuery, $Parameters)->result_array();

		return $ResponseData;
	}

	function get_language_list($QuestionPaperId = 0)
	{
		$ResponseData = array();
		$strQuery = "";
		$strCondition = " AND QP.assessment_type_id IS NULL ";

		if ($QuestionPaperId > 0)
		{
			$strQuery = "SELECT		L.value AS language_id,
									L.code AS language_code,
									L.name AS language_name
						 FROM		master.list AS L
						 LEFT JOIN	content.question_papers AS QP ON code='L0009' AND QP.language_id::TEXT = L.value 
						 WHERE 		QP.id = $QuestionPaperId";
		}
		else
		{
			$strQuery = "SELECT 	L.value AS language_id,
									L.code AS language_code,
									L.name AS language_name
						 FROM 		master.list AS L
						 WHERE 		L.code='L0009'
						 AND		L.value NOT IN (SELECT language_id::TEXT FROM content.question_papers)
						 ORDER BY	L.name";

		}

		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_language_list_data()
	{
		$ResponseData = array();
		$strQuery = "SELECT 	L.id AS language_id,
								L.code AS language_code,
								L.name AS language_name,
								L.native_name
					 FROM 		master.languages AS L
					 ORDER BY	L.sort_order";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_version_language_list_data()
	{
		$ResponseData = array();
		$strQuery = "SELECT 	L.id AS language_id,
								L.code AS language_code,
								L.name AS language_name,
								L.native_name
					 FROM 		master.languages AS L
					 WHERE		L.id <> 1
					 ORDER BY	L.name";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_preview_question_paper_data($QuestionPaperId = 0, $LanguageId = 1)
	{
		$ResponseData = array(
			'question_paper_data' 	=> $this->get_question_paper_detail($QuestionPaperId, $LanguageId),
			'part_data' 			=> $this->get_preview_part_data($QuestionPaperId, $LanguageId)
		);

		return $ResponseData;
	}

	function get_preview_part_data($question_paper_id = 0, $LanguageId = 1)
	{
		$ResponseData = array();
		$PartDataArray = $this->get_part_list();

		foreach ($PartDataArray as $PartData)
		{
			$ResponseData[] = array(
				'part_id' 		=> $PartData['part_id'],
				'part_code' 	=> $PartData['part_code'],
				'part_name' 	=> $PartData['part_name'],
				'section_data' 	=> $this->get_preview_section_data($question_paper_id, $PartData['part_id'], $LanguageId)
			);
		}

		return $ResponseData;
	}

	function get_preview_section_data($question_paper_id = 0, $part_id = 0, $LanguageId = 1)
	{
		$ResponseData = array();

		$SectionDataArray = $this->get_section_list_for_part_id($part_id);
		foreach ($SectionDataArray as $SectionData)
		{
			$ResponseData[] = array(
				'section_id' 	=> $SectionData['section_id'],
				'section_code' 	=> $SectionData['section_code'],
				'section_name' 	=> $SectionData['section_name'],
				'question_data' => $this->get_preview_question_data($question_paper_id, $SectionData['section_id'], $LanguageId)
			);
		}

		return $ResponseData;
	}

	function get_preview_question_data($question_paper_id, $section_id, $LanguageId = 1)
	{
		$QuestionImageFolderUrl = base_url(QUESTION_IMAGES);
		if ($LanguageId < 2) $LanguageId = 1;

		$strQuery = "SELECT 	Q.question_sno,
								Q.question_id,
								Q.question_paper_id,
								Q.question_paper_title,
								Q.part_id,
								Q.part_code,
								Q.part_name,
								Q.section_id,
								Q.section_code,
								Q.section_name,
                                Q.question_text || (CASE WHEN COALESCE(LQ.question_text, '') = '' OR COALESCE(Q.question_text, '') = COALESCE(LQ.question_text, '') THEN '' ELSE ' <br> <span class=\"version-color\">' || LQ.question_text || '</span>' END) AS question_text,                             
								Q.question_type_id,
								Q.question_type_name,
								Q.option1_text || (CASE WHEN COALESCE(LQ.option1_text, '') = '' OR COALESCE(Q.option1_text, '') = COALESCE(LQ.option1_text, '') THEN '' ELSE '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span class=\"version-color\">' || LQ.option1_text || '</span>' END) AS option1_text,                             
								Q.option2_text || (CASE WHEN COALESCE(LQ.option2_text, '') = '' OR COALESCE(Q.option2_text, '') = COALESCE(LQ.option2_text, '') THEN '' ELSE '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span class=\"version-color\">' || LQ.option2_text || '</span>' END) AS option2_text,                             
								Q.option3_text || (CASE WHEN COALESCE(LQ.option3_text, '') = '' OR COALESCE(Q.option3_text, '') = COALESCE(LQ.option3_text, '') THEN '' ELSE '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span class=\"version-color\">' || LQ.option3_text || '</span>' END) AS option3_text,                             
								Q.option4_text || (CASE WHEN COALESCE(LQ.option4_text, '') = '' OR COALESCE(Q.option4_text, '') = COALESCE(LQ.option4_text, '') THEN '' ELSE '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span class=\"version-color\">' || LQ.option4_text || '</span>' END) AS option4_text,                             
								Q.option5_text || (CASE WHEN COALESCE(LQ.option5_text, '') = '' OR COALESCE(Q.option5_text, '') = COALESCE(LQ.option5_text, '') THEN '' ELSE '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span class=\"version-color\">' || LQ.option5_text || '</span>' END) AS option5_text, 
								Q.correct_option,
								Q.marks,
								Q.reverse_values,
								CASE 
                                	WHEN TRIM(COALESCE(Q.question_img, '')) = '' THEN ''
                                	ELSE '$QuestionImageFolderUrl' || Q.question_img
                                END AS question_img,
                                CASE 
                                	WHEN TRIM(COALESCE(Q.option1_img, '')) = '' THEN ''
                                	ELSE '$QuestionImageFolderUrl' || Q.option1_img
                                END AS option1_img,
                                CASE 
                                	WHEN TRIM(COALESCE(Q.option2_img, '')) = '' THEN ''
                                	ELSE '$QuestionImageFolderUrl' || Q.option2_img
                                END AS option2_img,
                                CASE 
                                	WHEN TRIM(COALESCE(Q.option3_img, '')) = '' THEN ''
                                	ELSE '$QuestionImageFolderUrl' || Q.option3_img
                                END AS option3_img,
                                CASE 
                                	WHEN TRIM(COALESCE(Q.option4_img, '')) = '' THEN ''
                                	ELSE '$QuestionImageFolderUrl' || Q.option4_img
                                END AS option4_img,
                                CASE 
                                	WHEN TRIM(COALESCE(Q.option5_img, '')) = '' THEN ''
                                	ELSE '$QuestionImageFolderUrl' || Q.option5_img
                                END AS option5_img
					 FROM		content.vw_questions AS Q
                     LEFT JOIN  content.language_questions AS LQ ON LQ.question_id = Q.question_id AND LQ.language_id = $LanguageId
					 WHERE		Q.question_paper_id = $question_paper_id
					 AND 		Q.section_id = $section_id
					 ORDER BY	Q.question_sno";

		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}
	//END: QUESTION PAPERS- By George

	//BEGIN: SAMPLE QUESTION PAPER- By George
	function get_sample_question_paper_detail()
	{
		$ResponseData = array();
		$strQuery = "SELECT	COUNT(id) AS question_count
					  FROM	content.sample_questions";
		$QuestionCount = $this->db->query($strQuery)->row()->question_count;
		return $QuestionCount;
	}

	function get_preview_sample_question_paper_data($LanguageId = 1)
	{
		$QuestionImageFolderUrl = base_url(SAMPLE_QUESTION_IMAGES);
		if ($LanguageId < 2) $LanguageId = -111;

		$strQuery = "SELECT 	Q.question_sno,
								Q.question_id,
								Q.part_id,
								Q.part_code,
								Q.part_name,
								Q.section_id,
								Q.section_code,
								Q.section_name,
                                Q.question_text || (CASE WHEN COALESCE(LQ.question_text, '') = '' OR COALESCE(Q.question_text, '') = COALESCE(LQ.question_text, '') THEN '' ELSE ' <br> <span class=\"version-color\">' || LQ.question_text || '</span>' END) AS question_text,                             
								Q.question_type_id,
								Q.question_type_name,
								Q.option1_text || (CASE WHEN COALESCE(LQ.option1_text, '') = '' OR COALESCE(Q.option1_text, '') = COALESCE(LQ.option1_text, '') THEN '' ELSE '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span class=\"version-color\">' || LQ.option1_text || '</span>' END) AS option1_text,                             
								Q.option2_text || (CASE WHEN COALESCE(LQ.option2_text, '') = '' OR COALESCE(Q.option2_text, '') = COALESCE(LQ.option2_text, '') THEN '' ELSE '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span class=\"version-color\">' || LQ.option2_text || '</span>' END) AS option2_text,                             
								Q.option3_text || (CASE WHEN COALESCE(LQ.option3_text, '') = '' OR COALESCE(Q.option3_text, '') = COALESCE(LQ.option3_text, '') THEN '' ELSE '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span class=\"version-color\">' || LQ.option3_text || '</span>' END) AS option3_text,                             
								Q.option4_text || (CASE WHEN COALESCE(LQ.option4_text, '') = '' OR COALESCE(Q.option4_text, '') = COALESCE(LQ.option4_text, '') THEN '' ELSE '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span class=\"version-color\">' || LQ.option4_text || '</span>' END) AS option4_text,                             
								Q.option5_text || (CASE WHEN COALESCE(LQ.option5_text, '') = '' OR COALESCE(Q.option5_text, '') = COALESCE(LQ.option5_text, '') THEN '' ELSE '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span class=\"version-color\">' || LQ.option5_text || '</span>' END) AS option5_text, 
								Q.correct_option,
								Q.marks,
								Q.reverse_values,
								CASE 
                                	WHEN TRIM(COALESCE(Q.question_img, '')) = '' THEN ''
                                	ELSE '$QuestionImageFolderUrl' || Q.question_img
                                END AS question_img,
                                CASE 
                                	WHEN TRIM(COALESCE(Q.option1_img, '')) = '' THEN ''
                                	ELSE '$QuestionImageFolderUrl' || Q.option1_img
                                END AS option1_img,
                                CASE 
                                	WHEN TRIM(COALESCE(Q.option2_img, '')) = '' THEN ''
                                	ELSE '$QuestionImageFolderUrl' || Q.option2_img
                                END AS option2_img,
                                CASE 
                                	WHEN TRIM(COALESCE(Q.option3_img, '')) = '' THEN ''
                                	ELSE '$QuestionImageFolderUrl' || Q.option3_img
                                END AS option3_img,
                                CASE 
                                	WHEN TRIM(COALESCE(Q.option4_img, '')) = '' THEN ''
                                	ELSE '$QuestionImageFolderUrl' || Q.option4_img
                                END AS option4_img,
                                CASE 
                                	WHEN TRIM(COALESCE(Q.option5_img, '')) = '' THEN ''
                                	ELSE '$QuestionImageFolderUrl' || Q.option5_img
                                END AS option5_img
					 FROM		content.vw_sample_questions AS Q
                     LEFT JOIN  content.sample_language_questions AS LQ ON LQ.question_id = Q.question_id AND LQ.language_id = $LanguageId
					 ORDER BY	Q.question_sno";

		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_sample_question_data($PageRequestData = array())
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$strOrderBy = "";
		$SearchCondition = "";
		$Data = array();

		$arrColumnsToBeSearched = array("Q.question_text", "Q.question_type_name", "Q.section_name", "Q.part_name", "Q.option1_text", "Q.option2_text", "Q.option3_text", "Q.option4_text", "Q.option5_text", "Q.correct_option");
		$arrSortByColumns = array(
			0 => null,
			1 => 'Q.question_text',
			2 => 'Q.question_type_name',
			3 => 'Q.section_name',
			4 => 'Q.part_name',
			5 => 'Q.option1_text',
			6 => 'Q.option2_text',
			7 => 'Q.option3_text',
			8 => 'Q.option4_text',
			9 => 'Q.option5_text',
			10 => 'Q.correct_option',
			11 => null
		);

		$strCondition = "";

		$strQuery = "SELECT COUNT(Q.question_id)::INTEGER AS total_record_count 
					 FROM 	content.vw_sample_questions AS Q ";

		$strQuery .= $strCondition;

		$strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
		$intTotalRecordCount = $strTotalRecordCount * 1;

		$intTotalFilteredCount = $intTotalRecordCount;

		$strOrderBy = " ORDER BY Q.question_id  ";

		if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '')
			$strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";

		$StartIndex = $PageRequestData['start'];
		$PageLength = $PageRequestData['length'];
		if ($PageLength < 0) $PageLength = 'all';

		if (!$intTotalRecordCount)
			return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
		else
		{
			$SearchCondition = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '')
			{
				$SearchCondition = " AND (";
				for ($i = 0; $i < count($arrColumnsToBeSearched); $i++)
					$SearchCondition .= $arrColumnsToBeSearched[$i] . " LIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";

				$SearchCondition = substr_replace($SearchCondition, "", -3);
				$SearchCondition .= ')';
			}

			$strQuery = "SELECT COUNT(Q.question_id)::INTEGER AS total_filtered_count 
						 FROM	content.vw_sample_questions AS Q ";
			$strQuery .= $strCondition;
			$strQuery .= $SearchCondition;
			$intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

			$strQuery = "SELECT 	row_number() OVER(ORDER BY Q.question_id) AS question_sno,
									Q.question_id,
									Q.question_text,
									Q.part_name,
									Q.section_name,
									Q.question_type_name,
									Q.option1_text,
									Q.option2_text,
									Q.option3_text,
									Q.option4_text,
									Q.option5_text,
									Q.correct_option
						 FROM		content.vw_sample_questions AS Q ";
			$strQuery .= $strCondition;
			$strQuery .= $SearchCondition;
			$strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

			$QueryData = $this->db->query($strQuery);

			$SerialNumber = $StartIndex;
			$intActiveStatus = 1;
			$QuestionPaperTitle = "";
			$Actions = "";
			foreach ($QueryData->result() as $QueryRow)
			{
				$ResponseRow = array();
				$SerialNumber++;
				$ResponseRow[] = "";

				$Actions = '<a class="btn btn-sm btn-primary" title="Edit Sample Question" href="javascript:void(0);" onclick="ShowSampleQuestionEntry(' . $QueryRow->question_id . ')" style="margin-right:2px;"><i class="icon-compose"></i></a>';
				$Actions .= '<a class="btn btn-sm btn-primary" title="Add or Edit Sample Question Language Version" href="javascript:void(0);" onclick="AddEditSampleQuestionLanguageVersions(' . $QueryRow->question_id . ')" style="margin-right:2px;"><i class="icon-ios-browsers"></i></a>';

				$ResponseRow[] = $Actions;
				$ResponseRow[] = '<center>' . $QueryRow->question_sno . '</center>';
				$ResponseRow[] = $QueryRow->question_text;
				$ResponseRow[] = $QueryRow->question_type_name;
				$ResponseRow[] = $QueryRow->section_name;
				$ResponseRow[] = $QueryRow->part_name;
				$ResponseRow[] = $QueryRow->option1_text;
				$ResponseRow[] = $QueryRow->option2_text;
				$ResponseRow[] = $QueryRow->option3_text;
				$ResponseRow[] = $QueryRow->option4_text;
				$ResponseRow[] = $QueryRow->option5_text;
				$ResponseRow[] = $QueryRow->correct_option;
				$Data[] = $ResponseRow;
			}

			$ResponseData = array(
				"draw" 					=> intval($PageRequestData['draw']),
				"recordsTotal" 			=> intval($intTotalRecordCount),
				"recordsFiltered" 		=> intval($intTotalFilteredCount),
				"data" 					=> $Data
			);

			return $ResponseData;
		}
	}

	function get_sample_section_list_for_question_id($question_id = 0)
	{
		$strQuery = "SELECT 	S.code AS section_code,
								S.name AS section_name
					 FROM		content.sections AS S
					 INNER JOIN	content.sample_questions AS Q ON Q.part_id = S.part_id
					 WHERE		Q.id = $question_id
					 ORDER BY	S.sort_order";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_sample_question_detail($question_id = 0)
	{
		$ResponseData = array();
		if ($question_id > 0)
		{
			$strQuery = "SELECT  	*
						 FROM    	content.vw_sample_questions
						 WHERE		question_id = $question_id";
			$ResponseData = $this->db->query($strQuery)->result_array();
		}

		return $ResponseData;
	}

	function SaveSampleQuestionData($SaveRequestData)
	{
		$strQuery = "SELECT * FROM content.fn_save_sample_question(";
		$strQuery .= $SaveRequestData['question_id'] . ",";
		$strQuery .= $SaveRequestData['part_id'] . ",";
		$strQuery .= "'" . $SaveRequestData['section_code'] . "',";
		$strQuery .= $SaveRequestData['question_type_id'] . ",";
		$strQuery .= "'" . $SaveRequestData['question_text'] . "',";
		$strQuery .= "'" . $SaveRequestData['option1_text'] . "',";
		$strQuery .= "'" . $SaveRequestData['option2_text'] . "',";
		$strQuery .= "'" . $SaveRequestData['option3_text'] . "',";
		$strQuery .= "'" . $SaveRequestData['option4_text'] . "',";
		$strQuery .= "'" . $SaveRequestData['option5_text'] . "',";
		$strQuery .= $SaveRequestData['correct_option'] . ",";
		$strQuery .= $SaveRequestData['marks'] . ",";
		$strQuery .= $SaveRequestData['reverse_values'] . ",";
		$strQuery .= "'" . $SaveRequestData['question_img'] . "',";
		$strQuery .= "'" . $SaveRequestData['option1_img'] . "',";
		$strQuery .= "'" . $SaveRequestData['option2_img'] . "',";
		$strQuery .= "'" . $SaveRequestData['option3_img'] . "',";
		$strQuery .= "'" . $SaveRequestData['option4_img'] . "',";
		$strQuery .= "'" . $SaveRequestData['option5_img'] . "')";

		$ResponseData = $this->db->query($strQuery)->result_array();

		return $ResponseData;
	}

	function get_language_version_sample_question_data($LanguageId = 0)
	{
		$strQuery = "SELECT * FROM content.vw_sample_question_language_versions WHERE language_id IN (1,$LanguageId)";
		$ResponseData = $this->db->query($strQuery)->result_array();

		return $ResponseData;
	}

	function upload_sample_question_paper_data($PageRequestData)
	{
		$Parameters = array(
			$PageRequestData['PartCodeList'],
			$PageRequestData['SectionCodeList'],
			$PageRequestData['QuestionTextList'],
			$PageRequestData['QuestionTypeList'],
			$PageRequestData['Option1TextList'],
			$PageRequestData['Option2TextList'],
			$PageRequestData['Option3TextList'],
			$PageRequestData['Option4TextList'],
			$PageRequestData['Option5TextList'],
			$PageRequestData['CorrectOptionList'],
			$PageRequestData['MarkList'],
			$PageRequestData['ReverseValueList'],
			$PageRequestData['QuestionImageList'],
			$PageRequestData['Option1ImageList'],
			$PageRequestData['Option2ImageList'],
			$PageRequestData['Option3ImageList'],
			$PageRequestData['Option4ImageList'],
			$PageRequestData['Option5ImageList'],
			$PageRequestData['user_id']
		);

		$strQuery = "SELECT * FROM content.fn_upload_sample_question_paper_data(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$ResponseData = $this->db->query($strQuery, $Parameters)->result_array();

		return $ResponseData;
	}

	function upload_sample_question_paper_version_data($PageRequestData)
	{
		$Parameters = array(
			$PageRequestData['QuestionIdList'],
			$PageRequestData['RowIndexList'],
			$PageRequestData['LanguageNameList'],
			$PageRequestData['QuestionTextList'],
			$PageRequestData['Option1TextList'],
			$PageRequestData['Option2TextList'],
			$PageRequestData['Option3TextList'],
			$PageRequestData['Option4TextList'],
			$PageRequestData['Option5TextList'],
			$PageRequestData['user_id']
		);

		$strQuery = "SELECT * FROM content.fn_upload_sample_question_paper_version_data(?,?,?,?,?,?,?,?,?,?)";
		$ResponseMessage = $this->db->query($strQuery, $Parameters)->row()->fn_upload_sample_question_paper_version_data;

		return $ResponseMessage;
	}

	function get_section_list_for_sample_question_id($question_id = 0)
	{
		$strQuery = "SELECT 	S.code AS section_code,
								S.name AS section_name
					 FROM		content.sections AS S
					 INNER JOIN	content.sample_questions AS Q ON Q.part_id = S.part_id
					 WHERE		Q.id = $question_id
					 ORDER BY	S.sort_order";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function save_sample_question_language_version_data($RequestData = array())
	{
		$ResponseData = array();

		$ReqData = array(
			0 => $RequestData['question_id'],
			1 => $RequestData['language_code'],
			2 => str_replace("\n", "<br>", $RequestData['question']),
			3 => str_replace("\n", "<br>", $RequestData['option1']),
			4 => str_replace("\n", "<br>", $RequestData['option2']),
			5 => str_replace("\n", "<br>", $RequestData['option3']),
			6 => str_replace("\n", "<br>", $RequestData['option4']),
			7 => str_replace("\n", "<br>", $RequestData['option5'])
		);

		$strQuery = "SELECT * FROM content.fn_save_sample_question_language_version_data(?,?,?,?,?,?,?,?)";
		$ResponseData = $this->db->query($strQuery, $ReqData)->row()->fn_save_sample_question_language_version_data;
		if (!is_null($ResponseData))
		{
			$ResponseData = array(
				"language_question_id" => $ResponseData
			);
		}

		return $ResponseData;
	}

	public function get_sample_question_detail_for_language($QuestionId = 0, $LanguageCode = '')
	{
		$LanguageCode = strtoupper($LanguageCode);
		$ResponseData = array();
		if ($QuestionId > 0)
		{
			$strQuery = "SELECT 	LQ.question_id,
									REPLACE(LQ.question_text, '<br>', '\n') AS question_text,
									REPLACE(LQ.option1_text, '<br>', '\n') AS option1_text,
									REPLACE(LQ.option2_text, '<br>', '\n') AS option2_text,
									REPLACE(LQ.option3_text, '<br>', '\n') AS option3_text,
									REPLACE(LQ.option4_text, '<br>', '\n') AS option4_text,
									REPLACE(LQ.option5_text, '<br>', '\n') AS option5_text
						FROM 		content.sample_language_questions AS LQ 
						LEFT JOIN 	master.languages AS L ON L.id = LQ.language_id
						WHERE 		LQ.question_id = $QuestionId
						AND 		UPPER(L.code) = '$LanguageCode'";
			$ResponseData = $this->db->query($strQuery)->result_array();
		}

		return $ResponseData;
	}
	//END: SAMPLE QUESTION PAPER- By George

	//BEGIN: QUESTIONS - By George
	function get_question_paper_title($QuestionPaperId = 0)
	{
		$strQuery = "SELECT QP.question_paper_title 
					 FROM 	content.question_papers AS QP
					 WHERE	QP.id = $QuestionPaperId";
		$QuestionPaperTitle = $this->db->query($strQuery)->row()->question_paper_title;
		return $QuestionPaperTitle;
	}

	function get_question_data($PageRequestData = array(), $QuestionPaperId = 0)
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$strOrderBy = "";
		$SearchCondition = "";
		$Data = array();

		$arrColumnsToBeSearched = array("Q.question_text", "Q.question_type_name", "Q.section_name", "Q.part_name", "Q.option1_text", "Q.option2_text", "Q.option3_text", "Q.option4_text", "Q.option5_text", "Q.correct_option");
		$arrSortByColumns = array(
			0 => null,
			1 => null,
			2 => 'Q.question_id',
			2 => 'Q.question_text',
			3 => 'Q.question_type_name',
			4 => 'Q.section_name',
			5 => 'Q.part_name',
			6 => 'Q.option1_text',
			7 => 'Q.option2_text',
			8 => 'Q.option3_text',
			9 => 'Q.option4_text',
			10 => 'Q.option5_text',
			11 => 'Q.correct_option',
			12 => null
		);

		$strCondition = "";

		$strQuery = "SELECT COUNT(Q.question_id)::INTEGER AS total_record_count 
					 FROM 	content.vw_questions AS Q
					 WHERE  Q.question_paper_id = $QuestionPaperId ";

		$strQuery .= $strCondition;

		$strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
		$intTotalRecordCount = $strTotalRecordCount * 1;

		$intTotalFilteredCount = $intTotalRecordCount;

		$strOrderBy = " ORDER BY Q.question_id  ";

		if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '')
			$strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";

		$StartIndex = $PageRequestData['start'];
		$PageLength = $PageRequestData['length'];
		if ($PageLength < 0) $PageLength = 'all';

		if (!$intTotalRecordCount)
			return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
		else
		{
			$SearchCondition = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '')
			{
				$SearchCondition = " AND (";
				for ($i = 0; $i < count($arrColumnsToBeSearched); $i++)
					$SearchCondition .= $arrColumnsToBeSearched[$i] . " LIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";

				$SearchCondition = substr_replace($SearchCondition, "", -3);
				$SearchCondition .= ')';
			}

			$strQuery = "SELECT COUNT(Q.question_id)::INTEGER AS total_filtered_count 
						 FROM	content.vw_questions AS Q
						 WHERE  Q.question_paper_id = $QuestionPaperId ";
			$strQuery .= $strCondition;
			$strQuery .= $SearchCondition;
			$intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

			$strQuery = "SELECT 	row_number() OVER(ORDER BY Q.question_id) AS question_sno,
									Q.question_id,
									Q.question_paper_id,
									Q.question_text,
									Q.part_name,
									Q.section_name,
									Q.question_type_name,
									Q.option1_text,
									Q.option2_text,
									Q.option3_text,
									Q.option4_text,
									Q.option5_text,
									Q.correct_option
						 FROM		content.vw_questions AS Q
						 WHERE  	Q.question_paper_id = $QuestionPaperId ";
			$strQuery .= $strCondition;
			$strQuery .= $SearchCondition;
			$strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

			$QueryData = $this->db->query($strQuery);

			$SerialNumber = $StartIndex;
			$intActiveStatus = 1;
			$QuestionPaperTitle = "";
			$Actions = "";
			foreach ($QueryData->result() as $QueryRow)
			{
				$ResponseRow = array();
				$SerialNumber++;
				$ResponseRow[] = "";

				$Actions = '<a class="btn btn-sm btn-primary" title="Edit Question" href="javascript:void(0);" onclick="ShowQuestionEntry(' . $QueryRow->question_paper_id . ',' . $QueryRow->question_id . ')" style="margin-right:2px;"><i class="icon-compose"></i></a>';
				$Actions .= '<a class="btn btn-sm btn-primary" title="Add or Edit Question Language Version" href="javascript:void(0);" onclick="AddEditQuestionLanguageVersions(' . $QueryRow->question_paper_id . ',' . $QueryRow->question_id . ')" style="margin-right:2px;"><i class="icon-ios-browsers"></i></a>';

				$ResponseRow[] = $Actions;
				$ResponseRow[] = '<center>' . $QueryRow->question_sno . '</center>';
				//$ResponseRow[] = $QueryRow->question_id;
				$ResponseRow[] = $QueryRow->question_text;
				$ResponseRow[] = $QueryRow->question_type_name;
				$ResponseRow[] = $QueryRow->section_name;
				$ResponseRow[] = $QueryRow->part_name;
				$ResponseRow[] = $QueryRow->option1_text;
				$ResponseRow[] = $QueryRow->option2_text;
				$ResponseRow[] = $QueryRow->option3_text;
				$ResponseRow[] = $QueryRow->option4_text;
				$ResponseRow[] = $QueryRow->option5_text;
				$ResponseRow[] = $QueryRow->correct_option;
				$Data[] = $ResponseRow;
			}

			$ResponseData = array(
				"draw" 					=> intval($PageRequestData['draw']),
				"recordsTotal" 			=> intval($intTotalRecordCount),
				"recordsFiltered" 		=> intval($intTotalFilteredCount),
				"data" 					=> $Data
			);

			return $ResponseData;
		}
	}

	function get_question_detail($question_id = 0)
	{
		$ResponseData = array();
		if ($question_id > 0)
		{
			$strQuery = "SELECT  	*
						 FROM    	content.vw_questions
						 WHERE		question_id = $question_id";
			$ResponseData = $this->db->query($strQuery)->result_array();
		}

		return $ResponseData;
	}

	public function get_question_detail_for_language($QuestionId = 0, $LanguageCode = '')
	{
		$LanguageCode = strtoupper($LanguageCode);
		$ResponseData = array();
		if ($QuestionId > 0)
		{
			$strQuery = "SELECT 	LQ.question_id,
									REPLACE(LQ.question_text, '<br>', '\n') AS question_text,
									REPLACE(LQ.option1_text, '<br>', '\n') AS option1_text,
									REPLACE(LQ.option2_text, '<br>', '\n') AS option2_text,
									REPLACE(LQ.option3_text, '<br>', '\n') AS option3_text,
									REPLACE(LQ.option4_text, '<br>', '\n') AS option4_text,
									REPLACE(LQ.option5_text, '<br>', '\n') AS option5_text
						FROM 		content.language_questions AS LQ 
						LEFT JOIN 	master.languages AS L ON L.id = LQ.language_id
						WHERE 		LQ.question_id = $QuestionId
						AND 		UPPER(L.code) = '$LanguageCode'";
			$ResponseData = $this->db->query($strQuery)->result_array();
		}

		return $ResponseData;
	}

	function get_language_detail_for_id($LanguageId = 0)
	{
		$strQuery = "SELECT * FROM master.languages WHERE id = $LanguageId";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function save_question_language_version_data($RequestData = array())
	{
		$ResponseData = array();

		$ReqData = array(
			0 => $RequestData['question_id'],
			1 => $RequestData['language_code'],
			2 => str_replace("\n", "<br>", $RequestData['question']),
			3 => str_replace("\n", "<br>", $RequestData['option1']),
			4 => str_replace("\n", "<br>", $RequestData['option2']),
			5 => str_replace("\n", "<br>", $RequestData['option3']),
			6 => str_replace("\n", "<br>", $RequestData['option4']),
			7 => str_replace("\n", "<br>", $RequestData['option5'])
		);

		$strQuery = "SELECT * FROM content.fn_save_question_language_version_data(?,?,?,?,?,?,?,?)";
		$ResponseData = $this->db->query($strQuery, $ReqData)->row()->fn_save_question_language_version_data;
		if (!is_null($ResponseData))
		{
			$ResponseData = array(
				"language_question_id" => $ResponseData
			);
		}

		return $ResponseData;
	}

	function get_part_list()
	{
		$strQuery = "SELECT  	id AS part_id,
								code AS part_code,
								name AS part_name
					 FROM    	content.parts
					 ORDER BY	id";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_section_list_for_question_id($question_id = 0)
	{
		$strQuery = "SELECT 	S.code AS section_code,
								S.name AS section_name
					 FROM		content.sections AS S
					 INNER JOIN	content.questions AS Q ON Q.part_id = S.part_id
					 WHERE		Q.id = $question_id
					 ORDER BY	S.name";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_section_list_for_part_id($part_id = 0)
	{
		$strQuery = "SELECT 	S.id AS section_id,
								S.code AS section_code,
								S.name AS section_name
					 FROM		content.sections AS S
					 WHERE		S.part_id = $part_id
					 ORDER BY	S.name";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_question_type_list()
	{
		$strQuery = "SELECT  	id AS question_type_id,
								code AS question_type_code,
								name AS question_type_name
					 FROM    	content.question_types
					 ORDER BY	id";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function SaveQuestionData($SaveRequestData)
	{
		$strQuery = "SELECT * FROM content.fn_save_question(";
		$strQuery .= $SaveRequestData['question_id'] . ",";
		$strQuery .= $SaveRequestData['question_paper_id'] . ",";
		$strQuery .= $SaveRequestData['part_id'] . ",";
		$strQuery .= "'" . $SaveRequestData['section_code'] . "',";
		$strQuery .= $SaveRequestData['question_type_id'] . ",";
		$strQuery .= "'" . $SaveRequestData['question_text'] . "',";
		$strQuery .= "'" . $SaveRequestData['option1_text'] . "',";
		$strQuery .= "'" . $SaveRequestData['option2_text'] . "',";
		$strQuery .= "'" . $SaveRequestData['option3_text'] . "',";
		$strQuery .= "'" . $SaveRequestData['option4_text'] . "',";
		$strQuery .= "'" . $SaveRequestData['option5_text'] . "',";
		$strQuery .= $SaveRequestData['correct_option'] . ",";
		$strQuery .= $SaveRequestData['marks'] . ",";
		$strQuery .= $SaveRequestData['reverse_values'] . ",";
		$strQuery .= "'" . $SaveRequestData['question_img'] . "',";
		$strQuery .= "'" . $SaveRequestData['option1_img'] . "',";
		$strQuery .= "'" . $SaveRequestData['option2_img'] . "',";
		$strQuery .= "'" . $SaveRequestData['option3_img'] . "',";
		$strQuery .= "'" . $SaveRequestData['option4_img'] . "',";
		$strQuery .= "'" . $SaveRequestData['option5_img'] . "')";

		$ResponseData = $this->db->query($strQuery)->result_array();

		return $ResponseData;
	}

	function get_candidate_questions($CandidateId = 0, $LanguageId = 0)
	{
		$strQuery = "SELECT * FROM content.fn_get_candidate_questions($CandidateId, $LanguageId)";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_instructions($part_id = -1, $language_id = 1)
	{
		$RequestData = array(
			1 => $part_id,
			2 => $language_id
		);

		$strQuery = "SELECT * FROM content.fn_get_instructions(?, ?)";
		$ResponseData = $this->db->query($strQuery, $RequestData)->result_array();
		return $ResponseData;
	}

	function get_assessment_instructions($assessment_type_id = -1, $part_id = -1, $language_id = 1)
	{
		$RequestData = array(
			1 => $assessment_type_id,
			2 => $part_id,
			3 => $language_id
		);

		$strQuery = "SELECT * FROM content.fn_get_assessment_instructions(?, ?, ?)";
		$ResponseData = $this->db->query($strQuery, $RequestData)->result_array();
		return $ResponseData;
	}

	function get_sample_instructions($language_id = 1)
	{
		$RequestData = array(
			1 => $language_id
		);

		$strQuery = "SELECT * FROM content.fn_get_sample_instructions(?)";
		$ResponseData = $this->db->query($strQuery, $RequestData)->result_array();
		return $ResponseData;
	}

	function get_sample_questions($LanuageId)
	{
		$ResponseData  = array();
		$Parameters = array( 1 => $LanuageId );

		$strQuery = "SELECT * FROM content.fn_get_sample_questions(?) ORDER BY question_sno";
		$ResponseData = $this->db->query($strQuery, $Parameters)->result_array();
		return $ResponseData;
	}

	function get_resource_list($LanguageId = 1, $ResourceCode = '')
	{
		$ResponseData  = array();
		$Parameters = array( 1 => $LanguageId, 2 => $ResourceCode);

		$strQuery = "SELECT * FROM content.fn_get_text_resources(?, ?)";
		$ResponseData = $this->db->query($strQuery, $Parameters)->result_array();
		return $ResponseData;
	}
	//END: QUESTIONS - By George

	//BEGIN: INSTRUCTIONS - By George
	function get_instruction_part_list()
	{
		$ResponseData  = array();
		$strQuery = "SELECT 	id AS part_id, 
								code AS part_code,
								(name || ' Instructions') AS part_name 
					 FROM 		content.parts 
					 ORDER BY 	id";
		$ResponseData = $this->db->query($strQuery)->result_array();
		$ResponseData[] = array(
			"part_id" => "-1",
			"part_code" => "S",
			"part_name" => "Sample Instructions"
		);
		return $ResponseData;
	}

	function get_assessment_type_list()
	{
		$ResponseData  = array();
		$strQuery = "SELECT  	id AS assessment_type_id,
								name AS assessment_type_name
					FROM		assessment.assessment_types
					ORDER BY	id;";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_instruction_list_data($AssessmentTypeId = 1, $PartId = 0, $LanguageId = 1)
	{
		$ResponseData = array();
		$strQuery     = "SELECT * FROM content.fn_get_instruction_data($AssessmentTypeId, $PartId, $LanguageId)";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function delete_instruction($RequestArray = array())
	{
		$strQuery = "SELECT * FROM content.fn_delete_instruction(?, ?)";
		$ResponseData = $this->db->query($strQuery, $RequestArray)->row()->fn_delete_instruction;
		return $ResponseData;
	}

	function add_instruction($RequestArray = array())
	{
		$strQuery = "SELECT * FROM content.fn_add_instruction(?, ?, ?, ?)";
		$ResponseData = $this->db->query($strQuery, $RequestArray)->row()->fn_add_instruction;
		return $ResponseData;
	}

	function save_instruction($RequestArray = array())
	{
		$InstructionSno = "Instruction " . $RequestArray['sno'] . ":";
		$ResponseData = array(
			"english_status"  => "$InstructionSno English Instruction could not be updated!",
			"language_status" => "$InstructionSno Language Version Instruction could not be updated!",
		);

		$strQuery = "SELECT * FROM content.fn_save_instruction(?, ?, ?, ?, ?, ?)";
		$Data = $this->db->query($strQuery, $RequestArray)->result_array();
		if (count($Data) > 0)
		{
			$ResponseData = array(
				"english_status"  => $InstructionSno . " " . $Data[0]['english_status'],
				"language_status" => $InstructionSno . " " . $Data[0]['language_status']
			);
		}

		return $ResponseData;
	}

	public function change_instruction_position($PartId = -111, $PositionFrom = -1, $PositionTo = -1)
	{
		$ResponseData = array(
			"status"  => -1,
			"message" => "Could not change instruction position!",
		);

		$RequestArray = array(
			"part_id" 			=> (isset($_REQUEST['part_id']) ?  $_REQUEST['part_id'] : $PartId),
			"position_from"		=> (isset($_REQUEST['position_from']) ?  $_REQUEST['position_from'] : $PositionFrom),
			"position_to"		=> (isset($_REQUEST['position_to']) ?  $_REQUEST['position_to'] : $PositionTo)
		);

		$strQuery = "SELECT * FROM content.fn_change_instruction_position(?, ?, ?)";
		$Data = $this->db->query($strQuery, $RequestArray)->result_array();

		if (count($Data) > 0)
		{
			$ResponseData = array(
				"status"  => $Data[0]['status'],
				"message" => $Data[0]['message']
			);
		}

		return $ResponseData;
	}
	//END: INSTRUCTIONS - By George

	public function do_get_candidate_sample_questions($LanguageId = 1)
	{
		$strQuery = "SELECT * FROM content.fn_get_sample_questions($LanguageId)";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	public function get_candidate_sample_questions($LanguageId = 1)
	{
		$strQuery = "SELECT * FROM content.fn_get_sample_questions($LanguageId)";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}
}
