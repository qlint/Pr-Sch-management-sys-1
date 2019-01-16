<style type="text/css">
.required{
	color:#F00;
}
</style>
<script>
	function removeFile() 
	{	
		if(document.getElementById("new_file").style.display == "none")
		{
			document.getElementById("existing_file").style.display = "none";
			document.getElementById("new_file").style.display = "block";
			document.getElementById("new_file_field").value = "1";
		}
		
		return false;
	}
</script>

<div id="parent_Sect">
	<?php 
	$this->renderPartial('leftside');
    $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    $guard = Guardians::model()->findByAttributes(array('id'=>$student->parent_id));
    $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
	?>
<div class="pageheader">
  <div class="col-lg-8">
    <h2><i class="fa fa-user"></i><?php echo Yii::t('app','Profile');?><span>View your profile here <?php echo CHtml::link('<span>'.Yii::t('app','Edit Profile').'</span>',array('editprofile'),array('class'=>'addbttn last'));?></span></h2>
  </div>  
  <div class="breadcrumb-wrapper"> <span class="label">You are here:</span>
    <ol class="breadcrumb">
      <!--<li><a href="index.html">Home</a></li>-->
      
      <li class="active"><?php echo Yii::t('app','Profile'); ?></li>
    </ol>
  </div>
  <div class="clearfix"></div>
