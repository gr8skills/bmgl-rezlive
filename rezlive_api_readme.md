# Rezlive API Library Documentation

## Overview

The `Rezlive_api` library is a CodeIgniter 2.1.4 wrapper for the Rezlive XML API, providing hotel search, booking, and management functionality.

**Location:** `application/libraries/Rezlive_api.php`

## Configuration

The library reads credentials from `application/config/constants.php`:

| Constant | Description |
|----------|-------------|
| `API_URL` | Base API URL (e.g., `http://test.xmlhub.com/testpanel.php/action/`) |
| `API_KEY` | API authentication key |
| `AGENT_CODE` | Agent code (e.g., `CD33604`) |
| `AGENT_USERNAME` | Agent username (e.g., `GOFLY1`) |

## Auto-loading

The library is auto-loaded via `application/config/autoload.php` and available as `$this->rezlive_api` in controllers.

---

## Public Methods

### Hotel Search & Details

#### `findHotels(array $params)`
Search for available hotels by location and dates.

**Parameters:**
| Name | Type | Default | Description |
|------|------|---------|-------------|
| `arrivalDate` | string | Today | Check-in date (d/m/Y) |
| `departureDate` | string | Tomorrow | Check-out date (d/m/Y) |
| `countryCode` | string | `DEFAULT_COUNTRY_CODE` | Country code |
| `cityCode` | string | `DEFAULT_CITY_CODE` | City code |
| `nationality` | string | `DEFAULT_COUNTRY_CODE` | Guest nationality |
| `adults` | int | 1 | Number of adults |
| `children` | int | 0 | Number of children |
| `rooms` | int | 1 | Number of rooms |
| `childrenAges` | string | '' | Children ages XML |
| `hotelIds` | array\|null | null | Specific hotel IDs to search |

**Returns:** `SimpleXMLElement|null`

**Example:**
```php
$hotels = $this->rezlive_api->findHotels([
    'arrivalDate' => '25/12/2024',
    'departureDate' => '28/12/2024',
    'countryCode' => 'NG',
    'cityCode' => '31606',
    'adults' => 2,
    'rooms' => 1
]);
```

---

#### `getHotelDetails(string|int $hotelId)`
Get detailed information about a specific hotel. **Results are cached for 1 hour.**

**Parameters:**
| Name | Type | Description |
|------|------|-------------|
| `$hotelId` | string\|int | Hotel ID |

**Returns:** `SimpleXMLElement|null`

**Example:**
```php
$details = $this->rezlive_api->getHotelDetails('12345');
$hotelInfo = $details->Hotels;
```

---

### Booking Flow

#### `preBook(array $params)`
Validate room availability before final booking.

**Required Parameters:**
| Name | Type | Description |
|------|------|-------------|
| `searchSessionId` | string | Session ID from search |
| `arrivalDate` | string | Check-in date (d/m/Y) |
| `departureDate` | string | Check-out date (d/m/Y) |
| `countryCode` | string | Country code |
| `cityCode` | string | City code |
| `hotelId` | string | Hotel ID |
| `totalRate` | float | Total price |
| `currency` | string | Currency code (e.g., USD) |
| `roomType` | string | Room type |
| `boardBasis` | string | Board basis (e.g., RO, BB) |
| `bookingKey` | string | Booking key from search |
| `adults` | int | Number of adults |
| `children` | int | Number of children |
| `totalRooms` | int | Number of rooms |

**Returns:** `SimpleXMLElement|null`

---

#### `bookHotel(array $params)`
Complete the final hotel booking.

**Required Parameters:** Same as `preBook()` plus:
| Name | Type | Description |
|------|------|-------------|
| `hotelName` | string | Hotel name |
| `guests` | array | Guest details per room |

**Guest Array Format:**
```php
$guests = [
    // Room 1
    [
        ['salutation' => 'Mr', 'firstName' => 'John', 'lastName' => 'Doe'],
        ['salutation' => 'Mrs', 'firstName' => 'Jane', 'lastName' => 'Doe']
    ],
    // Room 2
    [
        ['salutation' => 'Mr', 'firstName' => 'Bob', 'lastName' => 'Smith']
    ]
];
```

