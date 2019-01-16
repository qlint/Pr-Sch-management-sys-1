<style>
	.formCon input[type="text"], input[type="password"], textArea, select {padding:6px 3px 6px 3px; width:140px;}
	.exp_but { right:-11px; margin:0px 2px !important;}
</style>

<?php
$this->breadcrumbs=array(
Yii::t('app','Hostel')=>array('/hostel'),
Yii::t('app','Change Room'),
);?>
<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'student-form',
'enableAjaxValidation'=>false,
)); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        <?php $this->renderPartial('/settings/hostel_left');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Change Room');?></h1>
                
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
							echo Yii::t('app','To change room, enable the Edit option in Previous Academic Year Settings.');	
						?>
						</div>
						<div class="y_bx_list" style="width:95%;">
							<h1><?php echo CHtml::link(YYii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
						</div>
					</div>
				</div> <br />
				<?php
				}
				?>
                
                
                <div class="formCon">
                    <div class="formConInner">
                    <div onclick="hide('studentname')" style="cursor:pointer;"></div>
                        <div id="studentname" style="display:block;">
                            <div style="position:relative; width:180px;">
                            <?php  $this->widget('zii.widgets.jui.CJuiAutoComplete',
                                    array(
                                      'name'=>'name',
                                      'id'=>'name_widget',
                                      'source'=>$this->createUrl('/site/autocomplete'),
                                      'htmlOptions'=>array('placeholder'=>Yii::t('app','Student Name')),
                                      'options'=>
                                         array(
                                               'showAnim'=>'fold',
                                               'select'=>"js:function(student, ui) {
                                                  $('#id_widget').val(ui.item.id);
                                                 
                                                         }"
                                                ),
                                
                                    ));
                                     ?>
                            <?php echo CHtml::hiddenField('student_id','',array('id'=>'id_widget')); ?>
                            <?php echo CHtml::ajaxLink('',array('/site/explorer','widget'=>'1'),array('update'=>'#explorer_handler'),array('id'=>'explorer_student_name','class'=>'exp_but-n'));?></div>
                        <?php echo '<br/><br/>'. CHtml::submitButton(Yii::t('app','Search'),array('name'=>'search','class'=>'formbut')); ?>   
                        </div>
                    </div> <!-- END div class="formConInner" -->
                </div> <!-- END div class="formCon" -->
                
                
                
                <?php
                if(isset($list))
                {
                
                ?>
                <div class="pdtab_Con" style="padding:0px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr class="pdtab-h">
                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                         { ?>
                        <td align="center"><?php echo Yii::t('app','Student Name');?></td>
                         <?php } ?>
                        <td align="center"><?php echo Yii::t('app','Hostel');?></td>
                        <td align="center"><?php echo Yii::t('app','Floor');?></td>
                        <td align="center"><?php echo Yii::t('app','Room No');?></td>
                        <td align="center"><?php echo Yii::t('app','Bed');?></td>
                        <?php
                        if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
						{
						?>
                        <td align="center"><?php echo Yii::t('app','Action');?></td>
                        <?php
                        }
                        ?>
                    </tr>
                <?php
                    if($list==NULL)
                    {
                        echo '<tr><td align="center" colspan="6"><strong>'.Yii::t('app','No data available!').'</strong></td></tr>';
                    }
                    else					
                    {
						foreach($list as $list_1)
						{
							if($list_1->student_id!=NULL)
							{
								
								$student=Students::model()->findByAttributes(array('id'=>$list_1->student_id));
								//var_dump($student->attributes);
								//echo $list_1->floor;
								$floordetails=Floor::model()->findByAttributes(array('id'=>$list_1->floor));
								//var_dump($floordetails->attributes);
								$hostel=Hosteldetails::model()->findByAttributes(array('id'=>$floordetails->hostel_id));
			                      //var_dump($hostel->attributes);
								  
								  $room = Room::model()->findByAttributes(array('id'=>$list_1->room_no));
                	?>
                                <tr>
                                    <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                    { ?>
                                <td align="center">
                                    <?php
                                    if($student!=NULL)
                                    {
                                        $name='';
                                        $name=  $student->studentFullName('forStudentProfile');
                                        echo $name;
                                        //echo $student->last_name.' '.$student->first_name;
                                    }
                                
                                    ?>
                                </td>
                                    <?php } ?>
                                <td align="center"><?php echo $hostel->hostel_name;?></td>
                                <td align="center"><?php echo $floordetails->floor_no;?></td>
                                <td align="center"><?php echo $room->room_no;?></td>
                                <td align="center"><?php echo $list_1->bed_no;?></td>
                                <?php
								if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
								{
								?>
                                <td align="center"><?php echo CHtml::link(Yii::t('app','Change Room'),array('/hostel/room/change','id'=>$list_1->student_id));?></td>
                                <?php
								}
								?>
                                </tr>
                         <?php
                                }
                            }
                        ?>
                 </table>
                 
                 </div>
                <?php
                }
                }
                
                ?>    
            </div> <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>

<?php $this->endWidget(); ?>