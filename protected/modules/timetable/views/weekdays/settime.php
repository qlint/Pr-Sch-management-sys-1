<?php
$this->breadcrumbs=array(
	Yii::t('app','Weekdays')=>array('/timetable'),
	Yii::t('app','SetTime'),
);?>
<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'jobDialog',
                'options'=>array(
                    'title'=>Yii::t('job','Create Job'),
                    'autoOpen'=>true,
                    'modal'=>'true',
                    'width'=>'auto',
                    'height'=>'auto',
                ),
                ));
				?>
                
<?php echo $form->dropDownList($model,'item_type_id', CHtml::listData(ItemType::model()->findAll(), 'id', 'type'), array('empty'=>Yii::t('app','select Type'))); ?>
				
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>