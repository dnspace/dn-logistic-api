<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class ReportCWH_model extends CI_Model
{
    
    function __construct(){
        parent::__construct();
    }
 
    function get_part_list($date1, $date2){

        $result = $this->db->query("SELECT 
                og.*,
                whs.fsl_name,
                eg.engineer_name,
                eg.partner_name,
                od.part_number,
                od.serial_number
            FROM outgoings_detail od
            INNER JOIN 
            (
                SELECT 
                    o.outgoing_num,
                    o.outgoing_ticket,
                    o.fe_report,
                    o.fsl_code,
                    o.engineer_key,
                    o.outgoing_cust,
                    o.closing_date
                FROM 
                    outgoings o 
                WHERE 
                    (o.created_at BETWEEN '$date1' AND '$date2') 
                    AND o.outgoing_purpose NOT IN ('RWH')
                    AND o.outgoing_status = 'complete'
                    AND o.is_deleted = 0
            ) og ON og.outgoing_num = od.outgoing_num
            INNER JOIN 
            (
                SELECT e.engineer_key,e.engineer_name, sp.partner_name FROM engineers e
                INNER JOIN  service_partners sp ON e.partner_id = sp.partner_id
            ) eg ON og.engineer_key = eg.engineer_key
            INNER JOIN 
            (
                SELECT * FROM warehouse_fsl WHERE is_deleted = 0
            ) whs ON whs.fsl_code = og.fsl_code
            WHERE
                od.return_status NOT IN('RG','R','RGP')
        ")->result();
     	return $result;
    }

}