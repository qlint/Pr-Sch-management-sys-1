<?php
	$this->breadcrumbs=array(
		Yii::t('app','Fees')=>array('/fees'),
	);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">    
            <?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" width="75%">
                        <div class="cont_right formWrapper">
                            <h1><?php echo Yii::t('app','Fees Dashboard'); ?></h1>
                            <div class="overview" style="padding-top:0px;">
                                <div class="overviewbox ovbox1" style="margin-left:0px;">
                                    <h1><strong><?php echo Yii::t('app','Total Fee Categories'); ?></strong></h1>
                                    <div class="ovrBtm">
                                    	<?php echo $total_categories;?>
                                    </div>
                                </div>
                                <div class="overviewbox ovbox2">
                                    <h1><strong><?php echo Yii::t('app','Invoices Generated For'); ?></strong></h1>
                                    <div class="ovrBtm">
                                    	<?php echo count($invoices_for);?>
                                    </div>
                                </div>
                                <div class="clear"></div>                            
                            </div>
                            
							<?php if(Yii::app()->user->hasFlash('error')):?>
                            <div class="status_box" style="width:598px; margin:40px 0 0;">
                                <div class="sb_icon"></div>
                            	<span style="color:#FF0D50"><?php echo Yii::app()->user->getFlash('error'); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(Yii::app()->user->hasFlash('success')):?>
                            <div class="status_box" style="width:598px; margin:40px 0 0;">
                                <div class="sb_icon"></div>
                            	<span style="color:#39934E"><?php echo Yii::app()->user->getFlash('success'); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <!-- categories -->
                            <div style="width:97%" class="pdtab_Con">
                                <div style="font-size:13px; padding:5px 0px">
                                	<strong><?php echo Yii::t('app','Fee Categories'); ?></strong></div>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr class="pdtab-h">
                                            <td height="18" align="center"><?php echo Yii::t('app','Category Name'); ?></td>
                                            <td align="center"><?php echo Yii::t('app','Date Created'); ?></td>
                                            <td align="center"><?php echo Yii::t('app','Created By'); ?></td>
                                            <td align="center"><?php echo Yii::t('app','Invoice Generated'); ?></td>
                                            <td align="center"><?php echo Yii::t('app','Actions'); ?></td>                            
                                        </tr>
                                        <?php
                                        foreach($categories as $key=>$category){
										?>
                                        <tr>
                                            <td align="center">
												<?php
                                                	echo CHtml::link($category->name, array("/fees/view", "id"=>$category->id), array("target"=>"_blank"));
												?>
                                            </td>
                                            <td align="center">
												<?php
													$settings	= UserSettings::model()->findByAttributes(array('user_id'=>$category->created_by));
													if($settings!=NULL)
														echo date($settings->displaydate, strtotime($category->created_at));
													else
                                                		echo $category->created_at;
												?>
                                            </td>
                                            <td align="center">
                                            	<?php
													if($category->created_by==Yii::app()->user->id){
														echo Yii::t("app", "Me");
													}
													else{
														$user	= Profile::model()->findByAttributes(array('user_id'=>$category->created_by));
														if($user!=NULL)
															echo $user->fullname;
														else
															echo '-';
													}
												?>                                            	                                        
                                            </td>
                                            <td align="center">
                                            	<?php
                                                	echo ($category->invoice_generated==1)?Yii::t("app", "Yes"):Yii::t("app", "No");
												?>                                            
                                            </td>
                                            <td align="center">
                                                <?php
												//subscriptions
												$subscriptions	= FeeSubscriptions::model()->findAllByAttributes(array('fee_id'=>$category->id));
												if(count($subscriptions)==0){
													echo CHtml::link(Yii::t("app", "Create Subscriptions"), array("/fees/subscriptions", 'id'=>$category->id), array('title'=>Yii::t("app", "Click to create subscriptions")));
													echo " | ";
													echo CHtml::link(Yii::t("app", "Remove"), "#", array("submit"=>array("/fees/remove"), 'params'=>array('id'=>$category->id), 'title'=>Yii::t("app", "Remove this fee category"), 'confirm'=>Yii::t('app', 'Are you sure remove this fee category ?'), 'csrf'=>true));
												}
												else{
													if($category->invoice_generated==0){
														echo CHtml::link(Yii::t("app", "Generate Invoice(s)"), array("/fees/invoices/generate", 'id'=>$category->id), array('title'=>Yii::t("app", "Click to generate Invoice(s)"), 'confirm'=>Yii::t('app', 'Are you sure generate invoice(s) for this fee category ?')));
														echo " | ";
														echo CHtml::link(Yii::t("app", "Remove"), "#", array("submit"=>array("/fees/remove"), 'params'=>array('id'=>$category->id), 'title'=>Yii::t("app", "Remove this fee category"), 'confirm'=>Yii::t('app', 'Are you sure remove this fee category ?'), 'csrf'=>true));														
													}
													else
														echo CHtml::link(Yii::t("app", "View Invoice(s)"), array("/fees/invoices", 'FeeInvoices'=>array('fee_id'=>$category->id)), array('title'=>Yii::t("app", "Click to view invoice(s)")));
												}
												?>
                                            </td>                            
                                        </tr>
                                        <?php
										}
										if(count($categories)==0){
										?>
                                        <tr>
                                        	<td align="center" colspan="7"><?php echo Yii::t("app", "No data found");?></td>
                                        </tr>
                                        <?php
										}
										?>                       
                                    </tbody>                                                       
                                </table>
                                <?php                                          
									$this->widget('CLinkPager', array(
										'currentPage'=>$pages->getCurrentPage(),
										'itemCount'=>$item_count,
										'pageSize'=>$page_size,
										'maxButtonCount'=>5,
										//'nextPageLabel'=>'My text >',
										'header'=>'',
										'htmlOptions'=>array('class'=>'pages'),
									));
								?>
                                <div class="clear"></div>
                        	</div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>