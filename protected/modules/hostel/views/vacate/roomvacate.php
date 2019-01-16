<style>
.formCon input[type="text"], input[type="password"], textArea, select {padding:6px 3px 6px 3px; width:140px;}
.exp_but { right:-11px; margin:0px 2px !important;}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Hostel')=>array('/hostel'),
	Yii::t('app','Vacate'),
);
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vacate-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php
//echo '<strong>'.CHtml::label('Room No','').'</strong>&nbsp;&nbsp;';
//echo CHtml::dropDownList('BedNo','',CHtml::listData(Allotment::model()->findAll('status=:x',array(':x'=>'C')),'bed_no','bed_no'),array('prompt'=>'Select','id'=>'bed_no','submit'=>array('Vacate/create')));	
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/hostel_left');?>
    </td>
    <td valign="top"> 
        <div class="cont_right">
        <h1><?php echo Yii::t('app','Vacate Room');?></h1>
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
			$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
			if($year != $current_academic_yr->config_value and $is_insert->settings_value==0)
			{
			?>
			<div>
				<div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
					<div class="y_bx_head" style="width:95%;">
					<?php 
						echo Yii::t('app','You are not viewing the current active year. ');
						echo Yii::t('app','To vacate room, enable the Insert option in Previous Academic Year Settings.');	
					?>
					</div>
					<div class="y_bx_list" style="width:95%;">
						<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
					</div>
				</div>
			</div> <br />
			<?php
			}
			?>
            <div class="formCon">
                <div class="formConInner"> 
                    <table width="40%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="100%"><strong><?php echo Yii::t('app','Name');?></strong></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                            <div id="studentname" style="display:block;">
                                <div style="position:relative; width:180px" >
                                    <?php  
                                    $this->widget('zii.widgets.jui.CJuiAutoComplete',
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
                                    <?php echo CHtml::ajaxLink('',array('/site/explorer','widget'=>'1'),array('update'=>'#explorer_handler'),array('id'=>'explorer_student_name','class'=>'exp_but-n'));?>
                                </div>
                            </div>
                            </td>
                        </tr>
                    </table>
                <div style="padding-top:20px;"><?php echo CHtml::submitButton(Yii::t('app','Search'),array('name'=>'search','class'=>'formbut')); ?></div>
                </div> <!-- END div class="formConInner" -->
            </div> <!-- END div class="formCon" -->
    	</div> <!-- END div class="cont_right" -->
		<?php
        if(isset($list))
        {
        ?>
            
            <div class="pdtab_Con" style="padding-left:20px;">
            	<h3><?php echo Yii::t('app','Search Results');?></h3>
                <table width="95%" cellpadding="0" cellspacing="0" border="0" >
                    <tr class="pdtab-h">
                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                            { ?>
                            <td align="center"><?php echo Yii::t('app','Student name');?></td><?php } ?>
                        <td align="center"><?php echo Yii::t('app','Hostel');?></td>
                        <td align="center"><?php echo Yii::t('app','Floor');?></td>
                        <td align="center"><?php echo Yii::t('app','Room No');?></td>
                        <td align="center"><?php echo Yii::t('app','Bed');?></td>
                        <?php
						if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
						{
						?>
                        <td align="center"><?php echo Yii::t('app','Mess');?></td>
                        <?php
						}
						?>
                	</tr>
					<?php
                    if($list==NULL)
                    {
                    	echo '<tr><td align="center" colspan="6"><strong>'.Yii::t('app','Invalid search. Try again.').'</strong></td></tr>';
                    }
                    else
                    {
						foreach($list as $list_1)
						{							
							if($list_1->student_id!=NULL)
							{
								$student=Students::model()->findByAttributes(array('id'=>$list_1->student_id));
								$mess=MessFee::model()->findByAttributes(array('student_id'=>$list_1->student_id,'status'=>'C'));
								$floordetails=Floor::model()->findByAttributes(array('id'=>$list_1->floor));
								$hostel=Hosteldetails::model()->findByAttributes(array('id'=>$floordetails->hostel_id));
								$room = Room::model()->findByAttributes(array('id'=>$list_1->room_no));
							?>
							<tr>
                                                            <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                            { ?>
                                <td align="center"><?php 
                                $name='';
                                $name=  $student->studentFullName('forStudentProfile');
                                echo $name;
                               // echo $student->last_name.' '.$student->first_name; ?></td>
                            <?php } ?>
                                <td align="center"><?php echo $hostel->hostel_name;?></td>
                                <td align="center"><?php echo $floordetails->floor_no;?></td>
                                <td align="center"><?php echo ucfirst($room->room_no);?></td>
                                <td align="center"><?php echo $list_1->bed_no;?></td>
                                <?php
								if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
								{
								?>
                                <td align="center">
									<?php 
                                    if($mess->is_paid=='0')
                                    {
                                            echo Yii::t('app','Not Paid').'<br>'.CHtml::link(Yii::t('app','[Pay Now]'),array('/hostel/messManage/payfees','id'=>$list_1->student_id));
                                    }
                                    else if($mess->is_paid=='1')
                                    {
                                            echo Yii::t('app','No dues').'<br>'.CHtml::link(Yii::t('app','[Vacate]'),array('/hostel/vacate/create','id'=>$list_1->student_id));
                                    }
                                    ?>
								</td>
                                <?php
								}
								?>
							 </tr>
							
							<?php
							} // END if($list_1->student_id!=NULL)
						} // END foreach($list as $list_1)
						?>
						</table>
					</div> <!-- END div class="pdtab_Con" -->
					<?php
            		}
        } // END if(isset($list))
        ?>  

		</td>
	</tr>
</table>
<?php $this->endWidget(); ?>
