<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Hotel extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Display hotel details and room options
	 */
	public function index()
	{
		set_time_limit(0);

		// Get hotel data from POST or session
		$hotelId = $this->input->post('hotelId') ?: $this->session->userdata('hotelId');
		$hotelName = $this->input->post('hotelName') ?: $this->session->userdata('hotelName');
		$arrival = $this->input->post('arrival') ?: $this->session->userdata('arrival');
		$departure = $this->input->post('departure') ?: $this->session->userdata('departure');
		$guests = $this->input->post('guestData') ?: $this->session->userdata('guestData') ?: '1,0,1';

		// Save to session
		if ($this->input->post('hotelId')) {
			$this->session->set_userdata([
				'hotelId' => $hotelId,
				'hotelName' => $hotelName,
				'arrival' => $arrival,
				'departure' => $departure,
				'guestData' => $guests
			]);
		}

		$data['title'] = ($hotelName ?? 'Hotel') . " | MakeIFly";

		// Format dates
		$now = Carbon::createFromFormat('Y-m-d', $arrival)->format('d/m/Y');
		$afterOneMonth = Carbon::createFromFormat('Y-m-d', $departure)->format('d/m/Y');

		// Get city info
		$city = $this->city_model->get_by_code($hotelId);
		$cityCode = $city ? $city->city_code : DEFAULT_CITY_CODE;
		$countryCode = $city ? $city->country_code : DEFAULT_COUNTRY_CODE;

		// Parse guests
		$guestsArr = explode(',', $guests);
		$adults = isset($guestsArr[0]) ? (int)$guestsArr[0] : 1;
		$children = isset($guestsArr[1]) ? (int)$guestsArr[1] : 0;
		$rooms = isset($guestsArr[2]) ? (int)$guestsArr[2] : 1;

		// Generate children ages XML
		$childrenAges = '';
		if ($children > 0) {
			for ($i = 0; $i < $children; $i++) {
				$age = $i + 1;
				$childrenAges .= "<ChildrenAges><ChildAge>{$age}</ChildAge></ChildrenAges>";
			}
		}

		// Search hotel by ID using API library
		$apiResponse = $this->rezlive_api->findHotels([
			'arrivalDate' => $now,
			'departureDate' => $afterOneMonth,
			'countryCode' => $countryCode,
			'cityCode' => $cityCode,
			'nationality' => $countryCode,
			'adults' => $adults,
			'children' => $children,
			'rooms' => $rooms,
			'childrenAges' => $childrenAges,
			'hotelIds' => [$hotelId],
		]);

		$data['apiResponse'] = $apiResponse;
		$data['hotelCount'] = 0;
		$data['hotels'] = [];
		$data['hotelwiseroomcount'] = 0;
		$data['roomDetails'] = [];

		$SearchSessionId = $hotelCurrency = null;

		if ($apiResponse) {
			$data['hotelCount'] = isset($apiResponse->Hotels->Hotel) ? count($apiResponse->Hotels->Hotel) : 0;
			$data['hotels'] = $apiResponse->Hotels->Hotel ?? [];

			if ($data['hotelCount'] > 0) {
				$firstHotel = $data['hotels'][0];
				$data['hotelwiseroomcount'] = $firstHotel->Hotelwiseroomcount ?? 0;
				$data['roomDetails'] = $firstHotel->RoomDetails->RoomDetail ?? [];
			}

			$SearchSessionId = $apiResponse->SearchSessionId ?? null;
			$hotelCurrency = $apiResponse->Currency ?? '&#8358;';
		}

		// Defaults for view
		$data['defaults'] = (object)[
			'Booking' => (object)[
				'ArrivalDate' => $now,
				'DepartureDate' => $afterOneMonth,
				'CountryCode' => $countryCode,
				'City' => $cityCode,
			],
			'hotelName' => $hotelName,
			'currency' => $hotelCurrency,
			'guests' => $guests,
			'arrival' => $arrival,
			'departure' => $departure,
			'rooms' => $rooms,
			'adult' => $adults,
			'children' => $children,
			'searchSessionId' => $SearchSessionId,
			'countryCode' => $countryCode,
			'hotelCurrency' => $hotelCurrency,
		];

		// Get hotel details (cached)
		$hotelDetails = $this->rezlive_api->getHotelDetails($hotelId);
		$data['hotelDetails'] = $hotelDetails ? $hotelDetails->Hotels : null;

		$data['content'] = $this->load->view('pages/hotel', $data, TRUE);
		$this->load->view('layouts/master', $data);
	}

	/**
	 * Search hotels (redirects to home search with different view)
	 */
	public function search()
	{
		// Get form data
		$searchBox = $this->input->post('searchBox') ?: $this->session->userdata('searchBox');
		$checkIn = $this->input->post('checkIn') ?: $this->session->userdata('checkIn');
		$checkOut = $this->input->post('checkOut') ?: $this->session->userdata('checkOut');
		$guests = $this->input->post('guests') ?: $this->session->userdata('guests') ?: '1,0,1';

		// Save to session
		if ($this->input->post('checkIn')) {
			$this->session->set_userdata([
				'searchBox' => $searchBox,
				'checkIn' => $checkIn,
				'checkOut' => $checkOut,
				'guests' => $guests
			]);
		}

		// Parse guests
		$guestsArr = explode(',', $guests);
		$adults = isset($guestsArr[0]) ? (int)$guestsArr[0] : 1;
		$children = isset($guestsArr[1]) ? (int)$guestsArr[1] : 0;
		$rooms = isset($guestsArr[2]) ? (int)$guestsArr[2] : 1;

		// Get city codes
		$location = $this->city_model->get_codes_from_location($searchBox);

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

		// Search hotels
		$apiResponse = $this->rezlive_api->findHotels([
			'arrivalDate' => $arrival,
			'departureDate' => $checkoutDate,
			'countryCode' => $location['countryCode'],
			'cityCode' => $location['cityCode'],
			'nationality' => $location['countryCode'],
			'adults' => $adults,
			'children' => $children,
			'rooms' => $rooms,
		]);

		$data['apiResponse'] = $apiResponse;
		$data['hotelCount'] = 0;
		$data['hotels'] = [];

		if ($apiResponse) {
			$data['hotelCount'] = isset($apiResponse->Hotels->Hotel) ? count($apiResponse->Hotels->Hotel) : 0;
			$data['hotels'] = $apiResponse->Hotels->Hotel ?? [];
		}

		$data['defaults'] = (object)[
			'Booking' => (object)[
				'ArrivalDate' => $arrival,
				'DepartureDate' => $checkoutDate,
			],
			'location' => $searchBox ?: 'Lagos (Nigeria)',
			'currency' => '&#8358;',
			'guests' => $guests
		];

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
			$this->output->set_content_type('application/json')->set_output(json_encode([]));
			return;
		}

		$cities = $this->city_model->search($term, 10);

		$result = [];
		foreach ($cities as $row) {
			$result[] = [
				'id' => $row->id,
				'label' => $row->name . ' (' . $row->country_name . ')',
				'value' => $row->name . ' (' . $row->country_name . ')'
			];
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}
}
