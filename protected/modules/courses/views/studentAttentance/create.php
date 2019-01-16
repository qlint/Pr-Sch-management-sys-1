<?php
$this->breadcrumbs=array(
	Yii::t('app','Student Attendances')=>array('/courses'),
	Yii::t('app','Create'),
);
?>
 <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'jobDialog'.$day.$emp_id,
                'options'=>array(
                   // 'title'=>Yii::t('',''),
                    'autoOpen'=>true,
                    'modal'=>'true',
                    'width'=>'400',
                    'height'=>'auto',
					'title'=>Yii::t('app','Mark Attendance'),
                ),
                ));
				?>
<div style="padding:10px 0 0 0">
<?php /*?><h1><?php echo Yii::t('app','Mark Attentance'); ?></h1><?php */?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'day' =>$day,'month'=>$month,'year'=>$year,'emp_id'=>$emp_id,'batch_id'=>$batch_id)); ?>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>
</div>