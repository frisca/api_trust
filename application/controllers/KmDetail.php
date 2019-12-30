<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class KmDetail extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("allmodel");
    }

    public function index_get($id=null)
	{
        if($id == null){
            $card = $this->allmodel->getDataAll('t_rc_km_detail')->result();
            $status = true;
            $message = "success";
        }else{
            $card = $this->allmodel->getDataById('t_rc_km_detail', 'km_no', (int)$id)->row();
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
            'car_no'                                => (int)$this->post('car_no'),
            'model'                                 => $this->post('model'),
            'out_no'                                => (int)$this->post('out_no'),
            'km_awal'                               => (int)$this->post('km_awal'),
            'km_akhir'                              => (int)$this->post('km_akhir'),
            'total_jarak_tempuh'                    => (int)$this->post('total_jarak_tempuh')
        ];

        if($this->allmodel->saveData('t_rc_km_detail', $data) == true){
            $data = [
                'status' => true,
                'message' => 'success km detail has been created'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed km detail in been created'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put(){
        $data = [
            'km_no'                     => $this->put('km_no'),
            'car_no'                    => $this->put('car_no'),
            'model'                     => $this->put('model'),
            'out_no'                    => $this->put('out_no'),
            'km_awal'                   => $this->put('km_awal'),
            'km_akhir'                  => $this->put('km_akhir'),
            'in_no'                     => $this->put('in_no'),
            'total_jarak_tempuh'        => $this->put('total_jarak_tempuh')
        ];
        if($this->allmodel->updateData('t_rc_km_detail', $data, 'km_no', $this->put('km_no')) == true) {
            $data = [
                'status' => true,
                'message' => 'success km detail has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed km detail has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete($id){
        $data = [
            'in_no'  => $id
        ];
        if($this->allmodel->deleteData('t_rc_ckm_detail', 'km_no', (int)$id) == true) {
            $data = [
                'status' => true,
                'message' => 'success km detail has been deleted'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed km detail has been deleted'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}