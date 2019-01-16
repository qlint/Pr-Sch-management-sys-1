<?php

/**
 * This is the model class for table "fee_invoice_particulars".
 *
 * The followings are the available columns in table 'fee_invoice_particulars':
 * @property string $id
 * @property string $invoice_id
 * @property string $name
 * @property string $description
 * @property integer $amount
 */
class FeeInvoiceParticulars extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FeeInvoiceParticulars the static model class
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
		return 'fee_invoice_particulars';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invoice_id, name', 'required'),
			array('amount', 'type', 'type'=>'float', 'message'=>Yii::t('app', '{attribute} must be a valid number')),
			array('invoice_id', 'length', 'max'=>20),
			array('name', 'length', 'max'=>250),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, invoice_id, name, description, amount', 'safe', 'on'=>'search'),
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
			'invoice_id' => Yii::t('app','Invoice'),
			'name' => Yii::t('app','Name'),
			'description' => Yii::t('app','Description'),
			'amount' => Yii::t('app','Amount'),
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
		$criteria->compare('invoice_id',$this->invoice_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('amount',$this->amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}