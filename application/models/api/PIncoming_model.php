<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class PIncoming_model extends CI_Model
{
    var $tbl_incoming = 'incomings';
    var $view_incoming = 'viewincomings';
    var $primKey = 'incoming_id';
    var $indexKey = 'incoming_num';
    var $order = array('incoming_num' => 'desc'); // default order

    function __construct()
    {
        parent::__construct();
    }
 
    function count_all()
    {
        $this->db->from($this->tbl_incoming);
        $this->db->where('is_deleted', 0);
        return $this->db->count_all_results();
    }

    function get_data($arrWhere = array(), $arrOrder = array(), $type = "AND"){
        $rs = array();
        //Flush Param
        $this->db->flush_cache();
        
        $this->db->select('*');
        $this->db->from($this->tbl_incoming);

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
        $this->db->from($this->view_incoming);

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
        
        //Order By
        if (count($arrOrder) > 0){
            foreach ($arrOrder as $strField => $strValue){
                $this->db->order_by($strField, $strValue);
            }
        }
        
        return $rs;
    }
    
    function get_key_data($param) {
        $this->db->flush_cache();
        $q = $this->db->query("SELECT MAX(RIGHT(incoming_num,4)) AS idmax FROM ".$this->tbl_incoming." WHERE is_deleted = 0");
        $kd = ""; //first code
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->idmax); //konversi julah nilai digit yang didapat ke integer
                $tmp = $tmp+1; //lalu ditambahkan nilai 1 dari digit tersebut
                $kd = str_pad($tmp,4,'0',STR_PAD_LEFT); //Pad some digits to the left side of the string
            }
        }else{ //jika data kosong, maka set digit awal yaitu 1
            $kd = 1;
            $kd = str_pad($kd,4,'0',STR_PAD_LEFT);
        }
        $firstdate = date('Y-m-d', strtotime('first day of this month'));
        $curdate = date('Y-m-d');
        $maskdate = date('ym');
		
		if($curdate == $firstdate){
			$kd = 1;
			$kd = str_pad($kd,4,'0',STR_PAD_LEFT);
		}
        //gabungkan string dengan kode yang telah dibuat tadi
        // return $param.$fslcode.$maskdate.$kd;
        return $param.$maskdate.$kd;
//        return $param.$kd;
    }
	
    function get_key_data_ext($param, $digits) {
        $this->db->flush_cache();
        $q = $this->db->query("SELECT MAX(RIGHT(incoming_num,".$digits.")) AS idmax FROM ".$this->tbl_incoming." WHERE is_deleted = 0");
        $kd = ""; //first code
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->idmax); //konversi julah nilai digit yang didapat ke integer
                $tmp = $tmp+1; //lalu ditambahkan nilai 1 dari digit tersebut
				if($tmp < 10000){
					$kd = str_pad($tmp,$digits,'0',STR_PAD_LEFT); //Pad some digits to the left side of the string
				}elseif($tmp < 100000){
					$kd = str_pad($tmp,$digits+1,'0',STR_PAD_LEFT); //Pad some digits to the left side of the string
				}elseif($tmp < 1000000){
					$kd = str_pad($tmp,$digits+2,'0',STR_PAD_LEFT); //Pad some digits to the left side of the string
				}
            }
        }else{ //jika data kosong, maka set digit awal yaitu 1
            $kd = 1;
            $kd = str_pad($kd,$digits,'0',STR_PAD_LEFT);
        }
        $firstdate = date('Y-m-d', strtotime('first day of this month'));
        $curdate = date('Y-m-d');
        $maskdate = date('ym');
		
        // if($curdate == $firstdate){
			// $kd = 1;
			// $kd = str_pad($kd,$digits,'0',STR_PAD_LEFT);
        // }
		$millsec = substr(round(microtime(true)*1000), -2);
        //gabungkan string dengan kode yang telah dibuat tadi
        return $param.$maskdate.$millsec.$kd;
        // return $tmp;
    }
	
	/**
    *  Get Transaction Number
    *  
    *  Generating Transaction Number with dynamic value
    *  @param String $param Prefix of the transaction number
    *  @param Integer $pad Number digit you want to padding
    *  @return String new Transaction number
    */
    public function get_key_data_sql($param, $pad) {
        $this->db->flush_cache();
        
        //var
        $table = $this->tbl_incoming;
        $table_num = $this->indexKey;

        //logic
        $digit_prefix           = strlen($param);
        $digit_sum_tanggal      = 4;
        $digit_insert_tanggal   = $digit_prefix + 1;
        $digit_insert_padnum    = $digit_sum_tanggal + $digit_insert_tanggal + 1;

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
	function get_key_data($param, $fslcode) {
        $this->db->flush_cache();
        $q = $this->db->query("SELECT MAX(RIGHT(incoming_num,4)) AS idmax FROM ".$this->tbl_incoming." WHERE is_deleted = 0");
        $kd = ""; //first code
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->idmax); //konversi julah nilai digit yang didapat ke integer
                $tmp = $tmp+1; //lalu ditambahkan nilai 1 dari digit tersebut
                $kd = str_pad($tmp,4,'0',STR_PAD_LEFT); //Pad some digits to the left side of the string
            }
        }else{ //jika data kosong, maka set digit awal yaitu 1
            $kd = 1;
            $kd = str_pad($kd,4,'0',STR_PAD_LEFT);
        }
        $firstdate = date('d-m-Y', strtotime('first day of this month'));
        $curdate = date('Y-m-d');
        $maskdate = date('ym');
        //gabungkan string dengan kode yang telah dibuat tadi
        return $param.$fslcode.$maskdate.$kd;
//        return $param.$kd;
    }
	*/
    
    /**
     * This function is used to add new data to system
     * @return number $insert_id : This is last inserted id
     */
    function insert_data($dataInfo)
    {
        $this->db->trans_start();
        $this->db->insert($this->tbl_incoming, $dataInfo);
        
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
        $this->db->from($this->tbl_incoming);
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
        $this->db->update($this->tbl_incoming, $dataInfo);
        
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
         $this->db->delete($this->tbl_incoming);
         
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
         $this->db->from($this->tbl_incoming);
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