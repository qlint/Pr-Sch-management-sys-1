<style type="text/css">
.nothing-found{
	text-align:center;
	font-style:italic;
}
</style>
<?php 
$semester_enabled	= 	Configurations::model()->isSemesterEnabled(); 
$settings			=	UserSettings::model()->findByAttributes(array('user_id'=>1));
if($settings!=NULL)
{
    $date_format    =   $settings->displaydate;
    $time_format    =   $settings->timeformat;    
}
else
{
    $date_format    =   'm-d-Y';
    $time_format    =   'H:i:s';
}
?>

<div id="parent_Sect">
    <?php $this->renderPartial('/default/teacherleft');?>    
    <div class="pageheader">
        <h2><i class="fa fa-pencil"></i> <?php echo Yii::t('app', 'Online Examination');?> <span><?php echo Yii::t('app', 'View online exams here');?></span></h2>
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
            <ol class="breadcrumb">            
                <li class="active"><?php echo Yii::t('app', 'Online Examination');?></li>
            </ol>
        </div>
    </div>
    
    <div class="contentpanel">
        <div class="panel-heading" style="position:relative;">
            <div class="clear"></div>
            <h3 class="panel-title"><?php echo Yii::t('app','Online Exams'); ?> </h3>

        </div>
        <div class="people-item">
            <div class="opnsl_headerBox">
            <div class="opnsl_actn_box"> </div>
                        <div class="opnsl_actn_box">
            <div class="opnsl_actn_box1"> <?php
                    echo CHtml::link(Yii::t('app','Create Online Exam'),array('/onlineexam/exam/new','bid'=>$_REQUEST['bid']),array('class'=>'btn btn-primary'));
                ?></div>
            </div>
            </div>
            
            <div class="msg-successful">
                <div id="success_msg" style="display: none; color: #5ea822;"><center><?php echo Yii::t('app','Action performed successfully'); ?></center></div>
            </div>
            	<div class="row">
                <div class="pull-right">
                 <div class="paging_full_numbers clearfix">
                        <?php 
                          $this->widget('CLinkPager', array(
                          'currentPage'=>$pages->getCurrentPage(),
                          'itemCount'=>$item_count,
                          'pageSize'=>$page_size,
                          'maxButtonCount'=>5,
                            'prevPageLabel'=>'< Prev',
                          //'nextPageLabel'=>'My text >',
                          'header'=>'',
                        'htmlOptions'=>array('class'=>'pages'),
                        ));?>
                </div> 
                </div></div>
            <div class="tablebx">  

                <div class="clear"></div>
                <div class="table-responsive">                                    
                    <table class="table table-bordered mb30" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr class="tablebx_topbg">
                            <th width="6%"><?php echo Yii::t('app','Sl. No');?></th>	
                            <th width="16%"><?php echo Yii::t('app','Name');?></th>	                                
                            <th width="14%"><?php echo OnlineExams::model()->getAttributeLabel('batch_id');?></th>
							 <?php if($semester_enabled == 1){?>
									<th width="14%"><?php echo Yii::t('app','Semester');?></th>
							 <?php } ?>  
                            <th width="10%"><?php echo Yii::t('app','Start Time');?></th>	                                
                            <th width="10%"><?php echo Yii::t('app','End Time');?></th>	                                
                            <th width="13%"><?php echo Yii::t('app','Status');?></th>
                            <th width="30%"><?php echo Yii::t('app','Manage');?></th>
                        </tr>
                        </thead>
                        <?php 
                        if(isset($_REQUEST['page'])){
                            $i=($pages->pageSize*$_REQUEST['page'])-9;
                        }else{
                            $i=1;
                        }
                        $cls="even";
                        ?>
                        <?php
                        $date   =   strtotime(date("Y-m-d H:i:s"));                        
                        if($list)
                        { 
                            foreach($list as $list_1)
                            {
								$batch			=	Batches::model()->findByAttributes(array('id'=>$list_1->batch_id,'is_active'=>1,'is_deleted'=>0));
								$course 		= 	Courses::model()->findByAttributes(array('id'=>$batch->course_id)); 
								$sem_enabled	= 	Configurations::model()->isSemesterEnabledForCourse($course->id);
                            ?>
                                <tr class=<?php echo $cls;?>>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo CHtml::link($list_1->name, array('exam/questions','id'=>$list_1->id,'bid'=>$_REQUEST['bid'])); ?></td>
                                    <td><?php echo $list_1->batch; ?></td>
									<?php 
									 if($semester_enabled == 1){
										if($sem_enabled == 1 and $batch->semester_id != NULL){
												$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); ?>
												<td><?php echo ucfirst($semester->name); ?></td>
								  <?php } 
									  else{ ?>
										  <td><?php echo'-'; ?></td>
									  <?php }
									 }?>
									
                                    <td><?php echo date($date_format,strtotime($list_1->start_time)).'<br>'.date($time_format,strtotime($list_1->start_time)); ?></td>
                                    <td><?php echo date($date_format,strtotime($list_1->end_time)).'<br>'.date($time_format,strtotime($list_1->end_time)); ?></td>
                                    <td>
                                        <div class="Table-dropdown">
										<?php
                                        $datas= array(0=>Yii::t('app', "Default"),1=>Yii::t('app', "Open"),2=>Yii::t('app', "Closed"),3=>Yii::t('app', "Result Published"));
                                        //$datas= array(0=>Yii::t('app', "Default"),1=>Yii::t('app', "Open"),2=>Yii::t('app', "Closed"));
                                        echo CHtml::dropDownList('exam_status',$list_1->status, $datas,array('id'=>$list_1->id,
                                                                'ajax'=>array(
                                                                    'type'=>'POST',
                                                                    'url'=>CController::createUrl('/onlineexam/exam/updateStatus'),                                                                       
                                                                    'data'=>array('status'=>'js:this.value','exam_id'=>$list_1->id, Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                                                                    'success' => "function(data){
                                                                        var json = $.parseJSON(data);
                                                                        if(json.status=='success')
                                                                        {
                                                                            $('#success_msg').fadeIn('slow').delay(2000).fadeOut('slow');
                                                                            location.reload();
                                                                        }
                                                                        else
                                                                        {
                                                                            alert('Error');
                                                                        }
                                                                        
                                                                    }",
                                                                ),
                                                             ));                                                                               
                                        ?>
                                        </div>
                                    </td>
                                    <td><?php 
                                    //check any student attended this exam
                                    $exam_exists     =   OnlineExamStudentAnswers::model()->exists('exam_id = :exam_id',array('exam_id'=>$list_1->id)); 
                                    if(!$exam_exists && $list_1->status==0){
                                        echo CHtml::link(Yii::t('app', 'Add Question'), array('exam/addQuestion','id'=>$list_1->id,'bid'=>$_REQUEST['bid']), array('title'=>Yii::t('app', 'Add New Question'),'class'=>'view_Exmintn_atg opnsl_addqnBtn')); 
                                        echo "&nbsp;";
                                    }
                                    if(strtotime($list_1->start_time) >= $date)
                                    {
                                        
                                        echo CHtml::link(Yii::t('app', 'Update'), array('exam/update','id'=>$list_1->id,'bid'=>$_REQUEST['bid']), array('title'=>Yii::t('app', 'Update Exam'),'class'=>'view_Exmintn_atg opnsl_updateBtn')); 
                                        //echo CHtml::link(Yii::t('app','Update'), "#", array("submit"=>array('/onlineexam/exam/update','id'=>$list_1->id), 'csrf'=>true, 'class'=>'add-update-iocn'));
                                        echo "&nbsp;";
                                        echo CHtml::link(Yii::t('app','Delete'), "#", array("submit"=>array('/onlineexam/exam/delete','id'=>$list_1->id),'title'=>Yii::t('app', 'Delete Exam'),'confirm' => Yii::t('app', 'Are you sure?'), 'csrf'=>true, 'class'=>'view_Exmintn_atg opnsl_deleteBtn'));                                        
                                        echo "&nbsp;";
                                    }  
                                    
                                    if($list_1->status==3)
                                    {
                                        echo CHtml::link(Yii::t('app', 'Result'), array('exam/result','id'=>$list_1->id, 'bid'=>$_REQUEST['bid']), array('title'=>Yii::t('app', 'View Results'),'class'=>'view_Exmintn_atg Exm_aTgColor_y'));
                                    }
                                    $status     =   OnlineExamQuestions::model()->checkExamType($list_1->id);
                                    if($status && $list_1->status==2){
                                    echo "&nbsp;";
                                    echo CHtml::link(Yii::t('app', 'Verify'), array('/onlineexam/questions/verify','id'=>$list_1->id, 'bid'=>$_REQUEST['bid']), array('title'=>Yii::t('app', 'Verify Answers'),'class'=>'view_Exmintn_atg opnsl_verifyBtn'));
                                    }
                                    
                                    ?></td>
                                </tr>
                                <?php
                                if($cls=="even"){
                                    $cls="odd" ;
                                }else{
                                    $cls="even"; 
                                }
                                $i++;
                            } 
                        }
                        else{
                        ?>
                        <tr>
                            <td colspan="8" class="nothing-found"><?php echo Yii::t('app','Nothing Found'); ?></td>
                        </tr>
                        <?php		
                        }
                        ?>
                    </table>
                </div>
                
            </div> 
            <div class="row">
                <div class="pull-right">
            <div class="paging_full_numbers clearfix ">
                <?php                                          
                  $this->widget('CLinkPager', array(
                  'currentPage'=>$pages->getCurrentPage(),
                  'itemCount'=>$item_count,
                  'pageSize'=>$page_size,
                  'maxButtonCount'=>5,
                                          'prevPageLabel'=>'< Prev',
                  //'nextPageLabel'=>'My text >',
                  'header'=>'',
                'htmlOptions'=>array('class'=>'pages'),
                ));?>
                </div> <!-- END div class="pagecon" 2 -->
                </div>
                </div>
                <div class="clear"></div>                                            
        </div>                                                
    </div> 
</div> 
<div class="clear"></div>



