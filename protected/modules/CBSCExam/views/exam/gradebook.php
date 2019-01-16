<?php
$this->breadcrumbs=array(
	Yii::t('app','Examination')=>array('/examination'),
	Yii::t('app','Grade Book'),
);

$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'gradebook-form',
'enableAjaxValidation'=>false,
)); ?>

<script>
function displaytable() // Function to update mode dependent dropdown after selecting batch
{
	var course_id = document.getElementById('cid').value;
	var batch_id = document.getElementById('batchid').value;
	var subject_id = document.getElementById('subjectid').value;
	if(course_id == ''& batch_id == '')
	{
		$('#error').html('<?php echo Yii::t('app','select course'); ?>');
		return false;
	}
	else
	{
	window.location= 'index.php?r=examination/exam/gradebook&cid='+course_id+'&bid='+batch_id+'&subjectid='+subject_id;
	}
}
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top"><?php $this->renderPartial('/default/left_side');?></td>
    <td valign="top"><!-- div class="cont_right" -->
      
      <div class="cont_right">
        <h1><?php echo Yii::t('app','Grade Book');?></h1>
        
        <!-- DROP DOWNS -->
        <div class="formCon">
          <div class="formConInner">
            <table width="90%" border="0" cellspacing="0" cellpadding="0" class="s_search">
              <tr>
                <td>&nbsp;</td>
                <td style="width:200px;"><strong><?php echo Yii::t('app','Select Course');?></strong></td>
                <td>&nbsp;</td>
                <?php
                                $model=new Courses;
                                $criteria = new CDbCriteria;
                                $criteria->compare('is_deleted',0); ?>
                <td><?php
									$current_academic_yr = Configurations::model()->findByPk(35);
									$data = Courses::model()->findAllByAttributes(array('is_deleted'=>0,'academic_yr_id'=>$current_academic_yr->config_value),array('order'=>'id DESC'));  
									
                                    echo CHtml::dropDownList('cid','',CHtml::listData($data,'id','course_name'),array('prompt'=>Yii::t('app','Select Course'),'style'=>'width:190px;','options'=>array($_REQUEST['cid']=>array('selected'=>true)),
                                    'ajax' => array(
                                    'type'=>'POST',
                                    'url'=>CController::createUrl('/examination/exam/batchname'),
									'success' => 'function(data){
										$("#subjectid").html("<option value=\"\">Select Subject</option>");
										$("#batchid").html(data); 
									}',
                                    
									)));
									
									
                                    ?></td>
              </tr>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><strong><?php echo Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></strong></td>
                <td>&nbsp;</td>
                <td><?php  
								   	if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL)
									{
										$batch_list = CHtml::listData(Batches::model()->findAllByAttributes(array('course_id'=>$_REQUEST['cid'],'is_active'=>1,'is_deleted'=>0)),'id','name');
										echo CHtml::dropDownList('batchid','',$batch_list,array('prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'id'=>'batchid','style'=>'width:190px;', 'options'=>array($_REQUEST['bid']=>array('selected'=>true)), 'ajax' => array(
                                    'type'=>'POST',
                                    'url'=>CController::createUrl('/examination/exam/subjectname'),
                                    'update'=>'#subjectid', 'options'=>array($_REQUEST['batchid']=>array('selected'=>true))),));
									}
									else
									{
										echo CHtml::dropDownList('batchid','',array(),array('prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'id'=>'batchid','style'=>'width:190px;',  'ajax' => array(
                                    'type'=>'POST',
                                    'url'=>CController::createUrl('/examination/exam/subjectname'),
                                    'update'=>'#subjectid',)));
									}
                                    
                                    ?></td>
              </tr>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
              .
