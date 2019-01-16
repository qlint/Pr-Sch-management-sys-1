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
<style>

</style>
<?php 
	$this->renderPartial('leftside');
    $guardian = Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id'],'parent_id'=>$guardian->id,'is_active'=>'1','is_deleted'=>'0'));
	$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
	?>
<div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-male"></i><?php echo Yii::t('app','Student Profile'); ?> <span><?php echo Yii::t('app','View your profile here'); ?></span></h2>
        </div>
        
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app','courses'); ?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
<div class="contentpanel">
  <div class="col-sm-9 col-lg-12">
  	<div class="people-item">
                          <div class="media">
                            <a href="#" class="pull-left">
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
                              <h4 class="person-name"><?php echo ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);?></h4>
                              <div class="text-muted"><strong><?php echo Yii::t('app','Course :');?> </strong>
                                        <?php 
                                        $batch = Batches::model()->findByPk($student->batch_id);
                                        echo $batch->course123->course_name;
                                        ?></div>
                              <div class="text-muted"> <strong><?php echo Yii::t('app','Batch').' :';?></strong> <?php echo $batch->name;?></div>
                              <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
                              
                            </div>
                          </div>
                        </div>
                        <div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Edit document'); ?></h3>
                        </div>
                        <div class="people-item">
                        	<div>
                	<div class="edit_bttns" style="position:relative; top:-8px; right:3px; float:right;">
                        <ul>
                            <li><span>
                                <?php echo CHtml::link(Yii::t('app','Student Profile'), array('studentprofile'),array('class'=>'')); ?>
                            </span></li>
                        </ul>
                	</div> <!-- END div class="edit_bttns last" -->

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
                        
                        <p class="note" style="float:left"><?php echo Yii::t('app','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app','are required. Upload a file if it is removed'); ?></p>
                        
                        
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
                    
                        <div class="form-group" style="clear:left;">
                            <div id="innerDiv">
                            	
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
                                                <li>
                                                    <?php echo CHtml::link('<span>'.Yii::t('app','Download').'</span>', array('download','id'=>$model->id,'student_id'=>$model->student_id),array('class'=>'tt-download')); ?>
                                                </li>
                                                <li>
                                                    <?php echo CHtml::link('<span>'.Yii::t('app','Remove').'</span>', array('#'),array('class'=>'tt-delete','onclick'=>'return removeFile();')); ?>
                                                </li>
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
                        <div class="form-group">
                            <?php //echo CHtml::button('Add Another', array('class'=>'formbut','id'=>'addAnother','onclick'=>'addRow("documentTable");')); ?>
                            <?php echo CHtml::submitButton(Yii::t('app','Update'),array('id'=>'submit-btn', 'class'=>'btn btn-danger')); ?>
                        </div>
                            
                    
                    <?php $this->endWidget(); ?>
                   
                    </div>
                        </div>
  </div>
</div>
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


