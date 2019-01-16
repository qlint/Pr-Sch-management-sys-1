
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style type="text/css">

table{
		border-collapse:collapse;
}

table th{
	 font-size:12px;	
}
.table-grade-box th{
	 padding:8px; 	
}
.table-grade-box td{
	 padding:8px; 	
}
.table-head th{
	 padding:8px;
}
.table-box-mrgn{
	margin-top:8px;	
}
.vertcl-aln{ vertical-align:top;}
.tabl-thr-rght h4 span{
	margin-left:15px;	
}
.tabl-thr-rght h4{
	font-size:10px;	
}
.tabl-thr-rght p{
	margin:0px;
}
.table-grade-box p, h4{
	 margin:0px;
}
.table-grade-box p{
	   font-weight:400;
	    margin:5px 0px;
}

.main-tble-hd h2{
	font-size:30px;
	font-size: 28px;
	margin: 0px;	
}
.table-head{ border-bottom:0px;}

.report-hed td{ text-align:center; font-size:10px;}
.report-stu-dtls-table table{
	border-collapse:collapse;

	   	
}
.report-stu-dtls-table .inner-table{
	 border:1px solid #000;	
}
.report-stu-dtls-table .inner-table td{

	 padding:8px;
	 font-size:11px;	
}
.report-stu-dtls-table .br-td{
	 border-right:1px solid #000;

}
.tablegrade-spc td{
	 padding:4px;
}
</style>

<?php
$students = Students::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['batchid']));
$exam_groups = ExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['batchid']));
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
<!-- Header -->
	
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first " width="100">
                           <?php $logo=Logo::model()->findAll();?>
                            <?php
                            if($logo!=NULL)
                            {
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" height="100" />';
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
                                <?php echo Yii::t('app','Phone:')." ".$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <hr />
    <br />
    <!-- End Header -->
    <?php
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
if(isset($_REQUEST['exam_group_id']))
{ 
?>
    <table class="report-hed" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tbody>
    <tr>
    <td><h2>REPORT CARD</h2></td>
    </tr>
    </tbody>
    </table>
    <table class="table" width="100%" cellpadding="0" cellspacing="0" border="0"><tr><th height="10"></th></tr></table>   
    <?php $student=Students::model()->findByAttributes(array('id'=>$_REQUEST['id'],'is_deleted'=>0,'is_active'=>1)); ?>
    <?php 
    $batch 			= 	Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));
    $course 		= 	Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	$batch_student	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$student->batch_id));
    ?>
<div class="report-stu-dtls-table">

              <table cellspacing="0" cellpadding="0" border="0" width="100%" class="inner-table">
            	<tbody>
                <tr>
                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                    <td width="100"><?php echo Yii::t('app','Student Name');?></td>
                <td width="2">:</td>
                <td class="br-td"><?php echo $student->studentFullName("forStudentProfile");?> </td> <?php } ?>
                <td width="100"><?php echo Yii::t('app','Roll No');?></td>
                <td width="2">:</td>
                <td><?php echo $batch_student->roll_no;?></td>
                </tr>
                
                <tr>
                <td width="100"><?php echo Yii::t('app','Admission Number');?></td>
                <td width="2">:</td>
                <td class="br-td"><?php echo $student->admission_no;?></td>
                <?php if(in_array('batch_id', $student_visible_fields)){ ?>
                <td width="100">Course</td>
                <td width="2">:</td>
                <td><?php 
                    if($course->course_name!=NULL)
                            echo ucfirst($course->course_name);
                    else
                            echo '-';
                    ?></td><?php } ?>
                </tr>   
                                
                <tr>
                 <?php if(in_array('batch_id', $student_visible_fields)){ ?><td width="100"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></td>
                <td width="2">:</td>
                 <td class="br-td"><?php 
					if($batch->name!=NULL)
						echo ucfirst($batch->name);
					else
						echo '-';
					?> </td><?php } ?>
					<?php $semester_enabled	= 	Configurations::model()->isSemesterEnabled();  
                    $sem_enabled		= 	Configurations::model()->isSemesterEnabledForCourse($course->id);
                    if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){ 
						$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));?>
						<td width="100">
									<?php echo Yii::t('app','Semester'); ?>
						</td>
						<td width="2">:</td>
						<td><?php echo ucfirst($semester->name); ?></td>
						<?php
                    }else{?>
                        <td></td>
                        <td></td>
                        <td></td>
                        <?php
                    }?>               
                </tr>                                          
              </tbody>
              </table>

        </div>
    
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0"><tr><th height="10"></th></tr></table>              
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tbody>
<tr>
<td>
<table class="table-grade-box" width="100%" cellpadding="0" cellspacing="0" border="1">
	<thead>
    <tr><th class="main-tble-hd" colspan="8" width="720">SCHOLASTIC AREA</th></tr>
    <tr>
        <th><?php echo Yii::t('app','Sl no');?></th>
        <th><?php echo Yii::t('app','Subjects');?></th>
        <th><?php echo Yii::t('app','Periodic Test');?></th>
        <th><?php echo Yii::t('app','Note Book');?>)</th>
        <th><?php echo Yii::t('app','Subject Enrichment');?></th>
        <th><?php echo Yii::t('app','Written Exam');?></th>
        <th><?php echo Yii::t('app','Mark Obtained');?></th>   
        <th><?php echo Yii::t('app','Grade');?></th>                 
    </tr>
