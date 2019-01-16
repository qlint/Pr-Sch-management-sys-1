<?php
	$this->breadcrumbs=array(
		Yii::t('app','Fees')=>array('/fees'),
	);
$created_at	= '';
$is_paid	= '';
$uid		= '';
$course		= '';
$batch		= '';
$id			= '';
$fee_id		= '';
	//fee id
if(isset($_REQUEST['FeeInvoices']['fee_id']) and $_REQUEST['FeeInvoices']['fee_id']!=NULL){	
	$fee_id	=	$_REQUEST['FeeInvoices']['fee_id'];
}
//invoice id
if(isset($_REQUEST['FeeInvoices']['id']) and $_REQUEST['FeeInvoices']['id']!=NULL){
	$id		=	$_REQUEST['FeeInvoices']['id'];
}
//course
if(isset($_REQUEST['FeeInvoices']['course']) and $_REQUEST['FeeInvoices']['course']!=NULL){
	
	$course	=	$_REQUEST['FeeInvoices']['course'];
}
//batch
if(isset($_REQUEST['FeeInvoices']['batch']) and $_REQUEST['FeeInvoices']['batch']!=NULL){
	$batch	=	$_REQUEST['FeeInvoices']['batch'];
}
//invoice recipient
if(isset($_REQUEST['FeeInvoices']['uid']) and $_REQUEST['FeeInvoices']['uid']!=NULL){
	$uid	=	$_REQUEST['FeeInvoices']['uid'];
}
if(isset($_REQUEST['FeeInvoices']['is_paid']) and $_REQUEST['FeeInvoices']['is_paid']!=NULL){
	$is_paid	=	$_REQUEST['FeeInvoices']['is_paid'];
}
//invoice date
if(isset($_REQUEST['FeeInvoices']['created_at']) and $_REQUEST['FeeInvoices']['created_at']!=NULL){
	$created_at	=	$_REQUEST['FeeInvoices']['created_at'];
}

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">    
            <?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" width="75%">
                        <div class="cont_right formWrapper">
                            <h1><?php echo Yii::t('app','Manage Invoices'); ?></h1>
                            <div class="overview">
                                <div class="overviewbox overviewbox-onerow  ovbox1">
                                    <h1><strong><?php echo Yii::t('app','Total Invoices'); ?></strong></h1>
                                    <div class="ovrBtm">
                                    	<?php echo CHtml::link($total_invoices, array("/fees/invoices"));?>
                                    </div>
                                </div>
                                <div class="overviewbox overviewbox-onerow  ovbox2">
                                    <h1><strong><?php echo Yii::t('app','Paid Invoices'); ?></strong></h1>
                                    <div class="ovrBtm">
                                    	<?php echo CHtml::link(count($paid_invoices), array("/fees/invoices", "FeeInvoices"=>array("is_paid"=>1)));?>
                                    </div>
                                </div>
                                <div class="overviewbox overviewbox-onerow  ovbox2">
                                    <h1><strong><?php echo Yii::t('app','Un-Paid Invoices'); ?></strong></h1>
                                    <div class="ovrBtm">
                                    	<?php echo CHtml::link(($total_invoices - count($paid_invoices))-$canceled, array("/fees/invoices", "FeeInvoices"=>array("is_paid"=>0)));?>
                                    </div>
                                </div>
                                <div class="overviewbox overviewbox-onerow  ovbox2">
                                    <h1><strong><?php echo Yii::t('app','Cancelled');?></strong></h1>
                                    <div class="ovrBtm">
                                    	<?php echo CHtml::link($canceled, array("/fees/invoices", "FeeInvoices"=>array("is_paid"=>-1)));?>
                                    </div>
                                </div>
                                <div class="clear"></div>                            
                            </div>
                            <!-- categories -->

							<div class="clear"></div><br />


                            <!--filters-->
                            <?php $this->renderPartial('_filter', array('search'=>$search));?>
                            <!--filters-->
                            
                            
                            <div class="pdf-box">
                                <div class="box-one">
                                    <div class="bttns_addstudent-n">
                                        <ul>
                                        <li><a href='javascript:void(0);' class='formbut-n' id='mark-as-cancel'><?php echo Yii::t("app", "Cancel");?></a></li>
                                        <li><a href='javascript:void(0);' class='formbut-n' id='send-reminder'><?php echo Yii::t("app", "Send Reminder");?></a></li>
                                        </ul>
                                    </div>
                                </div>
                            <div class="box-two">
                            <div class="bttns_addstudent-n">
                            <?php
							if(count($invoices)>0){	?>
                            			<ul>
                                        <li>
                                        <?php 
											$url	= array("/fees/invoices/exportcsv");
											$url['FeeInvoices']	= $_GET['FeeInvoices'];
											echo CHtml::link(Yii::t("app", "Export CSV"), $url, array('class'=>'expertcv'));
										?>
                                        </li>
                                        <li>
                                        <?php 
										 	echo CHtml::link(Yii::t('app', 'Generate PDF'), array('/fees/invoices/invoicepdf','fee_id'=>$fee_id,'id'=>$id,'course'=>$course,'batch'=>$batch,'uid'=>$uid,'is_paid'=>$is_paid,'created_at'=>$created_at
),array('target'=>"_blank",'class'=>'pdf_but','class'=>'pdf_but')); 
										?>
                                        </li>
                                        </ul>
                                        <?php  } ?>
                            
                            </div>
                            </div>
                            </div>

                            <div class="clear"></div>
                            <div style="width:100%; padding-top:0px;" class="pdtab_Con">
                            	<?php $form=$this->beginWidget('CActiveForm', array(
                                    'id'=>'invoices-form',
                                    'enableAjaxValidation'=>false,
                                )); ?>
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                            <tr class="pdtab-h">
                                                <td><?php echo CHtml::checkBox('check-all', '', array('id'=>'check-all'));?></td>
                                            	<td height="18" align="center"><?php echo Yii::t('app','Invoice ID'); ?></td>
                                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                                <td align="center"><?php echo Yii::t('app','Recipient'); ?></td>
                                                <?php
                                                }
                                                ?>
                                                <td height="18" align="center"><?php echo Yii::t('app','Fee Category'); ?></td>                                            
                                                <td align="center"><?php echo Yii::t('app','Amount'); ?></td>
                                                <td align="center"><?php echo Yii::t('app','Balance'); ?></td>
                                                <td align="center"><?php echo Yii::t('app','Date'); ?></td>
                                                <td align="center"><?php echo Yii::t('app','Status'); ?></td>
                                                <td align="center"><?php echo Yii::t('app','Actions'); ?></td>                            
                                            </tr>
                                            <?php
                                            foreach($invoices as $key=>$invoice){
    										?>
                                            <tr>
                                                <td><?php echo CHtml::checkBox('invoice-check[]', '', array('class'=>'invoice-check', 'value'=>$invoice->id));?></td>
                                            	<td align="center"><?php echo $invoice->id;?></td>
                                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                                <td align="center">
                                                	<?php
                                                        $display_name   = "-";
                                                        if($invoice->table_id!=NULL and $invoice->table_id!=0){
                                                            if($invoice->user_type==1){ //student
                                                                $student        = Students::model()->findByPk($invoice->table_id);
                                                                if($student!=NULL)
                                                                    $display_name   = $student->studentFullName("forStudentProfile");
                                                            }
                                                        }
                                                        //display name
                                                        if($invoice->table_id!=NULL and $invoice->table_id!=0){
                                                            if($invoice->user_type==1)  //student
                                                                echo CHtml::link($display_name, array('/students/students/view', 'id'=>$invoice->table_id));
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
														$settings       = UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
														if($settings!=NULL)
														   $displaydate	=	$settings->displaydate;
														else
														   $displaydate	=	date('d M Y');  
														echo date($displaydate, strtotime($invoice->due_date));
													   
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
                                                	<?php echo CHtml::link(Yii::t("app", "View"), array("view", 'id'=>$invoice->id));?>
                                                </td>                            
                                            </tr>
                                            <?php
    										}
    										
    										if(count($invoices)==0){
    										?>
                                            <tr>
                                            	<td align="center" colspan="8"><?php echo Yii::t("app", "No data found");?></td>
                                            </tr>
                                            <?php
    										}
    										?>                       
                                        </tbody>                                                       
                                    </table>
                                <?php $this->endWidget();?>
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
                                <div class="clear"></div>
                        	</div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<script type="text/javascript">
$('#check-all').change(function(){
    if($(this).is(':checked')){
        $('.invoice-check').attr('checked', true);
    }
    else{
        $('.invoice-check').attr('checked', false);
    }
});

$('.invoice-check').change(function(){
    if($('.invoice-check').length==$('.invoice-check:checked').length){
        $('#check-all').attr('checked', true);
    }
    else{
        $('#check-all').attr('checked', false);
    }
});

$('#mark-as-paid').click(function(){
    if($('.invoice-check:checked').length==0){
        alert("<?php echo Yii::t("app", "Please select atleast one invoice");?>");
    }
    else{
        var datas   = $('form#invoices-form').serialize();
        if(confirm("<?php echo Yii::t("app", "Are you sure mark these invoices paid ?");?>")){
            $.ajax({
                url:"<?php echo Yii::app()->createUrl("/fees/invoices/markaspaid");?>",
                type:"POST",
                data:datas,
                dataType:"json",
                success:function(response){
                    if(response.status=="success"){
                        window.location.reload();
                    }
                    else{
                        alert("<?php echo Yii::t("app", "Some problem found while marking invoices as paid")?>");
                    }
                },
                error:function(){
                    alert("<?php echo Yii::t("app", "Some problem found while marking invoices as paid")?>");
                }
            });
        }
    }
});

$('#mark-as-unpaid').click(function(){
    if($('.invoice-check:checked').length==0){
        alert("<?php echo Yii::t("app", "Please select atleast one invoice");?>");
    }
    else{
        var datas   = $('form#invoices-form').serialize();
        if(confirm("<?php echo Yii::t("app", "Are you sure mark these invoices unpaid ?");?>")){
            $.ajax({
                url:"<?php echo Yii::app()->createUrl("/fees/invoices/markasunpaid");?>",
                type:"POST",
                data:datas,
                dataType:"json",
                success:function(response){
                    if(response.status=="success"){
                        window.location.reload();
                    }
                    else{
                        alert("<?php echo Yii::t("app", "Some problem found while marking invoices as unpaid")?>");
                    }
                },
                error:function(){
                    alert("<?php echo Yii::t("app", "Some problem found while marking invoices as unpaid")?>");
                }
            });
        }
    }
});

$('#mark-as-cancel').click(function(){
    if($('.invoice-check:checked').length==0){
        alert("<?php echo Yii::t("app", "Please select atleast one invoice");?>");
    }
    else{
        var datas   = $('form#invoices-form').serialize();
        if(confirm("<?php echo Yii::t("app", "Are you sure cancel these invoices ?");?>")){
            $.ajax({
                url:"<?php echo Yii::app()->createUrl("/fees/invoices/markascancel");?>",
                type:"POST",
                data:datas,
                dataType:"json",
                success:function(response){
                    if(response.status=="success"){
                        window.location.reload();
                    }
                    else{
                        alert("<?php echo Yii::t("app", "Some problem found while canceling these invoices")?>");
                    }
                },
                error:function(){
                    alert("<?php echo Yii::t("app", "Some problem found while canceling these invoices")?>");
                }
            });
        }
    }
});

$('#send-reminder').click(function(){
    if($('.invoice-check:checked').length==0){
        alert("<?php echo Yii::t("app", "Please select atleast one invoice");?>");
    }
    else{
        var datas   = $('form#invoices-form').serialize();
        if(confirm("<?php echo Yii::t("app", "Are you sure send reminder for these invoices ?");?>")){
            $.ajax({
                url:"<?php echo Yii::app()->createUrl("/fees/invoices/sendreminder");?>",
                type:"POST",
                data:datas,
                dataType:"json",
                success:function(response){
                    if(response.status=="success"){
						alert("<?php echo Yii::t("app", "Reminder send succesfully")?>");
                        window.location.reload();
                    }
                    else{
                        alert("<?php echo Yii::t("app", "Some problem found while sending reminder to these invoices")?>");
                    }
                },
                error:function(){
                    alert("<?php echo Yii::t("app", "Some problem found while  sending reminder to these invoices")?>");
                }
            });
        }
    }
});
</script>