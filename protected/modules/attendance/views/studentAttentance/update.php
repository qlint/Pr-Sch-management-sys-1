<?php
$this->breadcrumbs=array(
	Yii::t('app','Student Attentances')=>array('/courses'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Student Attentance'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Student Attentance'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View Student Attentance'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage Student Attentance'), 'url'=>array('admin')),
);
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'jobDialog'.$day.$emp_id,
                'options'=>array(
                    'title'=>Yii::t('app','Edit Attendance '),
                    'autoOpen'=>true,
                    'modal'=>'true',
                    'width'=>'auto',
                    'height'=>'auto',
                ),
                ));
				?>



<?php echo $this->renderPartial('_form1', array('model'=>$model,'day' =>$day,'month'=>$month,'year'=>$year,'emp_id'=>$emp_id));?>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>