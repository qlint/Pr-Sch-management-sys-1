<?php 
$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
if($batch!=NULL)
{
?>

    <div class="formCon">
        <div class="formConInner">
            <?php echo Yii::t('app','Course').' : ';?>
            <?php 
			$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
            if($course!=NULL)
            {
            	echo $course->course_name; 
            }
			?>
			
			<?php
			if(Configurations::model()->isSemesterEnabledForCourse($batch->course_id)){	// semester enabled or not
				/*$criteria		= new CDbCriteria;
				$criteria->join	= 'JOIN `semester_courses` `sc` ON `sc`.`semester_id`=`t`.`id`';
				$criteria->condition	= '`t`.`id`=:semester_id AND`sc`.`course_id`=:course_id';
				$criteria->params		= array(':semester_id'=>$batch->semester_id, ':course_id'=>$batch->course_id);
				$semester		= Semester::model()->find($criteria);*/
			?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <?php echo Yii::t('app', 'Semester').' : ';?><?php echo ($batch->semester!=NULL)?$batch->semester->name:' - '; ?>
            <?php
			}
            ?>
            
            &nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' : '; ?><?php echo $batch->name; ?>
            
            <?php
			if(Yii::app()->controller->id=='exams')
			{
				$exam_group = ExamGroups::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id']));
			?>				
				<br /><br /><?php echo Yii::t('app','Examination').' : '; ?><?php echo ucfirst($exam_group->name); 
				if(Yii::app()->controller->action->id=='update')
				{
					$exam = Exams::model()->findByAttributes(array('id'=>$_REQUEST['sid']));
					$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
				?>
                	&nbsp;&nbsp;&nbsp;&nbsp; <?php echo Yii::t('app','Subject').' : '; ?><?php echo $subject->name;
                
				}
			}
			?>
            
            <?php
			if((Yii::app()->controller->id=='gradingLevels' and Yii::app()->controller->action->id=='index') or (Yii::app()->controller->id=='exam' and Yii::app()->controller->action->id=='index'))
            {
				$rurl = explode('index.php?r=',Yii::app()->request->getUrl());
				$rurl = explode('&id=',$rurl[1]);
				echo CHtml::ajaxLink(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/site/explorer','widget'=>'2','rurl'=>$rurl[0]),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'sb_but','style'=>'right:80px;')); 
            }
			else
            {
            	echo CHtml::ajaxLink(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/site/explorer','widget'=>'2','rurl'=>'examination/exam'),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'sb_but','style'=>'right:80px;'));
            }
			echo CHtml::link('<span>'.Yii::t('app','close').'</span>',array('/examination'),array('class'=>'sb_but_close','style'=>'right:40px;'));
            ?>
                        
                        <?php
			if((Yii::app()->controller->id=='coScholastic' and (Yii::app()->controller->action->id=='index' or Yii::app()->controller->action->id=='manage')))
                        {
                                            $rurl = explode('index.php?r=',Yii::app()->request->getUrl());
                                            $rurl = explode('&id=',$rurl[1]);
                                            echo CHtml::ajaxLink(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/site/explorer','widget'=>'2','rurl'=>$rurl[0]),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'sb_but','style'=>'right:80px;')); 
                        }
                        else
                        {
                           // echo CHtml::ajaxLink(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/site/explorer','widget'=>'2','rurl'=>'examination/coScholastic'),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'sb_but','style'=>'right:80px;'));
                        }
                                    echo CHtml::link('<span>'.Yii::t('app','close').'</span>',array('/examination'),array('class'=>'sb_but_close','style'=>'right:40px;'));
                        ?>
            <br>
             <br> <br>
            <?php 
                if((Yii::app()->controller->id=='examScores' and Yii::app()->controller->action->id=='create'))
                {
                                                                       
                    if(isset($_REQUEST['examid']) && $_REQUEST['examid']!=NULL)
                    {
                        $exam_id    =   $_REQUEST['examid'];
                        $exam_model =   Exams::model()->findByPk($exam_id);                                                                                                                     
                        if($exam_model!=NULL)
                        {
                            $group= ExamGroups::model()->findByPk($exam_model->exam_group_id);
                            if($group)
                            {
                                echo Yii::t('app','Exam Group')." : ". $group->name."<br><br>";
                            }
                            $sel                    =   (isset($_REQUEST['examid']))?$_REQUEST['examid']:'';
                            $exam_group_id          =   $exam_model->exam_group_id;
                            $criteria               =   new CDbCriteria;
                            $criteria->join         =   'JOIN subjects su ON su.id = t.subject_id';                            
                            $criteria->condition    =   't.exam_group_id=:exam_group_id AND su.is_deleted=0';
                            $criteria->params       =   array(':exam_group_id'=>$exam_group_id);
                            $exams                  =   Exams::model()->findAll($criteria);
                            $data                   =   CHtml::listData($exams, 'id', 'subname');
                            echo Yii::t('app','Change Subject').' : ';    
                            echo CHtml::dropDownList('id','',$data,array('empty'=>Yii::t('app','Select'),'onchange'=>'newSubject()','id'=>'new_exam_id','options'=>array($sel=>array('selected'=>true))));
                        }
                    }
                }
            ?>
        </div>
    </div> 

<?php 
}
?>

<script>
    function newSubject()
    {
        var exam_id =   $('#new_exam_id').val();
        window.location.href=   "<?php echo $this->createUrl('create'); ?>"+'&examid='+exam_id+'&id='+<?php echo $_REQUEST['id']; ?>;        
    }
</script>