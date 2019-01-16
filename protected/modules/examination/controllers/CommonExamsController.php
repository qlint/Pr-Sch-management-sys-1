<?php
class CommonExamsController extends RController
{
	public function actionIndex()
	{
		$model	= new CommonExams('search');
		$model->unsetAttributes();  // clear any default values
		$this->render('index', array('model'=>$model));
	}
	
	public function actionCreate()
	{
		$model = new CommonExams;		
		if(isset($_POST['CommonExams'])){
			$model->attributes	= $_POST['CommonExams'];			
			if($model->exam_date!=NULL)
				$model->exam_date	= date('Y-m-d', strtotime($_POST['CommonExams']['exam_date']));
				
			$model->created_by	= Yii::app()->user->id;
			$model->created_at	= date('Y-m-d H:i:s');
			$model->batches		= $_POST['batch_id'];
			if($model->validate()){
				// code to save exams
				if($model->save()){
					// generate exam groups
					foreach($model->batches as $batch){						
						$exam_group	= new ExamGroups;
						$exam_group->name				= $model->name;
						$exam_group->batch_id			= $batch;
						$exam_group->common_exam_id		= $model->id;
						$exam_group->exam_type			= $model->exam_type;
						$exam_group->is_published		= $model->is_published;
						$exam_group->result_published	= $model->result_published;
						$exam_group->exam_date			= $model->exam_date;
						$exam_group->save();
					}
					
					Yii::app()->user->setFlash('success', Yii::t('app', 'Exam created successfully'));
					//redirect
					$this->redirect(array('index'));
				}
			}
		}
		
		$this->render('create', array('model'=>$model));
	}
	
	public function actionUpdate($id)
	{
		$model = CommonExams::model()->findByPk($id);
		if($model!=NULL){
			$exam_groups	= ExamGroups::model()->findAllByAttributes(array('common_exam_id'=>$model->id));
			$model->batches	= (count($exam_groups)>0)?CHtml::listData($exam_groups, 'batch_id', 'batch_id'):array();
			
			if(isset($_POST['CommonExams'])){
				$model->attributes	= $_POST['CommonExams'];			
				if($model->exam_date!=NULL)
					$model->exam_date	= date('Y-m-d', strtotime($_POST['CommonExams']['exam_date']));				
				
				$model->batches		= $_POST['batch_id'];
				if($model->validate()){
					// code to save exams
					if($model->save()){
						// generate exam groups
						foreach($model->batches as $batch){
							$exam_group		= ExamGroups::model()->findByAttributes(array('batch_id'=>$batch, 'common_exam_id'=>$model->id));
							if($exam_group==NULL){
								$exam_group	= new ExamGroups;
								$exam_group->batch_id			= $batch;
								$exam_group->common_exam_id		= $model->id;
							}
							$exam_group->name				= $model->name;							
							$exam_group->exam_type			= $model->exam_type;
							$exam_group->is_published		= $model->is_published;
							$exam_group->result_published	= $model->result_published;
							$exam_group->exam_date			= $model->exam_date;
							$exam_group->save();
							
							//remove remaining
							$criteria	= new CDbCriteria;
							$criteria->condition		= '`common_exam_id`=:common_exam_id';
							$criteria->params			= array(':common_exam_id'=>$model->id);
							$criteria->addNotInCondition('`batch_id`', $model->batches);
							$remove_exam_groups	= ExamGroups::model()->findAll($criteria);
							foreach($remove_exam_groups as $exam_group){
								$exam_group->delete();
								$exams 		= Exams::model()->findAllByAttributes(array('exam_group_id'=>$exam_group->id));
								foreach($exams as $exam){
									$exam_scores 	= ExamScores::model()->findAllByAttributes(array('exam_id'=>$exam->id));
									foreach($exam_scores as $exam_score){
										$exam_score->delete();
									}
									$exam->delete();
								}
							}
						}
						
						Yii::app()->user->setFlash('success', Yii::t('app', 'Exam updated successfully'));
						//redirect
						$this->redirect(array('index'));
					}
				}
			}
			
			$this->render('update', array('model'=>$model));
		}
		else{
			throw new CHttpException(404, Yii::t('app', 'Reqested page cannot be found.'));
		}
	}
	
