
 <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'changeType_dialog',
                'options'=>array(
                    'title'=>Yii::t('app','Attendance Type'),
                    'autoOpen'=>true,
                    'modal'=>'true',
                    'width'=>'auto',
                    'height'=>'auto',
					'open'=> 'js:function(event, ui){ 
									 $(".ui-dialog-titlebar-close").click(function(){ 
										 	$("form#student-attentance-form").remove();
										 }); 
								}',
					
                ),
                ));
				?>
<div>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>
</div>