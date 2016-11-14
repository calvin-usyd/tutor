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
		//GET COORDINATOR LIST
		$coor = array();
		$coordinators = $this->cCasual->getById('position', 'coordinator');
		//$f3->set('coordinator', $results);
		
		$f3->set('navTab', 'home');
		$f3->set('inc', 'manager.htm');
	}
	
}	