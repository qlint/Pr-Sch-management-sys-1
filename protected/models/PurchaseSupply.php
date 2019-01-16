<?php

/**
 * This is the model class for table "purchase_supply".
 *
 * The followings are the available columns in table 'purchase_supply':
 * @property integer $id
 * @property integer $item_id
 * @property integer $vendor_id
 * @property integer $quantity
 * @property string $price
 * @property integer $payment_id
 * @property integer $status
 */
class PurchaseSupply extends CActiveRecord
{
	public $which_button;
	/**
	 * Returns the static model of the specified AR class.
	 * @return PurchaseSupply the static model class
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
		return 'purchase_supply';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_id, vendor_id, quantity', 'required'),
			array('vendor_id, status', 'numerical', 'integerOnly'=>true),
			array('quantity', 'numerical', 'min' => 1, 'max'=>2147483647, 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, item_id, vendor_id, quantity, status, is_deleted', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'item_id' => 'Item',
			'vendor_id' => 'Vendor',
			'quantity' => 'Quantity',
			'status' => 'Status',
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
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('vendor_id',$this->vendor_id);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('status',$this->status);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function vendorName($data,$row)
	{
		$vendor = PurchaseVendors::model()->findByAttributes(array('id'=>$data->vendor_id));
		echo ucfirst($vendor->first_name).' '. ucfirst($vendor->last_name);
		
	}
}