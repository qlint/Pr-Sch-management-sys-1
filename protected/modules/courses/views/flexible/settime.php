<?php
$this->breadcrumbs=array(
	Yii::t('app','Weekdays')=>array('index'),
	Yii::t('app','Manage'),
);
?>
<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'jobDialog',
                'options'=>array(
                    'title'=>Yii::t('app','Create Job'),
                    'autoOpen'=>true,
                    'modal'=>'true',
                    'width'=>'auto',
                    'height'=>'auto',
                ),
                ));
				?>
                
<?php echo $form->dropDownList($model,'item_type_id', CHtml::listData(ItemType::model()->findAll(), 'id', 'type'), array('empty'=>Yii::t('app','Select Type'))); ?>
				
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>