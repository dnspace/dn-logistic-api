<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CPFslToCWH (CPFslToCWH - Controller)
 * CPFslToCWH class to control transactions
 * @author : Khazefa & Abasworm
 * @version : 2.0
 * @since : Sept 2017
 */
class CPFsltocwh extends REST_Controller
{
    private $fields;

    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Pfsltocwh_model','MTrans');
        $this->load->model('api/PfsltocwhDetail_model','MTrans_D');
        $this->load->model('api/PfsltocwhTmp_model','MTrans_T');
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 5000; // 5000 requests per hour per user/key

    }

    public function list_post(){
        $rs = array();
        $arrWhere = array();
        //$this->output->enable_profiler(TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        // $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
        //Condition
        //if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        // if ($ftrans_out != "") $arrWhere['fsltocwh_num'] = $ftrans_out;
        
        if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['date_1'] = $fdate1;
            $arrWhere['date_2'] = $fdate2;
		}else{
            $arrWhere['date_1'] = $date_before;
            $arrWhere['date_2'] = $date;
        }
        if($fstatus != '') $arrWhere['fsltocwh_status'] = $fstatus;
        
        $arrWhere["is_deleted"] = 0;
        //array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MTrans->get_viewdata($arrWhere, array('fsltocwh_num'=>'DESC'), 'AND');
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

    public function list2_post(){
        $rs = array();
        $arrWhere = array();
        //$this->output->enable_profiler(TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        // $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
        //Condition
        //if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        // if ($ftrans_out != "") $arrWhere['fsltocwh_num'] = $ftrans_out;
        
        if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['date_close_1'] = $fdate1;
            $arrWhere['date_close_2'] = $fdate2;
		}else{
            $arrWhere['date_close_1'] = $date_before;
            $arrWhere['date_close_2'] = $date;
        }
        if($fstatus != '') $arrWhere['fsltocwh_status'] = $fstatus;
        
        $arrWhere["is_deleted"] = 0;
        //array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MTrans->get_viewdata_close($arrWhere, array('fsltocwh_num'=>'DESC'), 'AND');
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
	
    /**
     * This function used to load the first screen of the user
     */
	public function list_view_post(){
        $rs = array();
        $arrWhere = array();

        $fcode = $this->input->post('fcode', TRUE);
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_out != "") $arrWhere['fsltocwh_num'] = $ftrans_out;
        if($fstatus != '') $arrWhere['fsltocwh_status'] = $fstatus;
        if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['date_1'] = $fdate1;
            $arrWhere['date_2'] = $fdate2;
		}else{
            $arrWhere['date_1'] = $date_before;
            $arrWhere['date_2'] = $date;
        }
        
        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MTrans->get_viewdata($arrWhere, array('fsltocwh_num'=>'DESC'), 'AND');
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
        
        $rs = $this->MTrans_D->get_viewdata($arrWhere, array('fsltocwh_num'=>'DESC'), 'AND');
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
        if ($funiqid != "") $arrWhere['tmp_fsltocwh_uniqid'] = $funiqid;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;
        if ($fserialnum != "") $arrWhere['serial_number'] = $fserialnum;
        if ($fuser != "") $arrWhere['user'] = $fuser;
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MTrans_T->get_data($arrWhere, array('tmp_fsltocwh_id'=>'ASC'), 'AND');
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
        $fuser = $this->input->post('fuser', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        
        $dataInfo = array(
            'part_number'=>$fpartnum, 
            'part_name'=>$fpartname, 
            'serial_number'=>$fserialnum, 
            'tmp_fsltocwh_uniqid'=>$fcartid, 
            'tmp_fsltocwh_qty'=>$fqty, 
            'user'=>$fuser, 
            'fullname'=>$fname, 
            'fsl_code'=>$fcode
        );

        $arrWhere = array(
            'part_number'=>$fpartnum, 
            'serial_number'=>$fserialnum, 
            'tmp_fsltocwh_uniqid'=>$fcartid
        );
        $exist = $this->MTrans_T->check_data_exists($arrWhere);
        
        if($exist > 0){
            //get qty tmp
            $stock = $this->cur_stock_tmp($fpartnum, $fserialnum, $fcartid);
            $ustock = $stock + $fqty;
            
            $dataInfo2 = array('tmp_fsltocwh_qty'=>$ustock);
            $result2 = $this->MTrans_T->update_data_2($dataInfo2, $fpartnum, $fserialnum, $fcartid);
        
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
            $result = $this->MTrans_T->insert_data($dataInfo);
            
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
    function create_trans_detail_post()
    {
        $ftransno = $this->input->post('ftransno', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fcreatedat = date('Y-m-d H:i:s');
        
        $dataInfo = array(
            'fsltocwh_num'=>$ftransno, 
            'part_number'=>$fpartnum, 
            'serial_number'=>$fserialnum, 
            'dt_fsltocwh_qty'=>$fqty, 
            'created_at'=>$fcreatedat
        );

        $result = $this->MTrans_D->insert_data($dataInfo);
        
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
            'fsltocwh_num'=>$ftransno, 
            'fsltocwh_date'=>$fdate, 
            'fsltocwh_purpose'=> $fpurpose, 
            'fsltocwh_notes'=> $ftransnotes,
            'fsl_code'=> $ffsl_code, 
            'fsltocwh_airwaybill'=> $fairwaybill,
            'fsltocwh_airwaybill2'=> $fairwaybill2,
            'delivery_time_type' => $fservice,
            'delivery_by' => $fdelivery_by,
            'fsltocwh_eta'=>$feta,
            'fsltocwh_qty'=> $fqty, 
			'user_key'=> $fuser, 
            'created_at'=>$fcreatedat
        );

        $result = $this->MTrans->insert_data($dataInfo);
        
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

        $arrWhere = array('part_number'=>$partnum, 'serial_number'=>$serialnum, 'tmp_fsltocwh_uniqid'=>$cartid);
        
        $rs = $this->MTrans_T->get_data($arrWhere, array(), 'AND');
        if ($rs){
			foreach($rs as $r){
				$stock = $r["tmp_fsltoucwh_qty"];
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
    function update_cart_post()
    {
        $fid = $this->input->post('fid', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        
        $dataInfo = array('tmp_fsltocwh_qty'=>$fqty);

        $result = $this->MTrans_T->update_data($dataInfo, $fid);
        
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

        $result = $this->MTrans_T->delete_data($fid);

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

        $result = $this->MTrans_T->delete_data2($fcartid);

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
        if ($funiqid != "") $arrWhere['tmp_fsltocwh_uniqid'] = $funiqid;

        // $arrWhere["is_deleted"] = 0;
        // array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MTrans_T->count_cart($arrWhere);
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
        
        $rs = $this->MTrans_T->get_data_info_2($fpartnum, $fserialnum, $funiqid);
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
		$fcode = $this->input->post('fcode', TRUE);
		
        // $result = $this->MOutgoing->get_key_data($fparam, $fcode);
        $result = $this->MTrans->get_key_data($fparam, 6);
        $this->response([
            'status' => TRUE,
            'result' => $result
        ], REST_Controller::HTTP_OK);
    }
    
    
    function list_delivery_time_post(){
        $rs = array();

        $fsl_code = $this->input->post('ffsl_code', TRUE);
        $delivery_type = $this->input->post('fdelivery_type', TRUE);
        
        $rs = $this->MTrans->get_eta($fsl_code, $delivery_type);
        
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
        $rs = $this->MTrans->get_detail_exists($transnum);
        
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

        if ($transnum != "") {
            $arrWhere['fsltocwh_num'] = $transnum;
        }else{
            $arrWhere['fsltocwh_num'] = "";
        }
        
        $arrWhere['is_deleted'] = '0';

        $rs = $this->MTrans_D->get_viewdata($arrWhere);
        
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

        if ($transnum != "") $arrWhere['fsltocwh_num'] = $transnum;

        $rs = $this->MTrans->get_viewdata($arrWhere);
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

    /*
    * update diff with insert data (mutation of row)
    */
    public function update_different_detail2_post(){

        $arrWhere = array();
        $rs_detail = array();
        $rs_insert = array();
        $data = array();
        $data_update = array();
        $data_add = array();
        $return_update = FALSE;
        $return_insert = FALSE;
        
        $fiddetail = $this->input->post('fid', TRUE);
        $fnewpn = $this->input->post('fnewpn',TRUE);
        $fnewsn = $this->input->post('fnewsn',TRUE);
        $fnotes = $this->input->post('faction', TRUE);
        
        if($fiddetail != ""){
            $rs_detail = $this->MTrans_D->get_data_info2($fiddetail);
            if(!empty($rs_detail)){
                if ($fnotes == 'diff_partnumber'){
                    if ($fnewpn != ""){
                        $data_update['old_part_number'] = $fnewpn;
                        $data_update['dt_notes'] = $fnotes;
                        $data_update['is_deleted'] = '1';
                        $data_add['part_number'] = $fnewpn;
                        $data_add['serial_number'] = $rs_detail['serial_number'];
                        $data_add['dt_notes'] = $fnotes;
                    }
                }
                else if($fnotes == 'diff_serialnumber'){
                    if ($fnewsn != ""){
                        $data_update['old_serial_number'] = $fnewsn;
                        $data_update['dt_notes'] = $fnotes;
                        $data_update['is_deleted'] = '1';
                        $data_add['part_number'] = $rs_detail['part_number'];
                        $data_add['serial_number'] = $fnewsn;
                        $data_add['dt_notes'] = $fnotes;
                    }
                }
                else if($fnotes == 'diff_pn_and_sn'){
                    if ($fnewsn != ""){
                        $data_update['old_part_number'] = $fnewpn;
                        $data_update['old_serial_number'] = $fnewsn;
                        $data_update['dt_notes'] = $fnotes;
                        $data_update['is_deleted'] = '1';
                        $data_add['part_number'] = $fnewpn;
                        $data_add['serial_number'] = $fnewsn;
                        $data_add['dt_notes'] = $fnotes;
                    }
                }
                else if($fnotes != ""){
                    $data_update['dt_notes'] = $fnotes;
                }

                if(!empty($data_update)){
                    $return_update = $this->MTrans_D->update_data3($data_update,$fiddetail);
                }

                $data_add['fsltocwh_num'] = $rs_detail['fsltocwh_num'];
                $data_add['dt_fsltocwh_qty'] = $rs_detail['dt_fsltocwh_qty'];
                $data_add['old_fsltocwh_id'] = $fiddetail;
                $return_insert = $this->MTrans_D->insert_data($data_add);
                
                $this->response([
                    'status' => TRUE,
                    'message' => 'Success update ' . $return_update . ' with new ID ' . $return_insert
                ], REST_Controller::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Failed retrive detail.'
                ], REST_Controller::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Missing parameter detail id '.$fiddetail
            ], REST_Controller::HTTP_OK);
        }
    }

    /*
    * Update Detail 
    */
    public function update_different_detail_post(){

        $arrWhere = array();
        $rs_detail = array();
        $rs_insert = array();
        $data = array();
        $data_update = array();
        $data_add = array();
        $return_update = FALSE;
        $return_insert = FALSE;
        
        $fiddetail = $this->input->post('fid', TRUE);
        $fnewpn = $this->input->post('fnewpn',TRUE);
        $fnewsn = $this->input->post('fnewsn',TRUE);
        $fnotes = $this->input->post('faction', TRUE);
        
        if($fiddetail != ""){
            $rs_detail = $this->MTrans_D->get_data_info2($fiddetail);
            if(!empty($rs_detail)){
                if ($fnotes == 'diff_partnumber'){
                    if ($fnewpn != ""){
                        $data_update['old_part_number'] = $fnewpn;
                        $data_update['old_serial_number'] = $fnewsn;
                        $data_update['dt_notes'] = $fnotes;
                        //$data_update['is_deleted'] = '1';
                        $data_update['part_number'] = $rs_detail['part_number'];
                        $data_update['serial_number'] = $rs_detail['serial_number'];
                        //$data_add['dt_notes'] = $fnotes;
                    }
                }
                else if($fnotes == 'diff_serialnumber'){
                    if ($fnewsn != ""){
                        $data_update['old_serial_number'] = $rs_detail['serial_number'];
                        $data_update['dt_notes'] = $fnotes;
                        //$data_update['is_deleted'] = '1';
                        //$data_add['part_number'] = $rs_detail['part_number'];
                        $data_update['serial_number'] = $fnewsn;
                        //$data_add['dt_notes'] = $fnotes;
                    }
                }
                
                else if($fnotes != ""){
                    $data_update['dt_notes'] = $fnotes;
                }

                if(!empty($data_update)){
                    $return_update = $this->MTrans_D->update_data3($data_update,$fiddetail);
                }

                //$data_add['fsltocwh_num'] = $rs_detail['fsltocwh_num'];
                //$data_add['dt_fsltocwh_qty'] = $rs_detail['dt_fsltocwh_qty'];
                //$data_add['old_fsltocwh_id'] = $fiddetail;
                //$return_insert = $this->MTrans_D->insert_data($data_add);
                
                $this->response([
                    'status' => TRUE,
                    'message' => 'Success update ' . $return_update //. ' with new ID ' . $return_insert
                ], REST_Controller::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Failed retrive detail.'
                ], REST_Controller::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Missing parameter detail id '.$fiddetail
            ], REST_Controller::HTTP_OK);
        }
    }
    

    public function update_detail_post(){
        $arrWhere = array();
        $rs = array();
        $return_update = FALSE;

        $fid = $this->input->post('fid', TRUE);
        $partnum = $this->input->post('fpartnum', TRUE);
        $serialnum = $this->input->post('fserialnum', TRUE);
        $qty = $this->input->post('fqty', TRUE);
        $notes = $this->input->post('fnotes', TRUE);
        
        if($fid != ''){
            if($partnum != '')$arrWhere['part_number']=$partnum;
            if($serialnum != '')$arrWhere['serial_number']=$serialnum;
            if($qty != '')$arrWhere['dt_fsltocwh_qty']=$qty;
            if($notes != '')$arrWhere['dt_notes']=$notes;
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Need an Parameter ID.'
            ], REST_Controller::HTTP_OK);
        }

        if(!empty($arrWhere)){
            $return_update = $this->MTrans_D->update_data3($arrWhere,$fid);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data to update.'
            ], REST_Controller::HTTP_OK);
        }
        var_dump($return_update);
        if($return_update)
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

    /*
    * Closing ini berfungsi sebagai closing dari fsltocwh, tetapi juga menambahkan stok dan duplikasi data ro diganti nama depannya menjadi RO.
    */
    public function closing_trans_post(){
        $arrWhere = array();
        $rs = array();
        $return_closing = FALSE;

        $transnum = $this->input->post('ftransnum', TRUE);
        $notes = $this->input->post('fnotes', TRUE);
        $user = $this->input->post('fuser', TRUE);

        if($transnum != ''){
            $rs_update = $this->MTrans->closing_trans($transnum,$notes,$user);
            $this->response([
                'status' => TRUE,
                'message' => 'Data updated.',
                'result' => $rs_update
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Transnum parameter not defined.'
            ], REST_Controller::HTTP_OK);
        }
    }
}
?>