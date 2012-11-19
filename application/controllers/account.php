<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('users_model');
		$this->load->model('accounts_model');
		$this->load->library('form_validation');
	}

	public function index(){
		// Load the account information page.
		$data['page'] = 'account/index';
		$data['title'] = 'My Account';
		
		// Load information about the user.
		$userHash = $this->my_session->userdata('userHash');
		$data['account'] = $this->accounts_model->getAccountInfo($userHash);
	
		// Check whether the site requires vendors to use PGP.
		$data['force_vendor_pgp'] = $this->my_config->force_vendor_pgp();

		$this->load->library('layout',$data);
	}

	public function edit(){
		$data['page'] = 'account/edit';
		$data['title'] = 'Edit Account';

		// Load information about the user.
		$userHash = $this->my_session->userdata('userHash');
		$data['account'] = $this->accounts_model->getAccountInfo($userHash);

		$this->load->library('layout',$data);
	} 

	public function replacePGP(){
		$userHash = $this->my_session->userdata('userHash');
		$data['account'] = $this->accounts_model->getAccountInfo($userHash);

		// Load information about the current user
		$userHash = $this->my_session->userdata('userHash');
		$loginInfo = $this->users_model->get_user(array('userHash' => $userHash));
		// Generate the hash of the password to test.
		$testPass = $this->general->hashFunction($this->input->post('passwordConfirm'),$loginInfo['userSalt']);	

		// Check the password for this username.
		$checkPass = $this->users_model->checkPass($loginInfo['userName'], $testPass);
		if($checkPass === TRUE){
			// Password correct
			$correctPass = TRUE;
		} else {
			// Password incorrect, stop code execution later.
			$correctPass = FALSE;
		}
		
		$data['page'] = 'account/replacePGP';
		$data['title'] = 'Replace PGP key';
		// If the pubKey POST value is set, try update it.
		if($this->input->post('pubKey')){
			$pubKey = $this->input->post('pubKey');

			if($correctPass==TRUE){
				// If the password is correct, delete any other public keys and add the new one.
				if($this->users_model->drop_pubKey_by_id($loginInfo['id'])){
					if($this->accounts_model->updateAccount($loginInfo['id'],array('pubKey' => $pubKey))){
						$data['page'] = 'account/index';
						$data['title'] = 'Account';		
						$data['force_vendor_pgp'] = $this->my_config->force_vendor_pgp();
						$data['returnMessage'] = 'Your PGP public key has been updated.';
					} else {
						$data['returnMessage'] = 'Unable to update your public key.';
					}
				} else {
					$data['returnMessage'] = 'Unable to remove your previous PGP key.';
				}
			} else {
				$data['returnMessage'] = "Your password was incorrect.";
			}
		}
		$this->load->library('Layout',$data);
	}

	public function update(){
		// Load information about the current user
		$userHash = $this->my_session->userdata('userHash');
		$loginInfo = $this->users_model->get_user(array('userHash' => $userHash));
		// Generate the hash of the password to test.
		$testPass = $this->general->hashFunction($this->input->post('passwordConfirm'),$loginInfo['userSalt']);	

		// Check the password for this username.
		$checkPass = $this->users_model->checkPass($loginInfo['userName'], $testPass);
		if($checkPass === TRUE){
			// Password correct
			$correctPass = TRUE;
		} else {
			// Password incorrect, stop code execution later.
			$correctPass = FALSE;
		}

		// Check if there's a problem with the submitted PGP key.
		$PGPfail = FALSE;
		$pubKey = $this->input->post('pubKey',TRUE);
		$changes = array();

		// Check if the public Key is set.
		if(	$this->input->post('pubKey') !== 'No Public Key found.' &&
			$this->input->post('pubKey') !== NULL ){
			// Load the sumitee
			$currentKey = $this->users_model->get_pubKey_by_id($loginInfo['id']);
			// Check if the current Key is empty
			if($currentKey === NULL){
				// Check the submission is not empty (in that case, do nothing).
				if($pubKey !== NULL){
					// Check the PGP Key is OK!
					if(substr($pubKey,0,36) == '-----BEGIN PGP PUBLIC KEY BLOCK-----'){
						// Add to the changes array.
						$changes['pubKey'] = $pubKey;
					} else {
						// Got this far, but there was a problem. Incorrect heading.
						$PGPfail = TRUE;
					}
				}
			// Check if there is currently a public Key.
			} else if(substr($currentKey,0,36) == 'BEGIN PGP PUBLIC KEY BLOCK-----'){

				// Check the POST data is also a PGP key, and that it's different to the current one. 
				if((substr($pubKey,0,36) == '-----BEGIN PGP PUBLIC KEY BLOCK-----') &&
				(sha2($pubKey) !== sha2($currentKey))){
					// New one is different, make the change!
					$changes['pubKey'] = $pubKey;
				} else if(substr($pubKey,0,36) !== '-----BEGIN PGP PUBLIC KEY BLOCK-----' &&
					$pubKey !== NULL){
					// Heading is invalid!
					$PGPfail = TRUE;
				}	
				// Do nothing if pubKey is empty. 
			}				
		}

		// Check if the two step field is being changed.
		$twoStep = $this->input->post('twoStep');
		if($twoStep !== $loginInfo['twoStepAuth']){
			$changes['twoStep'] = $twoStep;
		}

		// Check if the items-per-page has been changed.
		$items_per_page = $this->input->post('items_per_page');
		if($items_per_page !== $loginInfo['items_per_page']){
			$this->my_session->set_userdata('items_per_page',$items_per_page);
			$changes['items_per_page'] = $items_per_page;
		}

		// Force PGP messages
		$forcePGPmessage = $this->input->post('forcePGPmessage');
		if($forcePGPmessage !== $loginInfo['forcePGPmessage']){
			$changes['forcePGPmessage'] = $forcePGPmessage;
		}

		$passFail = FALSE;
		// Check if we are suppposed to update the password.
		if(	strlen($this->input->post('password0') > 0)){
			// Check the two passwords match.
			if($this->input->post('password0') == $this->input->post('password1')){
				$changes['password'] = $this->general->hashFunction($this->input->post('password0'),$loginInfo['userSalt']);
			} else {
				// Passwords don't match.
				$passFail = TRUE;
			}
		}

		// Check if we're updating the profile message
		$currentMessage = $this->users_model->get_profileMessage($loginInfo['id']);
		$newMessage = strip_tags(nl2br($this->input->post('profileMessage',TRUE)),'<br>');
		if($currentMessage !== $newMessage){
			$changes['profileMessage'] = $newMessage;
		}

		$error = FALSE;
		$errorMsg = "";
		// Check that the user has submitted the right password.
		if($PGPfail === TRUE){
			$error = TRUE;		// ERROR!
			$errorMsg .= "Please check your PGP Key.<br />";
		// Check if the passwords don't match.
		} else if($passFail === TRUE){
			$error = TRUE;		// ERROR!
			$errorMsg .= "Your passwords do not match.<br />";
		}

		if($correctPass === FALSE){
			$data['page'] = 'account/edit';
			$data['title'] = 'Edit Account';
                        $data['returnMessage'] = "Your password was incorrect, please try again.";
		} else if($error === TRUE){
			// Display the edit form.
			$data['page'] = 'account/edit';
			$data['title'] = 'Edit Account';
			$data['returnMessage'] = $errorMsg;
		} else {
			// No errors! Update the account.
			if($this->accounts_model->updateAccount($loginInfo['id'],$changes) == TRUE){
				// Success; display the account page.
				$data['page'] = 'account/index';
				$data['title'] = 'Account';
				$data['returnMessage'] = 'Account has been updated.';
			} else {
				// Otherwise, display the edit page.
				$data['page'] = 'account/edit';
				$data['title'] = 'Edit Account';
				$data['returnMessage'] = "Something went wrong, please try again.";
			}
		}
		
		// Load account info.
		$data['account'] = $this->accounts_model->getAccountInfo($userHash);
		$this->load->library('layout',$data);
	}

	// Delete the stored pubKey for the user, and disable two-step authentication if necessary.
	public function deletePubKey(){
		$userHash = $this->my_session->userdata('userHash');
		
		$user = $this->users_model->get_user(array('userHash' => $userHash));
		// Load the public key for the current user.
		$pubKey = $this->users_model->get_pubKey_by_id($user['id']);

		// Check if there is a PGP on record.
		if($pubKey == NULL){
			// Failure; no PGP key to delete.
			$data['page'] = "account/index";
			$data['title'] = "Account";
			$data['returnMessage'] = "You do not have a PGP key on record.";
		} else {
			// There is a PGP key, try delete it.
			if($this->users_model->drop_pubKey_by_id($user['id'])){
				// Success, display the account page.
				$data['page'] = "account/index";
				$data['title'] = "Account";
				$data['returnMessage'] = "Your PGP key has been erased.";
			} else {
				// Failure, show the accout page.
				$data['page'] = "account/index";
				$data['title'] = "Account";
				$data['returnMessage'] = "There was an error deleting your PGP key.";
			}
		}

		// Load account info for the user.
		$data['account'] = $this->accounts_model->getAccountInfo($userHash);

		$this->load->library('layout',$data);
	}
};

