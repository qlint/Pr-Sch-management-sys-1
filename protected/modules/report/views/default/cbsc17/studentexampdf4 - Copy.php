<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style type="text/css">
.table-stl{
	border-collapse:collaps;
}
.table-stl th, .table-stl td{
	 border:none;
	 padding:15px;
	 border:1px solid #000;	
	 font-size:12px;
}
.table-grade-box-head{
	 border-collapse:collapse;
	 border:none;	
}
.table-grade-box-head th{
	 font-size:12px;
	 text-align:left;
	 border: none;
}
.main-tble th{
	 font-size:12px !important;
	 text-align:center;
	 text-transform:uppercase;
	 font-weight:bold;
}
.main-tble td{
	text-transform:uppercase;
}
.main-tble{
	text-transform:uppercase;
}
.vertcl-aln{ vertical-align:top;}
.table-stl .table-border-non{
	 border:none;
	 text-transform:uppercase;
}
.table-grade-box-result{
	 border:1px solid #000;
	border-collapse:collaps;

}
.table-grade-box-result th{
	 border:none;
	 padding:15px;
}
</style>
<?php
$student					=	Students::model()->findByAttributes(array('id'=>$_REQUEST['id'],'is_deleted'=>0,'is_active'=>1)); 
$batch_student         		= 	BatchStudents::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$student->batch_id));
$batch 						= 	Batches::model()->findByAttributes(array('id'=>$batch_student->batch_id));
$course 					= 	Courses::model()->findByAttributes(array('id'=>$batch->course_id));
$student_visible_fields   	= 	FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
$semester				  	=	Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
$exam_groups    			=   CbscExamGroup17::model()->findAllByAttributes(array('batch_id'=>$batch->id,'date_published'=>1,'result_published'=>1,'type'=>2,'class'=>4));
?>
<table class="table-grade-box-head" width="100%" cellpadding="0" cellspacing="0" border="0">
	<thead>
		<tr>
			<?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
			<th><?php echo Yii::t('app','Student Name')." : ";?></th>
			<th><?php echo $student->studentFullName("forStudentProfile");?></th>
			<?php } ?>
			<?php if(in_array('batch_id', $student_visible_fields)){ ?>
				<td><?php echo Yii::t('app', 'Course/Batch')." : "; ?></td>
				<td><?php 
							if($course->course_name!=NULL)
									echo ucfirst($course->course_name)." / ";
							else
									echo '- ';
							
							if($batch->name!=NULL)
									echo ucfirst($batch->name);
							else
									echo '-';
							?></td>
							<?php 
				  $sem_enabled	= 	Configurations::model()->isSemesterEnabledForCourse($course->id);
				  if($sem_enabled==1 and $batch->semester_id!=NULL){ ?>
				 <td><?php echo Yii::t('app','Semester');?></td>
				<td><?php   echo ucfirst($semester->name);?></td>
			<?php 
				  }
				} ?>
				<td><?php echo Yii::t('app','Roll No');?></td>
				<td><?php echo $batch_student->roll_no;?></td>
		</tr>
	</thead>
</table>

