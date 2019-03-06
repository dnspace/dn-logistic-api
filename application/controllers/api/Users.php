<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : Users (UsersController)
 * User Class to control all user related operations.
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class Users extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Users_model','MUser');
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
        $fgroup = $this->input->post('fgroup', TRUE);
        $femail = $this->input->post('femail', TRUE);
        $f_isadmin = $this->input->post('f_isadmin', TRUE);

        //Condition
        if ($fkey != "") $arrWhere['user_key'] = $fkey;
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($fgroup != "") $arrWhere['group_name'] = $fgroup;
        if ($femail != "") $arrWhere['user_email'] = $femail;
        if ($f_isadmin != "") $arrWhere['is_admin'] = $f_isadmin;
		
		$arrWhere['is_deleted'] = 0;
		array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $result = $this->MUser->get_viewdata($this->security->xss_clean($arrWhere), array('user_fullname'=>'ASC'), 'AND');
		
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
        
        $result = $this->MUser->get_data_info($fkey);
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
        $fgroup = $this->input->post('fgroup', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $femail = $this->input->post('femail', TRUE);
        $ffsl = $this->input->post('ffsl', TRUE);
        $fcoverage = $this->input->post('fcoverage', TRUE);
        $fisadm = $this->input->post('fisadm', TRUE);
        $createdat = date('Y-m-d H:i:sa');
        
        $dataInfo = array('group_id'=>$fgroup, 'user_key'=>$fkey, 'user_pass'=>getHashedPassword($fpass), 'user_fullname'=>$fname, 'user_email'=>$femail, 
        'fsl_code'=>$ffsl, 'coverage_fsl'=>$fcoverage, 'is_admin'=>$fisadm, 'created_at'=>$createdat);

        $count = $this->MUser->check_data_exists(array('user_key' => $fkey));
        if ($count > 0)
        { 
            $this->response([
                'status' => FALSE,
                'message' => 'Data already exist'
            ], REST_Controller::HTTP_OK);
        }
        else
        { 
            $result = $this->MUser->insert_data($dataInfo);
        
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
        $fgroup = $this->input->post('fgroup', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $femail = $this->input->post('femail', TRUE);
        $ffsl = $this->input->post('ffsl', TRUE);
        $fcoverage = $this->input->post('fcoverage', TRUE);
        $fisadm = $this->input->post('fisadm', TRUE);
        $fupdatedat = date('Y-m-d H:i:sa');
        
        $dataInfo = array();
        
        if(empty($fpass))
        {
            $dataInfo = array('group_id'=>$fgroup, 'user_email'=>$femail, 'user_fullname'=>$fname, 'fsl_code'=>$ffsl, 'coverage_fsl'=>$fcoverage, 
			'updated_at'=>$fupdatedat);
        }
        else
        {
            $dataInfo = array('group_id'=>$fgroup, 'user_email'=>$femail, 'user_pass'=>getHashedPassword($fpass), 'user_fullname'=>$fname, 
				'fsl_code'=>$ffsl, 'coverage_fsl'=>$fcoverage, 'updated_at'=>$fupdatedat);
        }
        
        $result = $this->MUser->update_data($dataInfo, $fkey);
        
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
     * This function is used to edit the user information
     */
    function update_account_post()
    {
        $fkey = $this->input->post('fkey', TRUE);
        $fpass = $this->input->post('fpass', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $femail = $this->input->post('femail', TRUE);
        $fupdatedat = date('Y-m-d H:i:sa');
        
        $dataInfo = array();
        
        if(empty($fpass))
        {
            $dataInfo = array('user_email'=>$femail, 'user_fullname'=>$fname, 'updated_at'=>$fupdatedat);
        }
        else
        {
            $dataInfo = array('user_email'=>$femail, 'user_pass'=>getHashedPassword($fpass), 'user_fullname'=>$fname, 'updated_at'=>$fupdatedat);
        }
        
        $result = $this->MUser->update_data($dataInfo, $fkey);
        
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
        $result = $this->MUser->update_data($dataInfo, $fkey);

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
        
        $resultPas = $this->MUser->match_old_password($createdby, $oldPassword);
        
        if(empty($resultPas))
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Your old password are not correct'
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $usersData = array('user_pass'=>getHashedPassword($newPassword), 'updated_at'=>$fupdatedat);
            
            $result = $this->MUser->change_password($createdby, $usersData);
            
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