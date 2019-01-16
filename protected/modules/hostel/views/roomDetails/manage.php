<?php
$this->breadcrumbs=array(
	Yii::t('app','Room Details')=>array('index'),
	Yii::t('app','Manage'),
);

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/settings/hostel_left');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
            <h1><?php echo Yii::t('app','Manage Room Details');?></h1>
            <div class="table-responsive">
			<?php
            $bedinfo=RoomDetails::model()->findAll();
            if($bedinfo!=NULL)
            {
            ?>
            <table class="table table-bordered mb30" width="100%" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th><?php echo Yii::t('app','Room No');?></th>
                        <th><?php echo Yii::t('app','Floor');?></th>
                        <th><?php echo Yii::t('app','Bed No');?></th>
                        <th><?php echo Yii::t('app','Available');?></th>
                    </tr>
                </thead>
                    <?php
                    foreach($bedinfo as $bed_info)
                    {
                    ?>
                    <tr>
                    <td><?php echo $bed_info->room_no; ?></td>
                    <td><?php 
                    $floor=Floor::model()->findByAttributes(array('id'=>$bed_info->no_of_floors));
                    echo $floor->floor_no; ?></td>
                    <td><?php echo $bed_info->bed_no; ?></td>
                    <?php 
                    if($bed_info->status=='C')
                    {
                    echo '<td>'.Yii::t('app','Yes').'</td>';
                    }
                    else
                    {
                    echo '<td>'.Yii::t('app','No').'</td>';
                    }?>
                    </tr>
                    <?php
                    }
                    ?>
                    <?php
                    }
                    
                    ?>
               </table>
            </div> 
            </div>    
        </td>
    </tr>
</table>





