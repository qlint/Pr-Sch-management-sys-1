<style>
.invoice-fltr input[type="text"]{
	width:85%;
	
}
.invoice-fltr select{
	width:85%;
}
</style>
<?php
//get academic year
if(Yii::app()->user->year){
	$year 					= Yii::app()->user->year;
}
else{
	$current_academic_yr 	= Configurations::model()->findByAttributes(array('id'=>35));
	$year 					= $current_academic_yr->config_value;
}

$criteria	= new CDbCriteria;
$criteria->compare("academic_yr_id", $year);
$criteria->compare("is_deleted", 0);	
$courses	= Courses::model()->findAll($criteria);
if(count($courses)>0){
	$courses	= CHtml::listData($courses, "id", "course_name");
}
else{
	$courses	= array();
}

if($search->course){
	$criteria	= new CDbCriteria;
	$criteria->compare("course_id", $search->course);
	$criteria->compare("is_active", 1);
	$batches		= Batches::model()->findAll($criteria);
	if(count($batches)>0){
		$batches	= CHtml::listData($batches, 'id', 'name');
	}
	else{
		$batches	= array();
	}
}

//fee categories
$criteria	= new CDbCriteria;
$criteria->condition		= 'academic_year_id=:yr';
$criteria->params[':yr'] 	= $year;
$categorie	= FeeCategories::model()->findAll($criteria);
if(count($categorie)>0){
	$categories	= CHtml::listData($categorie, "id", "name");
}
else{
	$categories	= array();
}

$form=$this->beginWidget('CActiveForm', array(
	'method'=>'get',
));
?>

<div class="formCon">
<div class="formConInner invoice-fltr">
<h3><?php echo Yii::t('app','Search Invoices'); ?></h3>
<table width="98%">	
	<tr>
    	<td><?php echo Yii::t("app", "Fee Category");?></td>
    	<td><?php echo Yii::t("app", "Invoice ID");?></td>
        <td colspan="2"><?php echo Yii::t("app", "Recipient name");?></td>
    </tr>
    <tr>
    	<td><?php echo $form->dropDownList($search, 'fee_id', $categories, array("prompt"=>Yii::t("app", "All categories")));?></td>
    	<td><?php echo $form->textField($search, 'id');?></td>
        <td colspan="2"><?php echo $form->textField($search, 'uid', array('style'=>'width:212px;'));?></td>
    </tr>
    <tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td><?php echo Yii::t("app", "Course");?></td>
        <td><?php echo Yii::t("app", "Batch");?></td>
        <td><?php echo Yii::t("app", "Status");?></td>
        <td><?php echo Yii::t("app", "Invoice Date");?></td>		
    </tr>
    <tr>
        <td><?php echo $form->dropDownList($search, 'course', $courses, array('prompt'=>Yii::t("app", "All courses")));?></td>
        <td><?php echo $form->dropDownList($search, 'batch', $batches,array('prompt'=>Yii::t("app", "All batches")));?></td>
        <td><?php echo $form->dropDownList($search, 'is_paid', array(1=>Yii::t("app", "Paid"), 0=>Yii::t("app", "Unpaid"), -1=>Yii::t("app", "Cancelled")), array("prompt"=>Yii::t("app", "All")));?></td>
        <td>
			<?php
				$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings!=NULL){
					$dateformat	= $settings->dateformat;
				}
				else
					$dateformat = 'dd-mm-yy';
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$search,
					'attribute'=>'created_at',
					// additional javascript options for the date picker plugin
					'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>$dateformat,
						'changeMonth'=> true,
						'changeYear'=>true,
						'yearRange'=>'1900:'.(date('Y')+5)
					),
					'htmlOptions'=>array(
						'class'=>'custom-date',
						'data-timeid'=>$timeid,
					),
				));
			?>
        </td>
  
    </tr>
	
	<tr>
		<td colspan="4" style="padding-top:9px;"><?php echo CHtml::submitButton(Yii::t("app", 'Search'), array('name'=>'','class'=>'formbut')); ?></td>
	</tr>
</table>
</div>
</div>
<?php $this->endWidget(); ?>
<script>
$("#FeeInvoices_course").unbind('change').change(function(e) {
	var that	= this;
	if($(that).val!=""){
		var course	= $(that).val();
		$.ajax({
			url:'<?php echo Yii::app()->createUrl("/fees/invoices/getBatches");?>',
			type:'GET',
			data:{course:course},
			success: function(response){
				$('#FeeInvoices_batch').html(response);
			}
		});
	}
});
</script>