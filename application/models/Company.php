<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends MY_Model
{
    protected $tableName = 'neo_customer.companies';

    private $current_user_id, $current_timestamp;

    public $fillable = ['company_name', 'lead_type_id', 'lead_source_id', 'company_description',
                        'industry_id', 'functional_area_id', 'hr_name', 'hr_email', 'hr_phone', 'hr_designation',
                        'landline', 'fax_number', 'skype_id', 'annual_revenue', 'website','target_employers', 'remarks',
                        'created_at', 'created_by', 'updated_at', 'updated_by'];

    public function __construct() {
      parent::__construct();
      $this->current_user_id = $this->session->userdata('usr_authdet')['id'];
      $this->current_timestamp = date('Y-m-d H:i:s');
      $this->load->model('Location', 'location');
      $this->load->database();
    }

    public function find($company_id) {
      return $this->db->where('id', $company_id)->get($this->tableName)->row_array();
    }

    public function save($data) {
      $data = $this->makeTimeStamp($data, 'save');
      $this->db->trans_start();
      $this->db->insert($this->tableName, $this->getFillable($data));
      $this->createOrUpdateLocation($this->db->insert_id(), $data, 'save');
      $this->db->trans_complete();

      return $this->db->trans_status();
    }

    public function update($company_id, $data) {
      $data = $this->makeTimeStamp($data);

      $this->db->trans_start();
      $this->db->where('id', $company_id)->update($this->tableName, $this->getFillable($data));
      $this->createOrUpdateLocation($company_id, $data);
      $this->db->trans_complete();

      return $this->db->trans_status();
    }

    private function createOrUpdateLocation($customer_id, $data, $action='update') {
      $data['customer_id'] = $customer_id;
      $data['is_main_branch'] = TRUE;
      $this->db->reset_query();
      if($action=='save') {
          $this->location->save($data);
      } else {
         $this->location->updateRelation($data, ['customer_id'=>$data['customer_id'], 'is_main_branch'=>TRUE]);
      }
    }

    public function findLocation($data) {
      return $this->location->findFirst($data);
    }

    public function makeTimeStamp($data, $action='update') {
      $data['updated_at'] = $this->current_timestamp;
      $data['updated_by'] =$this->current_user_id;
      if($action=='save') {
        $data['created_at'] = $this->current_timestamp;
        $data['created_by'] =$this->current_user_id;
      }
      return $data;
    }

    //////////////////////////////////SUMIT///////////////////////////

    function getCompanyList($PageRequestData = array(),$user_id=0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        //Searching columns
        $arrColumnsToBeSearched = array("company_name", "industry" ,"functional_area","spoc_name", "spoc_email", "spoc_phone", "state", "district", "lead_source" );

        //Sorting columns
        $arrSortByColumns = array(
            0 => null,
            1 => null,
            2 => 'company_name',
            3 => 'industry',
            4 => 'functional_area',
            5 => 'spoc_name',
            6 => 'spoc_email',
            7 => 'spoc_phone',
            8 => 'state',
            9 => 'district',
            10 => 'lead_source'
        );

        //Change query here for total record
        $strQuery = "SELECT COUNT(s.id)::BIGINT AS total_record_count
                     FROM   neo_customer.vw_company_list AS s WHERE TRUE ";
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


            //Change query here for filtered rows
            $strQuery = "SELECT     COUNT(s.id)::BIGINT AS total_filtered_count
                         FROM       neo_customer.vw_company_list AS s
                         WHERE      TRUE ";

            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "select * from neo_customer.vw_company_list AS s WHERE TRUE ";
            //Main Query here for fetching details

            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            //$intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
              $Actions = '';                  
                  if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_update_roles())) {
                    $Actions .= '<a class="btn btn-sm btn-danger" title="Edit Lead" onclick="edit_lead(' . $QueryRow->id . ')"  style="margin-left: 2px;color:white;"><i class="icon-android-create"></i></a>';
                  }
                  $Actions .= '<a class="btn btn-sm btn-success" title="Create Opportunity" onclick="create_opportunity(' . $QueryRow->id . ')"  style="margin-left: 2px;"><i class="fa fa-magic"></i></a>';
                  
                  
                  
               // $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $Actions;
                $ResponseRow[] = $QueryRow->company_name ?? 'N/A';
                $ResponseRow[] = $QueryRow->opportunity_count ?? 'N/A';
                $ResponseRow[] = $QueryRow->company_description ?? 'N/A';
                $ResponseRow[] = $QueryRow->industry ?? 'N/A';
                $ResponseRow[] = $QueryRow->functional_area ?? 'N/A';                
                $ResponseRow[] = $QueryRow->spoc_name ?? 'N/A';
                $ResponseRow[] = $QueryRow->spoc_email ?? 'N/A';                
                $ResponseRow[] = $QueryRow->spoc_phone ?? 'N/A';
                $ResponseRow[] = $QueryRow->state ?? 'N/A';
                $ResponseRow[] = $QueryRow->district ?? 'N/A';                
                $ResponseRow[] = $QueryRow->lead_source ?? 'N/A';
                $ResponseRow[] = $QueryRow->remarks ?? 'N/A';
                
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

}
