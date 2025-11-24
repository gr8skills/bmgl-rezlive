<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
		set_time_limit(0);

		// Get booking data from POST or session using model
		$bookingData = $this->booking_model->get_booking_data();

		// Validate required data
		if (empty($bookingData['searchSessionId']) && empty($this->input->post('searchSessionId'))) {
			// Try to get from session if not in POST
			$bookingData = $this->booking_model->load_from_session();
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

		// Format dates for API
		$arrivalDateFormatted = Carbon::createFromFormat('Y-m-d', $arrivalDate)->format('d/m/Y');
		$departureDateFormatted = Carbon::createFromFormat('Y-m-d', $departureDate)->format('d/m/Y');

		// Calculate room rates
		$rates = $this->booking_model->calculate_room_rates($totalRate, $totalRooms);

		$data['title'] = 'Booking: ' . $hotelName;

		// Get city info for display
		$city = $this->city_model->get_by_code($hotelId);
		$cityName = $city ? $city->name : 'Lagos';
		if ($city) {
			$cityCode = $city->city_code;
			$countryCode = $city->country_code;
		} else {
			$cityCode = DEFAULT_CITY_CODE;
			$countryCode = DEFAULT_COUNTRY_CODE;
		}

		// Call PreBook API
		$apiResponse = $this->rezlive_api->preBook([
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
		]);

		$data['apiResponse'] = $apiResponse;

		// Default values for view
		$data['default'] = (object)[
			'countryCode' => $countryCode,
			'cityCode' => $cityCode,
			'cityName' => $cityName,
			'hotelId' => $hotelId,
			'hotelName' => $hotelName,
			'arrivalDate' => $arrivalDateFormatted,
			'departureDate' => $departureDateFormatted,
			'totalRate' => $totalRate,
			'currency' => $currency,
			'roomType' => $roomType,
			'boardBasis' => $boardBasis,
			'bookingKey' => $bookingKey,
			'adults' => $adults,
			'children' => $children,
			'totalRooms' => $totalRooms,
		];

		// Get hotel details (cached)
		$hotelDetails = $this->rezlive_api->getHotelDetails($hotelId);
		$data['hotelDetails'] = $hotelDetails ? $hotelDetails->Hotels : null;

		// Get exchange rate for currency conversion
		$data['exchangeRate'] = 1;
		if ($currency === 'USD' && $countryCode === 'NG') {
			$data['exchangeRate'] = $this->rezlive_api->getExchangeRate();
		}

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

		// Get guest details
		$guestData = [
			'firstName' => $this->input->post('firstName', TRUE),
			'lastName' => $this->input->post('lastName', TRUE),
			'email' => $this->input->post('email', TRUE),
			'phone' => $this->input->post('phone', TRUE),
		];

		// TODO: Implement actual booking API call
		// $bookingResponse = $this->rezlive_api->confirmBooking($guestData);

		// For now, just show a success message
		$this->session->set_flashdata('success', 'Booking request submitted successfully!');
		redirect('booking/success');
	}

	/**
	 * Booking success page
	 */
	public function success()
	{
		$data['title'] = 'Booking Confirmed | MakeIFly';
		$data['content'] = $this->load->view('pages/booking_success', $data, TRUE);
		$this->load->view('layouts/master', $data);
	}
}
