<style type="text/css">
.margin_space{ margin:10px 0}

.filters{margin:0 0 10px 0}
</style>

<?php 
$total = 0;//total sms count.
$current_date = 0;//current date sms count.
$current_month = 0;//current month sms count.
$data = SmsCount::model()->findAll();

foreach($data as $val)
	$total += $val->current;

$data = SmsCount::model()->findByAttributes(array('date'=>date('Y-m-d')));
if($data)
	$current_date = $data->current;


$currentMonth=date("Y-m");//current month.
$criteria=new CDbCriteria;
$criteria->condition = 'DATE_FORMAT(`date`, "%Y-%m")=:currentMonth';
$criteria->params= array(':currentMonth'=>$currentMonth);

$data = SmsCount::model()->findAll($criteria);
foreach($data as $val)
	$current_month += $val->current;
	
?>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Sms Counts'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    
    <?php $this->renderPartial('/configurations/left_side');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="75%"><div>
<h1><?php echo Yii::t('app','SMS Counter');?></h1>
<div class="overview">
	<div class="overviewbox ovbox1">
    	<h1><strong><?php echo Yii::t('app','Total SMS ');?></strong></h1>
        <div class="ovrBtm"><?php echo $total; ?></div>
    </div>
    <div class="overviewbox ovbox2">
    	<h1><strong><?php echo Yii::t('app','Today');?></strong></h1>
        <div class="ovrBtm"><?php echo $current_date; ?></div>
    </div>
    <div class="overviewbox ovbox2">
    	<h1><strong><?php echo Yii::t('app','Current Month');?></strong></h1>
        <div class="ovrBtm"><?php echo $current_month; ?></div>
    </div>
   <?php /*?> <div class="overviewbox ovbox3">
    	<h1><strong><?php echo Yii::t('employees','Inactive Users');?></strong></h1>
        <div class="ovrBtm">0</div>
    </div><?php */?>
  <div class="clear"></div>
    
