<style type="text/css">
.list_contner_hdng{ margin:8px;}
.max_student{ border-left: 3px solid #fff;
    margin: 0 3px;
    padding: 6px 0 6px 3px;
    word-break: break-all;}
.reg_bx td{
	vertical-align:top;	
}
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Purchase')=>array('/purchase/materialRequistion/index'),
	Yii::t('app','Material Requests'),
);
$roles = Rights::getAssignedRoles(Yii::app()->user->Id);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/leftside');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Material Requests'); ?></h1>
                    <div class="a_feed_cntnr1" id="a_feed_cntnr">
                    <?php
	Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);
				if(Yii::app()->user->hasFlash('successMessage')): 
?>
				<div class="flashMessage" style="color:#C00; padding-left:220px; font-size:13px">
					<?php echo Yii::app()->user->getFlash('successMessage'); ?>
				</div>
<?php endif; ?>
                    
                    	<?php
						if($material_requests)
						{
							if(isset($_REQUEST['page'])){
								$i=($pages->pageSize*$_REQUEST['page'])-9;
							}
							else{
								$i=1;
							}
							   ?>
                               <div class="pdtab_Con">
                               <table  width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr class="pdtab-h">
                                      <th><?php echo Yii::t('app','Batch name') ?></th>
                                      <th><?php echo Yii::t('app','Item Name') ?></th>
                                      <th><?php echo Yii::t('app','Requested by') ?></th>
                                      <th><?php echo Yii::t('app','Quantity') ?></th>
                                      <th><?php echo Yii::t('app','Status') ?></th>
                                      <th><?php echo Yii::t('app','Action') ?></th>
                                </tr>
                               <?php
								foreach($material_requests as $material_request)
								{
								?>
                                    <tr>
                                      <?php 
									  	 $student = Students::model()->findByAttributes(array('uid'=>$material_request->employee_id));
									  	 $batch   = Batches::model()->findByAttributes(array('id'=>$student->batch_id));
									    ?>
                                      
                                         <td><?php echo $batch->name;?></td>
                                         <?php 
                                            $item = PurchaseItems::model()->findByAttributes(array('id'=>$material_request->material_id)); 
                                          ?>
                                          <td><?php echo $item->name;?></td>
                                            	
                                           <td>
											   <?php if($student!=NULL){
                                                echo $student->first_name.' '.$student->middle_name.' '.$student->last_name;
                                                }
                                                else{
                                                $user = Profile::model()->findByAttributes(array('user_id'=>$material_request->employee_id));
                                                echo $user->firstname.' '.$user->lastname;
                                                }?>
                                            </td>
                                            <td><?php echo $material_request->quantity; ?></td>
												<?php
                                                if($material_request->status_pm == 2)
                                                {
                                                $status_class = 'tag_disapproved';
                                                $status_data = Yii::t('app','Rejected');
                                                }
                                                elseif($material_request->status_pm == 0)
                                                {
                                                $status_class = 'tag_pending';
                                                $status_data = Yii::t('app','Pending');
                                                }
                                                elseif($material_request->status_pm == 1)
                                                {
                                                $status_class = 'tag_approved';
                                                $status_data = Yii::t('app','Approved');
                                                }
                                                ?>
                                            <td><div class="<?php echo $status_class; ?>"><?php echo $status_data; ?></div></td>
                                            <?php if(key($roles) == 'Admin' or key($roles) == 'pm'){?>
                                            <td>
                                                <div class="online_but onln-adm-stus">
                                                    <ul class="tt-wrapper">
                                                    <?php
                                                    if($material_request->status_pm == 0)
                                                    {
                                                    ?>
                                                        <li>
															<?php
                                                            echo CHtml::link('<span>'.Yii::t('app','Approve').'</span>', array('requestapprove','id'=>$material_request->id),array('class'=>'tt-approved','confirm'=>Yii::t('app','Are you sure you want to approve this request ?'))); 
                                                            ?>
															<?php
                                                            echo CHtml::link('<span>'.Yii::t('app','Reject').'</span>', array('requestdisapprove','id'=>$material_request->id,'flag'=>1),array('class'=>'tt-disapproved','confirm'=>Yii::t('app','Are you sure you want to reject this request ?'))); 
                                                            ?>
                                                        </li>
														<?php
                                                        }
														 else if($material_request->status_pm == 1 && $material_request->is_issued == 0)
                                                        {
                                                        ?>
                                                        <li>
															<?php
                                                              echo CHtml::link('<span>'.Yii::t('app','issue Item').'</span>', array('issuerequest','id'=>$material_request->id),array('class'=>'tt-issue-item'));
                                                              ?>
                                                        </li>
                                                      <?php
                                                    }
													else if($material_request->is_issued == 1)
													{
														 echo Yii::t('app','Issued');
													}
                                                    ?>
                                                    </ul>
                                                </div>
                                            </td>
                                            <?php } ?>
                                         </tr>
                                         <tr></tr>
                                      
                                      <?php
									  $i++;
								}
								?>
														</table>
                                                        </div>
													
											
																	   
											</div> <!-- END div class="a_feed_innercntnt" -->
										</div> <!-- END div class="a_feed_online" -->
								<?php
						}
						else
						{
						?>
                        	<div>
                                <div class="yellow_bx" style="background-image:none;width:600px;padding-bottom:45px;">
                                    <div class="y_bx_head" style="width:580px;">
                                    <?php 
                                        echo Yii::t('app','No Material Requests');
                                    ?>
                                    </div>
                                   
                                </div>
                            </div>
                        <?php
						}
						?>    
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
                    </div> <!-- END div class="a_feed_cntnr" --> 
            </div> <!-- END div class="cont_right formWrapper" -->
        </td>
    </tr>
</table>            
