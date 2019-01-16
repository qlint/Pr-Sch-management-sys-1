<style>
	.table-responsive table td{
		bo	
	}
.table{
	margin:30px 0px;
	font-size:15px;
	border-collapse:collapse
}

.table td,th{
	border:1px  solid #C5CED9;
	padding:5px 7px;
	text-align:left;
	
}
.panel-title{
	text-align:center;
	color:#333;
	font-weight:600;
	 font-family:Arial, Helvetica, sans-serif;	
}
</style>
<?php
$student	= Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
$batch		= Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));
$course		= Courses::model()->findByAttributes(array('id'=>$batch->course_id));
$ex_group	= CbscExamGroup17::model()->findByPk($_REQUEST['examgroup']);
$exams 		= CbscExams17::model()->findAll('exam_group_id=:x',array(':x'=>$ex_group->id)); // Selecting exams(subjects) in an exam group
if($_REQUEST['sem']!=0 or $_REQUEST['sem']!=NULL){
			  $semester=Semester::model()->findByAttributes(array('id'=>$_REQUEST['sem']));
			 
		}
?>
<table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first" width="100">
                           <?php $filename=  Logo::model()->getLogo();
                            if($filename!=NULL)
                            { 
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td valign="middle" >
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; width:300px;  padding-left:10px;">
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo Yii::t('app','Phone: ').$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <hr />
	 <h3 class="panel-title"><?php echo Yii::t('app','Semester Assessment Report');?></h3> 
	 
	  <!-- Batch details -->
    <table style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        	<tr>
            	<?php if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){ ?>
            	<td style="width:150px;"><?php echo Yii::t('app','Student Name');?></td>
                <td style="width:10px;">:</td>
                <td style="width:200px;"><?php echo $student->studentFullName("forParentPortal");?></td>
				<?php } ?>
                
                <td style="width:150px;"><?php echo Yii::t('app','Course');?></td>
                <td style="width:10px;">:</td>
                <td style="width:200px;"><?php echo ucfirst($course->course_name);?></td>
				
				<td><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></td>
                <td style="width:10px;">:</td>
                <td style="width:200px;"><?php echo ucfirst($batch->name);?></td>
			</tr>
			<tr>
				<td><?php echo Yii::t('app','Semester');?></td>
                <td style="width:10px;">:</td>
                 <td style="width:200px;"><?php
				if($_REQUEST['sem']!=0 or $_REQUEST['sem']!=NULL){ 
				
					echo ucfirst($semester->name);
				}
				if($_REQUEST['sem']==0){ 
					echo Yii::t('app','All');;
				}
				?></td>
				
				<td style="width:150px;"><?php echo Yii::t('app','Exam Group');?></td>
                <td style="width:10px;">:</td>
                <td style="width:200px;"><?php echo ucfirst($ex_group->name);?></td>
            </tr>
        </table>
    <!-- END Batch details -->
	<br />
    <div class="people-item">
<table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
    <?php 
	if($ex_group->class ==4 ){
		?>
       <tr class="tablebx_topbg" style="background-color:#DCE6F1;"> 
        	 <td style="width:150px;"><?php echo Yii::t('app','Sl no');?></td>       
             <td style="width:150px;"><?php echo Yii::t('app','Subject');?></td>
             <td style="width:150px;"><?php echo Yii::t('app','Score');?></td>
             <td style="width:150px;"><?php echo Yii::t('app','Remarks');?></td>
             <td style="width:150px;"><?php echo Yii::t('app','Grade');?></td>
        </tr>
        <?php
		}else{
			?>
           <tr class="tablebx_topbg" style="background-color:#DCE6F1;"> 
                <td style="width:150px;"><?php echo Yii::t('app','Sl no');?></td>         
                <td style="width:150px;"><?php echo Yii::t('app','Subjects');?></td>
                <td style="width:150px;"><?php echo Yii::t('app','Periodic Test');?></td>
                <td style="width:150px;"><?php echo Yii::t('app','Note Book');?></td>
                <td style="width:150px;"><?php echo Yii::t('app','Subject Enrichment');?></td>
                <td style="width:150px;"><?php echo Yii::t('app','Written Exam');?></td>
                <td style="width:150px;"><?php echo Yii::t('app','Mark Obtained');?></td>   
                <td style="width:150px;"><?php echo Yii::t('app','Grade');?></td>     
            </tr>
            <?php
        } 
		
        if($exams==NULL)
        {
            echo '<tr><td align="center" colspan="4">'.Yii::t('app','No Results Found').'</td></tr>';	
        }
        else
        {  
			
			if($exams!=NULL)
			{
				$i=1;$k=0;
				foreach($exams as $exam)
				{
					$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
					if($subject!=NULL) // Checking if exam for atleast subject is created.
					{
						$scores = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student->id));
						if($scores!=NULL)                            
						{
							$k++;
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
							  if($ex_group->class !=4 ){
								  ?>
								<td><?php echo $scores->periodic_test; ?></td>
								<td><?php echo $scores->note_book; ?></td>       
								<td><?php echo $scores->subject_enrichment; ?></td>
								<td><?php echo $scores->written_exam; ?></td>
                               <?php
							  }?>
                                  
								<td><?php echo $scores->total; ?></td>
                               <?php 
							    if($ex_group->class ==4 ){
                               ?>
                              	 <td><?php echo ($scores->remarks)?$scores->remarks:"-"; ?></td> 
                               <?php
                               }
							   ?>
								<td><?php 
								 if($ex_group->class == 1){
											  	   echo CbscExamScores17::model()->getClass1Grade($scores->total);
												  
											  }
											  else{
												   echo CbscExamScores17::model()->getClass2Grade($scores->total);
												   
											  }
									 ?>
								</td>                
							</tr>
							<?php
						}
					}
				 } 
		
		} 
	}
	if($k==0){
		if($ex_group->class !=4 ){
			echo '<tr><td align="center" colspan="8">'.Yii::t('app','No Results Found').'</td></tr>';
		}else{			
			 echo '<tr><td align="center" colspan="4">'.Yii::t('app','No Results Found').'</td></tr>';
		}
	}
        ?>    
    </table>
	</div>