</div>    
<div class="contentpanel">
  <div class="col-sm-9 col-lg-12">
    <div class="people-item">
      <div class="media"> <a href="#" class="pull-left">
        <?php
		 if($student->photo_file_name!=NULL)
		 {
			 $path = Students::model()->getProfileImagePath($student->id); 
			echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'" width="100" height="103" class="thumbnail media-object" />';
		}
		elseif($student->gender=='M')
		{
			echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' width="100" height="103" class="thumbnail media-object" />'; 
		}
		elseif($student->gender=='F')
		{
			echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.' width="100" height="103" class="thumbnail media-object" />';
		}
		?>
        </a>
        <div class="media-body">
          <h4 class="person-name"><?php echo ucfirst($student->last_name).' '.ucfirst($student->first_name);?></h4>
          <div class="text-muted"><strong><?php echo Yii::t('app','Course :');?></strong>
            <?php 
			$batch = Batches::model()->findByPk($student->batch_id);
			echo $batch->course123->course_name;
			?>
          </div>
          <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></strong> <?php echo $batch->name;?></div>
          <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
        </div>
      </div>
    </div> <!-- END div class="profile_top" -->
            	
            <!-- Document Area -->
            <div class="people-item">	
            	<br />
            	
            	<div class="form">
                <div class="btn-demo" style="position:relative; top:-20px; right:3px; float:right;">
                	<div class="edit_bttns">
                        <ul>
                            <li><span>
                                <?php echo CHtml::link(Yii::t('app','Student Profile'), array('profile'),array('class'=>'')); ?>
                           </span> </li>
                        </ul>
                	</div> 
                    </div>
                    <!-- END div class="edit_bttns last" -->

					<?php $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'student-document-form',
                        'enableAjaxValidation'=>false,
                        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
                        //'action'=>CController::createUrl('documentupdate',array('document_id'=>$model->id))
                    )); ?>
                    
                        <?php 
                            if($form->errorSummary($model)){
                        ?>
                            <div class="errorSummary"><?php echo Yii::t('app','Input Error'); ?><br />
                                <span><?php echo Yii::t('app','Please fix the following error(s).'); ?></span>
                            </div>
                        <?php 
                            }
                            //var_dump($model->attributes);exit;
                        ?>
                        
                        <p class="note" style="float:left"><?php echo Yii::t('app','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app','are required. Upload a file if it is removed.'); ?></p>
                        <?php /*?><p class="note" style="float:left">Fields with <span class="required">*</span> are required.</p><?php */?>
                        
                        
                        <?php
                        Yii::app()->clientScript->registerScript(
                           'myHideEffect',
                           '$(".error").animate({opacity: 1.0}, 3000).fadeOut("slow");',
                           CClientScript::POS_READY
                        );
                        if(Yii::app()->user->hasFlash('errorMessage')): 
                        ?>
                            <div class="error" style="background:#FFF; color:#C00; margin-left:340px; top:150px; width:300px;">
                                <?php echo Yii::app()->user->getFlash('errorMessage'); ?>
                            </div>
                        <?php
                        endif;
                        ?>
                    
                        <div class="formCon" style="clear:left;">
                            <div class="formConInner" id="innerDiv">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="documentTable">
                                    <tr>
                                        <td><?php echo $form->labelEx($model,Yii::t('app','Document'),array('style'=>'float:left;')); ?><span class="required">&nbsp;*</span></td>
                                        <td>&nbsp;</td>
                                        <td class="hide-td"><?php echo $form->labelEx($model,Yii::t('app','Document Name'),array('style'=>'float:left;')); ?><span class="required">&nbsp;*</span></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    
                                    <tr>
                                    	<td>
                                        	<?php
												$static 		= array('Others' => 'Others');
												$document_arr	= array();
												
												$criteria			= new CDbCriteria();
                            					$criteria->join = 'LEFT JOIN student_document sd ON sd.doc_type = t.name and sd.doc_type <> "'.$model->doc_type.'" and sd.student_id = '.$model->student_id.'';
                            					$criteria->addCondition('sd.doc_type IS NULL');
												$student_documents	= StudentDocumentList::model()->findAll($criteria);
												if($student_documents != NULL){
													foreach($student_documents as $student_document){
														$document_arr[$student_document->name]	= html_entity_decode(ucfirst($student_document->name));
													}
												}
												echo $form->dropDownList($model,'doc_type',$document_arr + $static,array('prompt'=>Yii::t('app','Select Document'), 'class'=>'form-control'));	
											?>
                                            
                                        </td>
                                        <td>&nbsp;</td>
                                        <td class="hide-td">
                                            <?php echo $form->textField($model,'title',array('size'=>25,'maxlength'=>225,'class'=>'form-control')); ?>
                                             <?php echo $form->error($model,'title'); ?>
                                             
                                        </td>
                                        <td id="existing_file">
                                            <?php 
                                            if($model->file!=NULL and $model->file_type!=NULL)
                                            {
                                            ?>
                                            <ul class="tt-wrapper">
                                                <li><span>
                                                    <?php echo CHtml::link(Yii::t('app','View'), array('download','id'=>$model->id,'student_id'=>$model->student_id),array('class'=>'tt-download')); ?>
                                               </span> </li>
                                                <li><span>
                                                    <?php echo CHtml::link(Yii::t('app','Remove'), array('#'),array('class'=>'tt-delete','onclick'=>'return removeFile();')); ?>
                                               </span> </li>
                                            </ul>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td id="new_file" style="display:none; padding-left:20px;">
                                            <?php echo $form->fileField($model,'file'); ?>
                                            <?php echo $form->error($model,'file'); ?>
                                            <?php echo $form->hiddenField($model,'new_file_field',array('value'=>0,'id'=>'new_file_field')); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td><span class="type-error required"></span></td>
                                        <td>&nbsp;</td>
                                        <td><span class="title-error required"></span></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                                
                                <div class="row" id="student_id">
                                    <?php echo $form->hiddenField($model,'student_id',array('value'=>$model->student_id)); ?>
                                    <?php echo $form->error($model,'student_id'); ?>
                                </div>
                            
                                <div class="row" id="file_type">
                                    <?php //echo $form->labelEx($model,'file_type'); ?>
                                    <?php echo $form->hiddenField($model,'file_type'); ?>
                                    <?php echo $form->error($model,'file_type'); ?>
                                </div>
                            
                                <div class="row" id="created_at">
                                    <?php //echo $form->labelEx($model,'created_at'); ?>
                                    <?php echo $form->hiddenField($model,'created_at'); ?>
                                    <?php echo $form->error($model,'created_at'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row buttons" style="margin: 10px 0;">
                            <?php //echo CHtml::button('Add Another', array('class'=>'formbut','id'=>'addAnother','onclick'=>'addRow("documentTable");')); ?>
                            <?php echo CHtml::submitButton(Yii::t('app','Update'),array('id'=>'submit-btn', 'class'=>'btn btn-danger','submit'=>array('default/documentupdate','document_id'=>$model->id,'id'=>$model->student_id))); ?>
                        </div>
                            
                    
                    <?php $this->endWidget(); ?>
                   
                    </div> 
                    <!-- form -->
            </div> <!-- END div class="document_box" -->
        </div> <!-- END div class="parentright_innercon" -->
	</div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
<div class="clear"></div>

<?php if($model->doc_type == 'Others'){ ?>
	<script>$('.hide-td').show();</script>
<?php }else{ ?>
	<script>$('.hide-td').hide();</script>
<?php } ?>

<script type="text/javascript">
$('#StudentDocument_doc_type').change(function(ev){
	var document_type	= $(this).val();
	if(document_type == 'Others'){
		$('.hide-td').show();		
	}
	else{
		$('#StudentDocument_title').val('');
		$('.title-error').html('');
		$('.hide-td').hide();		
	}
});

$('#submit-btn').click(function(ev){
	var document_type	= $('#StudentDocument_doc_type').val();
	var title			= $('#StudentDocument_title').val();
	var flag			= 0;
	$('.type-error').html('');
	$('.title-error').html('');
	if(document_type == ''){
		$('.type-error').html("<?php echo Yii::t('app', 'Document cannot be blank'); ?>");
		flag	= 1;	
	}
	if(document_type == 'Others' && title == ''){		
		$('.title-error').html("<?php echo Yii::t('app', 'Document Name cannot be blank'); ?>");
		flag	= 1;
	}	
	if(flag == 1){
		return false;
	}
});
</script>

