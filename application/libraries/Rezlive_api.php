<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Rezlive API Library
 * Centralized library for all Rezlive API interactions
 */
class Rezlive_api
{
	protected $CI;
	protected $apiUrl;
	protected $apiKey;
	protected $agentCode;
	protected $agentUsername;
	protected $timeout = 60;
	protected $connectTimeout = 30;
	protected $cacheExpiry = 3600; // 1 hour cache
	protected $xmlDir;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->apiUrl = API_URL;
		$this->apiKey = API_KEY;
		$this->agentCode = AGENT_CODE;
		$this->agentUsername = AGENT_USERNAME;
		$this->xmlDir = APPPATH . 'xml/';

		// Load cache driver
		$this->CI->load->driver('cache', ['adapter' => 'file', 'backup' => 'file']);
	}

	/**
	 * Make API request
	 * @param string $endpoint API endpoint
	 * @param string $xmlString XML request body
	 * @param bool $useCache Whether to use caching
	 * @return SimpleXMLElement|null Parsed response or null on error
	 */
	public function request($endpoint, $xmlString, $useCache = false)
	{
		// Check cache first
		$cacheKey = 'rezlive_' . md5($endpoint . $xmlString);
		if ($useCache) {
			$cached = $this->CI->cache->get($cacheKey);
			if ($cached !== false) {
				return simplexml_load_string($cached);
			}
		}

		// Save request XML for debugging
		$this->saveXml('request', $xmlString);

		// Make API call
		$url = $this->apiUrl . $endpoint;
		$headers = ['x-api-key: ' . $this->apiKey];

		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => "XML=" . urlencode($xmlString),
			CURLOPT_SSL_VERIFYPEER => false, // TODO: Enable in production
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_TIMEOUT => $this->timeout,
			CURLOPT_CONNECTTIMEOUT => $this->connectTimeout,
		]);

		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error = curl_error($ch);
		curl_close($ch);

		if ($result === false) {
			log_message('error', "Rezlive API error [{$endpoint}]: {$error}");
			return null;
		}

		// Decode if gzipped
		$decoded = @gzdecode($result);
		$xmlResponse = $decoded !== false ? $decoded : $result;

		// Clean response
		$xmlResponse = trim($xmlResponse);
		$xmlResponse = preg_replace('/^\xEF\xBB\xBF/', '', $xmlResponse);

		// Save response XML for debugging
		$this->saveXml('response', $xmlResponse);

		// Parse XML
		libxml_use_internal_errors(true);
		$parsed = simplexml_load_string($xmlResponse);

		if ($parsed === false) {
			$errors = libxml_get_errors();
			log_message('error', "Rezlive XML parse error [{$endpoint}]: " . json_encode($errors));
			libxml_clear_errors();
			return null;
		}

		// Cache successful response
		if ($useCache && $parsed !== null) {
			$this->CI->cache->save($cacheKey, $xmlResponse, $this->cacheExpiry);
		}

		return $parsed;
	}

	/**
	 * Build authentication XML block
	 * @return string Authentication XML
	 */
	public function getAuthXml()
	{
		return "<Authentication>
        <AgentCode>{$this->agentCode}</AgentCode>
        <UserName>{$this->agentUsername}</UserName>
    </Authentication>";
	}

	/**
	 * Find hotels by location
	 * @param array $params Search parameters
	 * @return SimpleXMLElement|null
	 */
	public function findHotels($params)
	{
		$defaults = [
			'arrivalDate' => date('d/m/Y'),
			'departureDate' => date('d/m/Y', strtotime('+1 day')),
			'countryCode' => DEFAULT_COUNTRY_CODE,
			'cityCode' => DEFAULT_CITY_CODE,
			'nationality' => DEFAULT_COUNTRY_CODE,
			'adults' => 1,
			'children' => 0,
			'rooms' => 1,
			'childrenAges' => '',
			'hotelIds' => null,
		];

		$params = array_merge($defaults, $params);

		$hotelIdsXml = '';
		if (!empty($params['hotelIds'])) {
			$ids = is_array($params['hotelIds']) ? $params['hotelIds'] : [$params['hotelIds']];
			$hotelIdsXml = "<HotelIDs>" . implode('', array_map(fn($id) => "<Int>{$id}</Int>", $ids)) . "</HotelIDs>";
		}

		$xmlString = "<HotelFindRequest>
    {$this->getAuthXml()}
    <Booking>
        <ArrivalDate>{$params['arrivalDate']}</ArrivalDate>
        <DepartureDate>{$params['departureDate']}</DepartureDate>
        <CountryCode>{$params['countryCode']}</CountryCode>
        <City>{$params['cityCode']}</City>
        <GuestNationality>{$params['nationality']}</GuestNationality>
        <HotelRatings>
            <HotelRating>1</HotelRating>
            <HotelRating>2</HotelRating>
            <HotelRating>3</HotelRating>
            <HotelRating>4</HotelRating>
            <HotelRating>5</HotelRating>
        </HotelRatings>
        <Rooms>
            <Room>
                <Type>Room-{$params['rooms']}</Type>
                <NoOfAdults>{$params['adults']}</NoOfAdults>
                <NoOfChilds>{$params['children']}</NoOfChilds>
                {$params['childrenAges']}
            </Room>
        </Rooms>
        {$hotelIdsXml}
    </Booking>
</HotelFindRequest>";

		$endpoint = empty($params['hotelIds']) ? 'findhotel' : 'findhotelbyid';
		return $this->request($endpoint, $xmlString);
	}

	/**
	 * Get hotel details
	 * @param int|string $hotelId Hotel ID
	 * @return SimpleXMLElement|null
	 */
	public function getHotelDetails($hotelId)
	{
		$xmlString = "<HotelDetailsRequest>
    {$this->getAuthXml()}
    <Hotels>
        <HotelId>{$hotelId}</HotelId>
    </Hotels>
</HotelDetailsRequest>";

		return $this->request('gethoteldetails', $xmlString, true);
	}

	/**
	 * Pre-book a hotel room
	 * @param array $params Pre-booking parameters
	 * @return SimpleXMLElement|null
	 */
	public function preBook($params)
	{
		$required = ['searchSessionId', 'arrivalDate', 'departureDate', 'countryCode',
			'cityCode', 'hotelId', 'totalRate', 'currency', 'roomType',
			'boardBasis', 'bookingKey', 'adults', 'children', 'totalRooms', 'rates'];

		foreach ($required as $field) {
			if (!isset($params[$field])) {
				log_message('error', "PreBook missing required field: {$field}");
				return null;
			}
		}

		$childrenAges = $params['childrenAges'] ?? '<ChildrenAges></ChildrenAges>';

		$xmlString = "<PreBookingRequest>
    {$this->getAuthXml()}
    <PreBooking>
        <SearchSessionId>{$params['searchSessionId']}</SearchSessionId>
        <ArrivalDate>{$params['arrivalDate']}</ArrivalDate>
        <DepartureDate>{$params['departureDate']}</DepartureDate>
        <GuestNationality>{$params['countryCode']}</GuestNationality>
        <CountryCode>{$params['countryCode']}</CountryCode>
        <City>{$params['cityCode']}</City>
        <HotelId>{$params['hotelId']}</HotelId>
        <Price>{$params['totalRate']}</Price>
        <Currency>{$params['currency']}</Currency>
        <RoomDetails>
            <RoomDetail>
                <Type>{$params['roomType']}</Type>
                <BoardBasis>{$params['boardBasis']}</BoardBasis>
                <BookingKey>{$params['bookingKey']}</BookingKey>
                <Adults>{$params['adults']}</Adults>
                <Children>{$params['children']}</Children>
                {$childrenAges}
                <TotalRooms>{$params['totalRooms']}</TotalRooms>
                <TotalRate>{$params['rates']}</TotalRate>
            </RoomDetail>
        </RoomDetails>
    </PreBooking>
</PreBookingRequest>";

		return $this->request('prebook', $xmlString);
	}

	/**
	 * Save XML to file for debugging
	 * @param string $type 'request' or 'response'
	 * @param string $xml XML content
	 */
	protected function saveXml($type, $xml)
	{
		if (!is_dir($this->xmlDir)) {
			@mkdir($this->xmlDir, 0775, true);
		}
		$filename = $this->xmlDir . $type . time() . '.xml';
		@file_put_contents($filename, $xml);
	}

	/**
	 * Fetch USD to NGN exchange rate
	 * @return float Exchange rate (1 if unavailable)
	 */
	public function getExchangeRate()
	{
		$cacheKey = 'exchange_rate_usd_ngn';
		$cached = $this->CI->cache->get($cacheKey);

		if ($cached !== false) {
			return (float)$cached;
		}

		try {
			$ch = curl_init();
			curl_setopt_array($ch, [
				CURLOPT_URL => 'https://open.er-api.com/v6/latest/USD',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 10,
				CURLOPT_CONNECTTIMEOUT => 5,
			]);

			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($response !== false && $httpCode === 200) {
				$data = json_decode($response, true);
				if (isset($data['rates']['NGN'])) {
					$rate = (float)$data['rates']['NGN'];
					// Cache for 6 hours
					$this->CI->cache->save($cacheKey, $rate, 21600);
					return $rate;
				}
			}
		} catch (Exception $e) {
			log_message('error', 'Exchange rate error: ' . $e->getMessage());
		}

		return 1;
	}
}
