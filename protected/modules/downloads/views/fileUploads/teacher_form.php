<style>
.required{
	color:#4a535e;
}
.required span{
	color:#F00;
}

.note span{
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
<div class="row">
	<div class="col-md-6">
    <div class="row settng_block">
	<p class="form_required"><?php echo Yii::t('app', 'Fields with');?> <span class="required" >*</span> <?php echo Yii::t('app', 'are required.');?></h5>
    
<div class="col-md-12">
	<div class="form-group">
		<?php echo $form->labelEx($model,'title', array('class'=>'sttngs_label')); ?> 
        <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'title'); ?> 
	</div>
</div>
<div class="col-md-12"> 
    <div class="form-group">
		<?php echo $form->labelEx($model,'category', array('class'=>'sttngs_label')); ?>
        <?php echo $form->dropDownList($model,'category',CHtml::listData(FileCategory::model()->findAll(),'id','category'),array('prompt'=>Yii::t('app', 'Select category'),'class'=>'form-control')); ?>
        <?php echo $form->error($model,'category'); ?>
    </div>
</div>       
<div class="col-md-12"> 		
    <div class="form-group">
		<?php echo $form->labelEx($model,'placeholder', array('class'=>'sttngs_label')); ?>
        <?php
        $all_roles	=	new RAuthItemDataProvider('roles', array( 
        'type'=>2,
        ));
        $data		= $all_roles->fetchData();	
        $data_arr 	= array();
        if($data){
        foreach($data as $value){
        if($value->name != 'parent' and $value->name != 'BusSupervisor'){
        $data_arr[$value->name] = $value->name;
        }
        }
        }
        echo $form->dropDownList($model,'placeholder',$data_arr,array('prompt'=>Yii::t('app', 'Public') ,'class'=>'form-control'));
        ?>
        <?php echo $form->error($model,'placeholder'); ?>
    </div>
</div>
<div class="col-md-12">
	<div class="form-group course-batch">
		<?php echo $form->labelEx($model,'course', array('class'=>'sttngs_label')); ?>
 		<?php
            $current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
            
            $year = $current_academic_yr->config_value;
           
            $data 		= CHtml::listData(Courses::model()->findAll('is_deleted=:x AND academic_yr_id=:y',array(':x'=>'0',':y'=>$year),array('order'=>'course_name DESC')),'id','course_name');
            echo $form->dropDownList($model,'course',$data,
            array('prompt'=>Yii::t('app', 'Select Course'), 'encode'=>false,'class'=>'form-control',
            'ajax' => array(
            'type'=>'POST',
            'url'=>CController::createUrl('fileUploads/batch'),
            'update'=>'#batch_id',
            //'data'=>'js:$(this).serialize()'
            'data'=>'js:{course:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
            ))); 
            ?>
            <?php echo $form->error($model,'course'); ?>
	</div>
