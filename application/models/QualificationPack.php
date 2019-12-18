<?php

class QualificationPack extends MY_Model
{
  protected $tableName = 'neo.candidate_qp_details';

  protected $qp_detail_field  = ['center_name'=>'', 'center_type'=>'', 'batch_code'=>''];
  protected $document_detail_field  = ['document_number'=>'', 'document_type'=>''];
  protected $employment_detail_field  = ['employment_type'=>''];
  protected $education_detail_field = ['education_name'=>'', 'year_of_passing'=>'', 'technical_education'=>'', 'computer_knowledge'=>''];

  protected $candidate_detail_field = ['candidate_name'=> '', 'candidate_number'=>'', 'candidate_enrollment_id'=>'',
                                      'mobile'=>'', 'email'=>'', 'address'=>'', 'country'=>'', 'state'=>'', 'district'=>'',
                                      'pincode'=>'', 'gender_name'=>'', 'date_of_birth'=>'', 'father_name'=>'',
                                      'family_contact_number'=>'', 'language_known'=>'', 'religion'=>'', 'age'=>'',
                                      'marital_status'=>'', 'prefered_job_location'=>'', 'willing_to_travel'=>'',
                                      'willing_to_work_at_night'=>'', 'enrollment_date'=>'', 'caste_category'=>''];

  // public function get_qualification_pack() {
  //   $query = $this->db->get('master.qualification_pack');
  //   return $query->result();
  // }

  public function allByCandidate($candidate_id) {
    return $this->db->select('neo.candidate_qp_details.*, neo_master.qualification_packs.name as qp_name')->from($this->tableName)
    ->join('neo_master.qualification_packs', 'neo.candidate_qp_details.qualification_pack_id=neo_master.qualification_packs.id', 'LEFT')
    ->where('neo.candidate_qp_details.candidate_id', $candidate_id)->get()->result();
  }

  public function uploadBatchCSV($data) {
    $response_data=[];
    $row_number = 1;

    foreach($data as $row) {
      $temp_data = $row;
      $result = $this->db->where('neo.batches.batch_code', $row['batch_code'])
                      ->get('neo.batches')->result();

                      $this->db->reset_query();


                      if(count($result)==0) {

                        $this->db->trans_start();
                        $this->db->insert('neo.batches', $row);
                        $this->db->trans_complete();

                        if($this->db->trans_status()){
                            $temp_data ['status']= TRUE;
                            $temp_data ['db_log'] = 'Row inserted Successfully';
                        } else {
                          $temp_data ['status']= FALSE;
                          $temp_data ['db_log'] = 'Could not insert row';
                        }

                        $temp_data ['row_number'] = $row_number;

                      } else {
                        $temp_data ['status']= false;
                        $temp_data ['row_number'] = $row_number;
                        $temp_data ['db_log'] = 'No Duplicate Found based on Batchcode and Candidate Registration ID';

                      }
                      array_push($response_data, $temp_data);
                      $row_number++;
                    }
                    return $response_data;

  }

  public function uploadCandidates($data) {
    $response_data=[];
    $row_number = 1;
    foreach($data as $row) {

      $temp_data = $row;

      $result = $this->db->select('neo.candidates.id')->from('neo.candidates')
                      ->join('neo.candidate_qp_details', 'neo.candidates.id=neo.candidate_qp_details.candidate_id', 'LEFT')
                      ->where('neo.candidates.candidate_number', $row['candidate_number'])
                      ->where('neo.candidate_qp_details.batch_code', $row['batch_code'])
                      ->get()->result();

      $this->db->reset_query();


      if(count($result)==0) {

        $this->db->trans_start();

        $this->db->reset_query();
        $candidate_array = array_filter(array_intersect_key($row, $this->candidate_detail_field));
        if(trim($row['batch_code'])!=''){
            $this->db->reset_query();
            $candidate_array['mt_type']='MTS';
        }
        $this->db->insert('neo.candidates', $candidate_array);
        $candidate_id = $this->db->insert_id();


        $this->db->reset_query();
        $qp_array = array_filter(array_intersect_key($row, $this->qp_detail_field));
        $qp_array['candidate_id'] = $candidate_id;
        $this->db->insert('neo.candidate_qp_details', $qp_array);

        $this->db->reset_query();
        $employment_array = array_filter(array_intersect_key($row, $this->employment_detail_field));
        $employment_array['candidate_id'] = $candidate_id;
        $this->db->insert('neo.candidate_employment_details', $employment_array);

        $this->db->reset_query();
        $education_array = array_filter(array_intersect_key($row, $this->education_detail_field));
        $education_array['candidate_id'] = $candidate_id;
        $this->db->insert('neo.candidate_education_details', $education_array);

        $this->db->reset_query();
        $document_array = array_filter(array_intersect_key($row, $this->document_detail_field));
        $document_array['candidate_id'] = $candidate_id;
        $this->db->insert('neo.candidate_document_details', $document_array);

        $this->db->trans_complete();

        if($this->db->trans_status()){
            $temp_data ['status']= TRUE;
            $temp_data ['db_log'] = 'Row inserted Successfully';
        } else {
          $temp_data ['status']= FALSE;
          $temp_data ['db_log'] = 'Could not insert row';
        }

        $temp_data ['row_number'] = $row_number;

      } else {
        $temp_data ['status']= false;
        $temp_data ['row_number'] = $row_number;
        $temp_data ['db_log'] = 'No Duplicate Found based on Batchcode and Candidate Registration ID';

      }
      array_push($response_data, $temp_data);
      $row_number++;
    }
    return $response_data;
  }


//   public function getBatches()
//    {
//        return $this->db->get('neo.batches')->result();
//    }
}
