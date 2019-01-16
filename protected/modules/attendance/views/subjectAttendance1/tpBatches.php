<style type="text/css">
.table-responsive {
    border: 1px solid #ddd;
    margin-bottom: 15px;
    overflow-x: scroll;
    overflow-y: hidden;
    width: 100%;
}
</style>
<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

<div id="parent_Sect">
<?php $this->renderPartial('application.modules.teachersportal.views.default.leftside');?>
<div class="right_col"  id="req_res123">
<!--contentArea starts Here-->
<div id="parent_rightSect">
  <div class="parentright_innercon">
    <div class="pageheader">
      <div class="col-lg-8">
        <h2><i class="fa fa-file-text"></i></h2><?php echo Yii::t('app','Attendance'); ?><span><?php echo Yii::t('app','View your attendance here'); ?> </span></h2>
      </div>
      <div class="col-lg-2"> </div>
      <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          
          <li class="active"><?php echo Yii::t('app','Attendance'); ?></li>
        </ol>
      </div>
      <div class="clearfix"></div>
    </div>
    <div class="contentpanel">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo Yii::t('app','Mark Student Attendance'); ?></h3>
      </div>
      <div class="people-item">
         <?php $this->renderPartial('application.modules.teachersportal.views.default.employee_tab');?>
        <?php 
			   //If $list_flag = 1, table of batches will be displayed. If $list_flag = 0, attendance table will be displayed.
			   if($_REQUEST['id']!=NULL){
						$list_flag=0;   		
				 }
				else{
					 $employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
					 $batch_ids=ApplyLeaves::model()->getBatches($employee->id);
					
					 if(count($batch_ids)>1){
						 $list_flag = 1;
					 }
					 else{
						  $list_flag = 0;
						  $_REQUEST['id'] = $batch_ids;							 
					 }
				}?>
        <?php if($list_flag==1){ ?>
        <div class="cleararea"></div>
        <div class="table-responsive">
          <table width="80%" border="0" cellspacing="0" cellpadding="0" class="table mb30">
           <thead>
              <!--class="cbtablebx_topbg"  class="sub_act"-->
              <tr class="pdtab-h">
                <th align="center"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></th>
                <th align="center"><?php echo Yii::t('app','Class Teacher');?></th>
                <th align="center"><?php echo Yii::t('app','Start Date');?></th>
                <th align="center"><?php echo Yii::t('app','End Date');?></th>
              </tr>
              </thead>
               <tbody>
              <?php 
						  			
                          foreach($batch_ids as $batch_id)
                          {			
						  			$batch_1 = Batches::model()->findByPk($batch_id);
									$model = AttendanceSettings::model()->findByAttributes(array('config_key'=>'type'));
									if($model->config_value == 1)
						  				$link = CHtml::link($batch_1->name, array('/teachersportal/default/studentattendance','id'=>$batch_1->id));
									else
										$link = CHtml::link($batch_1->name, array('/attendance/subjectAttendance/tpAttendance','id'=>$batch_1->id));
								
                                    echo '<tr id="batchrow'.$batch_1->id.'">';
                                    echo '<td style="text-align:left; padding-left:10px; font-weight:bold;">'.$link.'</td>';
                                    $settings=UserSettings::model()->findByAttributes(array('id'=>1));
										if($settings!=NULL)
										{	
											$date1=date($settings->displaydate,strtotime($batch_1->start_date));
											$date2=date($settings->displaydate,strtotime($batch_1->end_date));
		
										}
                                    $teacher = Employees::model()->findByAttributes(array('id'=>$batch_1->employee_id));					
                                    echo '<td align="center">';
                                    if($teacher){
                                        echo Employees::model()->getTeachername($teacher->id);
                                    }
                                    else{
                                        echo '-';
                                    }
                                    echo '</td>';					
                                    echo '<td align="center">'.$date1.'</td>';
                                    echo '<td align="center">'.$date2.'</td>';
                                    echo '</tr>';
                                }
                               ?>
            </tbody>
          </table>
        </div>
        <?php }else{  ?>
        <p><?php echo Yii::t('app','No Assigned Subject for this teacher'); ?></p>
        <?php } ?>       
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>
