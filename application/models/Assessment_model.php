<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model :: Assessment Model
 * @author george.s@navriti.com
**/
class Assessment_model extends CI_Model
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

		$arrColumnsToBeSearched = array("QP.question_paper_title", "QPT.name", "QP.version_number");
		$arrSortByColumns = array(
			0 => null,
			1 => 'QP.question_paper_title',
			2 => 'QPT.name',
			3 => 'QP.version_number',
			4 => null,
			5 => null
		);

		$strCondition = "";

		$strQuery = "SELECT COUNT(QP.id)::INTEGER AS total_record_count 
					 FROM   assessments.question_papers AS QP
					 WHERE	TRUE";

		$strQuery .= $strCondition;

		$strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
		$intTotalRecordCount = $strTotalRecordCount * 1;

		$intTotalFilteredCount = $intTotalRecordCount;

		if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
			$strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
		}

		$StartIndex = $PageRequestData['start'];
		$PageLength = $PageRequestData['length'];
		if ($PageLength < 0) $PageLength = 'all';

		if (!$intTotalRecordCount) {
			return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
		} else {
			$SearchCondition = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '') {
				$SearchCondition = " AND (";
				for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
					$SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
				}

				$SearchCondition = substr_replace($SearchCondition, "", -3);
				$SearchCondition .= ')';
			}

			$strQuery = "SELECT COUNT(QP.id)::INTEGER AS total_filtered_count 
						 FROM   assessments.question_papers AS QP
						 WHERE	TRUE ";

			$strQuery .= $strCondition;
			$strQuery .= $SearchCondition;

			$intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

			$strQuery = "SELECT 	QP.id AS question_paper_id,
									QP.question_paper_title,
									QPT.name AS question_paper_type_name,
									LNG.name AS language_name,
									QP.version_number,
									QP.active_status
						FROM		assessments.question_papers AS QP
						LEFT JOIN	assessments.question_paper_type AS QPT ON QPT.id = QP.question_paper_type_id
						LEFT JOIN	master.list AS LNG ON LNG.code='L0009' AND LNG.value = QP.language_id::TEXT
			          	WHERE		TRUE ";

			$strQuery .= $strCondition;
			$strQuery .= $SearchCondition;
			$strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

			$QueryData = $this->db->query($strQuery);

			$SerialNumber = $StartIndex;
			$intActiveStatus = 1;
			foreach ($QueryData->result() as $QueryRow) {
				$intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
				$ResponseRow = array();
				$SerialNumber++;
				$ResponseRow[] = $SerialNumber;
				$ResponseRow[] = $QueryRow->question_paper_title;
				$ResponseRow[] = $QueryRow->question_paper_type_name;
				$ResponseRow[] = $QueryRow->language_name;
				$ResponseRow[] = $QueryRow->version_number;
				$ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->question_paper_id . "'" . ',' . $intActiveStatus . ')" style="width:100%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';
				$ResponseRow[] = '<a class="btn btn-sm btn-primary" title="Update Question Paper" href="javascript:void(0);" onclick="ShowUploadPopup(' . "'" . $QueryRow->question_paper_id . "'" . ')"><i class="glyphicon glyphicon-upload"></i></a>';
				$Data[] = $ResponseRow;
			}

			$ResponseData = array(
				"draw" => intval($PageRequestData['draw']),
				"recordsTotal" => intval($intTotalRecordCount),
				"recordsFiltered" => intval($intTotalFilteredCount),
				"data" => $Data
			);

			return $ResponseData;
		}
	}
	//END: QUESTION PAPERS- By George
}

