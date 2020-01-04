<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

  protected $tableName = '';

  public function getUserHierarchy($user_id){
    return $this->db->query('select * from neo_user.fn_get_recursive_team_data(?)', $user_id)->result_array();
  }

  public function getSalesHeadId() {
    return $this->db->where('email', 'manish.tiwari@labournet.in')->get('neo_user.users')->row_array()['id'];
  }

  public function findReportiesByManager($manager_id){
    return $this->db->where('reporting_manager_id', $manager_id)->get('neo_user.users')->result_array();
  }

  public function find($id) {
    return $this->db->where('id', $id)->get($this->tableName)->row_array();
  }


  public function allByCandidate($candidate_id) {
    return $this->db->where('candidate_id', $candidate_id)->get($this->tableName)->result();
  }

  public function all() {
    return $this->db->get($this->tableName)->result();
  }

  public function allCustomers() {
    return $this->db->where('is_customer', true)->get('neo_customer.customers')->result();
  }

  public function save($data) {
    $this->db->insert($this->tableName, array_filter($data, array($this, 'nonZeroFilter')));
    if($this->db->affected_rows() == 1 ) {
      return true;
    }
    return false;
  }

  public function update($id, $data) {
    $this->db->where('id', $id);
    $this->db->update($this->tableName, array_filter($data, array($this, 'nonZeroFilter')));
    if($this->db->affected_rows() == 1 ) {
      // $remarkdata['updated_by'] = $data['modified_by'];
      // $remarkdata['lead_status_id'] = $data['lead_status_id'];
      // $remarkdata['employer_id'] = $id;
      // $this->createLeadLog($remarkdata);
      return true;
    }
    return false;
  }

  public function deleteCandidateAssociation($candidate_id, $id) {
    $query = $this->db->where('candidate_id', $candidate_id)->where('id', $id)->get($this->tableName)->num_rows();
    if($query==1) {
      $this->db->delete($this->tableName, ['candidate_id'=>$candidate_id, 'id' => $id]);
      if($this->db->affected_rows() == 1 ) {
        return true;
      }
    }
    return false;
  }

  public function getCenters() {
      return $this->db->select('id, center_name')->where('status', '1')->get('neo_user.centers')->result();
  }

  public function getCommercialRemarkTypes() {
      return $this->db->select('id, name')->get('neo_master.commercial_remark_types')->result();
  }

  public function getCommercialStatuses() {
    return $this->db->select('*')->where_in('id', [20,21])->get('neo_master.lead_statuses')->result();
  }

  public function getRecruiters() {
    return $this->db->where('user_role_id', 11)->where('is_active', TRUE)->get('neo_user.users')->result();
  }

  public function getPlacementOfficers() {
    return $this->db->where('user_role_id', 14)->where('is_active', TRUE)->get('neo_user.users')->result();
  }

  public function getBusinessPractices() {
    $query = $this->db->get('neo_master.business_practices');
    return $query->result();
  }

  public function getBusinessVerticals() {
    $query = $this->db->where('is_active', TRUE)->order_by('id')->get('neo_master.business_verticals');
    return $query->result();
  }

  public function getCandidateSources() {
    $query = $this->db->get('neo_master.candidate_sources');
    return $query->result();
  }

  public function getCandidateStatuses() {
    $this->db->where('id !=', 17)->from('neo_master.candidate_statuses');
    $this->db->order_by("id", "asc");
    $query = $this->db->get();
    return $query->result();

    //$query = $this->db->get('neo_master.candidate_statuses');
    //return $query->result();
  }

  public function getCandidateTypes() {
    $query = $this->db->get('neo_master.candidate_types');
    return $query->result();
  }

  public function getCasteCategories() {
    return $this->db->get('neo_master.caste_categories')->result();
  }

  public function getCountries() {
    return $this->db->select('*')->where('active_status', 1)->get('master.country')->result();
  }

   public function getRegions() {
    return $this->db->select('*')->get('neo_master.region')->result();
  }

  public function getCustomerStatuses() {
    return $this->db->select('*')->where('is_active', TRUE)->order_by('sort_order')->get('neo_master.lead_statuses')->result();
  }

  public function getDistricts($state_id) {
    return $this->db->select('neo_master.districts.*')->where('state_id', $state_id)->from('neo_master.districts')
    //->join('neo_master.states', 'neo_master.candidates.state_id=neo_master.states.id', 'LEFT')
    ->get()->result();
  }

  public function getDocumentTypes() {
    $query = $this->db->get('neo_master.document_types');
    return $query->result();
  }

  public function getEducations() {
    //$query = $this->db->select('*')->order_by('sort_order', 'ASC')->get('neo_master.educations');
    $query = $this->db->select('*')->get('neo_master.educations');
    return $query->result();
  }

  public function getEmploymentTypes() {
    return $this->db->get('neo_master.employment_type')->result();
  }

  public function getEmploymentTypesActive() {
    return $this->db->where('is_active', TRUE)->get('neo_master.employment_type')->result();
  }

  public function getEmploymentTypesActiveArray() {
    return $this->db->where('is_active', TRUE)->where('name !=', 'Self Employed')->get('neo_master.employment_type')->result_array();
  }


  public function getFunctionalAreas() {
    $query = $this->db->get('neo_master.functional_areas');
    return $query->result();
  }

  public function getGenders() {
    $query = $this->db->get('neo_master.genders');
    return $query->result();
  }
  public function getIndustries() {
    $query = $this->db->get('neo_master.industries');
    return $query->result();
  }

  public function getJobOpenTypes() {
    $query = $this->db->get('neo_master.job_open_types');
    return $query->result();
  }

  public function getJobPriorityLevels() {
    $query = $this->db->get('neo_master.job_priority_levels');
    return $query->result();
  }

  public function getJobStatuses() {
    $query = $this->db->get('neo_master.job_statuses');
    return $query->result();
  }

  public function getJobTitle() {
    $query = $this->db->select("DISTINCT(job_title) ")->order_by('job_title')->get('neo_job.jobs');
    return $query->result();
  }

  public function getJobCustomerNames($type="all") {
      $query='';
      if($type=='job'){
          $customer_id_array = $this->db->select('DISTINCT(customer_id) as customer_id')->get('neo_job.jobs')->result_array();
          if(count($customer_id_array)) {
             $query = $this->db->where_in('id', array_column($customer_id_array, 'customer_id'));
          }
      }

    $query = $this->db->select("DISTINCT(customer_name) ")->order_by('customer_name')->get('neo_customer.customers');
    return $query->result();
  }

  public function getCustomerNames() {
    $query = $this->db->select("DISTINCT(customer_name) ")->where('is_customer', TRUE)->order_by('customer_name')->get('neo_customer.customers');
    return $query->result();
  }

  public function getLeadCustomerNames() {
    $query = $this->db->select("DISTINCT(customer_name) ")->where('is_customer', FALSE)->order_by('customer_name')->get('neo_customer.customers');
    return $query->result();
  }

  public function getPlacementOfficersNames() {
    $query = $this->db->select("DISTINCT(name) ")->where('user_role_id=', '14')->order_by('name')->get('neo_user.users');
    return $query->result();
  }

  public function getRecruiterNames() {
    $query = $this->db->select("DISTINCT(name) ")->where('user_role_id=', '11')->order_by('name')->get('neo_user.users');
    return $query->result();
  }

  public function getJobCode() {
    $query = $this->db->select("neo_job_code")->where('neo_job_code<>')->order_by('neo_job_code')->get('neo_job.jobs');
    return $query->result();
  }

  public function getLeadStatuses($lead_id=0) {
    // $status_id = 19;
    // if ($lead_id!=0) {
    //   if($this->db->where('customer_id', $lead_id)->where('lead_status_id', 18)->get('neo_customer.lead_logs')->num_rows()>0) {
    //     $status_id = 1;
    //   }
    //   $lead_status_id = $this->db->where('id', $lead_id)->get('neo_customer.customers')->row_array()['lead_status_id'];
    //   $query = $this->db->where('id >=', $lead_status_id);
    // }
    // $query = $this->db->where('is_active', TRUE)->where('id !=', $status_id)->order_by('sort_order');
    // return $query->get('neo_master.lead_statuses')->result();
    return $this->db->select('*')->where('is_active', TRUE)->order_by('sort_order')->get('neo_master.lead_statuses')->result();
  }

  public function getLeadSources() {
    $query = $this->db->get('neo_master.lead_sources');
    return $query->result();
  }

  public function getLeadManagedby() {
    $query = $this->db->select("DISTINCT(lead_managed_by) AS lead_managed_by")->where('is_customer', FALSE)->order_by('lead_managed_by')->get('neo_customer.customers');
    return $query->result();
  }

  public function getSpocName() {
    $query = $this->db->select("DISTINCT(spoc_name) AS spoc_name")->where('spoc_name<>')->where_in('is_customer', FALSE)->order_by('spoc_name')->get('neo_customer.vw_spoc_details');
    return $query->result();
  }

  public function getSpocEmail() {
    $query = $this->db->select("DISTINCT(spoc_email) AS spoc_email")->where('spoc_email<>')->where_in('is_customer', FALSE)->order_by('spoc_email')->get('neo_customer.vw_spoc_details');
    return $query->result();
  }

  public function getSpocPhone() {
    $query = $this->db->select("DISTINCT(spoc_phone) AS spoc_phone")->where('spoc_phone<>')->where_in('is_customer', FALSE)->order_by('spoc_phone')->get('neo_customer.vw_spoc_details');
    return $query->result();
  }



  public function getLearningTypes() {
    $query = $this->db->get('neo_master.learning_types');
    return $query->result();
  }

  // public function getLocations() {
  //    $query = $this->db->select("neo_master.locations.id as location_id, FORMAT('%s, %s, %s, %s, %s',neo_master.locations.location, neo_master.locations.city, neo_master.districts.name, neo_master.states.name, neo_master.country.name) as location_name")
  //             ->join('neo_master.districts', 'neo_master.locations.district_id = neo_master.districts.id', 'LEFT')
  //             ->join('neo_master.states', 'neo_master.districts.state_id = neo_master.states.id', 'LEFT')
  //             ->join('neo_master.country', 'neo_master.locations.country_id = neo_master.country.id', 'LEFT');
  //     return $query->get('neo_master.locations')->result();
  // }

  public function getLocations() {
     $query = $this->db->select("neo_master.locations.id as location_id, CONCAT(neo_master.locations.location, ', ', neo_master.locations.city, ',', neo_master.districts.name, ', ', neo_master.states.name, ', ', neo_master.country.name) as location_name")
              ->join('neo_master.districts', 'neo_master.locations.district_id = neo_master.districts.id', 'LEFT')
              ->join('neo_master.states', 'neo_master.districts.state_id = neo_master.states.id', 'LEFT')
              ->join('neo_master.country', 'neo_master.locations.country_id = neo_master.country.id', 'LEFT');
      return $query->get('neo_master.locations')->result();
  }

  public function getMaritalStatuses() {
    return $this->db->get('neo_master.marital_statuses')->result();
  }

  public function getLeadType() {
    $query = $this->db->get('neo_master.lead_type');
    return $query->result();
  }

  public function getQualificationPacks() {
    $this->db->from('neo_master.qualification_packs');
    $this->db->order_by("name", "asc");
    $query = $this->db->get();
    return $query->result();
  }

  public function getReligions() {
    return $this->db->get('neo_master.religions')->result();
  }

  public function getStates($country_id=0) {
    return $this->db->get('neo_master.states')->result();
  }

  public function getUserRoles() {
    $query = $this->db->where_not_in('id', [0,1])->order_by('id', 'ASC')->get('neo_user.user_roles');
    return $query->result();
  }

  public function getWorkAuthorizations() {
    $query = $this->db->get('neo_master.work_authorizations');
    return $query->result();
  }


  public function getJoinedCandidateCount($id) {
    $query = $this->db->select("joined_candidates")->where('id', $id)->get('neo_job.vw_job_list');
    return $query->row();
  }


  public function getCustomerSpocName() {
    $query = $this->db->select("DISTINCT(spoc_name) AS spoc_name")->where('spoc_name<>')->where_in('is_customer', TRUE)->order_by('spoc_name')->get('neo_customer.vw_spoc_details');
    return $query->result();
  }

  public function getCustomerSpocEmail() {
    $query = $this->db->select("DISTINCT(spoc_email) AS spoc_email")->where('spoc_email<>')->where_in('is_customer', TRUE)->order_by('spoc_email')->get('neo_customer.vw_spoc_details');
    return $query->result();
  }

  public function getCustomerSpocPhone() {
    $query = $this->db->select("DISTINCT(spoc_phone) AS spoc_phone")->where('spoc_phone<>')->where_in('is_customer', TRUE)->order_by('spoc_phone')->get('neo_customer.vw_spoc_details');
    return $query->result();
  }

  public function getJobVacancyDetail($job_id) {
    $openings = $this->db->where('id', $job_id)->get('neo_job.jobs')->row_array()['no_of_position'];
    $filled_vacancies = $this->db->where('job_id', $job_id)->where('candidate_status_id', 15)->from('neo_job.candidates_jobs')->count_all_results();
    return $openings>$filled_vacancies;
  }

  public function getSpocsByCustomerID($id) {
    $spocs = $this->db->select('spoc_detail')->where('customer_id', $id)->get('neo_customer.customer_branches')->row_array();
    $hr = $this->db->select('hr_name as spoc_name, hr_email as spoc_email, hr_phone as spoc_phone, hr_designation as spoc_designation')
          ->where('id', $id)->get('neo_customer.customers')->row();
    $spoc_array = json_decode($spocs['spoc_detail']);
    if($hr->spoc_name!=''){
      array_push($spoc_array, $hr);
    }
    return array_unique($spoc_array, SORT_REGULAR);
  }

  // public function getStates($country_id) {
  //   return $this->db->select('neo_master.state.*')->from('neo_master.state')
  //   ->join('neo_master.regions', 'neo_master.state.region_id=neo_master.regions.id', 'LEFT')
  //   ->join('neo_master.country', 'neo_master.regions.country_id=neo_master.country.id', 'LEFT')
  //   ->where('neo_master.country.id', $country_id)->get()->result();
  // }


  // public function getDistricts($state_id) {
  //   return $this->db->select('neo_master.district.*')->from('neo_master.district')
  //   ->join('neo_master.state', 'neo_master.district.state_id=neo_master.state.id', 'LEFT')
  //   ->where('neo_master.state.id', $state_id)->get()->result();
  // }

  function nonZeroFilter($var){
    return ($var !== NULL && $var !== FALSE && $var !== '');
  }


  function get_lead_data($requestData=array())
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
        2 => "R.customer_name",
        3 => "R.buisness_vertical_name",
        4 => "R.lead_status_name",
        5 => "R.lead_managed_by",
        6 => "R.spoc_name",
        7 => "R.spoc_email",
        8 => "R.spoc_phone",
        9 => "R.state",
        10 => "R.district",
        11 => "R.business_probability",
        12 => "R.lead_source_name",
        13 => "R.customer_description"
    );

    $column_search = array(
        1 => "R.customer_name",
        2 => "R.business_vertical_id",
        3 => "R.lead_status_id",
        4 => "R.lead_managed_by",
        5 => "R.spoc_name",
        6 => "R.spoc_email",
        7 => "R.spoc_phone",
        8 => "R.state_id",
        9 => "R.lead_source_id"
    );

    $HierarchyCondition = "";
       if ($TeamMemberIdList != '')
           $HierarchyCondition = " AND (R.assigned_user_ids||R.created_by && ARRAY[$TeamMemberIdList]) ";

    $TotalRecordsQuery = "WITH R AS
                        (
                            SELECT  C.id,
                                    C.created_by,
                                    (SELECT ARRAY_AGG(LU.user_id) FROM neo_customer.leads_users AS LU WHERE LU.lead_id=C.id) AS assigned_user_ids
                            FROM    neo_customer.customers AS C
                            WHERE   C.is_customer = FALSE
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
                case 3:
                case 8:
                case 9:
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
                            SELECT 	C.id,
                                        COALESCE(C.customer_name,FORMAT('Customer_%s',C.id)) AS customer_name,
                                        C.business_vertical_id,
                                        C.lead_status_id,
                                        B.spoc_name
                                        || (CASE WHEN COALESCE(TRIM(C.hr_name),'')<>'' THEN ','||TRIM(C.hr_name) ELSE '' END) AS spoc_name,
                                        B.spoc_email
                                        || (CASE WHEN COALESCE(TRIM(C.hr_email),'')<>'' THEN ','||TRIM(C.hr_email) ELSE '' END) AS spoc_email,
                                        B.spoc_phone
                                        || (CASE WHEN COALESCE(TRIM(C.hr_phone),'')<>'' THEN ','||TRIM(C.hr_phone) ELSE '' END) AS spoc_phone,
                                        C.lead_managed_by,
                                        C.lead_source_id,
                                        CB.state_id,
                                        d.name AS district,
                                        c.created_by,
                                        (SELECT ARRAY_AGG(LU.user_id) FROM neo_customer.leads_users AS LU WHERE LU.lead_id=C.id) AS assigned_user_ids
                            FROM	neo_customer.customers AS C
                            LEFT JOIN 	neo_master.lead_sources AS LS ON LS.id=C.lead_source_id
                            LEFT JOIN 	neo_master.lead_statuses AS LT ON LT.id=C.lead_status_id
                            LEFT JOIN   neo_master.business_verticals AS bv ON bv.id=C.business_vertical_id
                            LEFT JOIN   neo_customer.customer_branches AS CB ON CB.id=c.id
                            LEFT JOIN   neo_master.states AS s ON s.id=CB.state_id
                            LEFT JOIN   neo_master.districts AS d ON d.id=CB.district_id
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
                            WHERE 	C.is_customer = FALSE
                            ORDER BY    c.created_at DESC
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
                                        COALESCE(C.customer_name,FORMAT('Customer_%s',C.id)) AS customer_name,
                                        C.business_vertical_id,
                                        bv.name AS buisness_vertical_name,
                                        C.lead_status_id,
                                        LT.name AS lead_status_name,
                                        B.spoc_name
                                        || (CASE WHEN COALESCE(TRIM(C.hr_name),'')<>'' THEN ','||TRIM(C.hr_name) ELSE '' END) AS spoc_name,
                                        B.spoc_email
                                        || (CASE WHEN COALESCE(TRIM(C.hr_email),'')<>'' THEN ','||TRIM(C.hr_email) ELSE '' END) AS spoc_email,
                                        B.spoc_phone
                                        || (CASE WHEN COALESCE(TRIM(C.hr_phone),'')<>'' THEN ','||TRIM(C.hr_phone) ELSE '' END) AS spoc_phone,
                                        C.lead_managed_by,
                                        (CASE
                                                WHEN LT.value < 1 THEN 0
                                                ELSE (LT.value*100/12)
                                        END) AS business_probability,
                                        C.lead_source_id,
                                        LS.name AS lead_source_name,
                                        C.customer_description,
                                        C.created_by,
                                        CB.state_id,
                                        s.name AS location,
                                        CB.district_id,
                                        d.name AS district,
                                        (SELECT ARRAY_AGG(LU.user_id) FROM neo_customer.leads_users AS LU WHERE LU.lead_id=C.id) AS assigned_user_ids
                            FROM		neo_customer.customers AS C
                            LEFT JOIN 	neo_master.lead_sources AS LS ON LS.id=C.lead_source_id
                            LEFT JOIN 	neo_master.lead_statuses AS LT ON LT.id=C.lead_status_id
                            LEFT JOIN   neo_master.business_verticals AS bv ON bv.id=C.business_vertical_id
                            LEFT JOIN   neo_customer.customer_branches AS CB ON CB.id=c.id
                            LEFT JOIN   neo_master.states AS s ON s.id=CB.state_id
                            LEFT JOIN   neo_master.districts AS d ON d.id=CB.district_id
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
                            WHERE 		C.is_customer = FALSE
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

      $result_recs=$this->db->query($FinalQuery);

      $slno=$pg;
      $data = array();
      foreach ($result_recs->result() as $lead)
      {
        $Actions = '';
        if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_update_roles())) {
          $Actions .= '<button class="btn btn-sm btn-warning" title="Update Lead Status" onclick="open_lead_popup(' . $lead->id . ',' . $lead->lead_status_id . ')" style="margin-left: 2px;"><i class="fa fa-pencil-square-o"></i></button>';
        }
        if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_update_roles())) {
          $Actions .= '<a class="btn btn-sm btn-danger" title="Edit Lead" onclick="edit_lead(' . $lead->id . ')"  style="margin-left: 2px;color:white;"><i class="icon-android-create"></i></a>';
        }
        $Actions .= '<a class="btn btn-sm btn-success" title="Lead History" onclick="lead_history(' . $lead->id . ')"  style="margin-left: 2px;"><i class="fa fa-history"></i></a>';
        $Actions .= '<a class="btn btn-sm btn-primary" title="Additional Spoc Details" onclick="showAdditionalSpocs(' . $lead->id . ')"  style="margin-left: 2px;color:white;"><i class="fa fa-phone"></i></a>';
        if( $lead->lead_status_id==16 || $lead->lead_status_id ==21) {
          $Actions .= '<a class="btn btn-sm " title="Lead Commercials" href="'.base_url("/leads/commercials_documents/".$lead->id).'"  style="margin-left: 2px;color:white;background-color:#c72a9e;"><i class="fa fa-rupee"></i></a>';
        }
        if(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_assignment_roles())) {
          $Actions .= '<a class="btn btn-sm btn-warning" title="Assign Lead" onclick="open_placement_officer_assign_model(' . $lead->id . ')"  style="margin-left: 2px;color:white;"><i class="fa fa-tasks"></i></a>';
        }
        //in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_commercial_approve_roles()) ||

        $row=array();
        $slno++;
        $row[] = $slno;
        $row[] = $Actions;
        $row[] = $lead->customer_name;
         $row[] = $lead->buisness_vertical_name == '' ? 'N/A' : $lead->buisness_vertical_name;
        $row[] = ($lead->lead_status_id==16) ? ($lead->lead_status_name.' (Pending Approval)') : $lead->lead_status_name;
        $row[] = $lead->lead_managed_by == '' ? 'N/A' : $lead->lead_managed_by;
        $row[] = $lead->spoc_name == '' ? 'N/A' : $lead->spoc_name;
        $row[] = $lead->spoc_email == '' ? 'N/A' : $lead->spoc_email;
        $row[] = $lead->spoc_phone == '' ? 'N/A' : $lead->spoc_phone;
        $row[] = $lead->location == '' ? 'N/A' : $lead->location;
        $row[] = $lead->district == '' ? 'N/A' : $lead->district;
        $row[] = $lead->business_probability;
        $row[] = $lead->lead_source_name == '' ? 'N/A' : $lead->lead_source_name;
        $row[] = $lead->customer_description == '' ? 'N/A' : $lead->customer_description;
        $data[] = $row;
      }

      $response_data = array(
          "draw"            => intval( $requestData['draw'] ),
          "recordsTotal"    => intval( $totalData ),
          "recordsFiltered" => intval( $totalFiltered ),
          "data"            => $data
      );

      return $response_data;
    }
  }


}
