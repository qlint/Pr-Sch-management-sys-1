<?php
/**
 * Ajax Crud Administration
 * ClassTimingController *
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 * @license The MIT License
 */

class ClassTimingController extends RController
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

     public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
        
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('returnView','convertTime'),
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

        //AJAX CRUD

         public function actionReturnView(){

               //don't reload these scripts or they will mess up the page
                //yiiactiveform.js still needs to be loaded that's why we don't use
                // Yii::app()->clientScript->scriptMap['*.js'] = false;
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

             public function actionReturnForm(){

              //Figure out if we are updating a Model or creating a new one.
             if(isset($_POST['update_id']))$model= $this->loadModel($_POST['update_id']);else $model=new ClassTimings;
            //  Comment out the following line if you want to perform ajax validation instead of client validation.
            //  You should also set  'enableAjaxValidation'=>true and
            //  comment  'enableClientValidation'=>true  in CActiveForm instantiation ( _ajax_form  file).


             //$this->performAjaxValidation($model);

               //don't reload these scripts or they will mess up the page
                //yiiactiveform.js still needs to be loaded that's why we don't use
                // Yii::app()->clientScript->scriptMap['*.js'] = false;
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


        $this->renderPartial('_ajax_form', array('model'=>$model), false, true);
      }

        	public function actionIndex(){

		$model=new ClassTimings('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['id']))
			$model->batch_id=$_GET['id'];

		$this->render('index',array('model'=>$model));
		
	}


        public function actionAjax_Update(){
		if(isset($_POST['ClassTimings']))
		{
            $model=$this->loadModel($_POST['update_id']);
			$old_name = $model->name;
			$old_start_time = $model->start_time;
			$old_end_time = $model->end_time;
			$old_is_break = $model->is_break;		  
			$model->attributes=$_POST['ClassTimings'];
			$this->performAjaxValidation($model);
			if( $model->save(false)){
				//check whether any change occur in the common class timings
				if($model->admin_id!=0 and ($old_name != $model->name or $old_start_time != $model->start_time or $old_end_time != $model->end_time or $old_is_break != $model->is_break)){
					$model->saveAttributes(array('is_edit'=>1));				
				}
			
			echo json_encode(array('success'=>true));
			 }else
			 echo json_encode(array('success'=>false));
		}

}


  public function actionAjax_Create(){

               if(isset($_POST['ClassTimings']))
			   {
				    $model=new ClassTimings;
				    //set the submitted values
					$model->attributes=$_POST['ClassTimings'];
				    //return the JSON result to provide feedback.
					if($model->save(false)){
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
	public function convertTime($time)

 		{
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings!=NULL)
				{	
					$time1=date($settings->timeformat,strtotime($time));
		
				}
					echo $time1;
	 
 }
	public function actionAjax_delete(){
		$id=$_POST['id'];
		$deleted=$this->loadModel($id);
		if ($deleted->delete() ){
			Yii::app()->user->setFlash('success', Yii::t('app', 'Class timing deleted successfully'));
			$timetables=TimetableEntries::model()->findAllByAttributes(array('class_timing_id'=>$id));
			if($timetables){
				foreach($timetables as $timetable){
					$subwises=StudentSubjectwiseAttentance::model()->deleteAllByAttributes(array('timetable_id'=>$timetable->id));	
				}
			}
			echo json_encode (array('success'=>true));
			exit;
		}else{
			echo json_encode (array('success'=>false));
			exit;
		}
	}
	  
	 //Create Common Class Timmings
	public function actionCreate()
	{
		$model = new ClassTimings;
                $model->scenario = 'add';
		if(isset($_POST['ClassTimings']) and $_POST['ClassTimings']!=NULL){						
			$model->attributes=$_POST['ClassTimings'];
			if(isset($_POST['ClassTimings']['start_time']) and $_POST['ClassTimings']['start_time']!=NULL)
				$model->start_time = date("h:i A",strtotime($_POST['ClassTimings']['start_time']));
			if(isset($_POST['ClassTimings']['end_time']) and $_POST['ClassTimings']['end_time']!=NULL)
				$model->end_time = date("h:i A",strtotime($_POST['ClassTimings']['end_time']));
			$model->batch_id = $_REQUEST['id'];									
			if($model->save()){				
				Yii::app()->user->setFlash('success', Yii::t('app', 'Class timing created successfully'));
				$this->redirect(array('/courses/classTiming','id'=>$_REQUEST['id']));
			}
		}
		$this->render('create',array('model'=>$model));
	}

//Update Common Class Timings
	public function actionUpdate()
	{// var_dump($_POST['ClassTimings']);exit;
		$model = ClassTimings::model()->findByAttributes(array('id'=>$_REQUEST['time_id']));
                $model->scenario = 'add';
		if(isset($_POST['ClassTimings']) and $_POST['ClassTimings']!=NULL){
			$old_name = $model->name;
			$old_start_time = $model->start_time;
			$old_end_time = $model->end_time;
			$old_is_break = $model->is_break;							
			$model->attributes=$_POST['ClassTimings'];
			if(isset($_POST['ClassTimings']['start_time']) and $_POST['ClassTimings']['start_time']!=NULL)
				$model->start_time = date("h:i A",strtotime($_POST['ClassTimings']['start_time']));
			if(isset($_POST['ClassTimings']['end_time']) and $_POST['ClassTimings']['end_time']!=NULL)
				$model->end_time = date("h:i A",strtotime($_POST['ClassTimings']['end_time']));
			$model->batch_id = $_REQUEST['id'];									
			if($model->save()){
				
				if(isset($_POST['ClassTimings']['on_sunday']) and $_POST['ClassTimings']['on_sunday'] == 0)
				{
					 $timetable = TimetableEntries::model()->findAllByAttributes(array('weekday_id'=>1,'batch_id'=>$_REQUEST['id'],'class_timing_id'=>$_REQUEST['time_id']));
					 if($timetable)
					 {
						foreach($timetable as $timetab)
						{
						$timetab->delete(); 
						}
					 }
				}
				if(isset($_POST['ClassTimings']['on_monday']) and$_POST['ClassTimings']['on_monday'] == 0)
				{
					$timetable = TimetableEntries::model()->findAllByAttributes(array('weekday_id'=>2,'batch_id'=>$_REQUEST['id'],'class_timing_id'=>$_REQUEST['time_id']));
					 if($timetable)
					 {
						foreach($timetable as $timetab)
						{
						$timetab->delete(); 
						}
					 }
				}
				if(isset($_POST['ClassTimings']['on_tuesday']) and$_POST['ClassTimings']['on_tuesday'] == 0)
				{
					$timetable = TimetableEntries::model()->findAllByAttributes(array('weekday_id'=>3,'batch_id'=>$_REQUEST['id'],'class_timing_id'=>$_REQUEST['time_id']));
					if($timetable)
					 {
						foreach($timetable as $timetab)
						{
						$timetab->delete(); 
						}
					 }
				}
				if(isset($_POST['ClassTimings']['on_wednesday']) and$_POST['ClassTimings']['on_wednesday'] == 0)
				{
					$timetable = TimetableEntries::model()->findAllByAttributes(array('weekday_id'=>4,'batch_id'=>$_REQUEST['id'],'class_timing_id'=>$_REQUEST['time_id']));
					 if($timetable)
					 {
						foreach($timetable as $timetab)
						{
						$timetab->delete(); 
						}
					 }
				}
				if(isset($_POST['ClassTimings']['on_thursday']) and$_POST['ClassTimings']['on_thursday'] == 0)
				{
					$timetable = TimetableEntries::model()->findAllByAttributes(array('weekday_id'=>5,'batch_id'=>$_REQUEST['id'],'class_timing_id'=>$_REQUEST['time_id']));
					 if($timetable)
					 {
						foreach($timetable as $timetab)
						{
						$timetab->delete(); 
						}
					 }
				}
				if(isset($_POST['ClassTimings']['on_friday']) and$_POST['ClassTimings']['on_friday'] == 0)
				{
					$timetable = TimetableEntries::model()->findAllByAttributes(array('weekday_id'=>6,'batch_id'=>$_REQUEST['id'],'class_timing_id'=>$_REQUEST['time_id']));
					if($timetable)
					 {
						foreach($timetable as $timetab)
						{
						$timetab->delete(); 
						}
					 }
				}
				if(isset($_POST['ClassTimings']['on_saturday']) and$_POST['ClassTimings']['on_saturday'] == 0)
				{
					$timetable = TimetableEntries::model()->findAllByAttributes(array('weekday_id'=>7,'batch_id'=>$_REQUEST['id'],'class_timing_id'=>$_REQUEST['time_id']));
					 if($timetable)
					 {
						foreach($timetable as $timetab)
						{
						$timetab->delete(); 
						}
					 }
				}
				Yii::app()->user->setFlash('success', Yii::t('app', 'Class timing updated successfully'));
				//check whether any change occur in the common class timings
				if($model->admin_id!=0 and ($old_name != $model->name or $old_start_time != $model->start_time or $old_end_time != $model->end_time or $old_is_break != $model->is_break)){
					$model->saveAttributes(array('is_edit'=>1));				
				}
				$this->redirect(array('/courses/classTiming','id'=>$_REQUEST['id']));
			}
		}
		$this->render('update',array('model'=>$model));
	}
}
