<?php

class TeachersportalModule extends CWebModule
{
	
	public $subjectMaxCharsDisplay = 100;
	public $ellipsis = '...';
	public $allowableCharsSubject = '0-9a-z.,!?@\s*$%#&;:+=_(){}\[\]\/\\-';
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'teachersportal.models.*',
			'teachersportal.components.*',
			'application.modules.portalmailbox.models.*',
			'application.modules.portalmailbox.*',
			
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			$controller->layout='application.views.portallayouts.teachers';
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
	
}
