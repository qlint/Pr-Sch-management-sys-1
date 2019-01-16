<?php

/**
 * This is the model class for table "fee_transactions".
 *
 * The followings are the available columns in table 'fee_transactions':
 * @property integer $id
 * @property integer $invoice_id
 * @property string $date
 * @property integer $payment_type
 * @property string $transaction_id
 * @property string $description
 * @property string $amount
 * @property string $proof
 * @property integer $is_deleted
 */
class FeeTransactions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FeeTransactions the static model class
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
		return 'fee_transactions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invoice_id, date, payment_type, amount', 'required'),
			array('invoice_id, payment_type, is_deleted', 'numerical', 'integerOnly'=>true),
			array('description, transaction_id, proof_type', 'safe'),
			array('proof', 'file', 'allowEmpty'=>true, 'types'=>'jpg,jpeg,gif,png,txt,doc,docx'),
			array('amount', 'type', 'type'=>'float', 'message'=>Yii::t('app', '{attribute} must be a valid number')),
			array('amount', 'validAmount', 'on'=>'transaction'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, invoice_id, date, payment_type, transaction_id, description, amount, proof, is_deleted', 'safe', 'on'=>'search'),
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
			'invoice_id' => Yii::t('app', 'Invoice'),
			'date' => Yii::t('app', 'Date'),
			'payment_type' => Yii::t('app', 'Payment Type'),
			'transaction_id' => Yii::t('app', 'Transaction ID'),
			'description' => Yii::t('app', 'Description'),
			'amount' => Yii::t('app', 'Amount'),
			'proof' => Yii::t('app', 'Proof'),
			'is_deleted' => Yii::t('app', 'Is Deleted'),
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
		$criteria->compare('invoice_id',$this->invoice_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('payment_type',$this->payment_type);
		$criteria->compare('transaction_id',$this->transaction_id,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('proof',$this->proof,true);
		$criteria->compare('is_deleted',$this->is_deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function validAmount(){
		$amount_payable		= FeeInvoices::model()->getAmountPayable($this->invoice_id);
		if($this->amount>$amount_payable){
			$this->addError("amount", Yii::t("app", "Amount must be lessthan or equal to amount payable"));
		}
	}
	
	public function getTransactionType(){
		$paymenttype	= FeePaymentTypes::model()->findByPk($this->payment_type);
		if($paymenttype)
			return $paymenttype->type;
		return "-";
	}

	public function getDeletedUser(){
		$user 	= User::model()->findByPk($this->deleted_by);
		if($user!=NULL){
			$profile	= Profile::model()->findByAttributes(array('user_id'=>$user->id));
			if($profile!=NULL)
				return $profile->fullname;
		}
		return NULL;
	}
}