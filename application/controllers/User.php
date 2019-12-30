<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class User extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("allmodel");
    }

    public function index_get($id=null)
	{
        if($id == null){
            $where = array(
                "status" => "Active"
            );
            $user = $this->allmodel->getDataAll('t_rc_user', $where)->result();
            $status = true;
            $message = "success";
        }else{
            $user = $this->allmodel->getDataById('t_rc_user', 'user_id', (int)$id)->row();
            $status =  true;
            $message = "success";
        }

        $data = [
            'status' => $status,
            'message' => $message,
            'data' => $user
        ];

        $this->response($data, REST_Controller::HTTP_OK);
    }
    
    public function index_post(){
        $data = [
            'nama_lengkap'  => $this->post('nama_lengkap'),
            'username'      => $this->post('username'),
            'password'      => md5($this->post('password')),
            'jenis_kelamin' => $this->post('jenis_kelamin'),
            'alamat'        => $this->post('alamat'),
            'profil_pic'    => $this->post('profil_pic'),
            'ktp'           => $this->post('ktp'),
            'no_tlp_1'      => $this->post('no_tlp_1'),
            'no_tlp_2'      => $this->post('no_tlp_2'),
            'status'        => "Active",
            'role'          => $this->post('role'),
            'cre_dt'        => date('Y-m-d')
        ];

        if($this->allmodel->saveData('t_rc_user', $data) == true){
            $data = [
                'status' => true,
                'message' => 'success user has been created'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed user has been created'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put(){
        $data = [
            'user_id'       => $this->put('user_id'),
            'nama_lengkap'  => $this->put('nama_lengkap'),
            'jenis_kelamin' => $this->put('jenis_kelamin'),
            'alamat'        => $this->put('alamat'),
            'ktp'           => $this->put('ktp'),
            'no_tlp_1'      => $this->put('no_tlp_1'),
            'role'          => $this->put('role'),
            'username'      => $this->put('username')
        ];
        if($this->allmodel->updateData('t_rc_user', $data, 'user_id', $this->put('user_id')) == true) {
            $data = [
                'status' => true,
                'message' => 'success user has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed user has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }

        
    }

    public function index_delete($id){
        // $this->response($id,REST_Controller::HTTP_OK);
        $data = [
            'user_id'       => $id,
            'status'        => 'Inactive'
        ];
        if($this->allmodel->updateData('t_rc_user', $data, 'user_id', (int)$id) == true) {
            $data = [
                'status' => true,
                'message' => 'success user has been deleted'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed user has been deleted'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}