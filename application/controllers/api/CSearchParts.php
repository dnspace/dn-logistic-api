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
class CSearchParts extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/searchparts_model','MSearch');
        
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
        $rs = $this->MSearch->get_parts_info();
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