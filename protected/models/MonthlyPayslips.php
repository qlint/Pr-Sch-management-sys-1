<?php

/**
 * This is the model class for table "monthly_payslips".
 *
 * The followings are the available columns in table 'monthly_payslips':
 * @property integer $id
 * @property string $salary_date
 * @property integer $employee_id
 * @property string $amount
 * @property integer $is_approved
 * @property integer $approver_id
 * @property integer $is_rejected
 * @property integer $rejector_id
 * @property string $reason
 */
class MonthlyPayslips extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return MonthlyPayslips the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'monthly_payslips';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id, is_approved, approver_id, is_rejected, rejector_id', 'numerical', 'integerOnly'=>true),
			array('amount, reason', 'length', 'max'=>255),
			array('salary_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, salary_date, employee_id, amount, is_approved, approver_id, is_rejected, rejector_id, reason', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t("app",'ID'),
			'salary_date' => Yii::t("app",'Salary Date'),
			'employee_id' => Yii::t("app",'Teacher'),
			'amount' => Yii::t("app",'Amount'),
			'is_approved' => Yii::t("app",'Is Approved'),
			'approver_id' => Yii::t("app",'Approver'),
			'is_rejected' => Yii::t("app",'Is Rejected'),
			'rejector_id' => Yii::t("app",'Rejector'),
			'reason' => Yii::t("app",'Reason'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('salary_date',$this->salary_date,true);
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('is_approved',$this->is_approved);
		$criteria->compare('approver_id',$this->approver_id);
		$criteria->compare('is_rejected',$this->is_rejected);
		$criteria->compare('rejector_id',$this->rejector_id);
		$criteria->compare('reason',$this->reason,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}