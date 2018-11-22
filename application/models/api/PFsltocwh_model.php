<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class PFsltocwh_model extends CI_Model
{
    var $tbl_ = 'fsltocwh';
    var $view_ = 'viewfsltocwh';
    var $primKey = 'fsltocwh_id';
    var $indexKey = 'fsltocwh_num';
    var $order = array('fsltocwh_num' => 'desc'); // default order

    function __construct(){
        parent::__construct();
    }
 
    function count_all(){
        $this->db->from($this->tbl_);
        $this->db->where('is_deleted', 0);
        return $this->db->count_all_results();
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

    function get_viewdata_close($arrWhere = array(), $arrOrder = array(), $type = "AND"){
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
                        if(strpos(strtolower($strField), 'date_close_1') !== false){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField >= '".$strValue." 00:00:00' ");
                            }
                        }elseif(strpos(strtolower($strField), 'date_close_2') !== false){
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
                        if(strpos(strtolower($strField), 'date_close_1') !== false){
                            $strField = substr($strField, 0, -2);
                            if(!empty($strValue)){
                                $this->db->where("$strField >= '".$strValue." 00:00:00' ");
                            }
                        }elseif(strpos(strtolower($strField), 'date_close_2') !== false){
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
    
    /**
    * This function is used to get value of ETA
    * @param {string} $fsl_code : FSL code
    * @param {string} $delivery_type : delivery yang digunakan
    * @return {mixed} $result : This is searched result
    */
    function get_eta($fsl_code, $delivery_type){
        $rs = array();
        $rs = $this->db->query(
            "SELECT *, DATE_ADD(DATE(NOW()),INTERVAL delivery_time_value DAY) AS 'ETA' FROM delivery_time WHERE fsl_code = '$fsl_code' AND delivery_time_type = '$delivery_type'"
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
                                FROM {$this->tbl_} AS dn
                                INNER JOIN warehouse_fsl AS wh ON dn.fsl_code = wh.fsl_code
                                INNER JOIN users AS us ON dn.user_key = us.user_key WHERE dn.{$this->tbl_}_num = ?",
                                array($transnum));
        $crow = $qry->result_array();
        
        if(count($crow > 0)){
            $rs = $crow[0];
            $qry_d = $this->db->query("
                                SELECT 
                                    dnd.*,
                                    p.part_name
                                FROM 
                                    {$this->tbl_}_detail AS dnd
                                INNER JOIN parts AS p ON p.part_number = dnd.part_number
                                WHERE dnd.{$this->tbl_}_num = ?", 
                              array($transnum));
            $rs_d = $qry_d->result_array();
            if(count($rs_d > 0)){
                $rs['detail'] = $rs_d;
            }
        }
        
        return $rs;
    }

    public function closing_trans($transnum,$notes,$user){
        $status_pending = 'open';
        $rs_trans = array();
        $rs_check_pending = array();
        $rs = array();
        $stock_table = '';

        $rt_update = 0;
        $rt_update_stock_wsps = 0;
        $rt_insert_trans = 0;
        $rt_insert_detail = 0;
        $rt_update_stock_dnrc = 0;
        
        $q_trans = $this->db->get_where($this->tbl_, array($this->indexKey=>$transnum)); //ambil data dari table transaksi.
        if($q_trans->num_rows() > 0){
            $rs_trans = $q_trans->row_array();
            switch($rs_trans[$this->tbl_.'_purpose']){ //pemilahan table stock
                case 'RBP':$stock_table = 'badpart';break;
                case 'RBS':$stock_table = 'badstock';break;
            }
        }
        $rs_check_pending = $this->db->query("SELECT * FROM {$this->tbl_}_detail 
            WHERE {$this->indexKey} = '$transnum' AND dt_notes ='doesnt_exist'
        "); //seleksi data sekaligus check part yang tidak ada/ doesn't exist
        $rows_number = $rs_check_pending->num_rows();
        $rs_pending = $rs_check_pending->row_array(); //mengambil data dari index yang pending.

        if($rows_number>0){ //check ada notes pending atau tidak?
            $status_pending = 'pending';
        }else{
            $status_pending = 'closed';
        }
       
        $this->db->query("UPDATE 
            {$this->tbl_} SET 
                closed_at = NOW(),
                closed_notes = '$notes',
                closed_user_key = '$user',
                {$this->tbl_}_status = '$status_pending'
            WHERE {$this->indexKey} = '$transnum'
        "); //update data closing
        $rt_update = $this->db->affected_rows() . ' OK!';

        if($rt_update > 0){ //jika sukses update data closing, maka ..
            $this->db->query("UPDATE p_stock_fsl_wsps_{$stock_table} 
                INNER JOIN {$this->tbl_}_detail ON {$this->tbl_}_detail.part_number = p_stock_fsl_wsps_{$stock_table}.stock_part_number
                SET p_stock_fsl_wsps_{$stock_table}.stock_last_value = IF(p_stock_fsl_wsps_{$stock_table}.stock_init_flag='Y', (p_stock_fsl_wsps_{$stock_table}.stock_init_value + {$this->tbl_}_detail.dt_{$this->tbl_}_qty), (p_stock_fsl_wsps_{$stock_table}.stock_last_value + {$this->tbl_}_detail.dt_{$this->tbl_}_qty))
                WHERE {$this->tbl_}_detail.{$this->indexKey} = '$transnum' AND ({$this->tbl_}_detail.dt_notes IS NULL OR {$this->tbl_}_detail.dt_notes != 'doesnt_exist')
            "); //Update Stock WSPS BAD STOCK / BAD PART
            $rt_update_stock_wsps = $this->db->affected_rows() . ' OK!';

            if($rt_update_stock_wsps > 0){
                // DUPPLIKASI DATA RO
                $this->db->query("INSERT INTO repairorder(
                    repairorder_num,
                    repairorder_date,
                    repairorder_purpose,
                    repairorder_notes,
                    repairorder_qty,
                    user_key,
                    fsl_code,
                    repairorder_status
                ) SELECT
                    REPLACE(fsltocwh_num,'DN-','RO-'),
                    fsltocwh_date,
                    fsltocwh_purpose,
                    fsltocwh_notes,
                    fsltocwh_qty,
                    user_key,
                    fsl_code,
                    'open'
                FROM fsltocwh            
                WHERE fsltocwh_num = '$transnum'"); //insert table repairorder berdasarkan transnum
                $rt_insert_trans = $this->db->affected_rows() . ' OK!';

                $this->db->query("INSERT INTO repairorder_detail(
                    repairorder_num,
                    part_number,
                    serial_number,
                    dt_repairorder_qty
                ) SELECT 
                    REPLACE(fsltocwh_num,'DN-','RO-'),
                    part_number,
                    serial_number,
                    dt_{$this->tbl_}_qty
                FROM {$this->tbl_}_detail 
                WHERE {$this->indexKey} = '$transnum' AND ({$this->tbl_}_detail.dt_notes IS NULL OR {$this->tbl_}_detail.dt_notes != 'doesnt_exist')"); //insert table repairorder_detail
                $rt_insert_detail = $this->db->affected_rows() . ' OK!';

                if(($rt_insert_trans > 0) AND ($rt_insert_detail > 0)){  //jika insert ke dalam table berhasil, maka ..
                    $this->db->query("UPDATE p_stock_fsl_dnrc_{$stock_table} 
                        INNER JOIN {$this->tbl_}_detail ON {$this->tbl_}_detail.part_number = p_stock_fsl_dnrc_{$stock_table}.stock_part_number
                        SET p_stock_fsl_dnrc_{$stock_table}.stock_last_value = IF(p_stock_fsl_dnrc_{$stock_table}.stock_init_flag='Y', (p_stock_fsl_dnrc_{$stock_table}.stock_init_value + {$this->tbl_}_detail.dt_{$this->tbl_}_qty), (p_stock_fsl_dnrc_{$stock_table}.stock_last_value + {$this->tbl_}_detail.dt_{$this->tbl_}_qty))
                        WHERE {$this->tbl_}_detail.{$this->indexKey} = '$transnum' AND ({$this->tbl_}_detail.dt_notes IS NULL OR {$this->tbl_}_detail.dt_notes != 'doesnt_exist')
                    "); //Update Stock DNRC BAD STOCK / BAD PART
                    $rt_update_stock_dnrc = $this->db->affected_rows() . ' OK!';
                    
                }
                
            }
            
        }

        $rs['update_closing_trans'] = $rt_update;
        $rs['update_stock_wsps'] = $rt_update_stock_wsps;
        $rs['insert_ro_trans'] = $rt_insert_trans;
        $rs['insert_ro_detail'] = $rt_insert_detail;
        $rs['update_stock_dnrc'] = $rt_update_stock_dnrc;
        return $rs;
    }
}