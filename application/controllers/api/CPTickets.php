<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : CPTickets (CPTicketsController)
 * CPTickets class to control to data part tickets
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class CPTickets extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/PTickets_model','MTickets');
        $this->load->model('api/PTicketsDetail_model','MTickets_D');
        $this->load->model('api/PTicketsTmp_model','MTickets_T');
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

        $fticketnum = $this->input->post('fticketnum', TRUE);

        //Condition
        if ($fticketnum != "") $arrWhere['pticket_number'] = $fticketnum;

        $arrWhere["deleted_at"] = NULL;
        array_push($arrWhere, $arrWhere["deleted_at"]);
        
        $rs = $this->MTickets->get_data($arrWhere, array('pticket_number'=>'DESC'), 'AND');
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

        $fticketid = $this->input->post('fticketid', TRUE);
        $fticketnum = $this->input->post('fticketnum', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);

        //Condition
        if ($fticketid != "") $arrWhere['pdticket_id'] = $fticketid;
        if ($fticketnum != "") $arrWhere['pticket_number'] = $fticketnum;
        if ($fpartnum != "") $arrWhere['part_number'] = $fpartnum;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MTickets_D->get_data($arrWhere, array('pticket_number'=>'DESC'), 'AND');
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

        $fticketnum = $this->input->post('fticket', TRUE);
        // $fpartnum = $this->input->post('fpartnum', TRUE);
        // $fqty = $this->input->post('fqty', TRUE);

        //Condition
        if ($fticketnum != "") $arrWhere['pticket_number'] = $fticketnum;

        $arrWhere["is_deleted"] = 0;
        array_push($arrWhere, $arrWhere["is_deleted"]);
        
        $rs = $this->MTickets_T->get_data($arrWhere, array('part_number'=>'DESC'), 'AND');
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
     * This function is used to add new tickets to the system
     */
    function create_tickets_post()
    {
        $fticket = $this->input->post('fticket', TRUE);
        $fdate = $this->input->post('fdate', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
        $ffsl = $this->input->post('ffsl', TRUE);
        $createdat = date('Y-m-d H:i:sa');
        
        $dataInfo = array('pticket_number'=>$fticket, 'pticket_date'=>$fdate, 
            'pticket_qty'=>$fqty, 'user_key'=> $fuser, 'fsl_code'=> $ffsl, 'created_at'=>$createdat);

        $result = $this->MTickets->insert_data($dataInfo);
        
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
    function create_tickets_detail_post()
    {
        $fticket = $this->input->post('fticket', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $createdat = date('Y-m-d H:i:sa');
        
        $dataInfo = array('pticket_number'=>$fticket, 'part_number'=>$fpartnum, 
            'pdticket_qty'=>$fqty, 'created_at'=>$createdat);

        $result = $this->MTickets_D->insert_data($dataInfo);
        
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
    function create_tickets_cart_post()
    {
        $fticket = $this->input->post('fticket', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        
        $dataInfo = array('pticket_number'=>$fticket, 'part_number'=>$fpartnum, 
            'ptt_qty'=>$fqty);

        $result = $this->MTickets_T->insert_data($dataInfo);
        
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
    function update_tickets_cart_post()
    {
        $fticket = $this->input->post('fticket', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        
        $dataInfo = array('pticket_number'=>$fticket, 'part_number'=>$fpartnum, 'ptt_qty'=>$fqty);

        $result = $this->MTickets_T->update_data_2($dataInfo, $fticket, $fpartnum);
        
        if($result)
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
     * This function is used to delete the data using data id
     * @return boolean $result : TRUE / FALSE
     */
    function delete_tickets_cart_post()
    {
        $fid = $this->input->post('fid', TRUE);

        $result = $this->MTickets_T->delete_data($fid);

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
     * This function is used to clear the data using data ticket number
     * @return boolean $result : TRUE / FALSE
     */
    function clear_tickets_cart_post()
    {
        $fticket = $this->input->post('fticket', TRUE);

        $result = $this->MTickets_T->delete_data_by_ticket($fticket);

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

        $result = $this->MTickets_T->check_data_exists($arrWhere);

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

    /**
     * This function is used to get new tickets number from the system
     */
    function grab_ticket_num_get()
    {
		$fparam = $this->input->get('fparam', TRUE);
		$ffsl_code = $this->input->get('ffsl_code', TRUE);
		
        $param = $fparam.$ffsl_code;
        $result = $this->MTickets->get_key_data($param);
        $ranstring = $this->common->randomString();
        
        $this->response([
            'status' => TRUE,
            'result' => $result
        ], REST_Controller::HTTP_OK);
    }
    
}
?>