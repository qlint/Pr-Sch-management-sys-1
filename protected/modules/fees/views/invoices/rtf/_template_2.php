<?php
//include library
Yii::import("application.extensions.PHPRtfLite.lib.PHPRtfLite");

//autoloader
PHPRtfLite::registerAutoloader();

//font family
$font_family	= 'Liberation Serif';

//font size
$n_10			= new PHPRtfLite_Font(10, $font_family);							// normal - 10px
$n_15			= new PHPRtfLite_Font(15, $font_family);						// normal - 15px
$n_20			= new PHPRtfLite_Font(20, $font_family);						// normal - 25px
$b_10			= new PHPRtfLite_Font(10, $font_family);$b_10->setBold();		// bold - 10px
$u_10			= new PHPRtfLite_Font(10, $font_family);$u_10->setUnderline();	// underline - 10px

//text alignment
$par_format_center		= new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER);
$par_format_left		= new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT);
$par_format_right		= new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_RIGHT);

//intializing object
$rtf = new PHPRtfLite();

//margins: left, top, right, bottom
$rtf->setMargins(1, .5, 1, 1);

//page format - A4, A5, etc
$rtf->setPaperFormat(PHPRtfLite_Paper_Format::FORMAT_A5);

//page orientation
$rtf->setLandscape();

//borders
$border 		= PHPRtfLite_Border::create($rtf, 1, '#000000');
$border_left 	= new PHPRtfLite_Border(
    $rtf,                                       // PHPRtfLite instance
    new PHPRtfLite_Border_Format(1, '#000000'), // left border: 2pt, green color
	NULL,	// top border: 1pt, yellow color
	NULL,	// right border: 2pt, red color
	NULL	// bottom border: 1pt, blue color
);

$border_right 	= new PHPRtfLite_Border(
    $rtf,                                       // PHPRtfLite instance
    NULL, // left border: 2pt, green color
	NULL,	// top border: 1pt, yellow color
	new PHPRtfLite_Border_Format(1, '#000000'),	// right border: 2pt, red color
	NULL	// bottom border: 1pt, blue color
);

$border_top 	= new PHPRtfLite_Border(
    $rtf,                                       // PHPRtfLite instance
    NULL, // left border: 2pt, green color
	new PHPRtfLite_Border_Format(1, '#000000'),	// top border: 1pt, yellow color
	NULL,	// right border: 2pt, red color
	NULL	// bottom border: 1pt, blue color
);

$border_bottom 	= new PHPRtfLite_Border(
    $rtf,                                       // PHPRtfLite instance
    NULL, // left border: 2pt, green color
	NULL,	// top border: 1pt, yellow color
	NULL,	// right border: 2pt, red color
	new PHPRtfLite_Border_Format(1, '#000000')	// bottom border: 1pt, blue color
);

//section
$sect = $rtf->addSection();

//fetching data
$configuration  = Configurations::model()->findByPk(5);
$college		= Configurations::model()->findAll();
$feeconfig 		= FeeConfigurations::model()->find();	//fee cofigurations
$feecat			= FeeCategories::model()->findByPk($invoice->fee_id);

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

//header
$table = $sect->addTable();
$table->addRows(4, 0.5);
$table->addColumnsList(array(19));
$table->writeToCell(1, 1, (($college!=NULL and isset($college[0]))?$college[0]->config_value:"-"), $n_20, $par_format_center);
$table->writeToCell(2, 1, (Yii::t('app', 'Affiliation No').':'.$college[21]->config_value).PHP_EOL.(($college!=NULL and isset($college[1]))?$college[1]->config_value:"-").PHP_EOL.Yii::t('app', 'Telephone').': '.(($college!=NULL and isset($college[2]))?$college[2]->config_value:"-").(($college!=NULL and isset($college[27]) and $college[27]!=NULL)?", ".$college[27]->config_value:"").' '.Yii::t('app', 'Email').': '.(($college!=NULL and isset($college[24]))?$college[24]->config_value:"-"), $n_10, $par_format_center);
$table->writeToCell(3, 1, '<hr/>', $n_10, $par_format_center);
$table->writeToCell(4, 1, Yii::t('app', 'Fee Receipt'), $n_15, $par_format_center);

//info
$table = $sect->addTable();
$table->addRows(4, .5);
$table->addColumnsList(array(2.5, .2, 4, 2.5, .2, 3, 2.5, .2, 4));

