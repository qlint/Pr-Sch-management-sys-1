<style type="text/css">
.errorMessage{
	color: #F00 !important;
	font-size: 11px;
}
</style>


<?php 
	if(in_array(Yii::app()->controller->action->id,array('create')) and Yii::app()->controller->id == 'employees')
	{
		$this->renderPartial('application.modules.employees.views.employees.createtab');
	}
	else
	{
		$this->renderPartial('application.modules.employees.views.employees.updatetab');
	}
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'employees-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	
    
<p class="note"><?php echo Yii::t('app','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app','are required.'); ?></p>
<div class="formCon" style="background:#fcf1d4; width:100%; border:0px #fac94a solid; color:#000;background:url(images/yellow-pattern.png); width:100%; border:0p ">

<div class="formConInner" style="padding:5px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<?php 
	$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	if($settings!=NULL)
	{	
		if(isset($model->joining_date) and $model->joining_date!=NULL)
		{	
			$model->joining_date=date($settings->displaydate,strtotime($model->joining_date));
		}
		
		if(isset($model->date_of_birth) and $model->date_of_birth!=NULL)
		{
			$model->date_of_birth=date($settings->displaydate,strtotime($model->date_of_birth));		
		}
	}
	
?>    
	<?php
		$emp_id_1= '';
		if(Yii::app()->controller->action->id=='create'){
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
			$emp_id	= Employees::model()->findByAttributes(array('id' => $_REQUEST['id']));
			$emp_id_1=$emp_id->employee_number;
		}
			?>
	<?php echo $form->labelEx($model,'employee_number'); ?></td>
    <td><?php echo $form->textField($model,'employee_number',array('size'=>20,'maxlength'=>255,'readonly'=>true,'value'=>$emp_id_1));  ?>
		<?php echo $form->error($model,'employee_number'); ?></td>
    
    <td><?php echo $form->labelEx($model,'joining_date'); ?></td>
    <td><?php 
    
    if(!(isset($model->joining_date)))
	{
		$model->joining_date= date("j M Y");
	}
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	if($settings!=NULL)
	{
		$date=$settings->dateformat;		
	}
	else
	{
		$date = 'dd-mm-yy';	
	}
	
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
			));
	
	 ?>
		<?php echo $form->error($model,'joining_date'); ?></td>
   
  </tr>
  </table>
</div>
</div>
<div class="formCon" >
<div class="formConInner">
<h3><?php echo Yii::t('app','General Details'); ?></h3>
<div class="txtfld-col-box">
<div class="txtfld-col">
<?php echo $form->labelEx($model,'first_name'); ?>	
<?php echo $form->textField($model,'first_name',array('size'=>32,'maxlength'=>255)); ?>
<?php echo $form->error($model,'first_name'); ?>
</div>
<div class="txtfld-col">
<?php echo $form->labelEx($model,'middle_name'); ?>
<?php echo $form->textField($model,'middle_name',array('size'=>10,'maxlength'=>255)); ?>
<?php echo $form->error($model,'middle_name'); ?>
</div>
<div class="txtfld-col">
<?php echo $form->labelEx($model,'last_name'); ?>
<?php echo $form->textField($model,'last_name',array('size'=>30,'maxlength'=>255)); ?>
<?php echo $form->error($model,'last_name'); ?>
</div>
</div>
<div class="txtfld-col-box">
<div class="txtfld-col">
<?php echo $form->labelEx($model,'gender'); ?>
<?php echo $form->dropDownList($model,'gender',array('M' => Yii::t('app','Male'), 'F' => Yii::t('app','Female')),array('empty' =>Yii::t('app','Select Gender'))); ?>
<?php echo $form->error($model,'gender'); ?>
</div>
<div class="txtfld-col">
 <?php 
					echo $form->labelEx($model,'date_of_birth'); 
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(                        
						'attribute'=>'date_of_birth',
						'model'=>$model,
						// additional javascript options for the date picker plugin
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>$date,
							'changeMonth'=> true,
							'changeYear'=>true,
							'yearRange'=>'1900:'
						),
						'htmlOptions'=>array(							
							'readonly'=>true,
						),
					));            		
				?>
		<?php echo $form->error($model,'date_of_birth'); ?>
