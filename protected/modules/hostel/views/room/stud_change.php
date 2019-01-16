<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-form',
	'enableAjaxValidation'=>false,
	)); ?>

<div class="pageheader">
  <div class="col-lg-8">
    <h2><i class="fa fa-group"></i><?php echo Yii::t('app','Hostel');?><span><?php echo Yii::t('app','View hostel');?> </span></h2>
  </div>
  <div class="col-lg-2"> </div>
  <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app','You are here').':';?></span>
    <ol class="breadcrumb">
      <li class="active"><?php echo Yii::t('app','hostel');?></li>
    </ol>
  </div>
  <div class="clearfix"></div>
</div>
<?php $this->renderPartial('/settings/studentleft');?>
<div class="contentpanel">
  <div class="col-sm-9 col-lg-12">
    <div class="panel-heading"> 
      <!-- panel-btns -->
      <h3 class="panel-title"><?php echo Yii::t('app','Change Room'); ?></h3>
    </div>
    <div id="parent_Sect">
      <div id="parent_rightSect">
        <div class="people-item">
          <div class="profile_details">
            <div id="studentname" style="display:block;">
              <div class="form-group">
              <div class="col-sm-2 col-4-reqst"> <?php
				$hosteldetails=Hosteldetails::model()->findAllByAttributes(array('is_deleted'=>0));
				$hostellist = CHtml::listData($hosteldetails,'id', 'hostel_name');
				echo '<strong>'.CHtml::label(Yii::t('app','Select Hostel'),array('class'=>'control-label'),'').'</strong>';
				?> </div>
              <div class="col-sm-3 col-4-reqst"> 
			  <?php
				echo CHtml::dropDownList('hostel','', $hostellist, array('empty' => 'Select a hostel','class'=>'form-control ',
				'ajax' => array(
                'type'=>'POST',
                'url'=>CController::createUrl('/hostel/room/floorlist'),
                'update'=>'#floor',
                'data'=>'js:{hostel:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}'
                ),'options'=>array($_REQUEST['id']=>array('selected'=>true))));
                ?>
                
				
                
                 </div>
              </div>
              
              <div class="form-group">
              <div class="col-sm-2 col-4-reqst"><?php echo '<strong>'.CHtml::label(Yii::t('app','Select Floor'),array('class'=>'control-label'),'').'</strong>';?> </div>
              <div class="col-sm-3 col-4-reqst">  
			  <?php echo CHtml::dropDownList('floor','','',array('prompt'=>Yii::t('app','Select'),'class'=>'form-control ')); ?>
              </div>
              </div>
              <div class="form-group">
                <div class="col-sm-2 col-4-reqst"> </div>
                <div class="col-sm-3 col-4-reqst"> <?php echo CHtml::submitButton( Yii::t('app','Search'),array('name'=>'search','class'=>'btn btn-primary')); ?></div>
              </div>
            </div>
            <!-- END div id="studentname" -->
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
            <br />
            <div class="table-responsive">
              <table class="table table-bordered mb30">
                <thead>
                  <tr >
                    <th ><?php echo Yii::t('app','Floor');?></th>
                    <th ><?php echo Yii::t('app','Room No');?></th>
                    <th ><?php echo Yii::t('app','Bed');?></th>
                    <th ><?php echo Yii::t('app','Action');?></th>
                  </tr>
                </thead>
                <?php
							
							foreach($list as $list_1)
							{
								
								//$floordetails=Floor::model()->findByPk($list_1->floor);
								$floordetails=Floor::model()->findByAttributes(array('id'=>$list_1->floor));
								$allot=Allotment::model()->findAll('room_no=:x and status=:y',array(':x'=>$list_1->id,':y'=>'C'));
								if($allot!=NULL)
								{
									foreach ($allot as $allot_1)
									{
									?>
                <tr>
                  <td ><?php echo $floordetails->floor_no;?></td>
                  <?php /*?><?php
     				$roomname=Room::model()->findByPk($allot_1->room_no);
      			  ?>
                  <td><?php echo $roomname->room_no;?></td><?php */?>
                  <?php /*?><td ><?php echo $allot_1->room_no;?></td><?php */?>
                  <td align="center"><?php echo $list_1->room_no;?></td>
                  <td ><?php echo $allot_1->bed_no;?></td>
                  <td ><?php echo CHtml::link(Yii::t('app','Request'),array('/hostel/room/roomrequest','studentid'=>$_REQUEST['id'],'allotid'=>$allot_1->id,'floor'=>$floordetails->id),array('confirm'=>Yii::t('app','Are you sure you want to request room change?')));?></td>
                </tr>
                <?php
									}
								}
											
								?>
                <?php						
							}
							?>
              </table>
            </div>
            <?php
						}
							
					} // END if(isset($list))
					?>
          </div>
          <!-- END div class="profile_details" --> 
        </div>
        <!-- END div class="parentright_innercon" -->
        
        <div class="clear"></div>
      </div>
      <!-- END div id="parent_rightSect" -->
      <div class="clear"></div>
    </div>
  </div>
</div>
<!-- END div id="parent_Sect" -->
<?php $this->endWidget(); ?>
