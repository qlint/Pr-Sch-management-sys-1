<?php
if(Yii::app()->controller->action->id == 'create'){
	$str	= Yii::t('app','Create Common Class Timings');
}
else{
	$str	= Yii::t('app','Update Common Class Timings');
}
$this->breadcrumbs=array(
 Yii::t('app','Settings')=>array('/configurations'),
 $str
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
<div id="othleft-sidebar">
<?php $this->renderPartial('//configurations/left_side');?>
  </div>
 </td>
 <td valign="top">
<div class="cont_right formWrapper">  
<h1><?php echo $str; ?></h1>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
    <ul>
        <li><?php echo CHtml::link('<span>'.Yii::t('app','View Common Class Timings').'</span>', array('/commonClassTimings'), array('class'=>'a_tag-btn')); ?></li>
    </ul>
</div>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'common-class-timing-form',
'enableAjaxValidation'=>false
)); ?>

<div class="formCon">
	<div class="formConInner">
           
    	<table width="50%" border="0" cellspacing="0" cellpadding="0">
        	<tr>
            	<td valign="bottom" width="33%"><?php echo $form->labelEx($model,'name'); ?></td>
                <td>&nbsp;</td>
                <td valign="bottom" width="33%"><?php echo $form->labelEx($model,'start_time'); ?></td>
                <td>&nbsp;</td>
                <td valign="bottom" width="33%"><?php echo $form->labelEx($model,'end_time'); ?></td>
            </tr>
            <tr>
            	<td valign="top">
					<?php echo $form->textField($model,'name',array('size'=>25,'maxlength'=>255)); ?>
                    <?php echo $form->error($model,'name'); ?>
                </td>
                <td>&nbsp;</td>
                <td valign="top">
					<?php 
						$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
						if($settings!=NULL){
							$time=$settings->timeformat;
							if(!$model->isNewRecord){
								$model->start_time=date($settings->timeformat,strtotime($model->start_time));
								$model->end_time=date($settings->timeformat,strtotime($model->end_time));					
							}					
						}
						if($time=='h:i A'){
							$this->widget('application.extensions.jui_timepicker.JTimePicker', array(
									'model'=>$model,
									'attribute'=>'start_time',
									'name'=>'ClassTimings[start_time]',
									'options'=>array(
									'showPeriod'=>true,
									'showPeriodLabels'=> true,
									'showCloseButton'=> true,    
									'closeButtonText'=> 'Done',     
									'showNowButton'=> true,        
									'nowButtonText'=> 'Now',        
									'showDeselectButton'=> true,   
									'deselectButtonText'=> 'Deselect' 
								),	      
   							)); 
						}
						else if($time=='H:i'){
							$this->widget('application.extensions.jui_timepicker.JTimePicker', array(
									'model'=>$model,
									'attribute'=>'start_time',
									'name'=>'ClassTimings[start_time]',
									'options'=>array(
									'showPeriod'=>false,  
									'closeButtonText'=> 'Done',     
									'showNowButton'=> true,           
         						),	      
   							));
						}
    				?> 
                    <?php echo $form->error($model,'start_time'); ?>
                </td>
                <td>&nbsp;</td>
                <td valign="top">
                	<?php
						if($time=='h:i A'){
							$this->widget('application.extensions.jui_timepicker.JTimePicker', array(
									'model'=>$model,
									'attribute'=>'end_time',
									'name'=>'ClassTimings[end_time]',
									'options'=>array(
									'showPeriod'=>true,
									'showPeriodLabels'=> true,
									'showCloseButton'=> true,    
									'closeButtonText'=> 'Done',     
									'showNowButton'=> true,        
									'nowButtonText'=> 'Now',        
									'showDeselectButton'=> true,   
									'deselectButtonText'=> 'Deselect'
	 
         						),
	 
     
   							));
		   				}		   
		  				else if($time=='H:i'){		   
							$this->widget('application.extensions.jui_timepicker.JTimePicker', array(
									'model'=>$model,
									'attribute'=>'end_time',
									'name'=>'ClassTimings[end_time]',
									'options'=>array(
									'showPeriod'=>false,
									//'showPeriodLabels'=> false,
									//'showCloseButton'=> true,       
									'closeButtonText'=> 'Done',     
									'showNowButton'=> true,        
									'nowButtonText'=> 'Now',        
									//'showDeselectButton'=> true,   
									//'deselectButtonText'=> 'Deselect' ,
									//'hours'=>array(
									//'starts' => 0,
									//'ends'=> 23, ),
         						),	      
   							));
		   				}
    				?> 
                    <?php echo $form->error($model,'end_time'); ?>
                </td>                
            </tr>
			<tr>
                                <td></td>
				<td colspan="2"><?php echo $form->error($model,'exception'); ?>&nbsp;</td>
			</tr>
            <tr>
                <td><?php echo $form->checkBox($model,'is_break'); ?>
                <?php echo $form->labelEx($model,'is_break',array('style'=>'display:inline')); ?>
                <?php echo $form->error($model,'is_break'); ?></td>
				<td></td>
				
						 <?php
                $is_batch = Batches::model()->findAll();
                if($model->isNewRecord and $is_batch!=NULL){ ?>
                
                        <td><?php echo $form->checkBox($model,'all_batches'); ?>
                        <?php echo $form->labelEx($model,'all_batches',array('style'=>'display:inline')); ?>
                        <?php echo $form->error($model,'all_batches'); ?></td>
                    
                    
             <?php } ?> 
             </tr>
             	
                <!-- weekdays -->
                <?php
                if(Configurations::model()->timetableFormat()==2){	// if timetable format is flexible
				?>
                <tr>
                	<td colspan="7">&nbsp;</td>
                </tr>
                <tr>
                	<td colspan="7"><h3><?php echo Yii::t('app','Weekdays'); ?></h3></td>
                </tr>
            	<tr>                                           
                    <td colspan="7">
                        <?php echo $form->checkBox($model,'all_weekdays'); ?>
                        <?php echo $form->labelEx($model,'all_weekdays',array('style'=>'display:inline')); ?>
                        <?php echo $form->error($model,'all_weekdays'); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" width="100%">
                    <?php
					$batch_weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday<>0");
					$week_day_ids	= CHtml::listData($batch_weekdays, 'weekday', 'weekday');
					$weekdays		= ClassTimings::model()->weekDays;						
					$index			= 1;
                    foreach($weekdays as $attribute=>$weekday){
						if(in_array($index, $week_day_ids)){                       
                    ?>
                        <div  class="main-weekdays">
                            <?php echo $form->checkBox($model, $attribute, array('class'=>'weekday_check')); ?>
                            <?php echo CHtml::label($weekday, "ClassTimings_".$attribute, array('style'=>'display:inline')); ?>
                        </div>
                    <?php
						}
						$index++;
                    }
                    ?>
                    </td>
                </tr>
                <?php
				}
				?>
        </table>            
    </div>
</div>    

<div style="padding:0px 0 0 0px; text-align:left">
    	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Submit') : Yii::t('app','Save'),array('id'=>'submit_button_form','class'=>'formbut')); ?>
    </div>
<?php $this->endWidget(); ?>

<script>
	var check_all_weekdays	= function(){
		if($("input:checkbox.weekday_check").length==$("input:checkbox.weekday_check:checked").length){
			$("input:checkbox#ClassTimings_all_weekdays").attr("checked", true);
		}
		else{
			$("input:checkbox#ClassTimings_all_weekdays").attr("checked", false);
		}
	};
	
	$("input:checkbox.weekday_check").unbind("change").bind("change", function(){
		check_all_weekdays();
	});
	
	$("input:checkbox#ClassTimings_all_weekdays").change(function(){
		if($(this).is(":checked")){
			$("input:checkbox.weekday_check").not(":disabled").attr("checked", true);
		}
		else{
			$("input:checkbox.weekday_check").not(":disabled").attr("checked", false);
		}
	});
	
	$(document).ready(function(e) {
        check_all_weekdays();
    });
</script>