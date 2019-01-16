<?php $this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Transport Fee Management')=>array('/transport/transportation/viewall'),
	Yii::t('app','viewall'),
);?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/transportation/trans_left');?>
        </td>
        <td valign="top">
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Transportation Fee');?></h1>
                <?php
				$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
				if(Yii::app()->user->year)
				{
					$year = Yii::app()->user->year;
				}
				else
				{
					$year = $current_academic_yr->config_value;
				}
				$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
				if($year != $current_academic_yr->config_value and $is_insert->settings_value==0)
				{
				?>
                	<div>
						<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">
							<div class="y_bx_head" style="width:650px;">
							<?php 
								echo Yii::t('app','You are not viewing the current active year. ');
								echo Yii::t('app','To collect transportation fees, enable the Insert option in Previous Academic Year Settings.');	
							?>
							</div>
							<div class="y_bx_list" style="width:650px;">
								<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
							</div>
						</div>
					</div><br />
                <?php
				}

				?>
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
                            <td align="center">
                            <?php echo Yii::t('app','Action');?>
                            </td>
                        </tr>
                        
						<?php
                        $criteria = new CDbCriteria;
                        $criteria->order = 'id DESC';
                        $total = Transportation::model()->count($criteria);
                        $pages = new CPagination($total);
                        $pages->setPageSize(Yii::app()->params['listPerPage']);
                        $pages->applyLimit($criteria);  // the trick is here!
                        $route = Transportation::model()->findAll($criteria);
                        $page_size=Yii::app()->params['listPerPage'];
                        ?>
                        <?php 
                        //$route=Transportation::model()->findAll();
                        if($route)
                        {
                            foreach($route as $route1) 
                            {
								$student=Students::model()->findByAttributes(array('id'=>$route1->student_id,'is_deleted'=>0,'is_active'=>1));
                                $stop=StopDetails::model()->findByAttributes(array('id'=>$route1->stop_id));
                                $route=RouteDetails::model()->findByAttributes(array('id'=>$stop->route_id));
                                if(isset($student) and $student!=NULL)
								{
									?>
									<tr>
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
                                        <td align="center">
											<?php
                                            if($route1->is_paid=='0')
                                            {
												if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
												{
													echo CHtml::link(Yii::t('app','Unpaid'), "#", array('submit'=>array('/transport/Transportation/Payfees','id'=>$route1->student_id,), 'confirm'=>Yii::t('app','Are you sure?'), 'csrf'=>true)); 
												}
												else
												{
													echo Yii::t('app','Not Paid');
												}
											}
											else
											{
												echo Yii::t('app','Paid');	
												echo '  |   '.CHtml::link(Yii::t('app','Print Receipt'),array('/transport/Transportation/print','id'=>$route1->student_id),array('target'=>'_blank')); 
												
                                            }
                                            
                                            ?>
                                        </td>
									</tr>
									
									<?php
								}
                            }
                        }
                        else
                        {
                            echo '<tr><td align="center" colspan="6"><strong>'.Yii::t('app','No data available.').'</strong></div>';
                        }
                        ?>
							
                    </table>
                    <div class="pagecon">
                        <?php 
                          $this->widget('CLinkPager', array(
                          'currentPage'=>$pages->getCurrentPage(),
                          'itemCount'=>$total,
                          'pageSize'=>$page_size,
                          'maxButtonCount'=>5,
                          //'nextPageLabel'=>'My text >',
                          'header'=>'',
                        'htmlOptions'=>array('class'=>'pages'),
                        ));?>
                    </div>
                </div> <!-- END div class="pdtab_Con" -->
            </div> <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>



