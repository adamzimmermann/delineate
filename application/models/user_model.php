<?php

class User_model extends CI_Model {
	
	public function __construct() {
		$this->load->database();
	}
	
	public function listing($input = FALSE) {
		if ($input === FALSE) {
			$query = $this->db->get('user');
			return $query->result_array();
		}
		$query = $this->db->get_where('fillup', array('user_ID' => $input));
		return $query->result_array();
	}
	
	/*---------------------------------------------------------------------------------------------------------*/
	
}