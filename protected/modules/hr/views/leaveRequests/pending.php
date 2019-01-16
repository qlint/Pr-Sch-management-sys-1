<style type="text/css">
.pdtab_Con {
    margin: 0;
    padding: 5px 0 0;
}
.os-button-column ul li a{display: block;float: left;width: 20px;height: 20px;}
.os-button-column ul{ margin:0px; padding:0px;}
.os-button-column ul li{ padding:0px 3px; list-style:none; display:inline-block;}
.delete{ width:12px; height:12px; background:url(images/os-deleteicon.png) no-repeat center;}
.view{ width:12px; height:12px; background:url(images/os-viewicon.png) no-repeat center;}
.edit{ width:12px; height:12px; background:url(images/os-editicon.png) no-repeat center;}
.tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;

    /* Position the tooltip */
    position: absolute;
    z-index: 1;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
}
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','HR')=>array('/hr/leaveTypes'),
	Yii::t('app','Leave Requests')=>array('/hr/leaveRequests/pending'),
	Yii::t('app','Pending')
);

$settings = UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
            <?php $this->renderPartial('/default/leftside');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Pending Leave Requests');?></h1>
            	<div class="search_btnbx">
                	<div id="jobDialog"></div>
                	<div class="contrht_bttns"></div>
				</div>
              	<div class="clear"></div>
				<?php Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);
                if(Yii::app()->user->hasFlash('successMessage')): 
                ?>
                <div class="flashMessage" style="color:#C00; padding-left:220px; font-size:13px">
                    <?php echo Yii::app()->user->getFlash('successMessage'); ?>
                </div>
                <?php endif; ?>
                <div class="pdtab_Con" style="width:100%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="pdtab-h">
                            <td align="center" height="18" width="50"><?php echo '#';?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','Type');?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','Requested By');?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','From');?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','To');?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','Day(s)');?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','Actions');?></td>
                        </tr>
                        <?php if($requests){ ?>
							<?php
                            foreach($requests as $index=>$request){            
                            ?>
                            <tr>			        		
                                <td align="center"><?php echo ($pages->getCurrentPage() * $pages->getPageSize()) + ($i + 1);?></td>
                                <td align="center"><?php echo ($request->leaveType)?$request->leaveType->type:'-'; 
					//calculate employee remaining leave 
					$employee = Employees::model()->findByAttributes(array('uid'=>$request->requested_by));
					$taken	=  EmployeeAttendances::model()->findAllByAttributes(array('employee_leave_type_id'=>$request->leaveType->id, 'employee_id'=>$employee->id));
					$days=0;
					if($taken){							  
						foreach($taken as $take){
						if($take->is_half_day == 0){
								$days		=	$days+1;
								$leave 		= 	LeaveTypes::model()->findByAttributes(array('id'=>$request->leaveType->id)); 
								$remaining 	=	($leave->count)-($days);
							}else{
								$days		=	$days+.5;
								$leave 		= 	LeaveTypes::model()->findByAttributes(array('id'=>$request->leaveType->id)); 
								$remaining 	=	($leave->count)-($days); 
							}
						}	
					}
					else{
						$leave 			= 	LeaveTypes::model()->findByAttributes(array('id'=>$request->leaveType->id)); 
						$remaining		=   $leave->count;
					}
					//end
								?>
                                    <div class="tooltip">(
									<?php 
									if($remaining>=0){
										echo $remaining;
									} 
									else{
										 echo '0';
									}
									?>)
                                  	  <span class="tooltiptext"><?php echo Yii::t("app",'Remaining Leaves');?></span>
									</div>
                                </td>
                                <td align="center">
                                    <?php
                                        $employee	= Staff::model()->findByAttributes(array('uid'=>$request->requested_by));
                                        echo ($employee!=NULL)?$employee->fullname:'-';
                                    ?>
                                </td>
                                <td align="center">
                                    <?php
                                        if($settings){
                                            echo date($settings->displaydate, strtotime($request->from_date));
                                        }
                                        else{
                                            echo date('Y-m-d', $request->from_date);
                                        }
                                    ?>
                                </td>
                                <td align="center">
                                    <?php
                                        if($settings){
                                            echo date($settings->displaydate, strtotime($request->to_date));
                                        }
                                        else{
                                            echo date('Y-m-d', $request->to_date);
                                        }
                                    ?>
                                </td>
                                <td>
                                <?php
								if($request->is_half_day!=0)
									echo Yii::t("app",'Half Day');
								else{
									$start 	= $request->from_date;
									$end 	= $request->to_date;
									$diff	= (strtotime($end)- strtotime($start))/24/3600; 
									echo $diff+1 .' ';echo Yii::t("app",'Day(s)');
								}
								?>
                                </td>
                                <td align="center" class="os-button-column">
                                	<ul>
                                    	<li>
                                        	<?php echo CHtml::link('', array('view', 'id'=>$request->id),array('class'=>'view', 'title'=>Yii::t('app','View'), 'target'=>'_blank'));?>
                                        </li>
                                        <li>
                                			<?php echo CHtml::ajaxLink('',
												$this->createUrl('/hr/leaveRequests/approve'),
												array(
													'onclick'=>'$("#jobDialog").dialog("open"); return false;',
													'update'=>'#jobDialog',
													'type'=>'GET',
													'data' =>array(
														'id' =>$request->id
													),
													'dataType'=>'text'
												),
												array(
													'class'=>'approve-n',
													'title'=>Yii::t('app','Approve')
												)
											);?>
                                      	</li>
                                        <li>
                                			<?php echo CHtml::ajaxLink('',
												$this->createUrl('/hr/leaveRequests/reject'),
												array(
													'onclick'=>'$("#jobDialog").dialog("open"); return false;',
													'update'=>'#jobDialog',
													'type' =>'GET',
													'data'=>array(
														'id' =>$request->id
													),
													'dataType'=>'text'
												),
												array(
													'class'=>'delete',
													'title'=>Yii::t('app','Reject')
												)
											);?>
                                      	</li>
                                    </ul>
                                </td>                                
                            </tr>	
                            <?php
                            	$i++;
                            }
                        }
                        else{
                        ?>
                            <tr>
                                <td colspan="7" align="center"><?php echo Yii::t('app', 'No pending requests');?></td>
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
                                'header'=>'',
                                'htmlOptions'=>array('class'=>'pages'),
                            ));
                        ?>							
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </td>
    </tr>
</table>