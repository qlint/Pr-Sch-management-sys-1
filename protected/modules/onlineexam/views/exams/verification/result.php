<?php
$this->breadcrumbs=array(
	Yii::t('app','Online Examination') => array('/onlineexam/dashboard'),
	Yii::t('app','Verification'),
);
?>

<?php
$semester_enabled	= Configurations::model()->isSemesterEnabled(); 
$settings			=	UserSettings::model()->findByAttributes(array('user_id'=>1));
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
        $set_flag   =   1;
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
            	<h1><?php echo Yii::t('app','Online Exam Result')." - ". $exam_name;?></h1>
                
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>', array('/onlineexam/exams/'),array('class'=>'a_tag-btn')); ?></li>                                    
</ul>
</div> 
</div>
                
                <?php 
                if($set_flag==1){
                ?>
       
                    <?php
                    if($exam_model->status!=3)
                    {
                        ?> 
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="yellow-bg"><center><p><?php echo Yii::t('app','Exam Result Not Published');?></p></center> </div>
                                </div>
                            </div> 
                        </div>                                                     
                        <?php
                    }
                    else if($exam_model->status==3){
                    ?>
                
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
											 <?php if($semester_enabled == 1){?>
													<th><?php echo Yii::t('app','Semester');?></th>
											 <?php } ?>                             
                                        <td align="center"><?php echo Yii::t('app','Exam Name');?></td>                                 
                                        <td align="center"><?php echo Yii::t('app','Score');?></td>                                                        	
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
												<?php $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($cours->id);?>
												 <?php 
												 if($semester_enabled == 1){
													 if($sem_enabled == 1 and $batc->semester_id != NULL){
															$semester	= Semester::model()->findByAttributes(array('id'=>$batc->semester_id));?>
															<td><?php echo ucfirst($semester->name); ?></td>
											  <?php }
													else{ ?>
														<td><?php echo '-'; ?></td>
											  <?php }
												 }?>                                                                       
                                            <td align="center"><?php echo $exam_name; ?></td>
                                            <td align="center"><?php
                                            $grade_mark     =   0;    
                                            $total_mark     =   OnlineExamQuestions::model()->getTotalScore($exam_id);
                                            $text_score     =   OnlineExamAnswers::getTextScore($data->id, $exam_id, $data->batch_id); //total exam score for short and multi line questions 
                                            $choice_score   =   OnlineExamAnswers::getChoiceScore($data->id, $exam_id, $data->batch_id); //total exam score for multi choice and true/false questions
                                            $result_status  =   OnlineExamStudentAnswers::checkResultStatus($data->id, $exam_id);                                                                       
                                            if((is_numeric($text_score) or is_numeric($choice_score)) && ($result_status==1)) 
                                            {                                             
                                                $tot=0;
                                                if($text_score>=0)
                                                {
                                                    $tot+=$text_score;
                                                }
                                                if($choice_score>=0)
                                                {
                                                    $tot+=$choice_score;
                                                }
                                                $gain_total= floatval($tot);

                                                echo $gain_total." / ".floatval($total_mark); 
                                            }
                                            else if($result_status==3)
                                            {
                                                echo Yii::t('app', 'Verification Not Completed');
                                            }
                                            else
                                            {                                       
                                                echo Yii::t('app', 'Not Attended');
                                            }                                   

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
                
                <?php } }
                else
                {
                    ?>
                            <div class="Not-found">
                                    <p><?php echo Yii::t('app','No Result Found'); ?></p>
                            </div>
                        <?php 
                }
                ?>
                
            </div>
        </td>
    </tr>
</table>        
