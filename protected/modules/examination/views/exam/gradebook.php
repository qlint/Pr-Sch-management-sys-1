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
	var semester_id = document.getElementById('semester_id').value;
	if(course_id == ''& batch_id == '')
	{
		$('#error').html('<?php echo Yii::t('app','select course'); ?>');
		return false;
	}else if(semester_id !=""){
		window.location= 'index.php?r=examination/exam/gradebook&cid='+course_id+'&bid='+batch_id+'&subjectid='+subject_id+'&semester_id='+semester_id;
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
					$current_academic_yr=Configurations::model()->findByPk(35);
					$yr = $current_academic_yr->config_value;
					
					$criteria	= new CDbCriteria;
					$criteria->distinct		= true;                  
					$criteria->condition            = '`t`.`academic_yr_id`=:year AND `t`.`is_deleted`=:is_deleted';
					$criteria->params		= array(':year'=>$yr, ':is_deleted'=>0);
					$criteria->order		= '`t`.`course_name` ASC';
					$data	= Courses::model()->findAll($criteria);                   
					$data		= CHtml::listData($data, 'id', 'course_name');
					
				  echo CHtml::dropDownList('cid',(isset($_REQUEST['cid']))?$_REQUEST['cid']:'',$data,array('encode'=>false,'prompt'=>Yii::t('app','Select'),
					'ajax' => array(
					'type'=>'POST',
					'url'=>CController::createUrl('/examination/exam/semesters'),
					'dataType'=>'JSON',
					//'update'=>'#batch_id',
					'beforeSend'=>'js:function(){
						
								$("#semester_id").find("option").not(":first").remove();
								$("#batchid").find("option").not(":first").remove();                                                                                                
								$("#sem_div").hide();
					}', 
					'success'=>'js:function(response){
					if(response.status=="success")
					{
						if(response.sem_status=="1")
						{
							$("#sem_div").show();
							$("#semester_id").html(response.semester);
						}
							$("#batchid").html(response.batch);
					}
						
					}',
					'data'=>'js:{year:$("#year_drop").val(),cid:$(this).val(), id:"'.$_REQUEST['id'].'", "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
					), 
					//'style'=>'width:170px;',
					'id'=>'cid',
					'encode'=>false,
					'options' => array()));
			?>
			
            </div>
            
            <?php 
			$disp_status='none';
			if(isset($_REQUEST['cid']) && $_REQUEST['cid']!=NULL)
			{
				$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($_REQUEST['cid']);
				if($sem_enabled==1)
				{
					$disp_status='block';
				}
			}
			?>
			<div  style="display:<?php echo $disp_status; ?>;" class="txtfld-col" id="sem_div">  
			<?php echo Yii::t('app','Select Semester'); ?>
			<?php $criteria=new CDbCriteria;
				$criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id';
				$criteria->condition='`sc`.course_id =:course_id';
				$criteria->params=array(':course_id'=>$_REQUEST['cid']);
				$data	= Semester::model()->findAll($criteria);			
				$data	= CHtml::listData($data, 'id', 'name');
				$data_list 		= CMap::mergeArray(array(0=>Yii::t('app','Batch without semester')),$data);	 
			echo CHtml::dropDownList('semester_id',(isset($_REQUEST['semester_id']))?$_REQUEST['semester_id']:'',$data_list,array('encode'=>false,'prompt'=>Yii::t('app','Select'),
			'ajax' => array(
				'type'=>'POST',
				'url'=>CController::createUrl('/examination/exam/batches'),
				'update'=>'#batchid',
				'beforeSend'=>'js:function(){                                                                                               
					$("#batch_id").find("option").not(":first").remove();                                                                                             
				}', 
				'data'=>'js:{status:1,cid:$("#cid").val(), semester_id:$(this).val(), id:"'.$_REQUEST['id'].'", "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
			),  
			'id'=>'semester_id',
			'encode'=>false,
			'options' => array()));
			?>
			
			</div>
            <div class="txtfld-col">
            	<?php 
					echo Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");
					
					if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){
						$batch_list = CHtml::listData(Batches::model()->findAllByAttributes(array('course_id'=>$_REQUEST['cid'],'semester_id'=>$_REQUEST['semester_id'],'is_active'=>1,'is_deleted'=>0)),'id','name');
						echo CHtml::dropDownList('batchid','',$batch_list,array('encode'=>false,'prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'id'=>'batchid','style'=>'width:190px;', 'options'=>array($_REQUEST['bid']=>array('selected'=>true)), 'ajax' => array(
						'type'=>'POST',
						'url'=>CController::createUrl('/examination/exam/subjectname'),
						'update'=>'#subjectid', 'options'=>array($_REQUEST['batchid']=>array('selected'=>true))),));
					}
					else{
							echo CHtml::dropDownList('batchid','',array(),array('encode'=>false,'prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'id'=>'batchid','style'=>'width:190px;',  'ajax' => array(
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
						echo CHtml::dropDownList('subjectid','',$subject_list,array('encode'=>false,'prompt'=>Yii::t('app','Select Subject'),'id'=>'subjectid','style'=>'width:190px;', 'onchange'=>'displaytable()', 'options'=>array($_REQUEST['subjectid']=>array('selected'=>true)),));
					}
					else{
						echo CHtml::dropDownList('subjectid','',array(),array('encode'=>false,'prompt'=>Yii::t('app','Select Subject'),'id'=>'subjectid','style'=>'width:190px;', 'onchange'=>'displaytable()'));
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
		$students = BatchStudents::model()->BatchStudent($_REQUEST['bid']);
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
	if(ExamFormat::model()->getExamformat($batch_id)== 2){
	echo '<div class="listhdg" align="center">'.Yii::t('app','Cannot manage Grade Book for this batch').'</div>';
	}else
	echo '<div class="listhdg" align="center">'.Yii::t('app','No Students Found!!').'</div>';
}
elseif(!($exam_groups))
{
	if(ExamFormat::model()->getExamformat($batch_id)== 2){
        echo '<div class="listhdg" align="center">'.Yii::t('app','Cannot manage Grade Book for this batch').'</div>';
    }else
	echo '<div class="listhdg" align="center">'.Yii::t('app','No Exams Found!!').'</div>';
}
else
{?>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php echo CHtml::link('Generate Excel', array('/examination/exam/excelreport','batchid'=>$_REQUEST['bid'], 'subjectid'=>$_REQUEST['subjectid']), array('target'=>"_blank",'class'=>'excel_but-input')); ?></li>
<li><?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/examination/exam/printpdf','batchid'=>$_REQUEST['bid'], 'subjectid'=>$_REQUEST['subjectid'], 'subjectid'=>$_REQUEST['subjectid']),array('target'=>"_blank",'class'=>'pdf_but-input')); ?></li>
                                  
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
				  	if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){
                    	$exam = CbscExams::model()->findByAttributes(array('subject_id'=>$_REQUEST['subjectid'],'exam_group_id'=>$exam_group->id));
						$exam_score = CbscExamScores::model()->findByAttributes(array('student_id'=>$student->id,'exam_id'=>$exam->id));
				    }
					else{
						$exam = Exams::model()->findByAttributes(array('subject_id'=>$_REQUEST['subjectid'],'exam_group_id'=>$exam_group->id));
						$exam_score = ExamScores::model()->findByAttributes(array('student_id'=>$student->id,'exam_id'=>$exam->id));
					}
						$min =$exam->minimum_marks;
						if(($exam_score == NULL))
						{?>
							<td align="center"><?php echo '-';?></td>
				  <?php }
						else
						{
							?>
							<td align="center">
								<?php echo $exam_score->marks.'('.ExamScores::model()->getDefaultgradinglevel($_REQUEST['bid'],$exam_score->marks).')';
								
								?>
							</td>
						<?php
						}
						?>
                  
            <?php 
				}
				?>
			 
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

