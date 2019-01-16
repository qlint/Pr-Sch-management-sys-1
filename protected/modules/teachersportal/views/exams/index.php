<?php 
$this->pageTitle=Yii::app()->name . ' - '.Yii::t("app", "Profile");
$this->breadcrumbs=array(
	UserModule::t("Profile"),
);

?>
<?php echo $this->renderPartial('/default/leftside');?>
<?php 
	$semester_enabled	= Configurations::model()->isSemesterEnabled(); 
	$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	// Get unique batch ID from Timetable. Checking if the employee is teaching.
	$criteria=new CDbCriteria;
	$criteria->select= 'batch_id';
	$criteria->distinct = true;
	$criteria->condition='employee_id=:emp_id';
	$criteria->params=array(':emp_id'=>$employee->id);
	$batches_id = TimetableEntries::model()->findAll($criteria);
	$teach_count = count($batches_id);
	//echo 'Employee ID: '.$employee->id.'<br/>Teaching in '.count($batches_id).' batch(es)<br/>';
	
	//Get unique batch ID from Batches. Checking if the employee is a class teacher.
	$criteria=new CDbCriteria;
	$criteria->select= 'id';
	$criteria->distinct = true;
	$criteria->condition='employee_id=:emp_id';
	$criteria->params=array(':emp_id'=>$employee->id);
	$class_teacher = Batches::model()->findAll($criteria);
	$class_count = count($class_teacher);
	//echo 'Class Teacher of '.count($class_teacher).' batch(es)';
?>
<div class="pageheader">
      <h2><i class="fa fa-pencil"></i> <?php echo Yii::t("app", "Exams");?> <span><?php echo Yii::t("app", "View online exams here");?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t("app", "You are here:");?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t("app", "Exams");?></li>
        </ol>
      </div>
    </div>
    
    
    <div class="contentpanel">    
    	<div class="panel-heading">        
			<h3 class="panel-title"><?php echo Yii::t('app', 'View Examination Details'); ?></h3>
		</div>
        
        <div class="people-item">
         <?php
				$accademic_year = AcademicYears::model()->findAllByAttributes(array('is_deleted'=> 0));
				$acc_arr	= array();
				foreach($accademic_year as $value){
					$acc_arr[$value->id]	= ucfirst($value->name);
				}
				if(isset($_REQUEST['acc_id']) and $_REQUEST['acc_id'] != NULL){
					$accademic	= AcademicYears::model()->findByPk(array($_REQUEST['acc_id']));
				}
				else{
					$accademic	= AcademicYears::model()->findByAttributes(array('is_deleted'=> 0,'status'=>1));
				}
				
				echo Yii::t('app','Viewing Courses of Academic Year');
				if(count($accademic_year) > 1){
							 echo CHtml::dropDownList('acc_id','',$acc_arr,array('encode'=>false,'prompt'=>Yii::t("app",'Select Academic Year'),'style'=>'width:190px;','onchange'=>'getday()','class'=>'form-control','id'=>'acc_id','options'=>array($accademic->id=>array('selected'=>true))));
				}
				?>
       
