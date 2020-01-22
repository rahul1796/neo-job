<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opportunity extends MY_Model
{
    protected $tableName = 'neo_customer.opportunities';

    private $current_user_id, $current_timestamp;

    public $fillable = ['company_id', 'managed_by', 'lead_status_id', 'business_vertical_id',
                        'functional_area_id', 'industry_id', 'labournet_entity_id', 'opportunity_code', 'contract_id',
                        'created_at', 'created_by', 'updated_at', 'updated_by'];

    public function __construct() {
      parent::__construct();
      $this->current_user_id = $this->session->userdata('usr_authdet')['id'];
      $this->current_timestamp = date('Y-m-d H:i:s');
      $this->load->model('Location', 'location');
    }

    public function find($company_id) {
      return $this->db->where('id', $company_id)->get($this->tableName)->row_array();
    }

    public function save($data) {
      $data = $this->makeTimeStamp($data, 'save');
      $this->db->trans_start();
      $this->db->insert($this->tableName, $this->getFillable($data));
      $data['opportunity_id'] = $this->db->insert_id();
      $this->createOrUpdateLocation($data['opportunity_id'], $data, 'save');
      $data = $this->generateOpportunityCodes($data);
      $this->db->where('id', $data['opportunity_id'])->update($this->tableName, $this->getFillable($data));
      $this->db->trans_complete();

      return $this->db->trans_status();
    }

    public function update($opportunity_id, $data) {
      $data = $this->makeTimeStamp($data);

      $this->db->trans_start();
      $this->db->where('id', $opportunity_id)->update($this->tableName, $this->getFillable($data));
      $this->createOrUpdateLocation($opportunity_id, $data);
      $this->db->trans_complete();

      return $this->db->trans_status();
    }

    private function createOrUpdateLocation($opportunity_id, $data, $action='update') {
      $data['customer_id'] = $data['company_id'];
      $data['opportunity_id'] = $opportunity_id;
      $data['is_main_branch'] = FALSE;
      $this->db->reset_query();
      if($action=='save') {
          $this->location->save($data);
      } else {
         $this->location->updateRelation($data, ['customer_id'=>$data['customer_id'], 'opportunity_id'=>$data['opportunity_id']]);
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

    public function makeCodes($data) {

      return $data;
    }

    //generates contract code as well
    private function generateOpportunityCodes($data) {
      $result = $this->db->select('COM.company_name, BV.code', false)->from($this->tableName)
              ->join('neo_customer.companies AS COM', "COM.id={$this->tableName}.company_id", 'LEFT', FALSE)
              ->join('neo_master.business_verticals AS BV', "BV.id={$this->tableName}.business_vertical_id", 'LEFT', FALSE)
              ->where("{$this->tableName}.id", $data['opportunity_id'])->get()->row_array();
      $data['opportunity_code'] = 'OPP-'.strtoupper(substr($result['company_name'], 0, (strlen($result['company_name'])>3 ? 4 : 3))).'-'.$result['code'].'-'.$data['opportunity_id'];
      $data['contract_id'] = 'CON-'.strtoupper(substr($result['company_name'], 0, (strlen($result['company_name'])>3 ? 4 : 3))).'-'.$data['opportunity_id'];;
      return $data;
    }

    public function updateLeadStatus($data) {
      $this->db->trans_start();
      $this->db->where('id', $data['customer_id']);
      $maindata['lead_status_id'] = $data['lead_status_id'];
      if($data['is_paid']!=-1){
        $maindata['is_paid'] = $data['is_paid'];
        if($data['lead_status_id']==16 && $maindata['is_paid']==0) {
           $maindata['is_contract'] = true;
        }
      }
      unset($data['is_paid']);
      $this->db->update($this->tableName, $maindata);

      $this->db->reset_query();
      $this->createLeadLog($data, $data['remarks']);

      $this->db->trans_complete();
      return $this->db->trans_status();
    }

    public function createLeadLog($data, $remark='') {
      $data['remarks'] = $remark;
      return $this->db->insert('neo_customer.lead_logs', $data);
    }


    //sumit's code


    function getOppurunityList($PageRequestData = array(),$user_id=0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        //Searching columns
        $arrColumnsToBeSearched = array("company_name", "lead_status_name" ,"opportunity_code","contract_id", "business_vertical_name", "industry_name", "labournet_entity_name" );

        //Sorting columns
        $arrSortByColumns = array(
            0 => null,
            1 => null,
            2 => 'company_name',
            3 => 'lead_status_name',
            4 => 'opportunity_code',
            5 => 'contract_id',
            6 => 'business_vertical_name',
            7 => 'industry_name',
            8 => 'labournet_entity_name'
        );

        //Change query here for total record
        $strQuery = "SELECT COUNT(s.id)::BIGINT AS total_record_count
                     FROM   neo_customer.vw_oppurtunity AS s WHERE TRUE ";
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
                         FROM       neo_customer.vw_oppurtunity AS s
                         WHERE      TRUE ";

            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "select * from neo_customer.vw_oppurtunity AS s WHERE TRUE ";
            //Main Query here for fetching details

            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            //$intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
              $Actions = '';
                  if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_update_roles())) {
                    $Actions .= '<button class="btn btn-sm btn-warning" title="Update Opportunity Status" onclick="open_lead_popup(' . $QueryRow->id . ',' . $QueryRow->lead_status_id . ')" style="margin-left: 2px;"><i class="fa fa-pencil-square-o"></i></button>';
                  }
                  if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_update_roles())) {
                    $Actions .= '<a class="btn btn-sm btn-danger" title="Edit Opportunity" onclick="edit_lead(' . $QueryRow->id . ')"  style="margin-left: 2px;color:white;"><i class="icon-android-create"></i></a>';
                  }
                  $Actions .= '<a class="btn btn-sm btn-success" title="Opportunity History" onclick="lead_history(' . $QueryRow->id . ')"  style="margin-left: 2px;"><i class="fa fa-history"></i></a>';
                  $Actions .= '<a class="btn btn-sm btn-primary" title="Additional Spoc Details" onclick="showAdditionalSpocs(' . $QueryRow->id . ')"  style="margin-left: 2px;color:white;"><i class="fa fa-phone"></i></a>';
                  if( $QueryRow->lead_status_id==16 || $QueryRow->lead_status_id ==21) {
                    $Actions .= '<a class="btn btn-sm " title="Opportunity Commercials" href="'.base_url("/CommercialVerificationController/commericalsStore/".$QueryRow->id).'"  style="margin-left: 2px;color:white;background-color:#c72a9e;"><i class="fa fa-rupee"></i></a>';
                  }
                  if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_assignment_roles())) {
                    $Actions .= '<a class="btn btn-sm btn-warning" title="Assign Lead" onclick="open_placement_officer_assign_model(' . $QueryRow->id . ')"  style="margin-left: 2px;color:white;"><i class="fa fa-tasks"></i></a>';
                  }
               // $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $Actions;
                $ResponseRow[] = $QueryRow->company_name ?? 'N/A';
                $ResponseRow[] = $QueryRow->lead_status_name ?? 'N/A';
                $ResponseRow[] = $QueryRow->opportunity_code ?? 'N/A';
                $ResponseRow[] = $QueryRow->contract_id ?? 'N/A';
                $ResponseRow[] = $QueryRow->business_vertical_name ?? 'N/A';
                $ResponseRow[] = $QueryRow->industry_name ?? 'N/A';
                $ResponseRow[] = $QueryRow->labournet_entity_name ?? 'N/A';

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
