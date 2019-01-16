<?php

/**
 * This is the model class for table "fee_receipt_details".
 *
 * The followings are the available columns in table 'fee_receipt_details':
 * @property integer $id
 * @property integer $student
 * @property integer $batch
 * @property integer $collection
 */
class FeeReceipt extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FeeReceipt the static model class
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
		return 'fee_receipt_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student, batch, collection', 'required'),
			array('student, batch, collection', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, student, batch, collection', 'safe', 'on'=>'search'),
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
			'student' => Yii::t("app",'Student'),
			'batch' => Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),
			'collection' => Yii::t("app",'Collection'),
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
		$criteria->compare('student',$this->student);
		$criteria->compare('batch',$this->batch);
		$criteria->compare('collection',$this->collection);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}