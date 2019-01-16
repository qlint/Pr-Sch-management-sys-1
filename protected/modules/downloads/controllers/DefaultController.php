<?php

class DefaultController extends Controller
{
	public function actions()
    {
        return array(
            'upload'=>array(
                'class'=>'xupload.actions.XUploadAction',
                'path' =>Yii::app() -> getBasePath() . "/../uploadss",
                'publicPath' => Yii::app() -> getBaseUrl() . "/uploadss",
            ),
        );
    }
	
	public function actionIndex()
	{
		Yii::import("xupload.models.XUploadForm");
		$model = new XUploadForm;
		$this -> render('index', array('model' => $model, ));
		//$this->render('index');
	}
	public function actionUpload(){
		
	}	
}