<?php

/**
 * This is the model class for table "employee_leave_types".
 *
 * The followings are the available columns in table 'employee_leave_types':
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $status
 * @property string $max_leave_count
 * @property integer $carry_forward
 */
class EmployeeLeaveTypes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return EmployeeLeaveTypes the static model class
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
		return 'employee_leave_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status, carry_forward', 'numerical', 'integerOnly'=>true),
			array('name, code, max_leave_count', 'length', 'max'=>255),
                        array('max_leave_count', 'numerical', 'min'=>0.5),
                        array('max_leave_count', 'check'),
			array('max_leave_count','CRegularExpressionValidator', 'pattern'=>'/^([0-9]+(\.[5,0])?)$/','message'=>"{attribute} " .Yii::t("app", "must be full day or half day")),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('name','CRegularExpressionValidator', 'pattern'=>'/^[A-Za-z_ ]+$/','message'=>"{attribute} ".Yii::t("app","should contain only letters.")),
			array('id, name, code, status, max_leave_count, carry_forward', 'safe', 'on'=>'search'),
			array('name, status, max_leave_count', 'required',),
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
			'name' => Yii::t("app",'Name'),
			'code' => Yii::t("app",'Code'),
			'status' => Yii::t("app",'Status'),
			'max_leave_count' => Yii::t("app",'Max Leave Count'),
			'carry_forward' => Yii::t("app",'Carry Forward'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('max_leave_count',$this->max_leave_count,true);
		$criteria->compare('carry_forward',$this->carry_forward);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function check($attribute,$params)
        {
            if($this->max_leave_count!="")
            {
                $this->max_leave_count= $this->max_leave_count+0;
            }
        }
}