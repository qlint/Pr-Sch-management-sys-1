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
          <div class="txtfld-col-box">
          	<div class="txtfld-col">
            <?php 
				echo Yii::t('app','Select Course');
				
				$model		= new Courses;
				$criteria 	= new CDbCriteria;
				$criteria->compare('is_deleted',0);
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
			?>
			
            </div>
            
            <?php 
			$disp_status='none';
			if(isset($_POST['cid']) && $_POST['cid']!=NULL)
			{
				$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($_POST['cid']);
				if($sem_enabled==1)
				{
					$disp_status='block';
				}
			}
			?>
			<div width="14%" style="display:<?php echo $disp_status; ?>; padding-right: 10px" id="sem_div">  
			<strong><?php echo Yii::t('app','Select Semester'); ?></strong><br>
			<?php   
			if((isset($_POST['year']) && $_POST['year']!=NULL) && (isset($_POST['course_id']) && $_POST['course_id']!=NULL))
			{
				$criteria=new CDbCriteria;
				$criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id';
				$criteria->condition='`sc`.course_id =:course_id';
				$criteria->params=array(':course_id'=>$_REQUEST['course_id']);
				$data	= Semester::model()->findAll($criteria);			
				$data	= CHtml::listData($data, 'id', 'name');	
			}
			else
			{
				$data =  array();
			}
			echo CHtml::dropDownList('semester_id',(isset($_POST['semester_id']))?$_POST['semester_id']:'',$data,array('prompt'=>Yii::t('app','Select'),
			'ajax' => array(
				'type'=>'POST',
				'url'=>CController::createUrl('batches/batches'),
				'update'=>'#batch_id',
				'beforeSend'=>'js:function(){                                                                                               
					$("#batch_id").find("option").not(":first").remove();                                                                                             
				}', 
				'data'=>'js:{year:$("#year_drop").val(),course_id:$("#course_id").val(), semester_id:$(this).val(), id:"'.$_REQUEST['id'].'", "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
			),
			'disabled'=>(isset($_POST['action']) and ($_POST['action']==-1 or $_POST['action']==1))?false:true,
			//'style'=>'width:170px;',
			'id'=>'semester_id',
			'options' => array()));
			?>
			
			</div>
            <div class="txtfld-col">
            	<?php 
					echo Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");
					
					if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){
						$batch_list = CHtml::listData(Batches::model()->findAllByAttributes(array('course_id'=>$_REQUEST['cid'],'is_active'=>1,'is_deleted'=>0)),'id','name');
						echo CHtml::dropDownList('batchid','',$batch_list,array('prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'id'=>'batchid','style'=>'width:190px;', 'options'=>array($_REQUEST['bid']=>array('selected'=>true)), 'ajax' => array(
						'type'=>'POST',
						'url'=>CController::createUrl('/examination/exam/subjectname'),
						'update'=>'#subjectid', 'options'=>array($_REQUEST['batchid']=>array('selected'=>true))),));
					}
					else{
							echo CHtml::dropDownList('batchid','',array(),array('prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'id'=>'batchid','style'=>'width:190px;',  'ajax' => array(
						'type'=>'POST',
						'url'=>CController::createUrl('/examination/exam/subjectname'),
						'update'=>'#subjectid',)));
					}
				?>
            </div>
            <div class="txtfld-col">
            	<?php 
					echo Yii::t('app','Select Subject');
					
					if(isset($_REQUEST['subjectid']) and $_REQUEST['subjectid']!=NULL){
						$subject_list = CHtml::listData(Subjects::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid'],'is_deleted'=>0)),'id','name');
						echo CHtml::dropDownList('subjectid','',$subject_list,array('prompt'=>Yii::t('app','Select Subject'),'id'=>'subjectid','style'=>'width:190px;', 'onchange'=>'displaytable()', 'options'=>array($_REQUEST['subjectid']=>array('selected'=>true)),));
					}
					else{
						echo CHtml::dropDownList('subjectid','',array(),array('prompt'=>Yii::t('app','Select Subject'),'id'=>'subjectid','style'=>'width:190px;', 'onchange'=>'displaytable()'));
					}
				?>
            </div>
          </div>
          <div class="text-fild-block-full">
          
          </div>
          
            
          </div>
        </div>
<?php
	if(isset($_REQUEST['cid']) and isset($_REQUEST['bid']) and isset($_REQUEST['subjectid']))
	{
		$students = Students::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid'],'is_active'=>1,'is_deleted'=>0));
		$batch_id = $_REQUEST['bid'];
		if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){ // for cbsc format
			$exam_groups = CbscExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid']));
		}
		else{
			$exam_groups = ExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid']));
		}
		
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
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php echo CHtml::link('Generate Excel', array('/examination/exam/excelreport','batchid'=>$_REQUEST['bid'], 'subjectid'=>$_REQUEST['subjectid']), array('target'=>"_blank",'class'=>'excel_but-input')); ?></li>
<li><?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/examination/exam/printpdf','batchid'=>$_REQUEST['bid'], 'subjectid'=>$_REQUEST['subjectid']),array('target'=>"_blank",'class'=>'pdf_but-input')); ?></li>
                                  
</ul>
</div> 

</div>

                            

        <div class="pdtab_Con">
          <div style="font-size:13px; padding:5px 0px"><strong><?php echo Yii::t('app','Scores');?></strong></div>
            <div class="table-scroll">
            <div class="table-scroll-width">
          <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
              <tr class="pdtab-h">
                <td  align="center" width="25%"><?php echo Yii::t('app','Admission Number');?></td>
                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                <td align="center"  width="35%"><?php echo Yii::t('app','Student Name');?></td>
                <?php } ?>
                <?php if(in_array('date_of_birth', $student_visible_fields)){?>
                <td align="center"  width="20%"><?php echo Yii::t('app','Date Of Birth');?></td>
                <?php } ?>
				<?php foreach($exam_groups as $exam_group)
                { ?>
                    <td align="center" width="20%">
						<?php echo ucfirst($exam_group->name);?>
                        <?php echo Yii::t('app','Score');?>
                    </td>
                    <?php } ?>
        <?php   if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)!= 2){  ?>        
                    <td align="center" width="20%">
                        <?php echo Yii::t('app','Status');?>
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
					$status	=0;
               foreach($exam_groups as $exam_group)
                  { 
				  	if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){
                    	$exam = CbscExams::model()->findByAttributes(array('subject_id'=>$_REQUEST['subjectid'],'exam_group_id'=>$exam_group->id));
						$exam_score = CbscExamScores::model()->findByAttributes(array('student_id'=>$student->id,'exam_id'=>$exam->id));
				    }
					else{
						$exam = Exams::model()->findByAttributes(array('subject_id'=>$_REQUEST['subjectid'],'exam_group_id'=>$exam_group->id));
						$exam_score = ExamScores::model()->findByAttributes(array('student_id'=>$student->id,'exam_id'=>$exam->id));
					}
						$min =$exam->minimum_marks;
						if(!($exam_score))
						{?>
							<td align="center"><?php echo '-';?></td>
				  <?php }
						else
						{?>
							<td align="center"><?php 
							
							if($exam_score->marks<$min){
								$status = 1;
							}
							echo $exam_score->marks?></td>
						<?php
						}
						?>
                  
            <?php 
				}
			 if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)!= 2){  ?>   
			
          		  <td align="center" width="20%">
                        <?php if($status  == 0){
								echo "<span style='color:#006600'>".Yii::t('app','Passed').$roles."</span>";
							}else{
								 echo "<span style='color:#F00'>".Yii::t('app','Failed')."</span>";
							}
							 ?>
                    </td>
           <?php } ?>
			</tr>
            <?php
		} ?>      
            		
            		<tbody>
            	</tbody>
          	</table>
            </div>
            </div>
        	</div>
      	</div>
    <?php }?>    
      </div></td>
  </tr>
</table>
<?php } ?>
<?php $this->endWidget(); ?>

