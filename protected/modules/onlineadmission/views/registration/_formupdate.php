<script type="text/javascript">
function removeFile() 
{	
	if(document.getElementById("new_file").style.display == "none"){
		document.getElementById("existing_file").style.display = "none";
		document.getElementById("new_file").style.display = "block";
		document.getElementById("new_file_field").value = "1";
	}	
	return false;
}
</script>
<?php 
$token		= isset($_GET['token'])?$_GET['token']:NULL;
$student_id	= $this->decryptToken($token);	
$time 		= time(); 

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'center-document-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	'action'=>CController::createUrl('/onlineadmission/registration/documentUpdate',array('document_id'=>$model->id,'token'=>$_REQUEST['token']))
)); 
?>
<div class="panel panel-default wiz_right">
    <div class="panel-heading" style="position:relative;">    
        <div class="col-sm-8"><h3 class="panel-title"><?php echo Yii::t('app','Document Name'); ?></h3></div>
        <div class="col-sm-4">
        	<?php echo CHtml::link(Yii::t('app','Document List'), array('registration/step3', 'token'=>$_REQUEST['token']),array('class'=>' btn btn-success pull-right')); ?>
        </div>
        <div class="clearfix"></div>        
    </div>
    <div class="panel-body">        
        <?php if($form->errorSummary($model)){ ?>
            <div class="errorSummary"><?php echo Yii::t('app','Input Error'); ?><br />
            	<span><?php echo Yii::t('app','Please fix the following error(s).'); ?></span>
            </div>
        <?php } ?>        
        
        
        
        <?php
			Yii::app()->clientScript->registerScript(
			'myHideEffect',
			'$(".error").animate({opacity: 1.0}, 3000).fadeOut("slow");',
			CClientScript::POS_READY
			);
			if(Yii::app()->user->hasFlash('errorMessage')): 
			?>
			<div class="error1" style="color:#C00; padding-left:200px; ">
			<?php echo Yii::app()->user->getFlash('errorMessage'); ?>
			</div>
			<?php
			endif;
        ?>
        
        <div class="formCon" style="clear:left;">
            <div class="formConInner" id="innerDiv">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="documentTable">
                    <tr>
                        <td width="40%"><?php echo $form->labelEx($model,Yii::t('app','Document Name')); ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>                    
                    <tr>
                        <td>                                                        
                            <?php 
								$criteria = new CDbCriteria;
								$criteria->join = 'LEFT JOIN student_document osd ON osd.title = t.id and osd.title<>'.$model->title.' and osd.student_id = '.$student_id.'';
								$criteria->addCondition('osd.title IS NULL');
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
								
								echo CHtml::activeDropDownList($model,'title', $document_arr,array('prompt' => Yii::t('app','Select Document Type'),'class'=>'form-control mb15','id'=>$time)); 
								echo $form->error($model,'title'); 
							?>
                        </td>
                        <td id="existing_file">
							<?php if($model->file!=NULL and $model->file_type!=NULL){ ?>
                                <div class="btn-demo" style="margin:10px 10px 5px;">
									<?php echo CHtml::link('<span>'.Yii::t('app','View').'</span>', array('registration/download','id'=>$model->id,'token'=>$_REQUEST['token']),array('class'=>'btn btn-primary')); ?>                                                                
                                    <?php echo CHtml::link('<span>'.Yii::t('app','Remove').'</span>', array('#'),array('class'=>'btn btn-danger','onclick'=>'return removeFile();')); ?>                                
                                </div>                            
                            <?php } ?>
                        </td>
                        <td id="new_file" style="display:none; padding-left:20px;">
							<?php echo $form->fileField($model,'file'); ?>
                            <?php echo $form->error($model,'file'); ?>
                            <?php echo $form->hiddenField($model,'new_file_field',array('value'=>0,'id'=>'new_file_field')); ?>
                        </td>
                    </tr>
                </table>                
                <div class="row" id="file_type">                
					<?php echo $form->hiddenField($model,'file_type'); ?>
                    <?php echo $form->error($model,'file_type'); ?>
                </div>                
                <div class="row" id="created_at">                
					<?php echo $form->hiddenField($model,'created_at'); ?>
                    <?php echo $form->error($model,'created_at'); ?>
                </div>
            </div>
        </div>         
    </div>
	<div class="panel-footer">                                  
        <?php echo CHtml::submitButton(Yii::t('app','Update'),array('class'=>'btn btn-orange')); ?>              
	</div>                          
</div>
<?php $this->endWidget(); ?>                          