	public function actionDelete($id){
		if(Yii::app()->request->isPostRequest){
			$model	= CommonExams::model()->findByPk($id);
			if($model!=NULL){
				$criteria	= new CDbCriteria;
				$criteria->condition	= '`common_exam_id`=:common_exam_id';
				$criteria->params		= array(':common_exam_id'=>$model->id);
				$remove_exam_groups		= ExamGroups::model()->findAll($criteria);
				foreach($remove_exam_groups as $exam_group){
					$exams 		= Exams::model()->findAllByAttributes(array('exam_group_id'=>$exam_group->id));
					foreach($exams as $exam){
						$exam_scores 	= ExamScores::model()->findAllByAttributes(array('exam_id'=>$exam->id));
						foreach($exam_scores as $exam_score){
							$exam_score->delete();
						}
						$exam->delete();
					}
					
					$exam_group->delete();
				}
				
				$model->delete();
				Yii::app()->user->setFlash('success', Yii::t('app', 'Exam deleted successfully'));
			}
			else{
				throw new CHttpException(404, Yii::t('app', 'Invalid Request.'));
			}
		}
		else{
			throw new CHttpException(404, Yii::t('app', 'Invalid Request.'));
		}
	}
	
	public function actionManage($id){
		$model	= new Exams;
		
		if(isset($_POST['Exams'])){
			$errors			= array();
			// validate subject exams -  start
			$list 			= $_POST['Exams'];
			$count 			= count($list['subject_id']);
			for($i=1;$i<=$count;$i++){				
				if($list['maximum_marks'][$i]!=NULL || $list['minimum_marks'][$i]!=NULL || $list['start_time'][$i]!=NULL || $list['end_time'][$i]!=NULL){	
					$model					= new Exams;
					$model->exam_group_id 	= $list['exam_group_id'][$i];
					$model->subject_id 		= $list['subject_id'][$i];
					$model->maximum_marks 	= $list['maximum_marks'][$i];
					$model->minimum_marks 	= $list['minimum_marks'][$i];
					$model->start_time 		= $list['start_time'][$i];
					$model->end_time 		= $list['end_time'][$i];
					if($model->start_time)
						$model->start_time	= date('Y-m-d H:i',strtotime($model->start_time));
						
					if($model->end_time)
						$model->end_time	= date('Y-m-d H:i',strtotime($model->end_time));
						
					$model->grading_level_id 	= NULL;
					$model->weightage 			= NULL;
					$model->event_id 			= NULL;
					$model->created_at 			= date('Y-m-d H:i:s');
					$model->updated_at 			= date('Y-m-d H:i:s');
					if(!$model->validate()){					   
						//get error from particular model
						foreach($model->getErrors() as $attribute=>$error){
							$key				= "Exams_".$attribute."_".$i;							
							$errors[$key][$i]	= $error[0];
						}
					}										
				}
			}
			// validate subject exams -  end
			
			if(count($errors)>0){
				echo CJSON::encode(array('status'=>'error', 'errors'=>$errors));			
				exit;
			}
			else{
				// save subject exams -  start
				for($i=1;$i<=$count;$i++){				
					if($list['maximum_marks'][$i]!=NULL and $list['minimum_marks'][$i]!=NULL and $list['start_time'][$i]!=NULL and $list['end_time'][$i]!=NULL){
						// check whether subject is form common pool or not
						$subjects				= array();
						$subject				= Subjects::model()->findByPk($list['subject_id'][$i]);
						if($subject!=NULL){
							if($subject->admin_id!=0 and $subject->is_edit==0){	// if subject is from common pool, fetch all subjects with that common pool subject id
								$subjects		= Subjects::model()->findAllByAttributes(array('admin_id'=>$subject->admin_id));
							}
							else{
								$subjects		= array($subject);
							}
						}
						
						foreach($subjects as $subject){							
							$criteria				= new CDbCriteria;
							$criteria->condition	= '`batch_id`=:batch_id AND `common_exam_id`=:common_exam_id';
							$criteria->params		= array(':batch_id'=>$subject->batch_id, ':common_exam_id'=>$_REQUEST['id']);
							$exam_group				= ExamGroups::model()->find($criteria);
							
							$model 					= Exams::model()->findByAttributes(array('exam_group_id'=>$exam_group->id, 'subject_id'=>$subject->id));
							if($model==NULL){
								$model					= new Exams;
								$model->exam_group_id 	= $exam_group->id;
								$model->subject_id 		= $subject->id;
							}
							
							$model->maximum_marks 	= $list['maximum_marks'][$i];
							$model->minimum_marks 	= $list['minimum_marks'][$i];
							$model->start_time 		= $list['start_time'][$i];
							$model->end_time 		= $list['end_time'][$i];
							if($model->start_time)
								$model->start_time	= date('Y-m-d H:i',strtotime($model->start_time));
								
							if($model->end_time)
								$model->end_time	= date('Y-m-d H:i',strtotime($model->end_time));
								
							$model->grading_level_id 	= NULL;
							$model->weightage 			= NULL;
							$model->event_id 			= NULL;
							$model->created_at 			= date('Y-m-d H:i:s');
							$model->updated_at 			= date('Y-m-d H:i:s');
							
							if($model->save()){															
								$subject_name 	= Subjects::model()->findByAttributes(array('id'=>$model->subject_id));
								$examgroup 		= ExamGroups::model()->findByAttributes(array('id'=>$model->exam_group_id));
								$batch 			= Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
								$exam 			= ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
								
								//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
								ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'17',$model->id,$exam,NULL,NULL,NULL); 													
							}
						}
					}
				}
				// save subject exams -  end
				
				echo CJSON::encode(array('status'=>'success'));
				exit;
			}
		}	
		
