<?php

class DefaultController extends RController
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
	
	public function actionIndex()
	{
		$criteria = new CDbCriteria;		
		$criteria->order = 'id DESC';
		
		$total = PurchaseVendors::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
        $pages->applyLimit($criteria); 
		
		$model = PurchaseVendors::model()->findAll($criteria);
		$this->render('index', array('model'=>$model, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
	}

	
}