<style type="text/css">


.errorMessage{
	color: #F00 !important;
	font-size: 11px;
}
</style>
<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL)
{
	$date=$settings->dateformat;
	
	
}
else
$date = 'dd-mm-yy';

if(Yii::app()->controller->action->id=="create"){
	$emp_id	= Staff::model()->findAll(array('order' => 'id DESC','limit' => 1));
	if(!$emp_id){
		$emp_id_1='E1';
	}else{
		$length = strlen($emp_id[0]['employee_number']);
		$substr1 = substr($emp_id[0]['employee_number'],0,1);
		$substr2 = trim(substr($emp_id[0]['employee_number'],1,$length-1));
		$next_no = $substr2+1;				
		$emp_id_1 = 'E'.$next_no;
	}
}else{
	$emp_id_1	=	$model->employee_number;
}
?>
<div class="formCon" >

<div class="formConInner">
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'staff-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with');?> <span class="required"> * </span><?php echo Yii::t('app','are required');?>.</p>
	<div class="text-fild-bg-block">           
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'first_name'); ?>
            <?php echo $form->textField($model,'first_name',array('size'=>32,'maxlength'=>100)); ?>
            <?php echo $form->error($model,'first_name'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'middle_name'); ?>
            <?php echo $form->textField($model,'middle_name',array('size'=>32,'maxlength'=>100)); ?>
            <?php echo $form->error($model,'middle_name'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'last_name'); ?>
            <?php echo $form->textField($model,'last_name',array('size'=>32,'maxlength'=>100)); ?>
            <?php echo $form->error($model,'last_name'); ?>
        </div>
	</div>
    
    <div class="text-fild-bg-block">           
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'employee_number'); ?>
            <?php echo $form->textField($model,'employee_number',array('size'=>32,'maxlength'=>50,'value'=>$emp_id_1,'readonly'=>true)); ?>
            <?php echo $form->error($model,'employee_number'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'joining_date'); ?>
            <?php 
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							//'name'=>'Employees[joining_date]',
							'attribute'=>'joining_date',
							'model'=>$model,
							
							// additional javascript options for the date picker plugin
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>$date,
								'changeMonth'=> true,
									'changeYear'=>true,
									'yearRange'=>'1970:'
							),
							'htmlOptions'=>array(
								//'style'=>'height:20px;'
								//'value' => date('m-d-y'),
								'readonly'=>"readonly"
							),
						))
		 ?>
            <?php echo $form->error($model,'joining_date'); ?>
        </div>
        
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'gender'); ?>
            <?php echo $form->dropDownList($model,'gender',array('M' => Yii::t('app','Male'), 'F' => Yii::t('app','Female')),array('empty' =>Yii::t('app','Select Gender'))); ?>
            <?php echo $form->error($model,'gender'); ?>
        </div>
	</div>
    
    <div class="text-fild-bg-block">           
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'job_title'); ?>
            <?php echo $form->textField($model,'job_title',array('size'=>32,'maxlength'=>100)); ?>
            <?php echo $form->error($model,'job_title'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'employee_department_id'); ?>
            <?php  echo $form->dropDownList($model,'employee_department_id',CHtml::listData(EmployeeDepartments::model()->findAll(),'id','name'),array('empty' => Yii::t('app','Select Department'))); ?>
            <?php echo $form->error($model,'employee_department_id'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'qualification'); ?>
            <?php echo $form->textField($model,'qualification',array('size'=>32,'maxlength'=>100)); ?>
            <?php echo $form->error($model,'qualification'); ?>
        </div>
	</div>
    
    <div class="text-fild-bg-block">           
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email',array('size'=>32,'maxlength'=>100)); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'mobile_phone'); ?>
            <?php echo $form->textField($model,'mobile_phone',array('size'=>10,'maxlength'=>15)); ?>
            <?php echo $form->error($model,'mobile_phone'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'staff_type'); ?>
            <?php
				$criteria = new CDbCriteria;
				$criteria->condition = "id !=:x";
				$criteria->params = array(':x'=>1);		
				$posts = UserRoles::model()->findAll($criteria);
				echo $form->dropDownList($model,'staff_type',CHtml::listData($posts,'id','name'),array('empty' => Yii::t('app','Select Staff Type'))); ?>
            <?php echo $form->error($model,'staff_type'); ?>
        </div>
	</div>
    
    <div class="text-fild-bg-block">           
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'date_of_birth'); ?>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
							//'name'=>'Employees[joining_date]',
							'attribute'=>'date_of_birth',
							'model'=>$model,
							
							// additional javascript options for the date picker plugin
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>$date,
								'changeMonth'=> true,
									'changeYear'=>true,
									'yearRange'=>'1940:'
							),
							'htmlOptions'=>array(
								//'style'=>'height:20px;'
								//'value' => date('m-d-y'),
								'readonly'=>"readonly"
							),
						)); ?>
            <?php echo $form->error($model,'date_of_birth'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'experience_year'); ?>
            <?php 
				$years=array();
				$month=array();
				for($i=0;$i<=20;$i++){
					$years[$i]=$i;
					if($i<12){
						$month[$i]=$i;
					}
				}
				echo $form->dropDownList($model,'experience_year',$years,array('id'=>'experience_year','empty' => Yii::t('app','Years'))); ?>
            <?php echo $form->error($model,'experience_year'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'experience_month'); ?>
            <?php echo $form->dropDownList($model,'experience_month',$month,array('id'=>'experience_month','empty' => Yii::t('app','Months'))); ?>
            <?php echo $form->error($model,'experience_month'); ?>
        </div>
	</div>
    
     <div class="text-fild-bg-block">           
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'experience_detail'); ?>
           <?php echo $form->textArea($model,'experience_detail',array('rows'=>6, 'cols'=>10)); ?>
            <?php echo $form->error($model,'experience_detail'); ?>
        </div>
	</div>
    <h3><?php echo Yii::t('app','Salary Details');?></h3>
    <div class="text-fild-bg-block">           
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'basic_pay'); ?>
            <?php echo $form->textField($model,'basic_pay',array('size'=>10,'maxlength'=>6)); ?>
            <?php echo $form->error($model,'basic_pay'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'EPF'); ?>
            <?php echo $form->textField($model,'EPF',array('size'=>30,'maxlength'=>5)); ?>
            <?php echo $form->error($model,'EPF'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'ESI'); ?>
            <?php echo $form->textField($model,'ESI',array('size'=>30,'maxlength'=>5)); ?>
            <?php echo $form->error($model,'ESI'); ?>
        </div>
	</div>
     <div class="text-fild-bg-block">           
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'TDS'); ?>
            <?php echo $form->radioButtonList($model, 'tds_type',array(  0 => 'Amount', 1 => 'Percentage'),
														array('labelOptions'=>array('style'=>'display:inline'), 'separator'=>'  ',) ); 
							  echo $form->textField($model,'TDS',array('size'=>10,'maxlength'=>5)); ?>
            <?php echo $form->error($model,'TDS'); ?>
        </div>
	</div>
    
    <h3><?php echo Yii::t('app','Bank Details');?></h3>
    <div class="text-fild-bg-block">           
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'bank_name'); ?>
            <?php echo $form->textField($model,'bank_name',array('size'=>32,'maxlength'=>100)); ?>
            <?php echo $form->error($model,'bank_name'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'bank_acc_no'); ?>
            <?php echo $form->textField($model,'bank_acc_no',array('size'=>10,'maxlength'=>16)); ?>
            <?php echo $form->error($model,'bank_acc_no'); ?>
        </div>
	</div>
    
    <h3><?php echo Yii::t('app','Passport Details');?></h3>
     <div class="text-fild-bg-block">           
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'passport_no'); ?>
            <?php echo $form->textField($model,'passport_no',array('size'=>32,'maxlength'=>20)); ?>
            <?php echo $form->error($model,'passport_no'); ?>
        </div>
        <div class="text-fild-block inputstyle">
        	<?php echo $form->labelEx($model,'passport_expiry'); ?>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
							//'name'=>'Employees[joining_date]',
							'attribute'=>'passport_expiry',
							'model'=>$model,
							
							// additional javascript options for the date picker plugin
							'options'=>array(
								'showAnim'=>'fold',
								'dateFormat'=>$date,
								'changeMonth'=> true,
									'changeYear'=>true,
									'yearRange'=>'1970:'.(date('Y')+10)
							),
							'htmlOptions'=>array(
								//'style'=>'height:20px;'
								//'value' => date('m-d-y'),
								'readonly'=>"readonly"
							),
						)); ?>
            <?php echo $form->error($model,'passport_expiry'); ?>
        </div>
	</div>
    <div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>
    
    
	</div>
    </div>
    </div>
    
<?php $this->endWidget(); ?>
</div>
</div>