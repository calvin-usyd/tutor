<?php
class TutorCasualController extends TutorController
{
	function afterroute($f3) {
		if (!$f3->exists('SESSION.user') || $f3->get('SESSION.user') == '' || $f3->get('SESSION.position') != 'casual'){
			$f3->reroute('/login');
		}
		
		echo Template::instance()->render('z_layoutAccount.htm');
    }

	function index($f3){		
		$this->cProfile->getById('email', $f3->get('SESSION.email'));
		
		$f3->set('data', $this->cProfile);
		$f3->set('navTab', 'home');
		$f3->set('inc', 'casual.htm');
	}
	
	function defaultRedirect($f3){
		$f3->reroute('/casual');
	}
	
	function profileEdit($f3){
		if (
			$f3->get('POST.role') != '' && 
			$f3->get('POST.staffTitle') != '' && 
			$f3->get('POST.staffDOB') != '' && 
			$f3->get('POST.employeeID') != '' && 
			$f3->get('POST.address') != '' && 
			$f3->get('POST.suburb') != '' && 
			$f3->get('POST.state') != '' && 
			$f3->get('POST.postcode') != '' && 
			$f3->get('POST.phone') != '' 
		){
			$this->cProfile->getById('email', $f3->get('SESSION.email'));
			
			if ($this->cProfile->dry()){
				$f3->set('POST.email', $f3->get('SESSION.email'));
				$this->cProfile->add();
				
			}else{
				$this->cProfile->edit('email', $f3->get('SESSION.email'));
			}
			$msg = array('Success', 'Profile updated successfully!');
					
		}else{
			$msg = array('Warning', 'Please fill up all empty fields!');
		}
		
		$f3->reroute('/casual');
	}
}	