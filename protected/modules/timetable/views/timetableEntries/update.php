 <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
<?php
$this->breadcrumbs=array(
	Yii::t('app','Timetable Entries')=>array('/timetable'),
	Yii::t('app','update'),
);


?>
<div style="padding:0px 10px 0px 10px;">
<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'jobDialogupdate'.$class_timing_id.$weekday_id,
                'options'=>array(
                    'title'=>Yii::t('job','Update Time Table Entries'),
                    'autoOpen'=>true,
                    'modal'=>'true',
                    'width'=>'auto',
                    'height'=>'450',
					'open'=> 'js:function(event, ui) { $(".ui-dialog-titlebar-close").click(function() 
                                                                { $("#timetable-entries-form" ).remove(); }); }',
					
                ),
                ));
				?>
<?php

 echo $this->renderPartial('_form1', array('model'=>$model,'batch_id'=>$batch_id,'weekday_id'=>$weekday_id,'class_timing_id'=>$class_timing_id)); ?>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>
</div>