<?php
require 'SendEmailController.php';

class TutorCoordinatorController extends TutorController
{	
	function afterroute($f3) { 
		if (!$f3->exists('SESSION.user') || $f3->get('SESSION.user') == '' || $f3->get('SESSION.position') != 'coordinator'){
			$f3->reroute('/login');
		}
		
		echo Template::instance()->render('z_layoutAccount.htm');
    }

	function defaultRedirect($f3){
		$f3->reroute('/coordinator');
	}
	
	function index($f3){		
		$f3->set('navTab', 'home');
		$f3->set('inc', 'coordinator.htm');
	}

	private function casualArr(){
		return 	$this->users->getById(
			'position', 
			'casual'
		);
	}
	
	function casualList($f3){
		$this->echoJson(
			$this->f3RecToArr(
				$this->casualArr(), 
				array('fname', 'lname', 'email')//$this->users->fields()
			)
		);		
	}
	
	function courseList($f3){
		$this->echoJson(
			$this->f3RecToArr(
				$this->course->getByArray(array(
					'coordinatorEmail=? and status=?', 
					$f3->get('SESSION.user'), 
					'true'
				)),
				$this->course->fields()
			)
		);
	}
	
	function courseCasualList($f3){
		$this->echoJson(
			$this->f3RecToArr(
				$this->cCasual->getById(
					'courseLongId', 
					$f3->get('PARAMS.courseLongId')
				),
				$this->cCasual->fields()
			)
		);
	}
	
	function courseCasualAddEditDel($f3){
		$postData = json_decode($f3->get('BODY'), true);
		
		foreach($postData as $rec){
			if ($rec['lecturer'] != '' &&
				$rec['teachingMethod'] != '' 
				/*&&
				$rec['hours'] != '' &&
				$rec['rateCode'] != '' &&
				$rec['hourRate'] != '' &&
				$rec['cost'] != ''*/
			){
				if ($rec['delete'] == 'true'){
					if (isset($rec['courseCasualLongId'])){//DELETE CASUAL FROM COURSE IF THE CASUAL HAD PREVIOUSLY SAVED.
						$this->cCasual->delete('courseCasualLongId', $rec['courseCasualLongId']);
					}
				}else{
					$f3->set('POST.lecturer', $rec['lecturer']);
					$f3->set('POST.teachingMethod', $rec['teachingMethod']);
					$f3->set('POST.hours', $rec['hours']);
					$f3->set('POST.rateCode', $rec['rateCode']);
					$f3->set('POST.hourRate', $rec['hourRate']);
					$f3->set('POST.cost', ($rec['hours'] * $rec['hourRate'] ));
					$f3->set('POST.courseLongId', $f3->get('PARAMS.courseLongId'));
					
					$this->cCasual->reset();
					
					if (!isset($rec['courseCasualLongId'])){
						$f3->set('POST.courseCasualLongId', $this->genLongId());
						$this->cCasual->add();
						
					}else{
						$f3->set('POST.courseCasualLongId', $rec['courseCasualLongId']);
						$this->cCasual->editByArray(array('courseCasualLongId=?', $rec['courseCasualLongId']));

					}
				}
			}
		}
		
		$this->courseCasualList($f3);
	}
}	