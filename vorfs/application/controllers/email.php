<?php

class Email extends Controller
{
	
	function __construct()
	{
		parent::Controller();
	}
	
	
	function index()
	{
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host'=> 'ssl://smtp.googlemail.com',
			'smtp_port'=> 465,
			'smtp_user'=> 'gswlpickem@gmail.com',
			'smtp_pass'=> 'vorfs123'
		);
		
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		
		$this->email->from('gswlpickem@gmail.com', 'GSWL Pick Em');
		$this->email->to('msidorov1@gmail.com');
		$this->email->subject('Signup Successful');
		$this->email->message('Welcome to GSWL Pick Em!');
		
		if ($this->email->send())
		{
			$data['main_content'] = 'signup_successful';
			$this->load->view('includes/template', $data);
		}
		
		else
		{
			show_error($this->email->print_debugger());
		}
	}
}