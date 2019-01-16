<?php
/**
 * Ajax Crud Administration
 * CommonClassTimingsController *
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 * @license The MIT License
 */

class CommonClassTimingsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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


	/**
	 * @return array action filters
	 */

     /**
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	 */
        
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('returnView'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('ajax_create','ajax_update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','returnForm','ajax_delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ClassTimings::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='class-timings-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


    public function actionIndex(){

		$model=new ClassTimings('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ClassTimings']))
			$model->attributes=$_GET['ClassTimings'];

		$this->render('index',array('model'=>$model));
		
	}

//Create Common Class Timmings
	public function actionCreate()
	{
		$model = new ClassTimings;
        $model->scenario = 'add';
		if(isset($_POST['ClassTimings']) and $_POST['ClassTimings']!=NULL){
			$model->attributes=$_POST['ClassTimings'];
			$model->batch_id = '0';										
			
			if(isset($_POST['ClassTimings']['on_sunday']) and Configurations::model()->timetableFormat()==1){	// if timetable format is fixed
				$model->on_sunday = $model->on_monday = $model->on_tuesday = $model->on_wednesday = $model->on_thursday = $model->on_friday = $model->on_saturday = 0;				
			}
			
			if($model->save()){
				Yii::app()->user->setFlash('success', Yii::t('app', 'Class timing created successfully'));
				//Add Common class timings to all the batch if it is selected
				if(isset($_POST['ClassTimings']['all_batches']) and $_POST['ClassTimings']['all_batches'] == 1){
                                                                       
					$batches = Batches::model()->findAllByAttributes(array('is_active'=>1,'is_deleted'=>0));					
					if($batches){   
                                            
						foreach($batches as $batch)
                                                {                                                   
							$model_1 = new ClassTimings;
							//$model_1->attributes = $model->attributes;                                                        
							$model_1->start_time= $model->start_time;
							$model_1->end_time= $model->end_time;
							$model_1->is_break= $model->is_break;
							$model_1->name= $model->name;
							$model_1->batch_id = $batch->id;
							$model_1->admin_id = $model->id;
                            $model_1->is_edit= 0;
							
							if(isset($_POST['ClassTimings']['on_sunday']) and Configurations::model()->timetableFormat($batch->id)==2){	// if timetable format is flexible for this batch
								$model_1->on_sunday		= $model->on_sunday;
								$model_1->on_monday		= $model->on_monday;
								$model_1->on_tuesday	= $model->on_tuesday;
								$model_1->on_wednesday	= $model->on_wednesday;
								$model_1->on_thursday	= $model->on_thursday;
								$model_1->on_friday		= $model->on_friday;
								$model_1->on_saturday	= $model->on_saturday;
							}
							
							$model_1->save();
						}
					}
				}
				$this->redirect(array('/commonClassTimings'));
			}
		}
		$this->render('create',array('model'=>$model));
	}
	
//Update Common Class Timings
	public function actionUpdate()
	{
		$model = ClassTimings::model()->findByAttributes(array('id'=>$_REQUEST['id']));
       	$model->scenario = 'add';
		if(isset($_POST['ClassTimings']) and $_POST['ClassTimings']!=NULL){
			$old_model	= $model->attributes;var_dump($old_model);
			$old_name = $model->name;
			$old_start_time = $model->start_time;
			$old_end_time = $model->end_time;
			$old_is_break = $model->is_break;						
			$model->attributes=$_POST['ClassTimings'];
			$model->batch_id = '0';
			
			if(isset($_POST['ClassTimings']['on_sunday']) and Configurations::model()->timetableFormat()==1){	// if timetable format is fixed
				$model->on_sunday		= $old_model['on_sunday'];
				$model->on_monday		= $old_model['on_monday'];
				$model->on_tuesday		= $old_model['on_tuesday'];
				$model->on_wednesday	= $old_model['on_wednesday'];
				$model->on_thursday		= $old_model['on_thursday'];
				$model->on_friday		= $old_model['on_friday'];
				$model->on_saturday		= $old_model['on_saturday'];
			}
			
			if($model->save()){
				Yii::app()->user->setFlash('success', Yii::t('app', 'Class timing updated successfully'));
				//Update the chsnges to all the batches that contain non edited common timings 
				if($old_name != $model->name or $old_start_time != $model->start_time or $old_end_time != $model->end_time or $old_is_break != $model->is_break){
					$batch_common_timings = ClassTimings::model()->findAllByAttributes(array('admin_id'=>$model->id,'is_edit'=>0));
					if($batch_common_timings){
						foreach($batch_common_timings as $batch_common_timing){
							$batch_common_timing->name = $model->name;
							$batch_common_timing->start_time = $model->start_time;
							$batch_common_timing->end_time = $model->end_time;
							$batch_common_timing->is_break = $model->is_break;
							
							if(isset($_POST['ClassTimings']['on_sunday']) and Configurations::model()->timetableFormat($batch_common_timing->batch_id)==2){	// if timetable format is flexible for this batch
								$batch_common_timing->on_sunday		= $model->on_sunday;
								$batch_common_timing->on_monday		= $model->on_monday;
								$batch_common_timing->on_tuesday	= $model->on_tuesday;
								$batch_common_timing->on_wednesday	= $model->on_wednesday;
								$batch_common_timing->on_thursday	= $model->on_thursday;
								$batch_common_timing->on_friday		= $model->on_friday;
								$batch_common_timing->on_saturday	= $model->on_saturday;
							}
							
							$batch_common_timing->save();
						}
					}
				}
				$this->redirect(array('/commonClassTimings'));
			}
		}
		$this->render('create',array('model'=>$model));
	}
	public function actionAjax_delete(){
		$id=$_POST['id'];
		$deleted=$this->loadModel($id);
		if ($deleted->delete() ){
			Yii::app()->user->setFlash('success', Yii::t('app', 'Class timing deleted successfully'));
			//Delete all the non edited common class timings of all batches 
			$batch_common_timings = ClassTimings::model()->findAllByAttributes(array('admin_id'=>$id,'is_edit'=>0));
			if($batch_common_timings){
				foreach($batch_common_timings as $batch_common_timing){
					if($batch_common_timing){
						$batch_common_timing->delete();
					}
				}
			}
			echo json_encode (array('success'=>true));
			exit;
		}else{
			echo json_encode (array('success'=>false));
			exit;
		}
	}
}
