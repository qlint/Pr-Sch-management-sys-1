<?php

class CoScholasticController extends RController
{
	
	public $layout='//layouts/column2';

	
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
        public function   init() {
             $this->registerAssets();
              parent::init();
 }

        private function registerAssets(){

            Yii::app()->clientScript->registerCoreScript('jquery');

         //IMPORTANT about Fancybox.You can use the newest 2.0 version or the old one
        //If you use the new one,as below,you can use it for free only for your personal non-commercial site.For more info see
		//If you decide to switch back to fancybox 1 you must do a search and replace in index view file for "beforeClose" and replace with 
		//"onClosed"
        // http://fancyapps.com/fancybox/#license
          // FancyBox2
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.css', 'screen');
         // FancyBox
         //Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/fancybox/jquery.fancybox-1.3.4.js', CClientScript::POS_HEAD);
         // Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/fancybox/jquery.fancybox-1.3.4.css','screen');
        //JQueryUI (for delete confirmation  dialog)
         Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/jqui1812/js/jquery-ui-1.8.12.custom.min.js', CClientScript::POS_HEAD);
         Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/jqui1812/css/dark-hive/jquery-ui-1.8.12.custom.css','screen');
          ///JSON2JS
         Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/json2/json2.js');
       

           //jqueryform js
               Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/jquery.form.js', CClientScript::POS_HEAD);
              Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/form_ajax_binding.js', CClientScript::POS_HEAD);
              Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/client_val_form.css','screen');

 }
        
        
        public function actionIndex()
        {
            //check exam type of batch - default/CBSC
            if(isset($_REQUEST['id']) && ExamFormat::model()->getExamformat($_REQUEST['id'])== 2 )
            {
              //  $dataProvider=new CActiveDataProvider('CbscCoScholastic');
                $model=new CbscCoScholastic('search');
                $this->render('index',array(
                        'model'=>$model,
                ));
            }
            else
            {               
                throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
            }
            
        }
        
        
        public function actionReturnForm(){

              //Figure out if we are updating a Model or creating a new one.
             if(isset($_POST['update_id']))$model= $this->loadModel($_POST['update_id']);
			 else $model=new CbscCoScholastic;	
                $cs=Yii::app()->clientScript;
                $cs->scriptMap=array(
                                                 'jquery.min.js'=>false,
                                                 'jquery.js'=>false,
                                                 'jquery.fancybox-1.3.4.js'=>false,
                                                 'jquery.fancybox.js'=>false,
                                                 'jquery-ui-1.8.12.custom.min.js'=>false,
                                                 'json2.js'=>false,
                                                 'jquery.form.js'=>false,
                                                 'form_ajax_binding.js'=>false
        );

		if(isset($_POST['batch_id']))
		{
			$this->renderPartial('_ajax_form', array('model'=>$model,'batch_id'=>$_POST['batch_id']), false, true);
		}
		else
		{
        	$this->renderPartial('_ajax_form', array('model'=>$model), false, true);
		}
      }
    public function loadModel($id)
    {
        $model=CbscCoScholastic::model()->findByPk($id);
        if($model===null)
                throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
        
    public function actionReturnView()
    {
        $cs=Yii::app()->clientScript;
        $cs->scriptMap=array(
            'jquery.min.js'=>false,
            'jquery.js'=>false,
            'jquery.fancybox-1.3.4.js'=>false,
            'jquery.fancybox.js'=>false,
            'jquery-ui-1.8.12.custom.min.js'=>false,
            'json2.js'=>false,
            'jquery.form.js'=>false,
           'form_ajax_binding.js'=>false
        );

        $model=$this->loadModel($_POST['id']);
        $this->renderPartial('view',array('model'=>$model),false, true);
      }
      
      
    public function actionAjax_Create()
    {

               if(isset($_POST['CbscCoScholastic']))
		{
                       $model=new CbscCoScholastic;					   
                        //set the submitted values
                        $model->attributes=$_POST['CbscCoScholastic'];	
                        $this->performAjaxValidation($model);
                        if($model->save(false))
						
                        {
                                echo json_encode(array('success'=>true,'id'=>$model->primaryKey) );
                                exit;
                        }
                        else
                        {
                            echo json_encode(array('success'=>false));
                            exit;
                        }
		}
    }
    
    public function actionAjax_Update()
    {
        if(isset($_POST['CbscCoScholastic']))
        {
            $model=$this->loadModel($_POST['update_id']);  
            $b_id=  $model->batch_id; 
            $model->attributes=$_POST['CbscCoScholastic'];	
            $model->batch_id= $b_id;
            $this->performAjaxValidation($model);			
			
            if($model->save(false))
            {				
                echo json_encode(array('success'=>true));
            }
            else                
                echo json_encode(array('success'=>false));
        }
    }
    
    protected function performAjaxValidation($model)
    {
            if(isset($_POST['ajax']) && $_POST['ajax']==='co_scholastic-form')
            {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
            }
    }
    
    public function actionAjax_delete()
    {
        $id=$_POST['id'];				
        $deleted=$this->loadModel($id);
        
        if ($deleted->delete())
        {
            //delete all entry from score table
            CbscCoscholasticScore::model()->deleteAllByAttributes(array('coscholastic_id'=>$id));
            echo json_encode (array('success'=>true,'msg'=>deleted));
            exit;
        }
        else
        {
            echo json_encode (array('success'=>false));
            exit;
        }
    }
}
?>