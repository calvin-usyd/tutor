<?php
require 'SendEmailController.php';

class TutorAuthController extends TutorController{
	
	function login($f3){
		if ($f3->exists('POST.cred') && $f3->exists('POST.password') && $f3->exists('POST.position')){
			$cred = $f3->get('POST.cred');
			
			$result = $this->users->getByArray(array('email=? and position=?', $cred, $f3->get('POST.position')));
				
			$validPass = 0;
			
			if (count($result) > 0){
				$crypt = \Bcrypt::instance();
				$validPass = $crypt->verify($f3->get('POST.password'), $result[0]['password']);
			}
			
			if ($validPass){
				$id = $result[0]['id'];
				$position = $result[0]['position'];
				$email = $result[0]['email'];
				$fname = $result[0]['fname'];
				$lname = $result[0]['lname'];
				$phoneExtension = $result[0]['phoneExtension'];
				
				$f3->set('SESSION.user', $cred);/*email or id*/
				$f3->set('SESSION.email', $email);
				$f3->set('SESSION.fname', $fname);
				$f3->set('SESSION.lname', $lname);
				$f3->set('SESSION.position', $position);
				$f3->set('SESSION.phoneExtension', $phoneExtension);
				
				if ($position == 'admin' && $approved != '1'){
					$f3->set('warn_message', 'Waiting approval from calvin (calvin.chiew@sydney.edu.au)');

				}else{
					$f3->reroute('/'.$position);
				}
			}else{
				$f3->set('err_message', 'Invalid access credential. please try again!');
			}
		}
		$f3->set('inc', 'z_login.htm');
	}
	
	function logout($f3){
		$f3->clear('SESSION');
		$f3->clear('COOKIE');
		
		$f3->set('SESSION.user',null);
		$f3->set('SESSION.fname',null);
		$f3->set('SESSION.lname',null);
		$f3->set('SESSION.position',null);
		
		$f3->reroute('/');
	}
	
	function register($f3){
		if ($f3->exists('POST.fname') &&
			$f3->exists('POST.lname') &&
			$f3->exists('POST.email') &&
			$f3->exists('POST.position') &&
			$f3->exists('POST.password') &&
			$f3->exists('POST.confirmPassword')
		){
			$id = $f3->get('POST.id');
			
			$result = $this->users->getByArray(array('email=? and position=?', $f3->get('POST.email'), $f3->get('POST.position')));
			
			if (!$this->users->dry()){
				$f3->set('err_message', 'This email with the selected position has been registered!');
				
			}elseif ($f3->get('POST.password') != $f3->get('POST.confirmPassword')){
				$f3->set('err_message', 'Password and confirm password are not same!');
				
			}else{
				$crypt = \Bcrypt::instance();
				
				$f3->set('POST.password', $crypt->hash($f3->get('POST.password')));
				$f3->set('POST.userLongId', $this->genLongId());
				$this->users->add();
				
				$f3->set('SESSION.user', $id);
				//$f3->set('SESSION.userLongId', $this->users->userLongId);
				$f3->set('SESSION.fname', $this->users->fname);
				$f3->set('SESSION.lname', $this->users->lname);
				$f3->set('SESSION.email', $this->users->email);
				$f3->set('SESSION.position', $this->users->position);
				$f3->set('SESSION.phoneExtension', $this->users->phoneExtension);
				
				if ($this->users->position == 'admin' && $this->users->approved != '1'){
					$f3->set('warn_message', 'Your registration has been received! Waiting approval from calvin (calvin.chiew@sydney.edu.au)');//CALVIN MUST SET THE approved TO '1'
				
					$this->alertRegistration($f3, $f3->get('POST.email'), $f3->get('POST.fname'), $f3->get('POST.lname'));
				}else{
					$f3->reroute('/'.$this->users->position);
				}
			}
		}
		
		$f3->set('inc', 'z_register.htm');
	}
	
	private function alertRegistration($f3, $email, $fname, $lname){
		$sbj='Registration Alert | Tutor Management University Of Sydney';
		$fr='no-reply@tutor.quantumfi.com.au';
		$to='calvin.chiew@sydney.edu.au';
		$msg=$fname . ' ' . $lname . '(' . $email . ') has registered as admin and required your approval asap!';
		$frN='Quantumfi Bot';
		
		$cnt_json = json_encode(array('fr'=>$fr, 'to'=>$to, 'subject'=>$sbj, 'message'=>$msg, 'frName'=>$frN));
		
		$email = new SendEmailController();
		$email->post_json($f3->get('CONFIG_qMAPI'), $cnt_json);
		
	}
}