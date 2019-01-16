<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class OnlineRegisterSetting2 extends CFormModel
{
	public $academic_year;
	public $status;
	public $reg_no;
	public $show_link;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(			
			array('academic_year', 'required'),			
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'academic_year'=>Yii::t("app",("Academic Year")),
			'status'=>Yii::t("app",("Allow Online Admission")),
			'reg_no'=>Yii::t("app",("Registration No")),
			'show_link'=>Yii::t("app",("Show Link for Online Admission")),
		);
	}

	
}
