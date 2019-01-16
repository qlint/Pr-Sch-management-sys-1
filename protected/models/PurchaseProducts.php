<?php

/**
 * This is the model class for table "product_details".
 *
 * The followings are the available columns in table 'product_details':
 * @property integer $id
 * @property integer $vendor_id
 * @property string $item_id
 * @property string $description
 * @property integer $price
 */
class PurchaseProducts extends CActiveRecord
{
	public $which_button;
	/**
	 * Returns the static model of the specified AR class.
	 * @return ProductDetails the static model class
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
		return 'purchase_products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_id, description, price', 'required'),
			array('vendor_id, item_id, price', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, vendor_id, item_id, description, price,', 'safe', 'on'=>'search'),
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
			'vendor_id' => 'Vendor',
			'item_id' => 'Item',
			'description' => 'Description',
			'price' => 'Price',
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
		$criteria->compare('vendor_id',$this->vendor_id);
		$criteria->compare('item_id',$this->item_id,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price',$this->price);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function itemName($data,$row){
		$item = PurchaseItems::model()->findByAttributes(array('id'=>$data->item_id));
		echo ucfirst($item->name);
	}
	
}