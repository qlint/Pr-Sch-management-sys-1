<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
<div>
	<?php 
    if(isset($_REQUEST['id']) and $_REQUEST['id']){ 
        $this->beginWidget('zii.widgets.jui.CJuiDialog',array(
            'id'=>'jobDialog2',
            'options'=>array(
                'title'=>Yii::t('job','Update Subject Wise Attendance'),
                'autoOpen'=>true,
                'modal'=>'true',
                'width'=>'400',
                'height'=>'auto',
                'open'=> 'js:function(event, ui){$(".ui-dialog-titlebar-close").click(function(){$("#timetable-entries-form").remove();});}',                
            ),
        ));
    } else{
        $this->beginWidget('zii.widgets.jui.CJuiDialog',array(
            'id'=>'jobDialog1',
            'options'=>array(
                'title'=>Yii::t('job','Mark Subject Wise Attendance'),
                'autoOpen'=>true,
                'modal'=>'true',
                'width'=>'400',
                'height'=>'auto',
                'open'=> 'js:function(event, ui){$(".ui-dialog-titlebar-close").click(function(){$("#timetable-entries-form").remove();});}',                
            ),
        ));
    }
    ?>				
    <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
    <?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>
</div>