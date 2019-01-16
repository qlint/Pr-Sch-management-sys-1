<style>
table.attendance_table{ border-collapse:collapse}

.attendance_table{
	margin:30px 0px;
	font-size:8px;
	text-align:center;
	width:auto;
	/*max-width:600px;*/
	border-top:1px #CCC solid;
	border-right:1px solid #CCC;
}
.attendance_table td{
	border:1px solid #CCC;
	padding-top:10px; 
	padding-bottom:10px;
	width:auto;
	font-size:13px;
	
}

.attendance_table th{
	font-size:14px;
	padding:10px;
	border-left:1px #CCC solid;
	border-bottom:1px #CCC solid;
}

hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}
</style>
<?php
$settings			= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){
	$displaydate	= $settings->displaydate;	
}else{
	$settings		= UserSettings::model()->findByAttributes(array('user_id'=>1));
	$displaydate	= $settings->displaydate;
} 
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
	$date_l		= date($displaydate,strtotime($_REQUEST['date']));
	$daily_date	= date('Y-m-d',strtotime($_REQUEST['date']));
	$criteria->condition = "issued_date = '$daily_date'";
}
	if($flag == 1)
		$criteria->condition = $criteria->condition." and "."is_issued = 1";
	else
		$criteria->condition = "is_issued = 1";
		
	$criteria->order = 'id DESC';
	$sales 		= PurchaseSale::model()->findAll($criteria);
?>
	<!-- Header -->
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first" width="100">
                           <?php 
						   $filename=  Logo::model()->getLogo();
                            if($filename!=NULL)
                            { 
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td valign="middle">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; width:300px;  padding-left:10px;">
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo Yii::t('app','Phone: ').$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
   <hr />
	<!-- End Header -->
		<div align="center" style="text-align:center; display:block;">
					<?php 
					if($_REQUEST['year']!=NULL) {
						 echo Yii::t('app',' YEARLY SALE  REPORT').' - '.$year; 
                    }else if($_REQUEST['month'] !=NULL) {
						 echo Yii::t('app',' MONTHLY SALE  REPORT').' - '.$my; 
                    }else if(isset($_REQUEST['date']) and $_REQUEST['date']!=NULL){
						echo Yii::t('app',' DAILY SALE  REPORT').' - '.$date_l; 
					}else {
						echo Yii::t('app','OVERALL SALE  REPORT'); 
					}
                    ?>
		</div><br />
					<!-- Overall Report Table -->
					<div class="tablebx">
                        <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
                            <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
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
                                	<td colspan="4"><?php echo Yii::t('app','No data available!'); ?></td>
                                </tr>
                                <?php
							}
							?>
                        </table>
					</div>
					<!-- END Overall Report Table -->
