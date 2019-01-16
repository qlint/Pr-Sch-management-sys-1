<style>
.attendance_table table{ border-collapse:collapse}

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
	padding:8px;
	width:auto;
	font-size:13px;
	
}

.attendance_table th{
	font-size:14px;
	padding:15px;
	border:1px solid #CCC ;

}
.pdtab_Con h1{
		text-align:center;
		font-size:16px;
	]

hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}
</style>
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
<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){
	$dateformat	= $settings->dateformat;
}
else
{
	$dateformat = 'dd-mm-yy';
}
?>
    <div class="pdtab_Con" style="padding-top:0px;">
    <?php
	$course_name 	=   Courses::model()->findByAttributes(array('id'=>$course_id));
	$batch_name		=	Batches::model()->findByAttributes(array('id'=>$batch_id));
	?>
    	<h1><?php echo Yii::t('app','Fees Due Report');?><?php echo '('.$course_name->course_name.'/'.$batch_name->name.')';?></h1>
    <div class="attendance_table">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr class="pdtab-h">
                  <th align="center"><?php echo Yii::t('app','Id')?></th>
                  <th align="center"><?php echo Yii::t('app','Student Name')?></th>
                  <th align="center"><?php echo Yii::t('app','Admission No')?></th>
                  <th align="center"><?php echo Yii::t('app','Category')?></th>
                  <th align="center"><?php echo Yii::t('app','Due Date')?></th>
                  <th align="center"><?php echo Yii::t('app','Total Amount')?></th>
                  <th align="center"><?php echo Yii::t('app','Due')?></th>
              </tr>
              <?php 
              $i=1;
              foreach($model as $fees)
              { 
               $invoices =FeeInvoices::model()->findAllByAttributes(array('table_id'=>$fees->id,'user_type'=>'1','is_paid'=>'0','is_canceled'=>'0'));
              // var_dump($invoices);exit;
                foreach($invoices as $invoice){
              ?>
              <tr>
                 <td align="center"><?php echo $i;$i++; ?></td>
                 <td align="center"><?php echo $fees->first_name.' '.$fees->middle_name.' '.$fees->last_name; ?></td>
                 <td align="center"><?php echo $fees->admission_no; ?></td>
                 <?php
                 if(isset($invoice->name) and $invoice->name!=NULL){ 	
                  ?>
                  <td align="center"><?php echo $invoice->name; ?></td>
                  <?php
                 }
                 else{
                 ?>
                  <td align="center"><?php echo '-'; ?></td>
                  <?php
                 }
                 ?>
                  <?php
                 if(isset($invoice->due_date) and $invoice->due_date!=NULL){ 	
                  ?>
                  <td align="center"><?php echo date("d M Y",strtotime($invoice->due_date)); ?></td>
                  <?php
                 }
                 else{
                 ?>
                  <td align="center"><?php echo '-'; ?></td>
                  <?php
                 }
                 ?>
                 
                  <td align="center">
                  <?php
                    $invoice_amount = 0;
                    $criteria       = new CDbCriteria;
                    $criteria->compare("invoice_id", $invoice->id);
                    $particulars    = FeeInvoiceParticulars::model()->findAll($criteria);
                    foreach($particulars as $key=>$particular){
                        $amount = $particular->amount;
                        //apply discount
                        if($particular->discount_type==1){  //percentage
                            $idiscount          = (($particular->amount * $particular->discount_value)/100);
                            $amount     = $amount - $idiscount;
                        }
                        else if($particular->discount_type==2){ //amount
                            $amount = $amount - $particular->discount_value;
                        }
                        
                        //apply tax
                        if($particular->tax!=0){
                            $tax    = FeeTaxes::model()->findByPk($particular->tax);
                            if($tax!=NULL){
                                $itax   = (($amount * $tax->value)/100);
                                $amount = $amount + $itax;
                            }
                        }   
                        $invoice_amount += $amount;                                                     
                    }
                    echo number_format($invoice_amount, 2);
                ?>
                  </td>
                  <td>
                  <?php
                    $amount_payable = 0;
                    $payments       = 0;
                    $adjustments    = 0;
                    $criteria       = new CDbCriteria;
                    $criteria->compare('invoice_id', $invoice->id);
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
                    echo number_format($amount_payable, 2);
                ?>
                  </td>
                
                
              </tr>
          <?php
            }
          }
          ?>
        </table>
    
    </div>
   </div>

</div>