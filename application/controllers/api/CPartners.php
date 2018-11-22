<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CPartners (CPartnersController)
 * CPartners class to control to data partners
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class CPartners extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Partners_model','MPartner');
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
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

    /**
     * This function is used to get data list
     */
    public function list_post(){
        $result = array();
        $arrWhere = array();

        $fid = $this->input->post('fid', TRUE);
        $fkey = $this->input->post('fkey', TRUE);
        $fname = $this->input->post('fname', TRUE);

        //Condition
        if ($fid != "") $arrWhere['partner_id'] = $fid;
        if ($fkey != "") $arrWhere['partner_uniqid'] = $fkey;
        if ($fname != "") $arrWhere['partner_name'] = $fname;
		
        $result = $this->MPartner->get_data($this->security->xss_clean($arrWhere), array('partner_name'=>'ASC'), 'AND');
        if ($result){
            $this->response([
                    'status' => TRUE,
                    'result' => $result
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                    'status' => FALSE,
                    'message' => 'No data available'
            ], REST_Controller::HTTP_OK);
        }
    }
	
    /**
     * This function is used to get detail information
     */
    public function info_post(){
        $result = array();
        $arrWhere = array();

        $fkey = $this->input->post('fkey', TRUE);
        
        $result = $this->MPartner->get_data_info($fkey);
        if ($result){
            $this->response([
                    'status' => TRUE,
                    'result' => $result
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                    'status' => FALSE,
                    'message' => 'No data available'
            ], REST_Controller::HTTP_OK);
        }
    }
	
    /**
     * This function is used to add new data to the system
     */
    function insert_post()
    {
		$arrWhere = array();
		$dataInfo = array();
		
        $fkey = $this->input->post('fkey', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $flocation = $this->input->post('flocation', TRUE);
        $fcontact = $this->input->post('fcontact', TRUE);
        
		$dataInfo = array('partner_uniqid'=>$fkey, 'partner_name'=>$fname, 'partner_location'=>$flocation, 'partner_contact'=>$fcontact);
			
		$arrWhere = array('partner_uniqid'=>$fkey);
		$exist = $this->MPartner->check_data_exists($arrWhere);
		
		if($exist > 0){
            $this->response([
                'status' => FALSE,
                'message' => 'Data already exist'
            ], REST_Controller::HTTP_OK);
		}else{
			$result = $this->MPartner->insert_data($dataInfo);
			
			if($result > 0)
			{
				$this->response([
					'status' => TRUE,
					'message' => 'Data is successfully inserted'
				], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'message' => 'Failed to insert data'
				], REST_Controller::HTTP_OK);
			}
		}
    }
	
    /**
     * This function is used load user edit information
     * @param number $id : Optional : This is data id
     */
    function edit_post()
    {
        $fid = $this->input->post('fkey', TRUE);
        if($fid == null)
        {
           $this->response([
                'status' => FALSE,
                'message' => 'Data not found!'
            ], REST_Controller::HTTP_OK);
        }else{
            // $roles = $this->MPartner->get_user_roles();
            $result = $this->MPartner->get_data_info($fid);
            
            $this->response([
                'status' => TRUE,
                // 'roles' => $roles,
                'result' => $result
            ], REST_Controller::HTTP_OK);
        }
    }
    
    
    /**
     * This function is used to update the information data
     */
    function update_post()
    {
		$dataInfo = array();
		
        $fkey = $this->input->post('fkey', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $flocation = $this->input->post('flocation', TRUE);
        $fcontact = $this->input->post('fcontact', TRUE);
        
		$dataInfo = array('partner_name'=>$fname, 'partner_location'=>$flocation, 'partner_contact'=>$fcontact);
        
        $result = $this->MPartner->update_data($dataInfo, $fkey);
        
        if($result == true)
        {
            $this->response([
                'status' => TRUE,
                'message' => 'Data is successfully updated'
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Failed to update data'
            ], REST_Controller::HTTP_OK);
        }
    }


    /**
     * This function is used to delete the data using id
     * @return boolean $result : TRUE / FALSE
     */
    function delete_post()
    {
		$dataInfo = array();
		
        $fkey = $this->input->post('fkey', TRUE);

        $result = $this->MPartner->delete_data($fkey);

        if($result > 0)
        {
            $this->response([
                'status' => TRUE,
                'message' => 'Data is successfully deleted'
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Failed to delete data'
            ], REST_Controller::HTTP_OK);
        }
    }
}
?>