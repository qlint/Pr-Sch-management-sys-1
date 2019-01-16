<style type="text/css">
.max_student{ border-left: 3px solid #fff;
    margin: 0 3px;
    padding: 6px 0 6px 3px;
    word-break: break-all;}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Students')=>array('/students'),
	Yii::t('app','Waiting List'),
);?>
<script language="javascript">
function details(id)
{
	
	var rr= document.getElementById("dropwin"+id).style.display;
	
	 if(document.getElementById("dropwin"+id).style.display=="block")
	 {
		 document.getElementById("dropwin"+id).style.display="none"; 
	 }
	 if(  document.getElementById("dropwin"+id).style.display=="none")
	 {
		 document.getElementById("dropwin"+id).style.display="block"; 
	 }
	 //return false;
	

}
</script>

<script language="javascript">
function hide(id)
{
	$(".drop_search").hide();
	$('#'+id).toggle();	
}
</script>

<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/left_side');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">                       
            		<h1><?php echo Yii::t('app','Manage Waiting List'); ?></h1> 

                <div class="search_btnbx">
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
 <li><?php echo CHtml::link('<span>'.Yii::t('app','Clear All Filters').'</span>', array('list'), array('class'=>'a_tag-btn')); ?> </li>                                  
</ul>
</div> 

