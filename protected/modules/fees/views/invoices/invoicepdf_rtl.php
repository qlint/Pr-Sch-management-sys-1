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
padding:8px;
	width:auto;
	font-size:13px;
	
}

.attendance_table th{
	font-size:14px;
	padding:15px;
	border-left:1px #CCC solid;
	border-bottom:1px #CCC solid;
}

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
$settings			= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){
	$displaydate	= $settings->displaydate;	
}else{
	$settings		= UserSettings::model()->findByAttributes(array('user_id'=>1));
	$displaydate	= $settings->displaydate;
} 

//take value


if(Yii::app()->user->year)
{
	$year = Yii::app()->user->year;
}
else
{
	$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
	$year = $current_academic_yr->config_value;
}

//all invoices
$criteria					= new CDbCriteria;		
$criteria->condition		= 'academic_year_id=:yr';
$criteria->params[':yr'] 	= $year;
$total_invoices		= FeeInvoices::model()->count($criteria);

//filtered invoices
$search				= new FeeInvoices;
$search->uid		= "";
$search->is_paid	= "";

$page_size			= 20;
$criteria			= new CDbCriteria;
$criteria->condition		= 'academic_year_id=:yr';
$criteria->params[':yr'] 	= $year;

//conditions

//fee id
if(isset($_REQUEST['fee_id']) and $_REQUEST['fee_id']!=NULL){	
	$search->fee_id 		= $_REQUEST['fee_id'];
	$criteria->compare('t.fee_id', $search->fee_id);
	//var_dump($criteria);exit;
	
}

//invoice id
if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL){
	$search->id 		= $_REQUEST['id'];
	$criteria->compare('t.id', $search->id);
}

//course
if(isset($_REQUEST['course']) and $_REQUEST['course']!=NULL){
	$search->course 		= $_REQUEST['course'];
}

//batch
if(isset($_REQUEST['batch']) and $_REQUEST['batch']!=NULL){
	$search->batch 		= $_REQUEST['batch'];
	if($criteria->join!="")
		$criteria->join		.= " JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`table_id` JOIN `students` `s` ON `s`.`id`=`bs`.`student_id`";
	else
		$criteria->join		= "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`table_id` JOIN `students` `s` ON `s`.`id`=`bs`.`student_id`";
	
	$criteria->compare('`bs`.`batch_id`', $search->batch);
	$criteria->compare('`bs`.`status`', 1);
	$criteria->compare('`s`.`is_active`', 1);
	$criteria->compare('`s`.`is_deleted`', 0);
}
else if(isset($_REQUEST['course']) and $_REQUEST['course']!=NULL){
	$search->course 		= $_REQUEST['course'];
	$search->batch 		= $_REQUEST['batch'];
	if($criteria->join!="")
		$criteria->join		.= " JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`table_id` JOIN `batches` `b` ON `b`.`id`=`bs`.`batch_id` JOIN `students` `s` ON `s`.`id`=`bs`.`student_id`";
	else
		$criteria->join		= "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`table_id` JOIN `batches` `b` ON `b`.`id`=`bs`.`batch_id` JOIN `students` `s` ON `s`.`id`=`bs`.`student_id`";
	
	$criteria->compare('`b`.`course_id`', $search->course);
	$criteria->compare('`bs`.`status`', 1);
	$criteria->compare('`s`.`is_active`', 1);
	$criteria->compare('`s`.`is_deleted`', 0);
}

//invoice recipient
if(isset($_REQUEST['uid']) and $_REQUEST['uid']!=NULL){
	$search->uid 		= $_REQUEST['uid'];
	if($criteria->join=="")
		$criteria->join		= "JOIN `students` `s` ON `s`.`id`=`t`.`table_id`";
	
	if((substr_count( $_REQUEST['uid'],' '))==0){
		if($criteria->condition!="")
			$criteria->condition		.=	" AND ";
			
		$criteria->condition		.= '(s.first_name LIKE :name or s.last_name LIKE :name or s.middle_name LIKE :name)';
		$criteria->params[':name'] 	= $_REQUEST['uid'].'%';
	}
	else if((substr_count( $_REQUEST['uid'],' '))>=1){
		$name						= explode(" ",$_REQUEST['uid']);
		if($criteria->condition!="")
			$criteria->condition		.=	" AND ";	
			
		$criteria->condition		.= '(s.first_name LIKE :name or s.last_name LIKE :name or s.middle_name LIKE :name) and (s.first_name LIKE :name1 or s.last_name LIKE :name1 or s.middle_name LIKE :name1)';
		$criteria->params[':name'] 	= $name[0].'%';
		$criteria->params[':name1'] = $name[1].'%';			
	}
}

//invoice status
if(isset($_REQUEST['is_paid']) and $_REQUEST['is_paid']!=NULL){
	$search->is_paid 		= $_REQUEST['is_paid'];
	if($search->is_paid==-1)
		$criteria->compare('t.is_canceled', 1);
	else{
		$criteria->compare('t.is_paid', $search->is_paid);
		$criteria->compare('t.is_canceled', "=0");
	}
}

//invoice date
if(isset($_REQUEST['created_at']) and $_REQUEST['created_at']!=NULL){
	$search->created_at 		= date("Y-m-d", strtotime($_REQUEST['created_at']));
	$criteria->compare('STR_TO_DATE(t.created_at, "%Y-%m-%d")', $search->created_at);
	if($settings!=NULL and $settings->displaydate!=NULL){
		$dateformat	= $settings->displaydate;
	}
	else
		$dateformat = 'd M Y';
	$search->created_at	= date($dateformat, strtotime($search->created_at));
}
$criteria->order	= "`t`.`id` DESC";
$invoices	= FeeInvoices::model()->findAll($criteria);


?>
<div align="center" style="text-align:center; display:block;"> <?php  echo Yii::t('app','INVOICE DETAILS');   ?> </div>
<div class="tablebx">
    <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
        <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
        	<td><?php echo Yii::t('app','Sl No');?></td>
            <td><?php echo Yii::t('app','Invoice ID');?></td>
            <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                    <td align="center"><?php echo Yii::t('app','Recipient'); ?></td>
                    <?php
                    }
                    ?>
                <td><?php echo    Yii::t("app", 'Invoice Date');?>
            <td><?php echo Yii::t('app','Fee Category');?></td>
          <td align="center"><?php echo Yii::t('app','Amount'); ?></td>
            <td align="center"><?php echo Yii::t('app','Balance'); ?></td>
            <td align="center"><?php echo Yii::t('app','Status'); ?></td>
        </tr>
        <?php
		$i=1;
		foreach($invoices as $key=>$invoice){
			
			?>
            <tr>
          	    <td><?php echo $i++;?></td>
                <td style="padding-top:10px; padding-bottom:10px;"><?php echo $invoice->id;?></td>
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
                                echo $display_name;
                            else
                                echo $display_name;
                        }       
                        else{
                            echo $display_name;
                        }
                    ?>                                            	                                        
                </td>
                <?php } ?>
               <td align="center"><?php echo date($displaydate,strtotime($invoice-> created_at));?></td>
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
            </tr>
            <?php
		
			}?>
            
  </table>
</div>


<div class="tablebx">
                        <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
        <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
                                <td><?php echo Yii::t('app','Sl No');?></td>
          <td><?php echo Yii::t('app','Purchased by');?></td>
                                <td><?php echo Yii::t('app','Item name');?></td>
                                <td><?php echo Yii::t('app','Purchased Date');?></td>
                            </tr>