<?php
	// check guardians language, and use rtl template if needed
	$this->renderPartial("application.modules.fees.views.invoices.email._template", array('invoice'=>$invoice, 'particulars'=>$particulars));
?>