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


    function getCompanyData($requestData=array())
  {
    $order_by=" ";
    $search_type_id = isset($requestData['search_type_id']) ? intval($requestData['search_type_id']) : 0;
    $search_value = isset($requestData['search_value']) ? $requestData['search_value'] : '';

    $data = array();
//    $active_user_role_id = $this->session->userdata('usr_authdet')['user_group_id'];
    $TeamMemberIdList = implode(",",$this->session->userdata('user_hierarchy'));
    $columns = array(
                0 => null,
                1 => null,
                2 => 'R.company_name',
                3 => 'R.industry',
                4 => 'R.functional_area',
                5 => 'R.spoc_name',
                6 => 'R.spoc_email',
                7 => 'R.spoc_phone',
                8 => 'R.state',
                9 => 'R.district',
                10 => 'R.lead_source'
    );

      $column_search = array(
          1 => "R.company_name",
          2 => "R.lead_source_id",
          3 => "R.spoc_name",
          4 => "R.spoc_email",
          5 => "R.spoc_phone",
          6 => "R.state_id",
          7 => "R.industry_id",
          8 => "R.functional_area_id"
      );

    $HierarchyCondition = "";
       if ($TeamMemberIdList != '')
           $HierarchyCondition = " AND (R.assigned_user_ids||R.created_by && ARRAY[$TeamMemberIdList]) ";

    $TotalRecordsQuery = "WITH R AS
                        (
                            SELECT  C.id,
                                    C.created_by,
                                    (SELECT ARRAY_AGG(LU.user_id) FROM neo_customer.leads_users AS LU WHERE LU.lead_id=C.id) AS assigned_user_ids
                            FROM    neo_customer.companies AS C
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
                case 6:
                case 7:
                case 8:
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
                              SELECT 	    C.id,
                              COALESCE(C.company_name,FORMAT('Customer_%s',C.id)) AS company_name,            
                              B.spoc_name
                              || (CASE WHEN COALESCE(TRIM(C.hr_name),'')<>'' THEN ','||TRIM(C.hr_name) ELSE '' END) AS spoc_name,
                              B.spoc_email
                              || (CASE WHEN COALESCE(TRIM(C.hr_email),'')<>'' THEN ','||TRIM(C.hr_email) ELSE '' END) AS spoc_email,
                              B.spoc_phone
                              || (CASE WHEN COALESCE(TRIM(C.hr_phone),'')<>'' THEN ','||TRIM(C.hr_phone) ELSE '' END) AS spoc_phone,
                              C.lead_source_id,
                              LS.name AS lead_source_name,
                              C.company_description,
                              C.created_by,
                              CB.state_id,
                              s.name AS state,
                              CB.district_id,
                              d.name AS district,
                              (
                                SELECT count(*) AS count
                                FROM neo_customer.opportunities o
                                WHERE o.company_id = c.id
                              ) AS opportunity_count,
                                c.industry_id,
                                i.name AS industries,
                                c.functional_area_id,
                                fa.name AS functional_area,
                                c.remarks,
                              (SELECT ARRAY_AGG(LU.user_id) FROM neo_customer.leads_users AS LU WHERE LU.lead_id=C.id) AS assigned_user_ids
                              FROM		neo_customer.companies AS C
                              LEFT JOIN 	neo_master.lead_sources AS LS ON LS.id=c.lead_source_id
                              LEFT JOIN   neo_customer.customer_branches AS CB ON CB.customer_id=c.id AND cb.is_main_branch
                              LEFT JOIN   neo_master.states AS s ON s.id=CB.state_id
                              LEFT JOIN   neo_master.districts AS d ON d.id=CB.district_id
                              LEFT JOIN neo_master.industries AS i ON i.id=c.industry_id
                              LEFT JOIN neo_master.functional_areas AS fa ON fa.id=c.functional_area_id
                              LEFT JOIN
                              (
                              SELECT 	CB.customer_id,
                              STRING_AGG(t->>'spoc_name',',') AS spoc_name,
                              STRING_AGG(t->>'spoc_email',',') AS spoc_email,
                              STRING_AGG(t->>'spoc_phone',',') AS spoc_phone
                              FROM 	neo_customer.customer_branches AS CB
                              CROSS JOIN LATERAL json_array_elements(CB.spoc_detail::json) AS x(t)
                              GROUP BY CB.customer_id
                              ) AS B ON 	B.customer_id=C.id
                              ORDER BY c.created_at DESC
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
                            SELECT 	    C.id,
                                        COALESCE(C.company_name,FORMAT('Customer_%s',C.id)) AS company_name,            
                                        B.spoc_name
                                        || (CASE WHEN COALESCE(TRIM(C.hr_name),'')<>'' THEN ','||TRIM(C.hr_name) ELSE '' END) AS spoc_name,
                                        B.spoc_email
                                        || (CASE WHEN COALESCE(TRIM(C.hr_email),'')<>'' THEN ','||TRIM(C.hr_email) ELSE '' END) AS spoc_email,
                                        B.spoc_phone
                                        || (CASE WHEN COALESCE(TRIM(C.hr_phone),'')<>'' THEN ','||TRIM(C.hr_phone) ELSE '' END) AS spoc_phone,
                                        C.lead_source_id,
                                        LS.name AS lead_source_name,
                                        C.company_description,
                                        C.created_by,
                                        CB.state_id,
                                        s.name AS state,
                                        CB.district_id,
                                        d.name AS district,
                                        (
                                          SELECT count(*) AS count
                                          FROM neo_customer.opportunities o
                                          WHERE o.company_id = c.id
                                        ) AS opportunity_count,
                                          c.industry_id,
                                          i.name AS industries,
                                          c.functional_area_id,
                                          fa.name AS functional_area,
                                          c.remarks,
                                          (SELECT ARRAY_AGG(LU.user_id) FROM neo_customer.leads_users AS LU WHERE LU.lead_id=C.id) AS assigned_user_ids
                            FROM		neo_customer.companies AS C
                            LEFT JOIN 	neo_master.lead_sources AS LS ON LS.id=c.lead_source_id
                            LEFT JOIN   neo_customer.customer_branches AS CB ON CB.customer_id=c.id AND cb.is_main_branch
                            LEFT JOIN   neo_master.states AS s ON s.id=CB.state_id
                            LEFT JOIN   neo_master.districts AS d ON d.id=CB.district_id                            
                            LEFT JOIN neo_master.industries AS i ON i.id=c.industry_id
                            LEFT JOIN neo_master.functional_areas AS fa ON fa.id=c.functional_area_id
                            LEFT JOIN
                            (
                                    SELECT 	CB.customer_id,
                                                    STRING_AGG(t->>'spoc_name',',') AS spoc_name,
                                                    STRING_AGG(t->>'spoc_email',',') AS spoc_email,
                                                    STRING_AGG(t->>'spoc_phone',',') AS spoc_phone
                                    FROM 	neo_customer.customer_branches AS CB
                                    CROSS JOIN LATERAL json_array_elements(CB.spoc_detail::json) AS x(t)
                                    GROUP BY CB.customer_id
                            ) AS B ON 	B.customer_id=C.id
                            ORDER BY c.created_at DESC
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
                          $action_com_url = base_url("companiescontroller/edit/").$QueryRow->id;
                          if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_update_roles())) {
                            $Actions .= '<a class="btn btn-sm btn-danger" title="Edit Lead" href="'.$action_com_url.'"  style="margin-left: 2px;color:white;background-color:#004d80;border-color: #002e4d;"><i class="icon-android-create"></i></a>';
                          }
                          $action_opp_url = base_url("opportunitiescontroller/create/").$QueryRow->id;
                          $Actions .= '<a class="btn btn-sm btn-success" title="Create Opportunity" href="'.$action_opp_url.'"  style="margin-left: 2px;"><i class="fa fa-share"></i></a>';
        
        
        
                       // $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                        $ResponseRow = array();
                        $SerialNumber++;
                        $ResponseRow[] = $SerialNumber;
                        $ResponseRow[] = $Actions;
                        $ResponseRow[] = $QueryRow->company_name ?? 'N/A';
                        $ResponseRow[] = ($QueryRow->opportunity_count) ? '<center><b><a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Opportunity Detail" onclick="view_opportunity(' .  $QueryRow->id . ')">' . $QueryRow->opportunity_count . '</a></b></center>' : '<center>'.$QueryRow->opportunity_count.'</center>';
                        // $ResponseRow[] = $QueryRow->opportunity_count ?? 'N/A';
                        $ResponseRow[] = $QueryRow->company_description ?? 'N/A';
                        $ResponseRow[] = $QueryRow->industries ?? 'N/A';
                        $ResponseRow[] = $QueryRow->functional_area ?? 'N/A';
                        $ResponseRow[] = $QueryRow->spoc_name ?? 'N/A';
                        $ResponseRow[] = $QueryRow->spoc_email ?? 'N/A';
                        $ResponseRow[] = $QueryRow->spoc_phone ?? 'N/A';
                        $ResponseRow[] = $QueryRow->state ?? 'N/A';
                        $ResponseRow[] = $QueryRow->district ?? 'N/A';
                        $ResponseRow[] = $QueryRow->lead_source_name ?? 'N/A';
                        $ResponseRow[] = $QueryRow->remarks ?? 'N/A';
        
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


  function getOpportunityData($company_id=0)
	{
		$company_det_rec=$this->db->query("SELECT c.company_name, 
                                                o.opportunity_code
                                            FROM neo_customer.opportunities AS o
                                            LEFT JOIN neo_customer.companies AS c ON c.id=o.company_id
                                            WHERE c.id=?",$company_id);
		
		$opportunity_det_rec=$this->db->query("SELECT o.id,
                                                  c.company_name,
                                                  o.opportunity_code,
                                                  o.contract_id,
                                                  CD.file_name,
                                                  o.is_paid,
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
                                                  o.created_at,    
                                                  (SELECT ARRAY_AGG(LU.user_id) FROM neo_customer.leads_users AS LU WHERE LU.lead_id=o.id) AS assigned_user_ids    	     	 
                                              FROM neo_customer.opportunities AS O
                                              LEFT JOIN neo_customer.companies AS C ON C.id=o.company_id
                                              LEFT JOIN neo_master.lead_statuses AS LS ON LS.id=o.lead_status_id
                                              LEFT JOIN neo_master.business_verticals AS BV ON BV.id=o.business_vertical_id
                                              LEFT JOIN neo_master.industries AS i ON i.id=o.industry_id
                                              LEFT JOIN neo_master.labournet_entities AS le ON le.id=o.labournet_entity_id
                                              LEFT JOIN neo_customer.customer_documents AS CD ON CD.customer_id = o.id
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
                                              WHERE c.id=?",array($company_id));  

		if($company_det_rec->num_rows())
		{
			$output['status']=true;		
			$output['company_detail']=$company_det_rec->row_array();
			if($opportunity_det_rec->num_rows())
				$output['opportunity_detail']=$opportunity_det_rec->result_array();
			else
				$output['opportunity_detail']=array();
		}
		else
			$output['status']=false;
		return $output;
	}


}