</div>
<div class="txtfld-col">
<?php echo $form->labelEx($model,'employee_department_id'); ?>
<?php echo $form->dropDownList($model,'employee_department_id',CHtml::listData(EmployeeDepartments::model()->findAll(),'id','name'),array('empty' => Yii::t('app','Select Department'))); ?>
<?php echo $form->error($model,'employee_department_id'); ?>
</div>
</div>
<div class="txtfld-col-box">

	<div class="txtfld-col">
	    <?php echo $form->labelEx($model,'employee_category_id'); ?>
	    <?php 
	    $criteria2 = new CDbCriteria;
	    $criteria2->compare('status',1); 
	    echo CHtml::activeDropDownList($model,'employee_category_id',CHtml::listData(EmployeeCategories::model()->findAll($criteria2),'id','name'),array('prompt'=>Yii::t('app','Select Category'),
                    'ajax' => array(
                    'type'=>'POST',
                    'url'=>CController::createUrl('/employees/employees/positions'),
                    'update'=>'#position',
                    'data'=>'js:{employee_category_id:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',)));
        ?>
        <?php echo $form->error($model,'employee_category_id'); ?>
    </div>

    <div class="txtfld-col">
	    <?php echo $form->labelEx($model,'employee_position_id'); ?>
	    <?php 
	    	if($model->employee_category_id!=NULL or $model->employee_position_id!=NULL){
	    		$data=EmployeePositions::model()->findAll('employee_category_id=:x',array(':x'=>$model->employee_category_id));
               	$data=CHtml::listData($data,'id','name');
                echo CHtml::activeDropDownList($model,'employee_position_id',$data,array('prompt'=>Yii::t('app','Select Position'),'id'=>'position','options' => array($model->employee_category_id=>array('selected'=>true))));
	    	}else{
	    		echo CHtml::activeDropDownList($model,'employee_position_id',array(),array('prompt'=>Yii::t('app','Select Position'),'id'=>'position')); 
	    	}
	    ?>
	    <?php echo $form->error($model,'employee_position_id'); ?>
    </div>

    <div class="txtfld-col">
	    <?php echo $form->labelEx($model,'employee_grade_id'); ?>
	    <?php $criteria2 = new CDbCriteria;
	    $criteria2->compare('status',1); ?>
	    <?php echo $form->dropDownList($model,'employee_grade_id',CHtml::listData(EmployeeGrades::model()->findAll($criteria2),'id','name'),array('empty' => Yii::t('app','Select Grade'))); ?>
	    <?php echo $form->error($model,'employee_grade_id'); ?>
    </div>
    
</div>

<div class="txtfld-col-box">
	<div class="txtfld-col">
    <?php echo $form->labelEx($model,'job_title'); ?>
    <?php echo $form->textField($model,'job_title',array('size'=>15,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'job_title'); ?>
    </div>
    <div class="txtfld-col">
    <?php echo $form->labelEx($model,'qualification'); ?>
    <?php echo $form->textField($model,'qualification',array('size'=>15,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'qualification'); ?>
    </div>
</div>

<div class="txtfld-col-box">
    <div class="txtfld-col">
<?php echo $form->labelEx($model,Yii::t('app','Total Experience'),array('style'=>'')); ?>&nbsp;<span class="required">*</span>
   <?php echo $form->dropDownList($model,'experience_year',array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5
																			  ,'6'=>6,'7'=>7,'8'=>8,'9'=>9,'10'=>10,'11'=>11
																			  ,'12'=>12,'13'=>13,'14'=>14,'15'=>15,'16'=>16,'17'=>17
																			  ,'18'=>18,'19'=>19,'20'=>20),array('id'=>'experience_year','onchange'=>'star()','empty' => Yii::t('app','Years'))); ?>
		<?php echo $form->error($model,'experience_year'); ?>
       

    </div>
    <div class="txtfld-col">
    <?php echo $form->labelEx($model,'Month'); ?>  
 <?php echo $form->dropDownList($model,'experience_month',array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,
																				'6'=>6,'7'=>7,'8'=>8,'9'=>9,'10'=>10,'11'=>11,),array('id'=>'experience_month','onchange'=>'star2()','empty' => Yii::t('app','Months'))); ?>
		<?php echo $form->error($model,'experience_month'); ?>    
 </div>
 </div>
 <div class="txtfld-col-box">
 <div class="text-fild-block-full">