//Row 1
$table->writeToCell(1, 1, Yii::t('app', 'Receipt No'), $b_10, $par_format_left);
$table->writeToCell(1, 2, ':', $b_10, $par_format_left);
$table->writeToCell(1, 3, $invoice->id, $n_10, $par_format_left);
$table->writeToCell(1, 4, Yii::t('app', 'Date'), $b_10, $par_format_left);
$table->writeToCell(1, 5, ':', $b_10, $par_format_left);
//date
$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL and $settings->displaydate!=NULL)
	$date	= date($settings->displaydate, time());
else
	$date	= date('d M Y');
	
$table->writeToCell(1, 6, $date, $n_10, $par_format_left);
$table->writeToCell(1, 7, Yii::t('app', 'Mode'), $b_10, $par_format_left);
$table->writeToCell(1, 8, ':', $b_10, $par_format_left);
//last transaction
$last_transac	= NULL;
if(count($alltransactions)>0){
	$last_transac	= $alltransactions[0];
}

$table->writeToCell(1, 9, (($last_transac!=NULL)?$last_transac->transactionType:'-'), $n_10, $par_format_left);

//Row 2
if($last_transac!=NULL){
	if($last_transac->payment_type==3)
		$trans_info	= Yii::t('app', 'Cheque No / Bank Name');
	else
		$trans_info	= Yii::t('app', 'Transaction Info');
}
else{
	$trans_info	= Yii::t('app', 'Transaction Info');
}

$table->writeToCell(2, 1, $trans_info, $b_10, $par_format_left);
$table->writeToCell(2, 2, ':', $b_10, $par_format_left);
$table->writeToCell(2, 3, (($last_transac!=NULL)?$last_transac->transaction_id.' / '.$last_transac->description:'-'), $n_10, $par_format_left);
$table->writeToCell(2, 4, Yii::t('app', 'Term'), $b_10, $par_format_left);
$table->writeToCell(2, 5, ':', $b_10, $par_format_left);
$table->mergeCellRange(2, 6, 2, 9);
$table->writeToCell(2, 6, date('F', strtotime($invoice->due_date)), $n_10, $par_format_left);

//Row 3
$table->writeToCell(3, 1, Yii::t('app', 'Name'), $b_10, $par_format_left);
$table->writeToCell(3, 2, ':', $b_10, $par_format_left);
//name
$display_name   = "-";
if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){	
	if($invoice->table_id!=NULL and $invoice->table_id!=0){
		if($invoice->user_type==1){ //student
			$student        = Students::model()->findByPk($invoice->table_id);
			if($student!=NULL)
				$display_name   = $student->studentFullName("forStudentProfile");
		}
	}
}

$table->writeToCell(3, 3, $display_name, $n_10, $par_format_left);
$table->writeToCell(3, 4, Yii::t('app', 'Student ID'), $b_10, $par_format_left);
$table->writeToCell(3, 5, ':', $b_10, $par_format_left);
//student_id
$student_id	= '';
if($student==NULL){
	if($invoice->table_id!=NULL and $invoice->table_id!=0){
		if($invoice->user_type==1){ //student
			$student        = Students::model()->findByPk($invoice->table_id);
		}
	}
}
$student_id	= ($student!=NULL and $student->admission_no!=NULL)?$student->admission_no:'-';

$table->writeToCell(3, 6, $student_id, $n_10, $par_format_left);
$table->writeToCell(3, 7, Yii::t('app', 'S/d of'), $b_10, $par_format_left);
$table->writeToCell(3, 8, ':', $b_10, $par_format_left);
//guardian
$guardian_name	= '-';
if($student!=NULL){
	$guardian		= $student->getPrimaryGuardian($student->id);
	$guardian_name	= ($guardian!=NULL)?$guardian->fullname:'-';
}

$table->writeToCell(3, 9, $guardian_name, $n_10, $par_format_left);

//Row 4
$table->writeToCell(4, 1, Yii::t('app', 'Roll no'), $b_10, $par_format_left);
$table->writeToCell(4, 2, ':', $b_10, $par_format_left);
$table->writeToCell(4, 3, (($student!=NULL and $student->class_roll_no!=NULL)?$student->class_roll_no:'-'), $n_10, $par_format_left);
//course & batch
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

$table->writeToCell(4, 4, Yii::t('app', 'Class'), $b_10, $par_format_left);
$table->writeToCell(4, 5, ':', $b_10, $par_format_left);
$table->writeToCell(4, 6, $course_name, $n_10, $par_format_left);
$table->writeToCell(4, 7, Yii::t('app', 'Section'), $b_10, $par_format_left);
$table->writeToCell(4, 8, ':', $b_10, $par_format_left);
$table->writeToCell(4, 9, $batch_name, $n_10, $par_format_left);

