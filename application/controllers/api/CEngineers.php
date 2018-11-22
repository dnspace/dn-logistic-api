<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CEngineers (CEngineersController)
 * CEngineers Class to control all Engineer related operations.
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class CEngineers extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Engineer_model','MEngineer');
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 100; // 100 requests per hour per user/key
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
        ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * This function is used to get data list
     */
    public function list_post(){
        $result = array();
        $arrWhere = array();

        $fkey = $this->input->post('fkey', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $femail = $this->input->post('femail', TRUE);

        //Condition
        if ($fkey != "") $arrWhere['engineer_key'] = $fkey;
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($femail != "") $arrWhere['engineer_email'] = $femail;
		
		$arrWhere['is_deleted'] = 0;
		array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $result = $this->MEngineer->get_data($this->security->xss_clean($arrWhere), array('engineer_name'=>'ASC'), 'AND');
		
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
     * This function is used to get data list view
     */
    public function list_view_post(){
        $result = array();
        $arrWhere = array();

        $fkey = $this->input->post('fkey', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $fpartner = $this->input->post('fpartner', TRUE);
        $femail = $this->input->post('femail', TRUE);

        //Condition
        if ($fkey != "") $arrWhere['engineer_key'] = $fkey;
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($fpartner != "") $arrWhere['partner_uniqid'] = $fpartner;
        if ($femail != "") $arrWhere['engineer_email'] = $femail;
		
		$arrWhere['is_deleted'] = 0;
		array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $result = $this->MEngineer->get_viewdata($this->security->xss_clean($arrWhere), array('engineer_name'=>'ASC'), 'AND');
		
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
        
        $result = $this->MEngineer->get_data_info($fkey);
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
     * This function is used to add new user to the system
     */
    function insert_post()
    {
        $fkey = $this->input->post('fkey', TRUE);
        $fpass = $this->input->post('fpass', TRUE);
        $fpartner = $this->input->post('fpartner', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $ftitle = $this->input->post('ftitle', TRUE);
        $femail = $this->input->post('femail', TRUE);
        $fphone = $this->input->post('fphone', TRUE);
        $farea = $this->input->post('farea', TRUE);
        $fspv = $this->input->post('fspv', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $createdat = date('Y-m-d H:i:sa');
        
        $dataInfo = array('partner_id'=>$fpartner, 'engineer_key'=>$fkey, 'engineer_pass'=>getHashedPassword($fpass), 
		'engineer_name'=>$fname, 'engineer_title'=>$ftitle, 'engineer_email'=>$femail, 'engineer_phone'=>$fphone, 
		'engineer_area'=>$farea, 'engineer_spv'=>$fspv, 'fsl_code'=>$fcode, 'created_at'=>$createdat);

        $count = $this->MEngineer->check_data_exists(array('engineer_key' => $fkey));
        if ($count > 0)
        { 
            $this->response([
                'status' => FALSE,
                'message' => 'Data already exist'
            ], REST_Controller::HTTP_OK);
        }
        else
        { 
            $result = $this->MEngineer->insert_data($dataInfo);
        
            if($result > 0)
            {
                $this->response([
                    'status' => TRUE,
                    'result' => $result,
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
     * This function is used to edit the user information
     */
    function update_post()
    {
        $fkey = $this->input->post('fkey', TRUE);
        $fpass = $this->input->post('fpass', TRUE);
        $fpartner = $this->input->post('fpartner', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $ftitle = $this->input->post('ftitle', TRUE);
        $femail = $this->input->post('femail', TRUE);
        $fphone = $this->input->post('fphone', TRUE);
        $farea = $this->input->post('farea', TRUE);
        $fspv = $this->input->post('fspv', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        
        $dataInfo = array();
        
        if(empty($fpass))
        {
            $dataInfo = array('engineer_email'=>$femail, 'engineer_name'=>$fname, 'engineer_title'=>$ftitle, 
			'engineer_phone'=>$fphone, 'engineer_area'=>$farea, 'engineer_spv'=>$fspv, 'fsl_code'=>$fcode);
        }
        else
        {
            $dataInfo = array('engineer_email'=>$femail, 'engineer_pass'=>getHashedPassword($fpass), 'engineer_name'=>$fname, 'engineer_title'=>$ftitle, 
				'engineer_phone'=>$fphone, 'engineer_area'=>$farea, 'engineer_spv'=>$fspv, 'fsl_code'=>$fcode);
        }
        
        $result = $this->MEngineer->update_data($dataInfo, $fkey);
        
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
        $dataInfo = array('is_deleted'=>1);

        // $result = $this->MWarehouse->delete_data($fkey, $dataInfo);
        $result = $this->MEngineer->update_data($dataInfo, $fkey);

        if($result == true)
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
    
    
    /**
     * This function is used to change the password of the user
     */
    function changePassword_post()
    {
        $oldPassword = $this->input->post('oldPassword', TRUE);
        $newPassword = $this->input->post('newPassword', TRUE);
        // $createdby = $this->input->post('createdby', TRUE);
        $fupdatedat = date('Y-m-d H:i:sa');
        
        $resultPas = $this->MEngineer->match_old_password($createdby, $oldPassword);
        
        if(empty($resultPas))
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Your old password are not correct'
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $usersData = array('engineer_pass'=>getHashedPassword($newPassword), 'updated_at'=>$fupdatedat);
            
            $result = $this->MEngineer->change_password($createdby, $usersData);
            
            if($result > 0) { 
                $this->response([
                'status' => TRUE,
                'message' => 'success', 'Password updated successfully'
            ], REST_Controller::HTTP_OK);
            }
            else { 
                $this->response([
                'status' => FALSE,
                'message' => 'Password update failed'
            ], REST_Controller::HTTP_OK);
            }
        }
    }
}

?>