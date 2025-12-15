<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Include Composer autoloader for Carbon
require_once FCPATH . 'vendor/autoload.php';

use Carbon\Carbon;

class Booking extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Display booking page with pre-booking confirmation
	 */
	public function index()
	{
		// PRG Pattern: If POST request, save to session and redirect to GET
		if ($this->input->post('searchSessionId')) {
			$postData = array(
				'searchSessionId' => $this->input->post('searchSessionId'),
				'arrivalDate' => $this->input->post('arrivalDate'),
				'departureDate' => $this->input->post('departureDate'),
				'countryCode' => $this->input->post('countryCode'),
				'cityCode' => $this->input->post('cityCode'),
				'hotelId' => $this->input->post('hotelId'),
				'hotelName' => $this->input->post('hotelName'),
				'totalRate' => $this->input->post('totalRate'),
				'currency' => $this->input->post('currency'),
				'roomType' => $this->input->post('roomType'),
				'boardBasis' => $this->input->post('boardBasis'),
				'bookingKey' => $this->input->post('bookingKey'),
				'adults' => $this->input->post('adults'),
				'children' => $this->input->post('children'),
				'totalRooms' => $this->input->post('totalRooms'),
			);
			// Debug: Log POST data
			log_message('debug', 'Booking POST data: ' . print_r($postData, TRUE));

			$this->booking_model->save_to_session($postData);
			// Redirect to same page as GET request
			redirect('booking/index');
			return;
		}

		set_time_limit(0);

		// Get booking data from session (after redirect)
		$bookingData = $this->booking_model->load_from_session();

		// Debug: Log session data
		log_message('debug', 'Booking session data: ' . print_r($bookingData, TRUE));

		// Validate required data - redirect to home if no session data
		if (empty($bookingData['searchSessionId'])) {
			redirect('home');
			return;
		}

		// Extract booking data
		$searchSessionId = $bookingData['searchSessionId'];
		$arrivalDate = $bookingData['arrivalDate'];
		$departureDate = $bookingData['departureDate'];
		$countryCode = $bookingData['countryCode'];
		$cityCode = $bookingData['cityCode'];
		$hotelId = $bookingData['hotelId'];
		$hotelName = $bookingData['hotelName'];
		$totalRate = $bookingData['totalRate'];
		$currency = $bookingData['currency'];
		$roomType = $bookingData['roomType'];
		$boardBasis = $bookingData['boardBasis'];
		$bookingKey = $bookingData['bookingKey'];
		$adults = $bookingData['adults'];
		$children = $bookingData['children'];
		$totalRooms = $bookingData['totalRooms'];

		// Generate children ages XML
		$childrenAges = $this->booking_model->generate_children_ages_xml($children);

		// Format dates for API - handle both Y-m-d and d/m/Y formats
		try {
			$arrivalCarbon = Carbon::createFromFormat('Y-m-d', $arrivalDate);
		} catch (Exception $e) {
			try {
				$arrivalCarbon = Carbon::createFromFormat('d/m/Y', $arrivalDate);
			} catch (Exception $e) {
				$arrivalCarbon = Carbon::now();
			}
		}
		$arrivalDateFormatted = $arrivalCarbon->format('d/m/Y');

		try {
			$departureCarbon = Carbon::createFromFormat('Y-m-d', $departureDate);
		} catch (Exception $e) {
			try {
				$departureCarbon = Carbon::createFromFormat('d/m/Y', $departureDate);
			} catch (Exception $e) {
				$departureCarbon = Carbon::now()->addDay();
			}
		}
		$departureDateFormatted = $departureCarbon->format('d/m/Y');

		// Calculate room rates
		$rates = $this->booking_model->calculate_room_rates($totalRate, $totalRooms);

		$data['title'] = 'Booking: ' . $hotelName;

		// Get city name for display (use cityCode from session, don't overwrite it)
		$city = $this->city_model->get_by_code($cityCode);
		$cityName = $city ? $city->name : 'Lagos';

		// Call PreBook API
		try {
			$apiResponse = $this->rezlive_api->preBook(array(
				'searchSessionId' => $searchSessionId,
				'arrivalDate' => $arrivalDateFormatted,
				'departureDate' => $departureDateFormatted,
				'countryCode' => $countryCode,
				'cityCode' => $cityCode,
				'hotelId' => $hotelId,
				'totalRate' => $totalRate,
				'currency' => $currency,
				'roomType' => $roomType,
				'boardBasis' => $boardBasis,
				'bookingKey' => $bookingKey,
				'adults' => $adults,
				'children' => $children,
				'totalRooms' => $totalRooms,
				'rates' => $rates,
				'childrenAges' => $childrenAges,
			));
		} catch (Exception $e) {
			log_message('error', 'PreBook API Exception: ' . $e->getMessage());
			$apiResponse = null;
			$data['apiError'] = $e->getMessage();
		}

		$data['apiResponse'] = $apiResponse;

		// Default values for view
		$default = new stdClass();
		$default->countryCode = $countryCode;
		$default->cityCode = $cityCode;
		$default->cityName = $cityName;
		$default->hotelId = $hotelId;
		$default->hotelName = $hotelName;
		$default->arrivalDate = $arrivalDateFormatted;
		$default->departureDate = $departureDateFormatted;
		$default->totalRate = $totalRate;
		$default->currency = $currency;
		$default->roomType = $roomType;
		$default->boardBasis = $boardBasis;
		$default->bookingKey = $bookingKey;
		$default->adults = $adults;
		$default->children = $children;
		$default->totalRooms = $totalRooms;
		$data['default'] = $default;

		// Get hotel details (cached)
		$hotelDetails = $this->rezlive_api->getHotelDetails($hotelId);
		$data['hotelDetails'] = $hotelDetails ? $hotelDetails->Hotels : null;

		// Get exchange rate for currency conversion
		$data['exchangeRate'] = 1;
		if ($currency === 'USD' && $countryCode === 'NG') {
			$data['exchangeRate'] = $this->rezlive_api->getExchangeRate();
		}

		// log the booking data for debugging
		log_message('debug', 'Booking Data: ' . print_r($bookingData, TRUE));

		$data['content'] = $this->load->view('pages/booking', $data, TRUE);
		$this->load->view('layouts/master', $data);
	}

	/**
	 * Process final booking submission
	 */
	public function confirm()
	{
		// Run validation
		if (!$this->form_validation->run('guest_details')) {
			// Validation failed, show errors
			$this->session->set_flashdata('error', validation_errors());
			redirect('booking');
			return;
		}

		// Get booking data from session
		$bookingData = $this->booking_model->load_from_session();

		// Validate required booking data
		if (empty($bookingData['searchSessionId'])) {
			$this->session->set_flashdata('error', 'Session expired. Please start a new search.');
			redirect('home');
			return;
		}

		// Extract booking data
		$searchSessionId = $bookingData['searchSessionId'];
		$arrivalDate = $bookingData['arrivalDate'];
		$departureDate = $bookingData['departureDate'];
		$countryCode = $bookingData['countryCode'];
		$cityCode = $bookingData['cityCode'];
		$hotelId = $bookingData['hotelId'];
		$hotelName = $bookingData['hotelName'];
		$totalRate = $bookingData['totalRate'];
		$currency = $bookingData['currency'];
		$roomType = $bookingData['roomType'];
		$boardBasis = $bookingData['boardBasis'];
		$bookingKey = $bookingData['bookingKey'];
		$adults = (int) $bookingData['adults'];
		$children = (int) $bookingData['children'];
		$totalRooms = (int) $bookingData['totalRooms'];

		// Format dates for API (d/m/Y) - handle both Y-m-d and d/m/Y formats
		try {
			$arrivalCarbon = Carbon::createFromFormat('Y-m-d', $arrivalDate);
		} catch (Exception $e) {
			try {
				$arrivalCarbon = Carbon::createFromFormat('d/m/Y', $arrivalDate);
			} catch (Exception $e) {
				$arrivalCarbon = Carbon::now();
			}
		}
		$arrivalDateFormatted = $arrivalCarbon->format('d/m/Y');

		try {
			$departureCarbon = Carbon::createFromFormat('Y-m-d', $departureDate);
		} catch (Exception $e) {
			try {
				$departureCarbon = Carbon::createFromFormat('d/m/Y', $departureDate);
			} catch (Exception $e) {
				$departureCarbon = Carbon::now()->addDay();
			}
		}
		$departureDateFormatted = $departureCarbon->format('d/m/Y');

		// Calculate room rates (pipe-separated for multiple rooms)
		$rates = $this->booking_model->calculate_room_rates($totalRate, $totalRooms);

		// Build board basis for multiple rooms (pipe-separated)
		$boardBasisMultiple = implode('|', array_fill(0, $totalRooms, $boardBasis));

		// Build children ages string (format: age1*age2 for children)
		$childrenAges = '';
		if ($children > 0) {
			// Get children ages from POST or default to 5
			$ages = array();
			for ($i = 1; $i <= $children; $i++) {
				$age = $this->input->post('child_age_' . $i, TRUE);
				$ages[] = $age ? $age : '5';
			}
			$childrenAges = implode('*', $ages);
		}

		// Build guests array for each room
		$guests = array();
		$guestIndex = 1;
		$totalGuestsPerRoom = ceil(($adults + $children) / $totalRooms);

		for ($room = 1; $room <= $totalRooms; $room++) {
			$roomGuests = array();

			// Add guests for this room
			for ($g = 0; $g < $totalGuestsPerRoom && $guestIndex <= ($adults + $children); $g++) {
				$salutation = $this->input->post('salutation_' . $guestIndex, TRUE) ?: 'Mr';
				$firstName = $this->input->post('firstName_' . $guestIndex, TRUE);
				$lastName = $this->input->post('lastName_' . $guestIndex, TRUE);

				if ($firstName && $lastName) {
					$roomGuests[] = array(
						'salutation' => $salutation,
						'firstName' => strtoupper($firstName),
						'lastName' => strtoupper($lastName),
					);
				}
				$guestIndex++;
			}

			if (!empty($roomGuests)) {
				$guests[] = $roomGuests;
			}
		}

		// If no guests collected from numbered fields, try single guest fields
		if (empty($guests)) {
			$firstName = $this->input->post('firstName', TRUE);
			$lastName = $this->input->post('lastName', TRUE);
			$salutation = $this->input->post('salutation', TRUE) ?: 'Mr';

			if ($firstName && $lastName) {
				$guests[] = array(
					array(
						'salutation' => $salutation,
						'firstName' => strtoupper($firstName),
						'lastName' => strtoupper($lastName),
					)
				);
			}
		}

		// Store contact info in session for success page
		$guestFirstName = $this->input->post('firstName', TRUE);
		$guestLastName = $this->input->post('lastName', TRUE);
		$this->session->set_userdata('booking_email', $this->input->post('email', TRUE));
		$this->session->set_userdata('booking_phone', $this->input->post('phone', TRUE));
		$this->session->set_userdata('booking_guest_name', $guestFirstName . ' ' . $guestLastName);

		// Call bookHotel API
		$bookingResponse = $this->rezlive_api->bookHotel(array(
			'searchSessionId' => $searchSessionId,
			'arrivalDate' => $arrivalDateFormatted,
			'departureDate' => $departureDateFormatted,
			'countryCode' => $countryCode,
			'cityCode' => $cityCode,
			'hotelId' => $hotelId,
			'hotelName' => $hotelName,
			'currency' => $currency,
			'roomType' => $roomType,
			'boardBasis' => $boardBasisMultiple,
			'bookingKey' => $bookingKey,
			'adults' => $adults,
			'children' => $children,
			'childrenAges' => $childrenAges,
			'totalRooms' => $totalRooms,
			'totalRate' => $totalRate,
			'guests' => $guests,
		));

		// Log response for debugging
		log_message('debug', 'Booking Response: ' . print_r($bookingResponse, TRUE));

		// Check response
		if ($bookingResponse === null) {
			$this->session->set_flashdata('error', 'Booking failed. Please try again.');
			redirect('booking');
			return;
		}

		// Check for API error
		if (isset($bookingResponse->error)) {
			$errorMessage = (string) $bookingResponse->error;
			$this->session->set_flashdata('error', 'Booking failed: ' . $errorMessage);
			redirect('booking');
			return;
		}

		// Store booking confirmation details in session
		$confirmationData = array(
			'bookingId' => isset($bookingResponse->BookingId) ? (string) $bookingResponse->BookingId : '',
			'bookingRefNo' => isset($bookingResponse->BookingRefNo) ? (string) $bookingResponse->BookingRefNo : '',
			'status' => isset($bookingResponse->Status) ? (string) $bookingResponse->Status : 'Confirmed',
			'hotelName' => $hotelName,
			'arrivalDate' => $arrivalDateFormatted,
			'departureDate' => $departureDateFormatted,
			'totalRate' => $totalRate,
			'currency' => $currency,
		);
		$this->session->set_userdata('booking_confirmation', $confirmationData);

		// Clear booking session data
		$this->booking_model->clear_session();

		$this->session->set_flashdata('success', 'Booking confirmed successfully!');
		redirect('booking/success');
	}

	/**
	 * Booking success page
	 */
	public function success()
	{
		// Check if we have confirmation data
		$confirmation = $this->session->userdata('booking_confirmation');
		if (empty($confirmation)) {
			redirect('home');
			return;
		}

		$data['title'] = 'Booking Confirmed | ' . $confirmation['hotelName'];
		$data['confirmation'] = $confirmation;
		$data['content'] = $this->load->view('pages/booking_success', $data, TRUE);
		$this->load->view('layouts/master', $data);
	}
}

/* End of file booking.php */
/* Location: ./application/controllers/booking.php */
