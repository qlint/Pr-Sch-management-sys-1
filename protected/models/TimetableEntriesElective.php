<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class TimetableEntriesElective extends CFormModel
{
	public $subject_id;
	public $employee_id;
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('subject_id', 'required'),
			array('subject_id','check_subject'),
			
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'subject_id' => Yii::t('Subject'),
			'employee_id' => Yii::t('Teacher'),
		);
	}

	public function check_subject($attribute,$params)
	{
		$sub_id  = $this->subject_id;
		if($sub_id!=NULL){			
			$count=Subjects::model()->findByAttributes(array('id'=>$sub_id));
			$max_count=$count->max_weekly_classes;
			$classcount=TimetableEntries::model()->findAllByAttributes(array('subject_id'=>$sub_id,'batch_id'=>$this->batch_id));
			
			if(count($classcount)>=$max_count)
			{
				$this->addError($attribute, Yii::t("app",'Maximum weekly classes of this subject is exeeded!'));
			}
		}
	}
}