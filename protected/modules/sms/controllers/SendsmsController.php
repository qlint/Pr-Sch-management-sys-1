<?php
class SendsmsController extends RController
{
	public function filters()
	{
		return array(
		);
	}
	
	public function actionIndex($id)
	{
		$instance_status = SmsInstance::model()->findByPk($id);
		if($instance_status!=NULL and $instance_status->status==0){
			$instances = Sms::model()->findAllByAttributes(array('instance'=>$id));
			foreach($instances as $instance){
				if($instance->phone_number){
					$status = SmsSettings::model()->sendSms($instance->phone_number,0,$instance->message) ;
				}
			}		
			$instance_status->status=1;
			$instance_status->save();
			echo 'Instance '.$id.' completed';
		}
	}
}