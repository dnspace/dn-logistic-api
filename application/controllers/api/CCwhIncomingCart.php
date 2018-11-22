<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CPSupplyRepairToCwh (CPSupplyRepairToCwh - Controller)
 * CPSupplyRepairToCwh class to control transactions
 * @author : Khazefa & Abasworm
 * @version : 2.1#0001
 * @since : NOV 2018
 */
class CCwhIncomingCart extends REST_Controller
{
    private $mtmp;
    private $db_table_name = 'cwh_incoming_tmp';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('crud/crud_model','crud');
        $this->mtmp = array(
            'table' => 'cwh_incoming_tmp',
            //'view' => 'view'.$this->db_table_name,
            'primKey' => 'tmp_id',
            'indexKey' => 'tmp_uniqid',
            'order' => 'desc'
        ); //transaction variable
        $this->crud->set_table($this->mtmp);
    }

    public function list_tmp_post(){
        $funiqid = $this->input->post('funiqid', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $ftransoutnum = $this->input->post('ftransoutnum',TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
        $fcode = $this->input->post('fcode', TRUE);

        //Condition
        if ($ftransoutnum != "") $arrWhere['tmp_transout_num'] = $fuser;
        if ($funiqid != "") $arrWhere['tmp_uniqid'] = $funiqid;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;
        if ($fserialnum != "") $arrWhere['serial_number'] = $fserialnum;
        if ($fuser != "") $arrWhere['user'] = $fuser;
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->crud->get_data($arrWhere, array('tmp_id'=>'ASC'), 'AND');
        if ($rs){
            $this->response([
                    'status' => TRUE,
                    'result' => $rs
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                    'status' => FALSE,
                    'result' => array(),
                    'message' => 'No data were found'
            ], REST_Controller::HTTP_OK);
        }
    }

    /**
     * This function is used to add new tickets detail to the system
     */
    function create_trans_tmp_post()
    {
        $arrWhere = array();
        $dataInfo = array();
        $dataInfo2 = array();
        
        $ftransoutnum = $this->input->post('ftransoutnum',TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fpartname = $this->input->post('fpartname', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fcartid = $this->input->post('fcartid', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        
        $dataInfo = array(
            'tmp_transout_num' =>$ftransoutnum,
            'part_number'=>$fpartnum, 
            'part_name'=>$fpartname, 
            'serial_number'=>$fserialnum, 
            'tmp_uniqid'=>$fcartid, 
            'tmp_qty'=>$fqty, 
            'user'=>$fuser, 
            'fullname'=>$fname, 
            'fsl_code'=>$fcode
        );

        $arrWhere = array(
            'part_number'=>$fpartnum, 
            'serial_number'=>$fserialnum, 
            'tmp_uniqid'=>$fcartid
        );
        $exist = $this->crud->check_data_exists($arrWhere);
        
        if($exist > 0){
            //get qty tmp
            if(strtolower($fserialnum) == 'nosn'){
                $stock = $this->cur_stock_tmp($fpartnum, $fserialnum, $fcartid);
                $ustock = $stock + $fqty;
                
                $dataInfo2 = array('tmp_qty'=>$ustock);
                $result2 = $this->crud->update_data_2($dataInfo2, $fpartnum, $fserialnum, $fcartid);
            
                if($result2)
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
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Failed To update, Data already exist.'
                ], REST_Controller::HTTP_OK);
            }
        }else{
            $result = $this->crud->insert_data($dataInfo);
            
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
     * This function is used to add new tickets detail to the system
     */
    function update_cart_post()
    {
        $fid = $this->input->post('fid', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        
        $dataInfo = array('tmp_qty'=>$fqty);

        $result = $this->crud->update_data($dataInfo, $fid);
        
        if($result)
        {
            $this->response([
                'status' => TRUE,
                'message' => 'Data updated successfully'
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Data failed to create'
            ], REST_Controller::HTTP_OK);
        }
    }
	
	/**
     * This function is used to delete the data using data id
     * @return boolean $result : TRUE / FALSE
     */
    function delete_cart_post(){
        $fid = $this->input->post('fid', TRUE);
        $result = $this->crud->delete_data_by_primkey($fid);
        //$this->output->enable_profiler(TRUE);
        //var_dump($fid);
        if($result)
        {
            $this->response([
                'status' => TRUE,
                'message' => 'Delete successfully'
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Delete failed'
            ], REST_Controller::HTTP_OK);
        }
    }
	
	/**
     * This function is used to delete the data using data id
     * @return boolean $result : TRUE / FALSE
     */
    function delete_multi_cart_post()
    {
        $fcartid = $this->input->post('fcartid', TRUE);

        $result = $this->crud->delete_data($fcartid);

        if($result > 0)
        {
            $this->response([
                'status' => TRUE,
                'message' => 'Delete successfully'
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Delete failed'
            ], REST_Controller::HTTP_OK);
        }
    }
	
	public function total_cart_post(){
        $rs = array();
        $arrWhere = array();

        $funiqid = $this->input->post('funiqid', TRUE);

        //Condition
        if ($funiqid != "") $arrWhere['tmp_uniqid'] = $funiqid;

        // $arrWhere["is_deleted"] = 0;
        // array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->crud->count_cart($arrWhere);
        if (!empty($rs)){
            $this->response([
                    'status' => TRUE,
                    'result' => $rs
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                    'status' => FALSE,
                    'result' => array()
            ], REST_Controller::HTTP_OK);
        }
    }
	
	/**
     * This function is used to check the data using data info
     * @return boolean $result : TRUE / FALSE
     */
    function get_cart_info_post()
    {
		$rs = array();
        $arrWhere = array();

        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $funiqid = $this->input->post('funiqid', TRUE);
        
        $rs = $this->crud->get_data_info_2($fpartnum, $fserialnum, $funiqid);
        if ($rs){
            $this->response([
                    'status' => TRUE,
                    'result' => $rs
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                    'status' => FALSE,
                    'message' => 0
            ], REST_Controller::HTTP_OK);
        }
    }

    function cur_stock_tmp($partnum, $serialnum, $cartid){
        $rs = array();
        $arrWhere = array();
		$stock = 0;

        $arrWhere = array('part_number'=>$partnum, 'serial_number'=>$serialnum, 'tmp_uniqid'=>$cartid);
        
        $rs = $this->crud->get_data($arrWhere, array(), 'AND');
        if ($rs){
			foreach($rs as $r){
				$stock = $r["tmp_qty"];
			}
        }else{
            $stock = 0;
        }
		// $this->response([
                // 'status' => TRUE,
                // 'message' => $stock
            // ], REST_Controller::HTTP_OK);
		return $stock;
    }
}