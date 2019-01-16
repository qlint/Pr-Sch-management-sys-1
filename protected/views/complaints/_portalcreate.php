<div class="pageheader">
  <div class="col-lg-8">
    <h2><i class="fa fa-comment"></i><?php echo Yii::t("app",'Complaints');?><span><?php echo Yii::t("app",'Create');?></span></h2>
  </div>
  <div class="col-lg-2">
      </div>
  <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t("app",'You are here:');?></span>
    <ol class="breadcrumb">
      <!--<li><a href="index.html">Home</a></li>-->
      
      <li class="active"><?php echo Yii::t("app",'Complaints')?></li>
    </ol>
  </div>
  <div class="clearfix"></div>
</div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'complaints-_form-form',
	'enableAjaxValidation'=>false,
)); 

	$leftside = 'mailbox.views.default.left_side';
	
	$roles=Rights::getAssignedRoles(Yii::app()->user->Id); 
	if(sizeof($roles)==1 and key($roles) == 'student')
	{
		$leftside = 'application.modules.studentportal.views.default.leftside'; 
		
	}
	if(sizeof($roles)==1 and key($roles) == 'parent')
	{
		$leftside = 'application.modules.parentportal.views.default.leftside'; 
		
	}
	if(sizeof($roles)==1 and key($roles) == 'teacher')
	{
		$leftside = 'application.modules.teachersportal.views.default.leftside'; 
		
	}
	
	$this->renderPartial($leftside);
?>



<div class="contentpanel">
<div class="panel-heading">
	<h3 class="panel-title"><?php echo Yii::t("app",'Register your Complaints');?></h3>
</div>
<div class="people-item">

	<div class="form-group">
	<p class="note"><?php echo Yii::t("app",'Fields with');?> <span class="required">*</span><?php echo Yii::t("app", 'are required.');?></p>

	<?php //echo $form->errorSummary($model); ?>
    	<?php
		$categories=ComplaintCategories::model()->findAll();
		?>

	<?php /*?><div class="row">
		<?php echo $form->labelEx($model,'uid'); ?>
		<?php echo $form->textField($model,'uid'); ?>
		<?php echo $form->error($model,'uid'); ?>
	</div><?php */?>
<div class="row">
	<div class="col-md-8">    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model,'category_id'); ?>
                <?php echo $form->dropDownList($model, 'category_id',
                CHtml::listData($categories, 'id', 'categorynames'),array('prompt'=>Yii::t("app",'Select'),'class'=>'form-control'));;?>
                <?php /*?><?php echo $form->textField($model,'category_id'); ?><?php */?>
                <?php echo $form->error($model,'category_id'); ?>
            </div>
        </div>
        <div class="col-md-6">
        <div class="form-group">
            <?php echo $form->labelEx($model,'subject'); ?>
            <?php echo $form->textField($model,'subject',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'subject'); ?>
        </div>
        </div>
     </div>
 <div class="row" >
 <div class="col-md-12">   
	<div class="form-group">
		<?php echo $form->labelEx($model,'complaint'); ?>
		<?php echo $form->textArea($model,'complaint',array('rows'=>5, 'cols'=>15,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'complaint'); ?>
	</div>
  </div>
</div>
 <div class="row">
 <div class="col-md-12">
<div class="opnsl_headerBox">
    <div class="opnsl_actn_box"> </div>
        <div class="opnsl_actn_box">
        <div class="opnsl_actn_box1"><?php echo CHtml::submitButton(Yii::t("app",'Submit'),array('class'=>'btn btn-danger')); ?></div>
        <div class="opnsl_actn_box2"> <input type="reset" value="Reset" class="membergray_but btn btn-success"></div>
        </div>
</div>
 </div>
 </div> 
</div>
</div>

	<?php /*?><div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div><?php */?>

	<?php /*?><div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reopened_date'); ?>
		<?php echo $form->textField($model,'reopened_date'); ?>
		<?php echo $form->error($model,'reopened_date'); ?>
	</div><?php */?>



<?php $this->endWidget(); ?>

</div>

</div>
<!-- form -->
</div>
