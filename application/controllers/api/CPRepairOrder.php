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
class CPRepairOrder extends REST_Controller
{
    private $fields;
    private $db_index_key = 'repairorder_num';
    private $db_table_name = 'repairorder';

    private $mtrans ;
    private $mdetail;

    /**
     * This is default constructor of the class
     */
    public function __construct(){
        parent::__construct();
        $this->load->model('crud/crud_model','crud');
        
        $this->mtrans = array(
            'table' => $this->db_table_name,
            'view' => 'view'.$this->db_table_name,
            'primKey' => $this->db_table_name.'_id',
            'indexKey' => $this->db_table_name.'_num',
            'order' => 'desc'
        ); //transaction variable

        $this->mdetail = array(
            'table' => $this->db_table_name.'_detail',
            'view' => 'viewdetail'.$this->db_table_name,
            'primKey' => 'dt_'.$this->db_table_name.'_id',
            'indexKey' => $this->db_table_name.'_num',
            'order' => 'desc'
        );//detail variable

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        // $this->methods['index_get']['limit'] = 5000; // 5000 requests per hour per user/key

    }
    
    public function index_get(){
        $ranstring = $this->common->randomString();
        $this->response([
            'status' => FALSE,
            'message' => $ranstring
        ], REST_Controller::HTTP_OK);
    }

