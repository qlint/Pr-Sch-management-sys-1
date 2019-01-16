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
	 <?php $this->renderPartial('leftside');?> 
        <?php
		$employee=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$employee_id = $employee->id;
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
		?>
<div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-user"></i><?php echo Yii::t('app','Profile');?><span><?php echo Yii::t('app', 'View your profile here');?> </span></h2>
        </div>
        <div class="col-lg-2">
        
                </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app', 'Profile');?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>    
<div class="contentpanel">
  <div class="col-sm-9 col-lg-12">
    <div class="people-item">
              		<div class="media">
                      
                   <div class="prof_img">  
                    <a href="#" class="pull-left">
                     <?php
                     if($employee->photo_file_name!=NULL)
                     {
						 $path = Employees::model()->getProfileImagePath($employee->id);	 
                        echo '<img class=" thumbnail" src="'.$path.'" alt="'.$employee->photo_file_name.'" width="100" height="103" />';
                    }
                    elseif($employee->gender=='M')
                    {
                        echo '<img class="thumbnail media-object"  src="images/portal/prof-img_male.png" alt='.Employees::model()->getTeachername($employee->id).' width="100" height="103" />'; 
                    }
                    elseif($employee->gender=='F')
                    {
                        echo '<img class="thumbnail media-object"  src="images/portal/prof-img_female.png" alt='.Employees::model()->getTeachername($employee->id).' width="100" height="103" />';
                    }
                    ?>                           
                            </a>
                             <a href="javascript:void(0)" id="std_image" data-url=""><div class="upload"></div></a><div id="displayPercentage" style="position:absolute;
                    top:30px; left:30px"><div class="loading_app" ></div><div id="percentage" style="color:#FFF !important; font-size:14px; text-shadow:0px 0px 2px #000; color:#fff"></div></div>
                  </div>  
                   
                            <div class="media-body">
                              <h4 class="person-name"><?php echo Employees::model()->getTeachername($employee->id);?></h4>
                              <div class="text-muted"><strong><?php echo Yii::t('app','Job Title :');?></strong>
                                        <?php 
					//$posts=Batches::model()->findByPk($employee->job_title);
					echo $employee->job_title;
					?></div>
                              <div class="text-muted"> <strong><?php echo Yii::t('app','Department').' :';?></strong> <?php $department = EmployeeDepartments::model()->findByAttributes(array('id'=>$employee->employee_department_id));
		 				echo $department->name;?></div>
                              <div class="text-muted"><strong><?php echo Yii::t('app','Teacher No').' :';?></strong> <?php echo $employee->employee_number; ?></div>
                             
                              
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
                                <?php echo CHtml::link(Yii::t('app','Teacher Profile'), array('profile'),array('class'=>'')); ?>
                           </span> </li>
                        </ul>
                	</div> 
                    </div>
                    <!-- END div class="edit_bttns last" -->

					<?php $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'student-document-form',
                        'enableAjaxValidation'=>false,
                        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
                        'action'=>$this->createUrl('/teachersportal/default/documentupdate',array('document_id'=>$model->id))
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
                                        <td><?php echo $form->labelEx($model,Yii::t('app', 'Document Name'),array('style'=>'float:left;')); ?><span class="required">&nbsp;*</span></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;<?php //echo $form->labelEx($model,Yii::t('students','file')); ?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
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
                                                    <?php echo CHtml::link(Yii::t('app', 'View'), array('download','id'=>$model->id,'employee_id'=>$model->employee_id),array('class'=>'tt-download')); ?>
                                               </span> </li>
                                                <li><span>
                                                    <?php echo CHtml::link(Yii::t('app', 'Remove'), array('#'),array('class'=>'tt-delete','onclick'=>'return removeFile();')); ?>
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
                                </table>
                                
                                <div class="row" id="employee_id">
                                    <?php echo $form->hiddenField($model,'employee_id',array('value'=>$model->employee_id)); ?>
                                    <?php echo $form->error($model,'employee_id'); ?>
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
                            <?php echo CHtml::submitButton(Yii::t('app','Update'),array('class'=>'btn btn-danger')); ?>
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


