<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CStockWH (CStockWHController)
 * CStockWH class to control to data parts
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class CStockWH extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/StockTbl_model','MStock');
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
        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fflag = $this->input->post('fflag', TRUE);

        //Condition
        if ($fpid != "") $arrWhere['stock_id'] = $fpid;
        if ($fcode != "") $arrWhere['stock_fsl_code'] = $fcode;
        if ($fpartnum != "") $arrWhere['stock_part_number'] = $fpartnum;
        if ($fflag != "") $arrWhere['stock_init_flag'] = $fflag;
        
        $rs = $this->MStock->get_data($this->security->xss_clean($arrWhere), array('stock_part_number'=>'ASC'), 'AND', $fslcode);
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
	
	public function list_fsl_stock_post(){
        $rs = array();
        $arrWhere = array();

        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fflag = $this->input->post('fflag', TRUE);

        //Condition
        if ($fcode != "") $arrWhere['stock_fsl_code'] = $fcode;
        if ($fpartnum != "") $arrWhere['stock_part_number'] = $fpartnum;
        if ($fflag != "") $arrWhere['stock_init_flag'] = $fflag;
        
        $rs = $this->MStock->get_data_fsl($this->security->xss_clean($arrWhere), array('stock_last_value'=>'DESC'), 'AND', $fslcode);
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
	
	public function list_fsl_sub_stock_post(){
        $rs = array();
        $arrWhere = array();

        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fflag = $this->input->post('fflag', TRUE);

        //Condition
        if ($fcode != "") $arrWhere['stock_fsl_code'] = $fcode;
        if ($fpartnum != "") $arrWhere['stock_part_number'] = $fpartnum;
        if ($fflag != "") $arrWhere['stock_init_flag'] = $fflag;
        
        $rs = $this->MStock->get_data_fsl_sub($this->security->xss_clean($arrWhere), array('stock_last_value'=>'DESC'), 'AND', $fslcode);
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
	
	public function list_detail_fsl_stock_post(){
        $rs = array();
        $arrWhere = array();

        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fflag = $this->input->post('fflag', TRUE);

        //Condition
        if ($fcode != "") $arrWhere['stock_fsl_code'] = $fcode;
        
        $rs = $this->MStock->get_data_detail_fsl($this->security->xss_clean($arrWhere), array('stock_last_value'=>'DESC'), 'AND', $fslcode);
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
	
	public function list_view_wh_stock_post(){
        $rs = array();
        $arrWhere = array();

        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fflag = $this->input->post('fflag', TRUE);

        //Condition
        if ($fcode != "") $arrWhere['stock_fsl_code'] = $fcode;
        if ($fpartnum != "") $arrWhere['stock_part_number'] = $fpartnum;
        if ($fflag != "") $arrWhere['stock_init_flag'] = $fflag;
        
        $rs = $this->MStock->get_data_whstock($this->security->xss_clean($arrWhere), array('stock_last_value'=>'DESC'), 'AND');
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
	
    public function list_view_post(){
        $rs = array();
        $arrSearch = array();
		$searchText = "";

        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
		
        $arrSearch = array('table_name');
		$searchText = $fslcode;
		
        $rs = $this->MStock->get_viewdata($arrSearch, $searchText);
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
	
	public function list_sub_post(){
        $rs = array();
        $arrWhere = array();

        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
        // $fflag = $this->input->post('fflag', TRUE);

        //Condition
        if ($fcode != "") $arrWhere['stock_fsl_code'] = $fcode;
        if ($fpartnum != "") $arrWhere['stock_part_number'] = $fpartnum;
        // if ($fflag != "") $arrWhere['stock_init_flag'] = $fflag;
        
        $rs = $this->MStock->get_sub_data($this->security->xss_clean($arrWhere), array('stock_last_value'=>'DESC'), 'AND', $fslcode);
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

        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
        
        $result = $this->MStock->get_data_info($fslcode, $fpartnum);
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
    public function info_wh_post(){
        $result = array();
        $arrWhere = array();

        $fslcode = 'WSPS';  
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
        
        $result = $this->MStock->get_data_info($fslcode, $fpartnum);
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
		
        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fminval = $this->input->post('fminval', TRUE);
        $finitval = $this->input->post('finitval', TRUE);
        $flastval = $this->input->post('flastval', TRUE);
        $fflag = $this->input->post('fflag', TRUE);
        $createdat = date('Y-m-d H:i:sa');
        
		$dataInfo = array('stock_fsl_code'=>$fcode, 'stock_part_number'=>$fpartnum, 'stock_min_value'=>$fminval, 
			'stock_init_value'=>$finitval, 'stock_last_value'=>$flastval, 'stock_init_flag'=>$fflag);
			
		$arrWhere = array('stock_part_number'=>$fpartnum);
		$exist = $this->MStock->check_data_exists($arrWhere, $fslcode);
		
		if($exist > 0){
            $this->response([
                'status' => FALSE,
                'message' => 'Data already exist'
            ], REST_Controller::HTTP_OK);
		}else{
			$result = $this->MStock->insert_data($dataInfo, $fslcode);
			
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
        $fid = $this->input->post('fid', TRUE);
        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
		
        if($fpartnum == null)
        {
           $this->response([
                'status' => FALSE,
                'message' => 'Data not found!'
            ], REST_Controller::HTTP_OK);
        }else{
            $result = $this->MStock->get_data_info($fslcode, $fpartnum);
            
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
        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fminval = $this->input->post('fminval', TRUE);
        $finitval = $this->input->post('finitval', TRUE);
        $flastval = $this->input->post('flastval', TRUE);
        $fflag = $this->input->post('fflag', TRUE);
        $createdat = date('Y-m-d H:i:sa');
        
		$dataInfo = array('stock_fsl_code'=>$fcode, 'stock_part_number'=>$fpartnum, 'stock_min_value'=>$fminval, 
			'stock_init_value'=>$finitval, 'stock_last_value'=>$flastval, 'stock_init_flag'=>$fflag);
        
        $result = $this->MStock->update_data($dataInfo, $fslcode, $fpartnum);
        
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
     * This function is used to update data stock by fsl
     */
    function update_stock_post()
    {
		$dataInfo = array();
		
        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fflag = $this->input->post('fflag', TRUE);
        
		$dataInfo = array('stock_last_value'=>$fqty, 'stock_init_flag'=>$fflag);
        
        $result = $this->MStock->update_data($dataInfo, $fslcode, $fpartnum);
        
        if($result == true)
        {
            $this->response([
                'status' => TRUE,
                'message' => 'Stock is successfully updated'
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Failed to update stock data'
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
        $fslcode = strtolower($this->input->post('fcode', TRUE));
        $fcode = strtoupper($this->input->post('fcode', TRUE));
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $dataInfo = array('is_deleted'=>1);

        $result = $this->MStock->delete_data($fslcode, $fpartnum);
        // $result = $this->MStock->update_data($dataInfo, $fslcode, $fpartnum);

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