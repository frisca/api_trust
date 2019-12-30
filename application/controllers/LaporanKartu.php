<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class LaporanKartu extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("allmodel");
    }

    public function index_get()
	{
        $laporan = $this->allmodel->getDataLaporan();
        if($laporan->num_rows() > 0 ){
            $laporans = $laporan->result();
            $status = true;
            $message = "success";

            $data = [
                'status'    => $status,
                'message'   => $message,
                'data'      => $laporans
            ];
    
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $laporan  = "";
            $status = false;
            $message = "not found";

            $data = [
                'status'    => $status,
                'message'   => $message,
                'data'      => $laporans
            ];
    
            $this->response($data, REST_Controller::HTTP_NOT_FOUND);
        }
        
    }
}