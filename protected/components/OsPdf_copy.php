<?php

/**
 * PDF generator
 */
class OsPdf extends CApplicationComponent
{
    private $_language 		= "";
	private $_viewpath 		= "";
	private $_parameters	= "";
	private $_landscape		= "";
	private $_filename 		= "";
	private $_mpdf			= NULL;
	private $_rtl_languages	= array("ar");
	
    public function generate($path, $filename="report.pdf", $parameters=array(), $landscape=""){				
		$this->_language		= (isset(Yii::app()->language))?Yii::app()->language:"";
		$this->_viewpath		= $path;
		$this->_parameters		= $parameters;
		if($landscape==1)
			$this->_landscape		= "A4-L";
		$this->_filename		= $filename;
		
		include(dirname(__FILE__) .'/../vendors/MPDF/mpdf.php');	
		$this->_mpdf	= new mPDF($this->_language, $this->_landscape, '', 'freesans');		
		if(in_array($this->_language, $this->_rtl_languages)){	//if need rtl design to pdf, there must be file for rtl
			$this->_viewpath	.= "_rtl";
			$this->_mpdf->SetDirectionality('rtl');
		}
		//Yii::app()->controller->renderPartial($this->_viewpath, $this->_parameters);
		$this->_mpdf->WriteHTML(Yii::app()->controller->renderPartial($this->_viewpath, $this->_parameters, true));
		$this->_mpdf->Output($this->_filename, 'I');	
    }
}