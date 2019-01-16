<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<title>
<?php $college=Configurations::model()->findByPk(1); ?>
<?php echo $college->config_value ; ?></title>
<title>
<?php $college=Configurations::model()->findByPk(1); ?>
<?php echo $college->config_value ; ?></title>
<link href="portal_css/style.default.css" rel="stylesheet">
<link href="portal_css/fullcalendar.css" rel="stylesheet">
<link href="portal_css/jquery.datatables.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl;?>/portal_js/jquery-1.7.2.min.js"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->

<?php
  //disable jquery autoload
		Yii::app()->clientScript->scriptMap=array(
			'jquery.min.js'=>false,
			'jquery.js'=>false,
		);
  
  ?>
</head>
<body>

<!-- Preloader -->
<div id="preloader">
  <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>
<section>
  <div class="mainpanel">
    <div class="headerbar"> <a class="menutoggle"><i class="fa fa-bars"></i></a>
      <?php 
                        //$user=User::model()->findByAttributes(array('id'=>Yii::app()->user->id));
                        $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
                        //$guard=Guardians::model()->findByAttributes(array('ward_id'=>$student->id));
                        ?>
      <div class="searchform"><span class="col-md-4"><?php echo Yii::t('studentportal','Welcome'); ?> <strong><?php echo ucfirst($student->last_name.' '.$student->first_name);?></strong> <?php echo Yii::t('studentportal','in to your profile.'); ?></span></div>
      <div class="header-right">
        <ul class="headermenu">
          <li>
            <div class="btn-group"> <?php echo CHtml::link(Yii::t('studentportal','<i class="glyphicon glyphicon-envelope"></i><span class="badge">'.Mailbox::model()->newMsgs(Yii::app()->user->id).'</span>'),array('/portalmailbox'),array('class'=>'btn btn-default dropdown-toggle tp-icon')); ?> </div>
          </li>
          <li>
            <div class="btn-group">
              <?php 
                        //$user=User::model()->findByAttributes(array('id'=>Yii::app()->user->id));
                        $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
                        //$guard=Guardians::model()->findByAttributes(array('ward_id'=>$student->id));
                        ?>
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              <?php
						 if($student->photo_file_name!=NULL)
						 { 
						 	$path = Students::model()->getProfileImagePath($student->id);	
							echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'"  width="40" height="41" />';
						}
						elseif($student->gender=='M')
						{
							echo '<img  src="images/portal/p-small-male_img.png" alt='.$student->first_name.' />'; 
						}
						elseif($student->gender=='F')
						{
							echo '<img  src="images/portal/p-small-female_img.png" alt='.$student->first_name.' />';
						}
						?>
              <strong><?php echo ucfirst($student->last_name.' '.$student->first_name);?></strong> <span class="caret"></span> </button>
              <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                <li> <?php echo CHtml::link(Yii::t('studentportal','<i class="glyphicon glyphicon-user"></i> My Account'),array('/studentportal/default/profile'),array('class'=>'profile')); ?> </li>
                
                <li> <?php echo CHtml::link(Yii::t('studentportal','<i class="glyphicon glyphicon-cog"></i> Settings'),array('/user/profile'),array('class'=>'profile')); ?> </li>
                <li> <?php echo CHtml::link(Yii::t('studentportal','<i class="glyphicon glyphicon-log-out"></i> Logout'), array('/user/logout'));?> </li>
              </ul>
            </div>
          </li>
        </ul>
      </div><!-- header-right -->
    </div><!-- headerbar -->
    <?php echo $content;?> 
    </div>
  <!-- mainpanel --> 
</section>


<script src="portal_js/jquery-migrate-1.2.1.min.js"></script> 
<script src="portal_js/bootstrap.min.js"></script> 
<script src="portal_js/modernizr.min.js"></script> 
<script src="portal_js/jquery.sparkline.min.js"></script> 
<script src="portal_js/toggles.min.js"></script> 
<script src="portal_js/retina.min.js"></script> 
<script src="portal_js/jquery.cookies.js"></script> 
<script src="portal_js/morris.min.js"></script> 
<script src="portal_js/raphael-2.1.0.min.js"></script> 
<script src="portal_js/jquery.datatables.min.js"></script> 
<script src="portal_js/chosen.jquery.min.js"></script> 
<script src="portal_js/custom.js"></script>
</body>
</html>