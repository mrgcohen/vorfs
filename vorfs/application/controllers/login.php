<?php

class Login extends Controller {
	
	function index()
	{
	/***************************************************
	Sends user either to the front page or the actual site, depending on cookies.
	View: site (if cookie), login_form (if no cookie)
	*****************************************************/
		$data['error'] = 'none';
		$username = $this->session->userdata('username');
		if ($username)
			{
				redirect('site');
			}
		else
		{
			$this->load->view('login_form', $data);
		}
		
	}
	
	function validate_credentials()
	/***************************************************
	Tests login information.
	View: tutorial (if first time), site (if not first time), login_form (if wrong username/password)
	*****************************************************/
	{		
		$this->load->model('membership_model');
		$query = $this->membership_model->validate();
		
		if($query) // if the user's credentials validated...
		{
			$data = array(
				'username' => $this->input->post('username'),
				'is_logged_in' => true
			);
			$this->session->set_userdata($data);
			
			//get thing from members model
			$tutorial = 0;
			$tutorial = $this->membership_model->test_tutorial($data['username']);
			
			if ($tutorial){//If this is the first time or preference is on
				$this->membership_model->update_tutorial();
				redirect('site/tutorial');
			}
			else{//if preference is off
				redirect('site/');
			}
		}
		else // incorrect username or password
		{
			$data['error'] = 'Incorrect username/password.';
			$data['main_content'] = 'login_form';
			$this->load->view('includes/template', $data);
		}
	}	
	
	function signup()
	/***************************************************
	Loads the signup form to create an account.
	View: signup_form
	*****************************************************/
	{
		$data['error'] = 'none';
		$this->load->view('signup_form', $data);
	}
	
	function forgot()
	/***************************************************
	Loads forgot password form.
	View: forgot_password_form
	*****************************************************/
	{
		$data['main_content'] = 'forgot_password_form';
		$this->load->view('forgot_password_form', $data);
	}
	
	function forgot_sent()
	{
		$data['email_address'] = $this->input->post('email_address');
		echo 'an email will be sent with instructions to '. $data['email_address']. '.  have not made this yet.';
	}
	
	function create_team()
	{
		$data['team_name'] = $this->input->post('team_name');
		$data['username'] = $this->session->userdata('username');
		
		$this->load->model('membership_model');
		$duplicate = 0;
		$duplicate = $this->membership_model->check_team_duplicate($data);

		if ($duplicate == 0){
			$this->membership_model->update_team($data);
			redirect('/site/home/');
		}
		else{
			redirect('/site/home/');
		}
		
		
	}
	
	function create_member()
	{
		$this->load->library('form_validation');
		
		// field name, error message, validation rules
		$this->form_validation->set_rules('first_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');
		
		///this is not in tutorial, this is saving the form data to send to email function.	
		$data['first_name'] = $this->input->post('first_name');
		$data['last_name'] = $this->input->post('last_name');
		//$data['team_name'] = $this->input->post('team_name');
		$data['username'] = $this->input->post('username');
		$data['password'] = $this->input->post('password');
		$data['email_address'] = $this->input->post('email_address');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->signup();
		}
		
		else
		{			
			$this->load->model('membership_model');
			
			$query_usertest = $this->membership_model->test_username();

			if ($query_usertest)
			{
			
				if($query = $this->membership_model->create_member())
				{
					$this->initialize_stats($data);
					$this->__sendemail($data);
				}
				else
				{
					
					$this->signup();	
				}
			}
			
			else //if this is not a unique username
			{
				$data['main_content'] = 'signup_form';
				$data['error'] = 'Username taken, try a new username.';
				$this->load->view('includes/template', $data);
			}
		}
		
	}
	
	function initialize_stats($data)
	{
		$this->load->model('membership_model');
		$this->membership_model->initialize_stats();
		
	}
	
	function logout()
	{
		$this->load->helper('url');
		$this->session->sess_destroy();
		
		redirect('');
		//$this->index();
	}
	
	function __sendemail($data)
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
		$this->email->to($data['email_address']);
		$this->email->subject('Signup Successful');
		$this->email->message('Welcome to GSWL Pick Em, '. $data['first_name']. '!  Your username is: '. $data['username']. '.  Your password is: '. $data['password']. '.');
		

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



