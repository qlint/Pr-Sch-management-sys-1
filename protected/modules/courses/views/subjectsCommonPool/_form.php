<style>
.popup-input input[type="text"], textArea, select{
	/*width:100% !important;*/
 	box-sizing:border-box;
}
</style>
<div class="popup-input">
<?php
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'subjects-common-pool-form',
	)); 
?>
		<p class="note"><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app',' are required.');?></p>
		<?php echo $form->errorSummary($model); ?>
    	<div>
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        		<tr>
<?php 
					if(isset($_GET['val1']) and $_GET['val1'] !='null'){				
						$model->course_id = $_GET['val1'];					
					}
					if(Yii::app()->user->year){					
						$year = Yii::app()->user->year;					
					}					
					else{					
						$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));					
						$year = $current_academic_yr->config_value;					
					}

					$criteria = new CDbCriteria;												
					$criteria->condition = 'is_deleted = :is_deleted and academic_yr_id = :academic_yr_id';					
					$criteria->params = array(':is_deleted'=>0,':academic_yr_id'=>$year);					
					echo $form->dropDownList($model,'course_id',CHtml::listData(Courses::model()->findAll($criteria), 'id', 'course_name'),array(					
					'onchange'=>'checksplit()', 'class'=>'form-control','encode'=>false,'empty'=>Yii::t('app','Select Course'))); 			
					
			 	 	if(isset($_REQUEST['sub_id']) and Yii::app()->controller->action->id=='addupdate'){
            			 echo $form->hiddenField($model,'id',array('size'=>40,'maxlength'=>255,'value'=>$_REQUEST['sub_id'],''));
			 		}
?>
            	</tr>
				<tr>
					<td><?php echo $form->labelEx($model,'subject_name'); ?></td>
				</tr>
            	<tr>
                	<td>
						<div>
<?php  
							echo $form->textField($model,'subject_name',array('encode'=>false,'size'=>20,'maxlength'=>255,'style'=>'width:100%')); 
							echo $form->error($model,'subject_name');
?>
                		</div>
					</td>             
				</tr>
				<tr>				
					<td><?php echo $form->labelEx($model,'max_weekly_classes'); ?></td>				
				</tr>
            	<tr>
                	<td><div>
<?php 
						if($model->max_weekly_classes == NULL){
							$model->max_weekly_classes = '';
						}
						echo $form->textField($model,'max_weekly_classes',array('size'=>20,'maxlength'=>255,'style'=>'width:100%')); 
						echo $form->error($model,'max_weekly_classes'); ?>
					</div></td>
            	</tr> 
				<tr>
					<td>&nbsp;</td>
				</tr>					
				<tr class="split-check-tr"> 
					<td> 
						<?php echo $form->checkBox($model,'split_subject'); ?>
						<?php echo Yii::t('app','Split Subject'); ?>
					</td>                    
				</tr>
<?php  
				if(!$model->isNewRecord){
					$common_cps	= SubjectCommonpoolSplit::model()->findAllByAttributes(array('subject_id'=>$model->id));
					$k			= 1;
					foreach($common_cps as $common_cp){						
						$att			= 'subject_tilte'.$k;						
						$model->$att	= $common_cp->split_name; 						
						$k++;						
					}
				} 

				for($i=1;$i<=2;$i++){
?>
					<tr class="split-tr">
						<td>
<?php 
							if($i==1){ 
?>
								<label><?php echo Yii::t('app','First Sub Category');?>&nbsp;<span class="required" id="category_tr">*</span></label>
<?php	
							}
							else if($i==2){ 
?>
								<label><?php echo Yii::t('app','Second Sub Category');?>&nbsp;<span class="required" id="category_tr">*</span></label>
<?php
							}
?>
						</td>
					</tr> 
					<tr class="split-tr"> 
						<td> 
							<?php 
								echo $form->textField($model,'subject_tilte'.$i,array('encode'=>false,'size'=>20,'maxlength'=>255,'style'=>'width:100%'));
								echo $form->error($model,'subject_tilte'.$i); 
							?>
						</td>                   

					</tr>							
<?php
				}
					
?>
				<tr class="split-tr">
					<td>&nbsp;</td>
				</tr>			
<?php								
			  	if($model->isNewRecord){ 
?>				
					<tr>					
						<td>
							<?php echo $form->checkBox($model,'all_batches',array('checked'=>"true")); ?>					
							<?php echo Yii::t('app','All').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?>
						</td>                    					
					</tr>
<?php 
				}
?> 
			</table>
			<div class="popup_btn">
     			<?php
					echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('subjectsCommonPool/create','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
					if(data.status == "success"){
						$("#jobDialogs").dialog("close");

					 	if(data.flag==1){
							window.location.reload();
					 	}
					}
					else{
						$(".errorMessage").remove();
						var errors	= JSON.parse(data.errors);						
						$.each(errors, function(index, value){
							var err	= $("<div class=\"errorMessage\" />").text(value[0]);
							err.insertAfter($("#" + index));							
						});
					}
                    }'),array('id'=>'closeJobDialog','name'=>Yii::t('app','Submit'))); ?>
				</div>
<?php 
			$this->endWidget();
			if(isset($model->split_subject) and $model->split_subject==1){	
				$split_value	=	1;	
			}else{	
				$split_value	=	0;	
			}
			
			if((isset($_REQUEST['val1']) and $_REQUEST['val1'] != NULL) or (isset($_REQUEST['course_id']) and $_REQUEST['course_id'] != NULL)){ 
				if($_REQUEST['val1'] != NULL){
					$course_id	= $_REQUEST['val1'];
				}
				else if($_REQUEST['course_id'] != NULL){
					$course_id	= $_REQUEST['course_id'];
				}
				$course = Courses::model()->findByAttributes(array('id'=>$course_id));				
				if($course != NULL and $course->exam_format != 2){ 
					$is_split_check	= 1;	
				}				
				else{
					$is_split_check	= 0;
				}
			}			
?>
	</div>
</div>

<script type="text/javascript">
var split_subject	= '<?php echo $split_value;?>';
var is_split_check	= '<?php echo $is_split_check; ?>';
if(split_subject == 1){	
	$('.split-check-tr').show();
	$('.split-tr').show();	
	$('#SubjectsCommonPool_split_subject').prop('checked', true);
}
else{
	$('#SubjectsCommonPool_split_subject').prop('checked', false);
	$('.split-check-tr').hide();
	$('.split-tr').hide();
}
if(is_split_check == 1){
	$('.split-check-tr').show();
}
else{
	$('.split-check-tr').hide();
}
$("#SubjectsCommonPool_split_subject").click(function(){
	if($(this).is(":checked")){
		$('.split-tr').show();
	}else{
		$("#SubjectsCommonPool_subject_tilte1").val('');
		$("#SubjectsCommonPool_subject_tilte2").val('');
		$('.split-tr').hide();
	}
});   

function checksplit(){ 
	var course_id = $('#SubjectsCommonPool_course_id').val();
	if(course_id != ''){ 
		$.ajax({
			type: "POST",
			url: <?php echo CJavaScript::encode(Yii::app()->createUrl('courses/batches/checkcoursecbsc'))?>,
			data: {'course_id':course_id, "<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
			success: function(result){						
				if(result == 2){ // cbsc									
					$('#SubjectsCommonPool_split_subject').prop('checked', false);
					$("#SubjectsCommonPool_subject_tilte1").val('');
					$("#SubjectsCommonPool_subject_tilte2").val('');
					$('.split-tr').hide();
					$('.split-check-tr').hide();
				}
				else{
					$('.split-check-tr').show();								
				}
			}
		});
	}		
}
</script>