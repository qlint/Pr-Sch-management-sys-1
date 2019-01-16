<?php

/**
 * This is the model class for table "leave_types".
 *
 * The followings are the available columns in table 'hr_leave_types':
 * @property integer $id
 * @property string $type
 * @property string $description
 * @property integer $category
 * @property integer $gender
 * @property integer $count
 * @property integer $is_deleted
 */
class LeaveTypes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return HrLeaveTypes the static model class
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
		return 'leave_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, description, category, gender, count', 'required'),
			array('category, gender, is_deleted', 'numerical', 'integerOnly'=>true),
			array('count', 'numerical', 'min' => 1, 'max'=>50, 'integerOnly'=>true),
			array('type, description', 'length', 'max'=>255),
			array('count', 'checkcount'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, description, category, gender, count, is_deleted', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('app','ID'),
			'type' => Yii::t('app','Type'),
			'description' => Yii::t('app','Description'),
			'category' => Yii::t('app','Category'),
			'gender' => Yii::t('app','Gender'),
			'count' => Yii::t('app','Count'),
			'is_deleted' => Yii::t('app','Is Deleted'),
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('category',$this->category);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('count',$this->count);
		$criteria->compare('is_deleted',$this->is_deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function checkcount($attribute,$params)
	{		
            if($this->category!='')
            {
                if($this->category == 1)
                {
					if($this->count > 90){
                        $this->addError($attribute,Yii::t("app",'Count must be less than 90 for this category'));
					}
                }
				if($this->category == 2)
                {
					if($this->count > 365){
                        $this->addError($attribute,Yii::t("app",'Count must be less than 365 for this category'));
					}
                }
            }
	}
}