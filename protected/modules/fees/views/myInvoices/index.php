<?php
	$leftside = 'mailbox.views.default.left_side';
	
	$roles=Rights::getAssignedRoles(Yii::app()->user->Id); 	
	if(sizeof($roles)==1 and key($roles) == 'parent')
		$leftside = 'application.modules.parentportal.views.default.leftside'; 
	
	$this->renderPartial($leftside);
?>
<style>
table, th, td {
	border: 1px solid black;
	border-collapse: collapse;
}
</style>
<div class="pageheader">
    <div class="col-lg-8">
        <h2><i class="fa fa-money"></i><?php echo Yii::t("app",'Invoices');?><span><?php echo Yii::t("app",'View invoices here');?></span></h2>
    </div>
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t("app", "You are here");?>:</span>
        <ol class="breadcrumb">                
            <li class="active"><?php echo Yii::t("app", "Invoices")?></li>
        </ol>
    </div>    
    <div class="clearfix"></div>    
</div>
<div class="contentpanel"> 
    <div class="panel-heading">    
		<h3 class="panel-title"><?php echo Yii::t('app','Invoices');?></h3>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li>
			<?php
				$form=$this->beginWidget('CActiveForm', array(
					'method'=>'get',
					'action' => Yii::app()->createUrl('/fees/myInvoices')
				));
				echo Yii::t('app','Viewing invoices of');
				echo " ".$form->dropDownList(
					$search, 
					'id',
					CHtml::listData($children, 'id', 'studentnameforparentportal'),
					array(
						'prompt'=>Yii::t('app','All'),
						'id'=>'studentid',
						'style'=>'width:auto;display: inline; margin-left: 7px;',
						'class'=>'form-control input-sm mb14',
						'options'=>array(
							$stdid=>array(
								'selected'=>true
							)
						),
						'onchange'=>'this.form.submit();'
					)
				);
				$this->endWidget();
            ?>
            </li>
            </ul>
            </div>
        </div>
    </div> 
    <div class="people-item">  
		<div class="table-responsive">
            <table class="table table-hover">
                <tr class="pdtab-h">
                    <td height="18" align="center"><?php echo Yii::t('app','Invoice ID'); ?></td>
                    <?php
                        if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){                      
                    ?> 
                        <td align="center"><?php echo Yii::t('app','Recipient'); ?></td>
                    <?php } ?>
                    <td height="18" align="center"><?php echo Yii::t('app','Fee Category'); ?></td>                                            
                    <td align="center"><?php echo Yii::t('app','Amount'); ?></td>
                    <td align="center"><?php echo Yii::t('app','Balance'); ?></td>
                    <td align="center"><?php echo Yii::t('app','Status'); ?></td>
                    <td align="center"><?php echo Yii::t('app','Actions'); ?></td>                            
                </tr>
                <?php
                foreach($invoices as $key=>$invoice){
                ?>
                <tr>
                    <td align="center"><?php echo $invoice->id;?></td>
                    <?php
                        if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){                      
                    ?> 
                        <td align="center">
                            <?php
                                $display_name   = "-";
                                if($invoice->table_id!=NULL and $invoice->table_id!=0){
                                    if($invoice->user_type==1){ //student
                                        $student        = Students::model()->findByPk($invoice->table_id);
                                        if($student!=NULL)
                                            $display_name   = $student->studentFullName('forParentPortal');
                                    }
                                }
                                //display name
                                if($invoice->table_id!=NULL and $invoice->table_id!=0){
                                    if($invoice->user_type==1)  //student
                                        echo CHtml::link($display_name, array('/parentportal/default/studentprofile', 'id'=>$invoice->table_id), array('target'=>'_blank'));
                                    else
                                        echo $display_name;
                                }       
                                else{
                                    echo $display_name;
                                }
                            ?>                                                                                      
                        </td>
                    <?php } ?>
                    <td align="center"><?php echo $invoice->name;?></td>
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
                    <td align="center">
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
                    <td align="center">
                        <?php
                            if($invoice->is_canceled==1)
                                echo Yii::t("app","Canceled");
                            else
                                echo ($invoice->is_paid==1)?Yii::t("app","Paid"):Yii::t("app","Unpaid");
                        ?>
                    </td>
                    <td align="center">
                        <?php echo CHtml::link("<i class='fa fa-eye'></i>", array("view", 'id'=>$invoice->id));?>
                    </td>                            
                </tr>
                <?php
                }
                
                if(count($invoices)==0){
                ?>
                <tr>
                    <td align="center" colspan="7"><?php echo Yii::t("app", "No data found");?></td>
                </tr>
                <?php
                }
                ?>
            </table>
            <div class="pagecon">
				<?php                                          
                    $this->widget('CLinkPager', array(
                    'currentPage'=>$pages->getCurrentPage(),
                    'itemCount'=>$item_count,
                    'pageSize'=>$page_size,
                    'maxButtonCount'=>5,
                    //'nextPageLabel'=>'My text >',
                    'header'=>'',
                    'htmlOptions'=>array('class'=>'pages'),
                    ));
                ?>
            </div> <!-- END div class="pagecon"-->
		</div>
	</div>
</div>