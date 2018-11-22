<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class UserGroup_model extends CI_Model
{
    var $tbl_ugroup = 'user_group';
    var $view_ugroup = 'viewusers';
    var $primKey = 'group_id';
    var $indexKey = 'group_enc';
    var $order = array('group_display' => 'asc'); // default order

    function __construct()
    {
        parent::__construct();
    }
 
    function get_total_rows()
    {
        $this->db->from($this->tbl_ugroup);
        return $this->db->count_all_results();
    }

    function get_data($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('*');
        $this->db->from($this->tbl_ugroup);

		//for user group there is no need to check if parameter $arrWhere are empty or not because its only show all data list
		if($type == "AND"){
			foreach ($arrWhere as $strField => $strValue){
				if (is_array($strValue)){
					$this->db->where_in($strField, $strValue);
				}else{
					$this->db->where($strField, $strValue);
				}
			}
		}else{
			foreach ($arrWhere as $strField => $strValue){
				if (is_array($strValue)){
					$this->db->where_in($strField, $strValue);
				}else{
					$this->db->or_where($strField, $strValue);
				}
			}
		}
		$query = $this->db->get();
		$rs = $query->result_array();
        
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
        $this->db->from($this->view_ugroup);

		//for user group there is no need to check if parameter $arrWhere are empty or not because its only show all data list
		if($type == "AND"){
			foreach ($arrWhere as $strField => $strValue){
				if (is_array($strValue)){
					$this->db->where_in($strField, $strValue);
				}else{
					$this->db->where($strField, $strValue);
				}
			}
		}else{
			foreach ($arrWhere as $strField => $strValue){
				if (is_array($strValue)){
					$this->db->where_in($strField, $strValue);
				}else{
					$this->db->or_where($strField, $strValue);
				}
			}
		}
		$query = $this->db->get();
		$rs = $query->result_array();
        
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
        $this->db->insert($this->tbl_ugroup, $dataInfo);
        
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
        $this->db->from($this->tbl_ugroup);
        $this->db->where($this->primKey, $id);
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
        $this->db->update($this->tbl_ugroup, $dataInfo);
        
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
         $this->db->delete($this->tbl_ugroup);
         
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
         $this->db->from($this->tbl_ugroup);
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