<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CPOutgoings (CPOutgoingsController)
 * CPOutgoings class to control transactions
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class CReportDOA extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/ReportDOA_model','mreport');
        
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 5000; // 5000 requests per hour per user/key
    }
    
    public function index_get(){
        $ranstring = $this->common->randomString();
        $this->response([
            'status' => FALSE,
            'message' => $randomString
        ], REST_Controller::HTTP_OK);
    }
    
    public function list_post(){
        //var_dump($this->input->post('fsearch'));
        $fstatus = $this->input->post('fstatus', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
        
        if ($fdate1 != "" AND $fdate2 != "") {
            $date_1 = $fdate1;
            $date_2 = $fdate2;
		}else{
            $date_1 = $date_before;
            $date_2 = $date;
        }
        //if($fstatus != '') $arrWhere[$this->db_table_name.'_status'] = $fstatus;

        $rs = $this->mreport->get_part_list($date_1,$date_2);
        if ($rs){
            $this->response([
                    'status' => TRUE,
                    'result' => $rs
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                    'status' => FALSE,
                    'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }
    
}