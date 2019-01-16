<style>
.listbxtop_hdng
{
	font-size:15px;	
	/*color:#1a7701;*/
	/*text-shadow: 0.1em 0.1em #FFFFFF;*/
	/*font-weight:bold;*/
	text-align:left;
	
}

table.table_listbx{ border-collapse:collapse}

.table_listbx tr td, tr th {
border:1px solid #C5CED9;
 padding:5px;

}
td.listbx_subhdng
{
	color:#333333;
	font-size:13px;	
	font-weight:bold;
	width:200px;
		
}

.odd
{
	background:#DCE6F2;
}
td.subhdng_nrmal
{
	color:#333333;
	font-size:14px;
	width:510px;	
}
.table_listbx
{
	margin:0px;
	padding:0px;
	/*width:1061px;*/
	
}
.table_listbx td
{
	padding:8px 0px 8px 10px;
	margin:0px;
	
	
}
.table_listbxlast td
{
	border-bottom:none;
	
}


td.subhdng_nrmal
{
	color:#333333;
	font-size:12px;	
}
.last
{
	border-bottom:1px solid #C5CED9;
}
.first
{
	border:none;
}
hr{ border-bottom:1px solid #ccc; border-top:0px solid #fff;}
</style>


<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL)
{
	$date=$settings->displaydate;
}
else
	$date = 'd-m-Y';
if($list)
{
	$model	=	new SalaryDetails;
?>
	<!-- Header -->
	
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100">
                           <?php $filename=  Logo::model()->getLogo();
									if($filename!=NULL)
									{
                                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                            		}
                            ?>
                </td>
                <td  valign="middle" >
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
                                <?php echo Yii::t('app','Phone:')." ".$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <hr />
    <br />
    <!-- End Header -->
    <div align="center" style="text-align:center; font-size:18px; display:block;"><?php echo Yii::t('app','Pay Slip Report'); ?></div><br />
   
    <table class="table_listbx" width="100%" cellspacing="0" cellpadding="0"> 
         <tr class="listbxtop_hdng">
            
            <td width="40"><?php echo '#';?></td> 
            <td><?php echo Yii::t('app','Name'); ?></td>
            <td><?php echo Yii::t('app','Employee Number'); ?></td>
            <td><?php echo Yii::t('app','Month'); ?></td>
            <td><?php echo $model->getAttributeLabel('basic_pay'); ?></td>
            <td><?php echo $model->getAttributeLabel('earn_total'); ?></td>   
            <td><?php echo $model->getAttributeLabel('deduction_total'); ?></td>   
            <td><?php echo $model->getAttributeLabel('net_salary'); ?></td> 
            <!--<td style="border-right:none;">Task</td>-->
        </tr>
       <?php 
	   $i=1;
	   $basic_total 	=	$earn_total	= $deduct_total	=	$net_total	=	0;
        foreach($list as $list_1)
        {
            $employee	=	Staff::model()->findByPk($list_1->employee_id);
			$basic_total 	+=	$list_1->basic_pay;
			$earn_total 	+=	$list_1->earn_total;
			$deduct_total	+=	$list_1->deduction_total; 
			$net_total		+= 	$list_1->net_salary;
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td class="" width="180"><?php echo $employee->fullname;	?></td> 
                <td><?php echo $employee->employee_number;	?></td> 
                <td><?php echo date('F Y',strtotime($list_1->salary_date));	?></td> 
                <td align="center"><?php echo $list_1->basic_pay; ?></td> 
                <td align="center"><?php echo $list_1->earn_total; ?></td>
                <td align="center"><?php echo $list_1->deduction_total; ?></td>
                <td align="center"><?php echo $list_1->net_salary; ?></td>
            </tr>
            <?php
            $i++;
        } 
        ?>
        <tr class=<?php echo $cls;?>>
            <td colspan="4" align="center"><strong><?php echo Yii::t('app','Total'); ?></strong></td>
            <td align="center"><strong><?php echo number_format($basic_total, 2, '.', ''); ?></strong></td> 
            <td align="center"><strong><?php echo number_format($earn_total, 2, '.', ''); ?></strong></td>
            <td align="center"><strong><?php echo number_format($deduct_total, 2, '.', ''); ?></strong></td>
            <td align="center"><strong><?php echo number_format($net_total, 2, '.', ''); ?></strong></td>
        </tr>
    </table>
<?php
  }else{
	  echo Yii::t('app','No Data Found!!');
  }
  
?>
