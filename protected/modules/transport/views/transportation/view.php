<?php $this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Allotment')=>array('/transport/transportation/create'),
	Yii::t('app','View'),
	
);?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
            <?php $this->renderPartial('/transportation/trans_left');?>
        </td>
        <td valign="top">
            <div class="cont_right">
              
                    <h1><?php echo Yii::t('app','Transportation');?></h1>


                <div class="pdtab_Con" style="padding-top:0px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="pdtab-h">
                        <?php
							if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){						
					  ?> 
                            <td align="center">
                                <?php echo Yii::t('app','Student Name');?>
                            </td>
                       <?php } ?>     
                            <td align="center">
                                <?php echo Yii::t('app','Route');?>
                            </td>
                            <td align="center">
                                <?php echo Yii::t('app','Stop');?>
                            </td>
                            <td align="center">
                                <?php echo Yii::t('app','Fare');?>
                            </td>
                            
                        </tr>
                        <tr>
                            <?php $student=Students::model()->findByAttributes(array('id'=>$model->student_id));
                                  $stop=StopDetails::model()->findByAttributes(array('id'=>$model->stop_id));
                                  $route=RouteDetails::model()->findByAttributes(array('id'=>$stop->route_id));
 ?>
 						<?php
							if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){						
					  ?> 
                            <td align="center">
                                <?php echo $student->studentFullName('forStudentProfile');?>
                            </td>
                       <?php } ?>     
                            <td align="center">
                                <?php echo $route->route_name;?>
                            </td>
                            <td align="center">
                                <?php echo $stop->stop_name;?>
                            </td>
                            <td align="center">
                                <?php echo $stop->fare;?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
</table>



