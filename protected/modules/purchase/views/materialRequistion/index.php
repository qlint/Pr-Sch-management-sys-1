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

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php echo CHtml::link('<span>'.Yii::t('app','Material Requests from Students').'</span>', array('request'), array('class'=>'a_tag-btn')); ?></li>                                  
</ul>
</div> 
</div>

                
                    <div class="a_feed_cntnr" id="a_feed_cntnr">
                    
                    	<?php
						if($material_requests)
						{
							if(isset($_REQUEST['page'])){
								$i=($pages->pageSize*$_REQUEST['page'])-9;
							}
							else{
								$i=1;
							}
								foreach($material_requests as $material_request)
								{
								?>
								<div class="individual_feed">
									<div class="a_feed_online">
										<div class="a_feed_innercntnt">
											<div class="a_feed_inner_arrow"></div>
												<div class="onln-adm-list">
													<div class="onln-adm-name">
														<h1><strong>                                            	
															<?php 
															$item = PurchaseItems::model()->findByAttributes(array('id'=>$material_request->material_id)); 
															echo Yii::t('app','Item Name').' : '.$item->name; 
																?>
														</strong></h1>
													</div>
													<div class="onln-adm-date"></div>
												</div>
												<div class="onln-adm-list">
													<div class="onln-adm-table">
														<table class="reg_bx" width="300" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td width="30%"><p><?php echo Yii::t('app','Requested By'); ?></p></td>
																<td width="10">:</td>
														  <?php $employee = Employees::model()->findByAttributes(array('uid'=>$material_request->employee_id));  ?>
																<td><?php if($employee!=NULL){
																				echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;
																		   }
																		   else{
																			   $user = Profile::model()->findByAttributes(array('user_id'=>$material_request->employee_id));
																			   echo $user->firstname.' '.$user->lastname;
																		   }?></td>
															</tr>
															<tr>
																<td width="15%"><p><?php echo Yii::t('app','Department'); ?></p></td>
																<td>:</td>
														  <?php $department = EmployeeDepartments::model()->findByAttributes(array('id'=>$material_request->department_id));  ?>
																<td><?php echo $department->name; ?></td>
															</tr>
															<tr>
																<td><p><?php echo Yii::t('app','Quantity'); ?></p></td>
																<td>:</td>
																<td><?php echo $material_request->quantity; ?></td>
															</tr>
														</table>
													</div>
													<div class="onln-adm-table-icon">
													<div class="online_time onln-adm-stus">								
														<div class="online_time">
																<?php
																if($material_request->status == 2)
																{
																$status_class = 'tag_disapproved';
																$status_data = Yii::t('app','Rejected');
																}
																elseif($material_request->status == 0)
																{
																$status_class = 'tag_pending';
																$status_data = Yii::t('app','Pending');
																}
																elseif($material_request->status == 1)
																{
																$status_class = 'tag_approved';
																$status_data = Yii::t('app','Approved');
																}
																?>
															<div class="online_status" ><div class="<?php echo $status_class; ?>"><?php echo $status_data; ?></div></div>	
														</div>
														</div>
										   <?php if(key($roles) == 'Admin'){?>
														<div class="online_but onln-adm-stus">
															<ul class="tt-wrapper">
																<li>
																<?php
																if($material_request->status == 0)
																{
																echo CHtml::link('<span>'.Yii::t('app','Approve').'</span>', array('approve','id'=>$material_request->id),array('class'=>'tt-approved','confirm'=>Yii::t('app','Are you sure you want to approve this request ?'))); 
																
																echo CHtml::link('<span>'.Yii::t('app','Reject').'</span>', array('disapprove','id'=>$material_request->id,'flag'=>1),array('class'=>'tt-disapproved','confirm'=>Yii::t('app','Are you sure you want to reject this request ?'))); 
																
																}
																?>
																</li>
															</ul>
														</div>
											<?php } ?>
													</div>
												</div> 
																		   
											</div> <!-- END div class="a_feed_innercntnt" -->
										</div> <!-- END div class="a_feed_online" -->
									</div> <!-- END div class="individual_feed" -->
								<?php
								$i++;
								}
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
