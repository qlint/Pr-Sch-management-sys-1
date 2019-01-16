
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'registration-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with').'&nbsp;';?><span class="required">*</span> <?php echo '&nbsp;'.Yii::t('app','are required.');?></p>

	<?php echo $form->errorSummary($model);?>
    <br />
<div class="formCon" >
<div class="formConInner">
<div class="text-fild-bg-block">           
<div class="text-fild-block inputstyle">
<?php echo $form->labelEx($model,Yii::t('app','hostel')); ?>
<?php echo CHtml::dropDownList('hostel',$model->hostel,CHtml::listData(Hosteldetails::model()->findAll('is_deleted=:x',array(':x'=>'0')),'id','hostel_name'),array('required'=>'required','prompt'=>Yii::t('app','Select Hostel'),
'ajax' => array(
	'type'=>'POST',
	'data'=>'js:{hostel:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
	'url'=>CController::createUrl('/hostel/room/allot'),
	'update'=>'#floorid'
	)));?>
</div>
<div class="text-fild-block inputstyle">
<?php echo $form->labelEx($model,Yii::t('app','floor')); ?>
<?php 
	if(isset($model->hostel) and $model->hostel!=NULL)
	{
		if(isset($model->hostel))
		{
			$criteria = new CDbCriteria;
			$criteria->condition = "hostel_id=:x";
			$criteria->params = array(':x'=>$model->hostel);
			$data=Floor::model()->findAll($criteria);
		}
		
			$data=CHtml::listData($data,'id','floor_no');
			echo CHtml::dropDownList('floor',$model->floor,$data,array('prompt'=>Yii::t('app','Select Floor'),'id'=>'floorid'));
		
	}
	else
	{
		echo CHtml::dropDownList('floor','',array(),array('required'=>'required','prompt'=>Yii::t('app','Select Floor'),'id'=>'floorid'));
	}?>

</div>
<div class="text-fild-block inputstyle">
<?php echo $form->labelEx($model,'student_id'); ?>
<div style="position:relative;" ><?php 
	
	if(isset($model->student_id) and $model->student_id!=NULL)
	{
		$student_id	=	$_GET['studentid'];
		$student	=	Students::model()->findByPk($student_id);
	
		if(FormFields::model()->isVisible("fullname", "Students", 'forStudentProfile')){
		   $stdname = $student->studentFullName('forStudentProfile');
		}
	}
	   if($model->isNewRecord)
	   {
				$this->widget('zii.widgets.jui.CJuiAutoComplete',
						array(
						  'name'=>'name',
						  'value'=>$stdname,
						  'id'=>'name_widget',
						  'source'=>$this->createUrl('/site/autocomplete'),
						  'htmlOptions'=>array('placeholder'=>Yii::t('app','Student Name'),'style'=>'width:178px; padding:5px 3px;'),
						  'options'=>
							 array(
								   'showAnim'=>'fold',
								   'select'=>"js:function(student, ui) {
									  $('#id_widget').val(ui.item.id);
									 
											 }"
								),
									
					
						));
		
		 }
	     else
			{
				  $this->widget('zii.widgets.jui.CJuiAutoComplete',
						array(
						  'model'=>$model,
						  'name'=>'name',
						  'id'=>'name_widget',
						   'attribute'=>'student_id',
						  'source'=>$this->createUrl('/site/autocomplete'),
						  'htmlOptions'=>array('placeholder'=>Yii::t('app','Student Name')),
						  'options'=>
							 array(
								   'showAnim'=>'fold',
								   'select'=>"js:function(student, ui) {
									  $('#id_widget').val(ui.item.id);
									 
											 }"
											 
									),
					
						));
			}
						 ?>
        <?php echo CHtml::hiddenField('student_id','',array('id'=>'id_widget')); ?>
		<?php echo CHtml::ajaxLink('',array('/site/explorer','widget'=>'1'),array('update'=>'#explorer_handler'),array('id'=>'explorer_student_name','class'=>'exp_but-n'));?></div>

</div>
</div>
<div class="text-fild-bg-block"> 
<div class="text-fild-block inputstyle">
<?php echo $form->labelEx($model,'food_preference'); ?>

<?php echo $form->dropDownList($model,'food_preference',CHtml::listData(FoodInfo::model()->findAll('is_deleted=:x',array(':x'=>0)),'id','food_preference'),array('prompt'=>Yii::t('app','Select'))); ?>
		<?php //echo $form->error($model,'food_preference'); ?>

