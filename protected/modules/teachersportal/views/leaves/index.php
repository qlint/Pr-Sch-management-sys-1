<?php
$this->renderPartial('application.modules.teachersportal.views.default.leftside'); 
?>
<div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-check-square"></i><?php echo Yii::t("app",'Request leave');?><span><?php echo Yii::t("app",'View Leave Requests here');?></span></h2>
        </div>
        
    
        <div class="breadcrumb-wrapper">
            <span class="label">You are here:</span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t("app",'Leave Requests')?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
<div class="contentpanel"> 
    <div class="panel-heading">    
		<h3 class="panel-title"><?php echo Yii::t('app','Leave Requests');?></h3>
        
         <?php
		Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);
		if(Yii::app()->user->hasFlash('successMessage')): 
	?>
		<div class="flashMessage" style="color:#C00; padding-left:220px; font-size:13px">
			<?php echo Yii::app()->user->getFlash('successMessage'); ?>
		</div>
		<?php endif; ?>
        
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li>
			<?php
                echo CHtml::link(Yii::t('app','Request Leave'),array('create','id'=>Yii::app()->user->id),array('class'=>'btn btn-primary'));
            ?>
            </li>
            </ul>
            </div>
 		</div>
    </div> 
    <div class="people-item">  
<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
if($leaves)
{
?>		<div id="jobDialog"></div>
		<div class="table-responsive">
            <table class="table table-bordered mb30">
                    <style>
                        table, th, td {
                        border: 1px solid black;
                        border-collapse: collapse;
                                    }
                    </style>
                    <thead>
                        <tr>
                            <th><?php echo Yii::t('app','Leave Type'); ?></th>
                            <th><?php echo Yii::t('app','From Date'); ?></th>
                            <th><?php echo Yii::t('app','To Date'); ?></th>
                            <th><?php echo Yii::t('app','Is Half Day'); ?></th>
                            <th><?php echo Yii::t('app','Status'); ?></th>
                            <th><?php echo Yii::t('app','Action'); ?></th>
                        </tr>
                        </thead>
                        <?php
					foreach($leaves as $leave)
					{
						$leave_type	= LeaveTypes::model()->findByAttributes(array('id'=>$leave->leave_type_id)); 
						?>
					   <tr> 
                        <td><?php echo ucfirst($leave_type->type);?></td>
						<td><?php if($settings!=NULL){	
									$date1=date($settings->displaydate,strtotime($leave->from_date));
									echo $date1;
								}
								else{
									echo $leave->from_date; 
								}
							?></td>
						<td><?php if($settings!=NULL){	
									$date1=date($settings->displaydate,strtotime($leave->to_date));
									echo $date1;
								}
								else{
									echo $leave->to_date; 
								}
							?></td>
                		<td><?php if($leave->is_half_day == 0){echo '-'; }
								  if($leave->is_half_day == 1){echo Yii::t("app","Fore Noon"); }
								  if($leave->is_half_day == 2){echo Yii::t("app","After Noon"); }?></td>
                        <td><?php if($leave->status == 0){echo '<span class="opnsl_pending">'.Yii::t("app","Pending").'</span>'; }
								  if($leave->status == 1){echo '<span class="opnsl_approved">'.Yii::t("app","Approved").'</span>'; }
								  if($leave->status == 2){echo '<span class="opnsl_reject">'.Yii::t("app","Rejected").'</span>'; }
								  if($leave->status == 3){echo '<span class="opnsl_cancel">'.Yii::t("app","Cancelled").'</span>'; }?></td>
                        <td><?php if($leave->status == 0){
										echo CHtml::link(
											'Cancel',
											'javascript:void(0);',
											array(
												'class'=>'view_Exmintn_atg opnsl_cancelBtn open_popup',
												'data-ajax-url'=>$this->createUrl(
													'/teachersportal/leaves/cancel',
													array(
														'id' =>$leave->id,
													)
												),
												'data-target'=>"#myModal",
												'data-toggle'=>"modal",
												'data-modal-label'=>Yii::t("app", "Cancel Leave"),
												'data-modal-description'=>Yii::t("app", "Mark the reason for leave cancellation")
												
											)
										);
										echo '<br>';
									}
									elseif($leave->status == 3){
										echo Yii::t("app","Cancelled"); 
									}
									else{
										echo '-'; 
									}?>
                        </td>
            		</tr>           
					<?php                    
						
					}
				}
				else
				{
					echo Yii::t("app","No Leave Requests");
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
            'htmlOptions'=>array('class'=>'pagination'),
            ));?>
        </div> <!-- END div class="pagecon"-->
		</div>
       
      </div>  
	</div>




