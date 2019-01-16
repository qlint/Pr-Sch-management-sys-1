<?php
class RemoveController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
	public function actionIndex(){
		if(Yii::app()->request->isPostRequest and isset($_POST['id'])){
			$id			= $_POST['id'];
			$category	= FeeCategories::model()->findByPk($id);		
			if($category){
				if($category->invoice_generated==0){
					//remove all datas related to this category
					//remove subscriptions
					$subscriptions	= FeeSubscriptions::model()->findAllByAttributes(array('fee_id'=>$category->id));
					if(count($subscriptions)>0)
						FeeSubscriptions::model()->deleteAllByAttributes(array('fee_id'=>$category->id));
					
					//remove particulars
					$particulars	= FeeParticulars::model()->findAllByAttributes(array('fee_id'=>$category->id));
					foreach($particulars as $particular){
						//remove particular access
						$particular_accesses	= FeeParticularAccess::model()->findAllByAttributes(array('particular_id'=>$particular->id));
						if(count($particular_accesses)>0)
							FeeParticularAccess::model()->deleteAllByAttributes(array('particular_id'=>$particular->id));
						
						$particular->delete();
					}				
					
					$category->delete();
					
					//set up a flash message
					Yii::app()->user->setFlash('success', Yii::t("app", "Fee category removed !"));
					//redirect after procesing
					$this->redirect(array("/fees/dashboard"));
				}
				else{
					//set up a flash message
					Yii::app()->user->setFlash('error', Yii::t("app", "Can't remove fee category !"));
					//redirect after procesing
					$this->redirect(array("/fees/dashboard"));	
				}
			}
			else{
				throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
			}
		}
		else{
			throw new CHttpException(404, Yii::t('app', 'Invalid request.'));
		}
	}
}