<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Commercial extends MY_Model {

    protected $tableName = 'neo_customer.customer_commercials';

    protected $documentTable = 'neo_customer.customer_documents';

    protected $opportunityTable = 'neo_customer.opportunities';

    public function saveCommercials($id, $data, $data_document) {
      $customer_old_data = $this->db->where('id', $id)->get($this->opportunityTable)->row_array();
      $this->db->reset_query();
      $customer_data['has_commercial'] = TRUE;
      $customer_data['is_contract'] = FALSE;
      $customer_data['lead_status_id'] = 16;
      $this->db->trans_start();
      if($this->db->where('customer_id', $id)->get($this->tableName)->num_rows()>0){
        $this->db->reset_query();
        $this->db->where('customer_id', $id)->delete($this->tableName);
      }
      $this->db->reset_query();
      $this->db->insert_batch($this->tableName, $data);
      $this->db->reset_query();
      $this->db->where('id', $id)->update($this->opportunityTable, $customer_data);

      if($customer_old_data['lead_status_id']!=16) {
        $lead_logs_data['lead_status_id'] = 16;
        $lead_logs_data['customer_id'] = $customer_old_data['id'];
        $lead_logs_data['remarks'] = 'Commercials & Document Updated';
        $lead_logs_data['created_by'] = $this->session->userdata('usr_authdet')['id'];
        $this->db->reset_query();
        $this->db->insert('neo_customer.lead_logs',$lead_logs_data);
      }

      if(!empty($data_document['file_name'])){
          $this->saveDocument($id, $data_document);
      }

      $this->db->trans_complete();
      return $this->db->trans_status();
    }

    public function saveDocument($id, $data) {
      $customer_data['has_documents'] = TRUE;
      $this->db->reset_query();
      $this->db->insert($this->documentTable, $data);
      $this->db->reset_query();
      $this->db->update($this->opportunityTable, $customer_data);
    }

    public function findDocument($id) {
      return $this->db->where('customer_id', $id)->get($this->documentTable)->result();
    }

    public function getCommercials($id) {
      return $this->db->where('customer_id', $id)->get($this->tableName)->result_array();
    }


      public function verfied_customer($id, $request) {
        $data['legally_verified'] = FALSE;
        $data['is_contract'] = FALSE;
        $data['updated_by'] = $this->session->userdata('usr_authdet')['id'];
        $data['updated_at'] = date('Y-m-d H:i:s');

        $logs_data['customer_id'] = $id;
        $logs_data['remarks'] = $request['remarks'];
        $logs_data['created_by'] = $this->session->userdata('usr_authdet')['id'];

        $this->db->trans_start();

        if($request['status']=='accept') {
          $data['legally_verified'] = TRUE;
          $data['is_contract'] = TRUE;
          $logs_data['lead_status_id'] = 20;

          $this->db->reset_query();
          $this->db->where('id', $id);
          $this->db->update($this->opportunityTable, $data);

          $this->db->reset_query();
          $this->db->insert('neo_customer.lead_logs', $logs_data);

          $data['lead_status_id'] = 22;
          $logs_data['lead_status_id'] = 22;

        } else {
          $data['lead_status_id'] = 21;
          $logs_data['lead_status_id'] = 21;
        }

        $this->db->reset_query();
        $this->db->where('id', $id);
        $this->db->update($this->opportunityTable, $data);

        $this->db->reset_query();
        $this->db->insert('neo_customer.lead_logs', $logs_data);

        $this->db->trans_complete();
        return $this->db->trans_status();
      }

      public function deleteCustomerDocument($customer_id, $id) {
        $query = $this->db->where('customer_id', $customer_id)->where('id', $id)->get($this->documentTable)->num_rows();
        if($query==1) {
          $this->db->delete($this->documentTable, ['customer_id'=>$customer_id, 'id' => $id]);
          if($this->db->affected_rows() == 1 ) {
            return true;

          }
        }
        return false;
      }

      public function getCommercialsByCustomerID($id) {
        return $this->db->where('customer_id', $id)->get($this->tableName)->result();
      }
}
