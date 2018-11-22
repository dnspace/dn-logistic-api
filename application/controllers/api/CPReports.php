<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CPReports (CPReportsController)
 * Login class to control to generate reports.
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class CPReports extends REST_Controller
{
	/**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Reports_model','MReport');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 5000; // 5000 requests per hour per user/key
    }
	
	/**
     * Index Page for this controller.
     */
    public function index_get()
    {
        $ranstring = $this->common->randomString();
        $this->response([
            'status' => FALSE,
            'message' => $ranstring
        ], REST_Controller::HTTP_OK);
    }
	
	public function list_outgoing_daily_reports_post(){
		$rs = array();
        $arrWhere = array();

        $fcode = $this->input->post('fcode', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);

        //Condition
        $arrWhere = array();
        
        $rs = $this->MReport->get_outgoing_daily_report($fcode, $fdate1, $fdate2);
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
	
	public function list_outgoing_used_part_post(){
		$rs = array();
        $arrWhere = array();

        $fcode = $this->input->post('fcode', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);

        //Condition
        $arrWhere = array();
        
        $rs = $this->MReport->get_outgoing_used_part($fcode, $fdate1, $fdate2);
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
	
	public function list_outgoing_replenish_plan_post(){
		$rs = array();
        $arrWhere = array();

        $fcode = $this->input->post('fcode', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);

        //Condition
        $arrWhere = array();
        
        $rs = $this->MReport->get_outgoing_replenish_plan($fcode, $fdate1, $fdate2);
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