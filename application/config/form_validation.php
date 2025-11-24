<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Form Validation Configuration
 * Define validation rules for different forms
 */

$config = [
	// Hotel search form validation
	'hotel_search' => [
		[
			'field' => 'searchBox',
			'label' => 'Location',
			'rules' => 'trim|max_length[200]'
		],
		[
			'field' => 'checkIn',
			'label' => 'Check-in Date',
			'rules' => 'required|regex_match[/^\d{4}-\d{2}-\d{2}$/]',
			'errors' => [
				'required' => 'Please select a check-in date.',
				'regex_match' => 'Invalid date format.'
			]
		],
		[
			'field' => 'checkOut',
			'label' => 'Check-out Date',
			'rules' => 'required|regex_match[/^\d{4}-\d{2}-\d{2}$/]',
			'errors' => [
				'required' => 'Please select a check-out date.',
				'regex_match' => 'Invalid date format.'
			]
		],
		[
			'field' => 'guests',
			'label' => 'Guests',
			'rules' => 'trim|regex_match[/^\d+,\d+,\d+$/]',
			'errors' => [
				'regex_match' => 'Invalid guest format.'
			]
		]
	],

	// Hotel details form validation
	'hotel_details' => [
		[
			'field' => 'hotelId',
			'label' => 'Hotel ID',
			'rules' => 'required|integer',
			'errors' => [
				'required' => 'Hotel ID is required.',
				'integer' => 'Invalid hotel ID.'
			]
		],
		[
			'field' => 'arrival',
			'label' => 'Arrival Date',
			'rules' => 'required|regex_match[/^\d{4}-\d{2}-\d{2}$/]'
		],
		[
			'field' => 'departure',
			'label' => 'Departure Date',
			'rules' => 'required|regex_match[/^\d{4}-\d{2}-\d{2}$/]'
		]
	],

	// Booking form validation
	'booking' => [
		[
			'field' => 'searchSessionId',
			'label' => 'Session ID',
			'rules' => 'trim|alpha_numeric'
		],
		[
			'field' => 'hotelId',
			'label' => 'Hotel ID',
			'rules' => 'required|integer'
		],
		[
			'field' => 'arrivalDate',
			'label' => 'Arrival Date',
			'rules' => 'required|regex_match[/^\d{4}-\d{2}-\d{2}$/]'
		],
		[
			'field' => 'departureDate',
			'label' => 'Departure Date',
			'rules' => 'required|regex_match[/^\d{4}-\d{2}-\d{2}$/]'
		],
		[
			'field' => 'totalRate',
			'label' => 'Total Rate',
			'rules' => 'required|numeric|greater_than[0]'
		],
		[
			'field' => 'currency',
			'label' => 'Currency',
			'rules' => 'required|alpha|exact_length[3]'
		],
		[
			'field' => 'adults',
			'label' => 'Adults',
			'rules' => 'required|integer|greater_than[0]'
		],
		[
			'field' => 'children',
			'label' => 'Children',
			'rules' => 'required|integer|greater_than_equal_to[0]'
		],
		[
			'field' => 'totalRooms',
			'label' => 'Total Rooms',
			'rules' => 'required|integer|greater_than[0]'
		]
	],

	// Guest details form validation (for final booking)
	'guest_details' => [
		[
			'field' => 'firstName',
			'label' => 'First Name',
			'rules' => 'required|trim|min_length[2]|max_length[50]|alpha_numeric_spaces',
			'errors' => [
				'required' => 'Please enter your first name.',
				'min_length' => 'First name must be at least 2 characters.',
				'alpha_numeric_spaces' => 'First name can only contain letters, numbers and spaces.'
			]
		],
		[
			'field' => 'lastName',
			'label' => 'Last Name',
			'rules' => 'required|trim|min_length[2]|max_length[50]|alpha_numeric_spaces',
			'errors' => [
				'required' => 'Please enter your last name.',
				'min_length' => 'Last name must be at least 2 characters.'
			]
		],
		[
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'required|trim|valid_email|max_length[100]',
			'errors' => [
				'required' => 'Please enter your email address.',
				'valid_email' => 'Please enter a valid email address.'
			]
		],
		[
			'field' => 'confirmEmail',
			'label' => 'Confirm Email',
			'rules' => 'required|trim|matches[email]',
			'errors' => [
				'matches' => 'Email addresses do not match.'
			]
		],
		[
			'field' => 'phone',
			'label' => 'Phone Number',
			'rules' => 'trim|min_length[10]|max_length[20]|regex_match[/^[\d\s\+\-\(\)]+$/]',
			'errors' => [
				'regex_match' => 'Please enter a valid phone number.'
			]
		]
	]
];