</thead> 
<tbody>
	 <?php 
                        $exams = CbscExams17::model()->findAll('exam_group_id=:x',array(':x'=>$_REQUEST['exam_group_id'])); // Selecting exams(subjects) in an exam group
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
                                            <td><?php echo $scores->periodic_test; ?></td>
                                            <td><?php echo $scores->note_book; ?></td>       
                                            <td><?php echo $scores->subject_enrichment; ?></td>
                                            <td><?php echo $scores->written_exam; ?></td>    
                                            <td><?php echo $scores->total; ?></td>
                                            <td><?php echo CbscExamScores17::model()->getClass2Grade($scores->total); ?></td>                
                                        </tr>
                                        <?php
                                    }
                                }
                             }                             
                        } ?>        
</tbody>
</table>
    </td>
</tr>


<tr>
	<td>
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="348" class="vertcl-aln">
<table class="table-grade-box table-box-mrgn" width="100%" cellpadding="0" cellspacing="0" border="1">
	<thead>
    <tr>
        <th align="left" width="100%">General Knowledge</th>
        <td width="100%"></td>
    </tr> 
     <tr>   
        <th align="left" width="100%">Moral Science</th>
        <td width="100%"></td>
    </tr> 
     <tr>         
        <th align="left" width="100%">Computer Science</th>
        <td width="100%"></td>
    </tr> 
     <tr>         
        <th align="left" width="100%">Discipling</th>
        <td width="100%"></td>        
    </tr>
</thead> 

</table>
</td>
<td width="10"></td>

<td width="348" class="vertcl-aln">
<table class="table-grade-box table-box-mrgn" width="100%" cellpadding="0" cellspacing="0" border="1">
	<thead>
    <tr>
        <th  colspan="2">CO-SCHOLASTIC AREA</th>
    </tr> 
     <tr>   
        <th align="left" width="100%">Work Education</th>
        <td width="100%"></td>
    </tr> 
     <tr>         
        <th align="left" width="100%">Art Education</th>
        <td width="100%"></td>
    </tr> 
     <tr>         
        <th align="left" width="100%">Health & Physical Education</th>
        <td width="100%"></td>        
    </tr>
</thead> 

</table>
</td>

</tr>
</table>
    </td>
</tr>






<tr>
<td>
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="345" class="vertcl-aln">
<table class="table-grade-box table-box-mrgn" width="100%" cellpadding="0" cellspacing="0" border="1">
	<thead>
        <tr>
        <th  colspan="2" align="left" width="345">Attendance</th>
    </tr> 
     
</thead> 
</table>
</td>
<td width="10"></td>

<td width="350" class="vertcl-aln">
<table class="table-grade-box table-box-mrgn tabl-thr-rght" width="100%" cellpadding="0" cellspacing="0" border="1">
	<thead>
 
     <tr>   
        <th align="left" width="180"><h4>Height(cm)<span></span></h4></th>
        <th align="left" width="180"><h4>Weight(kg)<span></span></h4></th>
    </tr> 
</thead> 

</table>
</td>

</tr>
</table>
    </td>
    
</tr>
<!-----------------table-footer---------------->
<tr>
<td>
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td height="20px"></td></tr></table>
</td>
</tr>

<tr>
<td>
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td>
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0">
<thead>
	<tr>
    	<th align="center"><h4>Grading scal for scholastic area</h4>
        	<h4>[Grade are awarded on a 8-point scale as follows]</h4>
        	
        </th>
    </tr>
</thead>
</table>
</td>
</tr>

<tr>
<td width="280" class="">
<table class="table-grade-box table-box-mrgn tablegrade-spc" width="100%" cellpadding="0" cellspacing="0" border="1">
	<tbody>
    <tr>
        <td align="center" width="100%">MARKS RANGE</td>
        <td width="100%">GRADE</td>
    </tr> 
     <tr>   
        <td align="center" width="100%">91-100</td>
        <td width="100%" align="center">A1</td>
    </tr> 
     <tr>         
        <td  align="center" width="100%">81-90</td>
        <td width="100%" align="center">A2</td>
    </tr> 
     <tr>         
        <td align="center" width="100%">71-80</td>
        <td width="100%" align="center">B1</td>        
    </tr>
    <tr>
        <td align="center" width="100%">61-70</td>
        <td width="100%" align="center">B2</td>
    </tr> 
     <tr>   
        <td align="center" width="100%">51-60</td>
        <td width="100%" align="center">C1</td>
    </tr> 
     <tr>         
        <td  align="center" width="100%">41-50</td>
        <td width="100%" align="center">C2</td>
    </tr> 
     <tr>         
        <td align="center" width="100%">33-40</td>
        <td width="100%" align="center">D</td>        
    </tr>    
     <tr>         
        <td align="center" width="100%">32 & Below</td>
        <td width="100%" align="center">E(Needs Improvement)</td>        
    </tr>    
    
    
    
    
</tbody> 

</table>
</td>
<td width="10"></td>
<td width="280" class="vertcl-aln">
<table class="table-grade-box table-box-mrgn tablegrade-spc " width="100%" cellpadding="0" cellspacing="0" border="1">
	<thead>
    <tr>
        <th  colspan="2">
        <h4>Grading scal for co-scholastic and Discipline CI.III-VII</h4>
        <p>[On a 3 point(AC) Gradinf scale]
        </th>
    </tr> 
     <tr>   
        <th align="center" width="100%">A</th>
        <td width="100%" align="center">Outstanding</td>
    </tr> 
     <tr>         
        <th align="center" width="100%">B</th>
        <td width="100%" align="center">Very good</td>
    </tr> 
     <tr>         
        <th align="center" width="100%">B</th>
        <td width="100%" align="center">Fair</td>        
    </tr>
    <tr>
        <th  colspan="2" height="50"></th>
    </tr> 
</thead> 

</table>
</td>

</tr>
</table>
    </td>
</tr>

 
    </tbody>
</table> 
<?php } ?>
