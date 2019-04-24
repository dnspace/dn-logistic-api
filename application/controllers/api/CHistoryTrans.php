<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CHistoryTrans (CHistoryTransController)
 * CHistoryTrans class to control transaction history
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class CHistoryTrans extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/HistoryTrans_model','MHistory');
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 5000; // 5000 requests per hour per user/key
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index_get()
    {
        $ranstring = $this->common->randomString();
        $this->response([
            'status' => FALSE,
            'message' => $ranstring
        ], REST_Controller::HTTP_OK);
    }

	public function list_part_e_post(){
        $rs = array();
        $arrWhere = array();

        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);

        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;
		
		$arrWhere["outgoing_status"] = "open";
		array_push($arrWhere, $arrWhere["outgoing_status"]);
        
        $rs = $this->MHistory->get_data_part_e($this->security->xss_clean($arrWhere), array('created_at'=>'ASC'), 'AND');
        if ($rs){
            $this->response([
                    'status' => TRUE,
                    'result' => $rs
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                    'status' => FALSE,
                    'message' => 'No data available'
            ], REST_Controller::HTTP_OK);
        }
    }

    public function list_part_d_post(){
        $rs = array();
        $arrWhere = array();

        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);

        //Condition
        if ($fcode != "") $arrWhere['vdo.fsl_code'] = $fcode;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;
		
		$arrWhere["delivery_note_status"] = "open";
		//array_push($arrWhere, $arrWhere["delivery_note_status"]);
        
        $rs = $this->MHistory->get_data_part_d($this->security->xss_clean($arrWhere), array('created_at'=>'ASC'), 'AND');
        if ($rs){
            $this->response([
                    'status' => TRUE,
                    'result' => $rs
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                    'status' => FALSE,
                    'message' => 'No data available'
            ], REST_Controller::HTTP_OK);
        }
    }
}