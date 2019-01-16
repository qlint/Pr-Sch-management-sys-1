<?php 
$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
if($batch!=NULL)
{
?>

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    

<li> <?php             
                if((Yii::app()->controller->id=='exams' and Yii::app()->controller->action->id=='create'))
                {
                    echo CHtml::link("<span>".Yii::t("app", "Back")."</span>", array('/examination/exam','id'=>$_REQUEST['id']), array('class'=>'a_tag-btn'));
                }
                else if(((Yii::app()->controller->action->id=='examScores' or Yii::app()->controller->action->id=='examScoresSplit') and Yii::app()->controller->action->id=='create'))
                {                     
                    $exam_id	=	$_GET['examid'];
                    $exam=Exams::model()->findByPk($exam_id);
                    echo CHtml::link("<span>".Yii::t("app", "Back")."</span>", array('/examination/exams/create','exam_group_id'=>$exam->exam_group_id,'id'=>$_REQUEST['id']), array('class'=>'a_tag-btn')); 
                }
            ?></li>
<li>
    <?php
                                if((Yii::app()->controller->id=='gradingLevels' and Yii::app()->controller->action->id=='index') or (Yii::app()->controller->id=='exam17' and Yii::app()->controller->action->id=='index'))
                                {
                                    $rurl = explode('index.php?r=',Yii::app()->request->getUrl());
                                    $rurl = explode('&id=',$rurl[1]);
                                    echo CHtml::ajaxLink(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/site/explorer','widget'=>'2','rurl'=>$rurl[0]),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'a_tag-btn','style'=>'right:80px;')); 
                                }
                                else if(!Yii::app()->controller->id=='coScholastic')
                                {
                                    echo CHtml::ajaxLink(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/site/explorer','widget'=>'2','rurl'=>'examination/exam'),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'a_tag-btn','style'=>'right:80px;'));
                                }   
                                
                                if((Yii::app()->controller->id=='coScholastic' and (Yii::app()->controller->action->id=='index' or Yii::app()->controller->action->id=='manage')))
                                {
                                                    $rurl = explode('index.php?r=',Yii::app()->request->getUrl());
                                                    $rurl = explode('&id=',$rurl[1]);
                                                    echo CHtml::ajaxLink(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/site/explorer','widget'=>'2','rurl'=>$rurl[0]),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'a_tag-btn','style'=>'right:80px;')); 
                                }
                                ?>       
</li>
            
<li><?php echo CHtml::link('<span>'.Yii::t('app','close').'</span>',array('/examination'),array('class'=>'sb_but_close-atndnce')); ?></li>                                    
</ul>
</div> 

</div>

<div class="clear"></div>

<div class="formCon">
	<div class="attnd-tab-inner-blk">
<div class="exam-table">
	<table border="0" cellpadding="0" cellspacing="0p" width="100%">
    	<thead>
        <tr>
        	<th class="course-icon"><?php echo Yii::t('app', 'Course'); ?></th>
                <?php 
                        if(Configurations::model()->isSemesterEnabledForCourse($batch->course_id)){?>
            <th class="semester-icon"><?php echo Yii::t('app', 'Semester'); ?> </th>
                        <?php } ?>
            <th class="batch-icon"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id") ?></th>
            <?php 
            if((Yii::app()->controller->id=='exam17' and (Yii::app()->controller->action->id=='examScores' or Yii::app()->controller->action->id=='examScoresSplit')))
            {                                                                       
                if((isset($_REQUEST['examid']) && $_REQUEST['examid']!=NULL) or (isset($_REQUEST['exam_group_id']) && $_REQUEST['exam_group_id']!=NULL))
                {?>
                    <th class="exam-icon"><?php echo Yii::t('app', 'Exam'); ?></th>
                                
          
            <th class="class-icon"><?php echo Yii::t('app', 'Class'); ?></th>
               <?php }  
			   } ?>       
        </tr>   
        </thead>
        <tbody>
        	<tr>
                    <td>
                        <?php
                        $course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
                        if($course!=NULL)
                        {
                            echo $course->course_name; 
                        }
                        ?>
                    </td>
                    <?php 
                        if(Configurations::model()->isSemesterEnabledForCourse($batch->course_id)){?>
                    <td>
                        <?php                         
                            echo ($batch->semester!=NULL)?$batch->semester->name:' - ';                        
                        ?>
                    </td>
                        <?php } ?>
                    <td>
                        <?php echo $batch->name; ?>
                    </td>
                    <?php 
                   if((Yii::app()->controller->id=='exam17' and (Yii::app()->controller->action->id=='examScores' or Yii::app()->controller->action->id=='examScoresSplit')))
                    {                                                                       
                        if(isset($_REQUEST['examid']) && $_REQUEST['examid']!=NULL)
                        {?>
                        <td>
                            <?php   
							$exam       = CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
							if(isset($_REQUEST['exam_group_id'])){
								$exam_group = CbscExamGroup17::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id']));
							}else{
								$exam_group = CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
							}
							echo ucfirst($exam_group->name);
							 ?>
                        </td>
                        <td><?php echo CbscExamGroup17::model()->getClassName($exam_group->class); ?></td>
                    <?php  } 
					} 
                    ?>
                       
            </tr>
            
            
        </tbody>	
    </table>
    </div>

    
    <div class="exam-table-blk">
<table border="0" cellpadding="0" cellspacing="0p" width="100%">
    <thead>
        <tr>
            <td> 
                <?php 
                 if((Yii::app()->controller->id=='exam17' and (Yii::app()->controller->action->id=='examScores' or Yii::app()->controller->action->id=='examScoresSplit')))
				 { ?>
                <table border="0" cellpadding="0" cellspacing="0p" width="36%" class="exam-table-gris">
                    <thead>
                        
                                <?php 
                                    if((Yii::app()->controller->id=='exam17' and (Yii::app()->controller->action->id=='examScores' or Yii::app()->controller->action->id=='examScoresSplit')))
                                    {

                                        if(isset($_REQUEST['examid']) && $_REQUEST['examid']!=NULL)
                                        {
                                            $exam_id    =   $_REQUEST['examid'];
                                            $exam_model =   CbscExams17::model()->findByPk($exam_id);                                                                                                                     
                                            if($exam_model!=NULL)
                                            {                                                
                                                $sel                    =   (isset($_REQUEST['examid']))?$_REQUEST['examid']:'';
                                                $exam_group_id          =   $exam_model->exam_group_id;
                                                $criteria               =   new CDbCriteria;
                                                $criteria->join         =   'JOIN subjects su ON su.id = t.subject_id';                            
                                                $criteria->condition    =   't.exam_group_id=:exam_group_id AND su.is_deleted=0 AND su.cbsc_common=0';
                                                $criteria->params       =   array(':exam_group_id'=>$exam_group_id);
                                                $exams                  =   CbscExams17::model()->findAll($criteria);
                                                $data                   =   CHtml::listData($exams, 'id', 'subname');
                                                ?>
                                                <tr>
                                                    <td width="40%" class="change-sub">
                                                        <p><?php echo Yii::t('app', 'Change Subject'); ?></p>
                                                    </td>
                                                    <td width="60%">    
                                                    <?php
                                                        echo CHtml::dropDownList('id','',$data,array('onchange'=>'newSubject()','id'=>'new_exam_id','options'=>array($sel=>array('selected'=>true))));
                                                    ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                ?>                                                               
                              
                    </thead>
                </table>
                <?php  } ?>
            </td>
        </tr> 
    </thead>
</table>
</div>	
    </div>
    </div>

<br />



<?php 
}
?>

<script>
    function newSubject()
    { 
	    var exam_id =   $('#new_exam_id').val();
		if(exam_id == '')
			exam_id=0;
        window.location.href=   "<?php echo $this->createUrl(Yii::app()->controller->action->id); ?>"+'&examid='+exam_id+'&id='+<?php echo $_REQUEST['id']; ?>;        
    }
</script>