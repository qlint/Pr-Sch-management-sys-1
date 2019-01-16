<style type="text/css">
.pdtab_Con {
    margin: 0;
    padding: 5px 0 0;
}
.pro-ul{ margin:0px; padding:0px;}
.pro-ul li{ padding:0px 3px; list-style:none; display:inline-block;}
.delete{ width:12px; height:12px; background:url(images/task-dlt.png) no-repeat center;}
.view{ width:12px; height:12px; background:url(assets/1effa1bf/gridview/view.png) no-repeat center;}
.edit{ width:12px; height:12px; background:url(images/task-edit.png) no-repeat center;}
.reg_bx td{
	vertical-align:top;	
}
.button-column a{
	 float:none;	
}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Hr')=>array('/hr'),
	Yii::t('app','Request Leave'),
);
?>
<div id="jobDialog"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top"><?php $this->renderPartial('/default/leftside');?></td>
    	<td valign="top">
        	<div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Leave Requests');?></h1>
                <div class="edit_bttns " style="top:16px; right:16px;">
                    <ul>
                    	<li><?php echo CHtml::link('<span>'.Yii::t('app','Request Leave').'</span>', array('create'),array('class'=>'addbttn last')); ?></li>
                    </ul>
            	</div>
                <?php
				Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);
			
				if(Yii::app()->user->hasFlash('successMessage')): 
			?>
				<div class="flashMessage" style="color:#C00; padding-left:220px; font-size:13px">
					<?php echo Yii::app()->user->getFlash('successMessage'); ?>
				</div>
				<?php endif; ?>
<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
?>	       
				<div class="pdtab_Con" style="width:100%">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  		<tbody>
                    		<tr class="pdtab-h">
                            	<td align="center" height="18"><?php echo Yii::t('app','Leave Type');?></td>
                            	<td align="center" height="18"><?php echo Yii::t('app','From Date');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','To Date');?></td>
                                 <td align="center" height="18"><?php echo Yii::t('app','Is Half Day');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Status');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Actions');?></td>
                            </tr>
					<?php
							if($leaves){
								foreach($leaves as $leave){
									$leave_type	= LeaveTypes::model()->findByAttributes(array('id'=>$leave->leave_type_id)); 
						?>
                                    <tr>
                                        <td align="center" width="40"><?php echo $leave_type->type;
										//echo CHtml::ajaxLink($leave_type->type,$this->createUrl('view'),
                                       // array('onclick'=>'$("#jobDialog_view").dialog("open"); return false;','update'=>'#jobDialog_view_div'.$leave->id,'type' =>'GET','data' => array('id' =>$leave->id),'dataType' => 'text',),array('id'=>'showJobDialog_view'.$leave->id,'class'=>'view', 'title'=>Yii::t('app','View')));?></td>
                                        <td align="center" width="200"><?php if($settings!=NULL){	
																				$date1=date($settings->displaydate,strtotime($leave->from_date));
																				echo $date1;
																			 }
																			 else{
																				echo $leave->from_date; 
																			 }
																		?></td>
                                        <td align="center" width="125"><?php if($settings!=NULL){	
																				$date1=date($settings->displaydate,strtotime($leave->to_date));
																				echo $date1;
																			 }
																			 else{
																				echo $leave->to_date; 
																			 }
																		?></td>
                                         <td align="center" width="125"><?php if($leave->is_half_day == 0){echo '-'; }
																			  if($leave->is_half_day == 1){echo Yii::t("app","Fore Noon"); }
																			  if($leave->is_half_day == 2){echo Yii::t("app","After Noon"); }?></td>
                                        <td align="center" width="200"><?php  if($leave->status == 0){echo Yii::t("app","Pending"); }
																			  if($leave->status == 1){echo Yii::t("app","Approved"); }
																			  if($leave->status == 2){echo Yii::t("app","Rejected"); }
																			  if($leave->status == 3){echo Yii::t("app","Cancelled"); }?></td>
                                        <td align="center" class="button-column">
                            	<?php if($leave->status == 0 or $leave->status == 1){
																echo CHtml::ajaxLink(Yii::t('app','Cancel'),$this->createUrl('cancel'),
																array('onclick'=>'$("#jobDialog").dialog("open"); return false;','update'=>'#jobDialog','type' =>'GET','data' => array('id' =>$leave->id),'dataType' => 'text',), array('id'=>'showJobDialog'.$leave->id,'class'=>'remove-form'));
									}
									elseif($leave->status == 3){
										echo Yii::t("app","Cancelled"); 
									}
									else{
										echo '-'; 
									}?>           	                        	
	                        </td>   
<?php		
								}
							}
							else{
?>
								<td colspan="6" style="text-align:center; font-style:italic;"><?php echo Yii::t('app','No Leave Requests!'); ?></td>
<?php								
							}
?>                            
                        </tbody>
                    </table>        
                </div>
          <div class="pagecon">
        <?php                                          
            $this->widget('CLinkPager', array(
            'currentPage'=>$pages->getCurrentPage(),
            'itemCount'=>$item_count,
            'pageSize'=>$page_size,
            'maxButtonCount'=>5,						
            'header'=>'',
            'htmlOptions'=>array('class'=>'pages'),
        ));?>
            </div>
             </div>
        </td>
    </tr>
</table>        
