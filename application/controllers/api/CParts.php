<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CParts (CPartsController)
 * CParts class to control to data parts
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class CParts extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Parts_model','MParts');
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

    public function list_post(){
        $rs = array();
        $arrWhere = array();

        $fpid = $this->input->post('fpid', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fmachine = $this->input->post('fmachine', TRUE);

        //Condition
        if ($fpid != "") $arrWhere['part_id'] = $fpid;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;
        if ($fname != "") $arrWhere['part_name'] = $fname;
        if ($fmachine != "") $arrWhere['part_machine'] = $fmachine;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MParts->get_data($this->security->xss_clean($arrWhere), array('part_name'=>'ASC'), 'AND');
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
	
	public function list_like_post(){
        $rs = array();
        $arrLike = array();

        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fmachine = $this->input->post('fmachine', TRUE);

        //Condition
        if ($fpartnum != "") $arrLike['part_number'] = $fpartnum;
        if ($fname != "") $arrLike['part_name'] = $fname;
        if ($fmachine != "") $arrLike['part_machine'] = $fmachine;
        
        $rs = $this->MParts->get_data_like($this->security->xss_clean($arrLike), array('part_name'=>'ASC'), 'AND');
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
	
    /**
     * This function is used to get detail information
     */
    public function info_post(){
        $result = array();
        $arrWhere = array();

        $fpartnum = $this->input->post('fpartnum', TRUE);
        
        $result = $this->MParts->get_data_info($fpartnum);
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
		
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fdesc = $this->input->post('fdesc', TRUE);
        $fmachine = $this->input->post('fmachine', TRUE);
        $createdat = date('Y-m-d H:i:sa');
        
		$dataInfo = array('part_number'=>$fpartnum, 'part_name'=>$fname, 'part_desc'=>$fdesc, 'part_machine'=>$fmachine, 'created_at'=>$createdat);
			
		$arrWhere = array('part_number'=>$fpartnum);
		$exist = $this->MParts->check_data_exists($arrWhere);
		
		if($exist > 0){
            $this->response([
                'status' => FALSE,
                'message' => 'Data already exist'
            ], REST_Controller::HTTP_OK);
		}else{
			$result = $this->MParts->insert_data($dataInfo);
			
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
     * This function is used load data edit information
     * @param number $id : Optional : This is data id
     */
    function edit_post()
    {
        $fserialnum = $this->input->post('fserialnum', TRUE);
        if($fserialnum == null)
        {
           $this->response([
                'status' => FALSE,
                'message' => 'Data not found!'
            ], REST_Controller::HTTP_OK);
        }else{
            $result = $this->MParts->get_data_info($fserialnum);
            
            $this->response([
                'status' => TRUE,
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
		
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fdesc = $this->input->post('fdesc', TRUE);
        $fmachine = $this->input->post('fmachine', TRUE);
        $updatedat = date('Y-m-d H:i:sa');
        
		$dataInfo = array('part_number'=>$fpartnum, 'part_name'=>$fname, 'part_desc'=>$fdesc, 'part_machine'=>$fmachine, 'updated_at'=>$updatedat);
        
        $result = $this->MParts->update_data($dataInfo, $fpartnum);
        
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
		
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $dataInfo = array('is_deleted'=>1);

        // $result = $this->MParts->delete_data($fpartnum);
        $result = $this->MParts->update_data($dataInfo, $fpartnum);

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
}
?>