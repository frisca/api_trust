<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class MobilKembali extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("allmodel");
    }

    public function index_get($id=null)
	{
        if($id == null){
            $card = $this->allmodel->getMobilKembali()->result();
            $status = true;
            $message = "success";
        }else{
            $card = $this->allmodel->getDataById('t_rc_car_in', 'in_no', (int)$id)->row();
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
        $card_jenis = "";
        $out_no = null;
        $km_awal = null;
        // $tanggal_keluar = "";

        $outs = $this->allmodel->getMobilKeluar()->result();
        if(!empty($outs)){
            foreach($outs as $v){
                if($v->car_no == (int)$this->post('car_no') && $v->status == "In Use" && $v->user_id == (int)$this->post('user_id')){
                    $out_no = (int)$v->out_no;
                }
                
            }
        }

        $data = [
            'in_dt'                     => $this->post('in_dt'),
            'out_no'                    => (int)$out_no,
            'km_akhir'                  => (int)$this->post('km_akhir'),
            'car_no'                    => (int)$this->post('car_no'),
            'model'                     => '',
            'user_id'                   => (int)$this->post('user_id'),
            'status'                    => $this->post('status'),
            'keterangan'                => ''
        ];

        
        $out = array(
            // "out_no" => $this->post("out_no"),
            "out_no" => (int)$out_no,
            "status" => "Done",
            "progress" => "Approve"
        );

        $car = array(
            "status_mobil" => "Ready"
        );

        if($this->allmodel->saveData('t_rc_car_in', $data) == true){
            $in = $this->allmodel->getDataByDesc('t_rc_car_in', 'car_no', 'desc', 1)->row();
            $cards = $this->allmodel->getAll('t_rc_e_card')->result();

            if(!empty($cards)){
                foreach($cards as $card){
                    if($card->e_card_code == $this->input->post('e_card_code') && $card->status == "Active"){
                        $card_jenis = $card->e_card_jenis;
                    }
                }
            }

            $km_awals = $this->allmodel->getDataById('t_rc_car_out', 'out_no', (int)$out_no)->row();

            $req_km = array(
                'car_no'                                => (int)$this->post("car_no"),
                'model'                                 => '',
                'out_no'                                => (int)$out_no,
                'in_no'                                 => $in->in_no,
                'km_awal'                               => (int)$km_awals->km_awal,
                'km_akhir'                              => (int)$this->post('km_akhir'),
                'total_jarak_tempuh'                    => (int)$km_awals->km_awal + (int)$this->input->post('km_akhir')
            );
            

            if($this->allmodel->saveData('t_rc_km_detail', $req_km) == true){
                if($this->allmodel->updateData('t_rc_car_out', $out, 'out_no', (int)$out_no) == true) {
                    if($this->allmodel->updateData('t_rc_car', $car, 'car_no', $this->post('car_no')) == true) {
                        // $data = [
                        //     'status' => true,
                        //     'message' => 'success car in has been created'
                        // ];
                        // $this->response($data, REST_Controller::HTTP_OK);
                        $req_card = array(
                            'e_card_code'  => $this->post('e_card_code'),
                            'car_no'  => (int)$this->post('car_no'),
                            'sisa_etol' => (int)$this->post('sisa_etol'),
                            'insert_dt' => date("Y-m-d"),
                            'e_card_jenis' => $card_jenis
                        );
                        if($this->allmodel->saveData('t_rc_e_card_detail', $req_card) == true){
                            $data = [
                                'status' => true,
                                'message' => 'success car in has been created'
                            ];
                            $this->response($data, REST_Controller::HTTP_OK);
                        }else{
                            $data = [
                                'status' => false,
                                'message' => 'failed car in been created'
                            ];
                            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
                        }
                    }else{
                        $data = [
                            'status' => false,
                            'message' => 'failed car in been created'
                        ];
                        $this->response($data, REST_Controller::HTTP_NOT_FOUND);
                    }
                }else{
                    $data = [
                        'status' => false,
                        'message' => 'failed car in been created'
                    ];
                    $this->response($data, REST_Controller::HTTP_NOT_FOUND);
                }
            }
        }else{
            $data = [
                'status' => false,
                'message' => 'failed car in been created'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put(){
        // $data = [
        //     'in_no'                     => $this->put('in_no'),
        //     'in_dt'                     => $this->put('in_dt'),
        //     'km_akhir'                  => $this->put('km_akhir')
        // ];
        // if($this->allmodel->updateData('t_rc_car_in', $data, 'in_no', $this->put('in_no')) == true) {
        //     $data = [
        //         'status' => true,
        //         'message' => 'success car in has been updated'
        //     ];
        //     $this->response($data, REST_Controller::HTTP_OK);
        // }else{
        //     $data = [
        //         'status' => false,
        //         'message' => 'failed car in has been updated'
        //     ];
        //     $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        // }
        $km_no = null;
        $km_awal = null;
        $km = $this->allmodel->getAll('t_rc_km_detail')->result();

        if(!empty($km)){
            foreach($km as $v){
                if($v->in_no == $this->put('in_no')){
                    $km_no = $v->in_no;
                    $km_awal = $v->km_awal;
                }
            }
        }

        $req_km = array(
            'km_no'=> $km_no,
            'km_awal' => $km_awal,
            'km_akhir' => $this->put('km_akhir'),
            'total_jarak_tempuh' => (int)$this->put('km_akhir') + (int) $km_awal
        );

        if($this->allmodel->updateData('t_rc_km_detail', $req_km, 'km_no', $km_no) == true) {
            $data = [
                'in_no'                     => $this->put('in_no'),
                'in_dt'                     => date("Y-m-d", strtotime($this->put('in_dt'))),
                'km_akhir'                  => $this->put('km_akhir')
            ];
            if($this->allmodel->updateData('t_rc_car_in', $data, 'in_no', $this->put('in_no')) == true) {
                $data = [
                    'status' => true,
                    'message' => 'success car in has been updated'
                ];
                $this->response($data, REST_Controller::HTTP_OK);
            }else{
                $data = [
                    'status' => false,
                    'message' => 'failed car in has been updated'
                ];
                $this->response($data, REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $data = [
                'status' => false,
                'message' => 'failed car in has been updated'
            ];
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete($id){
        $data = [
            'in_no'  => $id
        ];
        if($this->allmodel->deleteData('t_rc_car_in', 'in_no', (int)$id) == true) {
            $data = [
                'status' => true,
                'message' => 'success car in has been deleted'
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