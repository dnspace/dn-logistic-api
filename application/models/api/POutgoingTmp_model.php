<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class POutgoingTmp_model extends CI_Model
{
    var $tbl_outgoing_t = 'outgoings_tmp';
    var $view_outgoing = 'viewoutgoings';
    var $primKey = 'tmp_outgoing_id';
    var $indexKey = 'tmp_outgoing_uniqid';
    var $indexKey2 = 'part_number';
    var $indexKey3 = 'serial_number';
    var $order = array('tmp_outgoing_id' => 'desc'); // default order

    function __construct()
    {
        parent::__construct();
    }
 
    function count_all()
    {
        $this->db->from($this->tbl_outgoing_t);
        $this->db->where('is_deleted', 0);
        return $this->db->count_all_results();
    }
	
	function count_cart($arrWhere = array())
    {
		$rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('SUM(tmp_outgoing_qty) AS total');
        $this->db->from($this->tbl_outgoing_t);

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
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('*');
        $this->db->from($this->tbl_outgoing_t);

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
    
    function get_viewdata($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('*');
        $this->db->from($this->view_outgoing);

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
    
    /**
     * This function is used to add new data to system
     * @return number $insert_id : This is last inserted id
     */
    function insert_data($dataInfo)
    {
        $this->db->trans_start();
        $this->db->insert($this->tbl_outgoing_t, $dataInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get data information by id
     * @param number $id : This is id
     * @return array $result : This is data information
     */
    function get_data_info($cartid)
    {
        $this->db->select('*');
        $this->db->from($this->tbl_outgoing_t);
        $this->db->where($this->indexKey, $cartid);
        $query = $this->db->get();
        
        return $query->result();
    }
	
	/**
     * This function used to get data information by id
     * @param number $id : This is id
     * @return array $result : This is data information
     */
    function get_data_info_2($partnum, $serialnum, $cartid)
    {
        $this->db->select('*');
        $this->db->from($this->tbl_outgoing_t);
        $this->db->where($this->indexKey2, $partnum);
        $this->db->where($this->indexKey3, $serialnum);
        $this->db->where($this->indexKey, $cartid);
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
        $this->db->where($this->primKey, $id);
        $this->db->update($this->tbl_outgoing_t, $dataInfo);
        
        return TRUE;
    }
	
	/**
     * This function is used to update the data information
     * @param array $dataInfo : This is data updated information
     * @param number $id : This is data id
     */
    function update_data_2($dataInfo, $partnum, $serialnum, $cartid)
    {
        $this->db->where($this->indexKey2, $partnum);
        $this->db->where($this->indexKey3, $serialnum);
        $this->db->where($this->indexKey, $cartid);
        $this->db->update($this->tbl_outgoing_t, $dataInfo);
        
        return TRUE;
    }
    
    /**
     * This function is used to delete the data information
     * @param number $id : This is data id
     * @return boolean $result : TRUE / FALSE
     */
     function delete_data($id)
     {
         $this->db->where($this->primKey, $id);
         $this->db->delete($this->tbl_outgoing_t);
         
         return $this->db->affected_rows();
     }
	 
	 /**
     * This function is used to delete the data information
     * @param number $id : This is data id
     * @return boolean $result : TRUE / FALSE
     */
     function delete_data2($cartid)
     {
         $this->db->where($this->indexKey, $cartid);
         $this->db->delete($this->tbl_outgoing_t);
         
         return $this->db->affected_rows();
     }
	 
	 function get_abandoned_cart($fcode){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
		$prev_day = date('Y-m-d', mktime(0,0,0, date('m'), date('d') - 1, date('Y')));
        
        $this->db->select('*');
        $this->db->from($this->tbl_outgoing_t);
		$this->db->where("fsl_code", strtoupper($fcode));
		$this->db->where("createdAt <", $prev_day);
		$query = $this->db->get();
		$rs = $query->result_array();
		
		return $rs;
	 }
	 
	 /**
     * This function is used to delete the data information
     * @param number $id : This is data id
     * @return boolean $result : TRUE / FALSE
     */
     function delete_abandoned_cart($fcode)
     {
		 $prev_day = date('Y-m-d', mktime(0,0,0, date('m'), date('d') - 1, date('Y')));
         $this->db->query("DELETE FROM ".$this->tbl_outgoing_t." WHERE createdAt < '".$prev_day."' AND fsl_code = '".strtoupper($fcode)."'");
         
         return $this->db->affected_rows();
     }

    /**
     * This function is used to check whether field is already exist or not
     * @param {string} $param : This is param
     * @return {mixed} $result : This is searched result
     */
     function check_data_exists($arrWhere = array())
     {
         //Flush Param
         $this->db->flush_cache();
         $this->db->from($this->tbl_outgoing_t);
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