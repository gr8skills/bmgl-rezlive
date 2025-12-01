<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Include Composer autoloader for Carbon
require_once FCPATH . 'vendor/autoload.php';

use Carbon\Carbon;

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Homepage - Display default hotel search results
	 */
	public function index()
	{
		set_time_limit(0);
		$data['title'] = "Hotel | MakeIFly - Flight booking and Hotel Reservation";

		$now = date('d/m/Y');
		$afterFiveDays = date('d/m/Y', strtotime('+5 day'));
		$guests = '1,0,1';

		// Use Rezlive API library
		$apiResponse = $this->rezlive_api->findHotels(array(
			'arrivalDate' => $now,
			'departureDate' => $afterFiveDays,
			'countryCode' => DEFAULT_COUNTRY_CODE,
			'cityCode' => DEFAULT_CITY_CODE,
			'nationality' => DEFAULT_COUNTRY_CODE,
			'adults' => 1,
			'children' => 0,
			'rooms' => 1,
		));

		$data['apiResponse'] = $apiResponse;
		$data['hotelCount'] = 0;
		$data['hotels'] = array();

		if ($apiResponse && isset($apiResponse->Hotels) && isset($apiResponse->Hotels->Hotel)) {
			// Collect all Hotel elements into array (SimpleXML needs foreach to get all)
			$data['hotels'] = array();
			foreach ($apiResponse->Hotels->Hotel as $hotel) {
				$data['hotels'][] = $hotel;
			}
			$data['hotelCount'] = count($data['hotels']);
		}

		// Defaults for form prefills
		$defaults = new stdClass();
		$defaults->Booking = new stdClass();
		$defaults->Booking->ArrivalDate = $now;
		$defaults->Booking->DepartureDate = $afterFiveDays;
		$defaults->location = 'Lagos (Nigeria)';
		$defaults->currency = '&#8358;';
		$defaults->guests = $guests;
		$data['defaults'] = $defaults;

		$data['content'] = $this->load->view('pages/home', $data, TRUE);
		$this->load->view('layouts/master', $data);
	}

	/**
	 * Search hotels based on user input
	 */
	public function search()
	{
		// Run validation
		if ($this->input->post()) {
			$this->form_validation->set_rules('checkIn', 'Check-in Date', 'required');
			$this->form_validation->set_rules('checkOut', 'Check-out Date', 'required');
		}

		// Get form data from POST or session
		$searchBox = $this->input->post('searchBox') ? $this->input->post('searchBox') : $this->session->userdata('searchBox');
		$checkIn = $this->input->post('checkIn') ? $this->input->post('checkIn') : $this->session->userdata('checkIn');
		$checkOut = $this->input->post('checkOut') ? $this->input->post('checkOut') : $this->session->userdata('checkOut');
		$guests = $this->input->post('guests') ? $this->input->post('guests') : ($this->session->userdata('guests') ? $this->session->userdata('guests') : '1,0,1');

		// Save to session for persistence
		if ($this->input->post('checkIn')) {
			$this->session->set_userdata(array(
				'searchBox' => $searchBox,
				'checkIn' => $checkIn,
				'checkOut' => $checkOut,
				'guests' => $guests
			));
		}

		// Parse guests
		$guestsArr = explode(',', $guests);
		$adults = isset($guestsArr[0]) ? (int)$guestsArr[0] : 1;
		$children = isset($guestsArr[1]) ? (int)$guestsArr[1] : 0;
		$rooms = isset($guestsArr[2]) ? (int)$guestsArr[2] : 1;

		// Get city codes using model
		$location = $this->city_model->get_codes_from_location($searchBox);
		$cityCode = $location['cityCode'];
		$countryCode = $location['countryCode'];

		set_time_limit(0);
		$data['title'] = "Hotel Search | MakeIFly";

		// Format dates
		$arrival = Carbon::createFromFormat('Y-m-d', $checkIn)->format('d/m/Y');
		$checkoutDate = Carbon::createFromFormat('Y-m-d', $checkOut)->format('d/m/Y');

		// Validate dates
		$arrivalCarbon = Carbon::createFromFormat('d/m/Y', $arrival);
		$checkoutCarbon = Carbon::createFromFormat('d/m/Y', $checkoutDate);

		if ($checkoutCarbon->lessThanOrEqualTo($arrivalCarbon)) {
			$checkoutDate = $arrivalCarbon->addDay()->format('d/m/Y');
		}

		// Search hotels using API library
		$apiResponse = $this->rezlive_api->findHotels(array(
			'arrivalDate' => $arrival,
			'departureDate' => $checkoutDate,
			'countryCode' => $countryCode,
			'cityCode' => $cityCode,
			'nationality' => $countryCode,
			'adults' => $adults,
			'children' => $children,
			'rooms' => $rooms,
		));

		$data['apiResponse'] = $apiResponse;
		$data['hotelCount'] = 0;
		$data['hotels'] = array();

		if ($apiResponse && isset($apiResponse->Hotels) && isset($apiResponse->Hotels->Hotel)) {
			// Collect all Hotel elements into array (SimpleXML needs foreach to get all)
			$data['hotels'] = array();
			foreach ($apiResponse->Hotels->Hotel as $hotel) {
				$data['hotels'][] = $hotel;
			}
			$data['hotelCount'] = count($data['hotels']);
		}

		// Defaults for form
		$defaults = new stdClass();
		$defaults->Booking = new stdClass();
		$defaults->Booking->ArrivalDate = $arrival;
		$defaults->Booking->DepartureDate = $checkoutDate;
		$defaults->Booking->CountryCode = $countryCode;
		$defaults->Booking->City = $cityCode;
		$defaults->location = $searchBox ? $searchBox : 'Lagos (Nigeria)';
		$defaults->currency = '&#8358;';
		$defaults->guests = $guests;
		$data['defaults'] = $defaults;

		$data['content'] = $this->load->view('pages/home', $data, TRUE);
		$this->load->view('layouts/master', $data);
	}

	/**
	 * Autocomplete endpoint for city search
	 */
	public function autocomplete()
	{
		$term = $this->input->get('term', TRUE);

		if (empty($term)) {
			echo json_encode(array());
			return;
		}

		// Use city model for search
		$cities = $this->city_model->search($term, 10);

		$result = array();
		foreach ($cities as $row) {
			$result[] = array(
				'id' => $row->id,
				'label' => $row->name . ' (' . $row->country_name . ')',
				'value' => $row->name . ' (' . $row->country_name . ')'
			);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
