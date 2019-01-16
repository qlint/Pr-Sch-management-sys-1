
<?php $this->renderPartial('parentleft');?>
<?php
	$guardian 					= 	Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$students 					= 	Students::model()->findAllByAttributes(array('parent_id'=>$guardian->id));
    $student					=	Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
	$settings					=	UserSettings::model()->findByAttributes(array('user_id'=>1));
	$timezone 					= 	Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
	
?>
<div class="pageheader">
	<div class="col-lg-8">
        <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Online Exams'); ?><span><?php echo Yii::t('app','View your students Online Exams here'); ?></span></h2>
    </div>
    <div class="col-lg-2">
                <?php
                if(count($students)>1) // Show drop down only if more than 1 student present
				{
					$student_list = CHtml::listData($students,'id','studentnameforparentportal');
					if($_REQUEST['bid']!=NULL){
							$batchid = $_REQUEST['bid'];
							
					}
					
				?>
                    <div class="student_dropdown" style="top:15px;">
                        <?php
                        echo CHtml::dropDownList('sid','',$student_list,array('prompt'=>Yii::t('app','Select'),'id'=>'studentid','class'=>'form-control input-sm mb14','style'=>'width:auto;display: inline; margin-left: 7px;','options'=>array($_REQUEST['id']=>array('selected'=>true)),'onchange'=>'getstudent();'));
                        ?>                   
                    </div> <!-- END div class="student_dropdown" -->
            	<?php
				}
				?>
    </div>
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
            <ol class="breadcrumb">
            <!--<li><a href="index.html">Home</a></li>-->
            
            <li class="active"><?php echo Yii::t('app','Online Exams'); ?></li>
        </ol>
    </div>
    <div class="clearfix"></div>
