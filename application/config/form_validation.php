<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form Validation Configuration
 * Define validation rules for different forms
 */

$config = array(
	// Hotel search form validation
	'hotel_search' => array(
		array(
			'field' => 'searchBox',
			'label' => 'Location',
			'rules' => 'trim|max_length[200]'
		),
		array(
			'field' => 'checkIn',
			'label' => 'Check-in Date',
			'rules' => 'required'
		),
		array(
			'field' => 'checkOut',
			'label' => 'Check-out Date',
			'rules' => 'required'
		),
		array(
			'field' => 'guests',
			'label' => 'Guests',
			'rules' => 'trim'
		)
	),

	// Hotel details form validation
	'hotel_details' => array(
		array(
			'field' => 'hotelId',
			'label' => 'Hotel ID',
			'rules' => 'required|integer'
		),
		array(
			'field' => 'arrival',
			'label' => 'Arrival Date',
			'rules' => 'required'
		),
		array(
			'field' => 'departure',
			'label' => 'Departure Date',
			'rules' => 'required'
		)
	),

	// Booking form validation
	'booking' => array(
		array(
			'field' => 'searchSessionId',
			'label' => 'Session ID',
			'rules' => 'trim|alpha_numeric'
		),
		array(
			'field' => 'hotelId',
			'label' => 'Hotel ID',
			'rules' => 'required|integer'
		),
		array(
			'field' => 'arrivalDate',
			'label' => 'Arrival Date',
			'rules' => 'required'
		),
		array(
			'field' => 'departureDate',
			'label' => 'Departure Date',
			'rules' => 'required'
		),
		array(
			'field' => 'totalRate',
			'label' => 'Total Rate',
			'rules' => 'required|numeric|greater_than[0]'
		),
		array(
			'field' => 'currency',
			'label' => 'Currency',
			'rules' => 'required|alpha|exact_length[3]'
		),
		array(
			'field' => 'adults',
			'label' => 'Adults',
			'rules' => 'required|integer|greater_than[0]'
		),
		array(
			'field' => 'children',
			'label' => 'Children',
			'rules' => 'required|integer'
		),
		array(
			'field' => 'totalRooms',
			'label' => 'Total Rooms',
			'rules' => 'required|integer|greater_than[0]'
		)
	),

	// Guest details form validation (for final booking)
	'guest_details' => array(
		array(
			'field' => 'salutation',
			'label' => 'Title',
			'rules' => 'trim'
		),
		array(
			'field' => 'firstName',
			'label' => 'First Name',
			'rules' => 'required|trim|min_length[2]|max_length[50]'
		),
		array(
			'field' => 'lastName',
			'label' => 'Last Name',
			'rules' => 'required|trim|min_length[2]|max_length[50]'
		),
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'required|trim|valid_email|max_length[100]'
		),
		array(
			'field' => 'confirmEmail',
			'label' => 'Confirm Email',
			'rules' => 'required|trim|matches[email]'
		),
		array(
			'field' => 'phone',
			'label' => 'Phone Number',
			'rules' => 'required|trim|min_length[10]|max_length[20]'
		)
	)
);

/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */
