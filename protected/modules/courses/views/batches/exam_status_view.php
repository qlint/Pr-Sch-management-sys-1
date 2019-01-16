<style>
#jobDialog_comment{
	height:auto !important;
	
}
.seen-by{    margin: 0px 0px 17px 4px; padding:0px;}
.seen-by li{ list-style:none; display:block; background:url(images/bread-arrow.png) no-repeat left 3px; color:#868686;    padding: 0px 10px;}
.name-icon1{ background:url(images/bread-arrow.png) no-repeat left}
.seen-h4{     border-bottom: 1px solid#ececec;margin-bottom: 5px;}
.seen-h4 h4{ font-size:12px; font-family:Tahoma, Geneva, sans-serif; font-weight:600; color:#444; margin: 0px 0px 5px 4px;}
.ui-dialog .ui-dialog-title {
    float: left;
    color: #585858;
	font-weight: 900;
    background:url(images/info-icon.png) no-repeat left;
	padding: 10px 20px;
	font-size: 17px;
}

.ui-dialog .ui-dialog-titlebar {
    padding: 2px 0px 2px 10px !important;
}

.student-popup-table{
	border-collapse:collapse;
	 margin-top:12px;	
}
.student-popup-table th{
	border: 1px solid#ccc;
	font-size: 13px;
	text-transform: uppercase;
	color: #7d7d2c;
	background-color: #fbfbee;
}
.student-popup-table td{
	border: 1px solid#ccc;
	font-size: 12px;
	text-transform: uppercase;
	#9a9a9a
	padding:8px;
}

.student-popup-table .rsn-table{
	border: 1px solid#ccc;
	font-size: 12px;
	text-transform: uppercase;
	color: #717171;
	padding:15px;	
}
.passed{
	color:#4f8a10;
}
.failed{
	color:#F00;
}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'jobDialog_view',
	'options'=>array(
		'title'=>Yii::t('app','Exams Details'),
		'autoOpen'=>true,
		'modal'=>'true',
		'width'=>'auto',
		'height'=>'auto',
		'resizable'=>false,	
		'close'=>'js:function(){ window.location.reload(); }',
			
   ),
));	

$id 		= $_REQUEST['id'];
$batch_id	= $_REQUEST['batch_id'];
$student	= Students::model()->findByPk($id);
$batch_type  = ExamFormat::model()->getExamformat($batch_id);
$criteria				= new CDbCriteria();
$criteria->condition	= 'batch_id=:batch_id';
$criteria->params		= array(':batch_id'=>$batch_id);
if($batch_type == 1){
$exam_groups			= ExamGroups::model()->findAll($criteria);
}
else
{
$exam_groups			= CbscExamGroup17::model()->findAll($criteria);
}
$batch_student			= BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$batch_id, 'result_status'=>0));
?>