</div>
<div class="col-md-12">            
<div class="form-group course-batch">
    <?php echo $form->labelEx($model,'batch', array('class'=>'sttngs_label')); ?>    
    <?php
        if($model->course != NULL){
            $batches	= Batches::model()->findAll('is_active=:x AND is_deleted=:y AND course_id=:z',array(':x'=>'1',':y'=>0,':z'=>$model->course),array('order'=>'name DESC'));	
        }
        else{
            $batches	= Batches::model()->findAll('is_active=:x AND is_deleted=:y AND academic_yr_id=:z',array(':x'=>'1',':y'=>0,':z'=>$year),array('order'=>'name DESC'));
        }
        $data1		=	CHtml::listData($batches,'id','name');                     
        echo $form->dropDownList($model,'batch',$data1,array('options' => array($model->batch=>array('selected'=>true)), 'class'=>'form-control', 'id'=>'batch_id', 'empty'=>Yii::t('app','Select').' '.Students::model()->getAttributeLabel('batch_id'),'ajax'=>array(
                'type'=>'POST', 
                'url'=>Yii::app()->createUrl('/downloads/teachers/students'),						
                'update'=>'#students',
                'data'=>array('batch_id'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
              )));		
        echo $form->error($model,'batch'); 
    ?> 
    </div>
</div>
<div class="col-md-12">   
    <div class="form-group student-div">
            <?php echo $form->labelEx($model,'students', array('class'=>'sttngs_label')); ?>
            <?php			
                $students_arr	= array();
                $options		= array();	
                if($model->batch != NULL){
                    $student_lists	= Yii::app()->getModule('students')->studentsOfBatch($model->batch);
                    if($student_lists){
                        foreach($student_lists as $student){
                            $students_arr[$student->id] = $student->studentFullName('forTeacherPortal');
                        }
                    }
                }
                if($_POST['FileUploads']['students'] != NULL){
                    if(count($_POST['FileUploads']['students']) > 0){
                        for($i = 0; $i < count($_POST['FileUploads']['students']); $i++){
                            $options[$_POST['FileUploads']['students'][$i]] = array('selected' => 'selected');	
                        }
                    }	
                }
                else{
                    if($model->id != NULL){
                        $file_upload_students = FileUploadsStudents::model()->findAllByAttributes(array('table_id'=>$model->id));
                        if($file_upload_students){
                            foreach($file_upload_students as $value){
                                $options[$value->student_id] = array('selected' => 'selected');	
                            }
                        }
                    }
                }
                        
                echo $form->dropDownList($model, 'students[]', $students_arr, array('multiple'=>'multiple', 'id'=>'students','class'=>'form-control', 'empty'=>Yii::t('app','Select Student'),'encode' => false, 'options' =>$options));	
                echo $form->error($model,'students'); 
            ?> 
        </div>
</div>
<div class="col-md-12">     
<div class="form-group">
   <?php echo $form->labelEx($model,'description', array('class'=>'sttngs_label')); ?>       
        <?php 
            echo $form->textArea($model,'description',array('rows'=>3, 'class'=>'form-control'));		
            echo $form->error($model,'description'); 
        ?> 
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
			<?php echo $form->labelEx($model,'file', array('class'=>'sttngs_label required')); ?>
         	<?php
                if(!$model->isNewRecord and $model->file!=NULL and file_exists('uploads/shared/'.$model->id.'/'.$model->file)){
                    echo $model->file;
            ?>
            <?php echo CHtml::link(Yii::t('app','Remove'), "#", array("submit"=>array('removefile','id'=>$model->id),'confirm' => Yii::t('app', 'Are you sure you want to remove this file ?'), 'csrf'=>true));?>
            <?php
                }
                else{
            ?>		
            <?php echo $form->fileField($model,'file',array('rows'=>6, 'cols'=>50)).'('. Yii::t('app','Only files with these extensions are allowed: jpg, jpeg, png, gif, pdf, mp4, doc, txt, ppt, docx').')'; ?>
            <?php echo $form->error($model,'file'); ?>
            <?php 
                }
            ?>
        </div>
</div>
<div class="col-md-12">		        
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'btn  opnsl_fllBtn')); ?>
	</div>
</div>
</div>

<?php $this->endWidget(); ?>
</div>	
</div><!-- form -->
</div>
<script type="text/javascript">
var placeholder	= $('#FileUploads_placeholder').val();
$('.course-batch').hide();
$('.student-div').hide();
if(placeholder == 'student' || placeholder == 'teacher'){
	$('.course-batch').show();
	if(placeholder == 'student'){
		$('.student-div').show();
	}
}
$('#FileUploads_placeholder').change(function(ev){
	var placeholder	= $('#FileUploads_placeholder').val();
	if(placeholder == 'student' || placeholder == 'teacher'){
		$('.course-batch').show();
		if(placeholder == 'student'){
			$('.student-div').show();
		}
		else{
			$('#students').empty();
			$("<option />", {
				val: '',
				text: '<?php echo Yii::t('app', 'Select Student'); ?>'
			}).appendTo('#students');
			$('.student-div').hide();	
		}
	}
	else{
		$('#FileUploads_course').val('');		
		$('#batch_id').empty();
		$('#students').empty();
		
		$("<option />", {
			val: '',
			text: '<?php echo Yii::t('app', 'Select').' '.Students::model()->getAttributeLabel('batch_id'); ?>'
		}).appendTo('#batch_id');
		$("<option />", {
			val: '',
			text: '<?php echo Yii::t('app', 'Select Student'); ?>'
		}).appendTo('#students');
		$('.course-batch').hide();
		$('.student-div').hide();
	}
});
$('#FileUploads_course').change(function(ev){
	$('#students').empty();
	$("<option />", {
			val: '',
			text: '<?php echo Yii::t('app', 'Select Student'); ?>'
		}).appendTo('#students');
});
</script>