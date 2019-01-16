<style type="text/css">
.title-error, .file-error{
	color:#F00 !important;
	font-size:12px;
}
</style>
<?php 
$token		= isset($_GET['token'])?$_GET['token']:NULL;
$student_id	= $this->decryptToken($token);	
?>
<div class="se_panel_formwrap">
    <div class="wiz_right">
        <div class="Block-one">
<?php        	 
			$documents = StudentDocument::model()->findAllByAttributes(array('student_id'=>$student_id)); 
			if($documents){
?>			
                <h3 class="sub_head"><?php echo Yii::t('app','Uploaded Documents'); ?></h3>    
                <div class="table-responsive">
                    <table class="table table-bordered mb30" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <thead>
                            <tr>
                                <th width="70%"><?php echo Yii::t('app', 'Document'); ?></th>
                                <th><?php echo Yii::t('app', 'Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
<?php
							foreach($documents as $document){ // Iterating the documents            
            					$studentDocumentList = StudentDocumentList::model()->findByAttributes(array('id'=>$document->title));	
?>                        
                                <tr>
                                    <td><?php echo ucfirst($studentDocumentList->name);?></td>
                                    <td width="30%">
                                        <div class="action_atg">
<?php                                         	
												
                                				echo CHtml::link(Yii::t("app", "Remove"), "#", array("submit"=>array("registration/deletes",'token'=>$this->encryptToken($student_id),'document_id'=>$document->id),'confirm'=>Yii::t('app', 'Are you sure?'), 'csrf'=>true, 'class'=>'glyphicon glyphicon-trash'));	
?>                                        
                                        </div>
                                    </td>                                
                                </tr>
<?php
							}
?>							
                        </tbody>
                    </table>
                </div>	 
<?php
			}
?>			                      
        </div>               
		<div>
<?php
        	$required	= StudentDocumentList::model()->findAllByAttributes(array('is_required'=>1, 'mandatory'=>1));
			if($required != NULL){
?>                       
                <div class="mand_fld"> 
                    <h3 class="sub_head doc_req"><?php echo Yii::t('app','These documents are required,'); ?></h3>    
                    <div class="file_name">
                        <ul> 
<?php
							foreach($required as $required_name){
?>                        
                                <li><?php echo ucfirst($required_name->name); ?></li>
<?php
							}
?>                                
                        </ul>
                    </div>
                </div>  
<?php
			}
?>                        
			<?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'center-upload-form',
                'enableAjaxValidation'=>false,
                'htmlOptions'=>array('enctype'=>'multipart/form-data'),
                
            )); ?> 					                       
                <div class="form">
                	<div class="os_panel-body os_online_mandatory">
                        <div class="row" id="innerDiv"> 
                        <div class="col-md-12"> 
                           <div class="row">                      
                            <div class="documnt_addlist">    
                                <div class="col-md-6">
									<?php echo $form->labelEx($model,Yii::t('app','Document')); ?>
                                    <?php
										$criteria 				= new CDbCriteria;
										$criteria->join 		= 'LEFT JOIN student_document osd ON osd.title = t.id and osd.student_id = '.$student_id.'';
										$criteria->condition	= 'is_required=:is_required';
										$criteria->params		= array(':is_required'=>1); 
										$criteria->addCondition('osd.title IS NULL');
										$criteria->order		= 'name ASC';
										$student_documents		= StudentDocumentList::model()->findAll($criteria);
										
										$document_arr	= array();
										if($student_documents != NULL){
											foreach($student_documents as $value){
												$document_arr[$value->id]	= html_entity_decode(ucfirst($value->name));
											}
										}
                                    ?> 
                                    <div class="choosen-box">
										<?php echo $form->dropDownList($model,'title[]', $document_arr,array('prompt' => Yii::t('app','Select Document Type'),'class'=>'form-control mb15 title-field')); ?>
                                    	<?php echo $form->error($model,'title'); ?>
                                    	<span class="title-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <br />
                                    <div class="custm_file">
                                        <label for="StudentDocument_file" class="custom-file-upload"><i class="fa fa-cloud-upload"></i> <?php echo Yii::t('app', 'Upload File'); ?></label>
                                        <span class="clearfix" ></span>                                         
										<?php echo $form->fileField($model,'file[]', array('class'=>'upload_file')); ?>
                                        <?php echo $form->error($model,'file'); ?>
                                        <span class="file-error"></span>
                                        <p style="font-size:11px;"><?php echo '('.Yii::t('app','Only files with these extensions are allowed: jpg, png, pdf, doc, txt.').')'; ?></p>
                                   		
                                    </div>
                                </div>
                            </div>    
                        </div>     
 					 </div>  </div> 
                     	<input type="hidden" id="count-value" />
                        <div class="row" style="padding-top:20px;">
                            <?php echo $form->hiddenField($model,'document',array('value'=>1)); ?>
                            <?php echo $form->error($model,'document'); ?>    
                        </div>			                    
                        <div class="row" id="file_type">                
                            <?php echo $form->hiddenField($model,'file_type[]'); ?>
                            <?php echo $form->error($model,'file_type'); ?>
                        </div>        
                        <div class="row" id="created_at">                
                            <?php echo $form->hiddenField($model,'created_at[]'); ?>
                            <?php echo $form->error($model,'created_at'); ?>
                        </div>
     
                        <div>
                        <?php     
							echo CHtml::ajaxLink(Yii::t('app','Add Another'),array('addrow','id'=>$student_id), 
								array(								
									'type'=>'POST',									
									'data'=>array('count'=>'js:$("#count-value").val()', Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
									'dataType'=>'json',
									'success'=>'function(html){ jQuery("#innerDiv").append(html);
									}'
								), 
								array('class'=>'btn btn-primary', 'id'=>'add-another-btn')
							); 
						?>
                        
                        <?php echo CHtml::submitButton(Yii::t('app','Save'),array('class'=>'btn btn-success', 'id'=>'save-btn')); ?>
                        <?php							
							$count_required = count($required);
							$id 			= array();
							$uploadCount 	= 0; 
							foreach($required as $require){
								$uploads = StudentDocument::model()->findByAttributes(array('student_id'=>$student_id,'title'=>$require->id));
								if($uploads){
									$uploadCount++;
								}
							}
							
							if($count_required == $uploadCount){ 
								echo CHtml::link(Yii::t('app','Finish'),array('registration/finish','token'=>$this->encryptToken($student_id)),array('class'=>'btn btn-orange', 'confirm' => Yii::t('app', 'Are you sure, do you want to complete the registration procees?'))); 
							}
                        ?>
                        </div>    	
					</div>
				</div>
			<?php $this->endWidget(); ?>
		</div>
    </div>
