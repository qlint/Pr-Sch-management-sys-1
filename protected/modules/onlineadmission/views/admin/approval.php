<style type="text/css">

.a_feed_cntnr{
	margin-top:20px !important;
}
	 
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Students')=>array('/students'),
	Yii::t('app','Student Approval'),
);
?>
<script type="text/javascript">
	function details(id)
	{	
		var rr = document.getElementById("dropwin"+id).style.display;	
		if(document.getElementById("dropwin"+id).style.display=="block"){
			document.getElementById("dropwin"+id).style.display="none"; 
		}
		if(document.getElementById("dropwin"+id).style.display=="none"){
			document.getElementById("dropwin"+id).style.display="block"; 
		}	 
	}
	
	function hide(id)
	{
		$(".drop_search").hide();
		$('#'+id).toggle();	
	}
	
	function MM_jumpMenu(targ,selObj,restore){ //v3.0
	  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	  if (restore) selObj.selectedIndex=0;
	}
</script>
                           
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top"><?php $this->renderPartial('/default/left_side');?></td>
        <td valign="top">
        	<div class="cont_right formWrapper">
            		<h1><?php echo Yii::t('app','Online Registration Approval'); ?></h1>
                <div class="search_btnbx">

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
    <li><?php echo CHtml::link('<span>'.Yii::t('app','Clear All Filters').'</span>', array('approval'), array('class'=>'a_tag-btn')); ?></li>
    <li><?php echo CHtml::link('<span>'.Yii::t('app','Manage Waiting List').'</span>', array('WaitinglistStudents/list'), array('class'=>'a_tag-btn')); ?></li>                                  
</ul>
</div> 
</div>
                    <!-- Filter Start -->
                    <div class="filtercontner">
                    	<div class="filterbxcntnt">                    	
                        	<div class="filterbxcntnt_inner" style="border-bottom:#ddd solid 1px;">
                            	<ul>
                                	<li style="font-size:12px"><?php echo Yii::t('app','Filter Your Students:');?></li>
                                    <?php $form=$this->beginWidget('CActiveForm', array('method'=>'get')); ?>
                                    	<!-- Name Filter -->
                                        <li>
                                            <div onclick="hide('name')" style="cursor:pointer;"><?php echo Yii::t('app','Name');?></div>
                                            <div id="name" style="display:none; width:230px;" class="drop_search">
                                                <div class="droparrow" style="left:10px;"></div>
                                                <div class="filter_ul">
                                                	<ul>
                                                    <li class="Text_area_Box"> <input type="search" placeholder="<?php echo Yii::t('app','search');?>" name="name" value="<?php echo isset($_GET['name']) ? CHtml::encode($_GET['name']) : '' ; ?>" /> </li>
                                                    <li class="Btn_area_Box"><input type="submit" value="<?php echo Yii::t('app','Apply');?>" /> </li>
                                                    </ul>
                                                    </div>
                                                </div>
                                        </li>
                                        
                                        <!-- Admission Number Filter -->
                                        <li>
                                            <div onclick="hide('registrationnumber')" style="cursor:pointer;"><?php echo Yii::t('app','Application Id');?></div>
                                            <div id="registrationnumber" style="display:none;width:230px;" class="drop_search">
                                                <div class="droparrow" style="left:10px;"></div>
                                                 <div class="filter_ul">
                                                	<ul>
                                                    <li class="Text_area_Box"><input type="search" placeholder="<?php echo Yii::t('app','search');?>" name="registrationnumber" value="<?php echo isset($_GET['registrationnumber']) ? CHtml::encode($_GET['registrationnumber']) : '' ; ?>" /> </li>
                                                    <li class="Btn_area_Box"> <input type="submit" value="<?php echo Yii::t('app','Apply');?>" /> </li>
                                                    </ul>
                                                    </div>
                                            </div>
                                        </li>
                                        
                                        <!-- Batch Filter -->
                                        <li>
                                            <div onclick="hide('batch')" style="cursor:pointer;">
<?php											
												if(FormFields::model()->isVisible('batch_id','Students','forOnlineRegistration')){ 
													echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");
												}
