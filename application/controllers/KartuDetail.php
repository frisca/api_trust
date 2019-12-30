<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class KartuDetail extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("allmodel");
    }

    public function index_get($id=null)
	{
        if($id == null){
            $card = $this->allmodel->getAll('t_rc_e_card_detail')->result();
            $status = true;
            $message = "success";
        }else{
            $card = $this->allmodel->getDataById('t_rc_e_card_detail', 'detail_no', (int)$id)->row();
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
            'e_card_no'                                => $this->post('e_card_no'),
            'car_no'                                   => $this->post('car_no'),
            'model'                                    => $this->post('model'),
            'sisa_etol'                                => $this->post('sisa_etol'),
            'insert_dt'                                => $this->post('insert_dt'),
            'e_card_jenis'                             => $this->post('e_card_jenis')
        ];

        if($this->allmodel->saveData('t_rc_e_card_detail', $data) == true){
            $data = [
                'status' => true,
                'message' => 'success card detail has been created'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed card detail in been created'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put(){
        $data = [
            'detail_no'                                => $this->put('detail_no'),
            'e_card_no'                                => $this->put('e_card_no'),
            'car_no'                                   => $this->put('car_no'),
            'model'                                    => $this->put('model'),
            'sisa_etol'                                => $this->put('sisa_etol'),
            'insert_dt'                                => $this->put('insert_dt'),
            'e_card_jenis'                             => $this->put('e_card_jenis')
        ];
        if($this->allmodel->updateData('t_rc_e_card_detail', $data, 'detail_no', $this->put('detail_no')) == true) {
            $data = [
                'status' => true,
                'message' => 'success card detail has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed card detail has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete($id){
        $data = [
            'detail_no'  => $id
        ];
        if($this->allmodel->deleteData('t_rc_card_detail', 'detail_no', (int)$id) == true) {
            $data = [
                'status' => true,
                'message' => 'success card detail has been deleted'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed card detail has been deleted'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}