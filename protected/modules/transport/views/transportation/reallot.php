<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Search Students')=>array('/transport/transportation/studentsearch'),
	Yii::t('app','Reallot'),
);?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
	'enableAjaxValidation'=>false,
)); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/transportation/trans_left');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Reallot Transportation');?></h1>
				<?php
                $current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
                if(Yii::app()->user->year)
                {
                	$year = Yii::app()->user->year;
                }
                else
                {
                	$year = $current_academic_yr->config_value;
                }
                $is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
                if($year != $current_academic_yr->config_value and $is_edit->settings_value==0)
				{
                ?>
                <div>
                <div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
                    <div class="y_bx_head" style="width:95%;">
                    <?php 
                        echo Yii::t('app','You are not viewing the current active year. ');
                        echo Yii::t('app','To edit or reallot transportation, enable the Edit option in Previous Academic Year Settings.');	
                    ?>
                    </div>
                    <div class="y_bx_list" style="width:95%;">
                        <h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
                    </div>
                </div>
                </div> <br />
                <?php
                }
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
				{
                ?>    
                <div class="formCon">
                    <div class="formConInner">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" class="s_search">
                            <tr>
                                <td width="24%"><strong><?php echo Yii::t('app','Name');?></strong></td>
                                <td width="2%">&nbsp;</td>
                                <td width="11%"><strong><?php echo Yii::t('app','Route');?></strong></td>
                                <td width="3%">&nbsp;</td>
                                <td width="55%"><strong><?php echo Yii::t('app','Stop');?></strong></td>
                            </tr>
                            <tr>
                                <td> 
									<?php 
									
										//echo $form->textField($model,'student_id',array('size'=>20)); 
										$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
										echo $form->textField($model,'student_id',array('size'=>20,'maxlength'=>255,'value'=>$student->studentFullName('forStudentProfile'),'disabled'=>true,)); 
                        				echo $form->hiddenField($model,'student_id',array('value'=>$model->student_id)); 
									?>
                                </td>
                                <td>&nbsp;</td>
                               <td>
			<?php
            
            
             //if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
                        {
			           
							  echo CHtml::dropDownList('route','',CHtml::listData(RouteDetails::model()->findAll(),'id','route_name'),array('required'=>'required','prompt'=>   Yii::t('app','Select Route '),
			 'ajax' => array(
			'type'=>'POST',
			'url'=>CController::createUrl('/transport/transportation/routes'),
			'update'=>'#stop_id',
			'data'=>'js:{route:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',)));
						}
			?>
                
            </td>
                                <td>&nbsp;</td>
                                <td> 
								<?php echo CHtml::activeDropDownList($model,'stop_id',array(),array('required'=>'required','prompt'=>Yii::t('app','Select Stop'),'id'=>'stop_id')); ?>
                      
                                </td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </div> <!-- END div class="formConInner" -->
                </div> <!-- END div class="formCon" -->
                <div class="row buttons">
					<?php echo CHtml::submitButton(Yii::t('app', 'Reallot'),array('name'=>'save','class'=>'formbut')); ?>
                </div>
                <?php
				}
				?>
            </div> <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>
