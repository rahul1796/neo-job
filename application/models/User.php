<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Model
{
  protected $tableName = 'neo_user.users';
  private $exclude_fields = ['centers'=>''];

  public function findEmail($email) {
    $query = $this->db->where('LOWER(email)', strtolower($email))->where('is_active', true)->get('neo_user.users')->result();
    if(count($query) == 1) {
      return true;
    }
    return false;
  }

  public function updatePassword($email, $password) {
    $this->db->query("UPDATE neo_user.users SET pwd = ? WHERE	email=?", [$password, $email]);
    if($this->db->affected_rows() == 1 ) {
      return true;
    }
    return false;
  }
  
  // public function updateUserPassword($id,$confirmpassword) {   
  //   $this->db->query("UPDATE neo_user.users SET pwd = ? WHERE	id=?", [$confirmpassword, $id]);
  //   if($this->db->affected_rows() == 1 ) {
  //     return true;
  //   }
  //   return false;
  // }

  public function getReportingManager($id) {
    $this->db->reset_query();
    $result = $this->db->select('neo_user.users.id as id, neo_user.users.name as name, neo_user.user_roles.name as role_name')
              ->from('neo_user.users')
              ->join('neo_user.user_roles', 'neo_user.user_roles.id=neo_user.users.user_role_id')
              ->where('user_role_id', $id)
              ->where('is_active', TRUE)->get()->result();

    // if(count($result)==0){
    //     $this->db->reset_query();
    //     $role_id = $this->db->where('id', $id)->get('neo_user.user_roles')->row();
    //     return $this->getReportingManager($role_id->reporting_manager_role_id);
    // }
    return $result;
  }

  public function getReportingManagerRoles($id) {
    $roles_id = $this->db->query('select * from neo_user.fn_get_recursive_reporting_manager_roles(?)', $id)->result_array();
    $this->db->reset_query();
    return $this->db->where_in('id', array_column($roles_id, 'user_role_id'))->get('neo_user.user_roles')->result();
  }

  // public function getReportingManager($id) {
  //   $this->db->reset_query();
  //   $result = $this->getReportingManagerQuery([$id]);
  //
  //   if(count($result)==0){
  //       $this->db->reset_query();
  //       $role_id = $this->db->query('select * from neo_user.fn_get_recursive_reporting_manager_roles(?)', $id)->result_array();
  //       $this->db->reset_query();
  //       $result = $this->getReportingManagerQuery(array_column($role_id, 'user_role_id'));
  //   }
  //   return $result;
  // }

  public function getReportingManagerQuery($ids) {
    return $this->db->select('neo_user.users.id as id, neo_user.users.name as name, neo_user.user_roles.name as role_name')
              ->from('neo_user.users')
              ->join('neo_user.user_roles', 'neo_user.user_roles.id=neo_user.users.user_role_id')
              ->where('is_active', TRUE)
              ->where_in('user_role_id', $ids)
              ->get()->result();
  }

  public function save($data){
    $this->db->trans_start();

    $this->db->insert($this->tableName, array_filter(array_diff_key($data, $this->exclude_fields), array($this, 'nonZeroFilter')));
    $user_id = $this->db->insert_id();
    if(in_array($data['user_role_id'], [9,11,14,12,13])) {
      $this->db->reset_query();
      $this->replaceCenterUsers($user_id, $data['created_by'], $data['centers']);
    } else {
      $this->detachCenters($user_id);
    }
    $this->db->trans_complete();

    return $this->db->trans_status();
  }

  public function update($id, $data) {
    $this->db->trans_start();

    $this->db->where('id', $id);
    $this->db->update($this->tableName, array_filter(array_diff_key($data, $this->exclude_fields), array($this, 'nonZeroFilter')));
    if(in_array($data['user_role_id'], [9,11,14,12,13])) {
      $this->db->reset_query();
      $this->replaceCenterUsers($id, $data['updated_by'], $data['centers']);
    } else {
      $this->detachCenters($id);
    }
    $this->db->trans_complete();
    return $this->db->trans_status();

  }

  public function replaceCenterUsers($user_id, $created_by, $data) {
    $this->db->reset_query();
    $this->db->delete('neo_user.centers_users', ['user_id'=>$user_id]);
    foreach($data as $id) {
      $row = array();
      $row['center_id'] = $id;
      $row['user_id'] = $user_id;
      $row['created_by'] = $created_by;
      $this->db->reset_query();
      $this->db->insert('neo_user.centers_users', $row);
    }
  }

  public function detachCenters($user_id) {
    $this->db->reset_query();
    $this->db->delete('neo_user.centers_users', ['user_id'=>$user_id]);
  }

  public function getAssociatedCenters($id) {
    $query = $this->db->where('user_id', $id)->get('neo_user.centers_users')->result_array();
    return array_column($query, 'center_id');
  }

  public function getUserCurrentPassword($id)
  {
      $this->db->where('id', $id);
      $query = $this->db->get('neo_user.users');       
      return $query->row();
  }
 

  public function checkOldPassword($id,$oldpass)
  { 
     $query= $this->db->query("SELECT * FROM neo_user.fn_check_password_validity(?,?) AS counter", [$id, $oldpass])->row_array();
     return $query;
  }
 

   public function updateUserPassword($id, $userdata)
   {
       $this->db->where('id', $id);
       $this->db->update('neo_user.users', $userdata);
      if($this->db->affected_rows() == 1 ) {
        return true;
      }
      return false;
   }

}
