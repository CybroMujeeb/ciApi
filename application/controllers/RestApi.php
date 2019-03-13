<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 use Restserver\Libraries\REST_Controller;
require(APPPATH . 'libraries/REST_Controller.php');
 //use Restserver\Libraries\REST_Controller;
   class RestApi extends REST_Controller{
       
       public function __construct() {
               parent::__construct();
               $this->load->model('user_model');
               $this->load->model('authenticate');
 
       }

       public function login_post(){

          $userDetails = $this->authenticate->Login();

            if(!$userDetails)
            {
              
              $this->response(array('status' => 0, 'message' => 'Invalid Login', 'code' => 401), 401);

            }elseif(!$userDetails->login_status){

              $this->response(array('status' => 0, 'message' => 'Account is Inactive', 'code' => 423), 423);

            }else if(!empty($userDetails)){

              $response = array('status' => 1, 'message' => 'successfully logged in', 'userdetails' => JWT::encode($userDetails, config_item('encryption_key')));

              $this->response($response, 202);
              
            }

            $this->response(array('status' => 0, 'message' => 'Bad Gateway', 'code' => 502), 502);

       }


       public function patients_post(){

          $userdetails = $this->authenticate->validateToken($this->post('userToken'));

          if(!$userdetails){

            $this->response(array('status' => 0, 'message' => 'Network authentication Required', 'code' => 511), 511);

          }

          if(!$this->authenticate->User($userdetails->email_id, $userdetails->password)){

            $this->response(array('status' => 0, 'message' => 'Un authorized', 'code' => 401), 401);
            
          }

          $limit = $this->post('limit') ? $this->post('limit') : 0;

          $offset = $this->post('offset') ? $this->post('offset') : 0;

          $patients = $this->user_model->getAllPatients(array('limit' => $limit, 'start' => $offset));

          if(empty($patients)){

            $this->response(array('status' => 0, 'message' => 'No Content', 'code' => 204), 204);

          }

          $this->response($patients, 202);

          //$this->input->request_headers()

       }


       public function patient_post(){

          $userdetails = $this->authenticate->validateToken($this->post('userToken'));

          if(!$userdetails){

            $this->response(array('status' => 0, 'message' => 'Network authentication Required', 'code' => 511), 511);

          }

          if(!$this->authenticate->User($userdetails->email_id, $userdetails->password)){

            $this->response(array('status' => 0, 'message' => 'Invalid authentication', 'code' => 401), 401);
            
          }

          $patientid = $this->post('patientid');

          $patients = $this->user_model->getPatient($patientid);

          if(empty($patients)){

            $this->response(array('status' => 0, 'message' => 'No Content', 'code' => 204), 204);
            
          }

          $this->response($patients, 202);

       }
       
       public function user_get(){
           $r = $this->user_model->read();
           $this->response($r); 
       }

       public function user_put(){
           $id = $this->uri->segment(3);
 
           $data = array(
            'name' => $this->input->get('name'),
            'pass' => $this->input->get('pass'),
            'type' => $this->input->get('type')
           );
 
            $r = $this->user_model->update($id,$data);
               $this->response($r); 
       }
 
       public function user_post(){
           $data = array(
           'name' => $this->input->post('name'),
           'pass' => $this->input->post('pass'),
           'type' => $this->input->post('type')
           );
           $r = $this->user_model->insert($data);
           $this->response($r); 
       }
       public function user_delete(){
           $id = $this->uri->segment(3);
           $r = $this->user_model->delete($id);
           $this->response($r); 
       }

       
 
}