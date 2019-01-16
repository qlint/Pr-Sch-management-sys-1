<?php
$this->breadcrumbs=array(
	Yii::t('app','Online Examination') => array('/onlineexam/dashboard'),
	Yii::t('app','Verification'),
);
?>

<?php 
$settings	=	UserSettings::model()->findByAttributes(array('user_id'=>1));
$semester_enabled	= Configurations::model()->isSemesterEnabled();
if($settings!=NULL)
{
    $date_format    =   $settings->displaydate;
    $time_format    =   $settings->timeformat;    
}
else
{
    $date_format    =   'm-d-Y';
    $time_format    =   'H:i:s';
}
$exam_name='';
$set_flag=0;
if(isset($_REQUEST['id']) && $_REQUEST['id']!=NULL)
{
    $exam_id    =   $_REQUEST['id'];   
    $exam_model =   OnlineExams::model()->findByPk($exam_id);  
    if($exam_model!=NULL)
    {
        //check exam have short / multi line questions
        $status     =   OnlineExamQuestions::model()->checkExamType($exam_id);
        if($status && $exam_model->status==2){
            $set_flag=1;
        }
        $exam_name  =   $exam_model->name; 
        $exam_batch_id  =   $exam_model->batch_id;
    }
    
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top"><?php $this->renderPartial('/default/admin_left');?></td>
    	<td valign="top">
        	<div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Online Exam Verification')." - ".$exam_name;?></h1>
                
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>', array('/onlineexam/exams'),array('class'=>'a_tag-btn')); ?></li>                                    
</ul>
</div> 
</div>
       
                <div class="pdtab_Con" style="width:100%">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                            <tr class="pdtab-h">
                                 <td height="18" align="center"><?php echo Yii::t('app','Sl. No.');?></td>	
                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>                            
                                <td align="center"><?php echo Yii::t('app','Student Name');?></td> 
                                <?php } ?>
                                <td align="center"><?php echo Yii::t('app','Admission Number');?></td> 
                                <?php if(FormFields::model()->isVisible('batch_id','Students','forStudentProfile')){?>
                                            <td align="center"><?php echo Yii::app()->getModule("students")->labelCourseBatch();?></td>
                                    <?php } ?>   
								<?php if($semester_enabled==1){ ?>
									  <td align="center"><?php echo Yii::t('app','Semester');?></td>
								<?php } ?>                          
                                <td align="center"><?php echo Yii::t('app','Exam Name');?></td> 
                                <td align="center"><?php echo Yii::t('app','Evaluation Status');?></td> 
                                <td align="center"><?php echo Yii::t('app','Manage');?></td>                                                        	
                            </tr>
                            <?php
                            if(isset($_REQUEST['page'])){
                                    $i=($pages->pageSize*$_REQUEST['page'])-9;
                            }
                            else{
                                    $i=1;
                            }
                            if($list)
                            {
                                foreach($list as $data)
                                {
                                ?>
                                <tr class=<?php echo $cls;?>>
                                    <td align="center"><?php echo $i; ?></td>
                                    <td align="center"><?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                            {
                                                $name='';
                                                $name=  $data->studentFullName('forStudentProfile');
                                                echo $name;
                                            } ?>
                                    </td>
                                    <td align="center"><?php echo $data->admission_no; ?></td>
                                    <?php 
                                        $batc = Batches::model()->findByAttributes(array('id'=>$exam_batch_id,'is_active'=>1,'is_deleted'=>0));
										$sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($batc->course_id);   
                                        if($batc!=NULL)
                                        {
                                            $cours = Courses::model()->findByAttributes(array('id'=>$batc->course_id)); 
                                            if(FormFields::model()->isVisible('batch_id','Students','forStudentProfile'))
                                            {?>
                                            <td align="center"><?php echo $cours->course_name.' / '.$batc->name; ?></td> 
                                            <?php } 
                                        }
                                        else{
                                            if(FormFields::model()->isVisible('batch_id','Students','forStudentProfile')){?> 
                                            <td align="center">-</td>
                                            <?php } 
                                            }
                                        ?>
										<?php if($semester_enabled==1){?>
											<td align="center">
											<?php if($sem_enabled==1 and $batc->semester_id!=NULL){
													$semester 	= Semester::model()->findByAttributes(array('id'=>$batc->semester_id));
													echo $semester->name;
												  }
												  else{
													echo '-';
												  }?>
											</td>
									<?php }?>                                                                        
                                    <td align="center"><?php echo $exam_name; ?></td>
                                    <td align="center"><?php 
                                    $total_qp_count         =   OnlineExamQuestions::model()->getQuestionsCount($exam_id);
                                    $total_verified_count   =   OnlineExamStudentAnswers::model()->getVerifiedAnswerCount($data->id, $exam_id); 
                                    
                                    echo $total_verified_count." / ".$total_qp_count; ?></td>
                                    <td align="center"><?php 
                                    $exists     =   OnlineExamStudentAnswers::model()->exists('exam_id = :exam_id AND student_id=:student_id',array('exam_id'=>$exam_id,'student_id'=>$data->id));
                                    if(isset($_REQUEST['id']) && $_REQUEST['id']!=NULL && $exists)
                                    {
                                        echo CHtml::link(Yii::t('app', 'Evaluate'), array('/onlineexam/exams/verifyAnswer','id'=>$data->id,'exam_id'=>OnlineExams::model()->encryptToken($_REQUEST['id']),'offset'=>OnlineExams::model()->encryptToken(0)), array('title'=>Yii::t('app', 'Evaluate Student Answers'),'class'=>'add-Ans-icon icon-bg')); 
                                    }
                                    else
                                        echo "-";
                                    ?></td>     
                                </tr>
                                <?php
                                if($cls=="even"){
                                    $cls="odd" ;
                                }else{
                                    $cls="even"; 
                                }
                                $i++;
                                }
                            }
                            else{
                            ?>
                                <td colspan="6" style="text-align:center; font-style:italic;"><?php echo Yii::t('app','Nothing Found!'); ?></td>
                            <?php								
                            }
                            ?>                            
                        </tbody>
                    </table>        
                </div>
            <div class="pagecon">
				<?php                                          
                $this->widget('CLinkPager', array(
                'currentPage'=>$pages->getCurrentPage(),
                'itemCount'=>$item_count,
                'pageSize'=>$page_size,
                'maxButtonCount'=>5,						
                'header'=>'',
                'htmlOptions'=>array('class'=>'pages'),
                ));?>
			</div>	
                
            </div>
        </td>
    </tr>
</table>        
