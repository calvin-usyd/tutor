<?php
class TutorFrontPageController extends TutorController{
	
	function index($f3){
		
		if (!$f3->exists('SESSION.user') || $f3->get('SESSION.user') == ''){
			$f3->set('inc', 'z_login.htm');
			
		}/*elseif ($f3->get('SESSION.position') == 'coordinator'){
			$f3->reroute('/coordinator');
		}elseif ($f3->get('SESSION.position') == 'coordinator'){
			$f3->reroute('/coordinator');
		}elseif ($f3->get('SESSION.position') == 'coordinator'){
			$f3->reroute('/coordinator');
		}*/else{
			$f3->reroute('/' . $f3->get('SESSION.position'));
		}
	}
	
	function setGuiTheme($f3){
		$user = $f3->get('SESSION.user');
		$theme = $f3->get('POST.guiTheme');
		
		$f3->set('POST.username', $user);
		
		$f3->set('SESSION.guiTheme', $theme);
		
		$this->setting->getById('username', $user);
		
		if ($this->setting->guiTheme == null){
			
			$this->setting->add();
			
		}elseif ($this->setting->guiTheme != $theme){
			
			$this->setting->edit('username', $user);
		}
		
		echo 'Theme was set to '.$f3->get('SESSION.guiTheme');
		
		die();
	}
}