<style type="text/css">
.nav-tabs > li{ margin: 2px 0 0 2px;}
</style>

 <div class="panel-heading">          
                <?php
                   $batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
                ?>

                        
               <h3 class="panel-title"><?php echo $batch->coursename;?></h3> 
                
                
             </div> 
             
                    <div class="opnsl_headerBox">
                    <div class="opnsl_actn_box"> </div>
                        <div class="opnsl_actn_box">
                            <div class="opnsl_actn_box1">
        	<?php
		$criteria = new CDbCriteria;
		$criteria->join = 'INNER JOIN  timetable_entries ON t.id =  timetable_entries.employee_id' ;
		$criteria->distinct = true;
		$criteria->condition='uid = :match4';
		$criteria->params[':match4'] = Yii::app()->user->id;
		$teacher= Employees::model()->find($criteria);
		
		
		
		if(isset($teacher) && $teacher!= NULL)
		{
			$batch_array=array();
			$coursearray2=TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$teacher->id));
			foreach($coursearray2 as $coursearray12)
			{
				if(!in_array($coursearray12->batch_id,$batch_array))
				$batch_array[]=$coursearray12->batch_id;
			}
		
		}
			if(count($batch_array) > 1){
			if(Yii::app()->controller->action->id=='exams'||Yii::app()->controller->action->id=='subjects'||Yii::app()->controller->action->id=='create'||Yii::app()->controller->action->id=='update'||Yii::app()->controller->action->id=='trainees'||Yii::app()->controller->action->id=='exam'||Yii::app()->controller->action->id=='timetable'||Yii::app()->controller->action->id=='studentlog'){
							echo CHtml::link(Yii::t('app', 'Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/teachersportal/course'),array('class'=>'addbttn'));	
					}
				}?>
                </div>
                </div>
                
                </div>
