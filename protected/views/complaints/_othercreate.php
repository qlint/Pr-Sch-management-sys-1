<style type="text/css">
.complaint_drop{
	width:213px;
}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app', 'Register Complaint')=>array('/complaints/create'),	
);


?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
<?php 

	$leftside = 'mailbox.views.default.left_side';
	
	
	$this->renderPartial($leftside);
?>



</td>
    <td valign="top">
        <div class="cont_right formWrapper">

            	<h1><?php echo Yii::t("app",'Register Complaint');?></h1>
 


 <?php 
 $form=$this->beginWidget('CActiveForm', array(
	'id'=>'complaints-_form-form',
	'enableAjaxValidation'=>false,
)); 
 
 
 ?>


	<p class="note"><?php echo Yii::t("app",'Fields with');?> <span class="required">*</span><?php echo Yii::t("app", 'are required.');?></p>

	<?php //echo $form->errorSummary($model); ?><br />

    	<?php
		$categories=ComplaintCategories::model()->findAll();
		?>


<div class="formCon">
<div class="formConInner">	
<table width="75%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><strong><?php echo $form->labelEx($model,'category_id'); ?></strong></td>
		<td>&nbsp;</td>
		<td><?php echo $form->dropDownList($model, 'category_id',
                CHtml::listData($categories, 'id', 'categorynames'),array('prompt'=>Yii::t("app",'Select'),'class'=>'complaint_drop'));?>                
                <?php echo $form->error($model,'category_id'); ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td> <strong><?php echo $form->labelEx($model,'subject'); ?></strong></td>
		<td>&nbsp;</td>
		<td> <?php echo $form->textField($model,'subject',array('class'=>'form-control', 'size'=>30)); ?>
            <?php echo $form->error($model,'subject'); ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><strong><?php echo $form->labelEx($model,'complaint'); ?></strong></td>
		<td>&nbsp;</td>
		<td><?php echo $form->textArea($model,'complaint',array('rows'=>3, 'cols'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'complaint'); ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><?php echo CHtml::submitButton(Yii::t("app",'Submit'),array('class'=>'formbut')); ?>
        <input type="reset" value="Reset" class="membergray_but formbut" style="padding:7px 15px 8px 15px;"></td>
	</tr>
</table>
	
</div>
</div>
   

	<div class=" buttons">
		
		
		</div>
		
		<?php $this->endWidget(); ?>

 </div>
		</td>
	


  </tr>
</table>
