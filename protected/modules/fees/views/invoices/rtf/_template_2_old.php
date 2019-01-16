<style>
.invoice-table td {
	padding-left: 10px !important;
	font-family:"Courier New", Courier, monospace;
}
.recept-table{
	border-collapse:collapse;
	
}
.recept-table td{

	padding:6px;
	font-size:12px;	
	font-family:"Courier New", Courier, monospace;
}

.recept-table th{

	padding:6px;
	font-size:12px;
	text-align:left;
	font-family:"Courier New", Courier, monospace;
}
.recept-table{
	margin:5px 0px;
	font-size:8px;
	border-collapse:collapse;
	font-family:"Courier New", Courier, monospace;

}
table.attendance_table{ border-collapse:collapse}
.attendance_table{
	margin:5px 0px;
	font-size:6px;
	border-collapse:collapse;
	border:1px solid #000;
	font-family:"Courier New", Courier, monospace;
}
.attendance_table td{
	border-left:1px solid #000;
	border-right:1px solid #000;
	padding:6px;
	width:auto;
	font-size:11px;
	font-family:"Courier New", Courier, monospace;
}
hr {
	border-bottom: 1px solid #000;
	border-top: 0px solid
}
.ruppee-lttr p{
	margin:0px; padding:0px;
	font-size:12px;	
	font-family:"Courier New", Courier, monospace;
}
.signtr-brdr{
	border:none;	
}
.signtr-brdr td{
	 font-size:11px;
	 font-family:"Courier New", Courier, monospace;
}
.school-heade-tbl td{
	text-align:center;
	 font-family:"Courier New", Courier, monospace;
}
</style>
<?php
	$configuration  = Configurations::model()->findByPk(5);
	$college=Configurations::model()->findAll();
	$feeconfig 	= FeeConfigurations::model()->find();	//fee cofigurations
	$feecat	=	FeeCategories::model()->findByPk($invoice->fee_id);
	
	$invoice_amount = 0;
    foreach($particulars as $key=>$particular){
        $amount = $particular->amount;		
		if($feeconfig->discount_in_fee==1){
			//apply discount
			if($particular->discount_type==1){  //percentage
				$idiscount  = (($particular->amount * $particular->discount_value)/100);
				$amount     = $amount - $idiscount;
			}
			else if($particular->discount_type==2){ //amount
				$amount = $amount - $particular->discount_value;
			}
		}
        
		if($feeconfig->tax_in_fee==1){
			//apply tax
			if($particular->tax!=0){
				$tax    = FeeTaxes::model()->findByPk($particular->tax);
				if($tax!=NULL){
					$itax   = (($amount * $tax->value)/100);
					$amount = $amount + $itax;
				}
			}
		}
		
        $invoice_amount   += $amount;
    }

    $amount_payable = 0;
    $payments       = 0;
    $adjustments    = 0;

    $criteria       = new CDbCriteria;
    $criteria->compare('invoice_id', $invoice->id);
	$criteria->order	= 'id DESC';
    $alltransactions    = FeeTransactions::model()->findAll($criteria);

    foreach($alltransactions as $index=>$ctransaction){
        if($ctransaction->is_deleted==0 and $ctransaction->status==1){
            if($ctransaction->amount<0){
                $adjustments    += $ctransaction->amount;
            }
            else{
                $payments       += $ctransaction->amount;
            }
        }
    }

    $amount_payable = $invoice_amount - ( $payments + $adjustments );
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="school-heade-tbl">
   <tbody>
        <tr>
            <td style="font-size:18px;"><?php echo ($college!=NULL and isset($college[0]))?$college[0]->config_value:"-"; ?></td>
        </tr>
        <?php
        if($college!=NULL and isset($college[21]) and $college[21]!=""){
		?>
        <tr>
            <td style="font-size:10px;"><?php echo Yii::t('app', 'Affiliation No').':'.$college[21]->config_value; ?></td>
        </tr>
        <?php
		}
		?>
        <tr>
            <td style="font-size:10px;"><?php echo ($college!=NULL and isset($college[1]))?$college[1]->config_value:"-"; ?></td>
        </tr>
        <tr>
            <td  style="font-size:10px; "><?php echo Yii::t('app', 'Telephone');?> :<?php echo (($college!=NULL and isset($college[2]))?$college[2]->config_value:"-").(($college!=NULL and isset($college[27]) and $college[27]!=NULL)?", ".$college[27]->config_value:""); ?> <?php echo Yii::t('app', 'Email');?> :<?php echo ($college!=NULL and isset($college[24]))?$college[24]->config_value:"-"; ?></td>
        </tr>
    </tbody>
