<style>
.overviewbox{
	width:226px;
}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Hostel'),
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">

    <?php $this->renderPartial('/settings/hostel_left');?>

    </td>
    <td valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="75%">
        <?php
		$vacant=Allotment::model()->findAll('status=:x',array(':x'=>'C'));
		$allot=RoomRequest::model()->findAll('status=:x',array(':x'=>'C'));
		$mess=MessFee::model()->findAll('is_paid=:x',array(':x'=>0));
		?>
        <div class="cont_right">
<h1><?php echo Yii::t('app','Hostel Dashboard');?></h1>
<div class="overview" style="padding-top:0px;">
	<div class="overviewbox ovbox1" style="margin-left:0px;">
    	<h1><strong><?php echo Yii::t('app','Vacant Beds');?></strong></h1>
        <div class="ovrBtm"><?php echo count($vacant);?></div>
    </div>
    <div class="overviewbox ovbox2">
    	<h1><strong><?php echo Yii::t('app','Room Requests');?></strong></h1>
        <div class="ovrBtm"><?php echo count($allot);?></div>
    </div>
    <div class="overviewbox ovbox3">
    	<h1><strong><?php echo Yii::t('app','Mess Dues');?></strong></h1>
        <div class="ovrBtm"><?php echo count($mess);?></div>
    </div>
  <div class="clear"></div>

</div>
<div class="pdtab_Con">
	<div style="font-size:13px; padding:5px 0px"><strong><?php echo Yii::t('app','Available Rooms');?></strong></div>
<div class="table-responsive">
    <table class="table table-bordered mb30" width="100%" cellspacing="0" cellpadding="0">
    <thead>
 <tr>
            <th><?php echo Yii::t('app','Hostel');?></th>
            <th><?php echo Yii::t('app','Floor');?></th>
            <th><?php echo Yii::t('app','Room No');?></th>
            <th><?php echo Yii::t('app','Availability');?></th>
        </tr>
        </thead>
        <?php
		$criteria=new CDbCriteria;
		$criteria->order = 'id DESC';
		// $criteria->condition='status = :match4 group by room_no';
		$criteria->condition='status = :match4 group by room_no, project1.t.id';
		$criteria->params[':match4'] = 'C';
		$total = Allotment::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria);  // the trick is here!
		$list = Allotment::model()->findAll($criteria);
		$page_size=Yii::app()->params['listPerPage'];
		//$list=Allotment::model()->findAll($criteria);
		if($list!=NULL)
		{
			foreach($list as $list_1)
			{
				$room=Room::model()->findByAttributes(array('id'=>$list_1->room_no));
				$allot=Allotment::model()->findAllByAttributes(array('room_no'=>$list_1->room_no));
				$floor=Floor::model()->findByAttributes(array('id'=>$room->floor));
				$hostel=Hosteldetails::model()->findByAttributes(array('id'=>$floor->hostel_id));

		?>
        <tr>
            <td align="center"><?php echo $hostel->hostel_name; ?></td>
            <td align="center"><?php echo $floor->floor_no; ?></td>
            <td align="center"><?php echo $room->room_no.'<br>'.Yii::t('app','Bed(s)').'&nbsp;-&nbsp;';
								foreach($allot as $allot_1)
								{

									$data=Allotment::model()->findAllByAttributes(array('room_no'=>$allot_1->room_no,'status'=>'C'));
									echo $allot_1->bed_no.'&nbsp;&nbsp; ';
								}

								 ?></td>
            <td align="center">  <?php
						   echo (count($data)).'/'.(count($allot));
						   ?></td>
        </tr>

      <?php }
	  }
	  else
	  {
		  echo '<tr><td align="center" colspan="4"><strong>'.Yii::t('app','No data available').'</strong></td></tr>';
	  }
	  ?>
        </tbody>
     </table>
      <div class="pagecon">
                                                 <?php
	                                                  $this->widget('CLinkPager', array(
													  'currentPage'=>$pages->getCurrentPage(),
													  'itemCount'=>$total,
													  'pageSize'=>$page_size,
													  'maxButtonCount'=>5,
													  //'nextPageLabel'=>'My text >',
													  'header'=>'',
												  'htmlOptions'=>array('class'=>'pages'),
												  ));?>
                                                  </div>
                                 </div>
<div class="clear"></div>
</div>
		</td>
       </tr>
     </table>
    </td>
   </tr>
</table>
