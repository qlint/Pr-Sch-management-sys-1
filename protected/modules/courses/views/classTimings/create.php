 <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
<?php
$this->breadcrumbs=array(
	Yii::t('app','Courses')=>array('/courses'),
	Yii::t('app','Create Class Timings'),
);

$this->menu=array(
	array('label'=>'List ClassTimings', 'url'=>array('index')),
	array('label'=>'Manage ClassTimings', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Create Class Timings');?></h1>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'jobDialog',
                'options'=>array(
                    'title'=>Yii::t('app','Create Timing'),
                    'autoOpen'=>true,
                    'modal'=>'true',
                    'width'=>'auto',
                    'height'=>'auto',
					'resizable'=>false,
					
                ),
                ));
				?>
<?php echo $this->renderPartial('_form', array('model'=>$model,'batch_id'=>$batch_id)); ?>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>