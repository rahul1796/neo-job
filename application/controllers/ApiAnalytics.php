<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiAnalytics extends CI_Controller {

  protected $token = '49515982904b81a989a53a388f9ceccd43878dfa';

  public function getBatchPlacement() {
    $this->authoriseAPI();
    $this->load->model('Api', 'api');
    $this->printResponse($this->api->getCandidateCustomer());
  }

  public function authoriseAPI() {
    $headers = $this->input->request_headers();
    if(empty($headers['api-token']) || $this->input->request_headers()['api-token'] != $this->token) // && $this->input->request_headers()['api-token'] != $this->token
    {
      $data['code'] = '401';
      $data['status'] = 'fail';
      $data['message']= 'Unauthorised Access';
      $this->printResponse($data);
    }
  }

  public function printResponse($data) {
    echo json_encode($data);
    exit;
  }
}
