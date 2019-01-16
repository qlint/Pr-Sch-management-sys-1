<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app','are required.');?></p>
    <div class="formCon">
    <div class="formConInner">
<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','isbn')); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'isbn',array('size'=>20)); ?>
		<?php echo $form->error($model,'isbn'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','title')); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'title',array('size'=>20)); ?>
		<?php  echo $form->error($model,'title'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','subject')); ?></td>
    <td>&nbsp;</td>
    <td>
		<?php 
			 /*$criteria = new CDbCriteria;
			 $criteria->compare('is_deleted',0);
			 echo $form->dropDownList($model,'subject',CHtml::listData(Subjects::model()->findAll($criteria),'id','name'),array('prompt'=>Yii::t('app','Select'))); */
		?>
        <?php  
			$this->widget('zii.widgets.jui.CJuiAutoComplete',
			array(
			  'model'=>$model,
			  'id'=>'subject',
			  'attribute'=>'subject',
			  'source'=>$this->createUrl('Book/listSubject'),
			  'htmlOptions'=>array('placeholder'=>Yii::t('app','Subject')),
			  'options'=>
				 array(
				   'showAnim'=>'fold',
				   'select'=>"js:function(hotel, ui) {
	
					  $('#subject').val(ui.item.id);
					 
							 }"
					),
		
			));
		?>
         <?php  echo $form->error($model,'subject'); ?>
	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','category')); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->dropDownList($model,'category',CHtml::listData(Category::model()->findAll(),'cat_id','cat_name'),array('prompt'=>Yii::t('app','Select'))); ?>
		<?php echo $form->error($model,'category'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','author')); ?></td>
    <td>&nbsp;</td>
    <td>
	
	<?php //echo $form->dropDownList($model,'author',CHtml::listData(Author::model()->findAll(),'auth_id','author_name'),array('prompt'=>'Select')); ?>
	<?php //echo $form->textField($model,'author',array('size'=>20)); ?>
    <?php //echo $form->textField($model,'client_county');
						$this->widget('zii.widgets.jui.CJuiAutoComplete',
						array(
						  'model'=>$model,
						  'id'=>'txtc',
						  'attribute'=>'author',
						  'source'=>$this->createUrl('Book/autocomplete1'),
						  'htmlOptions'=>array('placeholder'=>Yii::t('app','Author')),
						  'options'=>
							 array(
								   'showAnim'=>'fold',
								   'select'=>"js:function(hotel, ui) {
					
									  $('#txtc').val(ui.item.id);
									 
											 }"
									),
					
						));
						 ?>
		<?php echo $form->error($model,'author'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','edition')); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'edition',array('size'=>20)); ?>
		<?php echo $form->error($model,'edition'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','publisher')); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'publisher',array('size'=>20)); ?>
		<?php echo $form->error($model,'publisher'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','copy')); ?></td>
    <td>&nbsp;</td>
    <td><?php 
		$model->copy = $model->copy;
		echo $form->textField($model,'copy'); ?>
		<?php echo $form->error($model,'copy'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','book_position')); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'book_position'); ?>
		<?php echo $form->error($model,'book_position'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'shelf_no'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'shelf_no'); ?>
		<?php echo $form->error($model,'shelf_no'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
</table>
</div>
</div>

     <div class="row">
		<?php //echo $form->labelEx($model,'status'); ?>
		<?php echo $form->hiddenField($model,'status',array('value'=>'C')); ?>
		<?php //echo $form->error($model,'status'); ?>
	</div>
	<div class="row">
		<?php //echo $form->labelEx($model,'date'); ?>
		<?php echo $form->hiddenField($model,'date',array('value'=>date('Y-m-d'))); ?>
		<?php //echo $form->error($model,'date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->