</table>

<hr />

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="recept-table1">
    <tbody>
        <tr>
            <th align="center" style=" font-weight:600; font-size:14px;"><?php echo Yii::t('app', 'Fee Receipt');?></th>
        </tr>
    </tbody>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="recept-table">
	<tbody>
        <tr>
            <th width="10%"><?php echo Yii::t('app', 'Receipt No');?></th>
            <td width="2">:</td>
            <td width="24%"><?php echo $invoice->id;?></td>
            <th width="10%"><?php echo Yii::t('app', 'Date');?></th>
            <td width="2">:</td>
            <td width="20%">
            	<?php
					$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
					if($settings!=NULL and $settings->displaydate!=NULL)
						echo date($settings->displaydate, time());
					else
						echo date('d M Y');
				?>
            </td>
            <th width="10%">Mode</th>
            <td width="2">:</td>
            <td width="20%">
            	<?php
                	$last_transac	= NULL;
					if(count($alltransactions)>0){
						$last_transac	= $alltransactions[0];
					}
					
					echo ($last_transac!=NULL)?$last_transac->transactionType:'-';
				?>
            </td>
        </tr> 
        <tr>
            <th width="10%">
            	<?php
                if($last_transac!=NULL){
					if($last_transac->payment_type==3)
						echo Yii::t('app', 'Cheque No / Bank Name');
					else
						echo Yii::t('app', 'Transaction Info');
				}
				else{
					echo Yii::t('app', 'Transaction Info');
				}
				?>
          	</th>
            <td width="2">:</td>
            <td width="24%"><?php echo ($last_transac!=NULL)?$last_transac->transaction_id.' / '.$last_transac->description:'-';?></td>
            <th width="13%"><?php echo Yii::t('app', 'Term');?></th>
            <td width="2">:</td>
            <td width="20%" colspan="4"><?php echo date('F', strtotime($invoice->due_date));?></td>
        </tr>
         <tr>
            <th width="13%"><?php echo Yii::t('app', 'Name');?></th>
            <td width="2">:</td>
            <td width="30%">
            	<?php
                	if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){
						$display_name   = "-";
						if($invoice->table_id!=NULL and $invoice->table_id!=0){
							if($invoice->user_type==1){ //student
								$student        = Students::model()->findByPk($invoice->table_id);
								if($student!=NULL)
									$display_name   = $student->studentFullName("forStudentProfile");
							}
						}
						//display name
						echo $display_name;
					}
				?>
            </td>
            <th width="10%"><?php echo Yii::t('app', 'Student ID');?></th>
            <td width="2">:</td>
            <td width="10%">
         	<?php
					$student_id	= '';
					if($student==NULL){
						if($invoice->table_id!=NULL and $invoice->table_id!=0){
							if($invoice->user_type==1){ //student
								$student        = Students::model()->findByPk($invoice->table_id);
							}
						}
					}
					
					$student_id	= ($student!=NULL and $student->admission_no!=NULL)?$student->admission_no:'-';					
					echo $student_id;
				?>
            </td>
            <th width="13%"><?php echo Yii::t('app', 'S/d of');?></th>
            <td width="2">:</td>
            <td width="30%">
            	<?php
					$guardian_name	= '-';
					if($student!=NULL){
                		$guardian		= $student->getPrimaryGuardian($student->id);
						$guardian_name	= ($guardian!=NULL)?$guardian->fullname:'-';
					}
					echo $guardian_name;
				?>      
            </td>
        </tr>
                   
    </tr>       
   
        <tr>
        <th width="10%"><?php echo Yii::t('app', 'Roll no');?></th>
            <td width="2">:</td>
            <td width="20%">
            	<?php
					$student_roll_no	= ($student!=NULL and $student->class_roll_no!=NULL)?$student->class_roll_no:'-';					
					echo $student_roll_no;
				?> 
            </td>
            <th width="10%"><?php echo Yii::t('app', 'Class');?></th>
            <td width="2">:</td>
            <td width="24%">
            	<?php
					$course_name	= '-';
					$batch_name		= '-';
					if($student!=NULL){
						$criteria		= new CDbCriteria;
						$criteria->join	= 'JOIN `batch_students` `bs` ON `bs`.`batch_id`=`t`.`id`';
						$criteria->condition	= '`bs`.`student_id`=:student_id AND `bs`.`status`=:status AND `result_status`=:result_status';
						$criteria->params		= array(':student_id'=>$student->id, ':status'=>1, ':result_status'=>0);
                		$batch			= Batches::model()->find($criteria);
						$batch_name		= ($batch!=NULL)?$batch->name:'-';
						$course_name	= ($batch!=NULL and $batch->course123!=NULL)?$batch->course123->course_name:'-';
					}
					echo $course_name;
				?>
            </td>

		<th width="10%"><?php echo Yii::t('app', 'Section');?></th>
            <td width="2">:</td>
            <td width="20%">
			<?php echo $batch_name;?>     
            </td>
           
        </tr> 
             
    </tr>          
    </tbody>
