<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/Format.php';
require_once APPPATH . '../vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Auth extends RestController
{
     public function __construct()
    {
        parent::__construct();
        $this->load->model('Db_model', '', true);
        // Add this before any output
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: X-API-KEY,Content-Type, Authorization,Origin,X-Requested-With,Content-Type,Accept,Access-Control-Request-Method');
        header("HTTP/1.1 200 OK");
        header("Content-Type: application/json");
        
        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit();
        }


    }

    public function index_get()
    {
        $this->update_key_post();
        
        // Retrieve the API key from the database
        $key = $this->Db_model->getfilteredData("SELECT `key` FROM `tbl_api_keys`");
        $api_key = $key[0]->key;

        // Use the API key dynamically
        $header_value = "{{X-API-KEY}}";
        $header_value = str_replace("{{X-API-KEY}}", $api_key, $header_value);
        // Create the response array
        $AuthArray = [
            'success' => "logged in successfully",
            'token' => $api_key,
        ];

        // Output the response
        echo json_encode($AuthArray, JSON_PRETTY_PRINT);
    }

    public function update_key_post()
    {
        // Generate a new API key
        $new_key = $this->generateApiKey();
        // $new_key = $header_value;

        // Define the data and where clause
        $data = ['key' => $new_key];
        $where = ['id' => 1]; // Assuming you're updating the key with a specific ID (you can adjust as needed)

        // Update the API key in the database
        $result = $this->Db_model->updateData('tbl_api_keys', $data, $where);
    }

    // Method to generate a random API key
    private function generateApiKey($length = 64)
    {
        return bin2hex(random_bytes($length / 2));
    }
}
