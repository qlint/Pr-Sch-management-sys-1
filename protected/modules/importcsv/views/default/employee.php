<?php
$this->breadcrumbs=array(
Yii::t('app','Import CSV')=>array('/importcsv'),
Yii::t('app','Teacher Users'),
);


?>
<?php $form=$this->beginWidget('CActiveForm', array(
		'method'=>'post',
		)); ?>
        
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="247" valign="top">
            <?php $this->renderPartial('/default/left_side');?>
            </td>
            <td valign="top">
            
                <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app','Create Users'); ?></h1>
                <!-- Flash Message -->
                <?php
					Yii::app()->clientScript->registerScript(
					'myHideEffect',
					'$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',
					CClientScript::POS_READY
					);
                ?>
                <?php
					/* Success Message */
					if(Yii::app()->user->hasFlash('success')): 
					?>
					<div class="flashMessage" style="background:#FFF; color:#C00; padding-left:220px; font-size:13px">
					<?php echo Yii::app()->user->getFlash('success'); ?>
					</div>
					<?php endif; ?>
                    <div class="clear"></div>
                    <div class="emp_right_contner">
                        <div class="emp_tabwrapper">
                            
                            <?php $this->renderPartial('/default/emp_tab_nav');?>
                           
                        	<div class="clear"></div>
                            <br />
                            <div class="pagecon" style="height:17px;">
								<?php     							                                 
                                  $this->widget('CLinkPager', array(
                                  'currentPage'=>$pages->getCurrentPage(),
                                  'itemCount'=>$item_count,
                                  'pageSize'=>50,
                                  'maxButtonCount'=>5,
                                  //'nextPageLabel'=>'My text >',
                                  'header'=>'',
                                'htmlOptions'=>array('class'=>'pages',"style"=>"margin:0px;"),
                                ));?>
                            </div>
                            <div class="emp_cntntbx" >
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
                                $is_create = PreviousYearSettings::model()->findByAttributes(array('id'=>1));
                                ?>
                                <?php
                                //$employeelist = Employees::model()->findAll(array('condition'=>'uid=:x and is_deleted=:y','params'=>array(':x'=>0,':y'=>0),'order'=>'last_name ASC'));
                                ?>
                                <?php
                                if($year != $current_academic_yr->config_value and $is_create->settings_value==0 and count($employeelist)!=0)
                                {
                                ?>
                                    <div>
                                        <div class="yellow_bx" style="background-image:none;width:690px;padding-bottom:45px;">
                                            <div class="y_bx_head" style="width:650px;">
												<?php 
                                                echo Yii::t('app','You are not viewing the current active year. ');
                                                echo Yii::t('app','To create employee users, enable the Create option in Previous Academic Year Settings.');	
                                                
                                                
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
                                <div align="center" style="font-size:16px; font-style:bold; padding:10px 0px;">
                                	<!-- Create User Button Only-->
									<?php /*?><?php 
                                    if(count($employeelist)!=0)
                                    {
                                    	echo Yii::t('app','You have not created user accounts for ').count($employeelist).' employees ';
										if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
										{
											echo CHtml::submitButton(Yii::t('app','Create Now'),array('confirm'=>'Are you sure?','name'=>'employeeuser','value'=>'Create Now','class'=>'formbut'));
										}
                                    }
                                    else 
                                    { 
                                   	 echo Yii::t('app','No data available'); 
                                    }?><?php */?>
                                    <!-- Create User Button Only-->
                                    
                                     <!-- Parents List -->
                                     
                                    <div class="tablebx">
                                    
                                      <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                      <tbody>
                                          <tr class="tablebx_topbg">
                                            <td><?php echo Yii::t('app','Sl. No.');?></td>
                                            <td> 
                                                <?php 
                                                if($employeelist)
                                                {
                                                    echo CHtml::CheckBox('all_employees','',array('value'=>0)); 
                                                }
                                                else
                                                {
                                                    echo CHtml::CheckBox('all_employees','',array('value'=>0,'disabled'=>'disabled')); 
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo Yii::t('app','Teacher Name');?> </td>
                                            <td><?php echo Yii::t('app','Email');?> </td>
                                            <?php /*?><td><?php echo Yii::t('app','Personal Email');?> </td>
                                            <td><?php echo Yii::t('app','Status');?> </td><?php */?>
                                            
                                            <!--<td style="border-right:none;">Task</td>--> 
                                          </tr>
											<?php
                                            if(isset($_REQUEST['page']))
											{
												$i=($pages->pageSize*$_REQUEST['page'])-49;
											}
											else
											{
												$i=1;
											}
                                            $cls = 'even';
                                            if($employeelist)
                                            {
                                                foreach($employeelist as $employee)
                                                {
                                                ?>
                                                    <tr id="1" class="<?php echo $cls; ?>">
                                                        <td><?php echo $i; ?></td>
                                                        <td>
                                                            <?php
                                                            if($employee->email!=NULL)
                                                            {
                                                                echo CHtml::CheckBox('employee_user[]','',array('value'=>$employee->id,'class'=>'employee')); 
                                                            }
                                                            else
                                                            {
                                                            
                                                                echo CHtml::CheckBox('employee_user[]','',array('disabled'=>'disabled','class'=>'inactiveemployee'));
                                                                                              
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo Employees::model()->getTeachername($employee->id);?></td>
                                                        <td><?php echo $employee->email; ?></td>
                                                        <?php /*?><td><?php echo $employee->email; ?></td>
                                                        <td>
															<?php
                                                            if($employee->status==1)
                                                            {
                                                               echo 'Active';
                                                            }
                                                            else
                                                            {
                                                               echo 'Inactive';
                                                            }                                                 
                                                            ?>
                                                        </td><?php */?>
                                                    </tr>
                                                <?php
                                                    $i++;						
                                                    if($cls=="even")
                                                    {
                                                        $cls="odd" ;
                                                    }
                                                    else
                                                    {
                                                        $cls="even"; 
                                                    }
                                                }
                                            }
                                            else
                                            {
                                            ?>
                                                <tr>
                                                    <td colspan="5" style="padding:11px;"><?php echo Yii::t('app','No data available!');?></td>
                                                </tr>
                                            <?php	
                                            }
                                            ?>
                                        </tbody>
                                      </table>
                                    </div>   
                                    <!-- Parents List -->
                                </div>
                                <br />
                                <div class="pagecon" style="height:17px;">
                                    <?php     							                                 
                                      $this->widget('CLinkPager', array(
                                      'currentPage'=>$pages->getCurrentPage(),
                                      'itemCount'=>$item_count,
                                      'pageSize'=>50,
                                      'maxButtonCount'=>5,
                                      //'nextPageLabel'=>'My text >',
                                      'header'=>'',
                                    'htmlOptions'=>array('class'=>'pages',"style"=>"margin:0px;"),
                                    ));?>
                                </div>
                                <?php
							   if($employeelist)
								{
									echo CHtml::submitButton(Yii::t('app','Create Now'),array('confirm'=>Yii::t('app','Are you sure. You want to create user account?'),
																'name'=>'employeeuser','value'=>Yii::t("app",'Create Now'),'class'=>'formbut'));
								}
								?>    
                            </div>
                        </div>
                    </div>
                </div>
            
            </td>
        </tr>
    </table>
<?php $this->endWidget(); ?>
<script type="text/javascript"> 
	$(document).ready(function(){
	
	 
	 $("#all_employees").change(function(){ /* Check/Uncheck all Students */
		  if (this.checked) {
			$('.employee').attr('checked', true);
		  }
		  else{
			$('.employee').attr('checked', false);
		  }
	  });
	  
	   $(".employee").change(function(){ /* Check/Uncheck all SMS functions on enabling/disabling of SMS All */
		  if (this.checked) {
			if (!$('input.employee[type=checkbox]:not(:checked)').length){
				$('#all_employees').attr('checked', true);	
			}
		  }
		  else{
			$('#all_employees').attr('checked', false);
		  }
	  });
 }); 
 
</script>