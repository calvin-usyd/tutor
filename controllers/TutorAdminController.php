<?php
class TutorAdminController extends TutorController
{
	function afterroute($f3) {
		if (!$f3->exists('SESSION.user') || $f3->get('SESSION.user') == '' || $f3->get('SESSION.position') != 'admin'){
			$f3->reroute('/login');
		}
		
		echo Template::instance()->render('z_layoutAccount.htm');
    }

	function index($f3){
		$f3->set('navTab', 'home');
		
		$f3->set('inc', 'admin.htm');
	}
	
	function defaultRedirect($f3){
		$f3->reroute('/admin');
	}
	
	function courseList($f3){
		$this->echoJson(array_map(
			function($val){
				return array(
					'courseLongId'=>$val['courseLongId'], 
					'siteName'=>$val['siteName'], 
					'courseName'=>$val['courseName'], 
					'unitOfStudy'=>$val['unitOfStudy'], 
					'coordinatorEmail'=>$val['coordinatorEmail'],
					'status'=>false
				);
			}, 
			$this->course->all()
		));
	}
	
	function courseAddEditDel($f3){
		
		$postData = json_decode($f3->get('BODY'), true);
		
		foreach($postData as $rec){
			if ($rec['siteName'] != '' &&
				$rec['courseName'] != '' &&
				$rec['unitOfStudy'] != '' &&
				$rec['coordinatorEmail'] != ''			
			){
				if ($rec['delete'] == 'true'){
					$this->cCasual->getById('courseLongId', $rec['courseLongId']);
					
					if ($this->cCasual->dry()){//COURSE ID IS NOT FOUND ELSE WHERE, THEN IS SAVED TO DELETE
						$this->course->delete('courseLongId', $rec['courseLongId']);
						
					}else{//OTHERWISE SET INACTIVE.
						$f3->set('POST.status', 'inactive');
					}
				}else{
					$f3->set('POST.status', 'active');
				}
			
				$f3->set('POST.siteName', $rec['siteName']);
				$f3->set('POST.courseName', $rec['courseName']);
				$f3->set('POST.unitOfStudy', $rec['unitOfStudy']);
				$f3->set('POST.coordinatorEmail', $rec['coordinatorEmail']);
				
				$this->course->reset();
				
				if (!isset($rec['courseLongId'])){
					$f3->set('POST.courseLongId', $this->genLongId());
					$this->course->add();
					
				}else{
					$f3->set('POST.courseLongId', $rec['courseLongId']);
					$this->course->editByArray(array('courseLongId=?', $rec['courseLongId']));

				}
			}
		}
		
		$this->courseList($f3);
	}
}	