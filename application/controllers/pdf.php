<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf extends CI_Controller {
public function __construct(){
	parent::__construct();
	 $this->load->model('Company', 'company');
}
	public function index($company_id=0)
	{
		 $opportunity_results=$this->company->getOpportunityList_pdf_download($company_id); 	

		$mpdf = new \Mpdf\Mpdf();		
		$data["opportunity_results"] = $opportunity_results;		
		$mpdf->SetFooter('Document Title');
		$html=$this->load->view('welcome_message',$data,true);
		$mpdf->WriteHTML($html);
		$mpdf->Output($opportunity_results[0]['company_name'].'-opportunity-list.pdf','D');
	}

	public function contract($company_id=0)
	{
		 $contract_results=$this->company->getContractList_pdf_download($company_id); 
		$mpdf = new \Mpdf\Mpdf();		
		$data["contract_results"] = $contract_results;		
		$mpdf->SetFooter('Document Title');
		$html=$this->load->view('contracts',$data,true);
		$mpdf->WriteHTML($html);
		$mpdf->Output($contract_results[0]['company_name'].'-contract_list.pdf','D');
	}
}