<?php echo $form->labelEx($model,'experience_detail',array('style'=>'float:left;')); ?><span id="required" class="required" style="visibility:hidden; display:inline">*</span>
<?php echo $form->textArea($model,'experience_detail',array('rows'=>6, 'cols'=>48,'style'=>'width:100%;')); ?>
<label class="inform_text">Add experience details, if teacher have atleast 1 month of experience</label>
		<?php echo $form->error($model,'experience_detail'); ?>
 </div>
 </div>






</div>
</div>

<div class="formCon">
<div class="formConInner">
<h3><?php echo Yii::t('app','Personal Details');?></h3>

<div class="txtfld-col-box">
    <div class="txtfld-col">
		<?php echo $form->labelEx($model,'marital_status'); ?>
        <?php echo $form->dropDownList($model,'marital_status',array('Single'=>Yii::t('app','Single'),'Married'=>Yii::t('app','Married'),'Divorced'=>Yii::t('app','Divorced'))); ?>
        <?php echo $form->error($model,'marital_status'); ?> 
    </div>
    <div class="txtfld-col">
		<?php echo $form->labelEx($model,'children_count'); ?>
        <?php echo $form->textField($model,'children_count',array('size'=>15,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'children_count'); ?>
    </div>    
    <div class="txtfld-col">
		<?php echo $form->labelEx($model,'father_name'); ?>
        <?php echo $form->textField($model,'father_name',array('size'=>15,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'father_name'); ?>
    </div>    
</div>
<div class="txtfld-col-box">
    <div class="txtfld-col">
		<?php echo $form->labelEx($model,'mother_name'); ?>
        <?php echo $form->textField($model,'mother_name',array('size'=>15,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'mother_name'); ?>
    </div>
    <div class="txtfld-col">
		<?php echo $form->labelEx($model,'husband_name'); ?>
        <?php echo $form->textField($model,'husband_name',array('size'=>15,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'husband_name'); ?>
    </div>    
    <div class="txtfld-col">
		<?php echo $form->labelEx($model,'blood_group'); ?>
        <?php echo $form->dropDownList($model,'blood_group',array('A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+-' => 'AB+', 'AB-' => 'AB-'),
        array('empty' => Yii::t('app','Unknown'))); ?>
        <?php echo $form->error($model,'blood_group'); ?>
    </div>    
</div>
<div class="txtfld-col-box">
    <div class="txtfld-col">
		<?php echo $form->labelEx($model,'nationality_id'); ?>
        <?php echo $form->dropDownList($model,'nationality_id',CHtml::listData(Nationality::model()->findAll(),'id','name'),array(
        'style'=>'','empty'=>Yii::t('app','Select Nationality')
        )); ?>
        <?php echo $form->error($model,'nationality_id'); ?>
    </div>
    <div class="txtfld-col">
		<?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email',array('size'=>15,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>    
    
</div>


</div>
</div>
<div class="formCon" style=" background:#EDF1D1; border:0px #c4da9b solid; color:#393; background:#EDF1D1 url(images/green-bg.png); border:0px #c4da9b solid; color:#393;  ">

<div class="formConInner" style="padding:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	
  <tr>
    <td></td>
    <td> </td>
    <td><?php 
	if($model->photo_data==NULL)
	{
	echo $form->labelEx($model,Yii::t('app','Upload Photo'));
	}
	else
	{
	echo $form->labelEx($model,Yii::t('app','Photo'));	
	}
	
	 ?>
		</td>
    <td>
	<?php 
		
		
		if($model->isNewRecord)
		{
			echo $form->fileField($model,'photo_data'); 
		    echo $form->error($model,'photo_data'); 
		}
		else
		{
			if($model->photo_file_name==NULL)
			{
				echo $form->fileField($model,'photo_data'); 
		        echo $form->error($model,'photo_data'); 
			}
			
			else
			{
				if(Yii::app()->controller->action->id=='update') {
					echo CHtml::link(Yii::t('app','Remove'), array('Employees/remove', 'id'=>$model->id),array('confirm'=>'Are you sure?')); 
					if($model->photo_file_name!=NULL){
						$path = Employees::model()->getProfileImagePath($model->id);
								
						echo '<img class="imgbrder" src="'.$path.'" alt="'.$model->photo_file_name.'" width="100" height="100" />';
					}
				}
				else if(Yii::app()->controller->action->id=='create') {
					echo CHtml::hiddenField('photo_file_name',$model->photo_file_name);
					echo CHtml::hiddenField('photo_content_type',$model->photo_content_type);
					echo CHtml::hiddenField('photo_file_size',$model->photo_file_size);
					echo CHtml::hiddenField('photo_data',bin2hex($model->photo_data));
					echo '<img class="imgbrder" src="'.$this->createUrl('Employees/DisplaySavedImage&id='.$model->primaryKey).'" alt="'.$model->photo_file_name.'" width="100" height="100" />';
				}
			}
		}
		
		 ?>
        
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>        
  </tr>
  <tr>
    <td>&nbsp;</td>                 	
    <td colspan="3">  
        <div id="image_size_error" style="color:#F00;"></div>                
     	 <?php echo Yii::t('app','Maximum file size is 1MB. Allowed file types are png,gif,jpeg,jpg'); ?>                        
    </td>
 </tr>

</table>
<div class="row">
		<?php //echo $form->labelEx($model,'photo_file_size'); ?>
		<?php echo $form->hiddenField($model,'photo_file_size'); ?>
		<?php echo $form->error($model,'photo_file_size'); ?>
	</div>

</div>
</div>
<div class="clear"></div>
	<div style="padding:0px 0 0 0px; text-align:left">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Next Step') : Yii::t('app','Save'),array('id'=>'submit_button_form','class'=>'formbut')); ?>
	</div>


</div>
</div><!-- form -->
<?php $this->endWidget(); ?>
<script type="text/javascript">

$('#submit_button_form').click(function(ev) {	
	var file_size = $('#Employees_photo_data')[0].files[0].size;	
	
	if(file_size>1048576)//File upload size limit to 1mb
	{		   	
		$('#image_size_error').html('<?php echo Yii::t('app','File size is greater than 1MB'); ?>');
		return false;
	}		
});

function star(){
	var year = '';
	var year_val = '';
	var mnth = '';
	var mnth_val = '';
	year = document.getElementById('experience_year');
	year_val = year.options[year.selectedIndex].value;
	mnth = document.getElementById('experience_month');
	mnth_val = mnth.options[mnth.selectedIndex].value;
	if(year_val!='' && year_val!=0){
		//alert(year_val);
		document.getElementById('required').style.visibility='visible';
	}
	if((year_val=='' || year_val==0) && (mnth_val=='' || mnth_val==0))
	{
		document.getElementById('required').style.visibility='hidden';
	}
	
}
function star2(){
	var year = '';
	var year_val = '';
	var mnth = '';
	var mnth_val = '';
	year = document.getElementById('experience_year');
	year_val = year.options[year.selectedIndex].value;
	mnth = document.getElementById('experience_month');
	mnth_val = mnth.options[mnth.selectedIndex].value;
	if(mnth_val!='' && mnth_val!=0){
		//alert(year_val);
		document.getElementById('required').style.visibility='visible';
	}
	if((year_val=='' || year_val==0) && (mnth_val=='' || mnth_val==0))
	{
		document.getElementById('required').style.visibility='hidden';
	}
}

$( document ).ready(function() { 
	year = document.getElementById('experience_year');
	mnth = document.getElementById('experience_month');
	var year_val	= year.options[year.selectedIndex].value;
	var month_val	=mnth.options[mnth.selectedIndex].value;
	if((year_val!='' || year_val!=0) && (month_val!='' || month_val!=0))	{
		document.getElementById('required').style.visibility='hidden';
	}
});
</script>