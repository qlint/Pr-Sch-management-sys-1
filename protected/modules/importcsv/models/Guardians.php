<?php

/**
 * This is the model class for table "guardians".
 *
 * The followings are the available columns in table 'guardians':
 * @property integer $id
 * @property integer $ward_id
 * @property string $first_name
 * @property string $last_name
 * @property string $relation
 * @property string $email
 * @property string $office_phone1
 * @property string $office_phone2
 * @property string $mobile_phone
 * @property string $office_address_line1
 * @property string $office_address_line2
 * @property string $city
 * @property string $state
 * @property integer $country_id
 * @property string $dob
 * @property string $occupation
 * @property string $income
 * @property string $education
 * @property string $created_at
 * @property string $updated_at
 */
class Guardians extends CActiveRecord
{
	public $radio;
	public $user_create;
    public $relation_other;
	public $same_address;
    public $student_name;
	
	private $_model;
	private $_modelReg;
	private $_rules = array();
	/**
	 * Returns the static model of the specified AR class.
	 * @return Guardians the static model class
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
		return 'guardians';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		if (!$this->_rules) {
			$required = array();
			$numerical = array();					
			$decimal = array();
			$safe	= array();
			$rules = array();
			
			$model=$this->getFields();
			
			foreach ($model as $field) {
				$field_rule 	= array();
				$rule_added		= false;
				if ($field->required==FormFields::REQUIRED_YES){
					array_push($required,$field->varname);
					$rule_added		= true;
				}
				if ($field->field_type=='DECIMAL'){
					array_push($decimal,$field->varname);
					$rule_added		= true;
				}
				if ($field->field_type=='INTEGER'){
					array_push($numerical,$field->varname);
					$rule_added		= true;
				}
				
				if($rule_added==false){
					array_push($safe,$field->varname);
				}
			}			
			array_push($rules,array(implode(',',$required), 'required'));
			array_push($rules,array(implode(',',$numerical), 'numerical', 'integerOnly'=>true));			
			array_push($rules,array(implode(',',$decimal), 'match', 'pattern' => '/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/'));
			array_push($rules,array(implode(',',$safe), 'safe'));
			array_push($rules,array('email','email'));
			//array_push($rules,array('email','unique'));
			array_push($rules,array('email','check'));
			array_push($rules,array('relation_other','check_relation'));
			
			$this->_rules = $rules;
		}
		return $this->_rules;  				
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		 'emergency'=>array(self::BELONGS_TO, 'Students', 'id'),
		 'active'=>array(self::BELONGS_TO, 'Students', 'is_active'),
		);
	}
        
	//for check relation is others and validation
	public function check_relation()
	{
		if($this->relation=='Others')
		{
			if($this->relation_other==" ")
			{
				$this->addError('relation_other', Yii::t("app","Relation Cannot be Blank"));
			}
		}
	}
        
	//check the email is unique
	public function check($attribute,$params)
    {	            				
		$student	= Students::model()->findByAttributes(array('email'=>$this->$attribute));
		$employee	= Employees::model()->findByAttributes(array('email'=>$this->$attribute));
		//$validate = User::model()->findByAttributes(array('email'=>$this->$attribute));
		if($this->$attribute!=''){
			if($employee!=NULL or $student!=NULL){
				$this->addError($attribute,Yii::t("app",'Email ').'"'.$this->$attribute.'"'.Yii::t('app',' has already been taken'));
			}
		}                               
    }
	//check the phone number is unique
	public function check_phone($attribute,$params)
    {
		
		$student= Students::model()->findByAttributes(array('phone1'=>$this->$attribute));
		$employee= Employees::model()->findByAttributes(array('mobile_phone'=>$this->$attribute));
		$parent= Guardians::model()->findByAttributes(array('mobile_phone'=>$this->$attribute));
		
		if(Yii::app()->controller->action->id!='update' and $this->$attribute!='')
		{
			
			if($student!=NULL or $employee!=NULL or $parent!=NULL)
			{
			
				$this->addError($attribute,Yii::t("app",'Mobile Phone already in use'));
			}
		}
		elseif(Yii::app()->controller->action->id == 'update' and $this->$attribute!='')
		{
			if($student!=NULL or $employee!=NULL or $parent!=NULL)
			{
				if($parent->id != $this->id)
					$this->addError($attribute,Yii::t("app",'Mobile Phone already in use'));
			}
		}
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		$labels = array(
			'uid' => Yii::t('app','User ID'),
			'id' => Yii::t("app",'ID'),
			'ward_id' => Yii::t("app",'Ward'),
			'relation_other'=>Yii::t("app",'Specify Relation')
		);
		$model=$this->getFields();
		
		foreach ($model as $field){
			$labels[$field->varname] = Yii::t('app', $field->title);
		}
			
		return $labels;			
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
		$criteria->join = 'JOIN guardian_list t2 ON t.id = t2.guardian_id JOIN students t1 ON t1.id = t2.student_id'; 
		$criteria->distinct = true;
		$criteria->condition = 't1.type=:type';
		$criteria->params = array(':type'=>0);
		$criteria->compare('t.id',$this->id);                
		$criteria->compare('t.ward_id',$this->ward_id);
		$criteria->compare('t.first_name',$this->first_name,true);
		$criteria->compare('t.last_name',$this->last_name,true);
		$criteria->compare('t.relation',$this->relation,true);
		$criteria->compare('t.email',$this->email,true);
		$criteria->compare('t.office_phone1',$this->office_phone1,true);
		$criteria->compare('t.office_phone2',$this->office_phone2,true);
		$criteria->compare('t.mobile_phone',$this->mobile_phone,true);
		$criteria->compare('t.office_address_line1',$this->office_address_line1,true);
		$criteria->compare('t.office_address_line2',$this->office_address_line2,true);
		$criteria->compare('t.city',$this->city,true);
		$criteria->compare('t.state',$this->state,true);
		$criteria->compare('t.country_id',$this->country_id);
		$criteria->compare('t.dob',$this->dob,true);
		$criteria->compare('t.occupation',$this->occupation,true);
		$criteria->compare('t.income',$this->income,true);
		$criteria->compare('t.education',$this->education,true);
		$criteria->compare('t.created_at',$this->created_at,true);
		$criteria->compare('t.updated_at',$this->updated_at,true);
		$criteria->compare('t.is_delete',0,true);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	function studentname($data,$row)
	{
           //echo $data->id;
		$posts = Students::model()->findAllByAttributes(array('parent_id'=>$data->id));
		if($posts!=NULL)
		{
			$students = array();
			foreach($posts as $post)
			{
				echo $post->first_name.' '.$post->last_name.'<br/>';
			}
		}
		else
		{
			return '-';
		}
	}
        
        function students($data,$row)
	{
          
           $array_list= array();
           $glist= GuardianList::model()->findAllByAttributes(array('guardian_id'=>$data->id));
           if($glist)
           {
               foreach ($glist as $student)
               {
                   $st_list= Students::model()->findByAttributes(array('id'=>$student->student_id,'is_active'=>1,'is_deleted'=>0));
                   if($st_list)
                   {
                       
                       $array_list[]=  ucfirst($st_list->first_name)." ".  ucfirst($st_list->last_name); 
                   }
               }
           }
           return implode(",", $array_list);
           
		
	}
        function studentlist($data,$row)
	{
          
           $array_list= array();
           $glist= GuardianList::model()->findAllByAttributes(array('guardian_id'=>$data->id));
           if($glist)
           {
               foreach ($glist as $student)
               {
                   $st_list= Students::model()->findByAttributes(array('id'=>$student->student_id,'is_active'=>1,'is_deleted'=>0));
                   if($st_list)
                   {
                       $name='';
                       if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                        {
                           $name='';
                            $name=  $st_list->studentFullName('forStudentProfile');
                        }
                       $array_list[]=  $name; 
                   }
               }
           }
           return implode(",", $array_list);
           
		
	}
        
        //action for het student name - multiple parent
        function studentname_parent($data,$row)
	{
            $list= GuardianList::model()->findByAttributes(array('guardian_id'=>$data->id));
            if($list)
            {
                $student_id= $list->student_id;
            }
            
		$posts = Students::model()->findByPk($student_id);
		if($posts!=NULL)
		{
			$students = array();
			//foreach($posts as $post)
			{
                            
				echo $posts->first_name.' '.$posts->last_name.'<br/>';
			}
		}
		else
		{
			return '-';
		}
	}
	
	function parentname($data,$row)
	{
		$name= "";
		//$posts=Students::model()->findByAttributes(array('id'=>$data->ward_id));
		if(FormFields::model()->isVisible('first_name','Guardians','forAdminRegistration'))
		{
			$name.= ucfirst($data->first_name);
		}
		if(FormFields::model()->isVisible('last_name','Guardians','forAdminRegistration'))
		{
			$name.= " ".ucfirst($data->last_name);
		}

		if($name=="")
		{
			return "-";
		}
		else
		{
			return CHtml::link($name, array('/students/guardians/view','id'=>$data->id));
		}

        //return CHtml::link(ucfirst($data->first_name).' '.ucfirst($data->last_name), array('/students/guardians/view','id'=>$data->id));
		//return ucfirst($data->first_name).' '.ucfirst($data->last_name);	
	}
        function parentnamedata($data,$row)
	{
		$name= "";
		//$posts=Students::model()->findByAttributes(array('id'=>$data->ward_id));
		if(FormFields::model()->isVisible('first_name','Guardians','forStudentProfile'))
		{
			$name.= ucfirst($data->first_name);
		}
		if(FormFields::model()->isVisible('last_name','Guardians','forStudentProfile'))
		{
			$name.= " ".ucfirst($data->last_name);
		}

		if($name=="")
		{
			return "-";
		}
		else
		{
			return CHtml::link($name, array('/students/guardians/view','id'=>$data->id));
		}

        //return CHtml::link(ucfirst($data->first_name).' '.ucfirst($data->last_name), array('/students/guardians/view','id'=>$data->id));
		//return ucfirst($data->first_name).' '.ucfirst($data->last_name);	
	}

	public function getFullname(){
		$name 	= "";
		if(FormFields::model()->isVisible('first_name','Guardians','forStudentPortal'))
        {
            $name 	.= ucfirst($this->first_name);
        }

        if(FormFields::model()->isVisible('last_name','Guardians','forStudentPortal'))
        {
            $name 	.= (($name!="")?" ":"").ucfirst($this->last_name);
        }

        return $name;
	}

	public function parentFullName($scope='forStudentPortal'){
		$name 	= "";

		if(FormFields::model()->isVisible('first_name', 'Guardians', $scope))
        {
            $name 	.= ucfirst($this->first_name);
        }
        
        if(FormFields::model()->isVisible('last_name','Guardians', $scope))
        {
            $name 	.= (($name!="")?" ":"").ucfirst($this->last_name);
        }

        return $name;
	}
        
	//function for return guardian relation - parent details
	function Guardian_relations()
	{
		   
		$id= $_REQUEST['id'];
		$relations= CHtml::listData(GuardianList::model()->findAllByAttributes(array('student_id'=>$id)), 'id', 'relation');
		$list= array('Father'=>Yii::t("app",'Father'),'Mother'=>Yii::t("app",'Mother'),'Others'=>Yii::t("app",'Others'));                        
	   
		//$list= array('Father'=>Yii::t("app",'Father'),'Mother'=>Yii::t("app",'Mother'),'Others'=>Yii::t("app",'Others'));
		return array_diff($list, $relations);
		
		
	}
	
	function Guard_relations()
	{                          
		$list= array('Father'=>Yii::t("app",'Father'),'Mother'=>Yii::t("app",'Mother'),'Others'=>Yii::t("app",'Others'));                                   
		//$list= array('Father'=>Yii::t("app",'Father'),'Mother'=>Yii::t("app",'Mother'),'Others'=>Yii::t("app",'Others'));
		return ($list);                        
	}
	
//Get the fiedls from form_fields	
	public function getFields() {
		$scope 		= NULL;
		$scope 		= Yii::app()->controller->module->scope;
		
		$criteria	= new CDbCriteria;
		$criteria->condition	= "`tab_selection`=:tab_selection AND `model`=:model";
		$criteria->params		= array(':tab_selection'=>2, 'model'=>"Guardians");
		if($scope!=NULL){
			$this->_modelReg	= FormFields::model()->$scope()->findAll($criteria);
		}
		else{
			$this->_modelReg	= FormFields::model()->findAll($criteria);
		}

		return $this->_modelReg;	
	}		
}