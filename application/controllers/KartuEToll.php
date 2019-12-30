<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class KartuEToll extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("allmodel");
    }

    public function index_get($id=null)
	{
        if($id == null){
            $where = array("status"=>"Active");
            $card = $this->allmodel->getDataAll('t_rc_e_card', $where)->result();
            $status = true;
            $message = "success";
        }else{
            $card = $this->allmodel->getDataById('t_rc_e_card', 'e_card_no', (int)$id)->row();
            $status =  true;
            $message = "success";
        }

        $data = [
            'status'    => $status,
            'message'   => $message,
            'data'      => $card
        ];

        $this->response($data, REST_Controller::HTTP_OK);
    }
    
    public function index_post(){
        $data = [
            'e_card_jenis'           => $this->post('e_card_jenis'),
            'e_card_code'            => $this->post('e_card_code'),
            'status'                 => $this->post('status')
        ];

        if($this->allmodel->saveData('t_rc_e_card', $data) == true){
            $data = [
                'status' => true,
                'message' => 'success e-card has been created'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed e-card has been created'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put(){
        $data = [
            'e_card_no'              => $this->put('e_card_no'),
            'e_card_jenis'           => $this->put('e_card_jenis'),
            'e_card_code'            => $this->put('e_card_code')
        ];
        if($this->allmodel->updateData('t_rc_e_card', $data, 'e_card_no', $this->put('e_card_no')) == true) {
            $data = [
                'status' => true,
                'message' => 'success e-car has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed e-car has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete($id){
        $data = [
            'e_card_no'        => $id,
            'status'           => 'Inactive'
        ];
        if($this->allmodel->updateData('t_rc_e_card', $data, 'e_card_no', (int)$id) == true) {
            $data = [
                'status' => true,
                'message' => 'success e-card has been deleted'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed e-card has been deleted'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}