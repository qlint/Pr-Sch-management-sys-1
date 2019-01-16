<style>
.events_con table td a{
	color:#F60;
}
</style>
<?php
$this->breadcrumbs=array(
        Yii::t('app','Hostel')=>array('/hostel'),
	Yii::t('app','Rooms')=>array('/hostel/room/manage'),
	Yii::t('app','Manage'),
);
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vacate-form',
	'enableAjaxValidation'=>false,
)); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
            <?php $this->renderPartial('/settings/hostel_left');?>
        </td>
        <td valign="top">
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Manage Room Details');?></h1>
                <div>
                 <div class="formCon">
					<div class="formConInner">
                   <?php echo Yii::t('app','Sort Rooms by').'&nbsp;';; 
echo CHtml::dropDownList('search','',array('1'=>Yii::t('app','All'),'2'=>Yii::t('app','Occupied'),'3'=>Yii::t('app','Vacant')),array('prompt'=>Yii::t('app','Select'),'id'=>'search_id','submit'=>array('Room/manage')));
?>					</div>
                </div>
               
                    <?php if(isset($list)) // From Allotment table
{		
	
	
	
		?> <div class="pdtab_Con" style="padding-top:0px;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr class="pdtab-h">
                           <td align="center"><?php echo Yii::t('app','Hostel');?></td>
                            <td align="center"><?php echo Yii::t('app','Floor');?></td>
                            <td align="center"><?php echo Yii::t('app','Room No');?></td>
                            <td align="center"><?php echo Yii::t('app','Availability');?></td>
                             <td align="center"><?php echo Yii::t('app','Action');?></td>
                        </tr>
                        <?php 
						
						if($list==NULL)
						{ 
						
							echo '<tr><td align="center" colspan="6"><strong>'.Yii::t('app','No data available').'</strong></td></tr>';
						}
						else
						{
						foreach($list as $list_1)
						{
						/*}
						$flwr=Floor::model()->findAll();
						foreach($flwr as $f)
						{*/
							$allot=Allotment::model()->findAllByAttributes(array('room_no'=>$list_1->room_no));
							//var_dump($allot);exit;
							$room=Room::model()->findByAttributes(array('id'=>$list_1->room_no));
							//var_dump($room);exit;
							$floor=Floor::model()->findByAttributes(array('id'=>$room->floor));
							//var_dump($floor);exit;
							$hostel=Hosteldetails::model()->findByAttributes(array('id'=>$room->hostel_id));
							//echo $hostel->hostel_name; 
							//var_dump($hostel);exit;
							?>
                        <tr>
                            <td align="center">
                                <?php echo $hostel->hostel_name; ?>
                            <td align="center">
                                <?php echo $floor->floor_no;
								  ?>
                            </td>
                            <td align="center">
                                <?php echo $room->room_no.'<br>'.Yii::t('app','Beds').'&nbsp;-&nbsp;';
								foreach($allot as $allot_1)
								{
									
									$data=Allotment::model()->findAllByAttributes(array('room_no'=>$allot_1->room_no,'status'=>'C'));
									echo $allot_1->bed_no.'&nbsp;&nbsp; ';
								}
							
								 ?>
                            </td>
                           <td align="center">
                           <?php
						   echo (count($data)).'/'.(count($allot));
						   ?>
                           </td>
                            
                                <?php /*?><?php if($list_1->status=='C')
	{
		echo '<td align="center">Yes</td>';
	}
	else
	{
	echo '<td align="center" >No</td>';
	}?><?php */?>
   								  <td align="center">
                                    <?php
                                    
                                        echo CHtml::link(Yii::t('app','Edit'),array('/hostel/room/update','id'=>$room->id)); 
                                  		echo ' | ';
									    echo CHtml::link(Yii::t('app','Delete'),array('/hostel/Hosteldetails/deletehostel','id'=>$room->id),array('confirm'=>'Are you sure?'));             ?>
                                    
                                    </td>
                           
                        </tr>
                        <?php 
			}
	}
	?>
                    </table>
 </div>
              <div class="pagecon">
 

<?php 

		                                              $this->widget('CLinkPager', array(
													  'currentPage'=>$pages->getCurrentPage(),
													  'itemCount'=>$item_count,
													  'pageSize'=>$page_size,
													  'maxButtonCount'=>5,
													  //'nextPageLabel'=>'My text >',
													  'header'=>'',
												  'htmlOptions'=>array('class'=>'pages'),
												  ));?>

</div>
  <?php
              }  ?>
            </div>
			</div>
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>