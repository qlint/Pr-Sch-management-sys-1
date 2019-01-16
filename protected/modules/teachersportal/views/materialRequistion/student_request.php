<?php
	$roles=Rights::getAssignedRoles(Yii::app()->user->Id); 
	if(sizeof($roles)==1 and key($roles) == 'teacher')
	{
		$this->renderPartial('application.modules.teachersportal.views.default.leftside'); 
	}
?>
<div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-shopping-cart"></i><?php echo Yii::t("app",'Material Requests from Students');?><span><?php echo Yii::t("app",'View Material Requests here');?></span></h2>
        </div>
        
    
        <div class="breadcrumb-wrapper">
            <span class="label">You are here:</span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t("app",'Material Requests')?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
   <div class="contentpanel"> 
    <div class="panel-heading">    
		<h3 class="panel-title"><?php echo Yii::t('app','Material Requistion');?></h3>
        
         <?php
		Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);
		if(Yii::app()->user->hasFlash('successMessage')): 
	?>
		<div class="flashMessage" style="color:#C00; padding-left:220px; font-size:13px">
			<?php echo Yii::app()->user->getFlash('successMessage'); ?>
		</div>
		<?php endif; ?>
		
    </div> 
    <div class="people-item">
      
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <div class="cont_right formWrapper">
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
							   ?>
                               <table class="table table-hover mb30" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                      <th><?php echo Yii::t('app','Batch name') ?></th>
                                      <th><?php echo Yii::t('app','Item Name') ?></th>
                                      <th><?php echo Yii::t('app','Requested by') ?></th>
                                      <th><?php echo Yii::t('app','Quantity') ?></th>
                                      <th><?php echo Yii::t('app','Status') ?></th>
                                      <th><?php echo Yii::t('app','Issue'); ?></th>
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
                                                if($material_request->status_tchr == 2)
                                                {
                                                $status_class = 'tag_disapproved';
                                                $status_data = Yii::t('app','Rejected');
                                                }
                                                elseif($material_request->status_tchr == 0)
                                                {
                                                $status_class = 'tag_pending';
                                                $status_data = Yii::t('app','Pending');
                                                }
                                                elseif($material_request->status_tchr == 1)
                                                {
                                                $status_class = 'tag_approved';
                                                $status_data = Yii::t('app','Approved');
                                                }
                                                ?>
                                            <td><div class="<?php echo $status_class; ?>"><?php echo $status_data; ?></div></td>
                                            <td style="text-align:center"><?php
												if($material_request->status_tchr== 1){
												if($material_request->is_issued== 0)
												{ 
												echo Yii::t('app','Not Issued');
												}
												if($material_request->is_issued== 1)
												{ 
												echo Yii::t('app','Issued');
												}
												}else{
												echo '-';
												}
                                            ?></td>
                                            <?php if(key($roles) == 'teacher'){?>
                                            <td>
                                                <div class="online_but onln-adm-stus">
                                                    <ul class="tt-wrapper">
                                                    <?php
                                                    if($material_request->status_tchr == 0)
                                                    {
                                                    ?>
                                                        <li>
															<?php
                                                            echo CHtml::link('<span>'.Yii::t('app','Approve').'</span>', array('requestapprove','id'=>$material_request->id),array('class'=>'tt-approved','confirm'=>Yii::t('app','Are you sure you want to approve this request ?'))); 
                                                            ?>
                                                        </li>
                                                        <li>
															<?php
                                                            echo CHtml::link('<span>'.Yii::t('app','Reject').'</span>', array('requestdisapprove','id'=>$material_request->id,'flag'=>1),array('class'=>'tt-disapproved','confirm'=>Yii::t('app','Are you sure you want to reject this request ?'))); 
                                                            ?>
                                                        </li>
														<?php
                                                        }
                                                        else if($material_request->status_tchr == 1 && $material_request->is_send == 0)
                                                        {
                                                        ?>
                                                        <li>
															<?php
                                                              echo CHtml::link('<span>'.Yii::t('app','Send Request').'</span>', array('sendrequest','id'=>$material_request->id),array('class'=>'tt-send-request'));
                                                              ?>
                                                        </li>
                                                      <?php
                                                    }
													else if($material_request->is_send == 1)
													{
														?>
                                                        <div class="request-sent"><?php
														 echo '<p>'.Yii::t('app','Request Sent').'</p>';?></div>
                                                         <?php
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
                         <div  class="pagination-block">
        <div class="dataTables_paginate paging_full_numbers">
			<?php 
              $this->widget('CLinkPager', array(
              'currentPage'=>$pages->getCurrentPage(),
              'itemCount'=>$item_count,
              'pageSize'=>$page_size,
              'maxButtonCount'=>5,
              'prevPageLabel'=>'< Prev',                              
              'prevPageLabel'=>'< Prev',
              'header'=>'',
            'htmlOptions'=>array('class'=>'pages'),
            ));?>
        </div> <!-- End div class="pagecon" -->
        </div>	                 
        </td>
    </tr>
</table>
      </div>  
	</div>




