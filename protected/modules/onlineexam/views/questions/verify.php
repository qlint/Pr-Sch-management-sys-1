<style type="text/css">
.nothing-found{
	text-align:center;
	font-style:italic;
}
</style>
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
        //check exam have short / multi line questions
        $status     =   OnlineExamQuestions::model()->checkExamType($exam_id);
        if($status && $exam_model->status==2){
            $set_flag=1;
        }
        $exam_name  =   $exam_model->name; 
    }
    
}


?>

<div id="parent_Sect">
    <?php $this->renderPartial('/default/teacherleft');?>    
    <div class="pageheader">
        <h2><i class="fa fa-pencil"></i> <?php echo Yii::t('app', 'Online Examination');?> <span><?php echo Yii::t('app', 'Verify online exams result here');?></span></h2>
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
            <ol class="breadcrumb">            
                <li class="active"><?php echo Yii::t('app', 'Online Examination');?></li>
            </ol>
        </div>
    </div>
    
    <div class="contentpanel">
        <div class="panel-heading" style="position:relative;">
            <div class="clear"></div>
            <h3 class="panel-title"><?php echo Yii::t('app','Exam Evaluation '); ?> </h3>

        </div>
        
        
        <div class="people-item"> 
            <div class="opnsl_headerBox">
            <div class="opnsl_actn_box"> </div>
            <div class="opnsl_actn_box">
            <div class="opnsl_actn_box1"><?php
            echo CHtml::link(Yii::t('app','Back'),array('/onlineexam/exam','bid'=>$exam_model->batch_id),array('class'=>'btn btn-primary'));
            ?></div>
            </div>
            
            </div>
            <?php 
            if($set_flag==1){
            ?>            
            <div class="tablebx">  
                <div class="pager" style="margin: 0 20px 10px 0;">
                        <?php 
                          $this->widget('CLinkPager', array(
                          'currentPage'=>$pages->getCurrentPage(),
                          'itemCount'=>$item_count,
                          'pageSize'=>$page_size,
                          'maxButtonCount'=>5,
                            'prevPageLabel'=>'< Prev',
                          //'nextPageLabel'=>'My text >',
                          'header'=>'',
                        'htmlOptions'=>array('class'=>'pages'),
                        ));?>
                </div> 
                <div class="clear"></div>
                <div class="table-responsive">                                    
                    <table class="table table-bordered mb30" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr class="tablebx_topbg">
                            <th><?php echo Yii::t('app','Sl. No.');?></th>	
                            <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>                            
                            <th><?php echo Yii::t('app','Student Name');?></th> 
                            <?php } ?>
                            <th><?php echo Yii::t('app','Admission Number');?></th> 
                            <?php if(FormFields::model()->isVisible('batch_id','Students','forStudentProfile')){?>
                                	<th><?php echo Yii::app()->getModule("students")->labelCourseBatch();?></th>
                            <?php } ?>
							 <?php if($semester_enabled == 1){?>
									<th><?php echo Yii::t('app','Semester');?></th>
							 <?php } ?>                                  
                            <th><?php echo Yii::t('app','Exam Name');?></th> 
                            <th><?php echo Yii::t('app','Evaluation Status');?></th> 
                            <th><?php echo Yii::t('app','Manage');?></th> 
                        </tr>
                        </thead>
                        <?php 
                        if(isset($_REQUEST['page'])){
                            $i=($pages->pageSize*$_REQUEST['page'])-9;
                        }else{
                            $i=1;
                        }
                        $cls="even";
                        ?>
                        <?php
                        $date   =   strtotime(date("Y-m-d H:i:s"));                        
                        if($list)
                        { 
                            foreach($list as $data)
                            {
                            ?>
                                <tr class=<?php echo $cls;?>>
                                    <td><?php echo $i; ?></td>
                                    <td><?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                            {
                                                $name='';
                                                $name=  $data->studentFullName('forStudentProfile');
                                                echo $name;
                                            } ?>
                                    </td>
                                    <td><?php echo $data->admission_no; ?></td>
                                    <?php 
                                        $batc = Batches::model()->findByAttributes(array('id'=>$exam_model->batch_id,'is_active'=>1,'is_deleted'=>0)); 
                                        if($batc!=NULL)
                                        {
                                            $cours 			= 	Courses::model()->findByAttributes(array('id'=>$batc->course_id)); 
											$sem_enabled	= 	Configurations::model()->isSemesterEnabledForCourse($cours->id);
                                            if(FormFields::model()->isVisible('batch_id','Students','forStudentProfile'))
                                            {?>
                                            <td><?php echo $cours->course_name.' / '.$batc->name; ?></td> 
                                            <?php } 
                                        }
                                        else{
                                            if(FormFields::model()->isVisible('batch_id','Students','forStudentProfile')){?> 
                                            <td>-</td>
                                            <?php } 
                                            }
                                        ?> 
									<?php 
									if($semester_enabled == 1){
											if($sem_enabled == 1 and $batc->semester_id != NULL){
														$semester	= Semester::model()->findByAttributes(array('id'=>$batc->semester_id)); ?>                                                                      
														<td><?php echo ucfirst($semester->name); ?></td>
											<?php } 
											else{ ?>
											<td><?php echo '-'; ?></td>
											<?php }
									}?>
									<td><?php echo $exam_name; ?></td> 
                                    <td><?php 
                                    $total_qp_count         =   OnlineExamQuestions::model()->getQuestionsCount($exam_id);
                                    $total_verified_count   =   OnlineExamStudentAnswers::model()->getVerifiedAnswerCount($data->id, $exam_id); 
                                    
                                    echo $total_verified_count." / ".$total_qp_count; ?></td>
                                    <td><?php
									$exists     =   OnlineExamStudents::model()->findByAttributes(array('exam_id'=>$exam_id,'student_id'=>$data->id, 'status'=>1));
									
                                    if(isset($_REQUEST['id']) && $_REQUEST['id']!=NULL && $exists!=NULL)
                                    {
                                        echo CHtml::link(Yii::t('app', 'Evaluate'), array('/onlineexam/questions/verifyAnswer','id'=>$data->id,'exam_id'=>OnlineExams::model()->encryptToken($_REQUEST['id']),'offset'=>OnlineExams::model()->encryptToken(0)), array('title'=>Yii::t('app', 'Evaluate Student Answers'),'class'=>'view_Exmintn_atg Exm_aTgColor_r')); 
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
                        <tr>
                            <td colspan="7" class="nothing-found"><?php echo Yii::t('app','Nothing Found'); ?></td>
                        </tr>
                        <?php		
                        }
                        ?>
                    </table>
                </div>
                <div class="pager" style="margin: 0 20px 10px 0;">
                <?php                                          
                  $this->widget('CLinkPager', array(
                  'currentPage'=>$pages->getCurrentPage(),
                  'itemCount'=>$item_count,
                  'pageSize'=>$page_size,
                  'maxButtonCount'=>5,
                                          'prevPageLabel'=>'< Prev',
                  //'nextPageLabel'=>'My text >',
                  'header'=>'',
                'htmlOptions'=>array('class'=>'pages'),
                ));?>
                </div> <!-- END div class="pagecon" 2 -->
                <div class="clear"></div>
            </div>     
            <?php 
            }
            else
            {
                $this->renderPartial('error');
            }
            ?>
        </div> 
        
        
    </div> 
</div> 
<div class="clear"></div>



