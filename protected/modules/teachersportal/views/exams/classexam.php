

<?php echo $this->renderPartial('/default/leftside');?>
<div class="pageheader">
      <h2><i class="fa fa-pencil"></i> <?php echo Yii::t("app", "Exams");?> <span><?php echo Yii::t("app", "View your exams here");?></span></h2>
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
    		<h3 class="panel-title"><?php echo Yii::t('app', 'My Class(es) Exam Details'); ?></h3>


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
    <div class="opnsl_headerBox">
                <div class="opnsl_actn_box"> </div>
                    <div class="opnsl_actn_box">
                        <div class="opnsl_actn_box1"><?php echo CHtml::link('<span>'.Yii::t('app','All Classes').'</span>',array('/teachersportal/exams/allexam'),array('class'=>'btn btn-primary'));?></div>
                    </div>
                </div>
	<?php 
	$semester_enabled	= Configurations::model()->isSemesterEnabled(); 
    if($_REQUEST['id']!=NULL){
			
	 }
	else{
		// Get batch ID from Batches
		$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		//$batches_id=Batches::model()->findAll("employee_id=:x AND is_active=:y AND is_deleted=:z", array(':x'=>$employee->id,':y'=>1,':z'=>0));
		$batches_id = Batches::model()->findAll('academic_yr_id=:x AND is_deleted=:y AND employee_id=:z AND is_active=1',array(':x'=>$accademic->id,':y'=>0,':z'=>$employee->id));
		
		if(count($batches_id) >= 1){ // List of batches is needed
			$flag = 1;
		}
		elseif(count($batches_id) <= 0){ // If not teaching in any batch
			$flag = 0;
			
		}
	}
	
	
	if($flag == 0){ // Displaying message
	?>
    <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;margin-top:60px;">
        <div class="y_bx_head">
            <?php echo Yii::t('app','No period is assigned to you now!'); ?>
        </div>      
    </div>
    <?php
	}
	if($flag == 1){ // Displaying batches the employee is assigned.
	?>
    	<div class="table-responsive">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered mb30">
                
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
					foreach($batches_id as $batch_id)
					{

						echo '<tr id="batchrow'.$batch_id->id.'">'; 
						echo '<td>'.$batch_id->coursename.'</td>';
						$batch			=	Batches::model()->findByAttributes(array('id'=>$batch_id->id,'is_active'=>1,'is_deleted'=>0));
						$teacher 		= 	Employees::model()->findByAttributes(array('id'=>$batch_id->employee_id));
						$course 		= 	Courses::model()->findByAttributes(array('id'=>$batch->course_id)); 
						$sem_enabled	= 	Configurations::model()->isSemesterEnabledForCourse($course->id);
						if($semester_enabled == 1){	
							if($sem_enabled == 1 and $batch->semester_id != NULL){
								$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
								echo '<td>'.ucfirst($semester->name).'</td>';
							}
							else{
								echo '<td>'.'-'.'</td>';
							}
						}
										
						echo '<td>';
						if($teacher){
							echo Employees::model()->getTeachername($teacher->id);
						}
						else{
							echo '-';
						}
						$cbsc_format    = ExamFormat::getCbscformat($batch->id);
						$exam_format	 = ExamFormat::model()->getExamformat($batch->id);// 1=>normal 2=>cbsc
						 echo '<td>';
						 echo CHtml::link(Yii::t('app','Online Exam'), array('/onlineexam/exam','bid'=>$batch_id->id), array('class'=>'view_Exmintn_atg Exm_aTgColor_g'));
						if($exam_format == 1){	// default			
							$exams_published = ExamGroups::model()->countByAttributes(array('batch_id'=>$batch_id->id,'is_published'=>1));
							$result_published = ExamGroups::model()->countByAttributes(array('batch_id'=>$batch_id->id,'result_published'=>1));
							
							if($exams_published > 0 or $result_published > 0){
								echo "&nbsp;&nbsp;&nbsp;".CHtml::link(Yii::t('app','View Examinations'), array('/teachersportal/exams/classexams','bid'=>$batch_id->id), array('class'=>'view_Exmintn_atg Exm_aTgColor_y'));
							}
							else{
								echo '<span class="no_Exam">'.Yii::t('app','No Exam Scheduled').'</span>';
							}
						}else if($cbsc_format){//cbsc
							$exams_published = CbscExamGroup17::model()->countByAttributes(array('batch_id'=>$batch_id->id,'date_published'=>1));
							$result_published = CbscExamGroup17::model()->countByAttributes(array('batch_id'=>$batch_id->id,'result_published'=>1));
							echo CHtml::link(Yii::t('app','View Examinations'), array('/teachersportal/exam17/allexams','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_b'));
							if($exams_published > 0 or $result_published > 0){
								echo "&nbsp;&nbsp;&nbsp;".CHtml::link(Yii::t('app','View Results'), array('/teachersportal/exams/results','bid'=>$batch_id->id), array('class'=>'view_Exmintn_atg Exm_aTgColor_y'));
							}
							else{
								echo '<span class="no_Exam">'.Yii::t('app','No Exam Scheduled').'</sapn>';
							}
							echo "&nbsp;&nbsp;&nbsp;".CHtml::link(Yii::t('app','View Co-scholastic Skills'), array('/teachersportal/coScholastic/index','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_v'));
																	
																	
						}	
						 
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
    </div>
</div>
<script>
 $('#acc_id').change(function(ev){
	var acc_id	= $(this).val();
	if(acc_id != ''){
		window.location= 'index.php?r=teachersportal/exams/classexam&acc_id='+acc_id;
	}
	else{
		window.location= 'index.php?r=teachersportal/exams/classexam';
	}
});
 </script>