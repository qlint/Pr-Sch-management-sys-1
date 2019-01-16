<div class="form">



<?php $form=$this->beginWidget('CActiveForm', array(

	'id'=>'book-form',

	'enableAjaxValidation'=>false,

)); ?>



	<p class="note"><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app','are required.');?></p>

    <div class="formCon">

    <div class="formConInner">

 

<div class="text-fild-bg-block">           

<div class="text-fild-block inputstyle">

<?php echo $form->labelEx($model,Yii::t('app','isbn')); ?>

<?php echo $form->textField($model,'isbn',array()); ?>

<?php echo $form->error($model,'isbn'); ?>

</div>

<div class="text-fild-block inputstyle">

<?php echo $form->labelEx($model,Yii::t('app','title')); ?>

<?php echo $form->textField($model,'title',array()); ?>

<?php  echo $form->error($model,'title'); ?>

</div>
<div class="text-fild-block inputstyle">

<?php echo $form->labelEx($model,Yii::t('app','subject')); ?>

		

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



</div>


</div>   

    

<div class="text-fild-bg-block">           


<div class="text-fild-block inputstyle">

<?php echo $form->labelEx($model,Yii::t('app','category')); ?>

<?php echo $form->dropDownList($model,'category',CHtml::listData(Category::model()->findAll(),'cat_id','cat_name'),array('prompt'=>Yii::t('app','Select'))); ?>

		<?php echo $form->error($model,'category'); ?>

</div>

<div class="text-fild-block inputstyle">

<?php echo $form->labelEx($model,Yii::t('app','author')); ?>



	

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

		<?php echo $form->error($model,'author'); ?>

</div>
<div class="text-fild-block inputstyle">

<?php echo $form->labelEx($model,Yii::t('app','edition')); ?>

	<?php echo $form->textField($model,'edition',array()); ?>

		<?php echo $form->error($model,'edition'); ?>

</div>


</div> 

<div class="text-fild-bg-block">           


<div class="text-fild-block inputstyle">

<?php echo $form->labelEx($model,Yii::t('app','publisher')); ?>

<?php echo $form->textField($model,'publisher',array('size'=>20)); ?>

		<?php echo $form->error($model,'publisher'); ?>

</div>
<div class="text-fild-block inputstyle">

<?php echo $form->labelEx($model,Yii::t('app','copy')); ?>

<?php 

		$model->copy = $model->copy;

		echo $form->textField($model,'copy'); ?>

		<?php echo $form->error($model,'copy'); ?>

</div>

<div class="text-fild-block inputstyle">

<?php echo $form->labelEx($model,Yii::t('app','book_position')); ?>

<?php echo $form->textField($model,'book_position'); ?>

		<?php echo $form->error($model,'book_position'); ?>

</div>

</div>

<div class="text-fild-bg-block">           



<div class="text-fild-block inputstyle">

<?php echo $form->labelEx($model,'shelf_no'); ?>

<?php echo $form->textField($model,'shelf_no'); ?>

		<?php echo $form->error($model,'shelf_no'); ?>

</div>



</div> 



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