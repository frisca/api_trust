<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Kendaraan extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("allmodel");
    }

    public function index_get($id=null)
	{
        if($id == null){
            $where = array("status" => "Active");
            $car = $this->allmodel->getDataAll('t_rc_car', $where)->result();
            $status = true;
            $message = "success";
        }else{
            $car = $this->allmodel->getDataById('t_rc_car', 'car_no', (int)$id)->row();
            $status =  true;
            $message = "success";
        }

        $data = [
            'status' => $status,
            'message' => $message,
            'data' => $car
        ];

        $this->response($data, REST_Controller::HTTP_OK);
    }
    
    public function index_post(){
        $data = [
            'no_plat'           => $this->post('no_plat'),
            'nama_pemilik'      => $this->post('nama_pemilik'),
            'alamat'            => $this->post('alamat'),
            'merk'              => $this->post('merk'),
            'type'              => $this->post('type'),
            'jenis'             => $this->post('jenis'),
            'model'             => $this->post('model'),
            'tahun'             => $this->post('tahun'),
            'warna'             => $this->post('warna'),
            'no_rangka'         => $this->post('no_rangka'),
            'no_mesin'          => $this->post('no_mesin'),
            'no_bpkb'           => $this->post('no_bpkb'),
            'status_mobil'      => $this->post('status_mobil'),
            'status'            => $this->post('status')
        ];

        if($this->allmodel->saveData('t_rc_car', $data) == true){
            $data = [
                'status' => true,
                'message' => 'success car has been created'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed car has been created'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put(){
        $data = [
            'car_no'            => $this->put('car_no'),
            'no_plat'           => $this->put('no_plat'),
            'nama_pemilik'      => $this->put('nama_pemilik'),
            'alamat'            => $this->put('alamat'),
            'merk'              => $this->put('merk'),
            'type'              => $this->put('type'),
            'jenis'             => $this->put('jenis'),
            'model'             => $this->put('model'),
            'tahun'             => $this->put('tahun'),
            'warna'             => $this->put('warna'),
            'no_rangka'         => $this->put('no_rangka'),
            'no_mesin'          => $this->put('no_mesin'),
            'no_bpkb'           => $this->put('no_bpkb')
        ];
        if($this->allmodel->updateData('t_rc_car', $data, 'car_no', $this->put('car_no')) == true) {
            $data = [
                'status' => true,
                'message' => 'success car has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed car has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }

        
    }

    public function index_delete($id){
        $data = [
            'car_no'        => $id,
            'status'        => 'Inactive'
        ];
        if($this->allmodel->updateData('t_rc_car', $data, 'car_no', (int)$id) == true) {
            $data = [
                'status' => true,
                'message' => 'success car has been deleted'
            ];
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [
                'status' => false,
                'message' => 'failed car has been deleted'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}