//invoice particulars
$row			= 1;
$column			= 1;
$colspan		= 0;
$pt_col_width	= 16;
if($feeconfig==NULL or $feeconfig->discount_in_invoice==1 or $feeconfig->tax_in_fee==1){
	$colspan++;
	$pt_col_width	-= 3;
}
if($feeconfig==NULL or $feeconfig->discount_in_invoice==1){
	$colspan++;
	$pt_col_width	-= 3;
}
if($feeconfig==NULL or $feeconfig->tax_in_fee==1){
	$colspan++;
	$pt_col_width	-= 3;
}

$table 	= $sect->addTable();
$table->addRow(.1);
$table->addColumn($pt_col_width);

$table->writeToCell($row, $column, Yii::t('app','Particulars'), $b_10, $par_format_left);
$cell = $table->getCell($row, $column);
$cell->setCellPaddings(0.1, 0.1, 0.1, 0.1);

if($feeconfig==NULL or $feeconfig->discount_in_invoice==1 or $feeconfig->tax_in_fee==1){
	$column++;
	$table->addColumn(3);
	$table->writeToCell($row, $column, Yii::t('app','Unit Price'), $b_10, $par_format_center);
}

if($feeconfig==NULL or $feeconfig->discount_in_invoice==1){
	$column++;
	$table->addColumn(3);
	$table->writeToCell($row, $column, Yii::t('app','Discount'), $b_10, $par_format_center);	
}

if($feeconfig==NULL or $feeconfig->tax_in_fee==1){
	$column++;
	$table->addColumn(3);
	$table->writeToCell($row, $column, Yii::t('app','Tax'), $b_10, $par_format_center);	
}

$column++;
$table->addColumn(3);
$table->writeToCell($row, $column, Yii::t('app','Amount'), $b_10, $par_format_center);
$table->setBorderForCellRange($border, $row, 1, $row, $column);

$amount_total  	= 0;
$fine_total     = 0;

$sub_total      = 0;
$discount_total = 0;
$tax_total      = 0;
foreach($particulars as $key=>$particular){
	$column		= 1;
	//amount
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
	
	$amount_total	+= $amount;
	
	$row++;
	$table->addRow(.1);	
	$table->writeToCell($row, $column, $particular->name, $n_10, $par_format_left);	
	$cell = $table->getCell($row, $column);
	$cell->setCellPaddings(0.1, 0.1, 0.1, 0.1);
		
	if($feeconfig==NULL or $feeconfig->discount_in_invoice==1 or $feeconfig->tax_in_fee==1){
		$column++;
		$table->addColumn(3);
		$table->writeToCell($row, $column, number_format($particular->amount, 2), $n_10, $par_format_center);
	}
	
	if($feeconfig==NULL or $feeconfig->discount_in_invoice==1){
		$column++;
		$table->addColumn(3);
		if($particular->discount_type==1)
			$discount	= $particular->discount_value." %";
		else if($particular->discount_type==2)
			$discount	= number_format($particular->discount_value, 2).(($configuration!=NULL)?" ".$configuration->config_value:'');
		else
			$discount	= "-";
		$table->writeToCell($row, $column, $discount, $n_10, $par_format_center);	
	}
	
	if($feeconfig==NULL or $feeconfig->tax_in_fee==1){
		$column++;
		$table->addColumn(3);
		$tax_value	= "-";
		$tax    	= FeeTaxes::model()->findByPk($particular->tax);
		if($tax!=NULL)
			$tax_value	= $tax->value." %";
			
		$table->writeToCell($row, $column, $tax_value, $n_10, $par_format_center);	
	}
	
	$column++;
	$table->writeToCell($row, $column, number_format($amount, 2), $n_10, $par_format_center);
}

$table->setBorderForCellRange($border_bottom, $row, 1, $row, $column);

//sub total
if($feeconfig==NULL or ($feeconfig->discount_in_invoice==1 or $feeconfig->tax_in_fee==1)){
	$row++;
	$table->addRow(.1);
	if($colspan>0)
		$table->mergeCellRange($row, 1, $row, $colspan+1);	
	$table->writeToCell($row, 1, Yii::t('app','Sub Total').(($configuration!=NULL)?" (".$configuration->config_value.")":''), $n_10, $par_format_right);
	$cell = $table->getCell($row, 1);
	$cell->setCellPaddings(0.1, 0.1, 0.1, 0.1);
	$table->writeToCell($row, $colspan+2, number_format($sub_total, 2), $n_10, $par_format_center);
}

