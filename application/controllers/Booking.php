<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Booking extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function index()
	{
		set_time_limit(0);
		// get the request inputs
		$searchSessionId = $this->input->post('searchSessionId');
		$arrivalDate = $this->input->post('arrivalDate');
		$departureDate = $this->input->post('departureDate');
		$countryCode = $this->input->post('countryCode');
		$cityCode = $this->input->post('cityCode');
		$hotelId = $this->input->post('hotelId');
		$hotelName = $this->input->post('hotelName');
		$totalRate = $this->input->post('totalRate');
		$currency = $this->input->post('currency');
		$roomType = $this->input->post('roomType');
		$boardBasis = $this->input->post('boardBasis');
		$bookingKey = $this->input->post('bookingKey');
		$adults = $this->input->post('adults');
		$children = $this->input->post('children');
		$totalRooms = $this->input->post('totalRooms');

		if (!is_null($searchSessionId)) {
			$this->session->set_userdata('searchSessionId', $searchSessionId);
			$this->session->set_userdata('arrivalDate', $arrivalDate);
			$this->session->set_userdata('departureDate', $departureDate);
			$this->session->set_userdata('countryCode', $countryCode);
			$this->session->set_userdata('cityCode', $cityCode);
			$this->session->set_userdata('hotelId', $hotelId);
			$this->session->set_userdata('hotelName', $hotelName);
			$this->session->set_userdata('totalRate', $totalRate);
			$this->session->set_userdata('currency', $currency);
			$this->session->set_userdata('roomType', $roomType);
			$this->session->set_userdata('boardBasis', $boardBasis);
			$this->session->set_userdata('bookingKey', $bookingKey);
			$this->session->set_userdata('adults', $adults);
			$this->session->set_userdata('children', $children);
			$this->session->set_userdata('totalRooms', $totalRooms);
		} else {
			$searchSessionId = $this->session->userdata('searchSessionId');
			$arrivalDate = $this->session->userdata('arrivalDate');
			$departureDate = $this->session->userdata('departureDate');
			$countryCode = $this->session->userdata('countryCode');
			$cityCode = $this->session->userdata('cityCode');
			$hotelId = $this->session->userdata('hotelId');
			$hotelName = $this->session->userdata('hotelName');
			$totalRate = $this->session->userdata('totalRate');
			$currency = $this->session->userdata('currency');
			$roomType = $this->session->userdata('roomType');
			$boardBasis = $this->session->userdata('boardBasis');
			$bookingKey = $this->session->userdata('bookingKey');
			$adults = $this->session->userdata('adults');
			$children = $this->session->userdata('children');
			$totalRooms = $this->session->userdata('totalRooms');
		}

		if ((int)$children > 0) {
			// ages = random number between 1 to 9 in this format: "2*3*4"
			$ages = implode('*', array_map(function() {
				return rand(1, 9);
			}, range(1, (int)$children)));
			$childrenAges = "<ChildrenAges>{$ages}</ChildrenAges>";
		} else{
			$childrenAges = "<ChildrenAges></ChildrenAges>";
		}
		$arrivalDate = Carbon::createFromFormat('Y-m-d', $arrivalDate)->format('d/m/Y');
		$departureDate = Carbon::createFromFormat('Y-m-d', $departureDate)->format('d/m/Y');

		// rates = totalRate/totalRooms in this format: 172.4848125|172.4848125
		$ratePerRoom = $totalRate / $totalRooms;
		$rates = implode('|', array_fill(0, $totalRooms, number_format($ratePerRoom, 7, '.', '')));

		$data['title'] = 'Booking: '. $hotelName;

		$this->db->select('name, country_code, city_code');
		$this->db->from('cities');
		$this->db->where('city_code', $hotelId);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$row = $query->row();
			$cityCode = $row->city_code;
			$cityName = $row->name;
			$countryCode = $row->country_code;
		} else {
			$cityCode = '31606'; // Default to Lagos if not found
			$countryCode = 'NG';
			$cityName = 'Lagos';
		}


		$xmlString = "<PreBookingRequest>
    <Authentication>
        <AgentCode>CD33604</AgentCode>
        <UserName>GOFLY1</UserName>
    </Authentication>
    <PreBooking>
        <SearchSessionId>{$searchSessionId}</SearchSessionId>
        <ArrivalDate>{$arrivalDate}</ArrivalDate>
        <DepartureDate>{$departureDate}</DepartureDate>
        <GuestNationality>{$countryCode}</GuestNationality>
        <CountryCode>{$countryCode}</CountryCode>
        <City>{$cityCode}</City>
        <HotelId>{$hotelId}</HotelId>
        <Price>{$totalRate}</Price>
        <Currency>{$currency}</Currency>
        <RoomDetails>
            <RoomDetail>
                <Type>{$roomType}</Type>
                <BoardBasis>{$boardBasis}</BoardBasis>
                <BookingKey>{$bookingKey}</BookingKey>
                <Adults>{$adults}</Adults>
                <Children>{$children}</Children>
                $childrenAges
                <TotalRooms>{$totalRooms}</TotalRooms>
                <TotalRate>{$rates}</TotalRate>
            </RoomDetail>
        </RoomDetails>
    </PreBooking>
