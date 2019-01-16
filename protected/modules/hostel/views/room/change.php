<?php
$this->breadcrumbs=array(
Yii::t('app','Hostel')=>array('/hostel'),
Yii::t('app','Allot Room')=>array('/hostel/registration/create'),
Yii::t('app','Change'),
);
?>
<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'student-form',
		'enableAjaxValidation'=>false,
		)); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top"><?php $this->renderPartial('/settings/hostel_left');?></td>
    <td valign="top"><div class="cont_right">
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
        <br />
        <?php
						}
						?>
        <div class="formCon">
          <div class="formConInner">
            <div id="studentname" style="display:block;">
              <table width="30%" border="0" cellspacing="0" cellpadding="0">
              
              <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','Select Hostel')); ?></td>
    <td>&nbsp;</td>
    <td>
	
	<?php  echo CHtml::dropDownList('hostel',$hostel,CHtml::listData(Hosteldetails::model()->findAll('is_deleted=:x',array(':x'=>'0')),'id','hostel_name'),array('prompt'=>Yii::t('app','Select'),
'ajax' => array(
	'type'=>'POST',
	'url'=>CController::createUrl('/hostel/room/allot'),
	'update'=>'#floorid',
	//'data'=>'js:$(this).serialize()'
	'data'=>'js:{hostel:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
	)));?>
    
		</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,Yii::t('app','Select Floor')); ?></td>
    <td>&nbsp;</td>
    <td><?php if(isset($floor) and $floor!=NULL)
	{
		
			$criteria = new CDbCriteria;
			$criteria->condition = "hostel_id=:x";
			$criteria->params = array(':x'=>$hostel);
			$data=Floor::model()->findAll($criteria);
	
		
			$data=CHtml::listData($data,'id','floor_no');
			echo CHtml::dropDownList('floor',$floor,$data,array('prompt'=>Yii::t('app','Select Floor'),'id'=>'floorid'));
	}else
	{

	echo CHtml::dropDownList('floor','',array(),array('prompt'=>Yii::t('app','Select'),'id'=>'floorid','ajax' => array(
	'type'=>'POST',
	'url'=>CController::createUrl('/hostel/room/room'),
	'update'=>'#roomid',
	'data'=>'js:$(this).serialize()')));
	}?>
		</td>
  </tr>
           <?php /*?><tr>
                  <td valign="middle"><?php echo '<strong>'.CHtml::label(Yii::t('hostel','Select Floor'),'').'</strong>';?></td>
                  <td>&nbsp;</td>
                  <td valign="top"><?php echo CHtml::activeDropDownList($model,'floor',CHtml::listData(Floor::model()->findAll(),'id','floor'),array('prompt'=>'Select')); ?></td>
                </tr><?php */?>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><?php echo CHtml::submitButton( Yii::t('app','Search'),array('name'=>'search','class'=>'formbut')); ?></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <!-- END div class="formCon" -->
        <?php
						if(isset($list))
						{
						
							if($list==NULL)
							{
								echo '<div align="center"><strong>'.Yii::t('app','No data available!').'</strong></div>';
							}
							else
							{
							?>
        <div class="pdtab_Con" style="padding-top:0px;">
          <table width="100%" cellpadding="0" cellspacing="0" border="0" >
            <tr class="pdtab-h"> `
              <td align="center"><?php echo Yii::t('app','Hostel');?></td>
              <td align="center"><?php echo Yii::t('app','Floor');?></td>
              <td align="center"><?php echo Yii::t('app','Room No');?></td>
              <td align="center"><?php echo Yii::t('app','Bed');?></td>
              <?php
											if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
											{
											?>
              <td align="center"><?php echo Yii::t('app','Action');?></td>
              <?php
											}
											?>
            </tr>
            <?php
			//var_dump($list_1);exit;
										foreach($list as $list_1)
										{
											$floordetails=Floor::model()->findByAttributes(array('id'=>$list_1->floor));
											$hostel = HostelDetails::model()->findByAttributes(array('id'=>$floordetails->hostel_id));
											$allot=Allotment::model()->findAll('room_no=:x and status=:y',array(':x'=>$list_1->id,':y'=>'C'));
											if($allot!=NULL)
											{
												foreach ($allot as $allot_1)
												{
													?>
            <tr>
              <td align="center"><?php echo $hostel->hostel_name; ?></td>
              <td align="center"><?php echo $floordetails->floor_no;
														?></td>
              <td align="center"><?php echo $list_1->room_no;?></td>
              <td align="center"><?php echo $allot_1->bed_no;?></td>
              <?php
                                                        if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
														{
														?>
              <td align="center"><?php echo CHtml::link(Yii::t('app','Allot'),array('/hostel/allotment/create','studentid'=>$_REQUEST['id'],'allotid'=>$allot_1->id,'floor_id'=>$floordetails->id));?></td>
              <?php
														}
														?>
            </tr>
            <?php
												}
											}
											//echo count($allot);
											//	exit;
											//	$room=Room::model()->findByAttributes(array('id'=>$list_1->room_no));
											
											
														
											?>
            <?php
													
											
										}
										?>
          </table>
        </div>
        <!-- END div class="pdtab_Con" -->
        <?php
							}
						}
						
						?>
      </div>
      <!-- END div class="cont_right" --></td>
  </tr>
</table>
<?php $this->endWidget(); ?>
