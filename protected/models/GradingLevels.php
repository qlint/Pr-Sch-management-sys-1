<?php

/**
 * This is the model class for table "grading_levels".
 *
 * The followings are the available columns in table 'grading_levels':
 * @property integer $id
 * @property string $name
 * @property integer $batch_id
 * @property integer $min_score
 * @property integer $order
 * @property integer $is_deleted
 * @property string $created_at
 * @property string $updated_at
 */
class GradingLevels extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return GradingLevels the static model class
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
		return 'grading_levels';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('batch_id, min_score, order, is_deleted', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('min_score', 'length', 'max'=>3),
			array('created_at, updated_at', 'safe'),
			array('name, min_score','required'),
			array('min_score', 'checkscore'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, batch_id, min_score, order, is_deleted, created_at, updated_at', 'safe', 'on'=>'search'),
                    array('id, name, batch_id, min_score, order, is_deleted, created_at, updated_at', 'safe', 'on'=>'searchs'),
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
			'name' => Yii::t("app",'Name'),
			'batch_id' => Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),
			'min_score' => Yii::t("app",'Min Score'),
			'order' => Yii::t("app",'Order'),
			'is_deleted' => Yii::t("app",'Is Deleted'),
			'created_at' => Yii::t("app",'Created At'),
			'updated_at' => Yii::t("app",'Updated At'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('batch_id',$this->batch_id);
		$criteria->compare('min_score',$this->min_score);
		$criteria->compare('order',$this->order);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function searchs()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		if(isset($_GET['key']))
		{
			if($_GET['key']=='NULL')
                        {   
		    $criteria->condition= 'batch_id IS NULL';
                        }
                       else
			$criteria->compare('batch_id',$_GET['key']);
			
		}
		else
		{
			$criteria->compare('batch_id',$this->batch_id);
		}
		$criteria->compare('min_score',$this->min_score);
		$criteria->compare('order',$this->order);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
	public function getCodeArray() {
        $codes = self::model()->findAll();
        $data = array (); // data to be returned
        foreach ($codes as $c) {
            $data[$c->id] = $c->id;
        }
        return $data;
    }
	public function checkScore($attribute,$params)
	{	                        
            if($this->name!='' and $this->min_score!='')
            {                                                                          
                $grade = GradingLevels::model()->findByAttributes(array('batch_id'=>$this->batch_id, 'min_score'=>$this->min_score));
                if($grade!=NULL )
                {
                   $this->addError('min_score',Yii::t("app",'Already grade created for this minimum score'));
                     
                }                                    
            }
	}
	
}