    public function list_post(){
        $this->crud->set_table($this->mtrans);
        $fstatus = $this->input->post('fstatus', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
        
        if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['date_1'] = $fdate1;
            $arrWhere['date_2'] = $fdate2;
		}else{
            $arrWhere['date_1'] = $date_before;
            $arrWhere['date_2'] = $date;
        }
        if($fstatus != '') $arrWhere[$this->db_table_name.'_status'] = $fstatus;
        
        $arrWhere["is_deleted"] = 0;
        
        $rs = $this->crud->get_viewdata($arrWhere, array($this->db_index_key=>'DESC'), 'AND');
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
        $this->crud->set_table($this->mtrans);
        $fcode = $this->input->post('fcode', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        // $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
        //Condition
        //if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        // if ($ftrans_out != "") $arrWhere[$this->db_index_key] = $ftrans_out;
        
        if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['date_close_1'] = $fdate1;
            $arrWhere['date_close_2'] = $fdate2;
		}else{
            $arrWhere['date_close_1'] = $date_before;
            $arrWhere['date_close_2'] = $date;
        }
        if($fstatus != '') $arrWhere[$this->db_table_name.'_status'] = $fstatus;
        
        $arrWhere["is_deleted"] = 0;
        //array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MTrans->get_viewdata_close($arrWhere, array($this->db_index_key=>'DESC'), 'AND');
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
        $this->crud->set_table($this->mtrans);

        $fcode = $this->input->post('fcode', TRUE);
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_out != "") $arrWhere[$this->db_index_key] = $ftrans_out;
        if($fstatus != '') $arrWhere[$this->db_table_name.'_status'] = $fstatus;
        if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['date_1'] = $fdate1;
            $arrWhere['date_2'] = $fdate2;
		}else{
            $arrWhere['date_1'] = $date_before;
            $arrWhere['date_2'] = $date;
        }
        
        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MTrans->get_viewdata($arrWhere, array($this->db_index_key=>'DESC'), 'AND');
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
        $this->crud->set_table($this->mtrans);
        
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        
        //Condition
        if ($fdate1 != "") $arrWhere['date_1'] = $fdate1;
        if ($fdate2 != "") $arrWhere['date_2'] = $fdate2;

        $arrWhere["is_deleted"] = 0;
        //array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MTrans_D->get_viewdata($arrWhere, array($this->db_index_key=>'DESC'), 'AND');
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

    function create_trans_detail_post(){
        $this->crud->set_table($this->mdetail);
        $ftransno = $this->input->post('ftransno', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $ftransoutnum = $this->input->post('ftransoutnum',TRUE);
        $ftransoutpurpose = $this->input->post('ftransoutpurpose',TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        //$fcreatedat = date('Y-m-d H:i:s');
        
        $dataInfo = array(
            $this->db_index_key =>$ftransno, 
            'part_number'=>$fpartnum, 
            'serial_number'=>$fserialnum, 
            'repairorder_num'=>$ftransoutnum,
            'repairorder_purpose' =>$ftransoutpurpose,
            'dt_'.$this->db_table_name.'_qty'=>$fqty, 
            //'created_at'=>$fcreatedat
        );

        $result = $this->crud->insert_data($dataInfo);
        
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

    public function update_trans_detail_post(){
        $this->crud->set_table($this->mdetail);
        $ftransno = $this->input->post('ftransno', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $ftransoutnum = $this->input->post('ftransoutnum',TRUE);
        $ftransoutpurpose = $this->input->post('ftransoutpurpose',TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        //$fcreatedat = date('Y-m-d H:i:s');
        
        $dataInfo = array(
            $this->db_index_key =>$ftransno, 
            'part_number'=>$fpartnum, 
            'serial_number'=>$fserialnum, 
            'repairorder_num'=>$ftransoutnum,
            'repairorder_purpose' =>$ftransoutpurpose,
            'dt_'.$this->db_table_name.'_qty'=>$fqty, 
            //'created_at'=>$fcreatedat
        );

        $result = $this->crud->insert_data($dataInfo);
        
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

    function create_trans_post(){
        $this->crud->set_table($this->mtrans);
        $ftransno = $this->input->post('ftransno', TRUE);
        $fdate = $this->input->post('fdate', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
        $ftransnotes = $this->input->post('ftransnotes', TRUE);
        $ffereport = $this->input->post('ffereport', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
        $fcreatedat = date('Y-m-d H:i:s');
        
        $dataInfo = array(
            $this->db_index_key =>$ftransno, 
            $this->db_table_name.'_date'=>$fdate, 
            $this->db_table_name.'_purpose'=> $fpurpose, 
            $this->db_table_name.'_notes'=> $ftransnotes,
            $this->db_table_name.'_qty'=> $fqty, 
            'fe_report' => $ffereport,
			'user_key'=> $fuser, 
            'created_at'=>$fcreatedat
        );

        $result = $this->crud->insert_data($dataInfo);
        
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

    function grab_ticket_num_post(){
        $this->crud->set_table($this->mtrans);
		$fparam = $this->input->post('fparam', TRUE);
		$fcode = $this->input->post('fcode', TRUE);
		
        // $result = $this->MOutgoing->get_key_data($fparam, $fcode);
        $result = $this->crud->get_key_data($fparam, 6);
        $this->response([
            'status' => TRUE,
            'result' => $result
        ], REST_Controller::HTTP_OK);
    }
    
    
    function list_delivery_time_post(){ //unused
        $this->crud->set_table($this->mtrans);

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
        $this->crud->set_table($this->mdetail);
        $transnum = $this->input->post('transnum', TRUE);
        $rs = $this->crud->get_detail_exists($transnum);
        
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

    //                  Function for viewing detail data
    ///////////////////////////////////////////////////////
    public function get_trans_detail_post(){
        $arrWhere = array();
        $this->crud->set_table($this->mdetail);
        $transnum = $this->input->post('ftransnum', TRUE);
        $partnum = $this->input->post('fpartnum',TRUE);
        $serialnum = $this->input->post('fserialnum', TRUE);

        if($transnum != '')$arrWhere[$this->db_index_key] = $transnum;
        if($partnum != '')$arrWhere['part_number'] = $partnum;
        if($serialnum != '')$arrWhere['serial_number'] = $serialnum;
        
        $arrWhere['is_deleted'] = '0';

        $rs = $this->crud->get_viewdata($arrWhere);
        
        if (!empty($rs)){
            
            $this->response([
                    'status' => TRUE,
                    'result' => $rs
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                    'status' => FALSE,
                    'result' => array(),
                    'message' => 'Data Not Found'
            ], REST_Controller::HTTP_OK);
        }

    }

    public function get_trans_post(){
        $arrWhere = array();
        $this->crud->set_table($this->mtrans);
        
        $transnum = $this->input->post('ftransnum', TRUE);

        if ($transnum != "") $arrWhere[$this->db_index_key] = $transnum;

        $rs = $this->crud->get_viewdata($arrWhere);
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

    /*
    * update diff with insert data (mutation of row)
    */
    public function update_different_detail2_post(){
        $this->crud->set_table($this->mdetail);
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

                $data_add[$this->db_index_key] = $rs_detail[$this->db_index_key];
                $data_add['dt_'.$this->db_table_name.'_qty'] = $rs_detail['dt_'.$this->db_table_name.'_qty'];
                $data_add['old_'.$this->db_table_name.'_id'] = $fiddetail;
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

    public function update_different_detail_post(){
        $this->crud->set_table($this->mdetail);
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
                        $data_update['part_number'] = $rs_detail['part_number'];
                        $data_update['serial_number'] = $rs_detail['serial_number'];
                    }
                }
                else if($fnotes == 'diff_serialnumber'){
                    if ($fnewsn != ""){
                        $data_update['old_serial_number'] = $rs_detail['serial_number'];
                        $data_update['dt_notes'] = $fnotes;
                        $data_update['serial_number'] = $fnewsn;
                    }
                }
                
                else if($fnotes != ""){
                    $data_update['dt_notes'] = $fnotes;
                }

                if(!empty($data_update)){
                    $return_update = $this->MTrans_D->update_data3($data_update,$fiddetail);
                }

                //$data_add[$this->db_index_key] = $rs_detail[$this->db_index_key];
                //$data_add['dt_'.$this->db_table_name.'_qty'] = $rs_detail['dt_'.$this->db_table_name.'_qty'];
                //$data_add['old_'.$this->db_table_name.'_id'] = $fiddetail;
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
        $this->crud->set_table($this->mdetail);
        $arrWhere = array();
        $rs = array();
        $return_update = FALSE;

        $fid = $this->input->post('fid', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $fflag = $this->input->post('fflag', TRUE);
        
        if($fid != ''){
            //$arrWhere['dt_'.$this->db_table_name.'_id'] = $fid;
            if($fpartnum != '')  $arrWhere['part_number']    =$fpartnum;
            if($fserialnum != '')$arrWhere['serial_number']  =$fserialnum;
            if($fqty != '')      $arrWhere['dt_'.$this->db_table_name.'_qty'] =$fqty;
            if($fnotes != '')    $arrWhere['dt_notes']       =$fnotes;
            if($fflag != '')     $arrWhere['flag_process']   = $fflag;
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Need an Parameter ID.'
            ], REST_Controller::HTTP_OK);
        }

        //var_dump($fid);
        if(!empty($arrWhere)){
            $return_update = $this->crud->update_data($arrWhere,$fid);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data to update.'
            ], REST_Controller::HTTP_OK);
        }
        //var_dump($return_update);
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
    * Closing ini berfungsi sebagai closing dari repair order, tetapi juga menambahkan stok dan duplikasi data ro diganti nama depannya menjadi RO.
    */
    public function closing_trans_post(){
        $this->crud->set_table($this->mtrans);
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

    public function check_sn_from_post(){ //check apakah sudah terdaftar di transaksi Repair Order atau belum
        $config = array(
            'table' => 'repairorder_detail',
            'view' => 'viewdetailrepairorder',
            'primKey' => 'repairorder_id',
            'indexKey' => 'repairorder_num',
            'order' => 'desc'
        );
        $this->crud->set_table($config);
        
        $transnum = $this->input->post('ftransnum', TRUE);
        $partnum = $this->input->post('fpartnum',TRUE);
        $serialnum = $this->input->post('fserialnum',TRUE);
        
        $arrWhere = array(
            'part_number' => $partnum,
            'serial_number' => $serialnum,
            'is_deleted' => 0
        );
        if($transnum!='') $arrWhere['fsltocwh_num']=$transnum;

        $rs = $this->crud->get_viewdata($arrWhere);
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