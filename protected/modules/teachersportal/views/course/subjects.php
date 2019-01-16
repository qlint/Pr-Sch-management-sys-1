<style>

</style>
<?php $this->renderPartial('/default/leftside');?> 
   <div class="pageheader">
      <h2><i class="fa fa-list-alt"></i> <?php echo Yii::t('app', 'My Course');?> <span><?php echo Yii::t('app', 'View courses here');?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
         <li class="active"><?php echo Yii::t('app', 'Course');?></li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
<div class="col-sm-9 col-lg-12">
<div class="panel panel-default">

<!--<div class="panel-heading">
                         
                          <h3 class="panel-title">Profile Details</h3>
                        </div>-->
                        
                       
    <?php $this->renderPartial('changebatch');?>          
     
                      
<div class="panel-body">

    <div id="parent_rightSect">
        <div class="parentright_innercon">
        	<?php $this->renderPartial('batch');?>
            <div class="edit_bttns" style="top:100px; right:25px">
                <ul>
                    <li>
                    <?php //echo CHtml::link('<span>'.Yii::t('studentportal','My Courses').'</span>', array('/studentportal/course'),array('class'=>'addbttn last'));?>
                    </li>
                </ul>
            </div>
            
         	
            <!-- Subjects Grid -->
            <div class="profile_details">
           
            <?php
			$employee= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			
			$this->widget('zii.widgets.grid.CGridView', array(
			'id' => 'subjects-grid',
			'dataProvider' => $dataProvider,
			'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
			'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
			'htmlOptions'=>array('class'=>'grid-view clear'),
			'columns' => array(
				'name',
				//'code',
			 array(
				'header'=>Yii::t('app','First Sub Category'),
				'value'=>array($model,'getsub_category1'), 
			),
			array(
				'header'=>Yii::t('app','Second Sub Category'),
				'value'=>array($model,'getsub_category2'), 
			),
				
			),
			'afterAjaxUpdate'=>'js:function(id,data){$.bind_crud()}'
			));
			?>
            
            </div>
            <!-- END Subjects Grid -->
            
            
            
        </div> 
        </div>
        </div>
        </div>
        </div>
        <!-- END div class="parentright_innercon" -->
    
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
<div class="clear"></div>

