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
</style>
<?php
$this->breadcrumbs=array(
	$this->module->id => array('/purchase'),
	Yii::t('app','Manage Vendors'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top"><?php $this->renderPartial('/default/leftside');?></td>
    	<td valign="top">
        	<div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app', 'Manage Sale');?></h1>

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php echo CHtml::link('<span>'.Yii::t('app','Add Sale').'</span>', array('/purchase/sale/create'),array('class'=>'a_tag-btn')); ?></li>                                    
</ul>
</div> 
</div>
                <?php
				Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);			
				if(Yii::app()->user->hasFlash('successMessage')): 
				?>
				<div class="flashMessage" style="color:#C00; padding-left:220px; font-size:13px">
					<?php echo Yii::app()->user->getFlash('successMessage'); ?>
				</div>
				<?php endif; ?>
                
                <div id="jobDialog"></div>
                <div class="row">
                <div class="col-md-12 ">
                 <div class="yellow-bg">               
                <div class="row">
                <div class="col-md-8 ">
                	<div class="filter-right-cnt">
					<?php
                    echo '<h3>'.Yii::t('app', 'Purchaser Type').'</h3>';
                    ?>
                    </div>
                </div>
                <div class="col-md-4 posicton-right ">
				<?php
                echo CHtml::dropDownList('type', isset($_REQUEST['type'])?$_REQUEST['type']:'', array(1=>Yii::t('app', 'Student'), 2=>Yii::t('app', 'Teacher'), 3=>Yii::t('app', 'Parent')), array('prompt'=>Yii::t('app', 'All'), 'id'=>'change-purchaser',));
                ?>
                </div>
                </div>
                                </div>
                </div>
                </div> 
				<div class="a_feed_cntnr">
                	<?php
					if(count($sales)>0){
						foreach($sales as $sale){
						?>
						<div class="individual_feed">
							<div class="a_feed_online">
								<div class="a_feed_innercntnt">
									<div class="a_feed_inner_arrow"></div>
										<div class="onln-adm-list">
											<div class="onln-adm-name">
												<h1>
													<strong>                                            	
														<?php 
															$item = PurchaseItems::model()->findByAttributes(array('id'=>$sale->material_id)); 
															echo Yii::t('app','Item Name').' : '.$item->name; 
														?>
													</strong>
												</h1>
											</div>
											<div class="onln-adm-date"></div>
										</div>
										<div class="onln-adm-list">
											<div class="onln-adm-table">
												<table class="reg_bx" width="300" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td width="30%"><p><?php echo Yii::t('app','Purchaser Type'); ?></p></td>
														<td width="10">:</td>                                             
														<td>
															<?php echo ($sale->purchaser==1)?Yii::t('app', 'Student'):(($sale->purchaser==2)?Yii::t('app', 'Teacher'):Yii::t('app', 'Parent'));?>
														</td>
													</tr>
													<tr>
														<td width="30%"><p><?php echo Yii::t('app','Purchased By'); ?></p></td>
														<td width="10">:</td>                                              
														<td>
															<?php															
																$user = Profile::model()->findByAttributes(array('user_id'=>$sale->employee_id));
																echo $user->fullname;
															?>
														</td>
													</tr>
													<tr>
														<td width="30%"><p><?php echo Yii::t('app','Purchased Date'); ?></p></td>
														<td width="10">:</td>                                              
														<td>
															<?php
																$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
																if($settings!=NULL){
																	$displaydate	= $settings->displaydate;	
																}else{
																	$displaydate	= 'Y-m-d'; 
																}
																echo date($displaydate, strtotime($sale->issued_date));
															?>
														</td>
													</tr>
													<tr>
														<td><p><?php echo Yii::t('app','Quantity'); ?></p></td>
														<td>:</td>
														<td><?php echo $sale->quantity; ?></td>
													</tr>
												</table>
											</div>
											<div class="onln-adm-table-icon">
											<div class="online_time onln-adm-stus">								
												<div class="online_time">
													<?php
														if($sale->status == 0){
															$status_class = 'tag_disapproved';
															$status_data = Yii::t('app','Not Issued');
														}else if($sale->is_issued == 1){
															$status_class = 'tag_approved';
															$status_data = Yii::t('app','Issued');
														}else if($sale->is_issued == 2){
															$status_class = 'tag_return';
															$status_data = Yii::t('app','Returned');
														}													
													?>
													<div class="online_status<?php if($sale->is_issued==2){?> tooltip-posctn<?php }?>">
														<?php if($sale->is_issued==2){?>
														<div class="tiiltip-block"><span><?php echo $sale->return_reason;?></span></div>
														<?php }?>
														<div class="<?php echo $status_class; ?>"><?php echo $status_data; ?></div>
													</div>	
												</div>
											</div>
											<div class="online_but onln-adm-stus">
												<ul class="tt-wrapper">
													<li>
													<?php
														if($sale->is_issued != 2){
															echo CHtml::ajaxLink('<span>'.Yii::t('app','Return Item').'</span>',
																$this->createUrl('/purchase/sale/returnitem'),
																array(
																	'onclick'=>'$("#jobDialog").dialog("open"); return false;',
																	'update'=>'#jobDialog',
																	'type'=>'GET',
																	'data' =>array(
																		'id' =>$sale->id
																	),
																	'dataType'=>'text'
																),
																array(
																	'class'=>'tt-return',
																	'title'=>Yii::t('app','Return Item'),
																	'id'=>'return_'.$sale->id
																)
															);
														}
													?>
													</li>
												</ul>
											</div>
										</div>
									</div>                   
								</div> <!-- END div class="a_feed_innercntnt" -->
							</div> <!-- END div class="a_feed_online" -->
						</div>
					
						<?php
						}
					}
					else{
						?>
						<div class="Not-found">	
						<?php
						echo '<p>'.Yii::t('app', 'No sale found').'</p>';
						?>
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
                </div>
            </div>
        </td>
    </tr>
</table>

<script>
$('select#change-purchaser').change(function(e) {
    var type	= $(this).val();
	window.location	= "<?php echo Yii::app()->createUrl('/purchase/sale/manage');?>&type=" + type;
});
</script>