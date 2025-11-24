<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Booking Model
 * Handles booking-related session data management
 */
class Booking_model extends CI_Model
{
	// Session keys for booking data
	private $sessionKeys = [
		'searchSessionId', 'arrivalDate', 'departureDate', 'countryCode',
		'cityCode', 'hotelId', 'hotelName', 'totalRate', 'currency',
		'roomType', 'boardBasis', 'bookingKey', 'adults', 'children', 'totalRooms'
	];

	/**
	 * Save booking data to session
	 * @param array $data Booking data array
	 */
	public function save_to_session($data)
	{
		foreach ($this->sessionKeys as $key) {
			if (isset($data[$key])) {
				$this->session->set_userdata($key, $data[$key]);
			}
		}
	}

	/**
	 * Load booking data from session
	 * @return array Booking data array
	 */
	public function load_from_session()
	{
		$data = [];
		foreach ($this->sessionKeys as $key) {
			$data[$key] = $this->session->userdata($key);
		}
		return $data;
	}

	/**
	 * Get booking data from POST or session
	 * @return array Booking data
	 */
	public function get_booking_data()
	{
		$searchSessionId = $this->input->post('searchSessionId');

		// If POST data exists, save to session and return
		if (!is_null($searchSessionId)) {
			$data = [];
			foreach ($this->sessionKeys as $key) {
				$data[$key] = $this->input->post($key);
			}
			$this->save_to_session($data);
			return $data;
		}

		// Otherwise load from session
		return $this->load_from_session();
	}

	/**
	 * Clear booking session data
	 */
	public function clear_session()
	{
		$this->session->unset_userdata($this->sessionKeys);
	}

	/**
	 * Generate children ages XML based on number of children
	 * @param int $children Number of children
	 * @return string XML string for children ages
	 */
	public function generate_children_ages_xml($children)
	{
		$children = (int)$children;
		if ($children <= 0) {
			return "<ChildrenAges></ChildrenAges>";
		}

		// Generate random ages between 1-9 in format "2*3*4"
		$ages = implode('*', array_map(function() {
			return rand(1, 9);
		}, range(1, $children)));

		return "<ChildrenAges>{$ages}</ChildrenAges>";
	}

	/**
	 * Calculate room rates string
	 * @param float $totalRate Total rate
	 * @param int $totalRooms Number of rooms
	 * @return string Rates in format "172.48|172.48"
	 */
	public function calculate_room_rates($totalRate, $totalRooms)
	{
		$totalRooms = max(1, (int)$totalRooms);
		$ratePerRoom = $totalRate / $totalRooms;
		return implode('|', array_fill(0, $totalRooms, number_format($ratePerRoom, 7, '.', '')));
	}
}
