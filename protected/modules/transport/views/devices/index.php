<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Devices')=>array('/transport/devices'),
	Yii::t('app','Assign'),
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/transportation/trans_left');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
            	<h1><?php echo Yii::t('app','Devices');?></h1>
                <div class="pdtab_Con" style="padding-top:0px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="pdtab-h">
                        	<td align="center" width="30">#</td>
                            <td align="center"><?php echo Yii::t('app','Device ID');?></td>
                            <td align="center"><?php echo Yii::t('app','Added by');?></td>
                            <td align="center"><?php echo Yii::t('app','Route');?></td>
                            <td align="center"><?php echo Yii::t('app','Assigned by');?></td>
                            <td align="center"><?php echo Yii::t('app','Status');?></td>
                            <td align="center"><?php echo Yii::t('app','Actions');?></td>
                     	</tr>
                        <?php
                        if(count($devices)>0){
							foreach($devices as $index=>$device){
						?>
                        <tr>
                        	<td align="center"><?php echo ($pages->currentPage* $pages->pageSize) + ($index+1);?></td>
                            <td align="center"><?php echo $device->device_id;?></td>
                            <td align="center">
								<?php
									$user	= User::model()->findByPk($device->created_by);
                                	echo ($user!=NULL)?$user->fullname:"-";
								?>
                           	</td>
                            <td align="center">
								<?php
									$route_device	= RouteDevices::model()->findByAttributes(array('device_id'=>$device->id));
									if($route_device!=NULL){
										$route	= RouteDetails::model()->findByPk($route_device->route_id);
										echo ($route!=NULL)?$route->route_name:"-";
									}
									else{
										echo "-";
									}                                	
								?>
                           	</td>
                            <td align="center">
								<?php
									if($route_device!=NULL){
										$user	= User::model()->findByPk($route_device->created_by);
										echo ($user!=NULL)?$user->fullname:"-";
									}
									else
										echo "-";
								?>
                           	</td>
                            <td align="center">
								<?php
                                	if($route_device!=NULL){
										if($route_device->status==1)
											echo Yii::t('app', 'Assigned. Approved.');
										else if($route_device->status==0)
											echo Yii::t('app', 'Assigned. Waiting for approval.');
									}
									else{
										echo "-";
									}
								?>
                           	</td>
                            <td align="center">
                            	<?php if($route_device!=NULL and $route_device->status==0){?>	
                            	<?php echo CHtml::link(Yii::t("app", "Approve"), "#", array("submit"=>array("approve", "id"=>$device->id), 'confirm'=>Yii::t('app', 'Are you sure ?'), "csrf"=>true));?>
                                 | 
                                <?php }?>
                                <?php if($route_device==NULL){?>
								<?php echo CHtml::link(Yii::t("app", "Assign"), array('assign', 'id'=>$device->id));?>
								<?php }else{?>
								<?php echo CHtml::link(Yii::t("app", "Unassign"), "#", array("submit"=>array("unassign", "id"=>$device->id), 'confirm'=>Yii::t('app', 'Are you sure ?'), "csrf"=>true));?>
                                 | 
                                <?php echo CHtml::link(Yii::t("app", "Edit"), array('update', 'id'=>$device->id));?>
                                <?php }?>
                                 | 
                                <?php echo CHtml::link(Yii::t("app", "Remove"), "#", array("submit"=>array("remove", "id"=>$device->id), 'confirm'=>Yii::t('app', 'Are you sure ?'), "csrf"=>true));?>
                            </td>
                        </tr>
                        <?php
							}
						}
						else{
						?>
                        <tr>
                        	<td align="center" colspan="7"><?php echo Yii::t('app', 'No devices added');?></td>
                       	</tr>
                        <?php
						}
						?>
                 	</table>
                    
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
              	</div>
            </div>
        </td>
    </tr>
</table>