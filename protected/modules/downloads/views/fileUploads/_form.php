<style>
.required{
	color:#F00;
}

</style>
<div class="inner_new_form">
<div class="form">
<div class="inner_new_formCon">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'file-uploads-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<h3 class="note"><?php echo Yii::t('app','Fields with').'<span class="required" > * </span>'.Yii::t('app','are required.');?></h3>

	<?php echo $form->errorSummary($model); ?>

	<div class="inner_new_formCon_row">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%"><?php echo $form->labelEx($model,'title'); ?></td>
    <td width="80%"><?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100)); ?></td>
  </tr>
</table>

	</div>

	<div class="inner_new_formCon_row">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%"><?php echo $form->labelEx($model,'category'); ?></td>
    <td width="80%"><?php echo $form->dropDownList($model,'category',CHtml::listData(FileCategory::model()->findAll(),'id','category'),array('prompt'=>Yii::t('app','Select category'))); ?></td>
  </tr>
</table>

		
        
	</div>

	<div class="inner_new_formCon_row">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%"><?php echo $form->labelEx($model,'placeholder'); ?></td>
    <td width="80%"><?php
			$all_roles	=	new RAuthItemDataProvider('roles', array( 
				'type'=>2,
			));
			
			$role_arr	= array();
			$datas		= $all_roles->fetchData();	
			if($datas){
				foreach($datas as $data){					
					if($data->name != 'parent' and $data->name != 'BusSupervisor'){
						$role_arr[$data->name] = $data->name;
					}
				}
			}
			echo $form->dropDownList($model,'placeholder',$role_arr,array('prompt'=>Yii::t('app','Public')));
        ?>
	</td>
  </tr>
</table>

		
        
	</div>
    
    
    <div class="inner_new_formCon_row course-batch">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%"><?php echo $form->labelEx($model,'course'); ?></td>
    <td width="80%"> <?php
		$current_academic_yr = Configurations::model()->findByPk(35);
		if(Yii::app()->user->year){
			$year = Yii::app()->user->year;
		}
		else{
			$year = $current_academic_yr->config_value;
		}
		
        $data 		= CHtml::listData(Courses::model()->findAll('is_deleted=:x AND academic_yr_id=:y',array(':x'=>'0',':y'=>$year),array('order'=>'course_name DESC')),'id','course_name');
		echo $form->dropDownList($model,'course',$data,
		array('prompt'=>Yii::t('app','Select Course'),
		'ajax' => array(
		'type'=>'POST',
		'url'=>CController::createUrl('fileUploads/batch'),
		'update'=>'#batch_id',
		'data'=>'js:{course:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',)));?> 
	</td>
  </tr>
</table>

		
       
	</div>

	<div class="inner_new_formCon_row course-batch">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%"><?php echo $form->labelEx($model,'batch'); ?></td>
    <td width="80%"><?php 
			if($model->course != NULL){
				$batches	= Batches::model()->findAll('is_active=:x AND is_deleted=:y AND course_id=:z',array(':x'=>'1',':y'=>0,':z'=>$model->course),array('order'=>'name DESC'));	
			}
			else{
				$batches	= Batches::model()->findAll('is_active=:x AND is_deleted=:y AND academic_yr_id=:z',array(':x'=>'1',':y'=>0,':z'=>$year),array('order'=>'name DESC'));
			}
			$data1		=	CHtml::listData($batches,'id','name');
			echo CHtml::activeDropDownList($model,'batch',$data1,array('prompt'=>Yii::t('app','Select').' '.Students::model()->getAttributeLabel('batch_id'),'id'=>'batch_id'));
		 ?>
	</td>
  </tr>
</table>
	</div>


	<div class="inner_new_formCon_row">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%"><?php echo $form->labelEx($model,'file'); ?></td>
    <td width="80%"> <?php
			if(!$model->isNewRecord and $model->file!=NULL and file_exists('uploads/shared/'.$model->id.'/'.$model->file)){
				echo $model->file;
		?>
        <?php echo CHtml::link(Yii::t('app','Remove'),array('removefile','id'=>$model->id),array('confirm'=>Yii::t('app','Are you sure you want to remove this file ?')));?>
        <?php
			}
			else{
		?>		
        <?php echo $form->fileField($model,'file',array('rows'=>6, 'cols'=>50))."<br />".'('.Yii::t('app','Maximum file size is 10MB. Only files with these extensions are allowed: jpg, jpeg, png, gif, pdf, mp4, doc, txt, ppt, docx').' )'; ?>
		
        <?php 
			}
		?>
        <div class="required" id="file_error"></div>
        </td>
  </tr>
</table>

		
       
	</div>

	<div class="inner_new_formCon_row row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('id'=>'submit_button_form', 'class'=>'formbut')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>	
</div><!-- form -->
</div>
<script type="text/javascript">
$('#submit_button_form').click(function(ev) {	
	$('#file_error').html('');		
	var file_size = $('#FileUploads_file')[0].files[0].size;		
	if(file_size>10485760){ //File upload size limit to 10mb			   	
		$('#file_error').html('<?php echo Yii::t('app','Maximum file size allowed is 10MB'); ?>');
		return false;
	}	
});

var placeholder	= $('#FileUploads_placeholder').val();
$('.course-batch').hide();
if(placeholder == 'student' || placeholder == 'teacher'){
	$('.course-batch').show();	
}

$('#FileUploads_placeholder').change(function(ev){
	var placeholder	= $('#FileUploads_placeholder').val();
	if(placeholder == 'student' || placeholder == 'teacher'){
		$('.course-batch').show();		
	}
	else{
		$('#FileUploads_course').val('');		
		$('#batch_id').empty();		
		
		$("<option />", {
			val: '',
			text: '<?php echo Yii::t('app', 'Select').' '.Students::model()->getAttributeLabel('batch_id'); ?>'
		}).appendTo('#batch_id');		
		$('.course-batch').hide();		
	}
});
</script>