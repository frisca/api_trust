<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Login extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("allmodel");
    }
    
    public function index_post(){
        $req = [
            'username'               => $this->post('username'),
            'password'               => md5($this->post('password'))
        ];

        $result = $this->allmodel->getDataRows('t_rc_user', $req);

        if ($result->num_rows() > 0){
            $status  = true;
            $message = "success";
            $user    = $result->row();
        }else{
            $status = false;
            $message = "invalid username and password";
            $user    = "";
        }

        $data = [
            'status'  => $status,
            'message' => $message,
            'data'    => $user
        ];

        $this->response($data, REST_Controller::HTTP_OK);
    }
}