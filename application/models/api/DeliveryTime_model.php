<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class DeliveryTime_model extends CI_models{
	public function __construct(){
		parent::__construct();
	}

	public function get_data_delivery_by(){
		$rs = $this->db->query("SELECT delivery_time.delivery_by FROM delivery_time GROUP BY delivery_by")->result_array();
		return $rs;
	}
}
