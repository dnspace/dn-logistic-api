<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CVendor (CVendorController)
 * CVendor class to control to data parts
 * @author : ABAS DAN KAZEPA
 * @version : 2.0
 * @since : Mei 2017
 */
class CVendor extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Vendor_model','MVendor');
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
        $rs = array();
        $arrWhere = array();

        $fpid = $this->input->post('fpid', TRUE);
        $fvendorcode = $this->input->post('fvendorcode', TRUE);
        $fvendorname = $this->input->post('fvendorname', TRUE);
        $fvendoraddress = $this->input->post('fvendoraddress', TRUE);

        //Condition
        if ($fpid != "") $arrWhere['vendor_id'] = $fpid;
        if ($fvendorcode != "") $arrWhere['vendor_code'] = $fvendorcode;
        if ($fvendorname != "") $arrWhere['vendor_name'] = $fvendorname;
        if ($fvendoraddress != "") $arrWhere['vendor_address'] = $fvendoraddress;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MVendor->get_data($this->security->xss_clean($arrWhere), array('part_name'=>'ASC'), 'AND');
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