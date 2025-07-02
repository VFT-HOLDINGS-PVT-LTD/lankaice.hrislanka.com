<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
class cron_job_model extends CI_Model 
{    	
    function cron_job()
    {
// 		$msg = "Hi! Thanks for watching this video. Comments if you want this code.";
// 		$msg = wordwrap($msg,70);
// 		// send email
// 		mail("pasinduramesh277@gmail.com","Codeignator cron job by Shinerweb",$msg);		
                                $config = array(
                                    'protocol' => 'smtp',
                                    'smtp_host' => 'mail.vfthris.com',
                                    'smtp_user' => 'mail@vfthris.com',
                                    'smtp_pass' => 'Wlm7?Ux7g[s1',
                                    'smtp_port' => 587,
                                    'charset' => 'utf-8',
                                    'mailtype' => 'html',
                                    'newline' => "\r\n",
                                );

                                $this->load->library("email");
                                $this->email->initialize($config);

                                $this->email->from("mail@vfthris.com");
                                $this->email->to("pasinduramesh277@gmail.com");
                                $this->email->message('Mail Test');
                                $this->email->subject("Mail Test");
    }
}

