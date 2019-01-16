<!DOCTYPE html>
<?php $direction = Configurations::model()->direction; ?>
<html lang="en"<?php if($direction == 'rtl'){ ?> dir="rtl" <?php } ?>>
<head>
<script src="<?php echo Yii::app()->request->baseUrl;?>/res_js/jquery-1.7.2.min.js"></script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<title>
<?php $college=Configurations::model()->findByPk(1); ?>
<?php echo $college->config_value ; ?></title>
<title>
<?php $college=Configurations::model()->findByPk(1); ?>
<?php echo $college->config_value ; ?></title>
<link href="res_css/style.default.css" rel="stylesheet">
<?php if($direction == 'rtl'){ ?>
 	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/res_css/style-rtl.css" />
 <?php }else{ ?>    
 	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/res_css/style-ltr.css" />
 <?php } ?>
  <!------------------Custom--theme----End------------------------------------------->
      <!--<link href="res_css/Custom-theme/portal_client.css" rel="stylesheet">-->
      <link href="res_css/Custom-theme/portal_main.css" rel="stylesheet">
  <!------------------Custom--theme-------End---------------------------------------->
<link href="res_css/fullcalendar.css" rel="stylesheet">
<link href="res_css/jquery.datatables.css" rel="stylesheet">
<?php 
 //check fav icon set
  $fav=  Favicon::model()->find();
  if(isset($fav->icon) and $fav->icon!="")
  { 
      ?>
      <link rel="icon" type="image/ico" href="<?php echo Yii::app()->request->baseUrl; ?>/uploadedfiles/school_favicon/<?php echo $fav->icon; ?>"/>
  <?php 
  }
  else
  {
      ?>
          <link rel="icon" type="image/ico" href="<?php echo Yii::app()->request->baseUrl; ?>/uploadedfiles/school_logo/favicon.ico"/>
          <?php
  }
 ?>
<!--<link rel="icon" type="image/ico" href="<?php echo Yii::app()->request->baseUrl; ?>/uploadedfiles/school_logo/favicon.ico"/>-->

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
<?php 
//check theme set 

$header_logo_background=""; $header_bar_background=""; $header_border=""; $header_dropdown_background=""; $header_dropdown_text="";
$header_dropdown_over=""; $header_text_color=""; $page_header_background=""; $page_header_text=""; $left_panel_background=""; $left_panel_text="";
$left_panel_over_background=""; $left_panel_over_text=""; $left_panel_active_background=""; $left_panel_active_text=""; $main_panel_background="";
 $themes= PortalThemes::model()->findByAttributes(array('user_id'=> Yii::app()->user->id));
    if($themes)
    {
      $header_logo_background=$themes->header_logo_background;
      $header_bar_background=$themes->header_bar_background;
      $header_border=$themes->header_border;
      $header_dropdown_background=$themes->header_dropdown_background;
      $header_dropdown_text= $themes->header_dropdown_text;
      $header_dropdown_over=$themes->header_dropdown_over;
      $header_text_color= $themes->header_text_color;
      $page_header_background= $themes->page_header_background;
      $page_header_text= $themes->page_header_text;
      $left_panel_background= $themes->left_panel_background;
      $left_panel_text= $themes->left_panel_text;
      $left_panel_over_background= $themes->left_panel_over_background;
      $left_panel_over_text= $themes->left_panel_over_text;
      $left_panel_active_background=$themes->left_panel_active_background;
      $left_panel_active_text= $themes->left_panel_active_text;
      $main_panel_background= $themes->main_panel_background;
      
    }
    
    ?>


</head>
<body style="background-color: <?php echo $left_panel_background; ?>; ">

<?php
	$lan		= 'en_us';
	//language configured by user
	$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	if($settings!=NULL){
		$lan	= $settings->language;
	}
	else{
		//language configured by admin
		$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
		if($settings!=NULL){
			$lan	= $settings->language;
		}
	}
	Yii::app()->translate->setLanguage($lan);
?>

<!-- Preloader -->
<div id="preloader">
  <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>
