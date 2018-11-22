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
class CPDeliveryNote extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/PDeliveryNote_model','MDeliveryNote');
        $this->load->model('api/PDeliveryNoteDetail_model','MDeliveryNote_D');
        $this->load->model('api/PDeliveryNoteTmp_model','MDeliveryNote_T');
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
        //$this->output->enable_profiler(TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fticket = $this->input->post('fticket', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_out != "") $arrWhere['delivery_note_num'] = $ftrans_out;
        
        if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['date_1'] = $fdate1;
            $arrWhere['date_2'] = $fdate2;
		}else{
            $arrWhere['date_1'] = $date_before;
            $arrWhere['date_2'] = $date;
		}
        $arrWhere["is_deleted"] = 0;
        //array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MDeliveryNote->get_viewdata($arrWhere, array('delivery_note_num'=>'DESC'), 'AND');
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
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_out != "") $arrWhere['delivery_note_num'] = $ftrans_out;
        if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['date_1'] = $fdate1;
            $arrWhere['date_2'] = $fdate2;
		}else{
            $arrWhere['date_1'] = $date_before;
            $arrWhere['date_2'] = $date;
		}
        if ($fstatus != "") $arrWhere['delivery_note_status'] = $fstatus;
        $arrWhere["is_deleted"] = 0;
        //array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MDeliveryNote->get_viewdata($arrWhere, array('delivery_note_num'=>'DESC'), 'AND');
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
        //$this->output->enable_profiler(TRUE);
        $rs = array();
        $arrWhere = array();

        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        
        //Condition
        if ($fdate1 != "") $arrWhere['date_1'] = $fdate1;
        if ($fdate2 != "") $arrWhere['date_2'] = $fdate2;

        $arrWhere["is_deleted"] = 0;
        //array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MDeliveryNote_D->get_viewdata($arrWhere, array('delivery_note_num'=>'DESC'), 'AND');
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
        if ($funiqid != "") $arrWhere['tmp_delivery_note_uniqid'] = $funiqid;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;
        if ($fserialnum != "") $arrWhere['serial_number'] = $fserialnum;
        if ($fuser != "") $arrWhere['user'] = $fuser;
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MDeliveryNote_T->get_data($arrWhere, array('tmp_delivery_note_id'=>'ASC'), 'AND');
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
        $fpurpose = $this->input->post('fpurpose', TRUE);
        $ftransnotes = $this->input->post('ftransnotes', TRUE);
        $ffsl_code = $this->input->post('fdest_fsl', TRUE);
        $fairwaybill = $this->input->post('fairwaybill', TRUE);
        $fairwaybill2 = $this->input->post('fairwaybill2', TRUE);
        $fservice = $this->input->post('fservice', TRUE);
        $fdelivery_by = $this->input->post('fdeliveryby');
        $feta = $this->input->post('feta',TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
        $fcreatedat = date('Y-m-d H:i:s');
        
        $dataInfo = array(
            'delivery_note_num'=>$ftransno, 
            'delivery_note_date'=>$fdate, 
            'delivery_note_purpose'=> $fpurpose, 
            'delivery_note_notes'=> $ftransnotes,
            'fsl_code'=> $ffsl_code, 
            'delivery_note_airwaybill'=> $fairwaybill,
            'delivery_note_airwaybill2'=> $fairwaybill2,
            'delivery_time_type' => $fservice,
            'delivery_by' => $fdelivery_by,
            'delivery_note_eta'=>$feta,
            'delivery_note_qty'=> $fqty, 
			'user_key'=> $fuser, 
            'created_at'=>$fcreatedat
        );

        $result = $this->MDeliveryNote->insert_data($dataInfo);
        
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
        $fnotes = $this->input->post('fnotes', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        
		if(empty($fnotes)){
			$dataInfo = array('delivery_note_status'=>$fstatus);
		}else{
			$dataInfo = array('delivery_note_notes'=>$fnotes, 'delivery_note_status'=>$fstatus);
		}

        $result = $this->MDeliveryNote->update_data($dataInfo, $ftrans_out);
        
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
        
        $dataInfo = array(
            'delivery_note_num'=>$ftransno, 
            'part_number'=>$fpartnum, 
            'serial_number'=>$fserialnum, 
			'dt_delivery_note_qty'=>$fqty, 
            'created_at'=>$fcreatedat
        );

        $result = $this->MDeliveryNote_D->insert_data($dataInfo);
        
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
        $fstatus = "RG";
        
        $dataInfo = array('return_status'=>$fstatus);

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
	
	function cur_stock_tmp($partnum, $serialnum, $cartid){
        $rs = array();
        $arrWhere = array();
		$stock = 0;

        $arrWhere = array('part_number'=>$partnum, 'serial_number'=>$serialnum, 'tmp_delivery_note_uniqid'=>$cartid);
        
        $rs = $this->MDeliveryNote_T->get_data($arrWhere, array(), 'AND');
        if ($rs){
			foreach($rs as $r){
				$stock = $r["tmp_delivery_note_qty"];
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
        $fuser = $this->input->post('fuser', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        
        $dataInfo = array(
            'part_number'=>$fpartnum, 
            'part_name'=>$fpartname, 
            'serial_number'=>$fserialnum, 
            'tmp_delivery_note_uniqid'=>$fcartid, 
			'tmp_delivery_note_qty'=>$fqty, 
            'user'=>$fuser, 
            'fullname'=>$fname, 
            'fsl_code'=>$fcode
        );

		$arrWhere = array(
            'part_number'=>$fpartnum, 
            'serial_number'=>$fserialnum, 
            'tmp_delivery_note_uniqid'=>$fcartid
        );
		$exist = $this->MDeliveryNote_T->check_data_exists($arrWhere);
		
		if($exist > 0){
            //get qty tmp
			$stock = $this->cur_stock_tmp($fpartnum, $fserialnum, $fcartid);
			$ustock = $stock + $fqty;
			
			$dataInfo2 = array('tmp_delivery_note_qty'=>$ustock);
			$result2 = $this->MDeliveryNote_T->update_data_2($dataInfo2, $fpartnum, $fserialnum, $fcartid);
        
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
			$result = $this->MDeliveryNote_T->insert_data($dataInfo);
			
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
        
        $dataInfo = array('tmp_delivery_note_qty'=>$fqty);

        $result = $this->MDeliveryNote_T->update_data($dataInfo, $fid);
        
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

        $result = $this->MDeliveryNote_T->delete_data($fid);

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

        $result = $this->MDeliveryNote_T->delete_data2($fcartid);

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

        $result = $this->MDeliveryNote_T->delete_data_by_ticket($fticket);

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

        $result = $this->MDeliveryNote_T->check_data_exists($arrWhere);

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
        if ($funiqid != "") $arrWhere['tmp_delivery_note_uniqid'] = $funiqid;

        // $arrWhere["is_deleted"] = 0;
        // array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MDeliveryNote_T->count_cart($arrWhere);
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
        
        $rs = $this->MDeliveryNote_T->get_data_info_2($fpartnum, $fserialnum, $funiqid);
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
		// $fcode = $this->input->post('fcode', TRUE);
		
        // $result = $this->MOutgoing->get_key_data($fparam, $fcode);
        $result = $this->MDeliveryNote->get_key_data($fparam,6);
        $ranstring = $this->common->randomString();
        
        $this->response([
            'status' => TRUE,
            'result' => $result
        ], REST_Controller::HTTP_OK);
    }
    
    
    function list_delivery_time_post(){
        $rs = array();

        $fsl_code = $this->input->post('ffsl_code', TRUE);
        $delivery_type = $this->input->post('fdelivery_type', TRUE);
        $delivery_by = $this->input->post('fdelivery_by', TRUE);
        
        $rs = $this->MDeliveryNote->get_eta($fsl_code, $delivery_type, $delivery_by);
        
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
    
    function get_data_detail_post(){
        $rs = array();
        $transnum = $this->input->post('transnum', TRUE);
        $rs = $this->MDeliveryNote->get_detail_exists($transnum);
        
        if (!empty($rs) || count($rs) > 0){
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

    public function get_trans_detail_post(){
        $arrWhere = array();
        $rs = array();
        
        $transnum = $this->input->post('ftransnum', TRUE);

        if ($transnum != "") $arrWhere['delivery_note_num'] = $transnum;

        $rs = $this->MDeliveryNote_D->get_viewdata($arrWhere);
        
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

    public function get_trans_post(){
        $arrWhere = array();
        $rs = array();
        $data = array();
        
        $transnum = $this->input->post('ftransnum', TRUE);

        if ($transnum != "") $arrWhere['delivery_note_num'] = $transnum;

        $rs = $this->MDeliveryNote->get_viewdata($arrWhere);
        if (!empty($rs)){
            foreach ($rs as $transdata){
                $data = $transdata;
            }
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
}
?>