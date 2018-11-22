<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CWarehouse (CWarehouseController)
 * CWarehouse class to control to data warehouse
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class CWarehouse extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Warehouse_model','MWarehouse');
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
        ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * This function is used to get data list
     */
    public function list_post(){
        $result = array();
        $arrWhere = array();

        $fcode = $this->input->post('fcode', TRUE);
        $fname = $this->input->post('fname', TRUE);
		$flimit = empty($this->input->post('flimit', TRUE)) ? 0 : (int)$this->input->post('flimit', TRUE);
        $fdeleted = $this->input->post('fdeleted', TRUE);

        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($fname != "") $arrWhere['fsl_name'] = $fname;
        if ($fdeleted != "") $arrWhere['is_deleted'] = $fdeleted;
		
        $result = $this->MWarehouse->get_data($this->security->xss_clean($arrWhere), array('field_order'=>'ASC'), 'AND', $flimit);
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

        $fcode = $this->input->post('fcode', TRUE);
        
        $result = $this->MWarehouse->get_data_info(strtoupper($fcode));
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
		
        $fcode = $this->input->post('fcode', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $flocation = $this->input->post('flocation', TRUE);
        $fnearby = $this->input->post('fnearby', TRUE);
        $fpic = $this->input->post('fpic', TRUE);
        $fphone = $this->input->post('fphone', TRUE);
        $fspv = $this->input->post('fspv', TRUE);
        $forder = $this->input->post('forder', TRUE);
        
		$dataInfo = array('fsl_code'=>$fcode, 'fsl_name'=>$fname, 'fsl_location'=>$flocation, 
			'fsl_nearby'=>$fnearby, 'fsl_pic'=>$fpic, 'fsl_phone'=>$fphone, 'fsl_spv'=>$fspv, 'field_order'=>$forder);
			
		$arrWhere = array('fsl_code'=>$fcode);
		$exist = $this->MWarehouse->check_data_exists($arrWhere);
		
		if($exist > 0){
            $this->response([
                'status' => FALSE,
                'message' => 'Data already exist'
            ], REST_Controller::HTTP_OK);
		}else{
			$result = $this->MWarehouse->insert_data($dataInfo);
			
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
        $fcode = $this->input->post('fcode', TRUE);
        if($fcode == null)
        {
           $this->response([
                'status' => FALSE,
                'message' => 'Data not found!'
            ], REST_Controller::HTTP_OK);
        }else{
            // $roles = $this->MWarehouse->get_user_roles();
            $result = $this->MWarehouse->get_data_info(strtoupper($fcode));
            
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
		
        $fcode = $this->input->post('fcode', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $flocation = $this->input->post('flocation', TRUE);
        $fnearby = $this->input->post('fnearby', TRUE);
        $fpic = $this->input->post('fpic', TRUE);
        $fphone = $this->input->post('fphone', TRUE);
        $fspv = $this->input->post('fspv', TRUE);
        $forder = $this->input->post('forder', TRUE);
        
		$dataInfo = array('fsl_name'=>$fname, 'fsl_location'=>$flocation, 
		'fsl_nearby'=>$fnearby, 'fsl_pic'=>$fpic, 'fsl_phone'=>$fphone, 'fsl_spv'=>$fspv, 'field_order'=>$forder);

        
        $result = $this->MWarehouse->update_data($dataInfo, strtoupper($fcode));
        
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
		
        $fcode = $this->input->post('fcode', TRUE);
        $dataInfo = array('is_deleted'=>1);

        // $result = $this->MWarehouse->delete_data($userId, $dataInfo);
        $result = $this->MWarehouse->update_data($dataInfo, strtoupper($fcode));

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