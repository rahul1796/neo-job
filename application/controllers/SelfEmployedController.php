<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class SelfEmployedController extends MY_Controller {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SelfEmployedCandidate','selfemployedcandidate');
    } 
    
 
    public function selfemployedlist()
    {
        $list = $this->selfemployedcandidate->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $selfemployedcandidate) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $selfemployedcandidate->region_name;
            $row[] = $selfemployedcandidate->batch_code;
            $row[] = $selfemployedcandidate->center_name;
            $row[] = $selfemployedcandidate->enrollment_no;
            $row[] = $selfemployedcandidate->batch_customer_name;
            $row[] = $selfemployedcandidate->candidate_name;
            $row[] = $selfemployedcandidate->qualification_pack;
            $row[] = $selfemployedcandidate->self_employment_start_date?? 'N/A';
            $row[] = $selfemployedcandidate->document_uploaded_on;
            if (trim($selfemployedcandidate->file_name)!='NA'){
                $row[] = ($selfemployedcandidate->file_name) ? '<center><b><a class="btn btn-sm btn-primary" href="'. base_url(). CUSTOMER_DOCUMENT_PATH .$selfemployedcandidate->file_name.'" title="Download Document">' . $selfemployedcandidate->file_name . '</a></b></center>' : '<center>'.$selfemployedcandidate->file_name.'</center>';
            }				
            elseif(trim($selfemployedcandidate->file_name)=='NA'){
                $row[] = $selfemployedcandidate->file_name;
            }	
 
            $data[] = $row;
           
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->selfemployedcandidate->count_all(),
                        "recordsFiltered" => $this->selfemployedcandidate->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function export_csv(){ 
		// file name 
		$filename = 'Selfemployed_Candidate_'.date('d-M').'.csv'; 
		header("Content-Description: File Transfer"); 
		header("Content-Disposition: attachment; filename=$filename"); 
		header("Content-Type: application/csv; ");
	   // get data 
		$usersData = $this->selfemployedcandidate->getSelfEmployedDetails();
		// file creation 
		$file = fopen('php://output','w');
		$header = array("Region","Batch Code","Center Name","Batch Customer Name", "Candidate name", "Qualification Pack", "Document Uploaded On", "File Link"); 
		fputcsv($file, $header);
		foreach ($usersData as $key=>$line){ 
			fputcsv($file,$line); 
		}
		fclose($file); 
		exit; 
	}

}