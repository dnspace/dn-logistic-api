<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Procedure_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }
	
	function exe_detail_outgoings($transno){
		$query=$this->db->query("call sp_detail_outgoings(?)");
		$data = array('ptransno' => $transno);
		$sql_query = $this->db->query($query, $data);
        mysqli_next_result( $this->db->conn_id);
		while(mysqli_next_result($this->db->conn_id)){
            if($l_result = mysqli_store_result($this->db->conn_id)){
				mysqli_free_result($l_result);
            }
        }
		mysqli_next_result( $this->db->conn_id);
		if($sql_query->num_rows()>0){
			return $sql_query->result_array();
		}else{
			return array();
		}
	}
	
	function exe_daily_report($fcode, $fdate1, $fdate2){
		$query = "call sp_daily_reports(?, ?, ?)";
		$data = array('pfslcode' => $fcode, 'pdate1' => $fdate1, 'pdate2' => $fdate2);
		$sql_query = $this->db->query($query, $data);
        mysqli_next_result( $this->db->conn_id);
		while(mysqli_next_result($this->db->conn_id)){
            if($l_result = mysqli_store_result($this->db->conn_id)){
				mysqli_free_result($l_result);
            }
        }
		mysqli_next_result( $this->db->conn_id);
		if($sql_query->num_rows()>0){
			return $sql_query->result_array();
		}else{
			return array();
		}
	}
}