</div>
<script type="text/javascript">
$('.upload_file').change(function(ev){
	var name	= $(this)[0].files[0].name;		
	$(this).closest('.custm_file').find('.clearfix').html(name);	
});   

$('#add-another-btn').click(function(ev){
	var count	= $('#count-value').val();
	if(count != ''){
		var current_count	= parseInt(count) + parseInt(1);
		$('#count-value').val(current_count);	
	}
	else{
		$('#count-value').val(1);		
	}
});

$('#save-btn').click(function(ev){
	var flag	= 0;
	$('.title-error').html('');
	$('.file-error').html('');
	var extenstion_arr	= ['jpg', 'jpeg','png', 'pdf', 'doc', 'txt'];
	$('.documnt_addlist').each(function(){
		var title	=  $(this).find('.title-field').val();
		var file	=  $(this).find('.upload_file').val();
		if(title != '' || file != ''){	
			if(title == ''){				
				$(this).find('.title-error').html("<?php echo Yii::t('app', 'Document cannot be blank'); ?>");
				flag	= 1;
			}
			if(file == ''){				
				$(this).find('.file-error').html("<?php echo Yii::t('app', 'File cannot be blank'); ?>");
				flag	= 1;
			}
		}		
	});
	if(flag == 1){
		return false;
	}
});
</script>