</div>
</div>
<div class="text-fild-bg-block">
<div class="text-fild-block-full">
<div class=" inputstyle">
<?php echo $form->labelEx($model,'desc'); ?>

<?php echo $form->textArea($model,'desc',array('rows'=>6)); ?>
		<?php echo $form->error($model,'desc'); ?>
</div>
</div>
</div>



<?php /*?><table width="60%" border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','hostel')); ?></td>
    <td>&nbsp;</td>
    <td><?php echo CHtml::dropDownList('hostel',$model->hostel,CHtml::listData(Hosteldetails::model()->findAll('is_deleted=:x',array(':x'=>'0')),'id','hostel_name'),array('required'=>'required','prompt'=>Yii::t('app','Select Hostel'),
ajax' => array(
	type'=>'POST',
	data'=>'js:{hostel:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
	url'=>CController::createUrl('/hostel/room/allot'),
	update'=>'#floorid'
	)));?>
		</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','floor')); ?></td>
    <td>&nbsp;</td>
    <td><?php 
	if(isset($model->hostel) and $model->hostel!=NULL)
	{
		if(isset($model->hostel))
		{
			$criteria = new CDbCriteria;
			$criteria->condition = "hostel_id=:x";
			$criteria->params = array(':x'=>$model->hostel);
			$data=Floor::model()->findAll($criteria);
		}
		
			$data=CHtml::listData($data,'id','floor_no');
			echo CHtml::dropDownList('floor',$model->floor,$data,array('prompt'=>Yii::t('app','Select Floor'),'id'=>'floorid'));
		
	}
	else
	{
		echo CHtml::dropDownList('floor','',array(),array('required'=>'required','prompt'=>Yii::t('app','Select Floor'),'id'=>'floorid'));
	}?>
	
		</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>


  <tr> 
    <td><?php echo $form->labelEx($model,'student_id'); ?></td>
    <td>&nbsp;</td>
    <td><div style="position:relative; width:180px" ><?php 
	
	if(isset($model->student_id) and $model->student_id!=NULL)
	{
		$student_id	=	$_GET['studentid'];
		$student	=	Students::model()->findByPk($student_id);
	
		if(FormFields::model()->isVisible("fullname", "Students", 'forStudentProfile')){
		   $stdname = $student->studentFullName('forStudentProfile');
		}
	}
	   if($model->isNewRecord)
	   {
				$this->widget('zii.widgets.jui.CJuiAutoComplete',
						array(
						  name'=>'name',
						  value'=>$stdname,
						  id'=>'name_widget',
						  source'=>$this->createUrl('/site/autocomplete'),
						  htmlOptions'=>array('placeholder'=>Yii::t('app','Student Name'),'style'=>'width:131px; padding:5px 3px;'),
						  options'=>
							 array(
								   showAnim'=>'fold',
								   select'=>"js:function(student, ui) {
									  $('#id_widget').val(ui.item.id);
									 
											 }"
								),
									
					
						));
		
		 }
	     else
			{
				  $this->widget('zii.widgets.jui.CJuiAutoComplete',
						array(
						  model'=>$model,
						  name'=>'name',
						  id'=>'name_widget',
						   attribute'=>'student_id',
						  source'=>$this->createUrl('/site/autocomplete'),
						  htmlOptions'=>array('placeholder'=>Yii::t('app','Student Name')),
						  options'=>
							 array(
								   showAnim'=>'fold',
								   select'=>"js:function(student, ui) {
									  $('#id_widget').val(ui.item.id);
									 
											 }"
											 
									),
					
						));
			}
						 ?>
        <?php echo CHtml::hiddenField('student_id','',array('id'=>'id_widget')); ?>
		<?php echo CHtml::ajaxLink('',array('/site/explorer','widget'=>'1'),array('update'=>'#explorer_handler'),array('id'=>'explorer_student_name','class'=>'exp_but'));?></div></td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'food_preference'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->dropDownList($model,'food_preference',CHtml::listData(FoodInfo::model()->findAll('is_deleted=:x',array(':x'=>0)),'id','food_preference'),array('prompt'=>Yii::t('app','Select'))); ?>
		<?php //echo $form->error($model,'food_preference'); ?></td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'desc'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'desc',array('size'=>20,'style'=>'width:132px;')); ?>
		<?php echo $form->error($model,'desc'); ?></td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
</table><?php */?>

	<div class="row buttons  text-fild-block-full">
		<?php echo CHtml::submitButton($model->isNewRecord ?  Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>
</div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->