<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Trans_model extends CI_Model
{
    var $tbl_ = 'sfvendor';
    var $view_ = 'viewsfvendor';
    var $primKey = 'sfvendor_id';
    var $indexKey = 'sfvendor_num';
    var $order = array('sfvendor_num' => 'desc'); // default order

    function __construct(){
        parent::__construct();
    }

    function get_viewdata_detail($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $rs = array();
        $this->db->flush_cache();
        
        $this->db->select('*');
        $this->db->from($this->view_);

        if(empty($arrWhere)){
            $rs = array();
        }else{
            $i = count($arrWhere);
            if($type == "AND"){
                foreach ($arrWhere as $strField => $strValue){
                    if (is_array($strValue)){
                        $this->db->where_in($strField, $strValue);
                    }else{
                        if(strpos(strtolower($strField), 'at_1') !== false){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField >= '".$strValue." 00:00:00' ");
                            }
                        }elseif(strpos(strtolower($strField), 'at_2') !== false){
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
                        if(strpos(strtolower($strField), 'at_1') !== false){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField >= '".$strValue." 00:00:00' ");
                            }
                        }elseif(strpos(strtolower($strField), 'at_2') !== false){
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
            $query = $this->db->get();
            $rs = $query->result_array();
        }
        return $rs;
    }

    //get all datatable
    function get_viewdata($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $rs = array();
        $this->db->flush_cache();
        
        $this->db->select('*');
        $this->db->from($this->tbl_);

        if(empty($arrWhere)){
            $rs = array();
        }else{
            $i = count($arrWhere);
            if($type == "AND"){
                foreach ($arrWhere as $strField => $strValue){
                    if (is_array($strValue)){
                        $this->db->where_in($strField, $strValue);
                    }else{
                        if(strpos(strtolower($strField), 'at_1') !== false){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField >= '".$strValue." 00:00:00' ");
                            }
                        }elseif(strpos(strtolower($strField), 'at_2') !== false){
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
                        if(strpos(strtolower($strField), 'at_1') !== false){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField >= '".$strValue." 00:00:00' ");
                            }
                        }elseif(strpos(strtolower($strField), 'at_2') !== false){
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
            $query = $this->db->get();
            $rs = $query->result_array();
        }
        return $rs;
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
        $table = $this->tbl_;
        $table_num = $this->tbl_.'_num';

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

    function insert_data($dataInfo){
        $this->db->trans_start();
        $this->db->insert($this->tbl_, $dataInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function delete_data($id){
        $this->db->where($this->indexKey, $id);
        $this->db->delete($this->tbl_);
        return $this->db->affected_rows();
    }

    function delete_trans($id){
        $this->db->query("UPDATE 
            {$this->tbl_} AS t1 INNER JOIN {$this->tbl_}_tmp AS t2 
            ON t1.{$this->tbl_}_id = t2.tmp_{$this->tbl_}_id
            SET t1.is_deleted = 1, t2.is_deleted = 1
            WHERE t1.{$this->tbl_}_id = $id");
        return $this->db->affected_rows();
    }



}