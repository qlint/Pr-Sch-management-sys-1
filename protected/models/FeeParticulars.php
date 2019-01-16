<?php

/**
 * This is the model class for table "fee_particulars".
 *
 * The followings are the available columns in table 'fee_particulars':
 * @property string $id
 * @property string $fee_id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $last_edited
 * @property integer $created_by
 * @property integer $edited_by
 */
class FeeParticulars extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FeeParticulars the static model class
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
		return 'fee_particulars';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, created_at, created_by', 'required'),
			array('created_by, edited_by', 'numerical', 'integerOnly'=>true),
			array('fee_id', 'length', 'max'=>20),
			array('name', 'length', 'max'=>250),
			array('academic_year_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, fee_id, name, description, created_at, last_edited, created_by, edited_by', 'safe', 'on'=>'search'),
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
			'fee_id' => Yii::t('app','Category'),
			'name' => Yii::t('app','Name'),
			'description' => Yii::t('app','Description'),
			'created_at' => Yii::t('app','Created At'),
			'last_edited' => Yii::t('app','Last Edited'),
			'created_by' => Yii::t('app','Created By'),
			'edited_by' => Yii::t('app','Edited By'),
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('fee_id',$this->fee_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('last_edited',$this->last_edited,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('edited_by',$this->edited_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}