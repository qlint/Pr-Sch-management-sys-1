<?php

class TransportationController extends RController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','autocomplete','routes','studentsearch','autocomplete1','settings','error','reallot','remove','allotstudent'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionInvoiceall()
	{
		//get academic year
		if(Yii::app()->user->year){
			$year 					= Yii::app()->user->year;
		}
		else{
			$current_academic_yr 	= Configurations::model()->findByAttributes(array('id'=>35));
			$year 					= $current_academic_yr->config_value;
		}
		
		//generate invoice
		if(isset($_REQUEST['invoice_month']) and $_REQUEST['invoice_month']!=NULL ){
			$month_val		=	date('F Y',strtotime($_REQUEST['invoice_month']));
			$month			=	date('m',strtotime($_REQUEST['invoice_month']));
			$month_year		=	date('Y',strtotime($_REQUEST['invoice_month']));
			$month_y		=	date('Ym',strtotime($_REQUEST['invoice_month']));
			$month_year_j	=	date('m-Y',strtotime($_REQUEST['invoice_month']));
			$routes = Transportation::model()->findAll();//take all routes
			
			if($routes){
				$invoice_total	=	0;
				$al_total		=  	0;
				foreach($routes as $route1){
					
					$stop	=	StopDetails::model()->findByAttributes(array('id'=>$route1->stop_id));
					$route	=	RouteDetails::model()->findByAttributes(array('id'=>$stop->route_id));
					$criteria = new CDbCriteria;
					$criteria->condition	= 'id =:id AND EXTRACT( YEAR_MONTH FROM `admission_date`) <= :month_y AND is_deleted = 0 AND is_active = 1';// check  accademic year 
					$criteria->params		= array(':id'=>$route1->student_id,':month_y'=>$month_y);
					
					$student=Students::model()->findAll($criteria);
					if(isset($student) and $student!=NULL){
						$al_total++;
						if($year){									
							$category	= new FeeCategories;														
							//add data into category table
							$category->name		= "Transportation Fee - ".$month_val;
							
							$fc					= FeeCategories::model()->findByAttributes(array('name'=>$category->name));
							
							if(!isset($fc) and $fc ==NULL){
								$acyear		= AcademicYears::model()->findByPk($year);
								$ac_start	= date('m-Y',strtotime($acyear->start));
								$ac_end		= date('m-Y',strtotime($acyear->end));
																
								$category->academic_year_id	= $year;
								$category->type				= 2;
								$category->description		= $category->name;
								$category->subscription_type= 6;
								// accademic year check
								if($month_year_j == $ac_start)
								  $category->start_date		=  date('Y-m-d',strtotime($acyear->start));
								else
								  $category->start_date		= date('Y-m-1',strtotime(date('1-'.$month.'-'.$month_year)));
								
								if($month_year_j == $ac_end)
								  $category->end_date		=date('Y-m-d',strtotime($acyear->end));
								else
								  $category->end_date		= date('Y-m-t',strtotime(date('1-'.$month.'-'.$month_year)));
								  
								$category->invoice_generated= 1;
								$category->amount_divided	= 0;
								$category->academic_year_id	= $year;
								$category->created_by		= Yii::app()->user->id;
								$category->created_at		= date("Y-m-d h:i:s");
								if($category->save()) {
									$fc_id	= $category->id;
								}else{
									
									$error	=	$category->getErrors();
									if($error['start_date'][0] !=NULL or $error['end_date'][0]!=NULL){
										Yii::app()->user->setFlash('errorMessage',Yii::t('app',"Month must be within selected academic year"));
									}
								}
									
							}
							else
								$fc_id	= $fc->id;
							if($fc_id !=NULL){
								$fs		= FeeSubscriptions::model()->findByAttributes(array('fee_id'=>$fc_id));//check fees category alredy exist
								$feecat	=	FeeCategories::model()->findByPk($fc_id);
								if(!isset($fs) and $fs==NULL){
									$subscription					= new FeeSubscriptions;
									$subscription->fee_id			= $fc_id;	
									$subscription->subscription_type= 6;
									
									$subscription->due_date			= $feecat->end_date;
									$subscription->created_at		= date("Y-m-d h:i:s");
									$subscription->created_by		= Yii::app()->user->id;	
									if($subscription->save())							
										$sub_id		=	$subscription->id;
								}else
										$sub_id		=	$fs->id;
								$fp		= FeeParticulars::model()->findByAttributes(array('fee_id'=>$fc_id));//check fees Particulars alredy exist
								if(!isset($fp) and $fp==NULL){
									//fee particular 
									$particular	= new FeeParticulars;
									$particular->academic_year_id	= $year;
									$particular->fee_id				= $fc_id;
									$particular->name				= 'Transportation Fee';
									$particular->description		= 'Transportation Fee';
									$particular->tax				= 0;
									$particular->discount_value		= 0.00;
									$particular->discount_type		= 0;						
									$particular->created_by			= Yii::app()->user->id;
									$particular->created_at			= date("Y-m-d h:i:s");
									if($particular->save())
										$fp_id		=	$particular->id;
								}
								else
									$fp_id	=	$fp->id;
									
								$fp_acc	= FeeParticularAccess::model()->findByAttributes(array('particular_id'=>$fp_id,'admission_no'=>$student[0]['admission_no']));//check fees Particulars Access alredy exist
								
								if(!isset($fp_acc) and $fp_acc==NULL){
									//fee particular access								
									$access						= new FeeParticularAccess;
									$access->academic_year_id	= $year;
									$access->particular_id		= $fp_id;
									$access->access_type		= 2;
									$access->admission_no		= $student[0]['admission_no'];
									$access->amount				= $stop->fare;
									$access->created_at			= date("Y-m-d h:i:s");
									$access->created_by			= Yii::app()->user->id;
									if($access->save())
										$fp_acc_id	=	$access->id;
								}
								else
									$fp_acc_id		=	$fp_acc->id;
								
								if(isset($fp_acc_id) and $fp_acc_id!=NULL){
									$criteria					= new CDbCriteria;		
									$criteria->condition		= 'fee_id=:fee_id AND table_id=:id AND user_type=:user_type';
								  	$criteria->params = array(':fee_id'=>(int) $fc_id,':id'=>(int) $student[0]['id'],'user_type'=>1);
									
									$fee_invoice	= FeeInvoices::model()->findAll($criteria);//check the student month invoice  alredy exist
								
									if($fee_invoice==NULL){
										
										$subscriptions	= FeeSubscriptions::model()->findByPk($sub_id);
										
										
										$feecat			=	FeeCategories::model()->findByPk($fc_id);	
										
										$feeinvoice		= new FeeInvoices;
										$feeinvoice->academic_year_id	= $year;
										$feeinvoice->uid				= $student[0]['uid'];						
										$feeinvoice->user_type			= 1;
										$feeinvoice->table_id			= $student[0]['id'];					
										$feeinvoice->fee_id				= $fc_id;
										$feeinvoice->subscription_id	= $sub_id;
										$feeinvoice->name				= $feecat->name;
										$feeinvoice->description		= $feecat->description;
										$feeinvoice->subscription_type	= $feecat->subscription_type;
										$feeinvoice->start_date			= $feecat->start_date;
										$feeinvoice->end_date			= $feecat->end_date;
										$feeinvoice->due_date			= $subscriptions->due_date;
										$feeinvoice->created_at			= date("Y-m-d h:i:s");
										$feeinvoice->created_by			= Yii::app()->user->id;	
										if($feeinvoice->save()){
											$particular			= FeeParticulars::model()->findByPk($fp_id);
											$particular_access	= FeeParticularAccess::model()->findByPk($fp_acc_id);
																																
											$invoiceparticular	= new FeeInvoiceParticulars;
											$invoiceparticular->invoice_id		= $feeinvoice->id;
											$invoiceparticular->name			= $particular->name;
											$invoiceparticular->description		= $particular->description;	
											$invoiceparticular->amount			= $particular_access->amount;							
											$invoiceparticular->tax				= $particular->tax;	//percentage											
											$invoiceparticular->discount_type	= $particular->discount_type;
											if($invoiceparticular->save())
											{
												$invoice_total++;
											}
										}
									}																
								}
							}							
						}
					}					
				}
			}
			if($invoice_total == 0 and $fc_id!=NULL)
				Yii::app()->user->setFlash('errorMessage',Yii::t('app',"Invoice(s) already generated for the selected month"));
				Yii::app()->user->setFlash('successMessage',$invoice_total." ".Yii::t('app',"out of")." ".$al_total." ".Yii::t('app',"Invoice(s) generated!"));
			if($invoice_total == 0)
				$this->render('invoiceall');
			else		
			    $this->redirect(array('index'));
		}
		
		$this->render('invoiceall');
	}
	
 public function actionInvoice($id)
 { 
		if(Yii::app()->user->year){
		$year 					= Yii::app()->user->year;
		}
		else{
		$current_academic_yr 	= Configurations::model()->findByAttributes(array('id'=>35));
		$year 					= $current_academic_yr->config_value;
		}
	if(isset($_REQUEST['invoice_month']) and $_REQUEST['invoice_month']!=NULL )
	{
		$month_val		=	date('Ym',strtotime($_REQUEST['invoice_month']));
		$month_v		=	date('F Y',strtotime($_REQUEST['invoice_month']));
		$curr_month		=	date('m',strtotime($_REQUEST['invoice_month']));
		$curr_year		=	date('Y',strtotime($_REQUEST['invoice_month']));
		$curr_month_year	=	date('m-Y',strtotime($_REQUEST['invoice_month']));
		$student  =   Students::model()->findByPK($id);
		if($student and $student !=NULL)
		{
			$ad_month =	  date('m', strtotime($student->admission_date));
			$ad_year  =   date('Y', strtotime($student->admission_date));
			$ad_year1  =   date('Ym', strtotime($student->admission_date));
			if($month_val>=$ad_year1)
			{
				$accademicyear		= AcademicYears::model()->findByPk($year);
				$accademic_s		= date('m-Y',strtotime($accademicyear->start));
				$accademic_e		= date('m-Y',strtotime($accademicyear->end));
				$feecategory 		=	new FeeCategories;
				$feecategory->name    = "Transportation Fee - ".$month_v;
				
				$feescategory = FeeCategories::model()->findByAttributes(array('name'=>$feecategory->name));
				
				if(!isset($feescategory) and $feescategory==NULL)
				{
					$feecategory->academic_year_id	= $year;
					$feecategory->type				= 2;
					$feecategory->description		= $feecategory->name;
					$feecategory->subscription_type = 6;
					if($curr_month_year == $accademic_s)
					  $feecategory->start_date		=  date('Y-m-d',strtotime($accademicyear->start));
					else
					  $feecategory->start_date		= date('Y-m-1',strtotime(date('1-'.$curr_month.'-'.$curr_year)));
					
					if($curr_month_year == $accademic_e)
					  $feecategory->end_date		= date('Y-m-d',strtotime($accademicyear->end));
					else
					  	$feecategory->end_date		    = date('Y-m-t',strtotime(date('1-'.$curr_month.'-'.$curr_year)));
						$feecategory->invoice_generated = 1;
						$feecategory->amount_divided	= 0;
						$feecategory->created_by		= Yii::app()->user->id;
						$feecategory->created_at		= date("Y-m-d h:i:s");
					if($feecategory->save())
					{
						$feecategory_id	= $feecategory->id;
					}
					else
					{
						$error	=	$feecategory->getErrors();
						if($error['start_date'][0] !=NULL or $error['end_date'][0]!=NULL){
						Yii::app()->user->setFlash('errorMessage',Yii::t('app',"Month must be within selected academic year"));
						$this->redirect(array('index'));
						}
					}
				}
				else
				{
					$feecategory_id	= $feescategory->id;
				}
				if($feecategory_id !=NULL)
				{
					$feesubscription			=   new FeeSubscriptions;
					$feessubscription 		 	=	FeeSubscriptions::model()->findByAttributes(array('fee_id'=>$feecategory_id));
					$feecat	=	FeeCategories::model()->findByPk($feecategory_id);
					if(!isset($feessubscription) and $feessubscription == NULL)
					{
						
						$feesubscription->fee_id			= $feecategory_id;	
						$feesubscription->subscription_type = 6;
						$feesubscription->due_date			= $feecat->end_date;
						$feesubscription->created_at		= date("Y-m-d h:i:s");
						$feesubscription->created_by		= Yii::app()->user->id;	
						if($feesubscription->save())							
						$feesubscript_id		=	$feesubscription->id;
					}   
					else
						$feesubscript_id		=	$feessubscription->id;
					if($feesubscript_id !=NULL)
					{
						$feeparticular			=   new FeeParticulars;
						$feesparticular			=	FeeParticulars::model()->findByAttributes(array('fee_id'=>$feesubscript_id));
						
						if(!isset($feesparticular) and $feesparticular == NULL)
						{
							
							$feeparticular->academic_year_id	= $year;
							$feeparticular->fee_id				= $feesubscript_id;
							$feeparticular->name				= 'Transportation Fee';
							$feeparticular->description			= 'Transportation Fee';
							$feeparticular->tax					= 0;
							$feeparticular->discount_value		= 0.00;
							$feeparticular->discount_type		= 0;						
							$feeparticular->created_by			= Yii::app()->user->id;
							$feeparticular->created_at			= date("Y-m-d h:i:s");
							if($feeparticular->save())
							$feeparticular_id		=	$feeparticular->id;
						}
						else
							$feeparticular_id	=	$feesparticular->id;
						
						if($feeparticular_id !=NULL)
						{
							$feeparticularaccess	=   new FeeParticularAccess;
							$feesparticularaccess	=	FeeParticularAccess::model()->findByAttributes(array('particular_id'=>$feeparticular_id,'admission_no'=>$student->admission_no)); 
							//var_dump($feesparticularaccess);exit;
							$transportation			=	Transportation::model()->findByAttributes(array('student_id'=>$id));    						                            $stopdetails			=	StopDetails::model()->findByAttributes(array('id'=>$transportation->stop_id));
							if(!isset($feesparticularaccess) and $feesparticularaccess==NULL)
							{
								//echo $feeparticular_id;exit;
								$feeparticularaccess->academic_year_id		= $year;
								$feeparticularaccess->particular_id			= $feeparticular_id;
								$feeparticularaccess->access_type			= 2;
								$feeparticularaccess->admission_no			= $student->admission_no;
								$feeparticularaccess->amount				= $stopdetails->fare;
								$feeparticularaccess->created_at			= date("Y-m-d h:i:s");
								$feeparticularaccess->created_by			= Yii::app()->user->id;
								$feeparticularaccess->save();
								if($feeparticularaccess->save())
								$feeparticularaccess_id	=	$feeparticularaccess->id;
							}
							else
								$feeparticularaccess_id		=	$feesparticularaccess->id;
							
							if($feeparticularaccess_id !=NULL)
							{
								
								$feeinvoices =	FeeInvoices::model()->findByAttributes(array('fee_id'=>$feecategory_id,'uid'=>$student->uid));
								if(!isset($feeinvoices) and $feeinvoices==NULL)
								{
									$feeinvoice	 =  new FeeInvoices;
									$fee_sub	= FeeSubscriptions::model()->findByPk($feesubscript_id);
									$fee_cat	=	FeeCategories::model()->findByPk($feecategory_id);
									$feeinvoice->academic_year_id	= $year;
									$feeinvoice->uid				= $student->uid;						
									$feeinvoice->user_type			= 1;
									$feeinvoice->table_id			= $student->id;					
									$feeinvoice->fee_id				= $feecategory_id;
									$feeinvoice->subscription_id	= $feesubscript_id;
									$feeinvoice->name				= $fee_cat->name;
									$feeinvoice->description		= $fee_cat->description;
									$feeinvoice->subscription_type	= $fee_cat->subscription_type;
									$feeinvoice->start_date			= $fee_cat->start_date;
									$feeinvoice->end_date			= $fee_cat->end_date;
									$feeinvoice->due_date			= $fee_sub->due_date;
									$feeinvoice->created_at			= date("Y-m-d h:i:s");
									$feeinvoice->created_by			= Yii::app()->user->id;
									if($feeinvoice->save())
									{
										$fee_particular			= FeeParticulars::model()->findByPk($feeparticular_id);
										$fee_particular_access	= FeeParticularAccess::model()->findByPk($feeparticularaccess_id);
										$feeinvoiceparticulars	= new FeeInvoiceParticulars;
										$feeinvoiceparticulars->invoice_id		= $feeinvoice->id;
										$feeinvoiceparticulars->name			= $fee_particular->name;
										$feeinvoiceparticulars->description		= $fee_particular->description;	
										$feeinvoiceparticulars->amount			= $fee_particular_access->amount;							
										$feeinvoiceparticulars->tax				= $fee_particular->tax;												
										$feeinvoiceparticulars->discount_type	= $fee_particular->discount_type;
										if($feeinvoiceparticulars->save())
										{
											Yii::app()->user->setFlash('successMessage', Yii::t('app','Invoice Generated Successfully'));
											$this->redirect(array('index'));
										}
									}
								}
								else
									Yii::app()->user->setFlash('errorMessage', Yii::t('app','Invoice Already Generated for This Month'));
									$this->redirect(array('index'));
							}
						}
					}
				}
					
			}
			else
			{
				Yii::app()->user->setFlash('errorMessage', Yii::t('app','Selected Month & Year Must be Greater than Admission Date'));
				$this->redirect(array('index'));
			}
		}
	}
	
	$this->render('studentinvoice');
	
	}
 }
