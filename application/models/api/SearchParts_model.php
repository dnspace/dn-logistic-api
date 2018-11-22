<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class SearchParts_model extends CI_Model
{
    
    function __construct(){
        parent::__construct();
    }
 
    function get_part_list(){
    	$data_imp = array();
        $result = array();

    	$data = $this->input->post('fsearch', TRUE);
    	$data_imp = '\''.preg_replace('/\r\n|\r|\n/', "','", $data).'\'';
        $data_arr = preg_split('/\r\n|\r|\n/', $data);
        if(!is_array($data_arr)){
            $data_arr = array($data_arr);
        }
    	$arr_list_parts = array();
        $i = 0;
        if(count($data_arr)>0){
            $rs_parts = $this->db->query("
                        SELECT p.*, ps.part_number_sub FROM parts AS p
                        INNER JOIN parts_subtitute AS ps ON ps.part_number =  p.part_number
                        WHERE 
                        p.part_number IN($data_imp)
                    ")->result_array(); 
            foreach($rs_parts as $d){
                
                $result[$i] = $d; 
                array_push($result[$i], 'keterangan');
                $result[$i]['keterangan'] = '';
                if(strpos($d['part_number_sub'],';')>0){
                    $data_split = preg_split('/;/', $d['part_number_sub'], PREG_SPLIT_NO_EMPTY);    
                }else{
                    $data_split = array($d['part_number_sub']);
                }
                var_dump($d['part_number_sub']);
                foreach($data_split as $dp){
                    $rs_subs = $this->db->query("SELECT * FROM p_stock_fsl_wsps WHERE stock_part_number = '$dp'")->row_array();
                    $keterangan = $rs_subs['stock_part_number'] . '[' . $rs_subs['stock_last_value'] . ']';
                    $result[$i]['keterangan'] .= $keterangan;
                }
                $i++;
            }
        }
    	
    	// $this->db->query("SELECT * FROM parts WHERE");

     //   	$this->db->query("SELECT * FROM parts_subtitute WHERE");	
     	return $result;
    }

    function get_parts_info(){
        $result = array();
        $i = 1;
        $data = $this->input->post('fsearch', TRUE);
        $data_imp = '\''.preg_replace('/\r\n|\r|\n|\s/', "','", $data).'\'';

        $rs_data = $this->db->query("
        SELECT p.*, 
            psf.stock_rack_loc,
            ps.part_number_sub, 
		    (
			   CASE 
				   WHEN psf.stock_init_flag = 'Y' THEN psf.stock_init_value
				   WHEN psf.stock_init_flag = 'N' THEN psf.stock_last_value
			   END
		    ) AS Stock
            FROM parts AS p
        LEFT JOIN parts_subtitute AS ps ON ps.part_number =  p.part_number
        INNER JOIN p_stock_fsl_wsps AS psf ON psf.stock_part_number = p.part_number
        WHERE 
            p.part_number IN($data_imp)
           
            ")->result_array();

        foreach($rs_data as $dt_arr){
            $result[$i] = $dt_arr;
            if($dt_arr['Stock']==0){
                $part_subtitute = array();
                $data_subtitute_split = '\''.preg_replace('/;/', "','", $dt_arr['part_number_sub']).'\'';
                $rs_data_sub = $this->db->query("SELECT stock_part_number, stock_last_value, stock_rack_loc FROM p_stock_fsl_wsps WHERE stock_part_number IN ($data_subtitute_split)")->result_array();
                foreach($rs_data_sub as $dt_arr_sub){
                    if($dt_arr_sub['stock_part_number'] != $dt_arr['part_number'])
                    $part_subtitute[] = $dt_arr_sub['stock_part_number'].' ('.$dt_arr_sub['stock_last_value'].')('.$dt_arr_sub['stock_rack_loc'].')';
                }
                //var_dump($data_subtitute_split);
                $imp_sub = implode(',&nbsp;&nbsp;',$part_subtitute);
                $result[$i]['part_subtitute'] = $imp_sub;
                //array_push($result[$i],array("part_subtitute"=>$part_subtitute));
                
            }else{
                // $result[$i]['stock_last_value'] = $dt_arr['Stock'];
                $result[$i]['part_subtitute'] = '';
            }
			$i++;
        }

        return $result;
    }
}