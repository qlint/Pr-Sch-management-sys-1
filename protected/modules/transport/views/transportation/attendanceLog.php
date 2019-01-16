<style>
    .genarate-pdf-bg{
        margin-bottom: 60px;
    
    }
    .pdf_but{ padding: 10px 0px 10px 32px;}
 </style>


<?php $this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Allotment')=>array('/transport/transportation/create'),
	Yii::t('app','View'),
	
);?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'attendance-form',
	'enableAjaxValidation'=>false,
	'method' => 'GET',
	'action'=>CController::createUrl('/transport/transportation/attendanceLog')
	
)); 

$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                        if($settings != NULL){
                            $date	= $settings->dateformat;
                        }
                        else{
                            $date 	= 'dd-mm-yy';
                            }   
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/transportation/trans_left');?>
        </td>
        <td valign="top">
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Attendance Log');?></h1>
                
                <div class="formCon">
                    <div class="formConInner">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" class="s_search">
                            <tr>
                                <td><strong><?php echo Yii::t('app','Select Route');?></strong></td>
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Select Date');?></strong></td>  
                                <td>&nbsp;</td>
                            </tr>
                             <tr>
                                <td><?php
                                    $route_id="";
                                    if(isset($_REQUEST['route_id']) && $_REQUEST['route_id']!=NULL)
                                    {
                                        $route_id= $_REQUEST['route_id'];
                                    }
                                    
                                echo CHtml::dropDownList('route_id',$route_id,CHtml::listData(RouteDetails::model()->findAll(),'id','route_name'),array('prompt'=>Yii::t('app','Select'),'style'=>'width:70%'));?></td>
                                <td>&nbsp;</td>
                                <td  width="180"><?php
                                    if(isset($_REQUEST['date']) && $_REQUEST['date']!=NULL)
                                    {
                                        $default_date= $_REQUEST['date'];
                                    }
                                    else
                                    {
                                        $default_date=date("j M Y");
                                    }
                                     $this->widget('zii.widgets.jui.CJuiDatePicker', array(                        
                                                        'name'=>'date',
                                                        'value'=>$default_date,
                                                        // additional javascript options for the date picker plugin
                                                        'options'=>array(
                                                                'showAnim'=>'fold',
                                                                'dateFormat'=>$date,
                                                                'changeMonth'=> true,
                                                                'changeYear'=>true,
                                                                'yearRange'=>'1900:'.(date('Y')+5),

                                                        ),
                                                        'htmlOptions'=>array(								
                                                                'readonly'=>true,
                                                            'style'=>'width:85%'
                                                        ),
                                    ));
                                ?></td>
                                <td><?php echo CHtml::submitButton( Yii::t('app','Submit'),array('name'=>'search','class'=>'formbut')); ?></td>
                               
                            </tr>
                            
                           
                          
                            <tr>
                            	<td colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                            	
                            </tr>
                        </table>
                    </div> <!-- END div class="formConInner" -->
                </div>
                    <?php 
                    if(isset($list) && count($list)>0)
                    {
                    ?>                                    
                    	<div class="pdf-box">
                            <div class="box-one"></div>
                            <div class="box-two">
                                 <div class="pdf-div">
                                 	<?php echo CHtml::link(Yii::t('app','Generate PDF'), array('transportation/attendancePdf','route_id'=>$_REQUEST['route_id'],'date'=>$default_date),array('target'=>'_blank','class'=>'pdf_but')); ?>
                                 </div>
                            </div>
                        </div>
                    <?php } ?>
                <div class="pdtab_Con" style="padding-top:0px;">
                    <?php 
                    if($list)
                    {
                    ?>
                       
                        
                  
                                
                    
                    
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="pdtab-h">
							
                            <td align="center" rowspan="2">
                            	<?php echo Yii::t('app','Sl No');?>
                            </td>
                            <?php
                            if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){						
                            ?> 
                            <td align="center" rowspan="2">
                            	<?php echo Yii::t('app','Student Name');?>
                            </td>
                             <?php } ?>  
                            <td align="center" rowspan="2">
                            	<?php echo Yii::t('app','Date');?>
                            </td>
                            <td align="center" colspan="2">
                            	<?php echo Yii::t('app','Morning');?>
                            </td>
                            <td align="center" colspan="2">
                            	<?php echo Yii::t('app','Evening');?>
                            </td>
                           
                        </tr>
                        <tr class="pdtab-h">
                            <td align="center" width="15%">
                            	<?php echo Yii::t('app','IN');?>
                            </td>
                            <td align="center" width="15%">
                            	<?php echo Yii::t('app','OUT');?>
                            </td>
                           <td align="center" width="15%">
                            	<?php echo Yii::t('app','IN');?>
                            </td>
                            <td align="center" width="15%">
                            	<?php echo Yii::t('app','OUT');?>
                            </td>
                        </tr>
                        
                       
                        <?php 
                            if(isset($_REQUEST['page']))
                            {
                            	$i=($pages->pageSize*$_REQUEST['page'])-9;
                            }
                            else
                            {
                            	$i=1;
                            }
                            $cls="even";
                            ?>
                        
                        <?php 
                        $no=1;
                            foreach($list as $list_1)
                            {
                            ?>
                            <tr>
                                <td align="center" width="10%"><?php echo $no; ?></td>
                                <?php
                                    if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){						
					$student = Students::model()->findByAttributes(array('id'=>$list_1->student_id));			?> 
                                <td style="text-align:center"><?php echo CHtml::link($student->studentFullName('forStudentProfile'),array('/students/students/view','id'=>$list_1->student_id)) ?></td>
                                <?php } ?> 
                                <td align="center" width="10%"><?php echo $default_date; ?></td>
                                <td align="center" width="15%">
                                    <?php 
                                        $morning_in= RouteAttendance::checkStatus($list_1->student_id, 1, 0, $default_date, $route_id);
                                        echo $morning_in;
                                    ?>
                                </td>
                                <td align="center" width="15%">
                                    <?php 
                                        $morning_out= RouteAttendance::checkStatus($list_1->student_id, 1, 1, $default_date, $route_id);
                                        echo $morning_out;
                                    ?>
                                </td>
                                <td align="center" width="15%">
                                    <?php 
                                        $evening_in= RouteAttendance::checkStatus($list_1->student_id, 2, 0, $default_date, $route_id);
                                        echo $evening_in;
                                    ?>
                                </td>
                                <td align="center" width="15%">
                                    <?php 
                                        $evening_out= RouteAttendance::checkStatus($list_1->student_id, 2, 1, $default_date, $route_id);
                                        echo $evening_out;
                                    ?>
                                </td>
                            </tr>
                        
                            
                            <?php 
                            $no++;
                            
                                    } ?>
                    </table>
                    <?php } 
                    elseif(isset ($_REQUEST['route_id']))
                    {
                    	echo '<div class="listhdg" align="center">'.Yii::t('app','Nothing Found!!').'</div>';	
                    }?>
                    
                </div>
            </div>
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>

<script>
$('#attendance-form').submit(function() {
  var route= $("#route_id").val();
  if(route==="")
  {
      alert("<?php echo Yii::t("app","Select Route"); ?>");
      return false;
  }
});
</script>