<?php
class DashboardController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
	public function actionIndex()
	{
		//fee categories
		$page_size			= 10;
		if(Yii::app()->user->year)
		{
			$year = Yii::app()->user->year;
		}
		else
		{
			$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
			$year = $current_academic_yr->config_value;
		}
		
		$criteria					= new CDbCriteria;		
		$criteria->condition		= 'academic_year_id=:yr';
		$criteria->params[':yr'] 	= $year;	
		$criteria->order			= "`id` DESC";
		$total						= FeeCategories::model()->count($criteria);
		$pages 						= new CPagination($total);
        $pages->setPageSize($page_size);
        $pages->applyLimit($criteria);		
		$categories					= FeeCategories::model()->findAll($criteria);
		
		//invoices generated for
		$criteria					= new CDbCriteria;
		$criteria->condition		= 'academic_year_id=:yr';
		$criteria->params[':yr'] 	= $year;
		$criteria->compare("invoice_generated", 1);				
		$invoices_for				= FeeCategories::model()->findAll($criteria);
		
		$this->render('index', array('categories'=>$categories, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>$page_size, 'total_categories'=>$total, 'invoices_for'=>$invoices_for));
	}
}