<section>
  <div class="mainpanel">
    <div class="headerbar" style="color: <?php echo $header_text_color; ?>; background-color: <?php echo $header_bar_background; ?>; border-left-color:<?php echo $header_border; ?> "> <a class="menutoggle" style="border-right-color: <?php echo $header_border; ?>"><i class="fa fa-bars"></i></a>
      <?php 
                        //$user=User::model()->findByAttributes(array('id'=>Yii::app()->user->id));
                        $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
                        //$guard=Guardians::model()->findByAttributes(array('ward_id'=>$student->id));
                        ?>
      <div class="searchform"><span class="col-md-4 profl_nme"><?php echo Yii::t('app','Welcome'); ?> <label class="name_bld"><?php echo $student->studentFullName("forStudentPortal"); ?></label> <?php echo Yii::t('app','in to your profile.'); ?></span></div>
      <div class="header-right">
        <ul class="headermenu">
          <li>
            <div class="btn-group"> <?php echo CHtml::link('<i class="glyphicon glyphicon-envelope"></i><span class="badge">'.Mailbox::model()->newMsgs(Yii::app()->user->id).'</span>',array('/portalmailbox'),array('class'=>'btn btn-default dropdown-toggle tp-icon','style'=>"background-color:$header_bar_background")); ?> </div>
          </li>
          <li>
            <div class="btn-group" >
              <?php 
                        //$user=User::model()->findByAttributes(array('id'=>Yii::app()->user->id));
                        $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
                        //$guard=Guardians::model()->findByAttributes(array('ward_id'=>$student->id));
                        ?>
              <button style="background-color: <?php echo $header_bar_background; ?> !important" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
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
              <strong ><?php echo $student->studentFullName("forStudentPortal"); ?></strong> <span class="caret"></span> </button>
              <ul class="dropdown-menu dropdown-menu-usermenu pull-right" style="background-color: <?php echo $header_dropdown_background; ?>; ">
                <li> <?php echo CHtml::link('<i class="glyphicon glyphicon-user"></i> '.Yii::t('app','My Account'),array('/studentportal/default/profile'),array('class'=>'profile')); ?> </li>
                <li> <?php echo CHtml::link('<i class="glyphicon glyphicon-cog"></i> '.Yii::t('app','Settings'),array('/user/accountProfile'),array('class'=>'profile')); ?> </li>
                <?php $link=Configurations::model()->findByPk(37); ?>
                 <li><a href="<?php echo $link->config_value;?>" target="_blank"><i class="fa fa-headphones"></i> <?php echo Yii::t('app','Help');?></a></li>
                
                <li> <?php echo CHtml::link('<i class="glyphicon glyphicon-log-out"></i> '.Yii::t('app','Logout'), array('/user/logout'));?> </li>
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
<!--modal box-->
<div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade in">
    <div class="modal-dialog modal-sm">
        <div class="modal-content form-bg">
            <div class="modal-header admin-portal-popup model_popup_hd">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><span class="fa fa-times"></span></button>
                <h4 id="myModalLabel" class="modal-title"></h4>
                <p id="myModelDescription"></p>
            </div>
            <div class="modal-body"></div>
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div>
<!--modal box end--> 


<script src="res_js/jquery-migrate-1.2.1.min.js"></script> 
<script src="res_js/bootstrap.min.js"></script> 
<script src="res_js/modernizr.min.js"></script> 
<script src="res_js/jquery.sparkline.min.js"></script> 
<script src="res_js/toggles.min.js"></script> 
<script src="res_js/retina.min.js"></script> 
<script src="res_js/jquery.cookies.js"></script> 
<script src="res_js/morris.min.js"></script> 
<script src="res_js/raphael-2.1.0.min.js"></script> 
<script src="res_js/jquery.datatables.min.js"></script> 
<script src="res_js/chosen.jquery.min.js"></script> 
<script src="res_js/custom.js"></script>
<script>
function open_popup_links(){
	$('.open_popup').unbind('click');
	$('.open_popup').on('click', function(e) {
		$('#myModal .modal-body, #myModalLabel').html('Loading...');
		var url			= $(this).attr('data-ajax-url'),
			label		= $(this).attr('data-modal-label') || $(this).text();
			description	= $(this).attr('data-modal-description') || $(this).text();
			mainClass	= $(this).attr('data-modal-class');
		if(typeof mainClass !== typeof undefined && mainClass !== false) {
			$('.modal-dialog').removeClass('modal-sm');
			$('.modal-header').removeClass('admin-portal-popup');
			$('.modal-dialog').addClass('modal-md');
			$('.modal-header').addClass('popupheader');
		}
		
		$('#myModalLabel').text(label);
		$('#myModelDescription').text(description);
		$.ajax({
			url:url,
			success: function(response){
				$('#myModal .modal-body').html(response);
			}
		});
	});
}

$(document).ready(function(){
	open_popup_links();
});
</script>
</body>
</html>
<style>
                .headermenu .dropdown-menu li a 
                {
                    color:<?php echo $header_dropdown_text; ?>;
                }
                .headermenu .dropdown-menu li a:hover
                {
                    background-color:<?php echo $header_dropdown_over; ?>;
                }
                .mainpanel
                {
                    background-color:<?php echo $main_panel_background; ?>;
                }
                .nav-bracket  li  a
                {
                    color: <?php echo $left_panel_text; ?>;
                }
                .nav-bracket  li  a:hover
                {
                    color: <?php echo $left_panel_over_text; ?>;
                    background-color:<?php echo $left_panel_over_background; ?>;
                }
                .nav-bracket  li.active a
                {
                    color: <?php echo $left_panel_active_text; ?>;
                    background-color:<?php echo $left_panel_active_background; ?>;
                }
                .headermenu li
                {
                    border-color: <?php echo $header_border; ?>;
                }
                .pageheader
                {
                    background-color: <?php echo $page_header_background; ?>;  
                    border-top-color: <?php echo $header_border; ?>;
                }
                .pageheader h2, .pageheader h2 span
                {
                    color: <?php echo $page_header_text; ?>;  
                }
                .pageheader h2 i.fa
                {
                    border-color: <?php echo $page_header_text; ?>;
                }
                .pageheader .breadcrumb-wrapper span.label, .pageheader .breadcrumb li.active
                {
                    color: <?php echo $page_header_text; ?>;  
                }
            </style>