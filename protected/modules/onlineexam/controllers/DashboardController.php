<?php

class DashboardController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
	public function actionIndex()
	{
            $roles = Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
            foreach($roles as $role)
            if(sizeof($roles)==1 and $role->name == 'Admin'){

                $this->render('index');
            }		
            else{
                //
            }
	}
}