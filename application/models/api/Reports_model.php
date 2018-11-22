<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Reports_model extends CI_Model
{
    protected $tbl_incoming_d = 'incomings_detail';

    function __construct()
    {
        parent::__construct();
    }

    function get_outgoing_daily_report($fcode, $fdate1, $fdate2)
    {
		// $this->db->distinct('od.part_number');
        $this->db->select('od.part_number, p.part_name, od.serial_number, o.outgoing_num, o.outgoing_ticket, o.engineer_key, e.engineer_name');
        $this->db->from('outgoings_detail as od');
        $this->db->join('outgoings as o','od.outgoing_num = o.outgoing_num', 'both');
        $this->db->join('parts as p','od.part_number = p.part_number', 'both');
        $this->db->join('engineers as e','o.engineer_key = e.engineer_key', 'both');
        $this->db->where('o.fsl_code', $fcode);
        $this->db->where('o.created_at >=', $fdate1);
        $this->db->where('o.created_at <=', $fdate2);
        $this->db->where('o.is_deleted', 0);
        $this->db->where('od.is_deleted', 0);
        $query = $this->db->get();
		
		$rs = $query->result_array();
        
        if(!empty($rs)){
            return $rs;
        } else {
            return array();
        }
    }
	
	function get_outgoing_used_part($fcode, $fdate1, $fdate2)
    {
        $this->db->select('od.part_number, p.part_name, od.serial_number, o.outgoing_num, o.outgoing_ticket, o.outgoing_purpose, o.fe_report, 
(CASE WHEN od.return_status = "RG" THEN "RG" ELSE "USED" END) AS status, o.engineer_key, e.engineer_name, sp.partner_name');
        $this->db->from('outgoings_detail as od');
        $this->db->join('outgoings as o','od.outgoing_num = o.outgoing_num', 'both');
        $this->db->join('parts as p','od.part_number = p.part_number', 'both');
        $this->db->join('engineers AS e','o.engineer_key = e.engineer_key', 'both');
        $this->db->join('service_partners AS sp','e.partner_id = sp.partner_id', 'both');
        $this->db->where('o.fsl_code', $fcode);
        $this->db->where('o.created_at >=', $fdate1);
        $this->db->where('o.created_at <=', $fdate2);
        $this->db->where('o.outgoing_status', 'complete');
        $this->db->where('o.is_deleted', 0);
        $this->db->where('od.is_deleted', 0);
		// $this->db->group_by('od.part_number');
        $query = $this->db->get();
		
		$rs = $query->result_array();
        
        if(!empty($rs)){
            return $rs;
        } else {
            return array();
        }
    }
	
	function get_outgoing_replenish_plan($fcode, $fdate1, $fdate2)
    {
        $this->db->select('od.part_number, p.part_name, SUM(od.dt_outgoing_qty) AS qty, ps.stock_init_value, ps.stock_min_value, ps.stock_last_value');
        $this->db->from('outgoings_detail as od');
        $this->db->join('outgoings as o','od.outgoing_num = o.outgoing_num', 'both');
        $this->db->join('parts as p','od.part_number = p.part_number', 'both');
        $this->db->join('p_stock_fsl_'.strtolower($fcode).' as ps','ps.stock_part_number = od.part_number', 'both');
        $this->db->where('o.fsl_code', $fcode);
        $this->db->where('o.created_at >=', $fdate1);
        $this->db->where('o.created_at <=', $fdate2);
        $this->db->where('o.outgoing_status', 'complete');
        $this->db->where('od.return_status <>', 'RG');
        $this->db->where('o.is_deleted', 0);
        $this->db->where('od.is_deleted', 0);
		$this->db->group_by('od.part_number');
        $query = $this->db->get();
		
		$rs = $query->result_array();
        
        if(!empty($rs)){
            return $rs;
        } else {
            return array();
        }
    }
}
?>