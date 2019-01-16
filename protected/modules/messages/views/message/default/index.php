<?php
$this->breadcrumbs=array(
	Yii::t('app', 'Message'),	
);

?>
<div style="background:#FFF;">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" >
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top" >
            <div style="padding:0px 20px 20px 20px;">
            <div align="right">
            <div style="padding:6px 0px;">
            <?php $form=$this->beginWidget('CActiveForm'); ?>
            	<table width="29%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><input type="checkbox" name="dontshow" id="checkbox" />
      <label for="checkbox"></label></td>
    <td style="font-size:11px; color:#999"><strong><?php echo Yii::t('app', "Don't show this messages again.");?></strong></td>
    <td><input name="hide" type="submit" class="wel_subbut"  value="<?php echo Yii::t('app', "Hide");?>" /></td>
  </tr>
</table>
<?php $this->endWidget(); ?>
</div>
		
            </div>	
            
              <div class="welcome_Con">
                <h1><?php echo Yii::t('app', "Congratulations ! Your ".Yii::app()->params['app_name']." installation is complete !");?></h1>
                <p><?php echo Yii::t('app', "Your ".Yii::app()->params['app_name']." system is now up and running.");?></p>
                </div>
              <div class="yellow_bx">
                <div class="thakyo_strip"></div>
                <div class="y_bx_head">
                  <?php echo Yii::t('app', "It appears that this is the first time that you are using this ".Yii::app()->params['app_name']." installation. For any new installation we recommend that you configure the following:");?>
                  </div>

                <div class="y_bx_list">
                  <h1><?php echo Yii::t('app', "Create a new academic year");?></h1>
                  <p><?php echo Yii::t('app', "Before moving on to adding students, courses etc the first step is to create an Academic Year as it forms the backbone for all operations in the application.");?> <br/><?php echo CHtml::link(Yii::t('app', 'Academic Year Management'),array('/academicYears/admin')) ?></p>
                </div>

                <div class="y_bx_list">
                  <h1><?php echo Yii::t('app', "Save your School Configurations");?></h1>
                  <p><?php echo Yii::t('app', "Enter details of your school, choose your application language, currency etc in the School Congfiguration area.");?> <br/><?php echo CHtml::link(Yii::t('app', 'School Configuration'),array('/configurations/create')) ?></p>
                </div>
                <div class="y_bx_list">
                  <h1><?php echo Yii::t('app', "Course and").' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.'Management';?></h1>
                  <p><?php echo Yii::t('app', "Creating Courses and").' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app',"will allow you to manage day-to-day student activities like attendance, examinations etc.");?><br/><?php echo CHtml::link(Yii::t('app', 'Add a Course and').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/courses/courses/create')) ?></p>
                  </div>
                <div class="y_bx_list">
                  <h1><?php echo Yii::t('app', "Add students");?></h1>
                  <p><?php echo Yii::t('app', "Get started with adding students into a ").' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?><br/><?php echo CHtml::link(Yii::t('app', 'Create a new student'),array('/students/students/create')) ?></p>
                  </div>
                <div class="y_bx_list">
                  <h1><?php echo Yii::t('app', "Add Teachers");?></h1>
                  <p><?php echo Yii::t('app', "Before adding Teachers, make sure you have created departments, categories and positions for them.");?><br/><?php echo CHtml::link(Yii::t('app', 'Create a new teacher'),array('/employees/employees/create')) ?></p>
                  </div>
                <div class="y_bx_list">
                  <h1><?php echo Yii::t('app', "Roles and Permissions");?></h1>
                  <p><?php echo Yii::t('app', "Create custom roles that can be assigned to custom users. You can manage who has access to which module in the application.");?><br/><?php echo CHtml::link(Yii::t('app', 'User Management'),array('/user/admin')) ?></p>
                  </div>
                
                </div>
            </div>
          </td>
          
        </tr>
      </table>
    </td>
  </tr>
</table>
</div>
        <script type="text/javascript">

	$(document).ready(function () {
            //Hide the second level menu
            $('#othleft-sidebar ul li ul').hide();            
            //Show the second level menu if an item inside it active
            $('li.list_active').parent("ul").show();
            
            $('#othleft-sidebar').children('ul').children('li').children('a').click(function () {                    
                
                 if($(this).parent().children('ul').length>0){                  
                    $(this).parent().children('ul').toggle();    
                 }
                 
            });
          
            
        });
    </script>