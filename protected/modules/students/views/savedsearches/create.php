 <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />

<div style="padding:20px 20px 0px 10px;">
<?php 

$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'jobDialog',
                'options'=>array(
                    'title'=>''.Yii::t('job','Save Filter').'',
                    'autoOpen'=>true,
                    'modal'=>'true',
                    'width'=>'auto',
                    'height'=>'auto',
					'resizable'=>false,
                ),
                ));

echo $this->renderPartial('_form', array('model'=>$model,'url'=>$url,'type'=>$type)); ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?></div>