<?php

/**
 * This is the model class for table "finance_fees".
 *
 * The followings are the available columns in table 'finance_fees':
 * @property integer $id
 * @property integer $fee_collection_id
 * @property string $transaction_id
 * @property integer $student_id
 * @property integer $is_paid
 */
class FinanceFees extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FinanceFees the static model class
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
		return 'finance_fees';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fees_paid', 'required','message'=>Yii::t("app",'Amount cannot be blank')),
			array('fee_collection_id, student_id, is_paid', 'numerical', 'integerOnly'=>true),
			array('fees_paid','numerical','integerOnly'=>true,'message'=>Yii::t("app",'Amount must be an integer')),
			array('transaction_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, fee_collection_id, transaction_id, student_id, fees_paid, is_paid', 'safe', 'on'=>'search'),
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
			'fee_collection_id' => Yii::t("app",'Fee Collection'),
			'transaction_id' => Yii::t("app",'Transaction'),
			'student_id' => Yii::t("app",'Student'),
			'fees_paid' => Yii::t("app",'Amount'),
			'is_paid' => Yii::t("app",'Is Paid'),
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
		$criteria->compare('fee_collection_id',$this->fee_collection_id);
		$criteria->compare('transaction_id',$this->transaction_id,true);
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('is_paid',$this->is_paid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}