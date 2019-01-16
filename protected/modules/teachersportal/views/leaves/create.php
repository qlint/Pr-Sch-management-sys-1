<div class="pageheader">
  <div class="col-lg-8">
    <h2><i class="fa fa-check-square"></i><?php echo Yii::t("app",'Leave Requests');?><span><?php echo Yii::t("app",'Apply Leave');?></span></h2>
  </div>
  <div class="col-lg-2">
      </div>
  <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t("app",'You are here:');?></span>
    <ol class="breadcrumb">
      <!--<li><a href="index.html">Home</a></li>-->
      
      <li class="active"><?php echo Yii::t("app",'Leave Requests')?></li>
    </ol>
  </div>
  <div class="clearfix"></div>
</div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'leave-request-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); 
	$this->renderPartial('/default/leftside');
?>

<?php 
   $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));   
   
   if($settings!=NULL)
    {
		if($settings->displaydate!=NULL){
       		$date = $settings->displaydate;
		}else{
			$date = 'd-m-Y';
		}
		if($settings->dateformat!=NULL){
			$datepick = $settings->dateformat;
		}else{
	   		$datepick = 'dd-mm-yy';
		}
	   if ($model->from_date!= NULL )
			$model->from_date=date($settings->displaydate,strtotime($model->from_date));
	   if ($model->to_date!= NULL )
		   $model->to_date=date($settings->displaydate,strtotime($model->to_date));
	   
    }
    else
	{
    	$date = 'd-m-Y';	
		$datepick = 'dd-mm-yy';	 
		 
		if ($model->from_date!= NULL )
   	   		$model->from_date=date($settings->displaydate,strtotime($model->from_date));
	   if ($model->to_date!= NULL )
		   $model->to_date=date($settings->displaydate,strtotime($model->to_date));
	}
	
 ?>

<div class="contentpanel">
<div class="panel-heading">
	<h3 class="panel-title"><?php echo Yii::t("app",'Request Leave');?></h3>
</div>
<?php
		$user=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->Id));
		if($user->gender == 'M'){
			$gender=1;
		}
		if($user->gender == 'F'){
			$gender=2;
		}
		
		$criteria=new CDbCriteria;
		$criteria->condition='(gender=:gender OR gender=0) AND is_deleted=:is_deleted';
		$criteria->params=array(':gender'=>$gender, ':is_deleted'=>0);
		$leave_types = LeaveTypes::model()->findAll($criteria);
?>

<div class="people-item">
<div class="row">
	<div class="col-md-12">
    	<div class="remn-leve-head">
        	<h5><?php echo Yii::t("app",'Remaining Leaves');?></h5>
        </div>
    </div>
</div>
<div class="row row-leaves">

