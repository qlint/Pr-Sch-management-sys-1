<?php
$this->breadcrumbs=array(
        Yii::t('app','Hostel')=>array('/hostel'),	
	Yii::t('app','Mess Change'),
);
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'registration-form',
	'enableAjaxValidation'=>false,
)); ?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        <?php $this->renderPartial('/settings/hostel_left');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Mess Manage');?></h1>
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
							echo Yii::t('app','To change the food preference, enable the Edit option in Previous Academic Year Settings.');	
						?>
						</div>
						<div class="y_bx_list" style="width:95%;">
							<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
						</div>
					</div>
				</div> <br />
				<?php
				}
				elseif(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
				{
				?>
                <div class="formCon">
                    <div class="formConInner"> 
						<?php
                        $student_id = $_REQUEST['id'];
                        $stud = Students::model()->findByAttributes(array('id'=>$_REQUEST['id'])); 
                        
                        $name='';
                        if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                        {                                                            
                            $name=  $stud->studentFullName('forStudentProfile');
                        }
                        //$name = $stud->first_name.' '.$stud->last_name;
                        $register = Registration::model()->findByAttributes(array('student_id'=>$_REQUEST['id']));
                        $rid = $register->id; 
                        $food = $register->food_preference;
                        $foodinfo = FoodInfo::model()->findByAttributes(array('id'=>$food));
                        $food_preference = $foodinfo->food_preference;
                        ?>
                        <table width="50%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><?php echo '<strong>'.Yii::t('app','Name').'</strong>'; ?></td>
                                <td>&nbsp;</td>
                                <td><input type="text" name="name" value="<?php echo $name;?>" readonly="readonly"></td>
                                <td><input type="text" name="id" value="<?php echo $student_id;?>" hidden="hidden"></td>
                            </tr>
                            <tr>
                            	<td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                <?php  
                                echo '<strong>'.Yii::t('app','Food Preference').'</strong>';
								?>
                                </td>
                                <td>&nbsp;</td>
                                <td width="3%">
									<?php										
										$food_list = FoodInfo::model()->findAllByAttributes(array('is_deleted'=>0));
										echo CHtml::dropDownList('food_preference','',CHtml::listData($food_list,'id','food_preference'),array('options' => array($register->food_preference=>array('selected'=>true)),'id'=>'food_preference','style'=>'width:146px'));?>
								</td>
                            </tr>
                        </table>
                    </div> <!-- END div class="formConInner" -->
                </div> <!-- END div class="formCon" -->
                <div style="padding-left:20px">
				<?php echo CHtml::button(Yii::t('app','Save'), array('submit' => array('Registration/Messchange','id'=>$rid),'class'=>'formbut')); ?>
                </div>
                <?php
				}
				?>
            </div> <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>

</div><!-- form -->

