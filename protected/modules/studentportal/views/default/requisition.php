<div class="pageheader">
  <div class="col-lg-8">
    <h2><i class="fa fa-compress"></i><?php echo Yii::t("app",'Material Requests');?><span><?php echo Yii::t("app",'Create Material Requests');?></span></h2>
  </div>
  <div class="col-lg-2">
      </div>
  <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t("app",'You are here:');?></span>
    <ol class="breadcrumb">
      <!--<li><a href="index.html">Home</a></li>-->
      
      <li class="active"><?php echo Yii::t("app",'Material Requests')?></li>
    </ol>
  </div>
  <div class="clearfix"></div>
</div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'material-requistion-form',
	'enableAjaxValidation'=>false,
)); 
	$this->renderPartial('/default/leftside');
?>

<div class="contentpanel">
<div class="panel-heading">
	<h3 class="panel-title"><?php echo Yii::t("app",'Request Material');?></h3>
</div>
<div class="people-item">
	<div class="form-group">
	<p class="note"><?php echo Yii::t("app",'Fields with');?> <span class="required">*</span><?php echo Yii::t("app", 'are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
    
     <div class="row">
        <div class="col-sm-4 col-4-reqst">
        <div class="form-group form-mtril">
            <?php echo $form->labelEx($model,'material_id'); ?>
            <?php
			$item_id =array();
			$criteria_n 			= 	new CDbCriteria;
			$criteria_n->condition	=   'quantity!=:quantity';
			$criteria_n->params 	= 	array(':quantity'=>0);
		    $items=PurchaseStock::model()->findAll($criteria_n);
			 foreach($items as $item)
			 {
				 $item_id[] = $item->item_id;
			 }
			
			$criteria 			= 	new CDbCriteria;
			$criteria->join		=   'JOIN `purchase_products` AS `p` ON t.id = p.item_id';
			$criteria->addInCondition('t.id',$item_id);
            echo $form->dropDownList($model,'material_id',CHtml::listData(PurchaseItems::model()->findAll($criteria),'id','name'),array('empty' => Yii::t('app','Select Material'),'class'=>'form-control')); ?>
        </div>
        </div>
     </div>
     <div class="row">
        <div class="col-sm-4 col-4-reqst">
            <div class="form-group form-mtril">
                <?php echo $form->labelEx($model,'quantity'); ?>
                <?php echo $form->textField($model,'quantity',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
            </div>
        </div>
    </div>
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Request') : Yii::t('app','Save'),array('class'=>'btn btn-danger')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>

</div>
<!-- form -->
</div>