?>										
                                        	</div>
                                            <div id="batch" style="display:none; color:#000; width:520px;  left:-200px" class="drop_search">
                                                <div class="droparrow" style="left:210px;"></div>
                                                    <div class="filter_ul">
                                                        <ul>
                                                            <li class="Text_area_Box-two">
                                                            <?php
																$is_active 				= PreviousYearSettings::model()->findByAttributes(array('id'=>7));
																$is_inactive 			= PreviousYearSettings::model()->findByAttributes(array('id'=>8));
																$data 					= CHtml::listData(Courses::model()->findAll('is_deleted=:x AND academic_yr_id=:y',array(':x'=>'0',':y'=>Yii::app()->user->year),array('order'=>'course_name DESC')),'id','course_name');
																echo Yii::t('app','Course');
																echo CHtml::dropDownList('cid','',$data,
																array('prompt'=>Yii::t('app','Select'),
																    'encode'=>false,
																	'ajax' => array(
																	'type'=>'POST',
																	'url'=>CController::createUrl('/onlineadmission/admin/batch'),
																	'update'=>'#batch_id',
																	'data'=>array('cid'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken)
																))); 
															?>
                                                            </li>
                                                            <li class="Text_area_Box-two">
                                                            <?php
																echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");
																$data1 = CHtml::listData(Batches::model()->findAll('is_active=:x AND is_deleted=:y AND academic_yr_id=:z',array(':x'=>'1',':y'=>0,':z'=>Yii::app()->user->year),array('order'=>'name DESC')),'id','name');
																echo CHtml::activeDropDownList($model,'batch_id',$data1,array('encode'=>false,'prompt'=>Yii::t('app','Select'),'id'=>'batch_id')); 
															?>
                                                            </li>   
                                                            <li class="Btn_area_Box">
                                                            <br />
                                                              <input type="submit" value="<?php echo Yii::t('app','Apply');?>" />
                                                            </li>
                                                        </ul>
                                                    </div>
                                                        
                                                        

                                            </div>
                                        </li>
                                                                                
                                    <?php $this->endWidget(); ?>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                            <div class="filterbxcntnt_inner_bot">
                                <div class="filterbxcntnt_left"><strong><?php echo Yii::t('app','Active Filters:');?></strong></div>
                                <div class="clear"></div>
                                <div class="filterbxcntnt_right">
                                    <ul>
                                        <!-- Name Active Filter -->
    <?php									 
                                        if(isset($_REQUEST['name']) and $_REQUEST['name']!=NULL){
                                            $j++; 
    ?>									
                                            <li><?php echo Yii::t('app','Name'); ?> : <?php echo $_REQUEST['name']?><a href="<?php echo Yii::app()->request->getUrl().'&name='?>"></a></li>
    <?php                                     
                                        }
    ?>									
                                        <!-- END Name Active Filter --> 
                                         
                                        <!-- END Application id -->    
<?php                                     
                                        if(isset($_REQUEST['registrationnumber']) and $_REQUEST['registrationnumber']!=NULL){ 
                                            $j++; 
?>									
                                            <li><?php echo Yii::t('app','Application Id'); ?> : <?php echo $_REQUEST['registrationnumber']?><a href="<?php echo Yii::app()->request->getUrl().'&registrationnumber='?>"></a></li>								
<?php									 
                                        }
?>                                                                                                                   
                                        
                                        <!-- END Application id -->                                                                                  
                                         
                                         <!-- Batch Active Filter -->
										<?php 
                                        if(isset($_REQUEST['Students']['batch_id']) and $_REQUEST['Students']['batch_id']!=NULL)
                                        { 
                                            $j++;
                                        ?>
                                            <li><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?> : <?php echo Batches::model()->findByAttributes(array('id'=>$_REQUEST['Students']['batch_id']))->name?><a href="<?php echo Yii::app()->request->getUrl().'&Students[batch_id]='?>"></a></li>
                                        <?php 
                                        }
                                        ?>
                                        <!-- END Batch Active Filter -->
                                                                                 
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>  
                    <!-- Filter End -->   
                    <div class="clear"></div>
                    
                    <!-- Alphabetic Sort Start -->   
                    <?php $this->widget('application.extensions.letterFilter.LetterFilter', array(
						//parameters
						'outerWrapperClass'=>'list_contner_hdng',
						'innerWrapperId'=>'letterNavCon',
						'innerWrapperClass'=>'letterNavCon',
						'activeClass'=>'ln_active',
					)); ?>    
                        	
                    <!-- Alphabetic Sort End -->  
                    
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
					if(Yii::app()->user->hasFlash('successMessage')): 
					?>
						<div class="flashMessage" style="background:#FFF; color:#689569; padding-left:220px; font-size:13px">
						<?php echo Yii::app()->user->getFlash('successMessage'); ?>
						</div>
					<?php endif; ?>
                    
                    <div class="a_feed_cntnr" id="a_feed_cntnr">
