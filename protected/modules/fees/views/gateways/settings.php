<?php
	$this->breadcrumbs=array(
		Yii::t('app','Fees')=>array('/fees'),
		Yii::t('app', 'Payment Gateway')
	);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">    
            <?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" width="247">
                        <div class="cont_right formWrapper">
                            <h1><?php echo Yii::t('app','Gateway Settings'); ?></h1>
                            <?php
                            $current_payment_gateway	= FeePaymentTypes::model()->paymentGateway();

							if($current_payment_gateway==1){	// check which payment gateway
								$this->renderPartial("application.modules.fees.views.gateways.settings._paypal", array('gateway'=>$gateway));
							}
							else{
							?>
							<div class="status_box" style="width:598px; margin:40px 0 0;">
                                <div class="sb_icon"></div>
                            	<span style="color:#FF0D50"><?php echo Yii::t('app', 'Payment gateway is not activated');?></span>
                            </div>
							<?php
							}
							?>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>