<table class="table-grade-box-head" width="100%" cellpadding="0" cellspacing="0" border="0"><thead><tr><th height="10"></th></tr></thead></table>
	<table class="table-grade-box-head" width="100%" cellpadding="0" cellspacing="0" border="0">
		<thead>
			<tr><th><?php echo Yii::t('app','PART');?></th></tr>
			<tr><th><?php echo Yii::t('app','ACADEMIC PERFORMANCE OF THE STUDENT');?></th></tr>
		</thead>
	</table>
	<table class="table-grade-box-head" width="100%" cellpadding="0" cellspacing="0" border="0"><thead><tr><th height="10"></th></tr></thead></table>
		<table class="table-stl" width="100%" cellpadding="0" cellspacing="0" border="0">
			<thead>
				<tr>
					<th rowspan="2"><?php echo Yii::t('app','Sl no');?></th>
					<th rowspan="2" colspan="2" class="main-tble"><?php echo Yii::t('app','Subjects');?></th>
					<th rowspan="2"><?php echo Yii::t('app','Max. Marks');?></th>
					<?php if($exam_groups!=NULL){
						foreach ($exam_groups as $exam){
							?>
								<th colspan="2"><?php echo ucfirst($exam->name); ?></th>
							<?php
						}
					} ?>        
			
					<th rowspan="2"><?php echo Yii::t('app','Annual Model Exam');?></th>
					<th rowspan="2"><?php echo Yii::t('app','Average');?></th>
				</tr>
    			<tr>
					<?php if($exam_groups!=NULL){
						foreach ($exam_groups as $exam){?>
								<th><?php echo Yii::t('app','Marks');?></th>
								<th><?php echo Yii::t('app','Grade');?></th>
				<?php }
					} ?>
				</tr>
    <?php 
    $subjects   =   Subjects::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid'],'is_deleted'=>0));
	
    if($subjects!=NULL){
		$sl=1;
        foreach($subjects as $subject){
            $sub1_total	=	0;
			$sub2_total	=	0;
            if($subject->split_subject==1){
                $split  =   SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$subject->id));?>
                    <tr>
                        <td rowspan="3"><?php echo $sl++;?></td>
                        <th class="main-tble" rowspan="3">
                        <p><?php 
                                if($subject->name!=NULL){
                                        if($subject->elective_group_id==0){
                                                echo ($subject->name!=NULL)? ucfirst($subject->name):'-';
                                        }else{
                                                $electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student, 'elective_group_id'=>$subject->elective_group_id));
                                     			$elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
                                                echo (($subject->name!=NULL)? ucfirst($subject->name).' (':'').$elective->name.(($subject->name!=NULL)?')':'');
                                        }
                                }
                            ?></p>

                        </th>
							<td><?php echo $split[0]->split_name; ?></td>
							<td>70</td>

                       <?php 
					   $k=0;
					   $sub1_total =	0;
					   if($exam_groups!=NULL){
                                foreach ($exam_groups as $exam){
								$exam_model =   CbscExams17::model()->findByAttributes(array('exam_group_id'=>$exam->id,'subject_id'=>$subject->id));
								if($exam_model!=NULL){
									$scores  =   CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam_model->id,'student_id'=>$student->id));
if($scores!=NULL){

										$k++;
										$val1		=	$scores->getcategory1($scores->id);
										$r_val1     =   $val1;
										$val1       =   ($val1*100)/70;
										$val1       =   number_format((float)$val1, 2, '.', '');
										$sub1_total =	$sub1_total+$r_val1;
									}else{

										$r_val1     =	NULL;
										$val1		=	NULL;
									}

                            	}else{
									$r_val1     =	NULL;
									$val1		=	NULL;

								}  ?>   <td><?php  echo  ($r_val1)?$r_val1:"-";?></td>
                                        <td><?php echo  ($val1)?CbscExamScores17::model()->getClass2Grade($val1):"-";?></td>

                                    <?php
                                }
                            } ?>

                        <td>--</td>
                        <td><?php echo $sub1_total/$k;?></td>
                    </tr> 
                    <tr>
                        <td><?php echo $split[1]->split_name; ?></td>
                        <td>30</td>
                       <?php
                        $sub2_total =	0; 
						  $k=0;
                        if($exam_groups!=NULL){
                                foreach ($exam_groups as $exam){
									$val2		=	NULL; 
									$exam_model =   CbscExams17::model()->findByAttributes(array('exam_group_id'=>$exam->id,'subject_id'=>$subject->id));
									if($exam_model!=NULL){
										$scores  =   CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam_model->id,'student_id'=>$student->id));										

										if($scores!=NULL){

											$k++;

											$val2		=	$scores->getcategory2($scores->id); 

											$r_val2     =   $val2;

											$val2       =   ($val2*100)/30;

											$val2       =   number_format((float)$val2, 2, '.', '');

											$sub2_total =	$sub2_total+$r_val2;

										}else{

											$r_val2     =	NULL;

											$val2		=	NULL;

										}

									}else{

										$r_val2     =	NULL;

										$val2		=	NULL;

									}	

										

                                    ?>

                                         <td><?php  echo  ($r_val2)?$r_val2:"-";?></td>

                                        <td><?php echo  ($val2)?CbscExamScores17::model()->getClass2Grade($val2):"-";?></td>

                                    <?php

                                }

                            } ?>

                        <td>--</td>

                        <td><?php echo $sub2_total/$k;?></td>

                    </tr>    

                    <tr>

                        <td class="main-tble"><?php echo Yii::t('app', 'Total'); ?></td>

                        <td>100</td>

                       <?php 

					   $sub_total =	0; 

					     $k=0;

					   if($exam_groups!=NULL){

						   		$total	=NULL;

                                foreach ($exam_groups as $exam){ 

									$exam_model =   CbscExams17::model()->findByAttributes(array('exam_group_id'=>$exam->id,'subject_id'=>$subject->id));

									if($exam_model!=NULL){

										$scores 	=   CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam_model->id,'student_id'=>$student->id));

										if($scores!=NULL){

											  $k++;

											$val1		=	$scores->getcategory1($scores->id); 

											$val2		=	$scores->getcategory2($scores->id);										

											$total		=	$val1+$val2; 

											$sub_total 	=	$sub_total+$total;

										}else{

											$total     	=	NULL; 

										}

									}else{

										$total    		=	NULL; 

									}	

									?>

                                     <td><?php echo  ($total)?$total:"-";?></td> 

                                     <td><?php echo  ($total)?CbscExamScores17::model()->getClass2Grade($total):"-";?></td>

                                    <?php

                                }

                            } ?>

                        <td>--</td>

                        <td><?php echo $sub_total/$k;?></td>



                    </tr> 

                <?php

            }else

            {

                ?>

                <tr>

                    <td><?php echo $sl++;?></td>

                    <th colspan="2">

                    <P><?php 

                        if($subject->name!=NULL){

                                if($subject->elective_group_id==0){

                                        echo ($subject->name!=NULL)? ucfirst($subject->name):'-';

                                }else{	 

								$electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student, 'elective_group_id'=>$subject->elective_group_id));

								$elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));

								echo (($subject->name!=NULL)? ucfirst($subject->name).' (':'').$elective->name.(($subject->name!=NULL)?')':'');

								}

                        }

                    ?></p>

                   

                    </th>  

                    <td>100</td>

                    <?php 

					

                    if($exam_groups!=NULL){	

					$tot=0;		

					$i=0;			

                        foreach ($exam_groups as $exam)

                        {

                            $exam_model =   CbscExams17::model()->findByAttributes(array('exam_group_id'=>$exam->id,'subject_id'=>$subject->id));

                            if($exam_model!=NULL){

                                $scores  =   CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam_model->id,'student_id'=>$student->id));

								$tot	=	$tot+$scores->total;

								$i++;

                            }else{



								 $scores  =NULL;

							}

                            ?>

                                <td><?php echo isset($scores)?$scores->total:"-"; ?></td>

                                <td><?php echo isset($scores)?CbscExamScores17::model()->getClass2Grade($scores->total):"-"; ?></td>

                            <?php

                        }

                    } ?> 

                    <td><?php echo "-";?></td>

                     <td><?php echo ($tot!=0)?$tot/$i:"-";?></td>

                    

                    

                </tr>   

                <?php

            }

            

            

        }

    }

    

    ?>

