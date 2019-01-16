<style>

.exp_but { right:-31px; margin:0px 2px !important;}
.ui-menu .ui-menu-item a{ color:#000 !important;}
.ui-menu .ui-menu-item a:hover{ color:#fff !important;}
.ui-autocomplete{box-shadow: 0 0 6px #d6d6d6;}
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Report')=>array('/report'),
	Yii::t('app','Student Assessment Report'),
);

$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
$student_name	= '';
$student_id		= '';
 if(isset($student) and $student!=NULL)
{
	$details		= Students::model()->findByAttributes(array('id'=>$student,'is_deleted'=>0,'is_active'=>1));
	$student_name	= $details->studentFullName("forStudentProfile");
	$student_id		= $student;
}
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-form',
	'enableAjaxValidation'=>false,
)); ?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('left_side');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Semester Assessment Report');?></h1>
                <div class="formCon">
                    <div class="formConInner">
<div class="txtfld-col-box">
<div class="txtfld-col">
<?php echo Yii::t('app','Name');?>
<div style="position:relative;">
                                    <?php  $this->widget('zii.widgets.jui.CJuiAutoComplete',
                                    array(
                                    'name'=>'name',
                                    'id'=>'name_widget',
									'value'=>$student_name,
                                    'source'=>$this->createUrl('/site/autocomplete'),
                                    'htmlOptions'=>array('placeholder'=>Yii::t('app','Student Name'),'style'=>'width:180px;'),
                                    'options'=>
                                    array(
										'showAnim'=>'fold',
										'select'=>"js:function(student, ui) {
											 $('#id_widget').val(ui.item.id);
											 var id	=	$('#id_widget').val();
											 $.ajax({
												'url': '".CController::createUrl("selectSemester")."',
      											'type': 'GET',
												data: 'id='+id,
												success: function(data){
													 $('#semester_id').html(data)
													 $('#batch_id').find('option').not(':first').remove();
												}
												});
										}"	
                                    ),
                                    
                                    ));
                                    ?>
                                    <?php echo CHtml::hiddenField('student_id',$student_id,array('id'=>'id_widget')); ?>
                                    <?php echo CHtml::ajaxLink('',array('/site/explorer','widget'=>'sem_ex'),array('update'=>'#explorer_handler'),array('id'=>'explorer_student_name','class'=>'exp_but-n'));?>
                                    </div>
</div>
<div class="txtfld-col">
<?php echo Yii::t('app','Semester');?>
<div>
										<?php 
										$sem_id='';
										if(isset($semid))
											$sem_id=$semid;
											
										$semester_data	=array();
										if(isset($student) and $student!=NULL){
											$stu_batchs=BatchStudents::model()->findAllByAttributes(array("student_id"=>$student)); 
											$datas=array();
											
											foreach($stu_batchs as $stu_batch)
											{
												$b_id	=	$stu_batch->batch_id;
												$batch	=	Batches::model()->findByPk($b_id);
												$sem	=	Semester::model()->findByPk($batch->semester_id);
												if(isset($sem) and $sem!=NULL){
												if(!in_array($sem->id,$data))
													$data[]=$sem->id;
												}
											
											}
											$criteria=new CDbCriteria;
											$criteria->addInCondition('id',$data);

											$semester_data	=	CHtml::listData(Semester::model()->findAll($criteria),'id','name');
										}
											
										echo CHtml::dropDownList('semester_id','',
												$semester_data,												
												array(
												'prompt'=>Yii::t('app','Select Semester'),
												'encode'=>false,
												'options' => array($semid=>array('selected'=>true)),
													'ajax' => array(
													'type'=>'GET', //request type
													'data'=>array('semid'=>'js:this.value',
																'sid'=>'js:$("#id_widget").val()'),
													'url'=>CController::createUrl('selectSemBatch'), //url to call.
													'update'=>'#batch_id', //selector to update
												))
												); ?>
                                    </div>
