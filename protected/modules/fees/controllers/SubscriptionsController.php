<?php
class SubscriptionsController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
	public function actionIndex($id)
	{
		//all fee categories
		$category	= FeeCategories::model()->findByPk($id);
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		if($settings!=NULL){
			$dateformat	= $settings->displaydate;
		}
		else
			$dateformat = 'd M Y';
		if($category->start_date!=NULL)
			$category->start_date	= date($dateformat, strtotime($category->start_date));
		if($category->end_date!=NULL)
			$category->end_date		= date($dateformat, strtotime($category->end_date));
			
		if($category){			
			if(isset($_POST['FeeCategories'])){
				$errors		= array();
				$has_error	= false;	
				
				$category->attributes			= $_POST['FeeCategories'];
				$category->subscription_type	= $_POST['FeeCategories']['subscription_type'];
				if(isset($_POST['FeeCategories']['start_date']) and $_POST['FeeCategories']['start_date']!=NULL)
					$category->start_date			= date("Y-m-d h:i:s", strtotime($_POST['FeeCategories']['start_date']));
				if(isset($_POST['FeeCategories']['end_date']) and $_POST['FeeCategories']['end_date']!=NULL)
					$category->end_date				= date("Y-m-d h:i:s", strtotime($_POST['FeeCategories']['end_date']));
					
				if(!$category->validate()){
					$has_error	= true;
					//get error from category
					foreach($category->getErrors() as $attribute=>$error){
						$key		= "FeeCategories_".$attribute;							
						$errors[$key][]	= $error[0];
					}
				}
				
				//due dates if needed for current selected subscription type
				if(isset($_POST['FeeSubscriptions']['due_date'])){
					foreach($_POST['FeeSubscriptions']['due_date'] as $i=>$date){
						$subscription						= new FeeSubscriptions;
						if(isset($_POST['FeeCategories']['start_date']) and $_POST['FeeCategories']['start_date']!=NULL)
							$subscription->start_date			= date("Y-m-d h:i:s", strtotime($_POST['FeeCategories']['start_date']));
						if(isset($_POST['FeeCategories']['end_date']) and $_POST['FeeCategories']['end_date']!=NULL)	
							$subscription->end_date				= date("Y-m-d h:i:s", strtotime($_POST['FeeCategories']['end_date']));
						$subscription->subscription_type	= $_POST['FeeCategories']['subscription_type'];
						if($date!=NULL)
							$subscription->due_date				= date("Y-m-d h:i:s", strtotime($date));
						$subscription->created_by			= Yii::app()->user->id;
						$subscription->created_at			= date("Y-m-d h:i:s");
											
						if(!$subscription->validate()){
							$has_error	= true;
							//get error from particular
							foreach($subscription->getErrors() as $attribute=>$error){
								$key		= "FeeSubscriptions_".$attribute."_".$i;							
								$errors[$key][$i]	= $error[0];
							}			
						}
					}
				}				
				
				if($has_error==true){
					echo CJSON::encode(array('status'=>'error', 'errors'=>$errors));			
					exit;
				}
				else{
					//save datas
					$category->attributes			= $_POST['FeeCategories'];
					$category->subscription_type	= $_POST['FeeCategories']['subscription_type'];
					$category->start_date			= date("Y-m-d h:i:s", strtotime($_POST['FeeCategories']['start_date']));
					$category->end_date				= date("Y-m-d h:i:s", strtotime($_POST['FeeCategories']['end_date']));
					if($category->save()){
						$due_dates	= array();
						switch($category->subscription_type){
							case 1:
							case 2:
							case 3:
							case 6:
								//get due dates
								if(isset($_POST['FeeSubscriptions']['due_date'])){
									foreach($_POST['FeeSubscriptions']['due_date'] as $i=>$date){
										$due_dates[]	= date("Y-m-d h:i:s", strtotime($date));
									}
								}
							break;
							
							case 4:
								//generate due dates from month day
								if(isset($_POST['FeeSubscriptions']['monthday'])){
									$year	= date("Y", strtotime($_POST['FeeCategories']['start_date']));
									$month	= date("m", strtotime($_POST['FeeCategories']['start_date']));
									$day	= $_POST['FeeSubscriptions']['monthday'];
									
									$start_date		= strtotime($_POST['FeeCategories']['start_date']);
									$end_date		= strtotime($_POST['FeeCategories']['end_date']);
									$generated_date	= strtotime(date($year."-".$month."-".$day));
									
									if($generated_date<$start_date){
										$generated_date	= strtotime("+1 month", $generated_date);
									}
									
									$start    = new DateTime(date("Y-m-d", $generated_date));
									$end      = new DateTime(date("Y-m-d", strtotime($_POST['FeeCategories']['end_date'])));
									$interval = DateInterval::createFromDateString('1 month');
									$period   = new DatePeriod($start, $interval, $end);					
									
									foreach ($period as $dt) {
										$due_dates[]	= $dt->format("Y-m-d h:i:s");
									}
								}							
							break;
							
							case 5:
								//generate due dates from week day
								if(isset($_POST['FeeSubscriptions']['weekday'])){
									/* Do not apply translate function to this block */
									$weekdays	= array(
										1=>"sunday",
										2=>"monday",
										3=>"tuesday",
										4=>"wednesday",
										5=>"thursday",
										6=>"friday",
										7=>"saturday",
									);
									/* Do not apply translate function to this block */
									$weekday	= $_POST['FeeSubscriptions']['weekday'];
									$start		= strtotime("next ".$weekdays[$weekday], strtotime($_POST['FeeCategories']['start_date']));
									$end		= strtotime("+1 day", strtotime($_POST['FeeCategories']['end_date']));
									
									$start    	= new DateTime(date("Y-m-d", $start));
									$end      	= new DateTime(date("Y-m-d", $end));
									$interval 	= DateInterval::createFromDateString('1 week');
									$period   	= new DatePeriod($start, $interval, $end);					
									
									foreach ($period as $dt) {
										$due_dates[]	= $dt->format("Y-m-d h:i:s");
									}
								}
							break;
						}
						
						//save all dates
						
						
						foreach($due_dates as $due_date){
							$already_found		= FeeSubscriptions::model()->findByAttributes(array('fee_id'=>$category->id, 'subscription_type'=>$_POST['FeeCategories']['subscription_type'], 'due_date'=>$due_date));
							if(!$already_found){
								$subscription						= new FeeSubscriptions;
								$subscription->fee_id				= $category->id;
								$subscription->start_date			= date("Y-m-d h:i:s", strtotime($_POST['FeeCategories']['start_date']));
								$subscription->end_date				= date("Y-m-d h:i:s", strtotime($_POST['FeeCategories']['end_date']));
								$subscription->subscription_type	= $_POST['FeeCategories']['subscription_type'];
								$subscription->due_date				= $due_date;
								$subscription->created_by			= Yii::app()->user->id;
								$subscription->created_at			= date("Y-m-d h:i:s");
								if(!$subscription->save()){
									//handle errros
								}
							}
						}
					}
					
					echo CJSON::encode(array('status'=>'success', 'redirect'=>Yii::app()->createUrl('/fees/dashboard')));			
					exit;
				}
			}
					
			$this->render('index', array('category'=>$category));
		}
		else{
			throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
		}
	}
	
	public function actionType($id){
		//all fee categories
		$category	= new FeeCategories;
		$data	= $this->renderPartial('_type_'.$id, array('category'=>$category), true, true);
		echo CJSON::encode(array('status'=>'success', 'data'=>$data));
	}
	
	public function actionRecurringType($id){
		$subscription	= new FeeSubscriptions;
		$data	= $this->renderPartial('_recurring_'.$id, array('subscription'=>$subscription), true, true);
		echo CJSON::encode(array('status'=>'success', 'data'=>$data));
	}	
	
	public function actionAddDueDate($timeid){
		$cs=Yii::app()->clientScript;
		$cs->scriptMap=array(
			'jquery.js'=>false,
			'jquery.min.js'=>false,
			'jquery-ui.min.js' => false,
		);
		$data	= $this->renderPartial('_due_date', array('timeid'=>$timeid), true, true);
		echo CJSON::encode(array('status'=>'success', 'data'=>$data));
	}
}