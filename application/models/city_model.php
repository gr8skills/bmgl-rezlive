<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * City Model
 * Handles all database operations related to cities
 */
class City_model extends CI_Model
{
	/**
	 * Search cities by term (name, city_code, or country_code)
	 * @param string $term Search term
	 * @param int $limit Maximum results to return
	 * @return array Array of city objects
	 */
	public function search($term, $limit = 10)
	{
		// Escape the search term for LIKE queries
		$term = $this->db->escape_like_str($term);

		$this->db->select('id, name, city_code, country_code, country_name');
		$this->db->from('cities');
		// Use raw where clause for OR conditions since CI 2.1.4 doesn't have group_start/group_end
		$this->db->where("(name LIKE '%{$term}%' OR city_code LIKE '%{$term}%' OR country_code LIKE '%{$term}%')", NULL, FALSE);
		$this->db->order_by('name', 'ASC');
		$this->db->limit($limit);

		return $this->db->get()->result();
	}

	/**
	 * Get city by city code
	 * @param string $cityCode The city code
	 * @return object|null City object or null if not found
	 */
	public function get_by_code($cityCode)
	{
		$this->db->select('id, name, city_code, country_code, country_name');
		$this->db->from('cities');
		$this->db->where('city_code', $cityCode);

		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->row() : null;
	}

	/**
	 * Get city by name and country
	 * @param string $cityName The city name
	 * @param string $countryName The country name
	 * @return object|null City object or null if not found
	 */
	public function get_by_name_and_country($cityName, $countryName)
	{
		$this->db->select('id, name, city_code, country_code, country_name');
		$this->db->from('cities');
		$this->db->where('name', $cityName);
		$this->db->where('country_name', $countryName);

		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->row() : null;
	}

	/**
	 * Parse location string in "City Name (Country Name)" format
	 * @param string $locationString The location string to parse
	 * @return array|null Array with cityName and countryName, or null if invalid format
	 */
	public function parse_location_string($locationString)
	{
		if (preg_match('/^(.*?)\s*\((.*?)\)$/', $locationString, $matches)) {
			return array(
				'cityName' => trim($matches[1]),
				'countryName' => trim($matches[2])
			);
		}
		return null;
	}

	/**
	 * Get city codes from location string, with fallback to defaults
	 * @param string $locationString Location in "City (Country)" format
	 * @return array Array with cityCode and countryCode
	 */
	public function get_codes_from_location($locationString)
	{
		$parsed = $this->parse_location_string($locationString);

		if ($parsed) {
			$city = $this->get_by_name_and_country($parsed['cityName'], $parsed['countryName']);
			if ($city) {
				return array(
					'cityCode' => $city->city_code,
					'countryCode' => $city->country_code,
					'cityName' => $city->name
				);
			}
		}

		// Return defaults if not found
		return array(
			'cityCode' => DEFAULT_CITY_CODE,
			'countryCode' => DEFAULT_COUNTRY_CODE,
			'cityName' => 'Lagos'
		);
	}
}

/* End of file city_model.php */
/* Location: ./application/models/city_model.php */
