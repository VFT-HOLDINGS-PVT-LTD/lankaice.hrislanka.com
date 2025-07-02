<?php

class cron_job_cnt extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library("pagination");
    }
    
    public function cron_job()
    {
        $this->load->model('cron_job_model');
        $this->cron_job_model->cron_job();
    }
}
