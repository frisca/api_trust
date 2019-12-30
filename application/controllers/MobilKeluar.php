<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class MobilKeluar extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("allmodel");
    }

    public function index_get($id=null)
	{
        if($id == null){
            $car = $this->allmodel->getMobilKeluar()->result();
            $status = true;
            $message = "success";
        }else{
            $car = $this->allmodel->getDataById('t_rc_car_out', 'out_no', (int)$id)->row();
            $status =  true;
            $message = "success";
        }

        $data = [
            'status'    => $status,
            'message'   => $message,
            'data'      => $car
        ];

        $this->response($data, REST_Controller::HTTP_OK);
    }
    
    public function index_post(){
        $data = [
            'out_dt'                 => $this->post('out_dt'),
            'km_awal'                => (int)$this->post('km_awal'),
            'tujuan'                 => $this->post('tujuan'),
            'car_no'                 => (int)$this->post('car_no'),
            'user_id'                => (int)$this->post('user_id'),
            'status'                 => $this->post('status'),
            'progress'               => $this->post('progress')
        ];

        if($this->allmodel->saveData('t_rc_car_out', $data) == true){
            $cars = array(
                'car_no' => $this->post('car_no'),
                'status_mobil' => "Request"
            );

            if($this->allmodel->updateData('t_rc_car', $cars, 'car_no', $this->post('car_no')) == true) {
                $data = [
                    'status' => true,
                    'message' => 'success car out has been created'
                ];
                $this->response($data, REST_Controller::HTTP_OK);
            }else{
                $data = [
                    'status' => false,
                    'message' => 'failed car out has been created'
                ];
                $this->response($data, REST_Controller::HTTP_FAILED);
            }
        }else{
            $data = [
                'status' => false,
                'message' => 'failed car out been created'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put(){
        $data = [
            'out_no'                 => $this->put('out_no'),
            'out_dt'                 => $this->put('out_dt'),
            'km_awal'                => $this->put('km_awal'),
            'tujuan'                 => $this->put('tujuan')
        ];
        if($this->allmodel->updateData('t_rc_car_out', $data, 'out_no', $this->put('out_no')) == true) {
            $data = [
                'status' => true,
                'message' => 'success car out has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed car out has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete($id){
        $data = [
            'out_no'  => $id
        ];
        if($this->allmodel->deleteData('t_rc_car_out', 'out_no', (int)$id) == true) {
            $data = [
                'status' => true,
                'message' => 'success car out has been deleted'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed car out has been deleted'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}