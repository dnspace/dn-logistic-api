<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CPOutgoings (CPOutgoingsController)
 * CPOutgoings class to control transactions
 * @author : Abas & KAZEFA
 * @version : 2.0
 * @since : Mei 2017
 */
class CPSupplyFromRepair extends REST_Controller
{
    //declare name of the table
    private $table_name = 'sfrepair';

    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('supplyFromRepair/Trans_model','MTrans');
        $this->load->model('supplyFromRepair/Detail_model','MTrans_D');
        $this->load->model('supplyFromRepair/Tmp_model','MTrans_T');
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


/////////////////////////////////////////////////////////////////////
// Trans IN FUNCTION    
/////////////////////////////////////////////////////////////////////

    /**
     * This function used to load data from table trans
     */
    public function list_view_post(){
        $rs = array();
        $arrWhere = array();
        //$this->output->enable_profiler(TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $ftrans_in = $this->input->post('ftrans_in', TRUE);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
		$date = date('Y-m-d');
		$date_before = date('Y-m-d', strtotime($date . '-1 day'));
		
        //Condition
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;
        if ($ftrans_in != "") $arrWhere[$this->table_name.'_num'] = $ftrans_in;
		if ($fdate1 != "" AND $fdate2 != "") {
            $arrWhere['created_at_1'] = $fdate1;
            $arrWhere['created_at_2'] = $fdate2;
		}else{
            $arrWhere['created_at_1'] = $date_before;
            $arrWhere['created_at_2'] = $date;
		}
        if ($fpurpose != "") $arrWhere[$this->table_name.'_purpose'] = $fpurpose;
		
        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MTrans->get_viewdata($arrWhere, array($this->table_name.'_num'=>'DESC'), 'AND');
        
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

    public function get_key_num_post()
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

    function create_trans_post()
    {
        $ftransno = $this->input->post('ftransno', TRUE);
        $fdate = $this->input->post('fdate', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
        $ftransnotes = $this->input->post('ftransnotes', TRUE);
        $freceivedby = $this->input->post('freceivedby', TRUE);
        $ffsl_code = $this->input->post('fcode', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        //$fponum = $this->input->post('fponum', TRUE); //NOT USE FOR REPAIR
        $fuser = $this->input->post('fuser', TRUE);
        $fcreatedate = date('Y-m-d H:i:s');
        
        $dataInfo = array(
            $this->table_name.'_num'=>$ftransno, 
            //$this->table_name.'_po_num'=>$fponum, //NOT USE FOR REPAIR
            $this->table_name.'_date'=>$fdate, 
            $this->table_name.'_purpose'=> $fpurpose, 
            $this->table_name.'_notes'=> $ftransnotes,
            'fsl_code'=> $ffsl_code, 
            'received_by' => $freceivedby,
            $this->table_name.'_qty'=> $fqty, 
			'user_key'=> $fuser, 
            'created_at'=> $fcreatedate
        );

        $result = $this->MTrans->insert_data($dataInfo);
        if($result > 0 && $result != '' && !is_null($result))
        {
            $this->response([
                'status' => TRUE,
                'message' => 'Data created successfully',
                'result' => $result
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

    function create_trans_detail_post()
    {
        $ftransno = $this->input->post('ftransno', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fid_trans = $this->input->post('fid_trans');
        $fcreatedat = date('Y-m-d H:i:s');
        
        $dataInfo = array(
            $this->table_name.'_num'=>$ftransno, 
            'part_number'=>$fpartnum, 
            'dt_'.$this->table_name.'_qty'=>$fqty, 
            'created_at'=>$fcreatedat,
            'id_'.$this->table_name => $fid_trans
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

    //Delete data
    public function delete_trans(){
        $fid = $this->input->post('fid',TRUE);
        $rs = $this->MTrans->delete_trans($fid);
        if($rs > 0){
            $this->response{[
                'status' => TRUE,
                'result' => $rs,
            ]};
        }else{
            $this->response{[
                'status' => FALSE
            ]};
        }
    }

    

/////////////////////////////////////////////////////////////////////
// CART IN FUNCTION    
/////////////////////////////////////////////////////////////////////

    /**
     * This function used to create / insert data to cart
     */
    function create_trans_tmp_post()
    {
        $arrWhere = array();
        $dataInfo = array();
        $dataInfo2 = array();
        
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fpartname = $this->input->post('fpartname', TRUE);
        $fcartid = $this->input->post('fcartid', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        
        $dataInfo = array(
            'part_number'=>$fpartnum, 
            'part_name'=>$fpartname, 
            'tmp_'.$this->table_name.'_uniqid'=>$fcartid, 
            'tmp_'.$this->table_name.'_qty'=>$fqty, 
            'user'=>$fuser, 
            'fullname'=>$fname, 
            'fsl_code'=>$fcode
        );

        $arrWhere = array(
            'part_number'=>$fpartnum, 
            'tmp_'.$this->table_name.'_uniqid'=>$fcartid
        );
        $exist = $this->MTrans_T->check_data_exists($arrWhere);
        
        if($exist > 0){
            $stock = $this->cur_stock_tmp($fpartnum, $fcartid);
            $ustock = $stock + $fqty;
            
            $dataInfo2 = array('tmp_'.$this->table_name.'_qty'=>$ustock);
            $result2 = $this->MTrans_T->update_data_2($dataInfo2, $fpartnum, $fcartid);
        
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

    public function list_tmp_post(){
        $rs = array();
        $arrWhere = array();

        $funiqid = $this->input->post('funiqid', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
        $fcode = $this->input->post('fcode', TRUE);

        //Condition
        if ($funiqid != "") $arrWhere['tmp_'.$this->table_name.'_uniqid'] = $funiqid;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;
        if ($fuser != "") $arrWhere['user'] = $fuser;
        if ($fcode != "") $arrWhere['fsl_code'] = $fcode;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MTrans_T->get_data($arrWhere, array('tmp_'.$this->table_name.'_id'=>'ASC'), 'AND');
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

    public function total_cart_post(){
        $rs = array();
        $arrWhere = array();

        $funiqid = $this->input->post('funiqid', TRUE);

        if ($funiqid != "") $arrWhere['tmp_'.$this->table_name.'_uniqid'] = $funiqid;
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
    
    public function get_trans_detail_post(){
        $arrWhere = array();
        $rs = array();
        
        $transnum = $this->input->post('ftransnum', TRUE);

        if ($transnum != "") $arrWhere['sfrepair_num'] = $transnum;

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

        if ($transnum != "") $arrWhere['sfrepair_num'] = $transnum;

        $rs = $this->MTrans->get_viewdata_detail($arrWhere);
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
    

/////////////////////////////////////////////////////////////////////
// BUILT IN FUNCTION    
/////////////////////////////////////////////////////////////////////
    function cur_stock_tmp($partnum, $cartid){
        $rs = array();
        $arrWhere = array();
        $stock = 0;

        $arrWhere = array('part_number'=>$partnum, 'tmp_'.$this->table_name.'_uniqid'=>$cartid);
        
        $rs = $this->MTrans_T->get_data($arrWhere, array(), 'AND');
        if ($rs){
            foreach($rs as $r){
                $stock = $r['tmp_'.$this->table_name.'_qty'];
            }
        }else{
            $stock = 0;
        }
        
        return $stock;
    }
    
///////////////////////////////////////////////////////////////////////
// TEST
//////////////////////////////////////////////////////////////////////

    public function test_post(){
        for($i=1;$i<10;$i++){
            $key = $this->get_key_data('OG', 5);
            $this->db->insert('testkey',array('trans_num'=>$key));
        }
        $this->response([
            'status' => FALSE,
            'message' => 'OK'
        ], REST_Controller::HTTP_OK);
    }

    /**
    *  Get Transaction Number
    *  
    *  Generating Transaction Number with dynamic value @version 1.2.0
    *  @param String $param Prefix of the transaction number
    *  @param Integer $pad Number digit you want to padding
    *  @return String new Transaction number
    */
    public function get_key_data($param, $pad) {
        $this->db->flush_cache();
        
        //var
        $table = 'testkey';
        $table_num = 'trans_num';

        //logic
        $digit_prefix           = strlen($param);
        $digit_sum_tanggal      = 4;
        $digit_insert_tanggal   = $digit_prefix + 1;
        $digit_insert_padnum    = $digit_insert_tanggal + $digit_sum_tanggal + 1;

        //query
        $q = $this->db->query("SELECT 
            	CAST(DATE_FORMAT(NOW(),'%y%m') AS CHAR) AS DATEi,
                CASE 
                    WHEN t1.MaxNo > 0 
                        THEN LPAD(CAST(t1.MaxNo AS UNSIGNED) + 1, $pad,'0')
                    ELSE 
                        LPAD(1,$pad,'0')
                END AS MAXi
            FROM (
                SELECT 
                    MAX(SUBSTRING($table_num,$digit_insert_padnum,$pad)) MaxNo
                FROM $table
                WHERE 
                    SUBSTRING($table_num,$digit_insert_tanggal,$digit_sum_tanggal) = CAST(DATE_FORMAT(NOW(),'%y%m') AS CHAR) 
                    AND $table_num LIKE '{$param}%'
            ) AS t1
        ")->row_array();
        $kodeNum = $q['DATEi'] . $q['MAXi'];

        //gabungkan string dengan kode yang telah dibuat tadi
        return $param.$kodeNum;
    }

}