</thead> 

<tbody>

</tbody>

</table>



<table class="table-grade-box-head" width="100%" cellpadding="0" cellspacing="0" border="0"><thead><tr><th height="10"></th></tr></thead></table>

<table class="table-stl main-tble" width="100%" cellpadding="0" cellspacing="0" border="0">

	<thead>

    <tr>

        <th colspan="3" class="table-border-non"><?php echo Yii::t('app','Internal Assessment');?></th>

    </tr>    

    <tr>

        <th colspan="3" class="main-tble"><?php echo Yii::t('app','Activities');?></th>

    </tr>

     <tr>   

        <td><?php echo Yii::t('app','Work Education');?></td>

        <td><?php echo Yii::t('app','Physical Education');?></td>

        <td><?php echo Yii::t('app','General Studies');?></td>

    </tr>     

     <tr>   

        <th></th>

        <th></th>

        <th></th>

    </tr> 

    

</thead> 



</table>



<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0">

<tbody>

<tr>

<td width="350" class="vertcl-aln">



<table class="table-stl main-tble" width="100%" cellpadding="0" cellspacing="0" border="0">

	<thead>

     <tr>   

        <th width="100%" colspan="4" class="table-border-non">Attendance</th>

    </tr>      

     <tr>   

        <th width="100%" colspan="2" class="main-tble">Height(cm)</th>

        <th width="100%" colspan="2" class="main-tble">weight(Kg)</th>

    </tr> 

     <tr>         

        <th width="100%">Term 1</th>

        <th width="100%">Term 11</th>

        <th width="100%">Term 1</th>

        <th width="100%">Term 11</th>

    </tr> 

     <tr>         

        <th width="100%">---</th>

        <th width="100%">---</th>

        <th width="100%">---</th>

        <th width="100%">---</th>

    </tr>

