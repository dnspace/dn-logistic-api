<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class PartSub_model extends CI_Model
{
    var $tbl_partsub = 'parts_subtitute';
    var $primKey = 'partsub_id';
    var $indexKey = 'part_number';
    var $order = array('part_number' => 'asc'); // default order

    function __construct()
    {
        parent::__construct();
    }
 
    function count_all()
    {
        $this->db->from($this->tbl_partsub);
        $this->db->where('is_deleted', 0);
        return $this->db->count_all_results();
    }

    function get_data($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        // Turn caching on
		$this->db->cache_on();
        $this->db->select('*');
        $this->db->from($this->tbl_partsub);

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
    
    function get_viewdata($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
		// Turn caching on
		$this->db->cache_on();
        $this->db->select('*');
        $this->db->from($this->view_parts);

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
        $this->db->insert($this->tbl_partsub, $dataInfo);
        
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
        $this->db->select('*');
        $this->db->from($this->tbl_partsub);
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
        $this->db->where($this->indexKey, $id);
        $this->db->update($this->tbl_partsub, $dataInfo);
        
        return TRUE;
    }
    
    /**
     * This function is used to delete the data information
     * @param number $id : This is data id
     * @return boolean $result : TRUE / FALSE
     */
     function delete_data($id)
     {
         $this->db->where($this->indexKey, $id);
         $this->db->delete($this->tbl_partsub);
         
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
         $this->db->from($this->tbl_partsub);
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