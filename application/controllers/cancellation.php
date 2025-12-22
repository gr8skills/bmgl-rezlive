<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cancellation extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Display cancellation lookup form
	 */
	public function index()
	{
		$data['title'] = 'Cancel Booking';
		$data['content'] = $this->load->view('pages/cancellation_lookup', $data, TRUE);
		$this->load->view('layouts/master', $data);
	}

	/**
	 * Look up booking and display cancellation policy
	 */
	public function policy()
	{
		// PRG Pattern: If POST request, save to session and redirect to GET
		if ($this->input->post('bookingId')) {
			$bookingId = trim($this->input->post('bookingId', TRUE));
			$bookingCode = trim($this->input->post('bookingCode', TRUE));

			// Basic validation
			if (empty($bookingId) || empty($bookingCode)) {
				$this->session->set_flashdata('error', 'Please enter both Booking ID and Booking Reference.');
				redirect('cancellation');
				return;
			}

			// Store in session for PRG
			$this->session->set_userdata('cancel_booking_id', $bookingId);
			$this->session->set_userdata('cancel_booking_code', $bookingCode);
			redirect('cancellation/policy');
			return;
		}

		// GET request - retrieve from session
		$bookingId = $this->session->userdata('cancel_booking_id');
		$bookingCode = $this->session->userdata('cancel_booking_code');

		if (empty($bookingId) || empty($bookingCode)) {
			$this->session->set_flashdata('error', 'Please enter your booking details.');
			redirect('cancellation');
			return;
		}

		// Fetch cancellation policy from API
		$policyResponse = $this->rezlive_api->getCancellationPolicy($bookingId, $bookingCode);

		// Log response for debugging
		log_message('debug', 'Cancellation Policy Response: ' . print_r($policyResponse, TRUE));

		$data['title'] = 'Cancellation Policy';
		$data['bookingId'] = $bookingId;
		$data['bookingCode'] = $bookingCode;
		$data['policyResponse'] = $policyResponse;

		// Check for errors in response
		if ($policyResponse === null) {
			$data['error'] = 'Unable to retrieve cancellation policy. Please try again later.';
		} elseif (isset($policyResponse->Error)) {
			$data['error'] = (string) $policyResponse->Error;
		} elseif (isset($policyResponse->error)) {
			$data['error'] = (string) $policyResponse->error;
		}

		$data['content'] = $this->load->view('pages/cancellation_policy', $data, TRUE);
		$this->load->view('layouts/master', $data);
	}

	/**
	 * Process cancellation confirmation
	 */
	public function confirm()
	{
		// Only accept POST requests
		if (!$this->input->post('confirm_cancel')) {
			redirect('cancellation');
			return;
		}

		$bookingId = $this->session->userdata('cancel_booking_id');
		$bookingCode = $this->session->userdata('cancel_booking_code');

		if (empty($bookingId) || empty($bookingCode)) {
			$this->session->set_flashdata('error', 'Session expired. Please enter your booking details again.');
			redirect('cancellation');
			return;
		}

		// Call cancel API
		$cancelResponse = $this->rezlive_api->cancelHotel($bookingId, $bookingCode);

		// Log response for debugging
		log_message('debug', 'Cancel Hotel Response: ' . print_r($cancelResponse, TRUE));

		// Check for errors
		if ($cancelResponse === null) {
			$this->session->set_flashdata('error', 'Cancellation failed. Please try again later.');
			redirect('cancellation/policy');
			return;
		}

		if (isset($cancelResponse->Error)) {
			$this->session->set_flashdata('error', 'Cancellation failed: ' . (string) $cancelResponse->Error);
			redirect('cancellation/policy');
			return;
		}

		if (isset($cancelResponse->error)) {
			$this->session->set_flashdata('error', 'Cancellation failed: ' . (string) $cancelResponse->error);
			redirect('cancellation/policy');
			return;
		}

		// Store cancellation confirmation in session
		$cancellationData = array(
			'bookingId' => $bookingId,
			'bookingCode' => $bookingCode,
			'status' => isset($cancelResponse->Status) ? (string) $cancelResponse->Status : 'Cancelled',
			'message' => isset($cancelResponse->Message) ? (string) $cancelResponse->Message : 'Your booking has been cancelled successfully.',
			'cancellationId' => isset($cancelResponse->CancellationId) ? (string) $cancelResponse->CancellationId : '',
		);
		$this->session->set_userdata('cancellation_confirmation', $cancellationData);

		// Clear cancellation session data
		$this->session->unset_userdata('cancel_booking_id');
		$this->session->unset_userdata('cancel_booking_code');

		$this->session->set_flashdata('success', 'Your booking has been cancelled successfully.');
		redirect('cancellation/success');
	}

	/**
	 * Display cancellation success page
	 */
	public function success()
	{
		$cancellation = $this->session->userdata('cancellation_confirmation');
		if (empty($cancellation)) {
			redirect('cancellation');
			return;
		}

		$data['title'] = 'Booking Cancelled';
		$data['cancellation'] = $cancellation;
		$data['content'] = $this->load->view('pages/cancellation_success', $data, TRUE);
		$this->load->view('layouts/master', $data);
	}
}

/* End of file cancellation.php */
/* Location: ./application/controllers/cancellation.php */
