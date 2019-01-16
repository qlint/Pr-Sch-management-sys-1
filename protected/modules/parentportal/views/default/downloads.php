<style type="text/css">
.nothing-found{
	text-align:center;
	font-style:italic;
}
</style>
<script type="text/javascript">
function getstudent() // Function to see student profile
{
	var student_id = $('#student_id').val();
	if(student_id != ''){
		window.location= 'index.php?r=parentportal/default/downloads&id='+student_id;	
	}
	else{
		window.location= 'index.php?r=parentportal/default/downloads';
	}
}
</script>
<?php 
	echo $this->renderPartial('application.modules.parentportal.views.default.leftside'); 
    
	$guardian = Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	
	$criteria 				= new CDbCriteria;		
	$criteria->join 		= 'LEFT JOIN guardian_list t1 ON t.id = t1.student_id'; 
	$criteria->condition 	= 't1.guardian_id=:guardian_id and t.is_active=:is_active and is_deleted=:is_deleted';
	$criteria->params 		= array(':guardian_id'=>$guardian->id,':is_active'=>1,'is_deleted'=>0);
	$students 				= Students::model()->findAll($criteria); 
		    
    $student_list 			= CHtml::listData($students,'id','studentnameforparentportal');    
?>
<div class="pageheader">
	<div class="col-lg-8">
		<h2><i class="fa fa-male"></i><?php echo Yii::t('app','Downloads '); ?> <span><?php echo Yii::t('app','Downloads here'); ?></span></h2>
	</div>
	<div class="col-lg-2">
		<?php 
        	echo CHtml::dropDownList('student_id','',$student_list,array('prompt'=>Yii::t('app','Select Student'),'id'=>'student_id','class'=>'form-control input-sm mb14','style'=>'width:auto;','options'=>array($_REQUEST['id']=>array('selected'=>true)),'onchange'=>'getstudent();'));
        ?>
	</div>
	<div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
		<ol class="breadcrumb">			
			<li class="active"><?php echo Yii::t('app','Downloads'); ?></li>
		</ol>
	</div>
	<div class="clearfix"></div>
