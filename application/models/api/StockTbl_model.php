<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class StockTbl_model extends CI_Model
{
    protected $tbl_stock_wh = 'p_stock_fsl';
    protected $view_tbl_stock = 'viewtblstocks';
    protected $view_tbl_whstock = 'view_warehouses_stock';
    protected $primKey = 'stock_id';
    protected $indexKey = 'stock_fsl_code';
    protected $indexKey2 = 'stock_part_number';
    protected $order = array('stock_part_number' => 'asc'); // default order

    function __construct()
    {
        parent::__construct();
    }
 
    function count_all($fslcode)
    {
        $this->db->from($this->tbl_stock_wh."_".$fslcode);
        return $this->db->count_all_results();
    }
	
	function make_like_conditions (array $fields, $search_text) {
		$likes = array();
		foreach ($fields as $field) {
			$likes[] = "$field LIKE '%$search_text%'";
		}
		return '('.implode(' || ', $likes).')';
	}

    function get_data($arrWhere = array(), $arrOrder = array(), $type = "AND", $fslcode){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('*');
        $this->db->from($this->tbl_stock_wh."_".$fslcode);

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
        
        //Order By
        if (count($arrOrder) > 0){
            foreach ($arrOrder as $strField => $strValue){
                $this->db->order_by($strField, $strValue);
            }
        }
        
        return $rs;
    }
	
	function get_data_fsl($arrWhere = array(), $arrOrder = array(), $type = "AND", $fslcode){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('pst.stock_id, pst.stock_fsl_code, pst.stock_part_number, p.part_name, pst.stock_min_value, pst.stock_init_value, pst.stock_last_value, pst.stock_init_flag');
        $this->db->from($this->tbl_stock_wh."_".$fslcode." AS pst");
        $this->db->join('parts as p','pst.stock_part_number = p.part_number', 'left');

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
        
        //Order By
        if (count($arrOrder) > 0){
            foreach ($arrOrder as $strField => $strValue){
                $this->db->order_by($strField, $strValue);
            }
        }
		
		$query = $this->db->get();
		$rs = $query->result_array();
        
        return $rs;
    }
	
	function get_data_fsl_sub($arrWhere = array(), $arrOrder = array(), $type = "AND", $fslcode){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('pst.stock_fsl_code, pst.stock_part_number, p.part_name, 
							(
								CASE 
									WHEN ps.part_number_sub IS NOT NULL THEN ps.part_number_sub
									WHEN ps.part_number_sub = "" THEN "NO SUBTITUTION"
									ELSE "NO SUBTITUTION" 
								END
							) AS partsub,
							 pst.stock_min_value, pst.stock_init_value, pst.stock_last_value, pst.stock_init_flag');
        $this->db->from($this->tbl_stock_wh."_".$fslcode." AS pst");
        $this->db->join('parts_subtitute as ps','pst.stock_part_number = ps.part_number', 'left');
        $this->db->join('parts as p','pst.stock_part_number = p.part_number', 'left');

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
        
        //Order By
        if (count($arrOrder) > 0){
            foreach ($arrOrder as $strField => $strValue){
                $this->db->order_by($strField, $strValue);
            }
        }
		
		$query = $this->db->get();
		$rs = $query->result_array();
        
        return $rs;
    }
	
	function get_data_detail_fsl($arrWhere = array(), $arrOrder = array(), $type = "AND", $fslcode){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('pst.stock_id, pst.stock_fsl_code, pst.stock_part_number, p.part_name, pst.stock_init_value, pst.stock_min_value,
			(
				SELECT CASE WHEN SUM(od.dt_outgoing_qty) IS NULL THEN 0 ELSE SUM(od.dt_outgoing_qty) END AS qtyonhand FROM outgoings_detail AS od 
				INNER JOIN outgoings AS o ON od.outgoing_num = o.outgoing_num 
				WHERE o.outgoing_status = "open" 
				AND o.fsl_code = "'.strtoupper($fslcode).'" 
				AND o.outgoing_purpose <> "RWH" 
				AND o.is_deleted = 0 
				AND od.is_deleted = 0 
				AND od.part_number = pst.stock_part_number
			) AS qty_onhand, 
			pst.stock_last_value, pst.stock_init_flag 
		');
        $this->db->from($this->tbl_stock_wh."_".$fslcode." AS pst");
        $this->db->join('parts as p','pst.stock_part_number = p.part_number', 'left');

		if(empty($arrWhere)){
            $rs = array();
        }else{
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
			//Order By
			if (count($arrOrder) > 0){
				foreach ($arrOrder as $strField => $strValue){
					$this->db->order_by($strField, $strValue);
				}
			}
			
			$query = $this->db->get();
			$rs = $query->result_array();
		}
        
        return $rs;
    }
	
	function get_data_whstock($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('*');
        $this->db->from($this->view_tbl_whstock);

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
        
        //Order By
        if (count($arrOrder) > 0){
            foreach ($arrOrder as $strField => $strValue){
                $this->db->order_by($strField, $strValue);
            }
        }
		
		$query = $this->db->get();
		$rs = $query->result_array();
        
        return $rs;
    }
    
    function get_viewdata($arrSearch = array(), $searchText){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('*');
        $this->db->from($this->view_tbl_stock);
		
		$like_conditions = $this->make_like_conditions($arrSearch, $searchText);
		$this->db->where($like_conditions);
		
		$query = $this->db->get();
		$rs = $query->result_array();
        
        return $rs;
    }

	function get_sub_data($arrWhere = array(), $arrOrder = array(), $type = "AND", $fslcode){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('pf.stock_fsl_code, pf.stock_part_number, p.part_name, pf.stock_min_value, pf.stock_init_value, pf.stock_last_value, 
			pf.stock_init_flag, ps.part_number_sub');
        $this->db->from($this->tbl_stock_wh."_".$fslcode." AS pf");
        $this->db->join('parts_subtitute as ps','pf.stock_part_number = ps.part_number', 'left');
        $this->db->join('parts as p','pf.stock_part_number = p.part_number', 'left');

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
        
        //Order By
        if (count($arrOrder) > 0){
            foreach ($arrOrder as $strField => $strValue){
                $this->db->order_by($strField, $strValue);
            }
        }
		
		$query = $this->db->get();
		$rs = $query->result_array();
        
        return $rs;
    }
    
    /**
     * This function is used to add new data to system
     * @return number $insert_id : This is last inserted id
     */
    function insert_data($dataInfo, $fslcode)
    {
        $this->db->trans_start();
        $this->db->insert($this->tbl_stock_wh."_".$fslcode, $dataInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get data information by id
     * @param number $id : This is id
     * @return array $result : This is data information
     */
    function get_data_info($fslcode, $partnum)
    {
        $this->db->select('*');
        $this->db->from($this->tbl_stock_wh."_".$fslcode);
        $this->db->where($this->indexKey, strtoupper($fslcode));
        $this->db->where($this->indexKey2, $partnum);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the data information
     * @param array $dataInfo : This is data updated information
     * @param number $id : This is data id
     */
    function update_data($dataInfo, $fslcode, $id)
    {
        $this->db->where($this->indexKey, strtoupper($fslcode));
        $this->db->where($this->indexKey2, $id);
        $this->db->update($this->tbl_stock_wh."_".$fslcode, $dataInfo);
        
        return TRUE;
    }
    
    /**
     * This function is used to delete the data information
     * @param number $id : This is data id
     * @return boolean $result : TRUE / FALSE
     */
     function delete_data($fslcode, $id)
     {
        $this->db->where($this->indexKey, strtoupper($fslcode));
        $this->db->where($this->indexKey2, $id);
		$this->db->delete($this->tbl_stock_wh."_".$fslcode);

		return $this->db->affected_rows();
     }

    /**
     * This function is used to check whether field is already exist or not
     * @param {string} $param : This is param
     * @return {mixed} $result : This is searched result
     */
     function check_data_exists($arrWhere = array(), $fslcode)
     {
         //Flush Param
         $this->db->flush_cache();
         $this->db->from($this->tbl_stock_wh."_".$fslcode);
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