<?php						
						//Display the selected academic yr	
						$academic_yr = AcademicYears::model()->findByAttributes(array('id'=>Yii::app()->user->year));										
						if($academic_yr){
?>							
							<center><div class="online_academic_yr"><?php echo Yii::t('app','Academic Year').' - '.ucfirst($academic_yr->name); ?></div></center>
<?php							
						}	
						if($students){							
							$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
							foreach($students as $student){
								if($student->gender == 'M'){
									$gender_class = "a_boy";
								}
								else{
									$gender_class = "a_girl";
								}
								
								if($settings!=NULL){	
									$student->registration_date = date($settings->displaydate,strtotime($student->registration_date));
									$timezone 					= Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
									date_default_timezone_set($timezone->timezone);
									$time 	= date($settings->timeformat,strtotime($student->created_at));								
								}
?>
								<div class="individual_feed">
									<div class="a_feed_online">
										<div class=<?php echo $gender_class; ?>></div> 
                                        <div class="a_feed_innercntnt">
                                        	<div class="a_feed_inner_arrow"></div>
                                            <div class="onln-adm-list">
                                            	<div class="onln-adm-name">
                                                    <h1><strong>                                            	
                                                        <?php
                                                            if(FormFields::model()->isVisible("fullname", "Students", "forOnlineRegistration")){
                                                                if($student->studentFullName('forOnlineRegistration')!=''){
                                                                    $std_name = $student->studentFullName('forOnlineRegistration');
                                                                }else{
                                                                    echo '-';
                                                                }
                                                            }else{
                                                                $std_name = '-';
                                                            }
                                                            echo CHtml::link($std_name, array('view', 'id'=>$student->id)); 
                                                        ?>
                                                        </strong>
                                                    </h1>
                                            	</div>
                                                <div class="onln-adm-date">
                                                	<p><?php echo Yii::t('app','at'); ?> <strong><?php echo $time; ?></strong>  - <strong><?php echo $student->registration_date; ?></strong></p>
                                                </div>
                                            </div>
                                            <div class="onln-adm-list">
                                                <div class="onln-adm-table">
                                                	<table class="reg_bx" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td width="30%"><p><?php echo Yii::t('app','App ID'); ?></p></td>
                                                            <td width="8">:</td>
                                                            <td><?php echo $student->registration_id; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><p><?php echo Yii::t('app','Email'); ?></p></td>
                                                            <td>:</td>
                                                            <td><?php echo $student->email; ?></td>
                                                        </tr>
                                                        <?php if(FormFields::model()->isVisible('phone1','Students','forOnlineRegistration')){ ?>
                                                            <tr>
                                                                <td><p><?php echo Yii::t('app','Phone'); ?></p></td>
                                                                <td>:</td>
                                                                <td><?php echo $student->phone1; ?></td>                                                            
                                                            </tr>
                                                        <?php } ?>
                                                        <?php if($student->status == 0 and $student->batch_id and FormFields::model()->isVisible('batch_id','Students','forOnlineRegistration')) { ?>
                                                            <tr>
                                                                <td><p><?php echo Yii::app()->getModule("students")->labelCourseBatch(); ?></p></td>
                                                                <td>:</td>
    <?php                                                                                                                         
                                                                $batc = Batches::model()->findByAttributes(array('id'=>$student->batch_id));                                                             
                                                                if($batc != NULL){
                                                                    $cours = Courses::model()->findByAttributes(array('id'=>$batc->course_id)); ?>
                                                                    <td><?php echo $cours->course_name.' / '.$batc->name; ?></td>                                                             
    <?php                                                             
                                                                }
    ?>                                                                                                                                                            
                                                            </tr>
                                                        <?php } ?>                                                    
                                                    </table>
                                                </div>
                                                <div class="onln-adm-table-icon">
                                                    <div class="online_time onln-adm-stus">
                                                    	<?php
															$status_data	= "";
															// Setting class for status label
															if($student->status == -1){
																$status_class 	= 'tag_disapproved';
																$status_data 	= Yii::t('app','Disapproved');
															}
															elseif($student->status == 0){
																$status_class 	= 'tag_pending';
																$status_data 	= Yii::t('app','Pending');
															}
															elseif($student->status == 1){
																$status_class 	= 'tag_approved';
																$status_data 	= Yii::t('app','Approved');
															}
															elseif($student->status == -3){
																$status_class 	= 'tag_waiting';
																$status_data 	= Yii::t('app','Waiting List');
															}
			?>                                               
															<div class="online_status"><div class="<?php echo $status_class; ?>"><?php echo $status_data; ?></div></div>
                                                    </div>
                                                    <div class="online_but onln-adm-stus">
                                                    	<div class="online_but">
                                                            <ul class="tt-wrapper">
                                                                <li>
																<?php                                                        	
                                                                    if($student->status == 1){ 
                                                                        echo CHtml::link('<span>'.Yii::t('app','Approved').'</span>', array('#'),array('class'=>'tt-approved-disabled','onclick'=>'return false;'));
                                                                    }
                                                                    else{																
                                                                        echo CHtml::ajaxLink(
																			'<span>'.Yii::t('app','Approve').'</span>',
																			$this->createUrl('admin/approve'),
																			array(
                                                                            	'onclick'=>'$("#jobDialog'.$student->id.'").dialog("open"); return false;',
                                                                            	//'update'=>'#jobDialog123'.$student->id,
																				'dataType'=>'json',
																				'success'=>'js:function(data){
																					if(data.status=="success"){
																						$("#jobDialog123'.$student->id.'").html(data.content);
																					}
																				}',
																				'error'=>'js:function(){
																					alert("'.Yii::t("app", "Some problem found").'!");
																					window.location.reload();
																				}',
																				'type' =>'GET',
																				'data'=>array(
																					'id' =>$student->id,
																					'bid'=>$student->batch_id
																				),
																			),
																			array(
																				'id'=>'showJobDialog'.$student->id,
																				'class'=>'tt-approved'
																			)
																		);                                                                        
                                                                    }
																?>													
                                                                </li>
                                                                
                                                                <li>
        <?php
                                                                    if($student->status == -1){																
                                                                        echo CHtml::link('<span>'.Yii::t('app','Disapproved').'</span>', array('#'),array('class'=>'tt-disapproved-disabled','onclick'=>'return false;')); 
                                                                    }
                                                                    else{
                                                                        echo CHtml::link('<span>'.Yii::t('app','Disapprove').'</span>', array('disapprove','id'=>$student->id),array('class'=>'tt-disapproved','confirm'=>Yii::t('app','Are you sure you want to disapprove this?'))); 
                                                                    }
        ?>                                                        	
                                                                </li>
                                                                
                                                                <li>
                                                                    <?php echo CHtml::link('<span>'.Yii::t('app','Delete').'</span>', "#", array('submit'=>array('delete','id'=>$student->id), 'class'=>'tt-delete','confirm'=>Yii::t('app','Are you sure you want to delete this?'), 'csrf'=>true)); ?>
                                                                </li>
                                                                
                                                                <li>
                                                                    <?php echo CHtml::link('<span>'.Yii::t('app','Waiting List').'</span>', array('WaitinglistStudents/create','id'=>$student->id),array('class'=>'tt-waiting',)); ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>    
                                            </div>
                                            
                                        </div>                                   	
                                    </div>
                                </div>    
                                <div id="<?php echo 'jobDialog123'.$student->id;?>"></div>
<?php								
							}
?>
                            <div class="pagecon">
<?php								                                                              
                                $this->widget('CLinkPager', array(
									'currentPage'=>$pages->getCurrentPage(),
									'itemCount'=>$item_count,
									'pageSize'=>10,
									'maxButtonCount'=>5,                                
									'header'=>'',
									'htmlOptions'=>array('class'=>'pages',"style"=>"margin:0px;"),
                                ));
?>								
                            </div>
<?php							
						}
						else{
?>
							<div>
                                <div class="yellow_bx" style="background-image:none;width:600px;padding-bottom:45px;">
                                    <div class="y_bx_head" style="width:580px;">
                                    <?php echo Yii::t('app','No Online Applicants for Approval'); ?>
                                    </div>
                                   
                                </div>
                            </div>
<?php						
						}
?>                    	
                    </div>
                    
                </div>
            </div>
        </td>
    </tr>
</table>        
<script type="text/javascript">
$('body').click(function() {
	$('#osload').hide();
	$('#name').hide();
	$('#registrationnumber').hide();	
	$('#batch').hide();
	
});

$('.filterbxcntnt_inner').click(function(event){
   event.stopPropagation();
});

$('.load_filter').click(function(event){
   event.stopPropagation();
});
</script>        