</div>
<?php
	if(isset($_REQUEST['id']) and $_REQUEST['id'] != NULL){
		$student = Students::model()->findByPk($_REQUEST['id']);
?>
        <div class="contentpanel"> 
            <div class="people-item">
                <div class="media"> 
                    <a href="#" class="pull-left">
                        <?php
                            if($student->photo_file_name != NULL){ 
                                $path = Students::model()->getProfileImagePath($student->id);
                                echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'" width="100" height="103" class="thumbnail media-object" />';
                            }
                            elseif($student->gender == 'M'){
                            echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' width="100" height="103" class="thumbnail media-object" />'; 
                            }
                            elseif($student->gender == 'F'){
                            echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.' width="100" height="103" class="thumbnail media-object" />';
                            }
                        ?>
                    </a>
                    <div class="media-body">
                    <?php
						if(FormFields::model()->isVisible("fullname", "Students", "forStudentPortal")){
							?>
							<h4 class="person-name"><?php $name = $student->studentFullName('forParentPortal');
								echo CHtml::link($name,array('/parentportal/default/studentprofile', 'id'=>$student->id));
							?></h4>
							<?php
						}
						?>
                        <?php 
						$batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id,'result_status'=>0));
						if(count($batchstudents)>1){ 
						echo CHtml::link('View Course Details', array('/parentportal/default/course', 'id'=>$student->id));
						}
						else{?>	
							  <?php if(FormFields::model()->isVisible('batch_id','Students','forParentPortal')){?>
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
                        <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
                    </div>
                </div>
            </div>
            <div class="panel-heading" style="position:relative;">
                <h3 class="panel-title"><?php echo Yii::t('app','File Uploads'); ?> </h3>
            </div>
            <div class="people-item">
				<?php 
					$form = $this->beginWidget('CActiveForm',
						array(
							'id'=>'',
							'action'=>'',
							'htmlOptions'=>array(
							'target'=>'_blank',
						)
					)); 
				?>                	
                    <table width="100%" cellpadding="0" cellspacing="0" class="table table-hidaction table-hover mb30">
                        <tr>
                            <th width="50"><input id="demo_box_1" class="css-checkbox" type="checkbox" /><label for="demo_box_1" name="demo_lbl_1" class="css-label"></label><input type="hidden" id="isChkd" value="true" /></th>
                            <th><?php echo Yii::t('app','Title'); ?></th>
                            <th><?php echo FileUploads::model()->getAttributeLabel('description'); ?></th>
                            <th><?php echo Yii::t('app','File Type'); ?></th>
                            <th><?php echo Yii::t('app','Posted By'); ?></th>
                        </tr>
<?php	
						$flag	= 0;
						if($files != NULL){
							foreach($files as $file){
								$document_status	= DocumentUploads::model()->fileStatus(5, $file->id, $file->file);      
								if($document_status == true){
									$flag = 1;
?>
									<tr>
                                    	<td width="60"><input type="checkbox" id="demo_box_<?php echo $file->id+1;?>" name="Downfiles[]" class="css-checkbox dl-files" value="<?php echo $file->id;?>" /><label for="demo_box_<?php echo $file->id+1;?>" name="demo_lbl_<?php echo $file->id+1;?>" class="css-label"></label></td>
                                        <td width="300" style=" padding-right:10px;">        
											<?php                                                 
                                                echo '<img src="images/arrow_left.png" style="margin:0px 10px 0px 10px" />';
                                                echo $file->title;
                                            ?>                                        
                                        </td>
                                        <td width="300"><font color="<?php echo $font; ?>"><?php echo ucfirst($file->description);?></font></td>
                                        <td width="80"><?php $parts	=	explode('/',$file->file_type); echo $parts[1].' file';?></td>
                                        <td width="150">
                                        	<?php												
												$posted_usr	= Profile::model()->findByAttributes(array('user_id'=>$file->created_by));
												echo $posted_usr->firstname.' '.$posted_usr->lastname;												
											?>
                                        </td>
                                    </tr>
<?php									
								}
							}
						}
						else{
?>
							<tr>
                            	<td colspan="5" class="nothing-found"><?php echo Yii::t('app','No files to download!'); ?></td>
                            </tr>
<?php						
						}
?>                        
                    </table>  
                    <?php
						if($flag == 1){
					?>
                    		<input type="submit" onclick="return validateForm();" value="<?php echo Yii::t('app','Download'); ?>" class="btn btn-danger" />
                    <?php	
						}
					?>  
                <?php $this->endWidget(); ?>
            </div>
        </div>
<?php
	}
	else{
?>
		<div class="contentpanel"> 
            <div class="panel-heading">
                <div class="nothing-found"><?php echo Yii::t('app', 'No students Selected'); ?></div>
            </div>
        </div>    
<?php		
	}
?>	
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.7.1.min.js"></script>

<script type="text/javascript">
$(document).ready(function () {
	$('#demo_box_1').change(function(){
		if($('#isChkd').val() == 'true'){
			$('.dl-files').prop('checked', true);			
			$('#isChkd').val('false');
		}
		else{
			$('.dl-files').prop('checked', false);
			$('#isChkd').val('true');
		}
	});
	
	$('.dl-files').change(function(){
		var all = $('input.dl-files').length;
		var checked = $('input.dl-files:checked').length;
		if(all == checked){
			$('#demo_box_1').prop('checked', true);
			$('#isChkd').val('false');
		}else{
			$('#demo_box_1').prop('checked', false);
			$('#isChkd').val('true');
		}
	});
});


function validateForm(){
	setTimeout('window.location.reload()',300);
	var chks	=	$("[type='checkbox'][name='Downfiles[]']:checked");
	if(chks.length==0){
		alert('<?php echo Yii::t('app','Select any file'); ?>');
		return false;
	}
}
</script>