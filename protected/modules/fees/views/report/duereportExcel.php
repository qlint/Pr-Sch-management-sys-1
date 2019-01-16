<?php if(isset($batch_id)){
$course_name 	=   Courses::model()->findByAttributes(array('id'=>$course_id));
$batch_name		=	Batches::model()->findByAttributes(array('id'=>$batch_id));
//echo $course_name->course_name;exit;
?>
<h3 align="center"><?php echo Yii::t('app','Fees Due Report')?><?php echo '('.$course_name->course_name.'/'.$batch_name->name.')';?></h3>
 <table border="1">
                        	<thead>
                                <tr class="pdtab-h">
                                    <td align="center"><?php echo Yii::t('app','ID	');?></td> 
                                    <td align="center"><?php echo Yii::t('app','Student Name');?></td> 
                                    <td align="center"><?php echo Yii::t('app','Admission No');?></td> 
                                    <td align="center"><?php echo Yii::t('app','Category');?></td> 
                                    <td align="center"><?php echo Yii::t('app','Due Date');?></td> 
                                    <td align="center"><?php echo Yii::t('app','Total Amount');?></td> 
                                    <td align="center"><?php echo Yii::t('app','Due');?></td> 
                                </tr>                                  
                          	</thead>                        
                            <tbody>                           
                                                                                       
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
                              
                               <?php
								 }
								 ?>