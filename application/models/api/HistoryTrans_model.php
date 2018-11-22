<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class HistoryTrans_model extends CI_Model
{
    protected $view_outgoing = 'viewoutgoings';
    protected $view_detailoutgoing = 'viewdetailoutgoings';
    protected $primKey = 'outgoing_id';
    protected $indexKey = 'outgoing_num';
    protected $indexKey2 = 'part_number';
    protected $order = array('outgoing_num' => 'asc'); // default order

    function __construct()
    {
        parent::__construct();
    }
	
	function get_data_part_e($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('vdo.part_number, vdo.serial_number, vo.engineer_name, vo.partner_name, vo.engineer_2_name, 
			vo.created_at, vo.outgoing_ticket, vdo.dt_outgoing_qty, vo.fsl_code, vo.fsl_name, vo.outgoing_num, vo.outgoing_purpose');
        $this->db->from($this->view_outgoing.' as vo');
        $this->db->join($this->view_detailoutgoing.' as vdo','vo.outgoing_num = vdo.outgoing_num', 'left');
        $this->db->where('vo.is_deleted', 0);
        $this->db->where('vdo.is_deleted', 0);

		if($type == "AND"){
			foreach ($arrWhere as $strField => $strValue){
				if (is_array($strValue)){
					$this->db->where_in($strField, $strValue);
				}else{
					$this->db->where($strField, $strValue);
				}
			}
		}else{
			foreach ($arrWhere as $strField => $strValue){
				if (is_array($strValue)){
					$this->db->where_in($strField, $strValue);
				}else{
					$this->db->or_where($strField, $strValue);
				}
			}
		}
        
        //Order By
        if (count($arrOrder) > 0){
            foreach ($arrOrder as $strField => $strValue){
                $this->db->order_by($strField, $strValue);
            }
        }
		
		$query = $this->db->get();
		$rs = $query->result_array();
        
        return $rs;
    }
}