</thead> 



</table>



</td>

<td width="3"></td>

<td width="350" class="vertcl-aln">

<table class="table-stl main-tble" width="100%" cellpadding="0" cellspacing="0" border="0">

	<thead>

    <tr>   

        <th width="100%" colspan="3" class="table-border-non">Health Status</th>

    </tr>  

     <tr>   

        <th width="100%" class="main-tble">Term 1</th>

        <th width="100%" class="main-tble">Term 11</th>

        <th width="100%" class="main-tble">Term 111</th>

    </tr> 

     <tr>         

        <th width="100%" height="87"></th>

        <th width="100%" height="87"></th>

        <th width="100%" height="87"></th>



    </tr> 

     

</thead> 



</table>

</td>





</tr>

</tbody>

</table>

<table class="table-grade-box-head" width="100%" cellpadding="0" cellspacing="0" border="0"><thead><tr><th height="10"></th></tr></thead></table>

<table class="table-stl main-tble" width="100%" cellpadding="0" cellspacing="0" border="0">

	<thead>

    <tr>

        <th>Exam</th>

        <th width="40%">Class Teacher Remarks</th>

        <th>Signature of class teacher</th>

        <th>Signature of class Principal</th>

        <th>Signature of class parent</th>

              

    </tr>

</thead> 

<tbody>

<?php

	   if($exam_groups!=NULL){				

			foreach ($exam_groups as $exam)

			{ 

				$exam_model =   CbscExams17::model()->findByAttributes(array('exam_group_id'=>$exam->id,'subject_id'=>$subject->id));

			

			?>

                    <tr>

                        <th><?php echo $exam->name;?></th>

                        <td>--</td>

                        <td>--</td>

                        <td>--</td>        

                        <td>--</td>

                                

                    </tr>

                    <?php 

			}

		}

?>



	

	       

</tbody>

</table>

<table class="table-grade-box-head" width="100%" cellpadding="0" cellspacing="0" border="0"><thead><tr><th height="10"></th></tr></thead></table>

<table class="table-grade-box-result main-tble" width="100%" cellpadding="0" cellspacing="0" border="0">

<thead>

	<tr>

    	<th align="left" height="100" colspan="3" style=" vertical-align:text-top;">Result</th>

    </tr>

	<tr>

    	<th align="left">Date</th>

        <th align="left">Class Teacher</th>

        <th align="left">Principal</th>

    </tr>    

</thead>

</table>

    

