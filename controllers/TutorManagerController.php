<?php
class TutorManagerController extends TutorController
{	
	function afterroute($f3) { 
		if (!$f3->exists('SESSION.user') || $f3->get('SESSION.user') == '' || $f3->get('SESSION.position') != 'manager'){
			$f3->reroute('/login');
		}
		
		echo Template::instance()->render('z_layoutAccount.htm');
    }

	function defaultRedirect($f3){
		$f3->reroute('/' . $f3->get('SESSION.position'));
	}
	
	function index($f3){		
		$f3->set('navTab', 'home');
		$f3->set('inc', 'manager.htm');
	}
	
	function casualAcademicList($f3){
		/*$user = $this->f3RecToArr(
			$this->user->all(),//ADMIN AND MANAGER ARE NOT MANY, SO JUST QUERY ALL
			array('email', 'fname', 'lname', 'position', 'phoneExtension')//$this->user->fields()
		);*/
		$this->echoJson(
			$this->f3RecToArr(
				$this->vCAcademic->all(), 
				$this->vCAcademic->fields()
			)
		);
	}
	
	function casualAcademicAddEdit($f3){
		$postData = json_decode($f3->get('BODY'), true);
		
		foreach($postData as $rec){
			$f3->set('POST.courseCasualLongId', $rec['courseCasualLongId']);
			$f3->set('POST.newOrExtension', $rec['newOrExtension']);
			$f3->set('POST.dateContract', $rec['dateContract']);
			$f3->set('POST.startDate', $rec['startDate']);
			$f3->set('POST.finishDate', $rec['finishDate']);
			$f3->set('POST.rc', $rec['rc']);
			$f3->set('POST.pc', $rec['pc']);
			$f3->set('POST.analysis', $rec['analysis']);
			
			$this->cAcademic->reset();
			
			if (isset($rec['caLongId'])){
				$f3->set('POST.caLongId', $rec['caLongId']);
				$this->cAcademic->editByArray(array('caLongId=?', $rec['caLongId']));
				
			}else{
				$f3->set('POST.caLongId', $this->genLongId());
				$this->cAcademic->add();
				
			}
		}
		$this->casualAcademicList($f3);
	}
}	