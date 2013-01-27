<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fillups extends CI_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		
		$this->load->helper('form');
		
		$this->load->model('fillup_model');
		$this->load->model('user_model');
		$this->load->model('vehicle_model');


	}
	
	
	public function index() {
		
		//$data['fillups'] = $this->fillup_model->get_news();
		$data['title'] = 'Fillup Listing';
		$data['fillups'] = $this->fillup_model->listing();
		
		$this->load->view('templates/header', $data);
		$this->load->view('fillups/listing', $data);
		$this->load->view('templates/footer');
	}
	
	//fillup listing
	public function view($vehicle = 'FALSE') {
		
		
	
		if ($vehicle !== 'FALSE') {
			$this->fillup_model->calculateMPG($vehicle);
			$data['title'] = 'Fillups for the ' . $vehicle;
		}	
		else {		
			$data['title'] = 'Fillups';
		}	
		
		$data['fillups'] = $this->fillup_model->listing($vehicle);	
		$data['vehicles'] = $this->vehicle_model->listing();
		$data['users'] = $this->user_model->listing();
		
		$this->load->view('templates/header', $data);
		$this->load->view('fillups/listing', $data);
		$this->load->view('templates/footer');
		
	}
	
	
	//fillup add form
	// public function add() {
	// 	$data['title'] = 'Add a Fillup';
		
	// 	$this->load->view('templates/header', $data);
	// 	$this->load->view('fillups/add', $data);
	// 	$this->load->view('templates/footer');
	// }
	
	public function process() {
		
		//$values = $_POST;
		//$this->load->helper(array('form', 'url'));
		//
		//$this->load->library('form_validation');
		//
		//if ($this->form_validation->run() == FALSE)
		//{
		//	$this->load->view('fillups/add');
		//}
		//else
		//{
		//	$this->db->insert('fillup', $_POST);
		//	//redirect('fillup/add');
		//	$this->load->view('fillups/list');
		//	
		//}
		//print_r($values);
		
		//foreach($_POST as $key=>$value) {
		//	echo $key . ': ' . $value;
		//}
		
		//$this->input->post('date');
		
		//$values = $_POST;
		
		$this->fillup_model->insert($_POST);
		
		////get the date
		//$values['date'] = date("Y-m-d");
		//
		////calculate cost
		//$values['cost'] = $values['gallons'] * $values['price'];
		//
		////assign the user
		//$query = $this->db->get_where('vehicle', array('ID' => $_POST['vehicle_ID']));
		//$vehicle = $query->row_array();
		//$values['user_ID'] = $vehicle['user_ID'];
		//
		////calculate mpg
		//$this->db->limit(1);
		//$this->db->order_by("date", "desc");
		//$this->db->select('mileage');
		////$this->db->get_where('fillup', array('vehicle_ID'=> $values['vehicle_ID']));
		//$query = $this->db->get_where('fillup', array('vehicle_ID'=> 0));
		//
		//$vehicle = $query->row_array();
		//$mileage = $vehicle['mileage'];
		//
		//$values['mpg'] =  ($values['mileage'] - $mileage) / $values['gallons'];
		//
		//$this->db->insert('fillup', $values);
		//
		//$data['title'] = 'Fillup Listing';
		//$data['fillups'] = $this->fillup_model->listing();
		//$this->load->view('templates/header', $data);
		//$this->load->view('fillups/listing', $data);
		//$this->load->view('templates/footer');
		
		header( 'Location: /fillups' ) ;
	}
	
	
	
	
/*------------------------------------------------------------------------------------------------------------------------------------------*/
	
	
	public function update($input) {
		
		
		//header('Content-Type: text/plain; charset=ISO-8859-1');
		
		/* connect to gmail */
		$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
		$username = 'delineate@adamzimmermann.com';
		$password = 'd3pictdeta!l';
		
		/* try to connect */
		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
		
		/* grab emails */
		$emails = imap_search($inbox,'UNSEEN');
		
		print_r($emails);
		
		/* if emails are returned, cycle through each... */
		if ($emails) {
  
			//puts oldest emails first
			// sort($emails);
			
			//testing
			//rsort($emails);
			
			$index = 0;
			
			//loops through emails
			foreach($emails as $email_number) {
				
				// $message = imap_fetchbody($inbox, $email_number, "1.1");
				// if ($message == "") {
				// 	$message = imap_fetchbody($inbox, $email_number, "1");
				// }
				// $message = strip_tags(trim(substr(quoted_printable_decode($message), 0, 100)));
				
				//print 'MESSAGE: ' . $message;
				// $message = explode(' ', $message);

        $message = explode(' ', trim(imap_fetchbody($inbox,$email_number,2)));
        $values[$index]['mileage'] = strip_tags($message[0]);
        $values[$index]['price'] = strip_tags($message[1]);
        $values[$index]['gallons'] = strip_tags($message[2]);
        
        
				
				// $values[$index]['mileage'] = $message[0];
				// $values[$index]['price'] = $message[1];
				// $values[$index]['gallons'] = $message[2];
				if(isset($message[3])) {
					$vehicle_name = $message[3];
				}
				
				
				//reformat time
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$date = strtotime($overview[0]->date);
				$values[$index]['date'] = date('Y-m-d H:i:s', $date);
				
				
				//calculate cost
				$values[$index]['cost'] = $values[$index]['gallons'] * $values[$index]['price'];
				
				
				//find user id
				$header = imap_headerinfo($inbox, $email_number);
				$phone = strip_tags($header->from[0]->mailbox);
        print_r($phone);
				$query = $this->db->get_where('user', array('phone' => $phone));
				$user = $query->row_array();
				$values[$index]['user_ID'] = $user['ID'];
				
				
				//vehicle name set
				if(isset($vehicle_name)) {
					//find vehicle id from vehicle name
					$query = $this->db->get_where('vehicle', array('nickname' => $vehicle_name));
					$vehicle = $query->row_array();
					if($vehicle) {
						$values[$index]['vehicle_ID'] = $vehicle['ID'];
						$found = true;
					}
					else {
						$found = false;
					}
				}
				//find vehicle id if name not assigned or name was invalid
				if(!isset($vehicle_name) || $found == false) {
					//find vehicle id for that user
					$query = $this->db->get_where('vehicle', array('user_ID' => $values[$index]['user_ID']));
					$vehicle = $query->row_array();
					if($vehicle) {
						$values[$index]['vehicle_ID'] = $vehicle['ID'];
					}
				}
				
				//calculate mpg
				$this->db->limit(1);
				$this->db->order_by("date", "desc");
				$this->db->select('mileage');
				$query = $this->db->get_where('fillup', array('vehicle_ID'=> $values[$index]['vehicle_ID']));
				$vehicle = $query->row_array();
				$mileage = $vehicle['mileage'];
				$values[$index]['mpg'] =  ($values[$index]['mileage'] - $mileage) / $values[$index]['gallons'];
				
				// updates the database
				$this->db->insert('fillup', $values[$index]);
				
				
				$index++;
				}
			
			//if on a specific vehicle
			if($input != 'FALSE') {
				foreach($values as $value) {
					if($value['vehicle_ID'] == $input) {
						$results[] = $value;
					}
				}
			}
			//if on a general listing page
			else {
				$results = $value;
			}
			
			if(isset($results)) {
				echo json_encode($results);
			}
			else {
				print 'other';
			}
		}
		else {
			print 'false';
		}
		
		/* close the connection */
		imap_close($inbox);
	}
	
	public function calculate() {


	}
}


