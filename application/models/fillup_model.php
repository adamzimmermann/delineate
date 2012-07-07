<?php

class fillup_model extends CI_Model {
	
	public function __construct() {
		$this->load->database();
	}
	
	public function listing($input = FALSE) {
		if ($input === 'FALSE') {
			$query = $this->db->get('fillup');
			return $query->result();
		}
		
		//foreach($input as $key=>$value) {
		//	$where;
		//}
		//$query = $this->db->get_where('fillup', $input);
		$query = $this->db->get_where('fillup', array('vehicle_ID' => $input));
		return $query->result();
	}
	
/*---------------------------------------------------------------------------------------------------------*/
	
	/*
		@PARAMETERS
			$vehicle_ID
				
	*/
	public function calculateMPG($vehicle_ID) {
		
		//$vehicle_ID = 0;
		
		if($vehicle_ID === 'FALSE') {
			//get all fillups for a vehicle
			$this->db->order_by("date", "asc");
			$query = $this->db->get('fillup');
			$fillups = $query->result_array();
		}
		else {
		
			//get all fillups for a vehicle
			$this->db->order_by("date", "asc");
			$query = $this->db->get_where('fillup', array('vehicle_ID'=> $vehicle_ID));
			$fillups = $query->result_array();
		
		}
		
		for($i=1; $i < count($fillups); $i++) {
					
			$fillups[$i]['mpg'] =  ($fillups[$i]['mileage'] - $fillups[$i-1]['mileage']) / $fillups[$i]['gallons'];
			
			$this->db->where('ID', $fillups[$i]['ID']);
			$this->db->update('fillup', $fillups[$i]); 		
		}
	}
	
/*---------------------------------------------------------------------------------------------------------*/
	
	
	public function insert($values) {
		//get the date
		$values['date'] = date("Y-m-d");
		
		//calculate cost
		$values['cost'] = $values['gallons'] * $values['price'];
		
		//assign the user
		$query = $this->db->get_where('vehicle', array('ID' => $values['vehicle_ID']));
		$vehicle = $query->row_array();
		$values['user_ID'] = $vehicle['user_ID'];
		
		//calculate mpg
		$this->db->limit(1);
		$this->db->order_by("date", "desc");
		$this->db->select('mileage');
		//$this->db->get_where('fillup', array('vehicle_ID'=> $values['vehicle_ID']));
		$query = $this->db->get_where('fillup', array('vehicle_ID'=> 0));
		
		$vehicle = $query->row_array();
		$mileage = $vehicle['mileage'];

		$values['mpg'] =  ($values['mileage'] - $mileage) / $values['gallons'];
		
		$this->db->insert('fillup', $values);
	}
/*---------------------------------------------------------------------------------------------------------*/

	public function vehicle_name($vehicle_ID) {
		$query = $this->db->get_where('vehicle', array('ID' => $values['vehicle_ID']));
		$vehicle = $query->row_array();
		return $vehicle['make'] + ' ' + $vehicle['model'];
	}


/*---------------------------------------------------------------------------------------------------------*/




/*---------------------------------------------------------------------------------------------------------*/



/*---------------------------------------------------------------------------------------------------------*/
}