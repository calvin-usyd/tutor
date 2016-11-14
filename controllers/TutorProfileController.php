<?php
class TutorProfileController extends TutorController
{
	function profileEdit($f3){
		if ($f3->get('POST.fname') != '' && 
			$f3->get('POST.lname') != '' && 
			$f3->get('POST.email') != '' ){
			$pass = true;
			if ($f3->get('POST.changePassword') == 1){
				if ($f3->get('POST.currentPassword') != '' && $f3->get('POST.newPassword') != ''){
				
					$this->users->getById('email', $f3->get('SESSION.email'));
					$validPass = 0;
					
					if (!$this->users->dry()){
						$crypt = \Bcrypt::instance();
						$validPass = $crypt->verify($f3->get('POST.currentPassword'), $this->users->password);

						if ($validPass){
							$f3->set('POST.password', $crypt->hash($f3->get('POST.newPassword')));
						}else{
							$msg = array('Danger', 'Incorrect current password!');
							$pass = false;
						}	
					}
				}else{
					$msg = array('Warning', 'Please fill up all the password fields or un-check "Change password" checkbox!');
					$pass = false;
				}
			}
			if ($pass){
				$this->users->reset();
				$this->users->edit('email', $f3->get('SESSION.email'));
				
				$f3->set('SESSION.fname', $f3->get('POST.fname'));
				$f3->set('SESSION.lname', $f3->get('POST.lname'));
				$f3->set('SESSION.email', $f3->get('POST.email'));
				
				$msg = array('Success', 'Profile updated successfully!');
			}
			
		}else{
			$msg = array('Warning', 'Please fill up all empty fields!');
		}
		
		echo json_encode($msg);
		die();
	}
	
	function profileView($f3){
		$msg = array(
			//'id'=>$f3->get('SESSION.user'), 
			'fname'=>$f3->get('SESSION.fname'), 
			'lname'=>$f3->get('SESSION.lname'),
			'email'=>$f3->get('SESSION.email'));
		echo json_encode($msg);
		die();
	}
}	