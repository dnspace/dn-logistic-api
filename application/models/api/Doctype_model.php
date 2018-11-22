<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Doctype_model extends CI_Model
{
    var $tbl_doctype = "document_type";
    var $tbl_subtype = "subtype";
    var $indexKey = 'id';
    var $order = array('id' => 'asc'); // default order 

    function __construct()
    {
        parent::__construct();
    }

    function count_all()
    {
        $this->db->from($this->tbl_doctype);
        return $this->db->count_all_results();
    }
	
	function get_list_by($arrWhere){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('dt.*');
        $this->db->from($this->tbl_doctype.' AS dt');
        $this->db->order_by('dt.id','ASC');

        if(count($arrWhere) > 0){
            foreach ($arrWhere as $strField => $strValue){
                if (is_array($strValue)){
                    $this->db->where_in($strField, $strValue);
                }else{
                    $this->db->where($strField, $strValue);
                }
            }
            $query = $this->db->get();
            $rs = $query->result_array();
        }else{
            $query = $this->db->get();
            $rs = $query->result_array();
        }
        return $rs;
    }
}