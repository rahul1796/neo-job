<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Center extends MY_Model
{
  protected $tableName = 'neo_user.centers';
  private $limit = 25;
  private $exclude_fields = ['center_managers'=>''];

  public function find($id) {
    return $this->db->where('id', $id)->get($this->tableName)->row_array();
  }

  public function getCenterTypes() {
    return $this->db->select('DISTINCT("center_type") as name')->from($this->tableName)->get()->result();
  }

  public function usersByCenterManagerRole() {
    return $this->db->where_in('user_role_id', [9])->get('neo_user.users')->result();
  }

  public function getAssociatedUsers($id) {
    $query = $this->db->where('center_id', $id)->get('neo_user.centers_users')->result_array();
    return array_column($query, 'user_id');
  }

  public function all() {
    return $this->db->get($this->tableName)->result();
  }

  public function save($data) {
    $this->db->trans_start();

    $this->db->insert($this->tableName, array_filter(array_diff_key($data, $this->exclude_fields), array($this, 'nonZeroFilter')));
    $center_id = $this->db->insert_id();

    $this->db->reset_query();
    $this->replaceCenterUsers($center_id, $data['created_by'], $data['center_managers']);
    $this->db->trans_complete();

    return $this->db->trans_status();
  }

  public function update($id, $data) {
    $this->db->trans_start();

    $this->db->where('id', $id);
    $this->db->update($this->tableName, array_filter(array_diff_key($data, $this->exclude_fields), array($this, 'nonZeroFilter')));

    $this->db->reset_query();
    $this->replaceCenterUsers($id, $data['updated_by'], $data['center_managers']);
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  public function replaceCenterUsers($center_id, $created_by, $data) {
    $this->db->reset_query();
    $this->db->delete('neo_user.centers_users', ['center_id'=>$center_id]);
    foreach($data as $id) {
      $row = array();
      $row['user_id'] = $id;
      $row['center_id'] = $center_id;
      $row['created_by'] = $created_by;
      $this->db->reset_query();
      $this->db->insert('neo_user.centers_users', $row);
    }
  }


  public function uploadCenterCSV($data) {
    $response_data=[];
    $row_number = 1;

    foreach($data as $row) {
      $temp_data = $row;
      $result = $this->db->where('neo_user.centers.center_id', $row['center_id'])
                      ->where('neo_user.centers.center_type', $row['center_type'])
                      ->get('neo_user.centers')->result();

                      $this->db->reset_query();


                      if(count($result)==0) {

                        $this->db->trans_start();
                        $this->db->insert('neo_user.centers', $row);
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
                        $temp_data ['db_log'] = 'No Duplicate Found based on Center ID and Center Name';

                      }
                      array_push($response_data, $temp_data);
                      $row_number++;
                    }
                    return $response_data;

  }

}
