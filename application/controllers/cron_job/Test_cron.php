<?php

class Test_cron extends CI_Controller
{
    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->load->helper(array('form', 'url'));
    //     $this->load->library("pagination");
    // }

    public function cron_job()
    {
        // $this->load->model('cron_job_model');
        // $this->cron_job_model->cron_job();
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'mail.vfthris.com',
            'smtp_user' => 'mail@vfthris.com',
            'smtp_pass' => 'Wlm7?Ux7g[s1',
            'smtp_port' => 587,
            'smtp_crypto' => 'tls', // Add this for security
            'charset' => 'utf-8',
            'mailtype' => 'html',
            'newline' => "\r\n",
            'wordwrap' => TRUE // Optional, but useful to wrap long lines
        );
        
        $this->load->library('email', $config); // Pass config during load
        $this->email->initialize($config);
        
        $this->email->from('mail@vfthris.com', 'Your Name');
        $this->email->to('pasinduramesh277@gmail.com');
        $this->email->subject('Mail Test');
        $this->email->message('This is a test email.');
        
        if ($this->email->send()) {
            echo 'Email sent successfully!';
        } else {
            echo 'Failed to send email.';
            echo $this->email->print_debugger(); // For debugging any errors
        }
    }

    public function index(){
    //   echo "test";
    $config = array(
    'protocol' => 'smtp',
    'smtp_host' => 'mail.vfthris.com',
    'smtp_user' => 'mail@vfthris.com',
    'smtp_pass' => 'Wlm7?Ux7g[s1',
    'smtp_port' => 587,
    'smtp_crypto' => 'tls', // Add this for security
    'charset' => 'utf-8',
    'mailtype' => 'html',
    'newline' => "\r\n",
    'wordwrap' => TRUE // Optional, but useful to wrap long lines
);

$this->load->library('email', $config); // Pass config during load
$this->email->initialize($config);

$this->email->from('mail@vfthris.com', 'Your Name');
$this->email->to('pasinduramesh277@gmail.com');
$this->email->subject('Mail Test');
$this->email->message('This is a test email.');

if ($this->email->send()) {
    echo 'Email sent successfully!';
} else {
    echo 'Failed to send email.';
    echo $this->email->print_debugger(); // For debugging any errors
}

    }
}
