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
        $this->db->select('od.part_number, p.part_name, od.serial_number, o.outgoing_num, o.outgoing_ticket, o.engineer_key, e.engineer_name, o.created_at');
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
        $this->db->select('
            od.part_number, 
            p.part_name, 
            od.serial_number,
            o.outgoing_num,
            o.outgoing_ticket,
            o.outgoing_purpose,
            o.created_at,
            o.fe_report,
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
        $this->db->where_not_in('od.return_status', array('RG','R','RGP'));
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

    public function get_parts1($fcode){
        $rs = array();
        $fsl = trim(strtolower($fcode));
        if($fcode != ''){
            $qry = $this->db->query("SELECT 
                p.part_name,
                pl.stock_fsl_code, 
                pl.stock_part_number, 
                pl.stock_min_value, 
                pl.stock_init_value, 
                pl.stock_last_value
                FROM p_stock_fsl_$fsl pl
                INNER JOIN parts p ON p.part_number = pl.stock_part_number
            ");
            $rs = $qry->result_array();
        }
        return $rs;
    }

    public function get_parts($fcode = array()){
        $res = array();
        $qr_union = "";
        $i = 0;
        if(length($fcode) > 0 ){
            foreach($fsl as $fcode){
                $union = $i != 0 ? " UNION ":"";
                $qr_union += "SELECT stock_fsl_code, stock_part_number, stock_min_value, stock_init_value, stock_last_value FROM p_stock_fsl_".strtolower($fsl);
            }
        }
        $this->db->query("SELECT 
                p.part_name, 
                pl.*
            FROM (
                $qr_union
            ) pl INNER JOIN parts p ON p.part_number = pl.stock_part_number
        ");
    }

    public function get_on_hand($fcode){
        $rs = array();
        $fsl = strtolower($fcode);
        if($fcode != ''){
            $qry = $this->db->query("SELECT
                o.outgoing_num, od.part_number, o.fsl_code, od.dt_outgoing_qty FROM 
                (    
                    SELECT * FROM outgoings 
                    WHERE outgoing_status IN('open','pending') 
                    AND fsl_code IN('$fsl') 
                    AND outgoing_purpose = 'M'
                    AND is_deleted = 0
                )o INNER JOIN outgoings_detail od ON od.outgoing_num = o.outgoing_num");
            $rs = $qry->result_array();
        }
        return $rs;
    }
}
?>