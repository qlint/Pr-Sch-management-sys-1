<?php $this->renderPartial('leftside');?> 
    <?php
    $student					=	Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$batchstudents    			= 	BatchStudents::model()->studentBatch($student->id);
	$student_visible_fields   	= 	FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
	$examgroup 					= 	CbscExamGroup17::model()->findByAttributes(array('id'=>$_REQUEST['id'], 'result_published'=>1));
    ?>
   <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Exams'); ?><span><?php echo Yii::t('app','View your Exam here'); ?></span></h2>
        </div>
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app','Exams'); ?></li>
            </ol>
        </div>
        <div class="clearfix"></div>
    </div>
     <div class="contentpanel">
     	<!--<div class="col-sm-9 col-lg-12">-->
        <div>
        	<div class="people-item">
                          <div class="media">
                            <a href="#" class="pull-left">
                                <?php
                     if($student->photo_file_name!=NULL)
                     { 
					 	$path = Students::model()->getProfileImagePath($student->id);
                        echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'" width="100" height="103" />';
                    }
                    elseif($student->gender=='M')
                    {
                        echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' width="100" height="103" />'; 
                    }
                    elseif($student->gender=='F')
                    {
                        echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.' width="100" height="103" />';
                    }
                    ?>                            
                            </a>
                            <div class="media-body">
					          <?php
					          if(FormFields::model()->isVisible("fullname", "Students", "forStudentPortal")){
					          ?>
					          <h4 class="person-name"><?php echo $student->studentFullName("forStudentPortal");?></h4>
					          <?php
					          }
					          ?>
					          <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
							  <?php 
							  $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
							  	if(count($batchstudents) == 1){
								   $batchstudent	=	BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'result_status'=>0)); ?>
								   <?php if($batchstudent->roll_no != NULL) {?>
											<div class="text-muted"><strong><?php echo Yii::t('app','Roll No').' :';?></strong> <?php echo $batchstudent->roll_no;
								   		}?></div>
						  <?php } ?>
							   <?php if(count($batchstudents)>1){ 
										echo CHtml::link('View Course Details', array('/studentportal/default/course'));
										}
										if(count($batchstudents) == 1){ ?>
											  <?php if(in_array('batch_id', $student_visible_fields)){ ?>      
											  <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
												<?php 
												  $batch = Batches::model()->findByPk($batchstudent->batch_id);
												  $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
												  echo $batch->course123->course_name;
												  
												?>
											  </div>          
											  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo $batch->name;?></div>
											  <?php } ?>
											   <?php  if($batch->semester_id!=NULL){ ?>
														<div class="text-muted"> <strong><?php echo Yii::t('app','Semester').' :';?></strong> <?php echo $semester->name;?></div>
												<?php } ?>
									<?php } ?>
					        </div>
                          </div>
                        </div>
                         <!-- END div class="profile_top" -->
                         
                         <div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Semester Assessment Report');?></h3>
                             <?php $semester_enabled	= Configurations::model()->isSemesterEnabled();?>
                              <table class="table table-hover mb30">
                              
                                <tr>
                                    <th><?php echo Yii::t('app','Course');?></th><th>:</th>
                                    <th>
                                     <?php 
                                      $batch = Batches::model()->findByPk($_REQUEST['bid']);
                                     $semester	= Semester::model()->findByAttributes(array('id'=>$_REQUEST['sem']));
                                      echo ucfirst(($batch->course123->course_name))?$batch->course123->course_name:"-"; 
                                    ?>
                                    </th>                        
                                    <th><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></th><th>:</th>
                                    <th><?php echo ucfirst(($batch->name))?$batch->name:"-";?></th>
                                </tr>
								
								 <tr>
                                    <?php
                                    if($semester_enabled == 1){ 
                                        if($batch->semester_id!=NULL){ 
                                        ?>
                                        <th><?php echo Yii::t('app','Semester');?></th><th>:</th>  
                                        <th><?php echo ucfirst(($semester->name))?$semester->name:"-";?></th>
                                        <?php
                                        }
                                    }
                                    ?>
                                        <th><?php echo Yii::t('app','Exam Group');?></th><th>:</th>  
                                        <th><?php echo ucfirst($examgroup->name);?></th>
                                </tr>
                                </table>
                        </div>
                        <div class="people-item">
                            <div class="btn-demo" style="position:relative; top:-8px; right:3px; float:right;">                    
                                <div class="edit_bttns" >
                                    <ul>
                                        <li>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>',array('/studentportal/default/SemResult','bid'=>$_REQUEST['bid'],'sem'=>$_REQUEST['sem']),array('class'=>'addbttn last'));?>
                                            <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/studentportal/default/SemResultpdf17','bid'=>$_REQUEST['bid'],'examgroup'=>$_REQUEST['id'], 'sem'=>$_REQUEST['sem']),array('target'=>"_blank",'class'=>'portal-pdf')); ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
  	<div class="os-table tablebx">
      <div class="tbl-grd"></div>
      <?php 
	  if($examgroup!=NULL) // If exam groups present
	  { 
		  	  $i = 1;
			  $flag1=0; 
			  $exams = CbscExams17::model()->findAll('exam_group_id=:x',array(':x'=>$examgroup->id)); // Selecting exams(subjects) in an exam group
			  if($exams!=NULL)
		  	  { 
				  
					foreach($exams as $exam)
					{ 
						$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
						if($subject!=NULL) // Checking if exam for atleast subject is created.
						{  
							$score = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student->id));
							if($score!=NULL)
							{ 
								$flag1=1;
							}
						}
					} 
					if($flag1==1)
					{  ?> 
                               
    <table class="table table-hover mb30">
    <?php
	if($examgroup->class ==4 ){
		?>
        <tr>   
        	<th><?php echo Yii::t('app','Sl no');?></th>         
            <th><?php echo Yii::t('app','Subject');?></th>
            <th><?php echo Yii::t('app','Score');?></th>
            <th><?php echo Yii::t('app','Remarks');?></th>
            <th><?php echo Yii::t('app','Grade');?></th>
        </tr>
        <?php
		}else{
			?>
            <tr>  
       		    <th><?php echo Yii::t('app','Sl no');?></th>           
                <th><?php echo Yii::t('app','Subjects');?></th>
                <th><?php echo Yii::t('app','Periodic Test');?></th>
                <th><?php echo Yii::t('app','Note Book');?></th>
                <th><?php echo Yii::t('app','Subject Enrichment');?></th>
                <th><?php echo Yii::t('app','Written Exam');?></th>
                <th><?php echo Yii::t('app','Mark Obtained');?></th>   
                <th><?php echo Yii::t('app','Grade');?></th>     
            </tr>
            <?php
        } 
        if($exams==NULL)
        {
            echo '<tr><td align="center" colspan="4">'.Yii::t('app','No Results Found').'</td></tr>';	
        }
        else
        {  
			$exams = CbscExams17::model()->findAll('exam_group_id=:x',array(':x'=>$examgroup->id)); // Selecting exams(subjects) in an exam group
			if($exams!=NULL)
			{
				$i=1;
				foreach($exams as $exam)
				{
					$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
					if($subject!=NULL) // Checking if exam for atleast subject is created.
					{
						$scores = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student->id));
						if($scores!=NULL)                            
						{
							?>                        
							<tr>
								<td><?php echo $i++;?></td>
								<td>
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
									?>
								</td>
								
                              <?php
							  if($examgroup->class !=4 ){
								  ?>
								<td><?php echo $scores->periodic_test; ?></td>
								<td><?php echo $scores->note_book; ?></td>       
								<td><?php echo $scores->subject_enrichment; ?></td>
								<td><?php echo $scores->written_exam; ?></td>
                               <?php
							  }else{
								  ?>
								  <td><?php echo $scores->total; ?></td>
                                  <td><?php 
								  if($scores->remarks!=NULL){
								  	echo ucfirst($scores->remarks);
								  }
								  else{
									  echo '-';
								  }
								   ?></td>
                                  <?php
							  }?>
							   
								<td><?php 
								 if($examgroup->class == 1){
											  	   echo CbscExamScores17::model()->getClass1Grade($scores->total);
												  
											  }
											  else{
												   echo CbscExamScores17::model()->getClass2Grade($scores->total);
												   
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
    </table>
        <?php		
		} 				  
	  }
		  
	  }else{
	 	 echo "<div style='text-align:center'>"."No Exams Details  !!"."</div>";
	  }
      ?>
    </div>
  </div>
            <!-- END div class="profile_details" -->
        </div> <!-- END div class="parentright_innercon" -->
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
