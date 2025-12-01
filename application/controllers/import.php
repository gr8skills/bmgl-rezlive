<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Include Composer autoloader for PhpSpreadsheet and Symfony Intl
require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Intl\Countries;

class Import extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

	public function index()
	{
		$this->load->view('import_view');
	}

	public function upload()
	{
		ini_set('max_execution_time', 300); // 5 minutes
		ini_set('memory_limit', '512M'); // optional, for large files

		$config['upload_path']   = './uploads/';
		$config['allowed_types'] = 'xlsx|xls';
		$config['max_size']      = 0; // no limit

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('file')) {
			echo $this->upload->display_errors();
		} else {
			$fileData = $this->upload->data();
			$filePath = $fileData['full_path'];

			// Truncate cities table before new insert
			$this->db->truncate('cities');

			$spreadsheet = IOFactory::load($filePath);
			$sheet = $spreadsheet->getActiveSheet()->toArray();

			$batchData = array();

			// Start from row 1 (skip header at row 0)
			for ($i = 1; $i < count($sheet); $i++) {
				$row = $sheet[$i];

				if (empty($row[0])) continue; // skip empty rows

				$countryCode = strtoupper(trim($row[3]));
				$countryName = $this->getCountryName($countryCode);

				$batchData[] = array(
					'id'            => $row[0],
					'name'          => $row[1],
					'city_code'     => $row[2],
					'country_code'  => $countryCode,
					'country_name'  => $countryName,
				);
			}

			if (!empty($batchData)) {
				// Insert in chunks to avoid memory issues
				$chunks = array_chunk($batchData, 500); // 500 rows per batch
				foreach ($chunks as $chunk) {
					$this->db->insert_batch('cities', $chunk);
				}
			}

			// Optionally delete file after import
			@unlink($filePath);

			$this->session->set_flashdata('message', 'Data imported successfully with country names!');
			redirect('import');
		}
	}

	private function getCountryName($code)
	{
		if (!$code) return null;

		try {
			return Countries::getName($code, 'en');
		} catch (Exception $e) {
			return $code; // fallback to code if not found
		}
	}
}

/* End of file import.php */
/* Location: ./application/controllers/import.php */
