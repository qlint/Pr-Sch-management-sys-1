<style>
table.studenace_table{
	border-top:1px solid #CCC;
	margin:30px 0px;
	font-size:12px;
	border-right:1px solid #CCC ;
}
.studenace_table td{
	border:1px solid #CCC ;
	padding:5px 6px;
	border-bottom:1px solid #CCC ;	
}
table{ 
	border-collapse:collapse;
}
hr{ 
	border-bottom:1px solid #ccc;
	border-top:0px solid #000
}	
h5{ 
	margin:0px;
	font-size:14px;
	padding:0px;
}	
.nothing-found{
	font-style:italic;
	text-align:center;
}
</style>
<!-- Header -->	
<?php
ini_set('memory_limit', '-1');
$semester_enabled		  = Configurations::model()->isSemesterEnabled(); 
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
        <td class="first" width="100">
		   <?php
            $filename =  Logo::model()->getLogo();
            if($filename!=NULL){                        
                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
            }
            ?>
        </td>
        <td  valign="middle" >
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
<!-- End Header -->
<h5 align="center"><?php echo Yii::t('app','STUDENTS INFORMATION');?></h5>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="studenace_table">
    <tr style="background:#dfdfdf;">
   		 <td align="center" width="10"><?php echo Yii::t('app','Sl. No.');?></td>
         <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
        	<td align="center" width="60"><?php echo Yii::t('app','Student Name');?></td>
        <?php } ?>
        <td align="center" width="40"><?php echo Yii::t('app','Admission No');?></td>
        <?php if(FormFields::model()->isVisible("batch_id", "Students", "forStudentProfile")){ ?>
       		<td align="center" width="60"><?php echo Yii::app()->getModule("students")->labelCourseBatch();?></td>
        <?php } ?>
		<?php if($semester_enabled == 1){ ?>
			<td align="center" width="40"><?php echo Yii::t('app','Semester');?></td>
		<?php } ?>
        <?php if(FormFields::model()->isVisible("gender", "Students", "forStudentProfile")){ ?>
        	<td align="center" width="40"><?php echo Yii::t('app','Gender');?></td>
        <?php } ?>
        <?php if( ($flag=='1')){ ?>
			<td align="center" width="60"><?php echo Yii::t('app','Guardian');?></td>
		<?php } ?>
    </tr>
<?php
	if($students){
		$i = 1;
		foreach($students as $student){
?>
			<tr>
            	<td align="center"><?php echo $i; ?></td>
                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                	<td align="center"><?php echo $student->studentFullName("forStudentProfile"); ?></td>
                <?php } ?>  
                <td align="center"><?php echo $student->admission_no; ?></td>  
                <?php if(FormFields::model()->isVisible("batch_id", "Students", "forStudentProfile")){ ?>
                	<td align="center">
                    	<?php
							$batch = Batches::model()->findByAttributes(array('id'=>$student->batch_id)); 
							if($batch!=NULL){
								$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id)); 
								echo $course->course_name.' / '.$batch->name; 
							}
							else{
								echo '-'; 
							}
						?>
                    </td>
                <?php } ?>  
				
				<?php $sem_enabled	= 	Configurations::model()->isSemesterEnabledForCourse($course->id);?>
					<td align="center">
						<?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){ 
								$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
								echo ucfirst($semester->name);
						 } 
						 else{
							 echo '-';
						 }?>
					</td>
                <?php if(FormFields::model()->isVisible("gender", "Students", "forStudentProfile")){ ?>
                	<td align="center">
                    	<?php 
							if($student->gender == 'M'){
								echo Yii::t('app','Male');
							}
							elseif($student->gender == 'F'){
								echo 'Female';
							}
							else{
								echo '-';
							}
						?>                        
                    </td>
                <?php }  
					if(isset($flag) && ($flag!='0')){ 
						$guardian = Guardians::model()->findByAttributes(array('id'=>$student->parent_id));
				?>
                		<td align="center">
                <?php
							if($guardian){
								echo ucfirst($guardian->first_name).' '.ucfirst($guardian->last_name);
							}
							else{
								echo '-';
							}
				
				?> 
                		</td>	
                <?php } ?>
            </tr>
<?php
			$i++;			
		}
	}
	else{
?>
		<tr>
        	<td colspan="5" class="nothing-found"><?php echo Yii::t('app','No students found'); ?></td>
        </tr>
<?php		
	}
?>    
</table>    



	
