
    
    <?php
$this->breadcrumbs=array(
	Yii::t('app','Students')=>array('index'),
	Yii::t('app','View'),
);


?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('left_side');?>
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
    <!--<div class="searchbx_area">
    <div class="searchbx_cntnt">
    	<ul>
        <li><a href="#"><img src="images/search_icon.png" width="46" height="43" /></a></li>
        <li><input class="textfieldcntnt"  name="" type="text" /></li>
        </ul>
    </div>
    
    </div>-->
 
    <div class="clear"></div>
    <div class="emp_right_contner">
    <div class="emp_tabwrapper">
     <div class="emp_tab_nav">
    <ul style="width:730px;">
          <li> 
		
        <?php     
	          if(Yii::app()->controller->action->id=='student')
	          {
		      echo CHtml::link(Yii::t('app','Student'), array('/importcsv/users/student'),array('class'=>'active'));
			  }
			  else
			  {
	          echo CHtml::link(Yii::t('app','Student'), array('/importcsv/users/student'));
			  }
	    ?>
		</li>
        
        
        
        <li> 
		
        <?php     
	          if(Yii::app()->controller->action->id=='parent')
	          {
		      echo CHtml::link(Yii::t('app','Parent'), array('/importcsv/users/parent'),array('class'=>'active'));
			  }
			  else
			  {
	          echo CHtml::link(Yii::t('app','Parent'), array('/importcsv/users/parent'));
			  }
	    ?>
		</li>
        
        <li> 
		
        <?php     
	          if(Yii::app()->controller->action->id=='employee')
	          {
		      echo CHtml::link(Yii::t('app','Teacher'), array('/importcsv/users/employee'),array('class'=>'active'));
			  }
			  else
			  {
	          echo CHtml::link(Yii::t('app','Teacher'), array('/importcsv/users/employee'));
			  }
	    ?>
		</li>
        
      
     
    <?php /*?><li><a href="#">Additional Notes</a></li><?php */?>
    </ul>
    </div>
    <div class="clear"></div>
    <div class="emp_cntntbx" >
    
    
    </div>
    </div>
    
    </div>
    </div>
   
    </td>
  </tr>
</table>