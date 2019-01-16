<?php

class SaleController extends RController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $defaultAction	= 'manage';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
	public function actionManage()
	{
		$criteria 	= new CDbCriteria;		
		if(isset($_REQUEST['type']) and $_REQUEST['type']!=0 and $_REQUEST['type']!=NULL){
			$criteria->condition	= '`t`.`purchaser`=:type';
			$criteria->params		= array(':type'=>$_REQUEST['type']);
		}
		$criteria->order = 'id DESC';
		
		$total 		= PurchaseSale::model()->count($criteria);
		$pages 		= new CPagination($total);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
        $pages->applyLimit($criteria); 
		
		$sales 		= PurchaseSale::model()->findAll($criteria);
		$this->render('index', array('sales'=>$sales, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
	}
	
	public function actionCreate(){
		$model	= new PurchaseSale;
		
		if(isset($_POST['PurchaseSale'])){
			$model->attributes	= $_POST['PurchaseSale'];
			$model->type		= 2;
			$model->status		= 1;
			$model->is_issued	= 1;
			$model->issued_date	= date('Y-m-d');
			if($model->save()){
				//deduct from stock
				$stock	= PurchaseStock::model()->findByAttributes(array('item_id'=>$model->material_id));				
				if($stock!=NULL){
					$stock->quantity		= $stock->quantity	- $model->quantity;
					$stock->save();
				}
				
				//flash message
				Yii::app()->user->setFlash('successMessage', Yii::t('app', 'Sale added successfully'));
				
				$this->redirect(array('manage'));
			}
		}
		
		$this->render('create', array('model'=>$model));
	}
	
	public function actionUsers(){
		echo CHtml::tag('option', array('value'=>''), Yii::t('app', 'Select Purchased By'),true);
		
		if(isset($_POST["PurchaseSale"]["purchaser"]) and $_POST["PurchaseSale"]["purchaser"]!=NULL){
			$criteria		= new CDbCriteria;
			$criteria->join	= 'JOIN `profiles` `p` ON `p`.`user_id`=`user`.`id`';
			switch($_POST["PurchaseSale"]["purchaser"]){
				case 1:
					$criteria->join			.= ' JOIN `students` `s` ON `s`.`uid`=`user`.`id`';
					$criteria->condition	= '`s`.`is_deleted`=:is_deleted AND `s`.`is_active`=:is_active';
					$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1);
				break;
				
				case 2:
					$criteria->join			.= ' JOIN `employees` `e` ON `e`.`uid`=`user`.`id`';
					$criteria->condition	= '`e`.`is_deleted`=:is_deleted';
					$criteria->params		= array(':is_deleted'=>0);
				break;
				
				case 3:
					$criteria->join			.= ' JOIN `guardians` `g` ON `g`.`uid`=`user`.`id`';
					$criteria->condition	= '`g`.`is_delete`=:is_deleted';
					$criteria->params		= array(':is_deleted'=>0);					
				break;
			}
			
			$criteria->order		= '`p`.`lastname` ASC';
			$users		= User::model()->findAll($criteria);
			$users		= CHtml::listData($users, 'id', 'profile.fullname');
			foreach($users as $value=>$label){
				echo CHtml::tag('option', array('value'=>$value), CHtml::encode($label),true);
			}
		}
	}
	
	public function actionReturnitem($id){
		$model	= PurchaseSale::model()->findByPk($id);
		$model->scenario	= 'return';
		if(isset($_POST['PurchaseSale'])){
			$model->attributes		= $_POST['PurchaseSale'];
			if(isset($model->return_date) and $model->return_date!=NULL)
				$model->return_date		= date("Y-m-d", strtotime($model->return_date));
			$model->is_issued		= 2;
			if($model->save()){
				$stock				= PurchaseStock::model()->findByAttributes(array('item_id'=>$model->material_id));
				if($stock!=NULL){
					$stock->quantity	= $stock->quantity + $model->quantity;
					$stock->save();
				}
				
				echo CJSON::encode(array('status'=>'success'));
				exit;
			}
			else{
				echo CJSON::encode(array('status'=>'error', 'errors'=>$model->getErrors()));
				exit;
			}
		}
		
		Yii::app()->clientScript->scriptMap['jquery.js'] 		= false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] 	= false;
		$this->renderPartial('return_item', array('id'=>$id,'model'=>$model), false, true);
	}
	public function actionSalereport(){
		$this->render('sale_report');
	}
	public function actionSaleoverallpdf()
    {
      	$department_name = EmployeeDepartments::model()->findByAttributes(array('id'=>$_REQUEST['id']));		
        
        $filename= Yii::t('app',' Sale Overall').' Report.pdf';
        Yii::app()->osPdf->generate("application.modules.purchase.views.sale.saleoverallpdf", $filename, array());
        
	}
}