</div>
<div class="clear"></div>
  <div style="margin-top:20px; width:90%" id="container"></div>
  <div class="pdtab_Con" style="width:97%">
                <div style="font-size:13px; padding:5px 0px"><strong><?php echo Yii::t('app','Recent SMS Counter Details');?></strong></div>
                <div class="col-lg-4 nopadding" >
                
                <?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'filter-form',
						'enableAjaxValidation'=>false,
					)); ?>
                    
                <div class="margin_space"><?php 
					$type_arr = array(1=>Yii::t('app','Date'),2=>Yii::t('app','Month'),3=>Yii::t('app','Date Range'));
					echo $form->dropDownList($filter,'type',$type_arr,array('prompt'=>Yii::t('app','Select'),'style'=>'width:190px;','options'=>array()));
				 ?>
                 </div>
                 <div class="filters" id="date_filter" <?php echo ($filter->date == NULL or $filter->date == '')?'style="display:none"':''; ?>>   
				<?php 
                        $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                        if($settings!=NULL)
                        {
                            $date=$settings->dateformat;
                        }
                        else
                        $date = 'dd-mm-yy';	
						
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        //'name'=>'Students[admission_date]',
                        'model'=>$filter,
                        'attribute'=>'date',
                        // additional javascript options for the date picker plugin
                        'options'=>array(
						'onSelect'=>"js:function(date){
										$('#filter-form').submit();
										}",
                        'showAnim'=>'fold',
                        'dateFormat'=>$date,
                        'changeMonth'=> true,
                        'changeYear'=>true,
                        'yearRange'=>'1900:'.(date('Y')+5)
                        ),
                        'htmlOptions'=>array(
                        'style'=>'border: 1px solid #c2cfd8;
									margin-left: 2px;
									padding: 5px;
									width: 177px;'
                        ),
                        ));
                        ?>  
                        </div>
                        <div class="filters" id="month_filter" <?php echo ($filter->month == NULL or $filter->month == '')?'style="display:none"':''; ?>>                     
				<?php 
				
					$month_arr = array();
					$monthname = array('January', 'February', 'March', 'April','May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
					for($i = 1 ; $i <= 12; $i++)
					{
						$month_arr[date("Y")."-".$i] =  $monthname[$i-1];
					}
					
					echo $form->dropDownList($filter,'month',$month_arr,array('prompt'=>'Select','style'=>'width:190px;','onchange'=>'getstatus()','options'=>array()));
				 ?>
                 </div>
                  <div class="filters" id="range_filter" <?php echo ($filter->range_from == NULL or $filter->range_from == '')?'style="display:none"':''; ?>>   
                 <?php 
                        $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                        if($settings!=NULL)
                        {
                            $date=$settings->dateformat;
                        }
                        else
                        $date = 'dd-mm-yy';	
						
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        //'name'=>'Students[admission_date]',
                        'model'=>$filter,
                        'attribute'=>'range_from',
                        // additional javascript options for the date picker plugin
                        'options'=>array(
						'onSelect'=>"js:function(date){
										var value =  $('#FilterForm_range_to').val();
										if(value != '' && value != null)
											$('#filter-form').submit();
										}",
                        'showAnim'=>'fold',
                        'dateFormat'=>$date,
                        'changeMonth'=> true,
                        'changeYear'=>true,
                        'yearRange'=>'1900:'.(date('Y')+5)
                        ),
                        'htmlOptions'=>array(
                        'style'=>'border: 1px solid #c2cfd8;
									margin-left: 2px;
									padding: 5px;
									width: 177px;'
                        ),
                        ));
                        ?>
                         
                         <?php 
                        $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                        if($settings!=NULL)
                        {
                            $date=$settings->dateformat;
                        }
                        else
                        $date = 'dd-mm-yy';	
						
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        //'name'=>'Students[admission_date]',
                        'model'=>$filter,
                        'attribute'=>'range_to',
                        // additional javascript options for the date picker plugin
                        'options'=>array(
						'onSelect'=>"js:function(date){
										var value =  $('#FilterForm_range_from').val();
										if(value != '' && value != null)
											$('#filter-form').submit();
										}",
                        'showAnim'=>'fold',
                        'dateFormat'=>$date,
                        'changeMonth'=> true,
                        'changeYear'=>true,
                        'yearRange'=>'1900:'.(date('Y')+5)
                        ),
                        'htmlOptions'=>array(
                        'style'=>'border: 1px solid #c2cfd8;
									margin-left: 2px;
									padding: 5px;
									width: 177px;'
                        ),
                        ));
                        ?>  
                 </div>
                 <?php $this->endWidget(); ?>
                 
                 </div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr class="pdtab-h">
                      <td align="center" height="18"><?php echo Yii::t('app','Date');?></td>
                      <td align="center"><?php echo Yii::t('app','Count');?></td>
                     
                      
                    </tr>
                  </tbody>
                  <?php foreach($list as $list_1)
	              { ?>
                    <tbody>
                    <tr>
                    <td align="center"><?php 
											$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($list_1->date));
									echo $date1;
		
								}
								else
								echo $list_1->date;
					 ?>&nbsp;</td>
                    <td align="center"><?php echo $list_1->current; ?>&nbsp;</td>
                  </tr>
                     
               </tbody>
               <?php
               } ?>
                               
               </table>
              </div>
 	</div></td>
        
      </tr>
    </table>
    </div>
    </td>
  </tr>
</table>

<script>
$("#FilterForm_type").change(function() {
    var value = $("#FilterForm_type").val();
	if(value!=''){
		if(value == 1){
			$(".filters").hide();
			$("#date_filter").show();
		}else if(value == 2){
			$(".filters").hide();
			$("#month_filter").show();
		}else{
			$(".filters").hide();
			$("#range_filter").show();
		}
	}else{
		$(".filters").hide();
	}
});

$("#FilterForm_month").change(function() {
	$('#filter-form').submit();
});	
</script>