</div>

 <div class="contentpanel">
          <div>
        	<div class="people-item">
              <div class="media">
                    <a href="#" class="pull-left">
                    <?php
                     if($student->photo_file_name!=NULL)
                     { 
					 	$path = Students::model()->getProfileImagePath($student->id);
                        echo '<img  src="'.$path.'" width="100" height="103" class="thumbnail media-object" />';
                    }
                    elseif($student->gender=='M')
                    {
                        echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' width="100" height="103" class="thumbnail media-object" />'; 
                    }
                    elseif($student->gender=='F')
                    {
                        echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.' width="100" height="103" class="thumbnail media-object" />';
                    }
                    ?>
                    </a>
                <div class="media-body">
                  <h4 class="person-name"><?php echo $student->studentFullName('forParentPortal');?></h4>
                  <?php 
				  $student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
				  	$batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id, 'result_status'=>0));
				  if(FormFields::model()->isVisible('batch_id','Students','forParentPortal')){ ?>
                  <?php if(count($batchstudents)>1){ 
							echo CHtml::link('View Course Details', array('/parentportal/default/course', 'id'=>$student->id));
						}
						else{?>	
							  <?php if(in_array('batch_id', $student_visible_fields)){ ?>      
							  <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
								<?php 
								  $batch = Batches::model()->findByPk($batchstudents[0]['batch_id']);
								  $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
								  echo ($batch->course123->course_name)?$batch->course123->course_name:"-";
								  $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
								?>
							  </div>          
							  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo ($batch->name)?$batch->name:"-";?></div>
							  <?php } ?>
							  <?php 
							  $semester_enabled		= Configurations::model()->isSemesterEnabled();   
							  $sem_enabled			= Configurations::model()->isSemesterEnabledForCourse($batch->course_id);
							  if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ ?>
										<div class="text-muted"> <strong><?php echo Yii::t('app','Semester').' :';?></strong> <?php echo ($semester->name)?$semester->name:"-";?></div>
								<?php } ?>
					<?php } ?>
				  
				<?php  } ?>     
                  <div class="text-muted"><strong>
				  	<?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?>
                  </div>
                  
                </div>
              </div>
            </div>
    
    <div class="panel-heading">
      <h3 class="panel-title"><?php echo Yii::t('app','Online Exam List');?></h3>
    </div>
    <div class="people-item">
   	 <?php $semester_enabled	= Configurations::model()->isSemesterEnabled();?>
          <table class="table table-hover mb30">
            <tr>
                <th><?php echo Yii::t('app','Course');?></th><th>:</th>
                <th>
                 <?php 
				  $batch = Batches::model()->findByPk($_GET['bid']);
				  $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
				  echo ($batch->course123->course_name)?$batch->course123->course_name:"-"; 
				?>
                </th>                        
                <th><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></th><th>:</th>
                <th><?php echo ($batch->name)?$batch->name:"-";?></th>
                <?php
				if(isset($semester_enabled) and $semester_enabled==1){
					if($batch->semester_id!=NULL){
					?>
                    <th><?php echo Yii::t('app','Semester');?></th><th>:</th>  
                    <th><?php echo ($semester->name)?$semester->name:"-";?></th>
                    <?php
                    }
				}
				?>
            </tr>
            </table>

        <div class="row">
        <div class="col col-md-3 pull-right">
        <?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>',array('/parentportal/default/exams'),array('class'=>'btn btn-danger pull-right'));?>
        </div>
        </div>
       <br />
         <div class="row">
         <div class="col-md-12">
        <div class="table-responsive">
            <table class="Comn-Table" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th><?php echo Yii::t('app','Exam Name');?></th>
                    <th><?php echo Yii::t('app','Start Time');?></th>
                    <th><?php echo Yii::t('app','End Time');?></th>
                    <th><?php echo Yii::t('app','Duration');?></th>
                    <th><?php echo Yii::t('app','Actions');?></th>
                </tr>
                    <?php
                    if($online_exams==NULL)
                    {
                    	echo '<tr><td align="center" colspan="5"><i>'.Yii::t('app','No Online Exams').'</i></td></tr>';	
                    }
                    else
                    {
						if(isset($_REQUEST['page'])){
						$i=($pages->pageSize*$_REQUEST['page'])-9;
						}
						else{
							$i=1;
						}
						foreach ($online_exams as $online_exam)
                        {?>
                            <tr>
                                <td><?php echo ucfirst($online_exam->name);?></td>
                                <td><?php 
										  $date_time  = Configurations::model()->convertDateTime($online_exam->start_time); 
										  echo $date_time;?>
                                </td>
                                <td><?php 
										  $date_time  = Configurations::model()->convertDateTime($online_exam->start_time); 
										  echo $date_time;
								?>
                                </td>
                              	<td><?php $hour = 	floor($online_exam->duration/60);
										  $mins = 	$online_exam->duration%60;
										  if($mins > 0){
								          	echo $hour.' '.Yii::t('app','hr').' '.$mins.' '.Yii::t('app','mins');
										  }
										  else{
											  echo $hour.' '.Yii::t('app','hr');
										  }?>
                               </td>
                                <td><?php 	$criteria 				= 	new CDbCriteria();
											$criteria->condition 	= 	'exam_id=:exam_id and is_deleted = :is_deleted';
											$criteria->params 		= 	array(':exam_id'=>$online_exam->id,':is_deleted'=>0);
											$criteria->addInCondition('question_type',array(3,4));
											$short_multi_ques 		= OnlineExamQuestions::model()->findAll($criteria); //total no of short and multi line questions
								
											$student_exam 		= 	OnlineExamStudents::model()->findByAttributes(array('exam_id'=>$online_exam->id, 'student_id'=>$student->id));
											$question 			= 	OnlineExamQuestions::model()->findAllByAttributes(array('exam_id'=>$online_exam->id));
											$exam_time			= 	date("Y-m-d H:i:s", strtotime("+".$online_exam->duration." minutes"));
											$current_time 		= 	date("Y-m-d H:i:s");
											$endtime 			=   date("Y-m-d H:i:s", strtotime($online_exam->end_time));
									
										if($online_exam->status == 1 and $student_exam == NULL){?>
                                            <a class="closed-btnarea">
                                            	<?php echo Yii::t('app','Open');?>
                                            </a>	
                                  <?php }
									   if($online_exam->status == 2){?>
												<a class="closed-btnarea">
													<?php echo Yii::t('app','Closed');?>
												</a>	
								 <?php }
										if((count($short_multi_ques) == 0 and $student_exam->status == 1 and $online_exam->status == 3) or ($online_exam->status == 3  and $student_exam->status == 1)){
										echo CHtml::link(Yii::t('app','View Results'),array('default/result', 'id'=>$online_exam->id, 'sid'=>OnlineExams::model()->encryptToken($student->id)),array( 'class'=>'view_Exmintn_atg Exm_aTgColor_y'));
										echo ' | ';
										echo CHtml::link(Yii::t('app','View Score'),array('default/score', 'bid'=>OnlineExams::model()->encryptToken($_REQUEST['bid']),'etoken'=>OnlineExams::model()->encryptToken($online_exam->id), 'id'=>OnlineExams::model()->encryptToken($student->id)),array( 'class'=>'view-ttl-score-icon icon-bg'));		
										}
										if($online_exam->status == 3  and $student_exam == NULL){ ?>
											<a class="closed-btnarea">
                                            	<?php echo Yii::t('app','Not Attend');?>
                                            </a>	
										<?php
										}
									    ?>
                                </td>
                            </tr>
                 	<?php $i++;
				 		}
                    }
                    ?>    
                </table>
            </div>
            </div>
            </div>
            <div class="row">
            	<div class="col-md-12">
            <div class="pagination-block pull-right pagination-top">
            <div class="paging_full_numbers clearfix ">
                <?php                                          
                  $this->widget('CLinkPager', array(
                  'currentPage'=>$pages->getCurrentPage(),
                  'itemCount'=>$item_count,
                  'pageSize'=>$page_size,
                  'maxButtonCount'=>5,
                  'prevPageLabel'=>'< Prev',
                  'header'=>'',
                'htmlOptions'=>array('class'=>'pages'),
                ));?>
             </div> <!-- END div class="pagecon" 2 -->
             </div>
             </div></div>
             <div class="clear"></div>
        </div> 

   
    <div class="clear"></div>
</div>
<script>
	function getstudent() // Function to see student profile
	{
		var studentid 		= $('#studentid').val();
		if(studentid!='')
		{
			
			window.location= 'index.php?r=onlineexam/default/exams&id='+studentid+'&bid='+"<?php echo $_REQUEST['bid']?>"+'&page='+"<?php echo $_REQUEST['page']?>";	
		}
		else
		{
			window.location= 'index.php?r=onlineexam/default/exams';
		}
	}
</script>