<?php

class DefaultController extends RController
{	
	public function actionIndex()
	{
		//registering js file
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->assetManager->publish(
				Yii::getPathOfAlias('application.modules.export.assets') . '/js/jquery-ui.1.10.4.js'
			)
		);
		
		$models			= Yii::app()->controller->module->allowedModels;
	
		if($models==NULL){
			foreach(glob('./protected/models/*.php') as $filename){
				$modelname			= str_replace(array("./protected/models/", ".php"), "", $filename);
				$models[$modelname]['label']	= $modelname;
			}		
		}		
		
		$modelsArray	= array();
		foreach($models as $mindex=>$smodel){
			$modelsArray[$mindex]	=	isset($smodel['label'])?$smodel['label']:$mindex;
		}
		
		if(isset($_POST['export-database'])){
		
			if(isset($_POST['reqColumns']) and count($_POST['reqColumns'])>0){
				$model	= $_POST['model'];
				$format	= 'csv';
				if(in_array($model, array_keys($modelsArray))){
					$export	=	new Export;
					if(!$export->exportdb($format, $model, $_POST['reqColumns'], isset($_POST['Compare'])?$_POST['Compare']:NULL))
						$this->redirect(array('index'));
				}
				else{
					Yii::app()->user->setFlash('exporterror',Yii::t('app','You are not allowed to access this model !!'));
					$this->redirect(array('index'));
				}
			}
			else{
				Yii::app()->user->setFlash('exporterror',Yii::t('app','Choose columns to export !!'));
				$this->redirect(array('index'));
			}
		}		
		$this->render('index', array('modelsArray'=>$modelsArray));
	}
	
	public function actionAttributes(){
		if(Yii::app()->request->isAjaxRequest and isset($_GET['model'])){
			$response		= array("result"=>"success");
			$model			= $_GET['model'];
			$allowedColumns	= array();
			$almodels	= Yii::app()->controller->module->allowedModels;
			
			$crmodel	= isset($almodels[$model])?$almodels[$model]:array();
			if($crmodel and isset($crmodel['allowedColumns']) and $crmodel['allowedColumns']!='all'){
				$attributes	= $crmodel['allowedColumns'];
			}
			else{
				$table		= $model::model()->tableSchema->name;
				$attributes	= Yii::app()->getDb()->getSchema()->getTable($table)->getColumnNames();					
			}
			
			foreach($attributes as $attribute){
				$allowedColumns[$attribute]	= $model::model()->getAttributeLabel($attribute);
			}			
			$response["data"]	= $allowedColumns;
			
			//check if a render option is there
			if($crmodel and isset($crmodel['render']) and $crmodel['render']!=''){
				$response['render']	= $this->renderPartial('render/'.$crmodel['render'], array('model'=>$model), true);
			}
			
			echo json_encode($response);
			Yii::app()->end();
		}
		else{
			echo json_encode(array('result'=>'failed'));
			Yii::app()->end();
		}
	}
	
	public function actionLoadbatches(){
		$criteria	= new CDbCriteria;
		$criteria->compare('course_id', $_POST['course_id']);
		$criteria->compare('is_deleted', 0);
		$data		= Batches::model()->findAll($criteria);		
		$data		= CHtml::listData($data,'id','name');
		
		echo "<option value=''>Select batch</option>";
		foreach($data as $value=>$batch)
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode($batch),true);
	}
}