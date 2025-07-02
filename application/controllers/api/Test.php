<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/Format.php';
require_once APPPATH . '../vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Test extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Db_model', '', true);
    }
    public function index_get()
    {
        // echo "I am RESTful API";
        $authUrl = "https://biotime.cloud:8000/api/user/";
        $payload = array(
            'email' => 'fitnessplace@vfthris.com',
            'password' => 'Fitnessplace@123',
        );

        // Initialize cURL
        $ch = curl_init($authUrl);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        // Execute cURL request
        $response = curl_exec($ch);

        echo $response;
    }
}
