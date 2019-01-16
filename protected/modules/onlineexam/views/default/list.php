<?php $this->renderPartial('studentleft');?>
<?php
    $student					=	Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    $exam 						= 	ExamScores::model()->findAllByAttributes(array('student_id'=>$student->id));
	$student_visible_fields   	= 	FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
	$settings					=	UserSettings::model()->findByAttributes(array('user_id'=>1));
	$timezone 					= 	Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
	$current_time 				= 	date("Y-m-d h:i:s");  
	
?>
<div class="pageheader">
	<div class="col-lg-8">
        <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Online Exams'); ?><span><?php echo Yii::t('app','View your Online Exams here'); ?></span></h2>
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
    <div class="panel-heading">
      <h3 class="panel-title"><?php echo Yii::t('app','Online Exam List');?></h3>
    </div>
    <div class="people-item">
     <?php 
		$semester_enabled	= Configurations::model()->isSemesterEnabled();
		$batch = Batches::model()->findByPk($_GET['bid']);
		$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
	 
	 ?>
          <table class="table table-hover mb30">
          <thead>
            <tr>
                <th><?php echo Yii::t('app','Course');?></th>
                                    
                <th><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></th>
                
                <?php
				if($semester_enabled == 1){
					if($batch->semester_id!=NULL){
					?>
                    <th><?php echo Yii::t('app','Semester');?></th>  
                    
                    <?php
                    }
				}
				?>
            </tr>
            </thead>
            <tbody>
            	<tr>
                	<td>
                    	<?php 
				  
				  echo ($batch->course123->course_name)?$batch->course123->course_name:"-"; 
				?>
                    </td>
                    <td>
                    	<?php echo ($batch->name)?$batch->name:"-";?>
                    </td>
                    <?php
						if($semester_enabled == 1){
							if($batch->semester_id!=NULL){
							?>
							
							<td><?php echo ($semester->name)?$semester->name:"-";?></td>
							<?php
							}
						}
						?>
                </tr>
            </tbody>
            </table>

        <div class="row">
        <div class="col col-md-3 pull-right">
        <?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>',array('/studentportal/default/exams'),array('class'=>'btn btn-danger pull-right'));?>
        </div>
        </div>
       <br />
         <div class="row">
         <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered mb30" border="0" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><?php echo Yii::t('app','Exam Name');?></th>
                    <th><?php echo Yii::t('app','Start Time');?></th>
                    <th><?php echo Yii::t('app','End Time');?></th>
                    <th><?php echo Yii::t('app','Duration');?></th>
                    <th><?php echo Yii::t('app','Actions');?></th>
                </tr>
                </thead>
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
                                <td><?php $date = date($settings->displaydate,strtotime($online_exam->start_time)); 
										  $time = date($settings->timeformat,strtotime($online_exam->start_time)); 
										  echo $date.' '.$time;?>
                                </td>
                                <td><?php $date = date($settings->displaydate,strtotime($online_exam->end_time)); 
										  $time = date($settings->timeformat,strtotime($online_exam->end_time)); 
										  echo $date.' '.$time;?>
                                </td>
                              	<td><?php $hour = 	floor($online_exam->duration/60);
										  $mins = 	$online_exam->duration%60;
										  if($mins > 0){
								          	echo $hour.' '.'hr'.' '.$mins.' '.'mins';
										  }
										  else{
											  echo $hour.' '.'hr';
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
										
									
										if($online_exam->status == 2){?>
                                            <a class="closed-btnarea">
                                            	<?php echo Yii::t('app','Closed');?>
                                            </a>	
                                  <?php }
								  		if($online_exam->status == 3  and $student_exam == NULL){ ?>
											<a class="closed-btnarea">
                                            	<?php echo Yii::t('app','Not Attended');?>
                                            </a>	
										<?php
										}
										if($online_exam->status != 3  and $student_exam != NULL){ ?>
											<a class="closed-btnarea">
                                            	<?php echo Yii::t('app','Attended');?>
                                            </a>	
										<?php
										}
 										
                                        if(count($question)>0 and $online_exam->status == 1 and $current_time > $online_exam->start_time and  $online_exam->end_time > $current_time and $exam_time <= $endtime  and $student_exam == NULL){
                                                        echo CHtml::link(Yii::t('app','Attend Now'),array('default/attend', 'etoken'=>OnlineExams::model()->encryptToken($online_exam->id),'offset'=>OnlineExams::model()->encryptToken(0)),array( 'class'=>'view-attnt-icon icon-bg'));
                                        }
                                        if(count($short_multi_ques) == 0 and $student_exam->status == 1){
                                                echo CHtml::link(Yii::t('app','View Results'),array('default/result', 'id'=>$online_exam->id),array( 'class'=>'view_Exmintn_atg Exm_aTgColor_y'));
                                                echo ' | ';
                                                echo CHtml::link(Yii::t('app','View Score'),array('default/score', 'key'=>$_REQUEST['bid'],'bid'=>OnlineExams::model()->encryptToken($_REQUEST['bid']), 'etoken'=>OnlineExams::model()->encryptToken($online_exam->id)),array( 'class'=>'view_Exmintn_atg Exm_aTgColor_Exm_score'));
                                                echo ' | ';
                                                echo CHtml::link(Yii::t('app','Answer Key'),array('default/answer', 'id'=>$online_exam->id),array( 'class'=>'view_Exmintn_atg Exm_aTgColor_Exm_anskey'));
                                        }
                                        else{
                                                if($student_exam->status == 1 and $online_exam->status == 3){
                                                        echo CHtml::link(Yii::t('app','View Results'),array('default/result', 'id'=>$online_exam->id),array( 'class'=>'view_Exmintn_atg Exm_aTgColor_y'));
                                                        echo ' | ';
                                                        echo CHtml::link(Yii::t('app','View Score'),array('default/score', 'key'=>$_REQUEST['bid'],'bid'=>OnlineExams::model()->encryptToken($_REQUEST['bid']), 'etoken'=>OnlineExams::model()->encryptToken($online_exam->id)),array( 'class'=>'view_Exmintn_atg Exm_aTgColor_Exm_score'));
                                                        echo ' | ';
                                                        echo CHtml::link(Yii::t('app','Answer Key'),array('default/answer', 'id'=>$online_exam->id),array( 'class'=>'view_Exmintn_atg Exm_aTgColor_Exm_anskey '));
                                                }
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
    </div> 
    <div class="clear"></div>
</div>