<div class="view-dialog-popup">
	<table width="400" border="0" cellpadding="0" cellspacing="0" class="student-popup-table">
        <thead>
            <tr>
                <th width="100px;" align="center"><?php echo Yii::t('app','Name'); ?></th>
                <th align="center"><?php echo $student->studentFullName('forStudentProfile');; ?></th>
            </tr>
			 <?php if(Configurations::model()->rollnoSettingsMode() != 1){?>       
				<tr>
					<th width="100px;" align="center">
					<?php echo Students::model()->getAttributeLabel('admission_no'); ?></th>
					<th align="center"><?php echo $student->admission_no; ?></th>
				</tr>
			<?php } ?>
			 <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
				 <tr>
					<th width="100px;" align="center">
					<?php echo Yii::t('app','Roll No'); ?></th>
					<th align="center"><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
													echo $batch_student->roll_no;
												}
												else{
													echo '-';
												}?></th>
				</tr>
			<?php } ?>
        </thead>
    </table> 
    <h3><?php echo Yii::t('app', 'Exams'); ?></h3>       
    <table width="400" border="0" cellpadding="0" cellspacing="0" class="student-popup-table">
        <thead>
            <tr>
                <th width="100px;" align="center"><?php echo Yii::t('app','Exams'); ?></th>
                <th align="center"><?php echo Yii::t('app','Status'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php
if($batch_type == 1){
			if($exam_groups){
				foreach($exam_groups as $exam_group){
					
?>
					<tr>
                    	<td width="100px;" align="center" class="rsn-table"><?php echo CHtml::link(ucfirst($exam_group->name), array('/examination/exams/create', 'exam_group_id'=>$exam_group->id, 'id'=>$batch_id), array('target'=>'_balnk')); ?></td>
                        <td align="center">
<?php
							if($exam_group->is_published == 0){
								echo Yii::t('app', 'Exam Not Published');
							}
							else if($exam_group->result_published == 0){
								echo Yii::t('app', 'Result Not Published');
							}
							else{
												
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'exam_group_id=:exam_group_id';
								$criteria->params		= array(':exam_group_id'=>$exam_group->id);
								$exams					= Exams::model()->findAll($criteria);
								if($exams){
									foreach($exams as $exam){
										$status  	= '';
										$min_mark	= $exam->minimum_marks;
										
										$subject	= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id, 'is_deleted'=>0));
										if($subject){
											
											if($subject->elective_group_id == 0){ //check whether the subject is normal subject
												$exam_score	= ExamScores::model()->findByAttributes(array('student_id'=>$id, 'exam_id'=>$exam->id));
												if($exam_score){ //check whether the student wrote the exam
													if($exam_score->marks < $min_mark){ //Check whether the student passed or not
														$status	= '<span class="failed">'.Yii::t('app', 'Failed').'</span>';
													}
													else{ //Check whether the student passed or not
														$status	= '<span class="passed">'.Yii::t('app', 'Passed').'</span>';
													}
												}
												else{ //If score not found, mark it as failed
													$status	= '<span class="failed">'.Yii::t('app', 'Failed').'</span>';
												}
												
											}
											else{ //In case of elective subject
												$student_elective	= StudentElectives::model()->findByAttributes(array('student_id'=>$id, 'elective_group_id'=>$subject->elective_group_id, 'status'=>1));
												if($student_elective){
													$exam_score	= ExamScores::model()->findByAttributes(array('student_id'=>$id, 'exam_id'=>$exam->id));
													if($exam_score){ //check whether the student wrote the exam
														if($exam_score->marks < $min_mark){ //Check whether the student passed or not
															$status	= '<span class="failed">'.Yii::t('app', 'Failed').'</span>';
														}
														else{ //Check whether the student passed or not
															$status	= '<span class="passed">'.Yii::t('app', 'Passed').'</span>';
													}
													}
													else{ //If score not found, mark it as failed
														$status	= '<span class="failed">'.Yii::t('app', 'Failed').'</span>';
													}
												}
											}
										}
										
									}
									echo $status;
								}
							
							}
?>                        	
                        </td>
                    </tr>
<?php					
				}				
			}
			else{
?>
				<tr>
                	<td width="100px;" align="center" class="rsn-table nothing-found" colspan="2"><?php echo Yii::t('app', 'No Exams Found'); ?></td>
                </tr>
<?php				
			}
			}
			else{
				if($exam_groups){
					
				foreach($exam_groups as $exam_group){
					?>
					<tr>
                    	<td width="100px;" align="center" class="rsn-table"><?php echo CHtml::link(ucfirst($exam_group->name), array('/examination/exams/create', 'exam_group_id'=>$exam_group->id, 'id'=>$batch_id), array('target'=>'_balnk')); ?></td>
                        <td align="center">
<?php
							if($exam_group->date_published == 0){
								echo Yii::t('app', 'Exam Not Published');
							}
							else if($exam_group->result_published == 0){
								echo Yii::t('app', 'Result Not Published');
							}
							else{
												
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'exam_group_id=:exam_group_id';
								$criteria->params		= array(':exam_group_id'=>$exam_group->id);
								$exams					= CbscExams17::model()->findAll($criteria);
								if($exams){
									foreach($exams as $exam){
										$status  	= '';
										$min_mark	= $exam->minimum_marks;
										$subject	= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id, 'is_deleted'=>0));
										if($subject){
											
											if($subject->elective_group_id == 0){ //check whether the subject is normal subject
												$exam_score	= CbscExamScores17::model()->findByAttributes(array('student_id'=>$id, 'exam_id'=>$exam->id));
												if($exam_score){ //check whether the student wrote the exam
													if($exam_score->total < $min_mark){ //Check whether the student passed or not
														$status	= '<span class="failed">'.Yii::t('app', 'Failed').'</span>';
													}
													else{ //Check whether the student passed or not
														$status	= '<span class="passed">'.Yii::t('app', 'Passed').'</span>';
													}
												}
												else{ //If score not found, mark it as failed
													$status	= '<span class="failed">'.Yii::t('app', 'Failed').'</span>';
												}
												
											}
											else{ //In case of elective subject
												$student_elective	= StudentElectives::model()->findByAttributes(array('student_id'=>$id, 'elective_group_id'=>$subject->elective_group_id, 'status'=>1));
												if($student_elective){
													$exam_score	= CbscExamScores17::model()->findByAttributes(array('student_id'=>$id, 'exam_id'=>$exam->id));
													if($exam_score){ //check whether the student wrote the exam
														if($exam_score->total < $min_mark){ //Check whether the student passed or not
															$status	= '<span class="failed">'.Yii::t('app', 'Failed').'</span>';
														}
														else{ //Check whether the student passed or not
															$status	= '<span class="passed">'.Yii::t('app', 'Passed').'</span>';
													}
													}
													else{ //If score not found, mark it as failed
														$status	= '<span class="failed">'.Yii::t('app', 'Failed').'</span>';
													}
												}
											}
										}
										
									}
									echo $status;
								}
							
							}
?>                        	
                        </td>
                     </tr>
					
			<?php }
				}
				else{
?>
				<tr>
                	<td width="100px;" align="center" class="rsn-table nothing-found" colspan="2"><?php echo Yii::t('app', 'No cbscExams Found'); ?></td>
                </tr>
<?php		
				}
			}
?>        	
        </tbody>
	</table>
</div>            

<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
