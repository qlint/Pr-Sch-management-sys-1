<?php
/**
 * Ajax Crud Administration
 * GradingLevelsController *
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 * @license The MIT License
 */

class CoScholasticController extends RController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

        

	/**
	 * @return array action filters
	 */

        public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}        	
        
        
	
        public function actionIndex()
        {		
		$this->render('index');		
	}
        
        public function actionManage()
        {
            $this->render("manage");
        }
        
        public function actionAdd()
        {
            if(isset($_POST) && $_POST!=NULL)
            {
                $post= $_POST;  
              //  var_dump($post); exit;
                $batch_id= $_POST['batch_id'];
                foreach ($post['data'] as $skill=>$data)
                {
                    foreach ($data as $student_id=>$score)
                    {
                       
                        $student_id= $student_id;
                        $skill_id= $skill;                 
                        $score_model= CbscCoscholasticScore::model()->findByAttributes(array('student_id'=>$student_id,'coscholastic_id'=>$skill_id));
                        if($score_model!=NULL)
                        {
                            
                            $model= CbscCoscholasticScore::model()->findByPk($score_model->id);                            
                            $model->score= $score;                                                       
                            $model->student_id= $student_id;
                            $model->coscholastic_id= $skill;
                            $model->save();
                        }
                        else
                        {
                            $model= new CbscCoscholasticScore;
                            if($score!=NULL)
                            {
                                $model->score= $score;
                                $model->student_id= $student_id;
                                $model->coscholastic_id= $skill;
                                $model->save();
                            }
                        }
                    }
                }
                        
                                                                                          
                }
                echo CJSON::encode(array('status'=>'success','redirect'=>Yii::app()->createUrl('/teachersportal/coScholastic/',array('skill'=>$skill_id,'bid'=>$batch_id))));
		exit;
            
        }



}