<div class="cont_right formWrapper usertable" >

            <div class="opnsl_headerBox">
            <div class="opnsl_actn_box"></div>
            <div class="opnsl_actn_box"> 
            <?php
            if($teach_count > 0 or $class_count > 0){
            $this->renderPartial('exam_tab',array('teach_count'=>$teach_count,'class_count'=>$class_count,'employee_id'=>$employee->id));
            ?>	
            
            </div>
            
            </div>
        

            	<div class="right_col"  id="req_res123">
    <!--contentArea starts Here-->     
                 <div id="parent_rightSect">        
                            <div class="yellow_bx">
                                <?php /*?><div class="y_bx_head" style="font-size:14px;">
                                   &nbsp;
                                </div><?php */?>
                                <?php if($teach_count>0)
								{?>
                                    <h5 class="subtitle"><?php echo Yii::t('app','Tutor Classes'); ?></h5>
                                    <p><?php echo Yii::t('app','Displays all classes exams details.'); ?></p>
                                    <?php
									$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
                                    $criteria=new CDbCriteria;
                                    $criteria->select= 'batch_id';
                                    $criteria->distinct = true;
                                    // $criteria->order = 'batch_id ASC'; Uncomment if ID should be retrieved in ascending order
                                    $criteria->condition='employee_id=:emp_id';
                                    $criteria->params=array(':emp_id'=>$employee->id);
                                    $batches_id = TimetableEntries::model()->findAll($criteria);
                                    if(count($batches_id) >= 1){ // List of batches is needed
                                        $flag = 1;
                                    }
                                    elseif(count($batches_id) <= 0){ // If not teaching in any batch
                                        $flag = 0;
                                        
                                    }
									
									if($flag == 0)
									{ // Displaying message
										?>
										<div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;margin-top:60px;">
											<div class="y_bx_head">
											   <?php echo Yii::t('app','No period is assigned to you now!'); ?>
											</div>      
										</div>
										<?php
										}
										if($flag == 1)
										{ // Displaying batches the employee is teaching.
										?>
											<div class="table-responsive">
											   <table class="table table-bordered mb30">
													<thead>
														<tr class="pdtab-h">
															<th align="center"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></th>
															 <?php if($semester_enabled == 1){?>
																	<th align="center"><?php echo Yii::t('app','Semester');?></th>
														 	 <?php } ?>      
															<th align="center"><?php echo Yii::t('app','Class Teacher');?></th>
															<th align="center"><?php echo Yii::t('app','Actions');?></th>
														</tr>
														</thead>
														<tbody>
														<?php 
														foreach($batches_id as $batchid)
														{
                                                            $teacher_id="";
															$batch			=	Batches::model()->findByAttributes(array('id'=>$batchid->batch_id,'academic_yr_id'=>$accademic->id,'is_active'=>1,'is_deleted'=>0));                                    
															$course 		= 	Courses::model()->findByAttributes(array('id'=>$batch->course_id)); 
															$sem_enabled	= 	Configurations::model()->isSemesterEnabledForCourse($course->id);
															if($batch){
																echo '<tr id="batchrow'.$batch->id.'">';
																/*echo '<td style="text-align:center; padding-left:10px; font-weight:bold;">'.CHtml::link($batch->name, array('/teachersportal/default/employeetimetable','id'=>$batch->id)).'</td>';*/
																echo '<td>'.ucfirst($batch->coursename).'</td>';
																if($semester_enabled == 1){
																	if($sem_enabled == 1 and $batch->semester_id != NULL){
																		$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
																		echo '<td>'.ucfirst($semester->name).'</td>';
																	}
																	else{
																		echo '<td>'.'-'.'</td>';
																	}
																}
																$teacher = Employees::model()->findByAttributes(array('id'=>$batch->employee_id));					
																echo '<td>';
																if($teacher){
																																$teacher_id=$teacher->uid;
																	echo Employees::model()->getTeachername($teacher->id);
																}
																else{
																	echo '-';
																}
																if(ExamFormat::model()->getExamformat($batch->id)==2 && ($teacher_id==Yii::app()->user->id))
																{
																	$exams_published = CbscExamGroups::model()->countByAttributes(array('batch_id'=>$batch->id,'date_published'=>1));
																	$result_published = CbscExamGroups::model()->countByAttributes(array('batch_id'=>$batch->id,'result_published'=>1));
																	
																}
																else
																{
																// Count if any exam timetables are published in a batch.
																$exams_published = ExamGroups::model()->countByAttributes(array('batch_id'=>$batch->id,'is_published'=>1));
																// Count if any exam results are published in a batch.
																$result_published = ExamGroups::model()->countByAttributes(array('batch_id'=>$batch->id,'result_published'=>1));
																}
																echo '<td>';
																
																 $cbsc_format    = ExamFormat::getCbscformat($batch->id);
																 $exam_format	 = ExamFormat::model()->getExamformat($batch->id);// 1=>normal 2=>cbsc																											 			 
																 if($exam_format == 1){
																	if($exams_published > 0 or $result_published > 0){
																		echo CHtml::link(Yii::t('app','View Examinations'), array('/teachersportal/exams/allexams','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_b'));
																		
																	}
																	else{
																		echo '<span class="no_Exam">'.Yii::t('app','No Exam Scheduled').'</span>';
																	}
																	
															    }else if($cbsc_format){//cbsc
																	
																		echo CHtml::link(Yii::t('app','View Examinations'), array('/teachersportal/exam17/allexams','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_b'));
																																			
																	
																}
																else{
																		echo '<span class="no_Exam">'.Yii::t('app','No Exam Scheduled').'</span>';
																		
																	}
                                                                echo CHtml::link(Yii::t('app','Online Exam'), array('/onlineexam/exam','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_g'));
																if(ExamFormat::model()->getExamformat($batch->id)==2 && ($teacher_id==Yii::app()->user->id))
																{
																	
																	echo CHtml::link(Yii::t('app','View Co-scholastic Skills'), array('/teachersportal/coScholastic/index','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_v'));
																	
																	echo CHtml::link(Yii::t('app','View Results'), array('/teachersportal/exams/results','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_y'));
																}
																/*else{
																	echo CHtml::link(Yii::t('app','View Examinations'), array('/teachersportal/exams/allexams','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_y'));
																}*/
																echo '</td>';
																															
																
																
																echo '</tr>';
															}
														}
														?>
													</tbody>
												</table>
											</div>
										<?php
										}
                                 }?>
                                <?php if($class_count>0){ ?> 
                                    <h5 class="subtitle"><?php echo Yii::t('app','My Class'); ?></h5>
                                    <p><?php echo Yii::t('app','View the exams for the class(es) that you are in charge.'); ?></p>
                                    
                                    <?php
									$year = Yii::app()->user->year;
									
									//$batches_id =Batches::model()->findAll("employee_id=:x AND is_active=:y AND is_deleted=:z", array(':x'=>$employee->id,':y'=>1,':z'=>0));
									$batches_id = Batches::model()->findAll('academic_yr_id=:x AND is_deleted=:y AND employee_id=:z AND is_active=1',array(':x'=>$accademic->id,':y'=>0,':z'=>$employee->id));
										if(count($batches_id) >= 1){ // List of batches is needed
											$flag = 2;
										}
										elseif(count($batches_id) <= 0){ // If not teaching in any batch
											$flag = 3;
											
										}
										
										if($flag == 3)
										{ // Displaying message
											?>
											<div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;margin-top:60px;">
												<div class="y_bx_head">
													<?php echo Yii::t('app','No period is assigned to you now!'); ?>
												</div>      
											</div>
											<?php
										}
											if($flag == 2)
											{ // Displaying batches the employee is assigned.
											?>
												<div class="table-responsive">
													<table width="80%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered mb30">
														
														<thead>
															<tr >
																<th ><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></th>
																<?php if($semester_enabled == 1){?>
																	<th><?php echo Yii::t('app','Semester');?></th>
														 	 	<?php } ?>    
																<th ><?php echo Yii::t('app','Class Teacher');?></th>
																<th ><?php echo Yii::t('app','Actions');?></th>
															</tr>
															</thead>
															<tbody>
															<?php 
														
															foreach($batches_id as $batchid)
															{
																$batch			=	Batches::model()->findByAttributes(array('id'=>$batchid->id,'is_active'=>1,'is_deleted'=>0));
																$course 		= 	Courses::model()->findByAttributes(array('id'=>$batch->course_id)); 
																$sem_enabled	= 	Configurations::model()->isSemesterEnabledForCourse($course->id);                                                               $teacher_id="";
																echo '<tr id="batchrow'.$batchid->id.'">'; 
																echo '<td>'.$batchid->coursename.'</td>';
																if($semester_enabled == 1){
																	if($sem_enabled == 1 and $batch->semester_id != NULL){
																		$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
																		echo '<td>'.ucfirst($semester->name).'</td>';
																	}
																	else{
																		echo '<td>'.'-'.'</td>';
																	}
																}
																$teacher = Employees::model()->findByAttributes(array('id'=>$batchid->employee_id));					
																echo '<td>';
																
																if($teacher){
																	$teacher_id= $teacher->uid;
																	echo Employees::model()->getTeachername($teacher->id);
																}
																else{
																	echo '-';
																}
																$batch_id = $batchid->id;																
																if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2) // for cbsc exam format
																{																																$exams_published = CbscExamGroups::model()->countByAttributes(array('batch_id'=>$batchid->id,'date_published'=>1));				
																	$result_published = CbscExamGroups::model()->countByAttributes(array('batch_id'=>$batchid->id,'result_published'=>1));
																}																															 					
																else{ 
																	// Count if any exam timetables are published in a batch.
																	$exams_published = ExamGroups::model()->countByAttributes(array('batch_id'=>$batchid->id,'is_published'=>1));
																	// Count if any exam results are published in a batch.
																	$result_published = ExamGroups::model()->countByAttributes(array('batch_id'=>$batchid->id,'result_published'=>1));
																}
																echo '<td>';
																$cbsc_format    = ExamFormat::getCbscformat($batchid->id);
																$exam_format	 = ExamFormat::model()->getExamformat($batchid->id);// 1=>normal 2=>cbsc
																if($exam_format == 1){
																	if($exams_published > 0 or $result_published > 0){
																	
																		echo CHtml::link(Yii::t('app','View Examinations'), array('/teachersportal/exams/classexams','bid'=>$batchid->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_b'));
																		
																	}
																	else{
																		echo '<span class="no_Exam">'.Yii::t('app','No Exam Scheduled').'</span>';
																		
																	}
																	
																}
																else if($cbsc_format){//cbsc																
																	echo CHtml::link(Yii::t('app','View Examinations'), array('/teachersportal/exam17/allexams','bid'=>$batchid->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_b'));
																																																			
																}
																echo CHtml::link(Yii::t('app','Online Exam'), array('/onlineexam/exam','bid'=>$batchid->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_g'));
																if(ExamFormat::model()->getExamformat($batchid->id)==2 && ($teacher_id==Yii::app()->user->id))
																{
																
																	echo CHtml::link(Yii::t('app','View Co-scholastic Skills'), array('/teachersportal/coScholastic/index','bid'=>$batchid->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_v'));
								
																	echo CHtml::link(Yii::t('app','View Results'), array('/teachersportal/exams/results','bid'=>$batchid->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_y'));
																}
																/*else{
																	echo CHtml::link(Yii::t('app','View Examinations'), array('/teachersportal/exams/classexams','bid'=>$batchid->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_y'));
																}*/
																echo '</td>';
																
																echo '</tr>';
															}
																?>
															</tbody>
														</table>
													</div>
													<?php
                                                    }
                                                    ?>
																			
													<?php } ?>
                                                     <div class="yb_timetable">&nbsp;</div>    
                                                   
                                                    <div class="yb_teacher_timetable">&nbsp;</div>
                                                     
                                                </div>
                                            </div>
                                        </div>	 
                                    <?php		 
                                        }else{
                                    ?>
                                    </div>   
                                    </div>
                        			<div class="clearfix"></div>
                        
                                    
                                    <div class="clearfix"></div>
                                        <div class="y_bx_head" style=" text-align:center;">
                                         <?php echo Yii::t('app','No exam details are available now!'); ?>    
                                    </div>
                                    
                        <?php } ?>		


             
    </div>
 <script>
 $('#acc_id').change(function(ev){
	var acc_id	= $(this).val();
	if(acc_id != ''){
		window.location= 'index.php?r=teachersportal/exams/index&acc_id='+acc_id;
	}
	else{
		window.location= 'index.php?r=teachersportal/exams/index';
	}
});
 </script>
  

