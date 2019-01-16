<style type="text/css">
.ui-menu .ui-menu-item a{ color:#000 !important;}
.ui-menu .ui-menu-item a:hover{ color:#fff !important;}
.ui-autocomplete{box-shadow: 0 0 6px #d6d6d6;}
.pdf-box {    
    margin-top: 0;   
}
</style>

<script language="javascript">

function updatemode() // Function to get the dependent dropdown after selecting department
{
	var dep_id = document.getElementById('dep_id').value;
	if(dep_id != ''){
		window.location= 'index.php?r=report/default/employeeattendance&dep='+dep_id;	
	}
	else{
		window.location= 'index.php?r=report/default/employeeattendance';	
	}
}

function getmode() // Function to get the dependent dropdown after selecting mode
{
	var mode_id = document.getElementById('mode_id').value;
	if(mode_id == 1) // Overall
	{
		window.location= 'index.php?r=purchase/sale/saleReport&mode=1';
	}
	else if(mode_id == 2) // Yearly
	{
		document.getElementById("filler").style.display="table-row";
		document.getElementById("year").style.display="table-row";
		document.getElementById("month").style.display="none";
		document.getElementById("daily").style.display="none";
		
	}
	else if(mode_id == 3) // Monthly
	{
		document.getElementById("filler").style.display="table-row";
		document.getElementById("year").style.display="none";
		document.getElementById("month").style.display="table-row";
		document.getElementById("daily").style.display="none";
	}
	else if(mode_id == 4) // Individual
	{
		document.getElementById("filler").style.display="table-row";
		document.getElementById("year").style.display="none";
		document.getElementById("month").style.display="none";
		document.getElementById("daily").style.display="table-row";
	}
	else
	{
		document.getElementById("daily").style.display="none";
		document.getElementById("filler").style.display="none";
		document.getElementById("year").style.display="none";
		document.getElementById("month").style.display="none";
	}
	
}

function getyearreport() // Function to get yearly report
{
	var mode_id = document.getElementById('mode_id').value;
	var year_value = document.getElementById('year_value').value;
	window.location= 'index.php?r=purchase/sale/saleReport&mode='+mode_id+'&year='+year_value;
	
}

function getmonthreport() // Function to get monthly report
{
	var mode_id = document.getElementById('mode_id').value;
	var month_value = document.getElementById('month_value').value;
	month_value = month_value.replace(/(^\s+|\s+$)/g,'');
	window.location= 'index.php?r=purchase/sale/saleReport&mode='+mode_id+'&month='+month_value;
	
}
function getdailyreport() // Function to get monthly report
{
	var mode_id = document.getElementById('mode_id').value;
	var dobtxt = document.getElementById('dobtxt').value;
	window.location= 'index.php?r=purchase/sale/saleReport&mode='+mode_id+'&date='+dobtxt;
	
}


</script>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Purchase')=>array('/purchase'),
	Yii::t('app','Sale Report'),
);
$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){
	$displaydate	= $settings->displaydate;	
	$date			=	str_ireplace("d","",$settings->dateformat);
	$date_pic		= $settings->dateformat;
}else{
	$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
	$displaydate	= $settings->displaydate;	
	$date			=	str_ireplace("d","",$settings->dateformat);
	$date_pic		= $settings->dateformat;	
}
if(isset($_REQUEST['date']) and $_REQUEST['date']!=NULL) 
{
	$daily_display	= date($displaydate,strtotime($_REQUEST['date']));
}
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/leftside');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Sale Report');?></h1>
                <!-- DROP DOWNS -->
                <div class="formCon">
                    <div class="formConInner">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" class="s_search">
                            <tr>
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Select Mode');?></strong></td>
                                <td>&nbsp;</td>
                                <td>
									<?php 
										echo CHtml::dropDownList('mode_id','',array('1'=>Yii::t('app','Overall'),'2'=>Yii::t('app','Yearly'),'3'=>Yii::t('app','Monthly'),'4'=>Yii::t('app','Daily')),array('prompt'=>Yii::t('app','Select Mode'),'style'=>'width:177px;','onchange'=>'getmode()','id'=>'mode_id','options'=>array($_REQUEST['mode']=>array('selected'=>true))));  ?>
                                </td>
                            </tr>
                            
                             <?php
							if($_REQUEST['mode']==2)
							{
								$year_style = "display:table-row";
								$month_style = "display:none";
								$daily_style = "display:none";
								$filler_style = "display:table-row";
							}
							elseif($_REQUEST['mode']==3)
							{
								$year_style = "display:none";
								$month_style = "display:table-row";
								$daily_style = "display:none";
								$filler_style = "display:table-row";
							}elseif($_REQUEST['mode']==4)
							{
								$year_style = "display:none";
								$month_style = "display:none";
								$daily_style = "display:table-row";
								$filler_style = "display:table-row";
							}
							else
							{
								$year_style = "display:none";
								$month_style = "display:none";
								$daily_style = "display:none";
								$filler_style = "display:none";
							}
							?>
                            
                            <tr id="filler" style=" <?php echo $filler_style; ?> ">
                            	<td colspan="4">&nbsp;</td>
                            </tr>
                           
                            <!-- ROW TO SELECT YEAR -->
                             <tr id="year" style=" <?php echo $year_style; ?> ">
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Select Year');?></strong></td>
                                <td>&nbsp;</td>
                                <td>
                                	<?php
									$yearNow = date("Y",strtotime('+5 years'));
									$yearFrom = $yearNow - 20;
									$arrYears = array();
									foreach (range($yearFrom, $yearNow) as $number) 
									{
										 $arrYears[$number] = $number; 
									 }
									 
									$arrYears = array_reverse($arrYears, true);
											 
									echo CHtml::dropDownList('year','',$arrYears,array('prompt'=>Yii::t('app','Select Year'),'style'=>'width:177px;','id'=>'year_value','onchange'=>'getyearreport()','options'=>array($_REQUEST['year']=>array('selected'=>true))));
									?>
                                </td>
                            </tr>
                            <!-- END ROW TO SELECT YEAR -->
                            
                            <!-- ROW TO SELECT MONTH -->
                            <tr id="month" style=" <?php echo $month_style; ?> ">
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Select Month');?></strong></td>
                                <td>&nbsp;</td>
                                <td>
                                    <?php
										$this->widget('ext.EJuiMonthPicker.EJuiMonthPicker', array(
											'name' => 'month_year',
											'value'=>$_REQUEST['month'],
											'options'=>array(
												'yearRange'=>'-20:',
												'dateFormat'=>$date,
											),
											'htmlOptions'=>array(
												'onChange'=>'js:getmonthreport()',
												'id' => 'month_value',
												'readonly'=>true
											),
										));  
									?>
                                </td>
                            </tr>
                             <!-- END ROW TO SELECT MONTH -->
                             <tr id="daily" style=" <?php echo $daily_style; ?> ">
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Select Date ');?></strong></td>
                                <td>&nbsp;</td>
                                <td><?php 
								
									$this->widget('zii.widgets.jui.CJuiDatePicker', array(
										'name'=>'daily',
										'value'=>$daily_display,								
										'options'=>array(
										'showAnim'=>'fold',
										'dateFormat'=>$date_pic,
										'changeMonth'=> true,
										'changeYear'=>true,
										'yearRange'=>'1900:'
										),
										'htmlOptions'=>array(
										'id' => 'dobtxt',
										'readonly'=>true,
										'onChange'=>'js:getdailyreport()',
										),
									));
									?>
                                  </td>
                            </tr>
                             
                        </table>
                    </div> <!-- END div class="formConInner" -->
                </div><!--  END div class="formCon" -->
                 <!-- END DROP DOWNS -->
                 
                 <!-- REPORT SECTION -->
                  <!-- REPORT SECTION -->
				<?php
				$year		= '';	
				$my			= '';
				$daily_date = '';
				$flag 		= 0;	
                $criteria 	= new CDbCriteria;
				if(isset($_REQUEST['year']) and $_REQUEST['year']!=NULL) // Checking if mode == 2 (Yearly Report)
				{
					$flag 	= 1;	
					$year	= $_REQUEST['year'];
					$criteria->condition = "year(`issued_date`) = $year";
				}
				if(isset($_REQUEST['month']) and $_REQUEST['month']!=NULL) // Checking if mode == 3 (Monthly Report)
				{
					$flag 	= 1;
					$my		=	$_REQUEST['month'];
					$month	=  date('m',strtotime($_REQUEST['month']));
					$myear	=  date('Y',strtotime($_REQUEST['month']));
					$criteria->condition = "year(`issued_date`) = $myear AND month(`issued_date`) = $month";
				}
				if(isset($_REQUEST['date']) and $_REQUEST['date']!=NULL) // Checking if mode == 4 (Daily Report)
				{
					$flag 	= 1;
					$daily_date	= date('Y-m-d',strtotime($_REQUEST['date']));
					$criteria->condition = "issued_date = '$daily_date'";
				}
            
                if(isset($_REQUEST['mode']) and $_REQUEST['mode']!=NULL ) 
				{
					
					if($flag == 1)
						$criteria->condition = $criteria->condition." and "."is_issued = 1";
					else
						$criteria->condition = "is_issued = 1";
						
					$criteria->order = 'id DESC';
					$sales 		= PurchaseSale::model()->findAll($criteria);
					?>
					<h3>
					<?php 
					if($_REQUEST['year']!=NULL) {
						 echo Yii::t('app',' Yearly Sale Report').' - '.$year; 
                    }else if($_REQUEST['month'] !=NULL) {
						 echo Yii::t('app',' Monthly Sale Report').' - '.$my; 
                    }else if(isset($_REQUEST['date']) and $_REQUEST['date']!=NULL){
						$daily_date			 =  $_REQUEST['date'];
						$date_l				 =	date($displaydate, strtotime($daily_date));
						echo Yii::t('app',' Daily Sale Report').' - '.$date_l; 
					}else {
						echo Yii::t('app','Overall Sale Report'); 
					}
                    ?></h3>
					<!-- Overall PDF -->                           
					<div class="pdf-box">
                        <div class="box-two">
							<?php echo CHtml::link(Yii::t('app', 'Generate PDF'), array('/purchase/sale/saleoverallpdf','year'=>$year,'month'=>$my,'date'=>$daily_date),array('target'=>"_blank",'class'=>'pdf_but','class'=>'pdf_but')); ?>
                            </div>
                        <div class="box-one">
                        </div>
					</div>
					
					<!-- END Overall PDF -->
					<!-- Overall Report Table -->
					<div class="tablebx">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="tablebx_topbg">
                                <td><?php echo Yii::t('app','Sl No');?></td>
                                <td><?php echo Yii::t('app','Purchased by');?></td>
                                <td><?php echo Yii::t('app','Item name');?></td>
                                <td><?php echo Yii::t('app','Purchased Date');?></td>
                            </tr>
                            <?php
							$i=1;
							foreach($sales as $sale)
							{
								$user = Profile::model()->findByAttributes(array('user_id'=>$sale->employee_id));
								$item = PurchaseItems::model()->findByAttributes(array('id'=>$sale->material_id)); 
								
								?>
								<tr>
									<td style="padding-top:10px; padding-bottom:10px;"><?php echo $i++;?></td>
									<td><?php echo $user->fullname; ?></td>
									<td><?php echo $item->name;?></td>
									<td><?php echo date($displaydate, strtotime($sale->issued_date));?></td>
								</tr>
								<?php
							}
							if(count($sales) ==0)
							{
								?>
								<tr>
									<td colspan="4" style="padding-top:10px; padding-bottom:10px;"><?php echo Yii::t('app','No data available!'); ?></td>
								</tr>
								<?php
							}
							?>
                        </table>
					</div>
					<!-- END Overall Report Table -->
					<?php
				}?>