</div>
<div class="txtfld-col">
<?php echo Yii::t('app','Batch');?>
<div>
										<?php 
										$batch_id='';
										
										if(isset($bid))
											$batch_id=$bid;
											
										$batch_data	=array();
										if(isset($semid) and $semid!=NULL){
											$criteria = new CDbCriteria;
											$criteria->select = 't.id, t.name,t.course_id';
											$criteria->join = ' LEFT JOIN `batch_students` AS `b` ON t.id = b.batch_id';
											$criteria->condition = 't.semester_id = :semester_id AND b.student_id=:student_id';
											$criteria->params = array(":semester_id" => $semid,":student_id"=>$student);
											$batches_d = Batches::model()->findAll($criteria);
											foreach($batches_d as $batch_d)
											{
												$course   		  =    Courses::model()->findByPk($batch_d->course_id);
												$batch_data[$batch_d->id] = $batch_d->name.' ('.$course->course_name.')';
											}
										}
										echo CHtml::dropDownList('batch_id','',$batch_data,array('empty' => Yii::t('app','Select Batch'),'encode'=>false,'options' => array($batch_id=>array('selected'=>true)),)); ?>
                                    </div>
</div>
</div>                  
<div style="margin-top:10px;"><?php echo CHtml::submitButton( Yii::t('app','Search'),array('name'=>'search','class'=>'formbut')); ?></div>
                    </div> <!-- END div class="formConInner" -->
                </div> <!--  END div class="formCon" -->
                <br />
                
                
                <?php
                //if(isset($_REQUEST['flag']) and $_REQUEST['flag']==1)
				if($flag==1)
                {
                	echo '<div class="listhdg" align="center">'.Yii::t('app','Invalid search! Please enter a student name ,Semester and Batch.').'</div>';	
                }
                else
                {
                }
                if(isset($list))
                {
					$details=Students::model()->findByAttributes(array('id'=>$student,'is_deleted'=>0,'is_active'=>1));
					$batch=Batches::model()->findByAttributes(array('id'=>$bid,'is_deleted'=>0));
					$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
					$semester=Semester::model()->findByPk($semid);
					
					?>
					<h3><?php echo Yii::t('app','Student Information');?></h3>
					<div class="tablebx">  
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="tablebx_topbg">
                            	<td><?php echo Yii::t('app','Admission No');?></td>
                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                <td><?php echo Yii::t('app','Student Name');?></td>
                                <?php } ?>
                                <td><?php echo Yii::t('app','Course');?></td>
                                <?php if(in_array('batch_id', $student_visible_fields)){ ?>
                                <td><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></td>
                                <?php } ?>
                                <td><?php echo Yii::t('app','Semester');?></td>
                            </tr>
                            <tr>
                            	<td><?php echo $details->admission_no; ?></td>
                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                <td style="padding:10px;"><?php echo CHtml::link($details->studentFullName("forStudentProfile"),array('/students/students/view','id'=>$details->id)); ?></td>
                                <?php }?>
                                <td>
                                	<?php 
									if($course->course_name!=NULL)
										echo $course->course_name;
									else
										echo '-';
									?>
                                </td>
                                <?php if(in_array('batch_id', $student_visible_fields)){ ?>
                                <td>
									<?php 
									if($batch->name!=NULL)
										echo $batch->name;
									else
										echo '-';
									?>
								</td>
                                <td>
									<?php 
									if($semester->name!=NULL)
										echo $semester->name;
									else
										echo '-';
									?>
								</td>
                                
                                <?php } ?>
                            </tr>
                        </table>
					</div> <!-- END div class="tablebx" Student Information -->
                    <br /><br />
					<h3><?php echo Yii::t('app','Semester Assessment Report');?></h3>
                    <?php
					$criteria=new CDbCriteria;
					$criteria->condition = "batch_id=:batch_id";
					$criteria->params = array(":batch_id"=>$batch->id);
					$criteria->order = 'id DESC';
					$examgroups = ExamGroups::model()->findAll($criteria); // Selecting exam groups in the batch of the student
					if($examgroups!=NULL) // If exam groups present
					{
						$i = 1;
						foreach($examgroups as $examgroup) 
						{
							$flag1=0;
						?>
                                    <?php
										$exams = Exams::model()->findAll('exam_group_id=:x',array(':x'=>$examgroup->id)); // Selecting exams(subjects) in an exam group
										if($exams!=NULL)
										{
											
									?>
                                            <br />
                                    <span style="float:left;"><h4><?php echo $i.'. '.ucfirst($examgroup->name); $i++;?></h4></span>
                                    <?php
									
                                    foreach($exams as $exam)
											{
												$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
												if($subject!=NULL) // Checking if exam for atleast subject is created.
												{ 
													$score = ExamScores::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student));
													if($score!=NULL)
													{
														$flag1=1;
													}
												}
											}
									?>
                                    <?php 
									if($flag1==1)
									{ ?>
                                    <span style="float:right"><?php echo CHtml::link(Yii::t('app', 'Generate PDF'), array('/report/default/semesterexampdf','exam_group_id'=>$examgroup->id,'id'=>$student,'bid'=>$bid,'semid'=>$semid),array('target'=>"_blank",'class'=>'pdf_but')); ?></span>
                                    <?php
									}
									?>
                                    <!-- Single Exam Table -->
                                    <?php $grouptype=ExamGroups::model()->findByAttributes(array('id'=>$examgroup->id,'result_published'=>1)); ?>
                                    <div class="tablebx" style="clear:both"> 
                                    <div class="os-table">
									<div class="tbl-grd"></div> 
                                        <table class="table table-bordered2" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <thead>
                                         <tr>
                                                 <th rowspan="3" colspan="2" class="header-td1" width="400"><?php echo Yii::t('app','Subject');?></th>
                                                <th  width="100"class="header-td1"><?php echo Yii::t('app','Score');?></th>
                                                <th width="100"><?php echo Yii::t('app','Status');?></th> 
                                                 <th width="100"><?php echo Yii::t('app','Remarks');?></th>            
                                         </tr>
                                         </thead>	
                                         <tbody>
                                        <?php
										$status	=0;
										if($exams!=NULL)
										{ 
											foreach($exams as $exam)
											{
												$min 	 = $exam->minimum_marks;
												$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
												if($subject!=NULL) // Checking if exam for atleast subject is created.
												{
												$score = ExamScores::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student,));
												if($score!=NULL)
													{
														 if($score->marks<$min){
															$status = 1;
														 }
														 else
															$status = 0;
																if($subject->split_subject == 1){?>
                                                                 	<tr>
                                                                         <td style="text-align:left;padding-left:50px" rowspan="3" class="header-td1">
                                                                          <?php 
																			if($subject->name!=NULL){
																				if($subject->elective_group_id==0){
																					echo ($subject->name!=NULL)? ucfirst($subject->name):'-';
																				}else{
																					$electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student, 'elective_group_id'=>$subject->elective_group_id));
																					$elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
																					echo (($subject->name!=NULL)? ucfirst($subject->name).' (':'').$elective->name.(($subject->name!=NULL)?')':'');
																					}
																			}
																			else
																			continue;
																			
																			$subject_cps	=	ExamScoresSplit::model()->findAllByAttributes(array('exam_scores_id'=>$score->id));
																			$mark_value=array();
																			foreach($subject_cps as $subject_cp){
																				$mark_value[]=$subject_cp->mark;
																			} 
																			$subjects_splits	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$exam->subject_id));  
																			$sub_value=array();
																			foreach($subjects_splits as $subjects_split){
																				$sub_value[]=$subjects_split->split_name;
																			}  
																			
																	?>
                                                                         </td>
                                                                        <td style="width: 130px" class="header-td1"><?php echo $sub_value[0];?></td>
                                                                        <td><?php echo $mark_value[0];?></td> 
                                                                        <td rowspan="3"><span style="color:#006600"><?php  
																			if($status  == 0){
																				echo "<span style='color:#006600'>".Yii::t('app','Passed').$roles."</span>";
																			}else{
																				echo "<span style='color:#F00'>".Yii::t('app','Failed')."</span>";
																			}
																		?></span></td> 
                                                                        <td rowspan="3"><span><?php
														   if($score->remarks!=NULL)
														   {
															   echo $score->remarks;
														   }
														   else
														   {
															   echo '-';
														   }
														   ?></span></td>             
                                                                 </tr>
                                                                     <tr>           
                                                                        <td  class="header-td1"><?php echo $sub_value[1];?></td>
                                                                        <td><?php echo $mark_value[1];?></td>
                                                                        
                                                                 </tr>
                                                                 
                                                                     <tr>           
                                                                        <td  class="header-td1"><?php echo Yii::t('app','Total');?></td>
                                                                        <td><?php
														$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$batch->id),array('order'=>'min_score DESC'));
									
														if(!$grades)
														{
															$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL));	
														}
							 							 $t = count($grades);
														 if($examgroup->exam_type == 'Marks') {  
														 echo $score->marks; } 
														  else if($examgroup->exam_type == 'Grades') {
														  	
														   foreach($grades as $grade)
																{
																	
																 if($grade->min_score <= $score->marks)
																	{	
																		$grade_value =  $grade->name;
																	}
																	else
																	{
																		$t--;
																		
																		continue;
																		
																	} 
																echo $grade_value ;
																break;
																
																}
																if($t<=0) 
																	{
																		$glevel = " No Grades" ;
																	} 
																
																} 
														   else if($examgroup->exam_type == 'Marks And Grades'){
															 foreach($grades as $grade)
																{
																	
																 if($grade->min_score <= $score->marks)
																	{	
																		$grade_value =  $grade->name;
																	}
																	else
																	{
																		$t--;
																		
																		continue;
																		
																	}
																echo $score->marks . " & ".$grade_value ;
																break;
																
																	
																} 
																if($t<=0) 
																	{
																		echo $score->marks." & ".Yii::t('app',"No Grades") ;
																	}
																 } 
														?></td>
                                                                          
                                                                 </tr> 
                                                                 <?php
														}else{ ?>
                                                          <tr>
                                                             <td colspan="2" style="text-align:left;padding-left:50px">
                                                             <?php 
																if($subject->name!=NULL){
																	if($subject->elective_group_id==0){
																		echo ($subject->name!=NULL)? ucfirst($subject->name):'-';
																	}else{	 
																		$electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student, 'elective_group_id'=>$subject->elective_group_id));
																		$elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
																		echo (($subject->name!=NULL)? ucfirst($subject->name).' (':'').$elective->name.(($subject->name!=NULL)?')':'');
																		}
																}
																else
																continue;
														?>
                                                             </td>
                                                             <td>
                                                             <?php
														$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$batch->id),array('order'=>'min_score DESC'));
									
														if(!$grades)
														{
															$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL));	
														}
							 							 $t = count($grades);
														 if($examgroup->exam_type == 'Marks') {  
														 echo $score->marks; } 
														  else if($examgroup->exam_type == 'Grades') {
														  	
														   foreach($grades as $grade)
																{
																	
																 if($grade->min_score <= $score->marks)
																	{	
																		$grade_value =  $grade->name;
																	}
																	else
																	{
																		$t--;
																		
																		continue;
																		
																	} 
																echo $grade_value ;
																break;
																
																}
																if($t<=0) 
																	{
																		$glevel = " No Grades" ;
																	} 
																
																} 
														   else if($examgroup->exam_type == 'Marks And Grades'){
															 foreach($grades as $grade)
																{
																	
																 if($grade->min_score <= $score->marks)
																	{	
																		$grade_value =  $grade->name;
																	}
																	else
																	{
																		$t--;
																		
																		continue;
																		
																	}
																echo $score->marks . " & ".$grade_value ;
																break;
																
																	
																} 
																if($t<=0) 
																	{
																		echo $score->marks." & ".Yii::t('app',"No Grades") ;
																	}
																 } 
														?>
                                                             </td>
                                                             <td><?php  
                                                            if($status  == 0){
                                                                echo "<span style='color:#006600'>".Yii::t('app','Passed').$roles."</span>";
                                                            }else{
                                                                echo "<span style='color:#F00'>".Yii::t('app','Failed')."</span>";
                                                            }
                                                        ?></td>
                                                             <td> <?php
														   if($score->remarks!=NULL)
														   {
															   echo $score->remarks;
														   }
														   else
														   {
															   echo '-';
														   }
														   ?></td>
                                                          </tr> 
                                                        <?php
														}
													}
												}
											}
										}
									 ?>
                                          
                                            
                                    </tbody>
                                </table>
                                </div>     
                            		</div>
                                    <?php
										}
									?>
                            <!-- END Single Exam Table -->	
						<?php
						
						} // END foreach($examgroups as $examgroup)
					}
					else // If no exam groups present in the batch of the student
					{
						echo '<div class="listhdg" align="center">'.Yii::t('app','No exam details available!').'</div>';	
					}
				
                } //END isset($list)
                ?>
                <div class="clear"></div>
            </div> <!-- End div class="cont_right" -->
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>