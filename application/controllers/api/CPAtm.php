<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CPAtm (CPAtmController)
 * CPAtm class to control to data parts
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class CPAtm extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Atm_model','MAtm');
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
        $fssbid = $this->input->post('fssbid', TRUE);
        $fmachid = $this->input->post('fmachid', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fcity = $this->input->post('fcity', TRUE);
		$flimit = empty($this->input->post('flimit', TRUE)) ? 0 : (int)$this->input->post('flimit', TRUE);
        $fdeleted = $this->input->post('fdeleted', TRUE);

		$date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
		
        //Condition
        if ($fpid != "") $arrWhere['atmp_id'] = $fpid;
        if ($fssbid != "") $arrWhere['atmp_ssbid'] = $fssbid;
        if ($fmachid != "") $arrWhere['atmp_machid'] = $fmachid;
        if ($fname != "") $arrWhere['atmp_cust'] = $fname;
        if ($fcity != "") $arrWhere['atmp_city'] = $fcity;
        if ($fdeleted != "") $arrWhere['is_deleted'] = $fdeleted;
		
        $rs = $this->MAtm->get_data($this->security->xss_clean($arrWhere), array('atmp_cust'=>'ASC'), 'AND', $flimit);
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
	
	public function list_backup_post(){
        $rs = array();
        $arrWhere = array();

        $fpid = $this->input->post('fpid', TRUE);
        $fssbid = $this->input->post('fssbid', TRUE);
        $fmachid = $this->input->post('fmachid', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fcity = $this->input->post('fcity', TRUE);
		$date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));

        //Condition
        if ($fpid != "") $arrWhere['atmp_id'] = $fpid;
        if ($fssbid != "") $arrWhere['atmp_ssbid'] = $fssbid;
        if ($fmachid != "") $arrWhere['atmp_machid'] = $fmachid;
		if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['createdAt_date1'] = $fdate1." 00:00:00";
            $arrWhere['createdAt_date2'] = $fdate2." 23:59:59";
		}else{
            // $arrWhere['createdAt_date1'] = $date_before;
            // $arrWhere['createdAt_date2'] = $date;
		}
        if ($fname != "") $arrWhere['atmp_cust'] = $fname;
        if ($fcity != "") $arrWhere['atmp_city'] = $fcity;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
		
        $rs = $this->MAtm->get_data($this->security->xss_clean($arrWhere), array('atmp_cust'=>'ASC'), 'AND', 10);
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
     * This function used to load unique data by each parameter
     */
	public function list_unique_get(){
        $rs = array();
        $arrLike = array();
        $arrValue = array();

        $fparam = $this->input->get('fparam', TRUE);
		$flimit = empty($this->input->get('flimit', TRUE)) ? 0 : (int)$this->input->post('flimit', TRUE);

        //Condition
        if ($fparam != "") {
			if(strpos($fparam, "serial") !== false){
				$arrValue = array('atmp_ssbid');
			}
			if(strpos($fparam, "bank") !== false){
				$arrValue = array('atmp_cust');
			}
			if(strpos($fparam, "city") !== false){
				$arrValue = array('atmp_city');
			}
		}else{
			$arrValue = array();
		}

        $rs = $this->MAtm->get_data_distinct($this->security->xss_clean($arrValue), $flimit);
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

        $fname = $this->input->post('fname', TRUE);
        $fcity = $this->input->post('fcity', TRUE);

        //Condition
        if ($fname != "") $arrLike['atmp_cust'] = $fname;
        if ($fcity != "") $arrLike['atmp_city'] = $fcity;
        
        $rs = $this->MAtm->get_data_like($this->security->xss_clean($arrLike), array('atmp_cust'=>'ASC'), 'AND');
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

        $fid = $this->input->post('fid', TRUE);
        $fssbid = $this->input->post('fssbid', TRUE);
		
		if(!empty($fid)){
			$result = $this->MAtm->get_data_info($fid);
		}elseif(!empty($fssbid)){
			$result = $this->MAtm->get_data_info2($fssbid);
		}
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
		
        $fssbid = $this->input->post('fssbid', TRUE);
        $fmachid = $this->input->post('fmachid', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $floc = $this->input->post('floc', TRUE);
        $faddress = $this->input->post('faddress', TRUE);
        $fpostcode = $this->input->post('fpostcode', TRUE);
        $fcity = $this->input->post('fcity', TRUE);
        $fprovince = $this->input->post('fprovince', TRUE);
        $fisland = $this->input->post('fisland', TRUE);
        
		$dataInfo = array('atmp_ssbid'=>$fssbid, 'atmp_machid'=>$fmachid, 'atmp_cust'=>$fname, 'atmp_loc'=>$floc, 
			'atmp_address'=>$faddress, 'atmp_postcode'=>$fpostcode, 'atmp_city'=>$fcity, 'atmp_province'=>$fprovince, 'atmp_island'=>$fisland);
			
		$arrWhere = array('atmp_ssbid'=>$fssbid);
		$exist = $this->MAtm->check_data_exists($arrWhere);
		
		if($exist > 0){
            $this->response([
                'status' => FALSE,
                'message' => 'Data already exist'
            ], REST_Controller::HTTP_OK);
		}else{
			$result = $this->MAtm->insert_data($dataInfo);
			
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
        $fssbid = $this->input->post('fssbid', TRUE);
        if($fssbid == null)
        {
           $this->response([
                'status' => FALSE,
                'message' => 'Data not found!'
            ], REST_Controller::HTTP_OK);
        }else{
            $result = $this->MAtm->get_data_info($fssbid);
            
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
		
        $fid = $this->input->post('fid', TRUE);
        $fmachid = $this->input->post('fmachid', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $floc = $this->input->post('floc', TRUE);
        $faddress = $this->input->post('faddress', TRUE);
        $fpostcode = $this->input->post('fpostcode', TRUE);
        $fcity = $this->input->post('fcity', TRUE);
        $fprovince = $this->input->post('fprovince', TRUE);
        $fisland = $this->input->post('fisland', TRUE);
        
		$dataInfo = array('atmp_machid'=>$fmachid, 'atmp_cust'=>$fname, 'atmp_loc'=>$floc, 
			'atmp_address'=>$faddress, 'atmp_postcode'=>$fpostcode, 'atmp_city'=>$fcity, 'atmp_province'=>$fprovince, 'atmp_island'=>$fisland);
        
        $result = $this->MAtm->update_data($dataInfo, $fid);
        
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
		
        $fid = $this->input->post('fid', TRUE);
        $dataInfo = array('is_deleted'=>1);

        $result = $this->MAtm->update_data($dataInfo, $fid);

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