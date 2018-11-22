<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Vendor_model extends CI_Model
{
    var $tbl_parts = 'vendor_supply';
    var $view_parts = 'ViewParts';
    var $primKey = 'vendor_id';
    var $indexKey = 'vendor_code';
    var $order = array('vendor_code' => 'asc'); // default order

    function __construct()
    {
        parent::__construct();
    }

    function get_data($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('*');
        $this->db->from($this->tbl_parts);

        if(empty($arrWhere)){
            $rs = array();
        }else{
            if($type == "AND"){
                foreach ($arrWhere as $strField => $strValue){
                    if (is_array($strValue)){
                        $this->db->where_in($strField, $strValue);
                    }else{
                        if(strpos(strtolower($strField), 'date_1') !== false){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField >= '".$strValue." 00:00:00' ");
                            }
                        }elseif(strpos(strtolower($strField), 'date_2') !== false){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField <= '".$strValue." 23:59:59' ");
                            }
                        }else{
                            $this->db->where($strField, $strValue);
                        }
                    }
                }
            }else{
                foreach ($arrWhere as $strField => $strValue){
                    if (is_array($strValue)){
                        $this->db->where_in($strField, $strValue);
                    }else{
                        if(strpos(strtolower($strField), 'date_1') !== false){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField >= '".$strValue." 00:00:00' ");
                            }
                        }elseif(strpos(strtolower($strField), 'date_2') !== false){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField <= '".$strValue." 23:59:59' ");
                            }
                        }else{
                            $this->db->or_where($strField, $strValue);
                        }
                    }
                }
            }
			// $this->db->limit(10);
            $query = $this->db->get();
            $rs = $query->result_array();
        }
        
        //Order By
        if (count($arrOrder) > 0){
            foreach ($arrOrder as $strField => $strValue){
                $this->db->order_by($strField, $strValue);
            }
        }
        
        return $rs;
    }
}