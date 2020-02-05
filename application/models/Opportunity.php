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

    public function findByCompanyID($company_id) {
      $query = $this->db->select('neo_customer.opportunities.company_id, neo_customer.opportunities.contract_id, neo_customer.opportunities.id, neo_customer.opportunities.business_vertical_id, neo_master.business_verticals.name')
            ->from('neo_customer.opportunities')
            ->join('neo_master.business_verticals', 'neo_master.business_verticals.id = neo_customer.opportunities.business_vertical_id', 'LEFT')
            ->where('is_contract', TRUE)
            ->where('neo_customer.opportunities.company_id', $company_id);
      return $query->get()->result();
    }

    public function getSpocsByOpportunityID($id) {
      $spocs = $this->db->select('spoc_detail')->where('opportunity_id', $id)->get('neo_customer.customer_branches')->row_array();
      $spoc_array = json_decode($spocs['spoc_detail']);
      return array_unique($spoc_array, SORT_REGULAR);
    }

    public function save($data) {
      $data = $this->makeTimeStamp($data, 'save');
      $this->db->trans_start();
      $this->db->insert($this->tableName, $this->getFillable($data));
      $data['opportunity_id'] = $this->db->insert_id();
      $this->createOrUpdateLocation($data['opportunity_id'], $data, 'save');
      $data = $this->generateOpportunityCodes($data);
      $this->db->where('id', $data['opportunity_id'])->update($this->tableName, $this->getFillable($data));
      $this->createInitialOpportunityLog($data);
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

    //opportunity identified log
    public function createInitialOpportunityLog($data) {
      $logs_data['customer_id'] = $data['opportunity_id'];
      $logs_data['created_by'] = $data['created_by'];
      $logs_data['lead_status_id'] = 1;
      $this->db->reset_query();
      $this->db->insert('neo_customer.lead_logs', $logs_data);
    }

    //generates contract code as well
    private function generateOpportunityCodes($data) {
      $result = $this->db->select('COM.company_name, BV.code', false)->from($this->tableName)
              ->join('neo_customer.companies AS COM', "COM.id={$this->tableName}.company_id", 'LEFT', FALSE)
              ->join('neo_master.business_verticals AS BV', "BV.id={$this->tableName}.business_vertical_id", 'LEFT', FALSE)
              ->where("{$this->tableName}.id", $data['opportunity_id'])->get()->row_array();
      $data['opportunity_code'] = 'OPP-'.strtoupper(substr(trim($result['company_name']), 0, (strlen(trim($result['company_name']))>3 ? 4 : 3))).'-'.$result['code'].'-'.$data['opportunity_id'];
      $data['contract_id'] = 'CON-'.strtoupper(substr(trim($result['company_name']), 0, (strlen(trim($result['company_name']))>3 ? 4 : 3))).'-'.$data['opportunity_id'];;
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
           unset($data['is_paid']);
           $this->db->update($this->tableName, $maindata);

           $this->db->reset_query();
           $this->createLeadLog($data, "Free Opportunity");

           $maindata['lead_status_id'] = 22;
           $data['lead_status_id']=22;
           $this->db->reset_query();
        }
      }
      unset($data['is_paid']);
      $this->db->where('id', $data['customer_id']);
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

    public function getLeadHistory($lead_id) {
      return $this->db->select('logs.* , status.name as status_name')->from('neo_customer.lead_logs as logs')
      ->join('neo_master.lead_statuses as status', 'logs.lead_status_id = status.id', 'LEFT')
      ->order_by('logs.id', 'ASC')
      ->where('logs.customer_id', $lead_id)->get()->result();
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
              $action_opp_url = base_url("opportunitiescontroller/edit/").$QueryRow->id;
                  if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_update_roles())) {
                    $Actions .= '<button class="btn btn-sm btn-warning" title="Update Opportunity Status" onclick="open_lead_popup(' . $QueryRow->id . ',' . $QueryRow->lead_status_id . ')" style="margin-left: 2px;"><i class="fa fa-pencil-square-o"></i></button>';
                  }
                  if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_update_roles())) {
                    $Actions .= '<a class="btn btn-sm btn-danger" title="Edit Opportunity" href="'.$action_opp_url.'"  style="margin-left: 2px;color:white;"><i class="icon-android-create"></i></a>';
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



    function getOpportunityData($requestData=array())
  {
    $order_by=" ";
    $search_type_id = isset($requestData['search_type_id']) ? intval($requestData['search_type_id']) : 0;
    $search_value = isset($requestData['search_value']) ? $requestData['search_value'] : '';

    $Data = array();
//    $active_user_role_id = $this->session->userdata('usr_authdet')['user_group_id'];
    $TeamMemberIdList = implode(",",$this->session->userdata('user_hierarchy'));
    $columns = array(
                0 => null,
                1 => null,
                2 => 'R.company_name',
                3 => 'R.lead_status_name',
                4 => 'R.opportunity_code',
                5 => 'contract_id',
                6 => 'R.business_vertical_name',
                7 => 'R.industry_name',
                8 => 'R.labournet_entity_name'
     );

      $column_search = array(
          1 => "R.company_name",
          2 => "R.lead_status_id",
          3 => "R.opportunity_code",
          4 => "R.contract_id",
          5 => "R.business_vertical_id",
          6 => "R.industry_id",
          7 => "R.labournet_entity_id"
      );

    $HierarchyCondition = "";
       if ($TeamMemberIdList != '')
           $HierarchyCondition = " AND (R.assigned_user_ids||R.created_by && ARRAY[$TeamMemberIdList]) ";

    $TotalRecordsQuery = "WITH R AS
                            (
                                SELECT  o.id,
                                        o.created_by,
                                        (SELECT ARRAY_AGG(LU.user_id) FROM neo_customer.leads_users AS LU WHERE LU.lead_id=o.id) AS assigned_user_ids
                                FROM    neo_customer.opportunities AS o
                                WHERE o.is_contract=false
                            )
                            SELECT  COUNT(id) AS total_recs
                            FROM    R
                            WHERE   TRUE
                        $HierarchyCondition";
    $total_records=$this->db->query($TotalRecordsQuery)->row()->total_recs;

    $totalData=$total_records*1;
    $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

    $pg=$requestData['start'];
    $limit=$requestData['length'];
    if($limit<0) $limit='all';

    if(!$total_records)
      return array('sEcho'=>'1', "iTotalRecords"=>"0", "iTotalDisplayRecords"=>"0",'aaData'=>array());
    else
    {
        $FilterCondition = "";
        if($this->session->userdata['usr_authdet']['user_group_id']==17) {
          $FilterCondition .= " AND lead_status_id IN (16,21)";
        }
        if ($search_type_id > 0)
        {
            switch($search_type_id)
            {
                case 2:
                case 5:
                case 6:
                case 7:
                    if ($search_value != '0')
                    {
                        $FilterCondition = $column_search[$search_type_id] . "=" . $search_value;
                    }
                    break;

                default:
                    if (trim($search_value) != '')
                    {
                        $FilterCondition = $column_search[$search_type_id] . " ~* '" . trim($search_value) . "'";
                    }
            }



            if (trim($FilterCondition) != "")
            {
                $FilterCondition = " AND ($FilterCondition) ";
            }


        }

        $FilterQuery = "WITH R AS
                              (
                              SELECT o.id,
                                    c.company_name,
                                    o.opportunity_code,
                                    o.contract_id,
                                    o.lead_status_id,
                                    ls.name AS lead_status_name,
                                    o.business_vertical_id,
                                    bv.name AS business_vertical,
                                    o.industry_id,
                                    i.name AS industry,
                                    o.labournet_entity_id,
                                    le.name AS labournet_entity,
                                    B.spoc_name,
                                    B.spoc_email,
                                    B.spoc_phone,
                                    o.created_by,
                                    (SELECT ARRAY_AGG(LU.user_id) FROM neo_customer.leads_users AS LU WHERE LU.lead_id=o.id) AS assigned_user_ids
                              FROM neo_customer.opportunities AS O
                              LEFT JOIN neo_customer.companies AS C ON C.id=o.company_id
                              LEFT JOIN neo_master.lead_statuses AS LS ON LS.id=o.lead_status_id
                              LEFT JOIN neo_master.business_verticals AS BV ON BV.id=o.business_vertical_id
                              LEFT JOIN neo_master.industries AS i ON i.id=o.industry_id
                              LEFT JOIN neo_master.labournet_entities AS le ON le.id=o.labournet_entity_id
                              LEFT JOIN
                                      (
                                        SELECT 	CB.opportunity_id,
                                                STRING_AGG(t->>'spoc_name',',') AS spoc_name,
                                                STRING_AGG(t->>'spoc_email',',') AS spoc_email,
                                                STRING_AGG(t->>'spoc_phone',',') AS spoc_phone
                                        FROM 	neo_customer.customer_branches AS CB
                                        CROSS JOIN LATERAL json_array_elements(CB.spoc_detail::json) AS x(t)
                                        GROUP BY CB.opportunity_id
                                      ) AS B ON 	B.opportunity_id=o.id
                                      WHERE o.is_contract=false
                              ORDER BY o.created_at
                              )
                              SELECT  COUNT(R.id) AS total_filtered
                              FROM    R
                              WHERE   TRUE
                        $FilterCondition
                        $HierarchyCondition";

      $totalFiltered = $this->db->query($FilterQuery)->row()->total_filtered;

      if($columns[$requestData['order'][0]['column']]!='')
      {
          $order_by=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  ";
      }
      $FinalQuery = 	"WITH R AS
                            (
                            SELECT o.id,
                                  c.company_name,
                                  o.opportunity_code,
                                  o.contract_id,
                                  o.lead_status_id,
                                  ls.name AS lead_status_name,
                                  o.business_vertical_id,
                                  bv.name AS business_vertical,
                                  o.industry_id,
                                  i.name AS industry,
                                  o.labournet_entity_id,
                                  le.name AS labournet_entity,
                                  B.spoc_name,
                                  B.spoc_email,
                                  B.spoc_phone,
                                  o.created_by,
                                  (SELECT ARRAY_AGG(LU.user_id) FROM neo_customer.leads_users AS LU WHERE LU.lead_id=o.id) AS assigned_user_ids
                            FROM neo_customer.opportunities AS O
                            LEFT JOIN neo_customer.companies AS C ON C.id=o.company_id
                            LEFT JOIN neo_master.lead_statuses AS LS ON LS.id=o.lead_status_id
                            LEFT JOIN neo_master.business_verticals AS BV ON BV.id=o.business_vertical_id
                            LEFT JOIN neo_master.industries AS i ON i.id=o.industry_id
                            LEFT JOIN neo_master.labournet_entities AS le ON le.id=o.labournet_entity_id
                            LEFT JOIN
                                    (
                                      SELECT 	CB.opportunity_id,
                                              STRING_AGG(t->>'spoc_name',',') AS spoc_name,
                                              STRING_AGG(t->>'spoc_email',',') AS spoc_email,
                                              STRING_AGG(t->>'spoc_phone',',') AS spoc_phone
                                      FROM 	neo_customer.customer_branches AS CB
                                      CROSS JOIN LATERAL json_array_elements(CB.spoc_detail::json) AS x(t)
                                      GROUP BY CB.opportunity_id
                                    ) AS B ON 	B.opportunity_id=o.id
                                    WHERE o.is_contract=false
                            ORDER BY o.created_at DESC
                            )
                            SELECT  R.*
                            FROM    R
                            WHERE   TRUE
                    $FilterCondition
                    $HierarchyCondition
                    $order_by
                    LIMIT $limit
                    OFFSET $pg";

                    $QueryData = $this->db->query($FinalQuery);

                    $SerialNumber = $pg;
                    //$intActiveStatus = 1;
                    foreach ($QueryData->result() as $QueryRow) {
                      $Actions = '';
                      $action_opp_url = base_url("opportunitiescontroller/edit/").$QueryRow->id;
                          if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_update_roles())) {
                            $Actions .= '<button class="btn btn-sm btn-warning" title="Update Opportunity Status" onclick="open_lead_popup(' . $QueryRow->id . ',' . $QueryRow->lead_status_id . ')" style="margin-left: 2px;background-color:#273c75;border-color: #192a56;"><i class="fa fa-pencil-square-o"></i></button>';
                          }
                          if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_update_roles())) {
                            $Actions .= '<a class="btn btn-sm btn-danger" title="Edit Opportunity" href="'.$action_opp_url.'"  style="margin-left: 2px;color:white;background-color:#33d9b2;border-color: #218c74;"><i class="icon-android-create"></i></a>';
                          }
                          $Actions .= '<a class="btn btn-sm btn-success" title="Opportunity History" onclick="lead_history(' . $QueryRow->id . ')"  style="margin-left: 2px;"><i class="fa fa-history"></i></a>';
                          $Actions .= '<a class="btn btn-sm btn-primary" title="Additional Spoc Details" onclick="showAdditionalSpocs(' . $QueryRow->id . ')"  style="margin-left: 2px;color:white;"><i class="fa fa-phone"></i></a>';
                          if( $QueryRow->lead_status_id==16 || $QueryRow->lead_status_id ==21) {
                            $Actions .= '<a class="btn btn-sm " title="Opportunity Commercials" href="'.base_url("/CommercialVerificationController/commericalsStore/".$QueryRow->id).'"  style="margin-left: 2px;color:white;background-color:#c72a9e;"><i class="fa fa-rupee"></i></a>';
                          }
                          // if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_assignment_roles())) {
                          //   $Actions .= '<a class="btn btn-sm btn-warning" title="Assign Lead" onclick="open_placement_officer_assign_model(' . $QueryRow->id . ')"  style="margin-left: 2px;color:white;"><i class="fa fa-tasks"></i></a>';
                          // }
                       // $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                        $ResponseRow = array();
                        $SerialNumber++;
                        $ResponseRow[] = $SerialNumber;
                        $ResponseRow[] = $Actions;
                        $ResponseRow[] = $QueryRow->company_name ?? 'N/A';
                        $ResponseRow[] = ($QueryRow->lead_status_id==16) ? ($QueryRow->lead_status_name.' (Pending Approval)') : $QueryRow->lead_status_name;
                        //$ResponseRow[] = $QueryRow->lead_status_name ?? 'N/A';
                        $ResponseRow[] = $QueryRow->opportunity_code ?? 'N/A';
                        //$ResponseRow[] = $QueryRow->contract_id ?? 'N/A';
                        $ResponseRow[] = $QueryRow->business_vertical ?? 'N/A';
                        $ResponseRow[] = $QueryRow->industry ?? 'N/A';
                        $ResponseRow[] = $QueryRow->labournet_entity ?? 'N/A';

                        $Data[] = $ResponseRow;
                    }

                    $ResponseData = array(
                        "draw" => intval($requestData['draw']),
                        "recordsTotal" => intval($totalData),
                        "recordsFiltered" => intval($totalFiltered),
                        "data" => $Data
                    );


      return $ResponseData;
    }
  }


}
