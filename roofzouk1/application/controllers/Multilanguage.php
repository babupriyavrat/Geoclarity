<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*	
 *	@author : Joyonto Roy
 *	date	: 1 August, 2013
 *	University Of Dhaka, Bangladesh
 *	Hospital Management system
 *	http://codecanyon.net/user/joyontaroy
 */


class Multilanguage extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->helper('url');
		/*cash control*/
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}
	
	function index()
	{
	}
	
	function select_language()
	{
	    $language = $this->input->post('language');
	    $redirectURL = $this->input->post('redirectURL');
	    
	    if ($language == '' || $language == false){
	        $language = 'english';
	    }
	    
		$this->session->set_userdata('language', $language);
		
		redirect($redirectURL, 'refresh');
	}
}
