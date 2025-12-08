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
	 * Detect user's location from IP address
	 * @return array Location data with city, country, cityCode, countryCode
	 */
	private function detectLocationFromIP()
	{
		// Check if we have cached location in session
		$cachedLocation = $this->session->userdata('detected_location');
		if ($cachedLocation) {
			return $cachedLocation;
		}

		// Default location
		$location = array(
			'city' => 'Lagos',
			'country' => 'Nigeria',
			'cityCode' => DEFAULT_CITY_CODE,
			'countryCode' => DEFAULT_COUNTRY_CODE,
			'locationString' => 'Lagos (Nigeria)'
		);

		try {
			// Get user's IP address
			$ip = $this->input->ip_address();

			// Skip for localhost/private IPs - use default
			if ($ip === '127.0.0.1' || $ip === '::1' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
				// For local development, try to get external IP or use default
				$ip = '';
			}

			// Use ip-api.com for geolocation (free, no API key required)
			$apiUrl = 'http://ip-api.com/json/' . $ip . '?fields=status,city,country,countryCode';

			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $apiUrl,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 5,
				CURLOPT_CONNECTTIMEOUT => 3,
			));

			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($response !== false && $httpCode === 200) {
				$geoData = json_decode($response, true);

				if (isset($geoData['status']) && $geoData['status'] === 'success' && !empty($geoData['city'])) {
					$detectedCity = $geoData['city'];
					$detectedCountry = $geoData['country'];

					// Try to find this city in our database
					$cityData = $this->city_model->get_by_name_and_country($detectedCity, $detectedCountry);

					if ($cityData) {
						$location = array(
							'city' => $cityData->name,
							'country' => $cityData->country_name,
							'cityCode' => $cityData->city_code,
							'countryCode' => $cityData->country_code,
							'locationString' => $cityData->name . ' (' . $cityData->country_name . ')'
						);
					} else {
						// City not in database, try searching by city name only
						$searchResults = $this->city_model->search($detectedCity, 1);
						if (!empty($searchResults)) {
							$cityData = $searchResults[0];
							$location = array(
								'city' => $cityData->name,
								'country' => $cityData->country_name,
								'cityCode' => $cityData->city_code,
								'countryCode' => $cityData->country_code,
								'locationString' => $cityData->name . ' (' . $cityData->country_name . ')'
							);
						}
					}
				}
			}
		} catch (Exception $e) {
			log_message('error', 'IP Geolocation error: ' . $e->getMessage());
		}

		// Cache in session for 1 hour
		$this->session->set_userdata('detected_location', $location);

		return $location;
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

		// Detect user's location from IP
		$detectedLocation = $this->detectLocationFromIP();

		// Use Rezlive API library with detected location
		$apiResponse = $this->rezlive_api->findHotels(array(
			'arrivalDate' => $now,
			'departureDate' => $afterFiveDays,
			'countryCode' => $detectedLocation['countryCode'],
			'cityCode' => $detectedLocation['cityCode'],
			'nationality' => $detectedLocation['countryCode'],
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
		$defaults->Booking->CountryCode = $detectedLocation['countryCode'];
		$defaults->Booking->City = $detectedLocation['cityCode'];
		$defaults->location = $detectedLocation['locationString'];
		$defaults->currency = '&#8358;';
		$defaults->guests = $guests;
		$data['defaults'] = $defaults;

		// Pass detected location info to view
		$data['detectedLocation'] = $detectedLocation;

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
