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
	Yii::t('app','Online Examination') => array('/onlineexam/dashboard'),
	Yii::t('app','Manage Exams'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top"><?php $this->renderPartial('/default/admin_left');?></td>
    	<td valign="top">
        	<div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Online Exams List');?></h1>
                
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php echo CHtml::link('<span>'.Yii::t('app','Add New Exam').'</span>', array('/onlineexam/exams/new'),array('class'=>'a_tag-btn')); ?></li>                                    
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
       			<?php $semester_enabled	= Configurations::model()->isSemesterEnabled(); ?>
                <div class="pdtab_Con" style="width:100%">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                            <tr class="pdtab-h">
                            	<td align="center" width="7%"><?php echo Yii::t('app', 'Sl No');?></td>                                            
                                <td height="" align="center" width="20%"><?php echo OnlineExams::model()->getAttributeLabel('name');?></td>
                                <td align="center" width="15%"><?php echo Yii::t('app', 'Course'); ?></td>
                                <td align="center" width="15%"><?php echo OnlineExams::model()->getAttributeLabel('batch_id');?></td>
								<?php if($semester_enabled==1){ ?>
										  <td align="center" width="15%"><?php echo Yii::t('app','Semester');?></td>
								<?php } ?>
                                <td align="center" width="20%"><?php echo Yii::t('app', 'Status');?></td>
                                <td align="center" width="25%"><?php echo Yii::t('app', 'Manage');?></td>
                            </tr>
                            <?php
                            if(isset($_REQUEST['page'])){
                                    $i=($pages->pageSize*$_REQUEST['page'])-9;
                            }
                            else{
                                    $i=1;
                            }
                            if($model)
                            {
                                foreach($model as $data){?>
                                <tr>
                                    <td align="center" width="40"><?php echo $i; ?></td>
                                    <td align="center" width="200"><?php echo CHtml::link(ucfirst($data->name), array('/onlineexam/exams/view','exid'=>$data->id)); ?></td>
                                    <td align="center">
                                        <?php 
                                        $batch=Batches::model()->findByAttributes(array('id'=>$data->batch_id,'is_active'=>1,'is_deleted'=>0));
										$sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($batch->course_id);                                                
                                        echo ucfirst($batch->course);
                                        ?>
                                    </td>
                                    <td align="center"><?php echo $data->batch;?></td>
									<?php if($semester_enabled==1){?>
											<td align="center">
											<?php if($sem_enabled==1 and $batch->semester_id!=NULL){
													$semester 	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
													echo ucfirst($semester->name);
												  }
												  else{
													echo '-';
												  }?>
											</td>
									<?php }?>
                                    <td align="center" width="150">
                                        <?php
                                        $datas= array(0=>Yii::t('app', "Default"),1=>Yii::t('app', "Open"),2=>Yii::t('app', "Closed"),3=>Yii::t('app', "Result Published"));
                                        //$datas= array(0=>Yii::t('app', "Default"),1=>Yii::t('app', "Open"),2=>Yii::t('app', "Closed"));
                                        echo CHtml::dropDownList('exam_status',$data->status, $datas,array('id'=>$data->id,
                                                                'ajax'=>array(
                                                                    'type'=>'POST',
                                                                    'url'=>CController::createUrl('/onlineexam/exam/updateStatus'),                                                                       
                                                                    'data'=>array('status'=>'js:this.value','exam_id'=>$data->id, Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
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
                                    </td>
                                    <td align="center" width="150" class="os-button-column">
                                         <ul class="tt-wrapper">
                                        <?php 
                                        
                                            $date   =   strtotime(date("Y-m-d H:i:s"));   
                                                //check any student attended this exam
                                                $exam_exists     =   OnlineExamStudentAnswers::model()->exists('exam_id = :exam_id',array('exam_id'=>$data->id)); 
                                                if(!$exam_exists && $data->status==0)
                                                {
                                                    echo '<li>'.CHtml::link('<span>'.Yii::t('app', 'Add Question').'</span>',array('exams/addQuestion','qid'=>$data->id), array('class'=>'add-icon')).'</li>';
                                                    //echo CHtml::link(Yii::t('app', 'Add Question'), array('exams/addQuestion','id'=>$data->id), array('title'=>Yii::t('app', 'Add New Question'),'class'=>'add-Ans-icon icon-bg')); 
                                                    echo "&nbsp;";
                                                }
                                                if(strtotime($data->start_time) >= $date)
                                                {
                                                    echo '<li>'.CHtml::link('<span>'.Yii::t('app', 'Update Exam').'</span>',array('exams/update','id'=>$data->id), array('class'=>'edit')).'</li>';
                                                    
                                                    //echo CHtml::link(Yii::t('app','Update'), "#", array("submit"=>array('/onlineexam/exam/update','id'=>$list_1->id), 'csrf'=>true, 'class'=>'add-update-iocn'));
                                                    echo "&nbsp;";
                                                    echo '<li>'.CHtml::link('<span>'.Yii::t('app','Delete Exam').'</span>', "#", array("submit"=>array('/onlineexam/exams/delete','id'=>$data->id),'title'=>Yii::t('app', 'Delete Exam'),'confirm' => Yii::t('app', 'Are you sure?'), 'csrf'=>true, 'class'=>'delete')),'</li>';                                        
                                                    echo "&nbsp;";
                                                }  

                                                if($data->status==3)
                                                {
                                                    echo '<li>'.CHtml::link('<span>'.Yii::t('app', 'Exam Result').'</span>', array('exams/result','id'=>$data->id), array('title'=>Yii::t('app', 'View Results'),'class'=>'view')).'</li>';
                                                }
                                                $status     =   OnlineExamQuestions::model()->checkExamType($data->id);
                                                if($status && $data->status==2){
                                                echo "&nbsp;";
                                                echo '<li>'.CHtml::link('<span>'.Yii::t('app', 'Verify Answers').'</span>', array('/onlineexam/exams/verify','id'=>$data->id), array('title'=>Yii::t('app', 'Verify Answers'),'class'=>'approve-n')).'</li>';
                                                }

                                                
                                                
                                                ?>
                                       
<?php /*?>                                            <?php 
                                                echo '<li>'.CHtml::link('', "#",array('submit'=>array('vendorDetails/delete','id'=>$data->id,), 'confirm'=>Yii::t('app','Are you sure you want to delete the vendor?'), 'csrf'=>true, 'class'=>'delete')).'</li>';
                                            ?><?php */?>
                                             
                                        </ul>                                                                            
                                    </td>

                                </tr>
                                <?php
                                $i++;
                                }
                            }
                            else{
                            ?>
                                <td colspan="6" style="text-align:center; font-style:italic;"><?php echo Yii::t('app','Nothing Found!'); ?></td>
                            <?php								
                            }
                            ?>                            
                        </tbody>
                    </table>        
                </div>
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
        </td>
    </tr>
</table>        
