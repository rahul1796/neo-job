<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class SelfEmployedCandidate extends MY_Model {
 
    var $table = 'neo.vw_self_employed_candidate_list';
    var $column_order = array(null, 'region_name','batch_code','center_name','enrollment_no','batch_customer_name','candidate_name','qualification_pack','self_employment_start_date','document_uploaded_on'); //set column field database for datatable orderable
    var $column_search = array('employment_start_date','center_name','batch_code','qualification_pack','enrollment_no'); //set column field database for datatable searchable 
    var $order = array('employment_start_date'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
         
        //add custom filter here
        if($this->input->post('employment_start_date'))
        {
            $this->db->where('employment_start_date', $this->input->post('employment_start_date'));
        }
        if($this->input->post('center_name'))
        {
            $this->db->where('center_name', $this->input->post('center_name'));
        }
        if($this->input->post('batch_code'))
        {
            $this->db->like('batch_code', $this->input->post('batch_code'));
        }
        if($this->input->post('qualification_pack'))
        {
            $this->db->like('qualification_pack', $this->input->post('qualification_pack'));
        }
        if($this->input->post('enrollment_no'))
        {
            $this->db->like('enrollment_no', $this->input->post('enrollment_no'));
        }
 
        $this->db->from($this->table);
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    public function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
   
    function getSelfEmployedDetails(){
        $response = array();
        $path = base_url(CUSTOMER_DOCUMENT_PATH);
       $this->db->select("region_name,batch_code,center_name,batch_customer_name, candidate_name, qualification_pack, document_uploaded_on, CONCAT('=HYPERLINK(\"', '{$path}', file_name,'\")') AS file_name");
       $q = $this->db->get('neo.vw_self_employed_candidate_list');
       $response = $q->result_array();
        return $response;
   }
    
}