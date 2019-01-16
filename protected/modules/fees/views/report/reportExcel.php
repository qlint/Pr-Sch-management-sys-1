<?php if(isset($start_date)){ ?>
   <h3 align="center"><?php echo Yii::t('app','Daily Collection Report').'('.date("d/m/y", strtotime($start_date)). 'to' .date("d/m/y", strtotime($end_date)).')';?></h3>
 <table border="1">
                        	<thead>
                                <tr class="pdtab-h">
                                    <td align="center"><?php echo Yii::t('app','ID	');?></td> 
                                    <td align="center"><?php echo Yii::t('app','INVOICE ID');?></td> 
                                    <td align="center"><?php echo Yii::t('app','CATEGORY');?></td> 
                                    <td align="center"><?php echo Yii::t('app','DATE	');?></td> 
                                    <td align="center"><?php echo Yii::t('app','PAYMENT TYPE');?></td> 
                                    <td align="center"><?php echo Yii::t('app','TRANSACTION ID');?></td> 
                                    <td align="center"><?php echo Yii::t('app','DESCRIPTION	');?></td> 
                                    <td align="center"><?php echo Yii::t('app','AMOUNT');?></td> 
                                </tr>                                  
                          	</thead>                        
                            <tbody>                           
                                                                                       
                                <?php
                                if(isset($model) and $model !=NULL)
                                {
                                    $i=1;
                                    foreach($model as $fees)
                                    {
                                            $sum = $sum+$fees->amount;
                                    ?>
                                    <tr class="data_tr">
                                            <td align="center"><?php echo $i; ?></td>
                                            <td align="center"><?php echo $fees->invoice_id; ?></td>
                                            <?php
											$invoice = FeeInvoices::model()-> findByAttributes(array('id'=>$fees->invoice_id));
											$category =FeeCategories::model()-> findByAttributes(array('id'=>$invoice->fee_id));
											?>
                                            <td align="center"><?php echo $category->name; ?></td>
                                            <td align="center"><?php echo date("d M Y", strtotime($fees->date)); ?></td>
                                            <td align="center"><?php echo $fees->invoice_id; ?></td>
                                            <td align="center"><?php echo $fees->transaction_id; ?></td>
                                            <td align="center"><?php echo $fees->description; ?></td>
                                            <td align="center"><?php echo $fees->amount; ?></td>
                                           
                                    </tr>

                                    <?php	
                                            $i++;										
                                    }
                                    ?>
                                        <tr class="data_tr">
                                            <td colspan="7" align="center">
                                                <?php echo Yii::t("app", "Total"); ?>
                                            </td>
                                            <td align="center">
                                                <?php echo number_format($sum, 2, '.', ''); ?>
                                            </td>                                            
                                        </tr>                                    
                                <?php }
                                else
                                {
                                    ?>
                                        <tr><td colspan="8"><center><?php echo Yii::t("app", "No Result Found"); ?></center></td></tr>
                                        <?php
                                }
                                
                                ?>
                                </tbody>
                            </table>   


<?php } ?>