<?php foreach($leave_types as $leave_type){
	$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$taken	=  EmployeeAttendances::model()->findAllByAttributes(array('employee_leave_type_id'=>$leave_type->id, 'employee_id'=>$employee->id));
	$days=0;
	if($taken){							  
		foreach($taken as $take){
			if($take->is_half_day == 0){
				$days		=	$days+1;
				$leave 		= 	LeaveTypes::model()->findByAttributes(array('id'=>$leave_type->id)); 
				$remaining 	=	($leave->count)-($days);
			}else{
				$days		=	$days+.5;
				$leave 		= 	LeaveTypes::model()->findByAttributes(array('id'=>$leave_type->id)); 
				$remaining 	=	($leave->count)-($days); 
			}
		}	
	}
	else{
			$leave 			= 	LeaveTypes::model()->findByAttributes(array('id'=>$leave_type->id)); 
			$remaining		=   $leave->count;
	}
	  ?>        
		<div class="col-md-2 col-4-reqst">
            <div class="remain-lv-box lv-type-one">
                <h4><?php 
			//	echo $remaining;
				if($remaining>=0){
					echo $remaining;
				} 
				else{
					 echo '0';
				}?>
                 </h4>
                <p><?php echo ucfirst($leave_type->type); ?></p>
            </div>
        </div>
<?php } ?>
</div>


	<div class="form-group">
	<p class="note"><?php echo Yii::t("app",'Fields with');?> <span class="required">*</span><?php echo Yii::t("app", 'are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-sm-3 col-4-reqst">
            <div class="form-group form-mtril">
                <?php echo $form->labelEx($model,'leave_type_id'); ?>
                <?php echo $form->dropDownList($model,'leave_type_id',CHtml::listData($leave_types,'id','type'),array('empty' => Yii::t('app','Select Leave Type'),'class'=>'form-control')); ?>
            </div>
        </div>
        <div class="col-sm-3 col-4-reqst">
        <div class="form-group form-mtril">
            <?php echo $form->labelEx($model,'from_date'); ?>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(                                
				'attribute'=>'from_date',
				'model'=>$model,
				// additional javascript options for the date picker plugin
				'options'=>array(
				'showAnim'=>'fold',
				'dateFormat'=>$datepick,
				'changeMonth'=> true,
				'changeYear'=>true,
				'yearRange'=>'1900:'.(date('Y')+5)
				),
				'htmlOptions'=>array(
				'class'=>'form-control',
				'readonly'=>'readonly'
				),
				));
				?>
        </div>
        </div>

        <div class="col-sm-3 col-4-reqst">
            <div class="form-group form-mtril">
                <?php echo $form->labelEx($model,'to_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(                                
						'attribute'=>'to_date',
						'model'=>$model,
						// additional javascript options for the date picker plugin
						'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>$datepick,
						'changeMonth'=> true,
						'changeYear'=>true,
						'yearRange'=>'1900:'.(date('Y')+5)
						),
						'htmlOptions'=>array(
						'class'=>'form-control',
						'readonly'=>'readonly'
						),
						));
						?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-4 col-4-reqst">
            <div class="form-group form-mtril">
                <?php echo $form->labelEx($model,'is_half_day'); 
				if($model->is_half_day!=0)
				{
					$model->half_day=1;
				}
				echo $form->checkBox($model,'half_day',array('id'=>'half_day','onClick'=>'halfday()'));
				?>
                <div id="half_day_div" style="display: none;">
					<?php echo $form->radioButton($model, 'is_half_day', array('value'=>'1','uncheckValue'=>null,'class'=>'half_day'))."Fore Noon &nbsp";
                          echo $form->radioButton($model, 'is_half_day', array('value'=>'2','uncheckValue'=>null,'class'=>'half_day'))." After Noon"; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-9 col-4-reqst">
            <div class="form-group form-mtril">
                <?php echo $form->labelEx($model,'reason'); ?>
                <?php echo $form->textArea($model,'reason',array('size'=>60,'maxlength'=>225,'class'=>'leave-textarea')	); ?>
            </div>
        </div>
    </div>
    
     <div class="row">
        <div class="col-sm-4 col-4-reqst">
            <div class="form-group form-mtril">
                <?php echo $form->labelEx($model,'file_name'); ?>
                 <?php echo $form->fileField($model,'file_name'); ?>
            </div>
        </div>
    </div>
    
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'btn btn-danger')); ?>
	</div>
<?php $this->endWidget(); ?>
</div>

</div>
<!-- form -->
</div>
<script>
var is_half			=	<?php echo ($model->is_half_day?$model->is_half_day:0) ?>;
if(is_half !=0){
	$( "#half_day_div" ).show("slow"); 
	$("#half_day").checked(true);
}
function halfday()
{
	var one_val	=	$('#half_day').is(":checked");
	if(one_val ==true){
		$( "#half_day_div" ).show("slow");  
	}else{
		$('input[class="half_day"]').attr('checked', false);
		$( "#half_day_div" ).hide("slow");  
	}
}
<?php /*?>var count = <?php echo $remaining; ?>
if (count == 0){		
	var r = confirm("<?php echo Yii::t('app', 'No leave left in the selected leave type.If you want to proceed press OK');?>");
						if (r==true)
						{
							document.getElementById("leave-form").submit();
						}
						else
						{
							return false;
						}
}<?php */?>
</script>