<!--              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>-->
              <tr>
                <td>&nbsp;</td>
                <td><strong><?php echo Yii::t('app','Select Subject');?></strong></td>
                <td>&nbsp;</td>
                <td><?php  
								   	if(isset($_REQUEST['subjectid']) and $_REQUEST['subjectid']!=NULL)
									{
										$subject_list = CHtml::listData(Subjects::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid'],'is_deleted'=>0)),'id','name');
										echo CHtml::dropDownList('subjectid','',$subject_list,array('prompt'=>Yii::t('app','Select Subject'),'id'=>'subjectid','style'=>'width:190px;', 'onchange'=>'displaytable()', 'options'=>array($_REQUEST['subjectid']=>array('selected'=>true)),));
									}
									else
									{
										echo CHtml::dropDownList('subjectid','',array(),array('prompt'=>Yii::t('app','Select Subject'),'id'=>'subjectid','style'=>'width:190px;', 'onchange'=>'displaytable()',
									));
									}
                                    ?></td>
              </tr>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
            </table>
          </div>
        </div>
<?php
	if(isset($_REQUEST['cid']) and isset($_REQUEST['bid']) and isset($_REQUEST['subjectid']))
	{
		$students = Students::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid']));
		$exam_groups = ExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid']));
?>	
<?php if(!($students))
{
	echo '<div class="listhdg" align="center">'.Yii::t('app','No Students Found!!').'</div>';
}
elseif(!($exam_groups))
{
	echo '<div class="listhdg" align="center">'.Yii::t('app','No Exams Found!!').'</div>';
}
else
{?>
<div class="ea_pdf" style="top:111px;">
								<?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/examination/exam/printpdf','batchid'=>$_REQUEST['bid'], 'subjectid'=>$_REQUEST['subjectid']),array('target'=>"_blank",'class'=>'pdf_but')); ?>
</div>


		<div class="ea_pdf" style="top:170px;">
             <?php echo CHtml::link('<img src="images/exel_ico.png" border="0" />', array('/examination/exam/excelreport','batchid'=>$_REQUEST['bid'], 'subjectid'=>$_REQUEST['subjectid']), array('target'=>"_blank")); ?>
           </div>
                            

        <div style="width:97%" class="pdtab_Con">
          <div style="font-size:13px; padding:5px 0px"><strong><?php echo Yii::t('app','Scores');?></strong></div>
          <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
              <tr class="pdtab-h">
                <td height="18" align="center"><?php echo Yii::t('app','Admission Number');?></td>
                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                <td align="center"><?php echo Yii::t('app','Student Name');?></td>
                <?php } ?>
                <?php if(in_array('date_of_birth', $student_visible_fields)){?>
                <td align="center"><?php echo Yii::t('app','Date Of Birth');?></td>
                <?php } ?>
     <?php foreach($exam_groups as $exam_group)
		{ ?>
		   
                <td align="center">
                
                	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0px; padding:0px">
                   
                      <tr>
                      
                        <td align="center" style="border-left:0px;border-right:0px;border-top:0px; border-bottom:1px #CCCCCC solid; padding:0px 0px 10px 0px"><?php echo $exam_group->name;?>
		</td>
                      </tr>
                      <tr>
                        <td align="center" style="border:0px; padding:10px 0px 0px 0px"><?php echo Yii::t('app','Score');?></td>
                        
                      </tr>
                  
                    </table>
                      

                </td>
                 <?php } ?> 
              </tr>
				
              
      <?php foreach($students as $student)
		{ ?> 
    
        	<tr>
                <td align="center"><?php echo $student->admission_no;?></td>
                <?php
                if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){
			       ?>
                <td align="center"><?php echo $student->studentFullName("forStudentProfile"); ?></td>
                <?php
				}
			    ?>
                <?php if(in_array('date_of_birth', $student_visible_fields)){?>
                <td align="center"><?php 
								$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($student->date_of_birth));
									echo $date1;
		
								}
								else
								echo $student->date_of_birth;?></td>
                                <?php } ?>
					<?php 
               foreach($exam_groups as $exam_group)
                  { 
                    $exam = Exams::model()->findByAttributes(array('subject_id'=>$_REQUEST['subjectid'],'exam_group_id'=>$exam_group->id));
					
						$exam_score = ExamScores::model()->findByAttributes(array('student_id'=>$student->id,'exam_id'=>$exam->id));
						if(!($exam_score))
						{?>
							<td align="center"><?php echo '-';?></td>
				  <?php }
						else
						{?>
							<td align="center"><?php echo $exam_score->marks;?></td>
						<?php
						}
						?>
                  
            <?php 
				}
			?>
			</tr>
            <?php
		} ?>      
            					</tbody>
            				<tbody>
            			</tbody>
            		<tbody>
            	</tbody>
          	</table>
        	</div>
      	</div>
    <?php }?>    
      </div></td>
  </tr>
</table>
<?php } ?>
<?php $this->endWidget(); ?>

