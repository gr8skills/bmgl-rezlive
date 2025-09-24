<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Hotel extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function testSession() {
		var_dump($this->session->all_userdata());
	}

	public function index() {
		set_time_limit(0);
		// Hotel form data
		$hotelId = $this->input->post('hotelId');
		$hotelName = $this->input->post('hotelName');
		$arrival   = $this->input->post('arrival');
		$departure  = $this->input->post('departure');
		$guests  = $this->input->post('guestData');

		// cache the form data
		if (!is_null($hotelId) && !is_null($arrival) && !is_null($departure)) {
			$this->session->set_userdata('hotelId', $hotelId);
			$this->session->set_userdata('hotelName', $hotelName);
			$this->session->set_userdata('arrival', $arrival);
			$this->session->set_userdata('departure', $departure);
			$this->session->set_userdata('guestData', $guests);
		} else {
			$hotelId = $this->session->userdata('hotelId');
			$hotelName = $this->session->userdata('hotelName');
			$arrival = $this->session->userdata('arrival');
			$departure = $this->session->userdata('departure');
			$guests = $this->session->userdata('guestData');
		}

		if (is_null($guests)) {
			$guests = '1,0,1'; // default to 1 adult, 0 children, 1 room
		}

		// var_dump($this->session->all_userdata());

		$data['title'] = $hotelName??'Hotel'." | MakeIFly - Flight booking and Hotel Reservation";

		$now = Carbon::createFromFormat('Y-m-d', $arrival)->format('d/m/Y');
		$afterOneMonth = Carbon::createFromFormat('Y-m-d', $departure)->format('d/m/Y');

		$this->db->select('name, country_code, city_code');
		$this->db->from('cities');
		$this->db->where('city_code', $hotelId);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$row = $query->row();
			$cityCode = $row->city_code;
			$countryCode = $row->country_code;
		} else {
			$cityCode = '31606'; // Default to Lagos if not found
			$countryCode = 'NG';
		}

		// Split guests string into parts
		$guestsArr = explode(',', $guests);

		$adults   = isset($guestsArr[0]) ? (int)$guestsArr[0] : 0;
		$children = isset($guestsArr[1]) ? (int)$guestsArr[1] : 0;
		$rooms    = isset($guestsArr[2]) ? (int)$guestsArr[2] : 0;

		$childrenAges = '';
		if ((int)$children > 0) {
			for ($i = 0; $i < $children; $i++) {
				$age = $i+1;
				$childrenAges .= "<ChildrenAges><ChildAge>{$age}</ChildAge></ChildrenAges>"; // Default age 2 for each child
			}
		}

		$SearchSessionId = $CountryCode = $hotelCurrency = null;

		// XML request string
		$xmlString = "<HotelFindRequest>
    <Authentication>
        <AgentCode>CD33604</AgentCode>
        <UserName>GOFLY1</UserName>
    </Authentication>
    <Booking>
        <ArrivalDate>{$now}</ArrivalDate>
        <DepartureDate>{$afterOneMonth}</DepartureDate>
        <CountryCode>{$countryCode}</CountryCode>
        <City>{$cityCode}</City>
        <GuestNationality>NG</GuestNationality>
        <HotelRatings>
            <HotelRating>1</HotelRating>
            <HotelRating>2</HotelRating>
            <HotelRating>3</HotelRating>
            <HotelRating>4</HotelRating>
            <HotelRating>5</HotelRating>
        </HotelRatings>
        <Rooms>
            <Room>
            	<Type>Room-{$rooms}</Type>
                <NoOfAdults>{$adults}</NoOfAdults>
                <NoOfChilds>{$children}</NoOfChilds>
                {$childrenAges}
            </Room>
        </Rooms>
        <HotelIDs>
            <Int>{$hotelId}</Int>
        </HotelIDs>
    </Booking>
