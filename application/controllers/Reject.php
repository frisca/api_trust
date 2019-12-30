<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Reject extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("allmodel");
    }

    public function index_put(){
        $data = [
            'out_no'                 => $this->put('out_no'),
            'status'                 => 'Ready',
            'progress'               => 'Reject'
        ];
        if($this->allmodel->updateData('t_rc_car_out', $data, 'out_no', $this->put('out_no')) == true) {
            // $data = [
            //     'status' => true,
            //     'message' => 'success car out has been updated'
            // ];
            // $this->response($data, REST_Controller::HTTP_OK);
            $cars = array(
                'car_no' => $this->put('car_no'),
                'status_mobil' => "Ready"
            );

            if($this->allmodel->updateData('t_rc_car', $cars, 'car_no', $this->put('car_no')) == true) {
                $data = [
                    'status' => true,
                    'message' => 'success car out has been rejected'
                ];
                $this->response($data, REST_Controller::HTTP_OK);
            }else{
                $data = [
                    'status' => false,
                    'message' => 'failed car out has been rejected'
                ];
                $this->response($data, REST_Controller::HTTP_FAILED);
            }
        }else{
            $data = [
                'status' => false,
                'message' => 'failed car out has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}