<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Admin_Controller 
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_auth');

		
	}

	public function check_admin() {
		if ($this->session->userdata('role') !== 'admin') {
			redirect('dashboard');
		}
	}

	/* 
		Check if the login form is submitted, and validates the user credential
		If not submitted it redirects to the login page
	*/
	public function login()
	{
		$this->logged_in();

		// Only process if it's a POST request
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run() == TRUE) {
				$email_exists = $this->model_auth->check_email($this->input->post('email'));

				if($email_exists == TRUE) {
					$login = $this->model_auth->login($this->input->post('email'), $this->input->post('password'));

					if($login) {
						$logged_in_sess = array(
							'id' => $login['id'],
							'username'  => $login['username'],
							'email'     => $login['email'],
							'logged_in' => TRUE
						);

						$this->session->set_userdata($logged_in_sess);
						redirect('dashboard', 'refresh');
					} else {
						$this->data['errors'] = 'Incorrect username/password combination';
					}
				} else {
					$this->data['errors'] = 'Email does not exists';
				}
			} else {
				// If validation fails, set the validation errors
				$this->data['errors'] = validation_errors();
			}
		}
		
		$this->load->view('login', isset($this->data) ? $this->data : array());
	}

	/*
		clears the session and redirects to login page
	*/
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('auth/login', 'refresh');
	}

}
