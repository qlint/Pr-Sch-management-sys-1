<?php

/**
 * This is the model class for table "vendor_details".
 *
 * The followings are the available columns in table 'vendor_details':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $address_1
 * @property string $address_2
 * @property string $city
 * @property string $state
 * @property integer $country_id
 * @property string $email
 * @property string $phone
 * @property string $currency
 * @property string $company_name
 * @property string $vat_number
 * @property string $cst_number
 * @property string $office_phone
 */
class PurchaseVendors extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return VendorDetails the static model class
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
		return 'purchase_vendors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_name, last_name, address_1, address_2, city, state, country_id, email, phone, currency, company_name, vat_number, cst_number, office_phone', 'required'),
			array('country_id, office_phone, phone', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name, address_1, address_2, city, state, email, phone, currency, company_name, vat_number, cst_number, office_phone', 'length', 'max'=>255),
			array('first_name, last_name, address_1, address_2, city, state, company_name, vat_number, cst_number', 'length', 'max'=>25),
			array('phone, office_phone', 'length', 'max'=>15),
			array('email','email'),	
			array('email','unique'),		
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, first_name, last_name, address_1, address_2, city, state, country_id, email, phone, currency, company_name, vat_number, cst_number, office_phone,', 'safe', 'on'=>'search'),
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
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'address_1' => 'Address 1',
			'address_2' => 'Address 2',
			'city' => 'City',
			'state' => 'State',
			'country_id' => 'Country',
			'email' => 'Email',
			'phone' => 'Phone',
			'currency' => 'Currency',
			'company_name' => 'Company Name',
			'vat_number' => 'VAT Number',
			'cst_number' => 'CST Number',
			'office_phone' => 'Office Phone',
			
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
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('address_1',$this->address_1,true);
		$criteria->compare('address_2',$this->address_2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('vat_number',$this->vat_number,true);
		$criteria->compare('cst_number',$this->cst_number,true);
		$criteria->compare('office_phone',$this->office_phone,true);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getFullname()
	{		           
		$name = ucfirst($this->first_name).' '.ucfirst($this->last_name);		   
		return $name;
	}
}