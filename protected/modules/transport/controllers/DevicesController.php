<?php

class DevicesController extends RController
{	
	public function filters(){
		return array(
			'rights'
		);
	}
	
	public function actionIndex(){
		$criteria			= new CDbCriteria;
		//$criteria->join		= "JOIN `route_devices` `rd` ON `rd`.`device_id`=`t`.`id`";
		$total 		= Devices::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria);  // the trick is here!		
		$devices	= Devices::model()->findAll($criteria);		
		$this->render('index', array('devices'=>$devices, 'pages' => $pages,'item_count'=>$total,'page_size'=>Yii::app()->params['listPerPage']));
	}
	
	public function actionAssign()
	{
		$model	= new RouteDevices;
		$model->device_id	= (isset($_REQUEST['id']))?$_REQUEST['id']:"";
		if(isset($_POST['RouteDevices'])){
			$model->attributes	= $_POST['RouteDevices'];
			$model->created_by	= Yii::app()->user->id;
			$model->created_at	= date("Y-m-d H:i:s");
			if($model->save())
				$this->redirect(array('index'));
		}
		$this->render('assign', array('model'=>$model));
	}
	
	public function actionUpdate($id)
	{
		$model	= RouteDevices::model()->findByAttributes(array('device_id'=>$id));
		if(isset($_POST['RouteDevices'])){
			$model->attributes	= $_POST['RouteDevices'];
			if($model->save())
				$this->redirect(array('index'));
		}
		
		$this->render('update', array('model'=>$model));
	}
	
	public function actionUnassign($id)
	{
		$model	= RouteDevices::model()->findByAttributes(array('device_id'=>$id));
		if($model!=NULL){
			$model->delete();
		}
		$this->redirect(array('index'));
	}
	
	public function actionApprove($id)
	{
		$model	= RouteDevices::model()->findByAttributes(array('device_id'=>$id));
		if($model!=NULL){
			$model->status	= 1;
			$model->save();
		}
		$this->redirect(array('index'));
	}
	
	public function actionRemove($id)
	{
		$device			= Devices::model()->findByPk($id);		
		$route_device	= RouteDevices::model()->findByAttributes(array('device_id'=>$id));
		if($device!=NULL){
			$device->delete();
		}
		if($route_device!=NULL){
			$route_device->delete();
		}
		
		$this->redirect(array('index'));
	}
}