</table>
<?php $colspan	= 3;?>
<div class="attendance_table1">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="attendance_table">
        <tbody>
            <tr class="pdtab-h">
                <td colspan="3" style=" border-bottom:1px solid #000;"><?php echo Yii::t('app','Particulars'); ?></td>
                <?php if($feeconfig==NULL or $feeconfig->discount_in_invoice==1 or $feeconfig->tax_in_fee==1){$colspan++;?>
                <td width="15%" align="center" style=" border-bottom:1px solid #000;"><?php echo Yii::t('app','Unit Price'); ?></td>
                <?php }?>
                <?php if($feeconfig==NULL or $feeconfig->discount_in_invoice==1){$colspan++;?>
                <td width="10%" align="center" style=" border-bottom:1px solid #000;"><?php echo Yii::t('app','Discount'); ?></td>
                <?php }?>
                <?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){$colspan++;?>
                <td width="10%" align="center" style=" border-bottom:1px solid #000;"><?php echo Yii::t('app','Tax'); ?></td>
                <?php }?>
                <td width="20%" style=" border-bottom:1px solid #000;" align="center"><?php echo Yii::t('app','Amount'); ?></td>
            </tr>
            <?php
				$amount_total  	= 0;
				$fine_total     = 0;
	
				$sub_total      = 0;
				$discount_total = 0;
				$tax_total      = 0;
				foreach($particulars as $key=>$particular){
            ?>
            	<tr>
                    <td colspan="3" <?php if($key+1==count($particulars)){?>style="border-bottom:1px solid #000;"<?php }?>><?php echo $particular->name;?></td>                                           
                    <?php if($feeconfig==NULL or $feeconfig->discount_in_invoice==1 or $feeconfig->tax_in_fee==1){?>
                    <td align="center" <?php if($key+1==count($particulars)){?>style="border-bottom:1px solid #000;"<?php }?>><?php echo number_format($particular->amount, 2);?></td>
                    <?php }?>
                    <?php if($feeconfig==NULL or $feeconfig->discount_in_invoice==1){?>
                    <td align="center" <?php if($key+1==count($particulars)){?>style="border-bottom:1px solid #000;"<?php }?>>
						<?php
                            if($particular->discount_type==1)
                                echo $particular->discount_value." %";
                            else if($particular->discount_type==2)
                                echo number_format($particular->discount_value, 2).(($configuration!=NULL)?" ".$configuration->config_value:'');
                            else
                                echo "-";
                        ?>
                    </td>
                    <?php }?>
                    <?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){?>
                    <td align="center" <?php if($key+1==count($particulars)){?>style="border-bottom:1px solid #000;"<?php }?>>
                        <?php 
                            $tax    = FeeTaxes::model()->findByPk($particular->tax);
                            if($tax!=NULL)
                                echo $tax->value." %";
                            else
                                echo "-";
                        ?>
                    </td>
                    <?php }?>
                    <td align="center" <?php if($key+1==count($particulars)){?>style="border-bottom:1px solid #000;"<?php }?>>
                        <?php
                            $sub_total  += $particular->amount;
                            $amount = $particular->amount;
							if($feeconfig->discount_in_fee==1){
								//apply discount
								if($particular->discount_type==1){  //percentage
									$idiscount  = (($particular->amount * $particular->discount_value)/100);
									$amount     = $amount - $idiscount;
									$discount_total += $idiscount;
								}
								else if($particular->discount_type==2){ //amount
									$amount = $amount - $particular->discount_value;
									$discount_total += $particular->discount_value;
								}
							}
                            
							if($feeconfig->tax_in_fee==1){
								//apply tax
								if($particular->tax!=0){
									$tax    = FeeTaxes::model()->findByPk($particular->tax);
									if($tax!=NULL){
										$itax   = (($amount * $tax->value)/100);
										$amount = $amount + $itax;
										$tax_total  += $itax;
									}
								}
							}
                            
                            echo number_format($amount, 2);
                        ?>
                    </td>
                </tr>
            <?php
					$amount_total	+= $amount;
				}
			?>
            <?php if($feeconfig==NULL or ($feeconfig->discount_in_invoice==1 or $feeconfig->tax_in_fee==1)){?>
            <tr>
                <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px; border-top:1px solid #000;"><?php echo Yii::t('app','Sub Total').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center" style=" border-top:1px solid #000;"><?php echo number_format($sub_total, 2);?></td>
            </tr>
            <?php }?>
            <?php if($feeconfig==NULL or $feeconfig->discount_in_invoice==1){ ?>
            <tr>
                <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Discount').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo ($discount_total!=0)?number_format($discount_total, 2):"-";?></td>
            </tr>
            <?php } ?>
            <?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){ ?>
            <tr>
                <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Tax').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo ($tax_total!=0)?number_format($tax_total, 2):"-";?></td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Total').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo number_format($amount_total, 2);?></td>
            </tr>                        
            <tr>
                <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Amount Received').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo number_format($payments, 2);?></td>
            </tr>
            <tr>
                <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Amount Due').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo ($amount_total>$payments)?'(-)':'(+)';?> <?php echo number_format(abs($amount_payable), 2);?></td>
            </tr>
        </tbody>
    </table>
    <div class="ruppee-lttr" style="text-decoration:underline">
    	<p><?php echo Yii::t('app', 'Amount received');?> : <?php echo Yii::app()->N2W->convert(round ($payments, 2))?> <?php echo Yii::t('app', 'rupees');?> <?php echo Yii::t('app', 'only');?></p>
    </div>
</div>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="signtr-brdr">
    <tbody>
        <tr>
            <td height="10px"></td>       
        </tr>
    </tbody>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="signtr-brdr">
    <tbody>
        <tr>
            <td width="50%"></td>
            <td width="50%" align="right" style=""><?php echo Yii::t('app','Authorised Signatory');?></td>
        </tr>
    </tbody>
</table>