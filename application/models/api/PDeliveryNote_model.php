<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class PDeliveryNote_model extends CI_Model
{
    var $tbl_ = 'delivery_note';
    var $view_ = 'viewdeliverynote';
    var $primKey = 'delivery_note_id';
    var $indexKey = 'delivery_note_num';
    var $order = array('delivery_note_num' => 'desc'); // default order

    function __construct(){
        parent::__construct();
    }
 
    function count_all(){
        $this->db->from($this->tbl_);
        $this->db->where('is_deleted', 0);
        return $this->db->count_all_results();
    }

    function get_data($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $rs = array();
        //Flush Param
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
			
			//Order By
			// if (count($arrOrder) > 0){
				foreach ($arrOrder as $strField => $strValue){
					$this->db->order_by($strField, $strValue);
				}
			// }
		
            $query = $this->db->get();
            $rs = $query->result_array();
        }
        
        
        return $rs;
    }
    
    function get_viewdata($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $rs = array();
        //Flush Param
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
    

    /**
    *  Get Transaction Number
    *  
    *  Generating Transaction Number with dynamic value
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
	
    /**
     * This function is used to add new data to system
     * @return number $insert_id : This is last inserted id
     */
    function insert_data($dataInfo){
        $this->db->trans_start();
        $this->db->insert($this->tbl_, $dataInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get data information by id
     * @param number $id : This is id
     * @return array $result : This is data information
     */
    function get_data_info($id){
        $this->db->select('*');
        $this->db->from($this->tbl_);
        $this->db->where($this->indexKey, $id);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the data information
     * @param array $dataInfo : This is data updated information
     * @param number $id : This is data id
     */
    function update_data($dataInfo, $id){
        $this->db->where($this->indexKey, $id);
        $this->db->update($this->tbl_, $dataInfo);
        
        return TRUE;
    }
    
    /**
     * This function is used to delete the data information
     * @param number $id : This is data id
     * @return boolean $result : TRUE / FALSE
     */
    function delete_data($id){
     $this->db->where($this->indexKey, $id);
     $this->db->delete($this->tbl_);

     return $this->db->affected_rows();
    }

    /**
    * This function is used to check whether field is already exist or not
    * @param {string} $param : This is param
    * @return {mixed} $result : This is searched result
    */
    function check_data_exists($arrWhere = array()){
         //Flush Param
         $this->db->flush_cache();
         $this->db->from($this->tbl_);
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
    
    function get_eta($fsl_code, $delivery_type, $delivery_by){
        $rs = array();
        $rs = $this->db->query(
            "SELECT *, IF(delivery_time_value = 0,0,DATE_ADD(DATE(NOW()),INTERVAL delivery_time_value DAY)) AS 'ETA' FROM delivery_time WHERE fsl_code = '$fsl_code' AND delivery_time_type = '$delivery_type' AND delivery_by = '$delivery_by'"
        )->result_array();
        
        return $rs;
    } 
    
    function get_detail_exists($transnum){
        $rs = array();
        if(empty($transnum)||$transnum == '' ) return $rs;
        
        $this->db->flush_cache();
        $qry = $this->db->query("
                                SELECT 
                                    dn.*,
                                    wh.fsl_name,
                                    wh.fsl_location,
                                    wh.fsl_pic,
                                    wh.fsl_phone,
                                    us.user_fullname
                                FROM delivery_note AS dn
                                INNER JOIN warehouse_fsl AS wh ON dn.fsl_code = wh.fsl_code
                                INNER JOIN users AS us ON dn.user_key = us.user_key WHERE dn.delivery_note_num = ?",
                                array($transnum));
        $crow = $qry->result_array();
        
        if(count($crow > 0)){
            $rs = $crow[0];
            $qry_d = $this->db->query("
                                SELECT 
                                    dnd.*,
                                    p.part_name
                                FROM 
                                    delivery_note_detail AS dnd
                                INNER JOIN parts AS p ON p.part_number = dnd.part_number
                                WHERE dnd.delivery_note_num = ?", 
                              array($transnum));
            $rs_d = $qry_d->result_array();
            if(count($rs_d > 0)){
                $rs['detail'] = $rs_d;
            }
        }
        
        return $rs;
    }

    /*
    *
    */
    public function update_qty($transnum){
        $this->db->query("UPDATE
                {$this->tbl_} 
            INNER JOIN (
                SELECT 
                    {$this->tbl_}_detail.{$this->indexKey}, 
                    SUM({$this->tbl_}_detail.dt_{$this->tbl_}_qty) summary 
                FROM {$this->tbl_}_detail 
                WHERE {$this->tbl_}_detail.{$this->indexKey} = '$transnum'
                GROUP BY {$this->tbl_}_detail.{$this->indexKey}
            ) dnd ON {$this->tbl_}.{$this->indexKey} = dnd.{$this->indexKey}
            SET {$this->tbl_}.{$this->tbl_}_qty = dnd.summary
            WHERE {$this->tbl_}.{$this->indexKey} = '$transnum'
        ");
        return $this->db->affected_rows();
    }
}