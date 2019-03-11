<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Crud_model extends CI_Model
{
    private $tbl;// = 'sfvendor_detail';
    private $view;//v = 'viewdetailsfvendor';
    private $primKey;// = 'sfvendor_detail_id';
    private $indexKey;// = 'sfvendor_num';
    private $order;// = array('sfvendor_num' => 'desc'); // default order

    function __construct()
    {
        parent::__construct();
    }

    /**
     * SET Table 
     * @param table String
     * @param view String
     * @param primKey String
     * @param indexKey String
     * @param order Array Default is array($tbl => Desc)
     */
    public function set_table($config){
        $fls = TRUE;
        $this->tbl      = (!isset($config['table']) || $config['table']=='')?FALSE:$config['table'];
        $this->view     = (!isset($config['view']) || $config['view']=='')?FALSE:$config['view'];
        $this->primKey  = (!isset($config['primKey']) || $config['primKey']=='')?FALSE:$config['primKey'];
        $this->indexKey = (!isset($config['indexKey']) || $config['indexKey']=='')?FALSE:$config['indexKey'];
        $this->order    = (!isset($config['order']) || $config['order']=='' AND !is_array($config['order'])) ? array($config['indexKey'] => 'desc'):$config['order'];
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
        $table = $this->tbl;
        $table_num = $this->tbl.'_num';

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
 
    function count_all()
    {
        $this->db->flush_cache();
        $this->db->from($this->tbl);
        $this->db->where('is_deleted', 0);
        return $this->db->count_all_results();
    }

    function count_cart($arrWhere = array())
    {
        $this->db->flush_cache();
        
        $this->db->select('SUM(tmp_qty) AS total');
        $this->db->from($this->tbl);

        if(empty($arrWhere)){
            $rs = array();
        }else{
            foreach ($arrWhere as $strField => $strValue){
				if (is_array($strValue)){
					$this->db->where_in($strField, $strValue);
				}else{
					$this->db->where($strField, $strValue);
				}
			}
            $query = $this->db->get();
            $rs = $query->result_array();
        }
        
        return $rs;
    }

    function get_data($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $this->db->flush_cache();
        
        $this->db->select('*');
        $this->db->from($this->tbl);

        if(empty($arrWhere)){
            $rs = array();
        }else{
            $i = count($arrWhere);
            if($type == "AND"){
                foreach ($arrWhere as $strField => $strValue){
                    if (is_array($strValue)){
                        $this->db->where_in($strField, $strValue);
                    }else{
                        if(strpos(strtolower($strField), 'at_1') !== FALSE || strpos(strtolower($strField), 'date_1') !== FALSE){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField >= '".$strValue." 00:00:00' ");
                            }
                        }elseif(strpos(strtolower($strField), 'at_1') !== FALSE || strpos(strtolower($strField), 'date_2') !== FALSE){
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
                        if(strpos(strtolower($strField), 'at_1') !== FALSE || strpos(strtolower($strField), 'at_1') !== FALSE){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField >= '".$strValue." 00:00:00' ");
                            }
                        }elseif(strpos(strtolower($strField), 'at_2') !== FALSE || strpos(strtolower($strField), 'at_2') !== FALSE){
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
        
        //Order By
        if (count($arrOrder) > 0){
            foreach ($arrOrder as $strField => $strValue){
                $this->db->order_by($strField, $strValue);
            }
        }
        
        return $rs;
    }
    
    function get_viewdata($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $this->db->flush_cache();
        
        $this->db->select('*');
        $this->db->from($this->view);

        if(empty($arrWhere)){
            $rs = array();
        }else{
            $i = count($arrWhere);
            if($type == "AND"){
                foreach ($arrWhere as $strField => $strValue){
                    if (is_array($strValue)){
                        $this->db->where_in($strField, $strValue);
                    }else{
                        if(strpos(strtolower($strField), 'at_1') !== FALSE || strpos(strtolower($strField), 'date_1') !== FALSE){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField >= '".$strValue." 00:00:00' ");
                            }
                        }elseif(strpos(strtolower($strField), 'at_2') !== FALSE || strpos(strtolower($strField), 'date_2') !== FALSE){
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
                        if(strpos(strtolower($strField), 'at_1' ) !== FALSE || strpos(strtolower($strField), 'date_1') !== FALSE){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField >= '".$strValue." 00:00:00' ");
                            }
                        }elseif(strpos(strtolower($strField), 'at_2') !== FALSE || strpos(strtolower($strField), 'date_2') !== FALSE){
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
        
        //Order By
        if (count($arrOrder) > 0){
            foreach ($arrOrder as $strField => $strValue){
                $this->db->order_by($strField, $strValue);
            }
        }
        
        return $rs;
    }
    
    /**
     * This function is used to add new data to system
     * @return number $insert_id : This is last inserted id
     */
    function insert_data($dataInfo)
    {
        $this->db->flush_cache();
        $this->db->trans_start();
        $this->db->insert($this->tbl, $dataInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get data information by id
     * @param number $id : This is id
     * @return array $result : This is data information
     */
    function get_data_info($id)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->tbl);
        $this->db->where($this->indexKey, $id);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the data information
     * @param array $dataInfo : This is data updated information
     * @param number $id : This is data id
     */
    function update_data($dataInfo, $id)
    {
        $this->db->flush_cache();
        $this->db->where($this->primKey, $id);
        $this->db->update($this->tbl, $dataInfo);
        if($this->db->affected_rows()>0)return TRUE;
        return FALSE;
    }
	
	/**
     * This function is used to update the data information
     * @param array $dataInfo : This is data updated information
     * @param number $id : This is data id
     */
    function update_data2($dataInfo, $trans_out)
    {
        $this->db->flush_cache();
        $this->db->where($this->indexKey, $trans_out);
        $this->db->update($this->tbl, $dataInfo);
        if($this->db->affected_rows()>0)return TRUE;
        return FALSE;
    }
    
    /**
     * This function is used to delete the data information
     * @param number $id : This is data id
     * @return boolean $result : TRUE / FALSE
     */
    function delete_data($id)
    {
        $this->db->flush_cache();
        $this->db->where($this->indexKey, $id);
        $ret = $this->db->delete($this->tbl);
        
        return $ret;
    }

    /**
     * This function is used to delete the data information
     * @param number $id : This is data id
     * @return boolean $result : TRUE / FALSE
     */
    function delete_data_by_primkey($id)
    {
        $this->db->flush_cache();
        $this->db->where($this->primKey, $id);
        $ret = $this->db->delete($this->tbl);
        
        return $ret;
    }

    /**
     * This Function is used to delete transaction
     * @param $id id tael
     */
    function delete_trans($id){
        $this->db->flush_cache();
        $this->db->query("UPDATE 
            {$this->tbl} AS t1 INNER JOIN {$this->tbl}_tmp AS t2 
            ON t1.{$this->tbl}_id = t2.tmp_{$this->tbl}_id
            SET t1.is_deleted = 1, t2.is_deleted = 1
            WHERE t1.{$this->tbl}_id = $id");
        return $this->db->affected_rows();
    }

    /**
     * This function is used to check whether field is already exist or not
     * @param {string} $param : This is param
     * @return {mixed} $result : This is searched result
     */
     function check_data_exists($arrWhere = array()){
         $this->db->flush_cache();
         $this->db->from($this->tbl);
         //Criteria
         if (count($arrWhere) > 0){
             foreach ($arrWhere as $strField => $strValue){
                 if (is_array($strValue)){
                     $this->db->where_in($strField, $strValue);
                 }else{
                     $this->db->where($strField, $strValue);
                 }
             }
         }
        return $this->db->count_all_results();
     }
}