</div>           
                  
                <!-- Filters Box -->
                <div class="filtercontner">
                    <div class="filterbxcntnt">
                    	<!-- Filter List -->
                        <div class="filterbxcntnt_inner" style="border-bottom:#ddd solid 1px;">
                            <ul>
                                <li style="font-size:12px"><?php echo Yii::t('app','Filter Your Students:');?></li>
                                
                                <?php $form=$this->beginWidget('CActiveForm', array(
                                'method'=>'get',
                                )); ?>
                                
                                <!-- Name Filter -->
                                <li>
                                    <div onClick="hide('name')" style="cursor:pointer;"><?php echo Yii::t('app','Name');?></div>
                                    <div id="name" style="display:none; width:226px; " class="drop_search" >
                                        <div class="droparrow" style="left:10px;"></div>
                                            <div class="filter_ul">
                                            <ul>
                                                <li class="Text_area_Box"> 
                                                <input type="search" placeholder="<?php echo Yii::t('app','search'); ?>" name="name" value="<?php echo isset($_GET['name']) ? CHtml::encode($_GET['name']) : '' ; ?>" />
                                                </li>
                                                <li class="Btn_area_Box">
                                                <input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" />
                                                </li>
                                            </ul>
                                            </div>
                                        
                                        
                                    </div>
                                </li>
                                 <!-- End Name Filter -->
                                <!-- Admission Number Filter -->
                                <li>
                                    <div onClick="hide('priority')" style="cursor:pointer;"><?php echo Yii::t('app','Priority');?></div>
                                    <div id="priority" style="display:none;width:230px;" class="drop_search">
                                        <div class="droparrow" style="left:10px;"></div>
                                        <div class="filter_ul">
                                        <ul>
                                            <li class="Text_area_Box"> 
                                           <input type="search" placeholder="<?php echo Yii::t('app','search'); ?>" name="priority" value="<?php echo isset($_GET['priority']) ? CHtml::encode($_GET['priority']) : '' ; ?>" />   
                                            </li>
                                            <li class="Btn_area_Box">
                                             <input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" />
                                            </li>
                                        </ul>
                                        </div>
                                      
                                       
                                    </div>
                                </li>
                                <!-- End Admission Number Filter --> 
                                <!-- Batch Filter -->
                                <li>
                                    <div onClick="hide('batch')" style="cursor:pointer;"><?php if(FormFields::model()->isVisible('batch_id','Students','forOnlineRegistration')){
										echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");
									}?></div>
                                    <div id="batch" style="display:none; color:#000; width:518px;left:-200px" class="drop_search">
                                        <div class="droparrow" style="left:210px;"></div>
                                        
                                        <div class="filter_ul">
                                        <ul>
                                            <li class="Text_area_Box-two">
                                                <?php
                                                    $is_active = PreviousYearSettings::model()->findByAttributes(array('id'=>7));
                                                    $is_inactive = PreviousYearSettings::model()->findByAttributes(array('id'=>8));
                                                    $data = CHtml::listData(Courses::model()->findAll('is_deleted=:x AND academic_yr_id=:y',array(':x'=>'0',':y'=>Yii::app()->user->year),array('order'=>'course_name DESC')),'id','course_name');
                                                        echo Yii::t('app','Course');
                                                        echo CHtml::dropDownList('cid','',$data,
                                                        array('prompt'=>Yii::t('app','Select'),
                                                        'ajax' => array(
                                                        'type'=>'POST',
                                                        'url'=>CController::createUrl('/onlineadmission/waitinglistStudents/batch'),
                                                        'update'=>'#batch_id',
                                                        'data'=>array('cid'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken)
                                                        ))); 
                                        ?>
                                            </li>
                                            <li class="Text_area_Box-two">
                                                <?php
                                                    echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");
                                                    $data1 = CHtml::listData(Batches::model()->findAll('is_active=:x AND is_deleted=:y AND academic_yr_id=:z',array(':x'=>'1',':y'=>0,':z'=>Yii::app()->user->year),array('order'=>'name DESC')),'id','name');
                                                    echo CHtml::activeDropDownList($model,'batch_id',$data1,array('prompt'=>Yii::t('app','Select'),'id'=>'batch_id'));
                                                ?>
                                            </li>
                                            <li class="Btn_area_Box">
                                            <br />
                                                <input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" />
                                            </li>
                                        
                                        </ul>
                                        </div>

                                    </div>
                                </li>
                                <!-- END Batch Filter -->
                                                            
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
                                    if(isset($_REQUEST['priority']) and $_REQUEST['priority']!=NULL){ 
                                        $j++; 
?>									
                                        <li><?php echo Yii::t('app','Priority'); ?> : <?php echo $_REQUEST['priority']?><a href="<?php echo Yii::app()->request->getUrl().'&priority='?>"></a></li>								
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
                   </div><!-- Filter Box Ends --> 
                   
                   <!-- END Filter Box -->
                <div class="clear"></div>
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
						<div class="flashMessage" style="background:#FFF; color:#689569; padding-left:200px; font-size:16px">
						<?php echo Yii::app()->user->getFlash('successMessage'); ?>
						</div>
					<?php endif;
					 /* End Success Message */
					?>                                                                
			<div class="tableinnerlist">
            	<?php if($waitingListStudents!=NULL)
					{
				?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr> 
                        	<?php
								if(FormFields::model()->isVisible("fullname", "Students", "forOnlineRegistration")){						
							?>                       	
                            	<th width="25%"><?php echo Yii::t('app','Name');?></th>
                            <?php } ?>
                            <?php if(FormFields::model()->isVisible('batch_id','Students','forOnlineRegistration')){?>    
                            	<th width="25%"><?php echo Yii::app()->getModule("students")->labelCourseBatch();?></th>
                            <?php } ?>    
                            <th width="15%"><?php echo Yii::t('app','Priority');?></th>   
                            
                            <th width="45%" colspan="3"><?php echo Yii::t('app','Actions');?></th>
                                                               
                        </tr>                         
					<?php 					
                        foreach($waitingListStudents as $list)
                        {
							$studentslist = Students::model()->FindByAttributes(array('id'=>$list->student_id));
							$batch = Batches::model()->FindByAttributes(array('id'=>$list->batch_id));	
                    ?>		
                        <tr>
                        	<?php
								if(FormFields::model()->isVisible("fullname", "Students", "forOnlineRegistration")){						
							?>
                        		<td><?php echo CHtml::link($studentslist->studentFullName('forOnlineRegistration'), array('//onlineadmission/admin/view', 'id'=>$studentslist->id)); ?></td>
                            <?php } ?>    
                        	<?php if(FormFields::model()->isVisible('batch_id','Students','forOnlineRegistration')){?>  
                            	<td><?php echo $batch->course123->course_name.' / '.$batch->name; ?></td>
                            <?php } ?>    
                            <td><?php echo $list->priority; ?></td>
                           <td><?php echo CHtml::link(Yii::t('app','Edit'), array('waitinglistStudents/manage','id'=>$list->student_id)).' | '.CHtml::link('<span>'.Yii::t('app','Remove').'</span>', "#", array('submit'=>array('waitinglistStudents/delete','id'=>$list->student_id,), 'class'=>'tt-delete','confirm'=>Yii::t('app','Are you sure you want to delete this?'), 'csrf'=>true)); ?></td>
                            <td>
								<?php
                                	echo CHtml::ajaxLink(
										'<span>'.Yii::t('app','Approve').'</span>',
										$this->createUrl('admin/approve'),
										array(
											'onclick'=>'$("#jobDialog'.$studentslist->id.'").dialog("open"); return false;',
											'dataType'=>'json',
											'success'=>'js:function(data){
												if(data.status=="success"){
													$("#jobDialog123'.$studentslist->id.'").html(data.content);
												}
											}',
											'error'=>'js:function(){
												alert("'.Yii::t("app", "Some problem found").'!");
												window.location.reload();
											}',
											'type' =>'GET',
											'data'=>array(
												'id' =>$studentslist->id,
												'bid'=>$list->batch_id
											),
										),
										array(
											'id'=>'showJobDialog'.$studentslist->id,
											'class'=>'tt-approved'
										)
									);
								?>
                        	</td>
                         <?php /*?><td><?php echo CHtml::link(Yii::t('registration','Make Pending'), array('waitinglistStudents/makepending','id'=>$list->student_id)); ?></td><?php */?>
                        </tr>
                       <div  id="<?php echo 'jobDialog123'.$studentslist->id;?>"></div> 
                    <?php
						}
					?>                                  
					</table>
                   <?php 
                    } 
					else
					{					   
                    ?>	
						<div>
                            <div class="yellow_bx" style="background-image:none;width:600px;padding-bottom:45px;">
                                <div class="y_bx_head" style="width:580px;">
                                <?php 
                                    echo Yii::t('app','Nothing Found!!');
                                ?>
                                </div>
                               
                            </div>
                        </div>
                                             
                   <?php  }  ?>  
                    </div>
                    
				     <div class="pagecon">
							<?php 							                                         
                              $this->widget('CLinkPager', array(
                              'currentPage'=>$pages->getCurrentPage(),
                              'itemCount'=>$item_count,
                              'pageSize'=>10,
                              'maxButtonCount'=>5,
                              //'nextPageLabel'=>'My text >',
                              'header'=>'',
                            'htmlOptions'=>array('class'=>'pages',"style"=>"margin:0px;"),
                            ));?>
                        </div>         
		</div>	
        </div>
        </td>
     </tr>
</table>  

<script>
$('body').click(function() {
	$('#osload').hide();
	$('#name').hide();
	$('#priority').hide();
	$('#batch').hide();	
	
});

$('.filterbxcntnt_inner').click(function(event){
   event.stopPropagation();
});

$('.load_filter').click(function(event){
   event.stopPropagation();
});
</script>                         