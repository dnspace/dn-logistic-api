<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CPOutgoings (CPOutgoingsController)
 * CPOutgoings class to control transactions
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class CPOutgoings extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/POutgoing_model','MOutgoing');
        $this->load->model('api/POutgoingDetail_model','MOutgoing_D');
        $this->load->model('api/POutgoingTmp_model','MOutgoing_T');
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
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fticket = $this->input->post('fticket', TRUE);
        $fcode_dest = $this->input->post('fcode_dest', TRUE);

        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_out != "") $arrWhere['outgoing_num'] = $ftrans_out;
        if ($fticket != "") $arrWhere['outgoing_ticket'] = $fticket;
        if ($fcode_dest != "") $arrWhere['fsl_dest_code'] = $fcode_dest;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MOutgoing->get_data($arrWhere, array('outgoing_num'=>'DESC'), 'AND');
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
	
	public function list_view_post(){
        $rs = array();
        $arrWhere = array();

        $fcode = $this->input->post('fcode', TRUE);
        $fdest_code = $this->input->post('fdest_code', TRUE);
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fticket = $this->input->post('fticket', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
		$date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
		
        //Condition
        if ($fdest_code != "") $arrWhere['fsl_dest'] = $fdest_code;
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_out != "") $arrWhere['outgoing_num'] = $ftrans_out;
		if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['created_at_1'] = $fdate1;
            $arrWhere['created_at_2'] = $fdate2;
		}else{
            $arrWhere['created_at_1'] = $date_before;
            $arrWhere['created_at_2'] = $date;
		}
        if ($fticket != "") $arrWhere['outgoing_ticket'] = $fticket;
        if ($fpurpose != "") $arrWhere['outgoing_purpose'] = $fpurpose;
        if ($fstatus != "") $arrWhere['outgoing_status'] = $fstatus;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MOutgoing->get_viewdata($arrWhere, array('outgoing_num'=>'DESC'), 'AND');
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
	
	public function list_view_history_post(){
        $rs = array();
        $arrWhere = array();

        $fcode = $this->input->post('fcode', TRUE);
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fticket = $this->input->post('fticket', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
		$date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
		
        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_out != "") $arrWhere['outgoing_num'] = $ftrans_out;
		if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['created_at_1'] = $fdate1;
            $arrWhere['created_at_2'] = $fdate2;
		}
		
        if ($fticket != "") $arrWhere['outgoing_ticket'] = $fticket;
        if ($fpurpose != "") $arrWhere['outgoing_purpose'] = $fpurpose;
        if ($fstatus != "") $arrWhere['outgoing_status'] = $fstatus;
        
        $rs = $this->MOutgoing->get_viewdata($arrWhere, array('outgoing_num'=>'DESC'), 'AND');
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

        $fid = $this->input->post('fid', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fticket = $this->input->post('fticket', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
		
        //Condition
        if ($fid != "") $arrWhere['outgoing_id'] = $fid;
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_out != "") $arrWhere['outgoing_num'] = $ftrans_out;
        if ($fticket != "") $arrWhere['outgoing_ticket'] = $fticket;
        if ($fpurpose != "") $arrWhere['outgoing_purpose'] = $fpurpose;
        if ($fstatus != "") $arrWhere['outgoing_status'] = $fstatus;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MOutgoing->get_viewdata($arrWhere, array('outgoing_num'=>'DESC'), 'AND');
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

        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);

        //Condition
        if ($ftrans_out != "") $arrWhere['outgoing_num'] = $ftrans_out;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;

        // $arrWhere["is_deleted"] = 0;
        // array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MOutgoing_D->get_viewdata($arrWhere, array('outgoing_num'=>'DESC'), 'AND');
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

        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);

        //Condition
        if ($ftrans_out != "") $arrWhere['outgoing_num'] = $ftrans_out;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;
        if ($fserialnum != "") $arrWhere['serial_number'] = $fserialnum;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MOutgoing_D->get_data($arrWhere, array('part_number'=>'DESC'), 'AND');
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
        $fuser = $this->input->post('fuser', TRUE);
        $fcode = $this->input->post('fcode', TRUE);

        //Condition
        if ($funiqid != "") $arrWhere['tmp_outgoing_uniqid'] = $funiqid;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;
        if ($fserialnum != "") $arrWhere['serial_number'] = $fserialnum;
        if ($fuser != "") $arrWhere['user'] = $fuser;
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MOutgoing_T->get_data($arrWhere, array('tmp_outgoing_id'=>'ASC'), 'AND');
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
	
	/**
	* This function is used to check data exist
	*/
	function is_data_exist(){
		$arrWhere = array('part_number'=>$fpartnum);
		$exist = $this->MParts->check_data_exists($arrWhere);
		
		if($exist > 0){
            $this->response([
                'status' => FALSE,
                'message' => 'Data already exist'
            ], REST_Controller::HTTP_OK);
		}else{
			
		}
	}

    /**
     * This function is used to add new tickets to the system
     */
    function create_trans_post()
    {
        $ftransno = $this->input->post('ftransno', TRUE);
        $fdate = $this->input->post('fdate', TRUE);
        $fticket = $this->input->post('fticket', TRUE);
        $fengineer_id = $this->input->post('fengineer_id', TRUE);
        $fengineer2_id = $this->input->post('fengineer2_id', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
        $fdelivery = $this->input->post('fdelivery', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $fcust = $this->input->post('fcust', TRUE);
        $floc = $this->input->post('floc', TRUE);
        $fssb_id = $this->input->post('fssb_id', TRUE);
        $fdest_code = $this->input->post('fdest_code', TRUE);
        $fcreatedat = date('Y-m-d H:i:s');
        
        $dataInfo = array('outgoing_num'=>$ftransno, 'outgoing_date'=>$fdate, 'outgoing_ticket'=>$fticket, 'engineer_key'=> $fengineer_id, 
			'engineer_2_key'=> $fengineer2_id, 'outgoing_purpose'=> $fpurpose, 'o_delivery_notes'=> $fdelivery, 'outgoing_qty'=> $fqty, 
			'user_key'=> $fuser, 'fsl_code'=> $fcode, 'fsl_dest_code'=> $fdest_code, 'outgoing_notes'=> $fnotes, 'outgoing_cust'=> $fcust, 'outgoing_loc'=> $floc, 
			'outgoing_ssbid'=> $fssb_id, 'created_at'=>$fcreatedat);

        // $count = $this->MOutgoing->check_data_exists(array('outgoing_num' => $ftransno));
        // if ($count > 0)
        // {
			
		// }
			
        $result = $this->MOutgoing->insert_data($dataInfo);
        
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
     * This function is used to update to the system
     */
    function update_post()
    {
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $ffe_report = $this->input->post('ffe_report', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $fclose_date = date('Y-m-d H:i:s');
        
		//check if fe report blank
		if(empty($ffe_report)){
			if($fstatus === "complete"){
				$dataInfo = array('closing_date'=>$fclose_date, 'outgoing_status'=>$fstatus);
			}else{
				$dataInfo = array('outgoing_status'=>$fstatus);
			}
		}else{
			if($fstatus === "complete"){
				$dataInfo = array('closing_date'=>$fclose_date, 'fe_report'=>$ffe_report, 'outgoing_status'=>$fstatus);
			}else{
				$dataInfo = array('fe_report'=>$ffe_report, 'outgoing_status'=>$fstatus);
			}
		}

        $result = $this->MOutgoing->update_data($dataInfo, $ftrans_out);
        
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
     * This function is used to add new tickets detail to the system
     */
    function create_trans_detail_post()
    {
        $ftransno = $this->input->post('ftransno', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fcreatedat = date('Y-m-d H:i:s');
        
        $dataInfo = array('outgoing_num'=>$ftransno, 'part_number'=>$fpartnum, 'serial_number'=>$fserialnum, 
			'dt_outgoing_qty'=>$fqty, 'created_at'=>$fcreatedat);

        $result = $this->MOutgoing_D->insert_data($dataInfo);
        
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
     * This function is used to update detail to the system
     */
    function update_detail_post()
    {
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        
        // $dataInfo = array('serial_number'=>$fserialnum, 'return_status'=>$fstatus);
        $dataInfo = array('return_status'=>$fstatus, 'dt_notes'=>$fnotes);

        $result = $this->MOutgoing_D->update_data2($dataInfo, $ftrans_out, $fpartnum, $fserialnum);
        
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
     * This function is used to update detail to the system
     */
    function update_detail_by_id_post()
    {
        $fid = $this->input->post('fid', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        
		if(empty($fstatus)){
			$dataInfo = array('serial_number'=>$fserialnum, 'dt_notes'=>$fnotes);
		}else{
			$dataInfo = array('serial_number'=>$fserialnum, 'dt_notes'=>$fnotes, 'return_status'=>$fstatus);
		}

        $result = $this->MOutgoing_D->update_data3($dataInfo, $fid);
        
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
     * This function is used to update detail status to the system by trans number
     */
    function update_detail_all_post()
    {
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        
        $dataInfo = array('return_status'=>$fstatus, 'dt_notes'=>$fnotes);

        $result = $this->MOutgoing_D->update_data($dataInfo, $ftrans_out);
        
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
	
	function cur_stock_tmp($partnum, $serialnum, $cartid){
        $rs = array();
        $arrWhere = array();
		$stock = 0;

        $arrWhere = array('part_number'=>$partnum, 'serial_number'=>$serialnum, 'tmp_outgoing_uniqid'=>$cartid);
        
        $rs = $this->MOutgoing_T->get_data($arrWhere, array(), 'AND');
        if ($rs){
			foreach($rs as $r){
				$stock = $r["tmp_outgoing_qty"];
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
	
	public function list_tmp_abandoned_post(){
        $rs = array();
        $arrWhere = array();
		
        $fcode = $this->input->post('fcode', TRUE);        
        $rs = $this->MOutgoing_T->get_abandoned_cart($fcode);
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
        $fstock = $this->input->post('fstock', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
		
		//Delete all cart entries older than one day
		// $empty_old_cart = $this->MOutgoing_T->delete_abandoned_cart($fcode);
        
        $dataInfo = array('part_number'=>$fpartnum, 'part_name'=>$fpartname, 'serial_number'=>$fserialnum, 'tmp_outgoing_uniqid'=>$fcartid, 
			'tmp_outgoing_qty'=>$fqty, 'tmp_stock'=>$fstock, 'user'=>$fuser, 'fullname'=>$fname, 'fsl_code'=>$fcode);

		$arrWhere = array('part_number'=>$fpartnum, 'serial_number'=>$fserialnum, 'tmp_outgoing_uniqid'=>$fcartid);
		$exist = $this->MOutgoing_T->check_data_exists($arrWhere);
		
		if($exist > 0){
            //get qty tmp
			$stock = $this->cur_stock_tmp($fpartnum, $fserialnum, $fcartid);
			$ustock = $stock + $fqty;
			
			$dataInfo2 = array('tmp_outgoing_qty'=>$ustock);
			$result2 = $this->MOutgoing_T->update_data_2($dataInfo2, $fpartnum, $fserialnum, $fcartid);
        
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
			$result = $this->MOutgoing_T->insert_data($dataInfo);
			
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
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fstock = $this->input->post('fstock', TRUE);
        
		if(empty($fserialnum)){
			$dataInfo = array('tmp_outgoing_qty'=>$fqty, 'tmp_stock'=>$fstock);
		}else{
			$dataInfo = array('serial_number'=>$fserialnum);
		}

        $result = $this->MOutgoing_T->update_data($dataInfo, $fid);
        
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

        $result = $this->MOutgoing_T->delete_data($fid);

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

        $result = $this->MOutgoing_T->delete_data2($fcartid);

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

        $result = $this->MOutgoing_T->delete_data_by_ticket($fticket);

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

        $result = $this->MOutgoing_T->check_data_exists($arrWhere);

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
        if ($funiqid != "") $arrWhere['tmp_outgoing_uniqid'] = $funiqid;

        // $arrWhere["is_deleted"] = 0;
        // array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MOutgoing_T->count_cart($arrWhere);
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
        
        $rs = $this->MOutgoing_T->get_data_info_2($fpartnum, $fserialnum, $funiqid);
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
		
        $result = $this->MOutgoing->get_key_data_sql($fparam, 5);
        $ranstring = $this->common->randomString();
        
		$arrWhere = array('outgoing_num'=>$result);
        $count_exist = $this->MOutgoing->check_data_exists($arrWhere);

        if($count_exist > 0)
        {
			$result2 = $this->MOutgoing->get_key_data_sql($fparam, 5);
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
		
        // $result = $this->MOutgoing->get_key_data($fparam, $fcode);
        $result = $this->MOutgoing->get_key_data_ext($fparam, $fdigits);
        $ranstring = $this->common->randomString();
        
		$arrWhere = array('outgoing_num'=>$result);
        $count_exist = $this->MOutgoing->check_data_exists($arrWhere);

        if($count_exist > 0)
        {
			// $result2 = $this->MOutgoing->get_key_data($fparam, $fcode);
			$result2 = $this->MOutgoing->get_key_data_ext($fparam, $fdigits);
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
     * This function is used to get total data by parameters
     */
    function get_total_by_post()
    {
        $rs = array();
        $arrWhere = array();

        $fcode = $this->input->post('fcode', TRUE);
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fticket = $this->input->post('fticket', TRUE);
        $fcode_dest = $this->input->post('fcode_dest', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);

        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_out != "") $arrWhere['outgoing_num'] = $ftrans_out;
		if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['created_at_1'] = $fdate1;
            $arrWhere['created_at_2'] = $fdate2;
		}else{
            $arrWhere['created_at_1'] = $date_before;
            $arrWhere['created_at_2'] = $date;
		}
        if ($fticket != "") $arrWhere['outgoing_ticket'] = $fticket;
        if ($fcode_dest != "") $arrWhere['fsl_dest_code'] = $fcode_dest;
        if ($fpurpose != "") $arrWhere['outgoing_purpose'] = $fpurpose;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MOutgoing->count_all_by($arrWhere, 'AND');
        if ((int)$rs > 0){
            $this->response([
                    'status' => TRUE,
                    'result' => (int)$rs
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                    'status' => FALSE,
                    'message' => 0
            ], REST_Controller::HTTP_OK);
        }
	}
}
?>