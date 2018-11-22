<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CPIncomings (CPIncomingsController)
 * CPIncomings class to control transactions
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class CPIncomings extends REST_Controller
{
	/**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/PIncoming_model','MIncoming');
        $this->load->model('api/PIncomingDetail_model','MIncoming_D');
        $this->load->model('api/PIncomingTmp_model','MIncoming_T');
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

        $fcode = $this->input->post('fcode', TRUE);
        $ftrans_in = $this->input->post('ftrans_in', TRUE);

        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_in != "") $arrWhere['incoming_num'] = $ftrans_in;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MIncoming->get_data($arrWhere, array('incoming_num'=>'DESC'), 'AND');
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
	
	public function list_view_post(){
        $rs = array();
        $arrWhere = array();

        $fcode = $this->input->post('fcode', TRUE);
        $ftrans_in = $this->input->post('ftrans_in', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
		$date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
		
        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_in != "") $arrWhere['incoming_num'] = $ftrans_in;
		if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['created_at_1'] = $fdate1;
            $arrWhere['created_at_2'] = $fdate2;
		}else{
            $arrWhere['created_at_1'] = $date_before;
            $arrWhere['created_at_2'] = $date;
		}
        if ($fpurpose != "") $arrWhere['incoming_purpose'] = $fpurpose;
		
        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MIncoming->get_viewdata($arrWhere, array('incoming_num'=>'DESC'), 'AND');
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
	
	public function info_view_post(){
        $rs = array();
        $arrWhere = array();

        $fcode = $this->input->post('fcode', TRUE);
        $ftrans_in = $this->input->post('ftrans_in', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
		
        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_in != "") $arrWhere['incoming_num'] = $ftrans_in;
        if ($fpurpose != "") $arrWhere['incoming_purpose'] = $fpurpose;
		
        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MIncoming->get_viewdata($arrWhere, array('incoming_num'=>'DESC'), 'AND');
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
	
	public function list_view_detail_post(){
        $rs = array();
        $arrWhere = array();

        $ftrans_in = $this->input->post('ftrans_in', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);

        //Condition
        if ($ftrans_in != "") $arrWhere['incoming_num'] = $ftrans_in;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MIncoming_D->get_viewdata($arrWhere, array('part_number'=>'ASC'), 'AND');
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

    public function list_detail_post(){
        $rs = array();
        $arrWhere = array();

        $ftransno = $this->input->post('ftransno', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);

        //Condition
        if ($ftransno != "") $arrWhere['incoming_num'] = $ftransno;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MIncoming_D->get_data($arrWhere, array('incoming_num'=>'DESC'), 'AND');
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
	
	public function list_tmp_post(){
        $rs = array();
        $arrWhere = array();

        $funiqid = $this->input->post('funiqid', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);

        //Condition
        if ($funiqid != "") $arrWhere['tmp_incoming_uniqid'] = $funiqid;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;
        if ($fserialnum != "") $arrWhere['serial_number'] = $fserialnum;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MIncoming_T->get_data($arrWhere, array('tmp_incoming_id'=>'ASC'), 'AND');
        if (!empty($rs)){
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

    /**
     * This function is used to add new tickets to the system
     */
    function create_trans_post()
    {
        $ftransno = $this->input->post('ftransno', TRUE);
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fdate = $this->input->post('fdate', TRUE);
        // $fticket = $this->input->post('fticket', TRUE);
        // $fengineer_id = $this->input->post('fengineer_id', TRUE);
        // $fengineer2_id = $this->input->post('fengineer2_id', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $fcode_from = $this->input->post('fcode_from', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $fcreatedat = date('Y-m-d H:i:s');
        
        // $dataInfo = array('incoming_num'=>$ftransno, 'incoming_date'=>$fdate, 'incoming_ticket'=>$fticket, 'engineer_key'=> $fengineer_id, 
			// 'engineer_2_key'=> $fengineer2_id, 'incoming_purpose'=> $fpurpose, 'incoming_qty'=> $fqty, 'user_key'=> $fuser, 'incoming_notes'=> $fnotes, 
			// 'created_at'=>$fcreatedat);
			
        $dataInfo = array('incoming_num'=>$ftransno, 'outgoing_num'=>$ftrans_out, 'incoming_date'=>$fdate, 'incoming_purpose'=> $fpurpose, 'incoming_qty'=> $fqty, 
			'user_key'=> $fuser, 'fsl_code'=> $fcode, 'fsl_from_code'=> $fcode_from, 'incoming_notes'=> $fnotes, 'created_at'=>$fcreatedat);

        $result = $this->MIncoming->insert_data($dataInfo);
        
        if($result > 0)
        {
            $this->response([
                'status' => TRUE,
                'message' => 'Data created successfully'
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
     * This function is used to add new tickets detail to the system
     */
    function create_trans_detail_post()
    {
        $ftransno = $this->input->post('ftransno', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        // $fnewpartnum = $this->input->post('fnewpartnum', TRUE);
        // $fnewserialnum = $this->input->post('fnewserialnum', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $fcreatedat = date('Y-m-d H:i:s');
        
        // $dataInfo = array('incoming_num'=>$ftransno, 'part_number'=>$fpartnum, 'serial_number'=>$fserialnum, 
			// 'dt_incoming_qty'=>$fqty, 'new_part_number'=>$fnewpartnum, 'new_serial_number'=>$fnewserialnum, 
			// 'dt_notes'=>$fnotes, 'created_at'=>$fcreatedat);
			
        $dataInfo = array('incoming_num'=>$ftransno, 'part_number'=>$fpartnum, 'serial_number'=>$fserialnum, 
			'dt_incoming_qty'=>$fqty, 'return_status'=>$fstatus, 'dt_notes'=>$fnotes, 'created_at'=>$fcreatedat);

        $result = $this->MIncoming_D->insert_data($dataInfo);
        
        if($result > 0)
        {
            $this->response([
                'status' => TRUE,
                'message' => 'Data created successfully'
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
	
	function cur_stock_tmp($partnum, $serialnum, $cartid){
        $rs = array();
        $arrWhere = array();
		$stock = 0;

        $arrWhere = array('part_number'=>$partnum, 'serial_number'=>$serialnum, 'tmp_incoming_uniqid'=>$cartid);
        
        $rs = $this->MIncoming_T->get_data($arrWhere, array(), 'AND');
        if ($rs){
			foreach($rs as $r){
				$stock = $r["tmp_incoming_qty"];
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
	
	/**
     * This function is used to add new tickets detail to the system
     */
    function create_trans_tmp_post()
    {
		$arrWhere = array();
		$dataInfo = array();
		$dataInfo2 = array();
		
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fpartname = $this->input->post('fpartname', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fcartid = $this->input->post('fcartid', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        // $fnewpartnum = $this->input->post('fnewpartnum', TRUE);
        // $fnewserialnum = $this->input->post('fnewserialnum', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
        $fname = $this->input->post('fname', TRUE);
        
        // $dataInfo = array('part_number'=>$fpartnum, 'part_name'=>$fpartname, 'serial_number'=>$fserialnum, 'tmp_incoming_uniqid'=>$fcartid, 'tmp_incoming_qty'=>$fqty, 
			// 'return_status'=>$fstatus, 'new_part_number'=>$fnewpartnum, 'new_serial_number'=>$fnewserialnum, 
			// 'tmp_notes'=>$fnotes, 'user'=>$fuser, 'fullname'=>$fname);
			
        $dataInfo = array('part_number'=>$fpartnum, 'part_name'=>$fpartname, 'serial_number'=>$fserialnum, 'tmp_incoming_uniqid'=>$fcartid, 'tmp_incoming_qty'=>$fqty, 
			'return_status'=>$fstatus, 'tmp_notes'=>$fnotes, 'user'=>$fuser, 'fullname'=>$fname);

		$arrWhere = array('part_number'=>$fpartnum, 'serial_number'=>$fserialnum, 'tmp_incoming_uniqid'=>$fcartid);
		$exist = $this->MIncoming_T->check_data_exists($arrWhere);
		
		if($exist > 0){			
			$dataInfo2 = array('return_status'=>$fstatus, 'tmp_notes'=>$fnotes);
			$result2 = $this->MIncoming_T->update_data_2($dataInfo2, $fpartnum, $fserialnum, $fcartid);
        
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
			$result = $this->MIncoming_T->insert_data($dataInfo);
			
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
    function create_trans_tmp_r_post()
    {
		$arrWhere = array();
		$dataInfo = array();
		$dataInfo2 = array();
		
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fpartname = $this->input->post('fpartname', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fcartid = $this->input->post('fcartid', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
        $fname = $this->input->post('fname', TRUE);
        
        $dataInfo = array('part_number'=>$fpartnum, 'part_name'=>$fpartname, 'serial_number'=>$fserialnum, 'tmp_incoming_uniqid'=>$fcartid, 'tmp_incoming_qty'=>$fqty, 
		'return_status'=>'RG', 'user'=>$fuser, 'fullname'=>$fname);

		$arrWhere = array('part_number'=>$fpartnum, 'serial_number'=>$fserialnum, 'tmp_incoming_uniqid'=>$fcartid);
		$exist = $this->MIncoming_T->check_data_exists($arrWhere);
		
		if($exist > 0){
			$this->response([
				'status' => FALSE,
				'message' => 'Data verified twice'
			], REST_Controller::HTTP_OK);
		}else{
			$result = $this->MIncoming_T->insert_data($dataInfo);
			
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
        
        $dataInfo = array('tmp_incoming_qty'=>$fqty);

        $result = $this->MIncoming_T->update_data($dataInfo, $fid);
        
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
    function delete_cart_post()
    {
        $fid = $this->input->post('fid', TRUE);

        $result = $this->MIncoming_T->delete_data($fid);

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
	
	/**
     * This function is used to delete the data using data id
     * @return boolean $result : TRUE / FALSE
     */
    function delete_multi_cart_post()
    {
        $fcartid = $this->input->post('fcartid', TRUE);

        $result = $this->MIncoming_T->delete_data2($fcartid);

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
	
	/**
     * This function is used to clear the data using data ticket number
     * @return boolean $result : TRUE / FALSE
     */
    function clear_tickets_cart_post()
    {
        $fticket = $this->input->post('fticket', TRUE);

        $result = $this->MIncoming_T->delete_data_by_ticket($fticket);

        if($result == true)
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
     * This function is used to check the data using data info
     * @return boolean $result : TRUE / FALSE
     */
    function check_tickets_cart_post()
    {
        $fticket = $this->input->post('fticket', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
		
		$arrWhere = array('pticket_number'=>$fticket, 'part_number'=>$fpartnum);

        $result = $this->MIncoming_T->check_data_exists($arrWhere);

        if($result > 0)
        {
            $this->response([
                'status' => TRUE
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => FALSE
            ], REST_Controller::HTTP_OK);
        }
    }
	
	public function total_cart_post(){
        $rs = array();
        $arrWhere = array();

        $funiqid = $this->input->post('funiqid', TRUE);

        //Condition
        if ($funiqid != "") $arrWhere['tmp_incoming_uniqid'] = $funiqid;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MIncoming_T->count_cart($arrWhere);
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
        
        $rs = $this->MIncoming_T->get_data_info_2($fpartnum, $fserialnum, $funiqid);
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

    /**
     * This function is used to get new tickets number from the system
     */
    function grab_ticket_num_post()
    {
		$fparam = $this->input->post('fparam', TRUE);
		
        $result = $this->MIncoming->get_key_data_sql($fparam, 5);
        $ranstring = $this->common->randomString();
        
		$arrWhere = array('incoming_num'=>$result);
        $count_exist = $this->MIncoming->check_data_exists($arrWhere);

        if($count_exist > 0)
        {
			$result2 = $this->MIncoming->get_key_data_sql($fparam, 5);
			$result = $result2;
        }
        else
        {
			$result = $result;
        }
		
        $this->response([
            'status' => TRUE,
            'result' => $result
        ], REST_Controller::HTTP_OK);
    }
	
    /**
     * This function is used to get new tickets number from the system
     */
    function grab_ticket_num_ext_post()
    {
		$fparam = $this->input->post('fparam', TRUE);
		$fdigits = $this->input->post('fdigits', TRUE);
		
        // $result = $this->MIncoming->get_key_data($fparam, $fcode);
        $result = $this->MIncoming->get_key_data_ext($fparam, $fdigits);
        $ranstring = $this->common->randomString();
        
		$arrWhere = array('incoming_num'=>$result);
        $count_exist = $this->MIncoming->check_data_exists($arrWhere);

        if($count_exist > 0)
        {
			// $result2 = $this->MIncoming->get_key_data($fparam, $fcode);
			$result2 = $this->MIncoming->get_key_data_ext($fparam, $fdigits);
			$result = $result2;
        }
        else
        {
			$result = $result;
        }
		
        $this->response([
            'status' => TRUE,
            'result' => $result
        ], REST_Controller::HTTP_OK);
    }
}