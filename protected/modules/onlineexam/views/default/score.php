<?php 
$roles = Rights::getAssignedRoles(Yii::app()->user->Id);
$exam_id     			=   OnlineExams::model()->decryptToken($_REQUEST['etoken']);
$exam					=	OnlineExams::model()->findByAttributes(array('id'=>$exam_id));
$text_score 			=   OnlineExamAnswers::getTextScore($student->id, $exam_id, $bid);
$choice_score 			=   OnlineExamAnswers::getChoiceScore($student->id, $exam_id, $bid);
$total_score 			=   $text_score+$choice_score;
$result_status 		    =   OnlineExamStudentAnswers::checkResultStatus($student->id, $exam_id);
  
$bid = isset($_REQUEST['key'])?$_REQUEST['key']:''; 
if(key($roles) == 'student')
{
	$this->renderPartial('studentleft');
	$onlineexam_link = CHtml::link('<span>'.Yii::t('app','Online Exams').'</span>',array('/onlineexam/default/list', 'bid'=>$exam->batch_id),array('class'=>'online-Exm-btn'));
	$student				=	Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$link    				=   CHtml::link(Yii::t('app','Back'),array('/onlineexam/default/list','bid'=>$bid),array('class'=>'btn btn-primary'));
	
}
if(key($roles) == 'parent')
{
	$this->renderPartial('parentleft');
	$id     				=   OnlineExams::model()->decryptToken($_REQUEST['id']);
	$student				=	Students::model()->findByAttributes(array('id'=>$id));
	$onlineexam_link = CHtml::link('<span>'.Yii::t('app','Online Exams').'</span>',array('/onlineexam/default/exams', 'id'=>$student->id, 'bid'=>$exam->batch_id),array('class'=>'online-Exm-btn'));
	$link 					=   CHtml::link(Yii::t('app','Back'),array('/onlineexam/default/exams','bid'=>$exam->batch_id, 'id'=>$student->id),array('class'=>'btn btn-primary'));
}?>
<div class="pageheader">
	<div class="col-lg-8">
        <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Online Exam Result'); ?><span><?php echo Yii::t('app','View your Online Exam Result here'); ?></span></h2>
    </div>
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
            <ol class="breadcrumb">
            <!--<li><a href="index.html">Home</a></li>-->
            
            <li class="active"><?php echo Yii::t('app','Online Exam Result'); ?></li>
        </ol>
    </div>
    <div class="clearfix"></div>
</div>
 
<div class="contentpanel">
    <div class="panel-heading">
      <h3 class="panel-title"><?php echo Yii::t('app','Online Exam Result');?></h3>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li>
                <?php echo $link;?>
                </li>
                </ul>
                </div>
            </div>
    </div>
    <div class="people-item">
		<div class="row">
        	<div class="col-md-2"></div>
        		<div class="col-md-8">
                <div class="title-exam title-exam-icon"><h3><?php echo Yii::t('app','Exam Name :').' '.ucfirst($exam->name);?></h3></div>
                <table class="Comn-Table-score" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody><tr>
                        <th><?php echo Yii::t('app','Student Name');?></th>
                        <th><?php echo Yii::t('app','Admission No');?></th>
                        <th><?php echo Yii::t('app','Your Mark');?></th>
                        <th><?php echo Yii::t('app','Total mark');?></th>

                    </tr>
                    <tr>
                    <td><?php echo ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);?></td>
                    <td><?php echo $student->admission_no;?></td>
                    <?php   if($result_status==3)
							{ ?>
								<td><?php echo Yii::t('app','Verification Not Completed');?></td>
					 <?php  } 
							else{?>
							<td><?php echo $total_score;?></td>
					  <?Php } ?>
                    
                    <td><?php echo OnlineExamQuestions::model()->getTotalScore($exam_id);?></td>
                    </tr>  
                    </tbody></table>
                    <div class="bck-online-block">
                    	 <?php echo $onlineexam_link;?>
                    </div>
                
                </div>
       <div class="col-md-2"></div>
        </div>
        </div> 
    </div> 
    <div class="clear"></div>
</div>
