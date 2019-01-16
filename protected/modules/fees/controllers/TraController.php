<?php

class TraController extends RController
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
			
			$month			=	date('m',strtotime($_REQUEST['invoice_month']));
			$month_year		=	date('Y',strtotime($_REQUEST['invoice_month']));
			$month_year_j	=	date('m-Y',strtotime($_REQUEST['invoice_month']));
			$routes = Transportation::model()->findAll();//take all routes
			
			if($routes){
				$invoice_total	=	0;
				$al_total		=  	0;
				foreach($routes as $route1){
					$al_total++;
					$stop	=	StopDetails::model()->findByAttributes(array('id'=>$route1->stop_id));
					$route	=	RouteDetails::model()->findByAttributes(array('id'=>$stop->route_id));
					$criteria = new CDbCriteria;
					$criteria->condition	= 'id =:id AND year(`admission_date`) <= :year AND month(`admission_date`) <= :month';// check  accademic year 
					$criteria->params		= array(':id'=>$route1->student_id,':year'=>$month_year,':month'=>$month);
					
					$student=Students::model()->findAll($criteria);
					if(isset($student) and $student!=NULL){
						if($year){		
							$category	= new FeeCategories;
														
							//add data into category table
							$category->name		= "Transportation Fee -".$_REQUEST['invoice_month'];
							
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
									$fee_invoice	= FeeInvoices::model()->findByAttributes(array('fee_id'=>$fc_id,'uid'=>$student[0]['uid']));//check the student month invoice  alredy exist
									
									if(!isset($fee_invoice) and $fee_invoice==NULL){
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
												$invoice_total++;
										}
									}																
								}
							}							
						}
					}					
				}
			}
			if($invoice_total!=0){
				Yii::app()->user->setFlash('successMessage',$invoice_total." ".Yii::t('app',"out of")." ".$al_total." ".Yii::t('app',"Invoice(s) generated!"));
			}
			
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
			$month	=	date('m',strtotime($_REQUEST['invoice_month']));
			$year	=	date('Y',strtotime($_REQUEST['invoice_month']));
			$routes = Transportation::model()->findByAttributes(array('student_id'=>$id));//take all routes
			var_dump($routes);exit;
		}
		
		$this->render('studentinvoice');
	}
}
