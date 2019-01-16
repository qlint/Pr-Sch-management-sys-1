<style type="text/css">
.submit-btn{	
    margin-left: 6px;
}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Settings')=>array('/configurations'),
	Yii::t('app','Online Registration Settings'),
);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    	<td width="247" valign="top">
            <div id="othleft-sidebar">
            	<?php $this->renderPartial('//configurations/left_side');?>
            </div>
        </td>
        <td valign="top">
        	<div class="cont_right formWrapper">  
				<h1><?php echo Yii::t('app','Online Registration Settings');?></h1>
                <?php
				Yii::app()->clientScript->registerScript(
				'myHideEffect',
				'$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',
				CClientScript::POS_READY
				);
				?>
				<?php
				/* Success Message */
				if(Yii::app()->user->hasFlash('successMessage')): 
				?>
					<div class="flashMessage" style="background:#FFF; color:#689569; padding-left:220px; font-size:13px">
					<?php echo Yii::app()->user->getFlash('successMessage'); ?>
					</div>
				<?php endif; ?>
                
                <?php 
					$start_admission = OnlineRegisterSettings::model()->findByAttributes(array('id'=>3));
					if($start_admission->config_value == NULL){
				?>	 
						<div class="yellow_box_notice">
							<?php echo Yii::t('app','Note : Admission Number needs to be set in').' '.CHtml::link(Yii::t('app','School Setup'),array('/configurations/create')); ?>
						</div>
				<?php } ?> 
<?php             	
				$form=$this->beginWidget('CActiveForm', array(
					'id'=>'onlineRegSettings-form',				
				)); 
?>		
					<div class="formCon">
						<div class="formConInner">	
                        	<div class="text-fild-bg-block">
                            	<div class="text-fild-block inputstyle">
                                	<?php 
										echo $form->labelEx($model,'academic_year', array('class'=>'opnsl_label')); 
										
										$academic_year = AcademicYears::model()->findAll(array('condition'=>'is_deleted=:is_deleted','params'=>array(':is_deleted'=>0)));
										 $val_yr = OnlineRegisterSettings::model()->findByPk(2);
										 
										 echo $form->dropDownList($model,'academic_year',CHtml::listData($academic_year,'id','name'),array('empty'=>Yii::t('app','Select Year'),'options'=>array($val_yr->config_value => array('selected'=>true))));
										 
										 echo $form->error($model,'academic_year');
									?>                                    
                                </div>
                                <div class="text-fild-block inputstyle">
                                	<?php
										echo $form->labelEx($model,'show_link', array('class'=>'opnsl_label'));
										
										$show_link = OnlineRegisterSettings::model()->findByPk(4); 
										if($show_link->config_value == 1){
											$model->show_link = 1;					
										}
										else{
											$model->show_link = 0;
										}
										
										echo '<div>'.$form->checkBox($model,'show_link').'</div>';
									?>                                                                     
                                </div>
                            </div> 
                            <div class="submit-btn">
                            	<?php echo CHtml::submitButton(Yii::t('app','Apply'),array('class'=>'formbut','name'=>'submit')); ?>                          
                            </div>        
                        </div>
                    </div>                        
                <?php $this->endWidget(); ?>
            </div>    
        </td>
    </tr>
</table>        