		$this->render('manage', array('model'=>$model));
	}
	
	public function actionDeleteExam($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model = Exams::model()->findByAttributes(array('id'=>$id));
			$subject_name = Subjects::model()->findByAttributes(array('id'=>$model->subject_id));
			$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$model->exam_group_id));
			$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
			$exam_name = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
			// we only allow deletion via POST request
			
			$exam			= Exams::model()->findByPk($id);
			if($exam!=NULL){
				$exam_scores 	= ExamScores::model()->findAllByAttributes(array('exam_id'=>$exam->id));
				foreach($exam_scores as $exam_score){
					$exam_score->delete();
				}
				$exam->delete();
				Yii::app()->user->setFlash('success', Yii::t('app',"Exam deleted successfully"));
			}
			
			//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
			ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'19',$model->id,$exam_name,NULL,NULL,NULL); 
		}
		else
			throw new CHttpException(400,Yii::t('app','Invalid request. Please do not repeat this request again.'));
	}
	
	public function actionBatches(){
		$response	= array('status'=>'success', 'data'=>Yii::t('app', 'No data found'));
		if(isset($_REQUEST['course_id']) and $_REQUEST['course_id']!=NULL){
			$course_id	= $_REQUEST['course_id'];
			$course		= Courses::model()->findByPk($course_id);
			if($course!=NULL){
				$criteria	= new CDbCriteria;
				$criteria->condition	= '`course_id`=:course_id AND is_active=:one AND is_deleted=:zero';
				$criteria->params		= array(":course_id"=>$course_id, ':one'=>1, ':zero'=>0);
				
				$semester_enabled	= Configurations::model()->isSemesterEnabledForCourse($course_id);
				if($semester_enabled){	// enabled
					$criteria->order	= '`semester_id` ASC';
				}
				if($criteria->order==""){
					$criteria->order		= '`name` ASC';
				}
				else{
					$criteria->order		.= ', `name` ASC';
				}
				
				$batches	= Batches::model()->findAll($criteria);
				
				if(count($batches)>0){
					$data	= $this->renderPartial('_batch', array('batches'=>$batches), true);
					$response['status']	= 'success';
					$response['data']	= $data;
				}
			}
		}
		echo json_encode($response);
		exit;
	}
}