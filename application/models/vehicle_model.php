<?php

class Vehicle_model extends CI_Model {
	
	public function __construct() {
		$this->load->database();
	}
	
	public function listing($input = FALSE) {
		if ($input === FALSE) {
			$query = $this->db->get('vehicle');
			return $query->result_array();
		}
		$query = $this->db->get_where('vehicle', array('vehicle_ID'=> $input));
		return $query->result_array();
	}
	
	/*---------------------------------------------------------------------------------------------------------*/

}