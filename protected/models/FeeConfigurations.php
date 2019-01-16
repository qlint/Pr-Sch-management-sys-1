<?php

/**
 * This is the model class for table "fee_configurations".
 *
 * The followings are the available columns in table 'fee_configurations':
 * @property integer $id
 * @property integer $tax_in_fee
 * @property integer $discount_in_fee
 * @property integer $discount_in_invoice
 * @property integer $invoice_template
 */
class FeeConfigurations extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FeeConfigurations the static model class
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
		return 'fee_configurations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invoice_template', 'required'),
			array('tax_in_fee, discount_in_fee, discount_in_invoice', 'safe'),
			array('tax_in_fee, discount_in_fee, discount_in_invoice, invoice_template', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tax_in_fee, discount_in_fee, discount_in_invoice, invoice_template', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('app', 'ID'),
			'tax_in_fee' => Yii::t('app', 'Enable Tax in Fee'),
			'discount_in_fee' => Yii::t('app', 'Enable Discount in Fee'),
			'discount_in_invoice' => Yii::t('app', 'Show Discount Column in Invoice Template'),
			'invoice_template' => Yii::t('app', 'Invoice Template'),
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
		$criteria->compare('tax_in_fee',$this->tax_in_fee);
		$criteria->compare('discount_in_fee',$this->discount_in_fee);
		$criteria->compare('discount_in_invoice',$this->discount_in_invoice);
		$criteria->compare('invoice_template',$this->invoice_template);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}