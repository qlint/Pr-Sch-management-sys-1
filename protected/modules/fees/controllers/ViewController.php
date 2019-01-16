<?php
class ViewController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
	public function actionIndex($id){
		$category	= FeeCategories::model()->findByPk($id);
		if($category){
			$criteria		= new CDbCriteria;
			$criteria->compare("fee_id", $id);
			$particulars	= FeeParticulars::model()->findAll($criteria);
			$this->render("index", array("category"=>$category, 'particulars'=>$particulars));
		}
		else{
			throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
		}
	}
}