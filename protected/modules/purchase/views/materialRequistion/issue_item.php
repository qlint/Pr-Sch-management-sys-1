<style type="text/css">
.list_contner_hdng{ margin:8px;}
.max_student{ border-left: 3px solid #fff;
    margin: 0 3px;
    padding: 6px 0 6px 3px;
    word-break: break-all;}
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Purchase')=>array('/purchase'),
	Yii::t('app','Issue Items'),
);

?>
<div id="jobDialog"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/leftside');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Issue Items'); ?></h1>
                
                    <div class="a_feed_cntnr" id="a_feed_cntnr">
<?php
	Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);
				if(Yii::app()->user->hasFlash('successMessage')): 
?>
				<div class="flashMessage" style="color:#C00; padding-left:220px; font-size:13px">
					<?php echo Yii::app()->user->getFlash('successMessage'); ?>
				</div>
<?php endif; ?>
                    
                    	<?php
						if(isset($_REQUEST['page'])){
								$i=($pages->pageSize*$_REQUEST['page'])-9;
							}
							else{
								$i=1;
							}
							
						if($issue_items)
						{
							
							foreach($issue_items as $issue_item)
							{
							
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
                                                            $item = PurchaseItems::model()->findByAttributes(array('id'=>$issue_item->material_id)); 
                                                            echo Yii::t('app','Item Name').' : '.$item->name; 
                                                            ?>
                                                        </strong>
                                                    </h1>
                                                </div>
                                                <div class="onln-adm-date"></div>
                                            </div>
                                            <div class="onln-adm-list">
                                                <div class="onln-adm-table">
                                                     <table class="reg_bx" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td width="15%"><p><?php echo Yii::t('app','Department'); ?></p></td>
                                                            <td>:</td>
                                                      <?php $department = EmployeeDepartments::model()->findByAttributes(array('id'=>$issue_item->department_id));  ?>
                                                            <td><?php echo $department->name; ?></td>
                                                        </tr>
                                                         <tr>
                                                            <td width="28%"><p><?php echo Yii::t('app','Requested By'); ?></p></td>
                                                            <td>:</td>
                                                      <?php $employee = Employees::model()->findByAttributes(array('uid'=>$issue_item->employee_id));  ?>
                                                            <?php $employee = Employees::model()->findByAttributes(array('uid'=>$issue_item->employee_id));  ?>
                                                            <td><?php if($employee!=NULL){
                                                                            echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;
                                                                       }
                                                                       else{
                                                                           $user = Profile::model()->findByAttributes(array('user_id'=>$issue_item->employee_id));
                                                                           echo $user->firstname.' '.$user->lastname;
                                                                       }?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><p><?php echo Yii::t('app','Quantity'); ?></p></td>
                                                            <td>:</td>
                                                            <td><?php echo $issue_item->quantity; ?></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                
                                                <div class="onln-adm-table-icon">
                                                        <div class="online_time onln-adm-stus">								
                                                            <div class="online_time">
                                                                    <?php
                                                                    if($issue_item->is_issued == 0)
                                                                    {
                                                                    $status_class = 'tag_disapproved';
                                                                    $status_data = Yii::t('app','Not Issued');
                                                                    }
                                                                    
                                                                    else if($issue_item->is_issued == 1)
                                                                    {
                                                                    $status_class = 'tag_approved';
                                                                    $status_data = Yii::t('app','Issued');
                                                                    }
																	else if($issue_item->is_issued == 2)
                                                                    {
																		$status_class = 'tag_return';
																		 $status_data = Yii::t('app','Returned');
																	}
                                                                    ?>
                                                            <div class="online_status tooltip-posctn" >
                                                            <?php
															if($issue_item->is_issued == 2)
															{
																$settings = UserSettings::model()->findByAttributes(array('user_id'=>1));
																if($settings!=NULL){
																	$displaydate	= $settings->displaydate;	
																}else{
																	$displaydate	= 'd M Y'; 
															}
																?>
                                                                    <div class="tiiltip-block">

                                                                        
                                                                        
                                                                        <span>
                                                                        
                                                                        <table width="100%" class="tooltip-table">
                                                                        	<tbody>
                                                                            	<tr>
                                                                                	<td>
																					<?php 
																						echo '<p>';
																						echo '<label>'.Yii::t('app','Date')." : ".'</label>';
																						echo '</p>';
																					?>
                                                                                    </td>
                                                                                    <td>
																					<?php
																						echo '<p>';																					echo '<p>';
																						echo date($displaydate,strtotime($issue_item->return_date));
																						echo '</p>';  
																					?>
                                                                                     </td>
                                                                                </tr>
                                                                                 <tr>
                                                                                	<td width="62px;">
																					<?php
																						echo '<p>';																					echo '<p>';
																							echo '<label>'.Yii::t('app','Reason')." : ".'</label>';
																						echo '</p>';  
																					?>                                                                                    
                                                                                    
                                                                                    
                                                                                    </td>
                                                                                    <td>
																					<?php
																						echo '<p>';																					echo '<p>';
																						echo $issue_item->return_reason ;
																						echo '</p>';  
																					?>                                                                                        
                                                                                    
                                                                                    </td>
                                                                                </tr> 
                                                                            </tbody>
                                                                        </table>
																		
                                                                        	<?php /*?><?php 
																			
																			echo '<p>';
																				echo '<label>'.Yii::t('app','Date')." : ".'</label>';
																				echo $issue_item->return_date ;
																			echo '</p>';
																			
																			echo '<p>';
																				echo '<label>'.Yii::t('app','Reason')." : ".'</label>';
																				echo $issue_item->return_reason ;
																			echo '</p>';
																			?><?php */?>
                                                                        </span>
                                                                    </div>
                                                                <?php
															}
															?>
                                                            <div class="<?php echo $status_class; ?>"><?php echo $status_data; ?></div>
                                                            
                                                            </div>	
                                                            </div>
                                                            </div>      
                                                        <div class="online_but onln-adm-stus">
                                                            <ul class="tt-wrapper">
                                                                <li>
                                                                <?php
                                                                if($issue_item->is_issued == 0)
                                                                { 
                                                                echo CHtml::link('<span>'.Yii::t('app','Issue').'</span>', array('issueitem','id'=>$issue_item->id),array('class'=>'tt-approved','onclick'=>'return issue()','confirm'=>Yii::t('app','Are you sure you want to issue this item ?'))); 
                                                                }
																elseif($issue_item->is_issued == 1)
																{
																	?>
                                                                     <li>
																		<?php echo CHtml::ajaxLink('<span>'.Yii::t('app','Return').'</span>',
                                                                            $this->createUrl('/purchase/materialRequistion/retrunitem'),
                                                                            array(
                                                                                'onclick'=>'$("#jobDialog").dialog("open"); return false;',
                                                                                'update'=>'#jobDialog',
                                                                                'type'=>'GET',
                                                                                'data' =>array(
                                                                                    'id' =>$issue_item->id
                                                                                ),
                                                                                'dataType'=>'text'
                                                                            ),
                                                                            array(
                                                                                'class'=>'tt-return',
                                                                               // 'title'=>Yii::t('app','Return'),
																				'id'=>'return_'.$issue_item->id
                                                                            )
                                                                        );?>
                                                                    </li>
                                                                    <?php
																}
																
                                                                ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>  

                                            </div> <!-- END div class="a_feed_innercntnt" -->
                                          </div> <!-- END div class="a_feed_online" -->
                                        </div> <!-- END div class="individual_feed" -->
                                            <?php
									$i++;
                               } // end foreach
							} //end if
							else
							{
							?>
								<div>
								<div class="yellow_bx" style="background-image:none;width:600px;padding-bottom:45px;">
									<div class="y_bx_head" style="width:580px;">
									<?php 
										echo Yii::t('app','No Items for Issue');
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


         