</HotelFindRequest>";

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
		$url = API_URL . 'findhotelbyid';
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

			// Count hotels
			$hotelCount = isset($data['apiResponse']->Hotels->Hotel) ? count($data['apiResponse']->Hotels->Hotel) : 0;

			$data['hotelCount'] = $hotelCount;

			$data['hotels'] = $data['apiResponse']->Hotels->Hotel ?? [];
			$hotelwiseroomcount = 0;
			$roomDetails = [];
			if ($data['hotelCount'] > 0) {
				//pick the first hotel
				$firstHotel = $data['hotels'][0];
				$hotelwiseroomcount = $firstHotel->Hotelwiseroomcount;
				$roomDetails = $firstHotel->RoomDetails->RoomDetail ?? [];
			}
			$data['hotelwiseroomcount'] = $hotelwiseroomcount;
			$data['roomDetails'] = $roomDetails;

			$SearchSessionId = $data['apiResponse']->SearchSessionId;
			$CountryCode = $data['apiResponse']->CountryCode ?? 'NG' ;
			$hotelCurrency = $data['apiResponse']->Currency ?? '&#8358;';

		}
		curl_close($ch);

		// Parse defaults (from request, for form prefills)
		$data['defaults'] = simplexml_load_string($xmlString);
		$data['defaults']->hotelName = $hotelName;
		$data['defaults']->currency = $hotelCurrency;
		$data['defaults']->guests = $guests;
		$data['defaults']->arrival = $arrival;
		$data['defaults']->departure = $departure;
		$data['defaults']->rooms = $rooms;
		$data['defaults']->adult = $adults;
		$data['defaults']->children = $children;
		$data['defaults']->searchSessionId = $SearchSessionId;
		$data['defaults']->countryCode = $CountryCode;
		$data['defaults']->hotelCurrency = $hotelCurrency;


		$hotelDetails = $this->getHotelDetails($hotelId);
		$data['hotelDetails'] = $hotelDetails->Hotels;


		// Render view
		$data['content'] = $this->load->view('pages/hotel', $data, TRUE);
		$this->load->view('layouts/master', $data);
	}

	public function search() {
		$searchBox = $this->input->post('searchBox');
		$checkIn   = $this->input->post('checkIn');
		$checkOut  = $this->input->post('checkOut');
		$guests    = $this->input->post('guests');

		if (!is_null($checkIn) && !is_null($checkOut)) {
			$this->session->set_userdata('searchBox', $searchBox);
			$this->session->set_userdata('checkIn', $checkIn);
			$this->session->set_userdata('checkOut', $checkOut);
			$this->session->set_userdata('guests', $guests);
		} else {
			$searchBox = $this->session->userdata('searchBox');
			$checkIn = $this->session->userdata('checkIn');
			$checkOut = $this->session->userdata('checkOut');
			$guests = $this->session->userdata('guests');
		}

		// Split guests string into parts
		$guestsArr = explode(',', $guests);

		$adults   = isset($guestsArr[0]) ? (int)$guestsArr[0] : 0;
		$children = isset($guestsArr[1]) ? (int)$guestsArr[1] : 0;
		$rooms    = isset($guestsArr[2]) ? (int)$guestsArr[2] : 0;

		if ($searchBox) {
			// Extract city name from "City Name (Country Name)" format
			if (preg_match('/^(.*?)\s*\((.*?)\)$/', $searchBox, $matches)) {
				$cityName = trim($matches[1]);
				$countryName = trim($matches[2]);

				// Query database for city code
				$this->db->select('city_code, country_code');
				$this->db->from('cities');
				$this->db->where('name', $cityName);
				$this->db->where('country_name', $countryName);
				$query = $this->db->get();

				if ($query->num_rows() > 0) {
					$row = $query->row();
					$cityCode = $row->city_code;
					$countryCode = $row->country_code;
				} else {
					$cityCode = '31606'; // Default to Lagos if not found
					$countryCode = 'NG';
				}
			} else {
				$cityCode = '31606'; // Default to Lagos if format is incorrect
				$countryCode = 'NG';
			}
		} else {
			$cityCode = '31606'; // Default to Lagos if empty
			$countryCode = 'NG';
		}


		set_time_limit(0);
		$data['title'] = "Hotel | MakeIFly - Flight booking and Hotel Reservation";

		$arrival = Carbon::createFromFormat('Y-m-d', $checkIn)->format('d/m/Y');
		$checkoutDate = Carbon::createFromFormat('Y-m-d', $checkOut)->format('d/m/Y');

		var_dump(['arrival'=>$arrival, 'checkOutDate'=>$checkoutDate]);

		// check if checkout date is greater than arrival date
		if (Carbon::createFromFormat('d/m/Y', $checkoutDate)->equalTo(Carbon::createFromFormat('d/m/Y', $arrival))) {
			$checkoutDate = Carbon::createFromFormat('d/m/Y', $arrival)->addDay()->format('d/m/Y');
		} elseif (Carbon::createFromFormat('d/m/Y', $checkoutDate)->lessThan(Carbon::createFromFormat('d/m/Y', $arrival))) {
			$arrival = Carbon::now()->format('d/m/Y');
			$checkoutDate = Carbon::now()->addDay()->format('d/m/Y');
		}


		// XML request string
		$xmlString = "<HotelFindRequest>
    <Authentication>    
        <AgentCode>CD33604</AgentCode>
        <UserName>GOFLY1</UserName>
    </Authentication>
    <Booking>
        <ArrivalDate>{$arrival}</ArrivalDate>
        <DepartureDate>{$checkoutDate}</DepartureDate>
        <CountryCode>{$countryCode}</CountryCode>
        <City>{$cityCode}</City>
        <GuestNationality>NG</GuestNationality>
        <HotelRatings>
            <HotelRating>1</HotelRating>
            <HotelRating>2</HotelRating>
            <HotelRating>3</HotelRating>
            <HotelRating>4</HotelRating>
            <HotelRating>5</HotelRating>
        </HotelRatings>
        <Rooms>
            <Room>
                <Type>Room-{$rooms}</Type>
                <NoOfAdults>{$adults}</NoOfAdults>
                <NoOfChilds>{$children}</NoOfChilds>
            </Room>
        </Rooms>
    </Booking>
</HotelFindRequest>";

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
		$url = API_URL . 'findhotel';
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
			// Count hotels
			$hotelCount = isset($data['apiResponse']->Hotels->Hotel) ? count($data['apiResponse']->Hotels->Hotel) : 0;

			$data['hotelCount'] = $hotelCount;

			$data['hotels'] = $data['apiResponse']->Hotels->Hotel ?? [];

		}
		curl_close($ch);

		// Parse defaults (from request, for form prefills)
		$data['defaults'] = simplexml_load_string($xmlString);
		$data['defaults']->location = $searchBox ?? 'Lagos (Nigeria)';
		$data['defaults']->currency = '&#8358;';
		$data['defaults']->guests = $guests;

		// Render view
		$data['content'] = $this->load->view('pages/home', $data, TRUE);
		$this->load->view('layouts/master', $data);
	}

	public function autocomplete()
	{
		$term = $this->input->get('term', TRUE);

		$this->db->like('name', $term);
		$this->db->or_like('city_code', $term);
		$this->db->or_like('country_code', $term);
		$this->db->order_by('name', 'ASC')->limit(10);
		$query = $this->db->get('cities');

		$result = [];
		foreach ($query->result() as $row) {
			$result[] = [
				'id'    => $row->id,
				'label' => $row->name . ' (' . $row->country_name . ')',
				'value' => $row->name . ' (' . $row->country_name . ')'
			];
		}

		echo json_encode($result);
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
