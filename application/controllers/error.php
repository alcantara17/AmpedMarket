<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller {

	public function __construct() {}

	public $error = array();

	public function index($params) {

		$error['forbidden'] = array(	'message' 	=> 'Not authorized to view this page.',
						'code' 		=> '403' );
		$error['itemNotFound'] = array(	'message' 	=> 'The item you have requested does not exist.',
						'code' 		=> '404' );
		$error['userNotFound'] = array(	'message' 	=> 'That user does not exist.',
						'code' 		=> '404' );
		$error['categoryNotFound'] = array('message' 	=> 'The requested category could not be found',
						'code'		=> '404' );
	

		if(is_array($error[$params])){
			show_error($error[$params]['message'], $error[$params]['code']);
		} else {
			show_error('Unable to complete your request, please try again.');
		}
	}
};