**Returns:** `SimpleXMLElement|null` with `BookingId`, `BookingRefNo`, `Status`

---

### Booking Management

#### `getBookingDetails(string $bookingId, string $bookingCode)`
Retrieve details of an existing booking.

**Parameters:**
| Name | Type | Description |
|------|------|-------------|
| `$bookingId` | string | Booking ID (e.g., `89868B`) |
| `$bookingCode` | string | Booking reference (e.g., `REZ68B80F21`) |

**Returns:** `SimpleXMLElement|null`

**Example:**
```php
$booking = $this->rezlive_api->getBookingDetails('89868B', 'REZ68B80F21');
```

---

#### `getConfirmationDetails(string $bookingId, string $bookingCode)`
Get booking confirmation details.

**Parameters:**
| Name | Type | Description |
|------|------|-------------|
| `$bookingId` | string | Booking ID |
| `$bookingCode` | string | Booking reference |

**Returns:** `SimpleXMLElement|null`

---

### Cancellation

#### `getCancellationPolicy(string $bookingId, string $bookingCode)`
Retrieve cancellation policy and charges for a booking.

**Parameters:**
| Name | Type | Description |
|------|------|-------------|
| `$bookingId` | string | Booking ID |
| `$bookingCode` | string | Booking reference |

**Returns:** `SimpleXMLElement|null` - May include `CancellationPolicy`, `CancellationCharge`, `RefundAmount`

**Example:**
```php
$policy = $this->rezlive_api->getCancellationPolicy('89868B', 'REZ68B80F21');
if ($policy && isset($policy->CancellationCharge)) {
    echo "Cancellation fee: " . $policy->CancellationCharge;
}
```

---

#### `cancelHotel(string $bookingId, string $bookingCode)`
Cancel an existing hotel booking.

**Parameters:**
| Name | Type | Description |
|------|------|-------------|
| `$bookingId` | string | Booking ID |
| `$bookingCode` | string | Booking reference |

**Returns:** `SimpleXMLElement|null` - Contains `Status`, `Message`, `CancellationId`

**Example:**
```php
$result = $this->rezlive_api->cancelHotel('89868B', 'REZ68B80F21');
if ($result && $result->Status == 'Cancelled') {
    echo "Booking cancelled successfully";
}
```

---

### Utility

#### `getExchangeRate()`
Fetch current USD to NGN exchange rate. **Cached for 6 hours.**

**Returns:** `float` - Exchange rate (returns 1 if unavailable)

---

#### `getAuthXml()`
Generate authentication XML block for API requests.

**Returns:** `string` - XML authentication block

---

## API Endpoints Reference

| Method | Endpoint | Caching |
|--------|----------|---------|
| `findHotels` | `findhotel` or `findhotelbyid` | No |
| `getHotelDetails` | `gethoteldetails` | Yes (1hr) |
| `preBook` | `prebook` | No |
| `bookHotel` | `bookhotel` | No |
| `getBookingDetails` | `getbookingdetails` | No |
| `getConfirmationDetails` | `getConfirmationDetails` | No |
| `getCancellationPolicy` | `getCancellationPolicyAfterBooking` | No |
| `cancelHotel` | `cancelhotel` | No |

---

## Debugging

- **Request/Response XML:** Saved to `application/xml/` directory
- **Error Logging:** Uses CodeIgniter's `log_message()` for errors
- **Cache Files:** Stored in `application/cache/` as `.cache` files

---

## Error Handling

All methods return `null` on failure. Check for errors:

```php
$response = $this->rezlive_api->findHotels($params);

if ($response === null) {
    // API call failed - check logs
}

if (isset($response->Error) || isset($response->error)) {
    // API returned an error
    $errorMsg = (string) ($response->Error ?? $response->error);
}
```
