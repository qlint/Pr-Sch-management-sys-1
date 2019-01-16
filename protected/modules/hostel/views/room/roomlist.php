 

<style>

.formConInner{width:auto;}

</style>

 

 

<?php

$this->breadcrumbs=array(

        Yii::t('app','Hostel')=>array('/hostel'),	

	Yii::t('app','Allot Room'),

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

                <h1><?php echo Yii::t('app','Allot Room');?></h1>

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

							echo Yii::t('app','To allot room, enable the Insert option in Previous Academic Year Settings.');	

						?>

						</div>

						<div class="y_bx_list" style="width:95%;">

							<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>

						</div>

					</div>

				</div>

				<?php

				}

				elseif(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))

				{

				?>

                <div class="formCon">

                    <div class="formConInner">

						<?php

                         if(isset($_REQUEST['id']) && (isset($_REQUEST['floor_id'])))

                        {

							$floordetails=Floor::model()->findByAttributes(array('id'=>$_REQUEST['floor_id']));														

							$room=Room::model()->findAllByAttributes(array('floor'=>$_REQUEST['floor_id']));

							?>

						

							<div class="pdtab_Con" style="padding-top:0px;">

                                <table width="100%" cellpadding="0" cellspacing="0" border="0" >

                                    <tr class="pdtab-h">

                                        <td align="center"><?php echo Yii::t('app','Floor');?></td>

                                        <td align="center"><?php echo Yii::t('app','Room No');?></td>

                                        <td align="center"><?php echo Yii::t('app','Bed');?></td>

                                        <td align="center"><?php echo Yii::t('app','Action');?></td>                                    

									</tr>

                                    <?php

                                    if($room!=NULL)

                                    {
										foreach($room as $room_1)

										{

											$list=Allotment::model()->findAllByAttributes(array('room_no'=>$room_1->id,'status'=>'C'));
											
											//$room_2 = Room::model()->findByAttributes(array(''=>));
											 if($list!=NULL)

                                   			 { 

											foreach($list as $list_1)

											{

											?>

                                                <tr>

                                                    <td align="center"><?php echo $floordetails->floor_no;

                                                    ?></td>

                                                    <td align="center"><?php echo $room_1->room_no;?></td>

                                                    <td align="center"><?php echo $list_1->bed_no;?></td>

                                                    <td align="center"><?php echo CHtml::link(Yii::t('app','Allot'),array('/hostel/allotment/create','studentid'=>$_REQUEST['id'],'allotid'=>$list_1->id,'floor_id'=>$_REQUEST['floor_id']));?></td>

                                                </tr>

											<?php

											}
										}
										 else

											{
		
												echo '<tr><td colspan = "4" align="center">'.Yii::t('app','Sorry! The requested room is not available').'</td></tr>';
		
											}
										
										}

                                    }

                                    else

                                    {

                                    	echo '<tr><td colspan = "4" align="center">'.Yii::t('app','Sorry! The requested room is not available').'</td></tr>';

                                    }

                                    ?>

                                </table>

                        	</div> <!-- END div class="pdtab_Con" -->

                        <?php

                        } // if(isset($_REQUEST['id']) && (isset($_REQUEST['floor_id'])))

                        ?>

                        

                    </div> <!-- END div class="formConInner" -->

                </div> <!-- END div class="formCon" -->

                <?php

				}

				?>

            </div> <!-- END div class="cont_right" -->

        </td>

    </tr>

</table>

	

<?php $this->endWidget(); ?>