//discount, if enabled
if($feeconfig==NULL or $feeconfig->discount_in_invoice==1){
	$row++;
	$table->addRow(.1);	
	if($colspan>0)
		$table->mergeCellRange($row, 1, $row, $colspan+1);
	$table->writeToCell($row, 1, Yii::t('app','Discount').(($configuration!=NULL)?" (".$configuration->config_value.")":''), $n_10, $par_format_right);
	$cell = $table->getCell($row, 1);
	$cell->setCellPaddings(0.1, 0.1, 0.1, 0.1);
	$table->writeToCell($row, $colspan+2, (($discount_total!=0)?number_format($discount_total, 2):"-"), $n_10, $par_format_center);
}

//tax, if enabled
if($feeconfig==NULL or $feeconfig->tax_in_fee==1){
	$row++;
	$table->addRow(.1);
	if($colspan>0)
		$table->mergeCellRange($row, 1, $row, $colspan+1);	
	$table->writeToCell($row, 1, Yii::t('app','Tax').(($configuration!=NULL)?" (".$configuration->config_value.")":''), $n_10, $par_format_right);
	$cell = $table->getCell($row, 1);
	$cell->setCellPaddings(0.1, 0.1, 0.1, 0.1);
	$table->writeToCell($row, $colspan+2, (($tax_total!=0)?number_format($tax_total, 2):"-"), $n_10, $par_format_center);
}

//total
$row++;
$table->addRow(.1);
if($colspan>0)
	$table->mergeCellRange($row, 1, $row, $colspan+1);
$table->writeToCell($row, 1, Yii::t('app','Total').(($configuration!=NULL)?" (".$configuration->config_value.")":''), $n_10, $par_format_right);
$cell = $table->getCell($row, 1);
$cell->setCellPaddings(0.1, 0.1, 0.1, 0.1);
$table->writeToCell($row, $colspan+2, number_format($amount_total, 2), $n_10, $par_format_center);

//amount received
$row++;
$table->addRow(.1);
if($colspan>0)
	$table->mergeCellRange($row, 1, $row, $colspan+1);
$table->writeToCell($row, 1, Yii::t('app','Amount Received').(($configuration!=NULL)?" (".$configuration->config_value.")":''), $n_10, $par_format_right);
$cell = $table->getCell($row, 1);
$cell->setCellPaddings(0.1, 0.1, 0.1, 0.1);
$table->writeToCell($row, $colspan+2, number_format($payments, 2), $n_10, $par_format_center);

//amount due
$row++;
$table->addRow(.1);
if($colspan>0)
	$table->mergeCellRange($row, 1, $row, $colspan+1);
$table->writeToCell($row, 1, Yii::t('app','Amount Due').(($configuration!=NULL)?" (".$configuration->config_value.")":''), $n_10, $par_format_right);
$cell = $table->getCell($row, 1);
$cell->setCellPaddings(0.1, 0.1, 0.1, 0.1);
$table->writeToCell($row, $colspan+2, (($amount_total>$payments)?'(-)':'(+)').number_format(abs($amount_payable), 2), $n_10, $par_format_center);

$table->setBorderForCellRange($border_left, 2, 1, $row, 2);
$table->setBorderForCellRange($border_right, 2, 1, $row, $column);
$table->setBorderForCellRange($border_bottom, $row, 1, $row, $column);

$row++;
$table->addRow(.1);
if($colspan>0)
	$table->mergeCellRange($row, 1, $row, $colspan+2);
$table->writeToCell($row, 1, Yii::t('app', 'Amount received').' : '.Yii::app()->N2W->convert(round ($payments, 2)).' '.(($configuration!=NULL and $configuration->config_value=="INR")?(Yii::t('app', 'rupees').' '):'').Yii::t('app', 'only'), $u_10, $par_format_left);
$cell = $table->getCell($row, 1);
$cell->setCellPaddings(0.1, 0.1, 0.1, 0.1);

$row++;
$table->addRow(.1);
$table->mergeCellRange($row, 1, $row, $colspan+2);
$table->writeToCell($row, 1, Yii::t('app','Authorised Signatory'), $n_10, $par_format_right);
$cell = $table->getCell($row, 1);
$cell->setCellPaddings(0.1, 0.1, 0.1, 0.1);

// download rtf document
$rtf->sendRtf('invoice-'.$invoice->id.'.rtf');