<?php

/**
 * This is the model class for table "fee_invoices".
 *
 * The followings are the available columns in table 'fee_invoices':
 * @property string $id
 * @property integer $academic_year_id
 * @property integer $uid
 * @property string $fee_id
 * @property string $subscription_id
 * @property string $name
 * @property string $description
 * @property integer $subscription_type
 * @property string $start_date
 * @property string $end_date
 * @property string $due_date
 * @property integer $total_amount
 * @property integer $amount_paid
 * @property integer $is_paid
 */
class FeeInvoices extends CActiveRecord
{
	public $course;
	public $batch;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return FeeInvoices the static model class
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
		return 'fee_invoices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('academic_year_id, uid, fee_id, subscription_id, name, subscription_type, start_date, end_date, due_date', 'required'),
			array('academic_year_id, uid, subscription_type, is_paid', 'numerical', 'integerOnly'=>true),
			array('amount_paid, total_amount', 'type', 'type'=>'float', 'message'=>Yii::t('app', '{attribute} must be a valid number')),
			array('fee_id, subscription_id', 'length', 'max'=>20),
			array('name', 'length', 'max'=>250),
			array('description, user_type, table_id, total_amount, amount_paid, is_paid', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, academic_year_id, uid, fee_id, subscription_id, name, description, subscription_type, start_date, end_date, due_date, total_amount, amount_paid, is_paid', 'safe', 'on'=>'search'),
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
	
	public function defaultScope()
	{
		return array(
			'join'=>'JOIN `students` `st` ON `t`.`table_id`=`st`.`id`',
			'condition'=> "t.user_type=1 AND st.is_active = 1 AND st.is_deleted = 0",
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'academic_year_id' => Yii::t('app','Academic Year'),
			'uid' => Yii::t('app','Uid'),
			'fee_id' => Yii::t('app','Fee'),
			'subscription_id' => Yii::t('app','Subscription'),
			'name' => Yii::t('app','Name'),
			'description' => Yii::t('app','Description'),
			'subscription_type' => Yii::t('app','Subscription Type'),
			'start_date' => Yii::t('app','Start Date'),
			'end_date' => Yii::t('app','End Date'),
			'due_date' => Yii::t('app','Due Date'),
			'total_amount' => Yii::t('app','Total Amount'),
			'amount_paid' => Yii::t('app','Amount Paid'),
			'is_paid' => Yii::t('app','Is Paid'),
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
		$criteria->compare('academic_year_id',$this->academic_year_id);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('fee_id',$this->fee_id,true);
		$criteria->compare('subscription_id',$this->subscription_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('subscription_type',$this->subscription_type);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('due_date',$this->due_date,true);
		$criteria->compare('total_amount',$this->total_amount);
		$criteria->compare('amount_paid',$this->amount_paid);
		$criteria->compare('is_paid',$this->is_paid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getAmountPayable($id){
		$criteria		= new CDbCriteria;
		$criteria->compare("invoice_id", $id);
		$particulars	= FeeInvoiceParticulars::model()->findAll($criteria);
		
		$criteria		= new CDbCriteria;
		$criteria->compare('invoice_id', $id);
		$alltransactions	= FeeTransactions::model()->findAll($criteria);
		
		$invoice_amount = 0;
		foreach($particulars as $key=>$particular){
			$amount = $particular->amount;
			//apply discount
			if($particular->discount_type==1){  //percentage
				$idiscount  = (($particular->amount * $particular->discount_value)/100);
				$amount     = $amount - $idiscount;
			}
			else if($particular->discount_type==2){ //amount
				$amount = $amount - $particular->discount_value;
			}
			
			//apply tax
			if($particular->tax!=0){
				$tax    = FeeTaxes::model()->findByPk($particular->tax);
				if($tax!=NULL){
					$itax   = (($amount * $tax->value)/100);
					$amount = $amount + $itax;
				}
			}
			$invoice_amount   += $amount;
		}
		
		$amount_payable = 0;
		$payments       = 0;
		$adjustments    = 0;
	
		foreach($alltransactions as $index=>$ctransaction){
			if($ctransaction->is_deleted==0 and $ctransaction->status==1){
				if($ctransaction->amount<0){
					$adjustments    += $ctransaction->amount;
				}
				else{
					$payments       += $ctransaction->amount;
				}
			}
		}
	
		$amount_payable = $invoice_amount - ( $payments + $adjustments );
		return $amount_payable;
	}
}