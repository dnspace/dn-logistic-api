<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : Doctype (DoctypeController)
 * Doctype Class to control all user related operations.
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class Doctype extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Doctype_model','MDoctype');
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
	 
    public function get_doctype_get(){
        $rs = array();
        $arrWhere = array();
        $fname = $this->input->post('fname', TRUE);
        $fgroup = $this->input->post('fgroup', TRUE);

        //Condition
        if(!empty($fname) || !empty($fgroup)){
            $arrWhere = array('name'=>$fname,'description'=>$fgroup);
        }
        $rs = $this->MDoctype->get_list_by($arrWhere);

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

?>