</PreBookingRequest>";

		// Ensure application/xml directory exists
		$dir = APPPATH . 'xml/';
		if (!is_dir($dir)) {
			if (!mkdir($dir, 0775, true)) {
				die('❌ Failed to create directories...');
			}
		}

		// Save request to file
		$filePath = $dir . 'request' . time() . '.xml';
		file_put_contents($filePath, $xmlString);

		// Send request via cURL
		$url = API_URL . 'prebook?XML';
		$headers = array('x-api-key: 20f3fdffd79b56d060f941fa4f0a9bda');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "XML=" . urlencode($xmlString));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if ($result === false) {
			log_message('error', 'Curl error: ' . curl_error($ch));
			$data['apiResponse'] = null;
		} else {
			// Try to decode response if gzipped
			$decoded = @gzdecode($result);
			$xmlResponse = $decoded !== false ? $decoded : $result;

			// Save response XML (optional)
			$responsePath = $dir . 'response' . time() . '.xml';
			file_put_contents($responsePath, $xmlResponse);

			// Parse API response
			$xmlResponse = trim($xmlResponse);

			// Remove BOM if present
			$xmlResponse = preg_replace('/^\xEF\xBB\xBF/', '', $xmlResponse);
			libxml_use_internal_errors(true);

			$data['apiResponse'] = simplexml_load_string($xmlResponse);
			if ($data['apiResponse'] === false) {
				log_message('error', 'Failed to parse API response XML.');
			}

		}
		curl_close($ch);

		$data['default'] = (object)[
			'countryCode' => $countryCode,
			'cityCode' => $cityCode,
			'cityName' => $cityName,
			'hotelId' => $hotelId,
			'hotelName' => $hotelName,
			'arrivalDate' => $arrivalDate,
			'departureDate' => $departureDate,
			'totalRate' => $totalRate,
			'currency' => $currency,
			'roomType' => $roomType,
			'boardBasis' => $boardBasis,
			'bookingKey' => $bookingKey,
			'adults' => $adults,
			'children' => $children,
			'totalRooms' => $totalRooms,
		];

		$hotelDetails = $this->getHotelDetails($hotelId);
		$data['hotelDetails'] = $hotelDetails ? $hotelDetails->Hotels : null;


		// Render view
		$data['content'] = $this->load->view('pages/booking', $data, TRUE);
		$this->load->view('layouts/master', $data);
	}

	public function getHotelDetails($hotelId)
	{
		$xmlString = "<HotelDetailsRequest>
    <Authentication>
        <AgentCode>CD33604</AgentCode>
        <UserName>GOFLY1</UserName>
    </Authentication>
    <Hotels>
        <HotelId>{$hotelId}</HotelId>
    </Hotels>
</HotelDetailsRequest>";

		// Ensure application/xml directory exists
		$dir = APPPATH . 'xml/';
		if (!is_dir($dir)) {
			if (!mkdir($dir, 0775, true)) {
				die('❌ Failed to create directories...');
			}
		}

		// Save request to file
		$filePath = $dir . 'request' . time() . '.xml';
		file_put_contents($filePath, $xmlString);

		// Send request via cURL
		$url = API_URL . 'gethoteldetails';
		$headers = array('x-api-key: 20f3fdffd79b56d060f941fa4f0a9bda');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "XML=" . urlencode($xmlString));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if ($result === false) {
			log_message('error', 'Curl error: ' . curl_error($ch));
			$data['apiResponse'] = null;
		} else {
			// Try to decode response if gzipped
			$decoded = @gzdecode($result);
			$xmlResponse = $decoded !== false ? $decoded : $result;

			// Save response XML (optional)
			$responsePath = $dir . 'response' . time() . '.xml';
			file_put_contents($responsePath, $xmlResponse);

			// Parse API response
			$data['apiResponse'] = simplexml_load_string($xmlResponse);
			if ($data['apiResponse'] === false) {
				log_message('error', 'Failed to parse API response XML.');
			}

		}
		curl_close($ch);

		return $data['apiResponse'];
	}
}
