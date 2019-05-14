<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class ReportDOA_model extends CI_Model
{
    
    function __construct(){
        parent::__construct();
    }
 
    function get_part_list($date1, $date2){

        $result = $this->db->query("SELECT 
                td.transnum,
                td.partnum,
                p.part_name AS partname,
                td.serialnum,
                td.qty,
                td.transdate,
                td.airwaybill,
                w.fsl_name
            FROM
            (
                SELECT
                    f.fsltocwh_num AS transnum,
                    fd.part_number AS partnum,
                    fd.serial_number AS serialnum,
                    fd.dt_fsltocwh_qty AS qty,
                    f.fsltocwh_date AS transdate,
                    f.fsltocwh_airwaybill AS airwaybill,
                    f.fsl_code
                FROM fsltocwh f 
                INNER JOIN fsltocwh_detail fd ON f.fsltocwh_num = fd.fsltocwh_num
                WHERE f.fsltocwh_purpose = 'RBS' AND f.is_deleted = 0
                AND f.created_at BETWEEN '$date1' AND '$date2'
            ) td
            INNER JOIN parts p ON p.part_number = td.partnum
            INNER JOIN warehouse_fsl w ON w.fsl_code = td.fsl_code
        ")->result();
     	return $result;
    }

}