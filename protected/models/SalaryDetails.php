<?php

/**
 * This is the model class for table "salary_details".
 *
 * The followings are the available columns in table 'salary_details':
 * @property integer $id
 * @property string $salary_date
 * @property string $basic_pay
 * @property string $incentive
 * @property string $over_time
 * @property string $hike
 * @property string $lop
 * @property string $loan
 * @property string $festival_bonus
 * @property string $tds
 * @property string $esi
 * @property string $epf
 * @property string $casual_leave
 * @property string $casual_remaining
 * @property string $sick_leave
 * @property string $sick_remaining
 * @property string $net_salary
 * @property string $note
 * @property string $created_at
 */
class SalaryDetails extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SalaryDetails the static model class
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
		return 'salary_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('salary_date, basic_pay', 'required'),
			array('basic_pay, incentive, over_time, hike, lop, loan, festival_bonus, tds, esi, epf, net_salary', 'length', 'max'=>10),
			array('casual_leave, casual_remaining, sick_leave, sick_remaining', 'length', 'max'=>3),
			array('incentive, over_time, hike, lop, loan, festival_bonus, tds, esi, epf','checkInt'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('earn_total,deduction_total,employee_id','safe'),
			array('id, salary_date, basic_pay, incentive, over_time, hike, lop, loan, festival_bonus, tds, esi, epf, casual_leave, casual_remaining, sick_leave, sick_remaining, net_salary, note, created_at', 'safe', 'on'=>'search'),
		);
	}
	
	
	public function checkInt($attribute,$params)
	{
		if($this->$attribute!="" and $this->$attribute!=0){
			if(filter_var($this->$attribute,FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^([0-9]*[.])?[0-9]+$/")))){
			}else{
				$this->addError($attribute,$this->getAttributeLabel($attribute).' '.Yii::t("app",'must be a number'));
			}
		}else{
			$this->$attribute 	=	0;
		}
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
			'basic_pay' => Yii::t("app",'Basic Pay'),
			'incentive' => Yii::t("app",'Incentive'),
			'over_time' => Yii::t("app",'Over Time'),
			'hike' => Yii::t("app",'Hike'),
			'lop' => Yii::t("app",'Loss Of Pay'),
			'loan' => Yii::t("app",'Loan or Advance'),
			'festival_bonus' => Yii::t("app",'Festival Bonus'),
			'tds' => Yii::t("app",'TDS'),
			'esi' => Yii::t("app",'ESI'),
			'epf' => Yii::t("app",'EPF'),
			'casual_leave' => Yii::t("app",'Casual Leave'),
			'casual_remaining' => Yii::t("app",'Casual Leave Remaining'),
			'sick_leave' => Yii::t("app",'Sick Leave'),
			'sick_remaining' => Yii::t("app",'Sick Leave Remaning'),
			'earn_total' => Yii::t("app",'Earn Total'),
			'deduction_total' => Yii::t("app",'Deduction Total'),
			'net_salary' => Yii::t("app",'Net Salary'),
			'note' => Yii::t("app",'Notes'),
			'created_at' => Yii::t("app",'Created At'),
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
		$criteria->compare('basic_pay',$this->basic_pay,true);
		$criteria->compare('incentive',$this->incentive,true);
		$criteria->compare('over_time',$this->over_time,true);
		$criteria->compare('hike',$this->hike,true);
		$criteria->compare('lop',$this->lop,true);
		$criteria->compare('loan',$this->loan,true);
		$criteria->compare('festival_bonus',$this->festival_bonus,true);
		$criteria->compare('tds',$this->tds,true);
		$criteria->compare('esi',$this->esi,true);
		$criteria->compare('epf',$this->epf,true);
		$criteria->compare('casual_leave',$this->casual_leave,true);
		$criteria->compare('casual_remaining',$this->casual_remaining,true);
		$criteria->compare('sick_leave',$this->sick_leave,true);
		$criteria->compare('sick_remaining',$this->sick_remaining,true);
		$criteria->compare('net_salary',$this->net_salary,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}