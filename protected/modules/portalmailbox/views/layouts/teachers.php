<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
   <title> <?php $college=Configurations::model()->findByPk(1); ?><?php echo $college->config_value ; ?></title>

  <link href="portal_css/style.default.css" rel="stylesheet">
  <link href="portal_css/fullcalendar.css" rel="stylesheet">
  <link href="portal_css/jquery.datatables.css" rel="stylesheet">
  <link rel="icon" type="image/ico" href="<?php echo Yii::app()->request->baseUrl; ?>/uploadedfiles/school_logo/favicon.ico"/>
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
   <script>
        $(document).ready(function(){
			$(".plusbut").click(function(){
				if ($(".addmenu").is(':hidden')){
					$(".addmenu").show();
				}
				else{
					$(".addmenu").hide();
				}
				return false;
			});
			
			$('.addmenu').click(function(e) {
				e.stopPropagation();
			});
			$(document).click(function() {
				$('.addmenu').hide();
			});
        });
        </script>
</head>
<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>

<section>

  <div class="mainpanel">
    
    <div class="headerbar">
      
      <a class="menutoggle"><i class="fa fa-bars"></i></a>
     			<?php 
                    $teacher=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));                   
                    ?>
       <div class="searchform"><span class="col-md-4"> <?php echo Yii::t('app','Welcome ').ucfirst($teacher->last_name.' '.$teacher->first_name).Yii::t('app',' in to your profile.'); ?></span></div>
      
      
   
      <div class="header-right">
        <ul class="headermenu">
        
          
          <li>
            <div class="btn-group">
               <?php echo CHtml::link('<i class="glyphicon glyphicon-envelope"></i>',array('/portalmailbox'),array('class'=>'btn btn-default dropdown-toggle tp-icon')); ?>
              <!--<div class="dropdown-menu dropdown-menu-head pull-right">
                <h5 class="title">You Have 1 New Message</h5>
                <ul class="dropdown-list gen-list">
                  <li class="new">
                    <a href="#">
                    <span class="thumb"><img src="images/photos/user1.png" alt="" /></span>
                    <span class="desc">
                      <span class="name">Draniem Daamul <span class="badge badge-success">new</span></span>
                      <span class="msg">Lorem ipsum dolor sit amet...</span>
                    </span>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                    <span class="thumb"><img src="images/photos/user2.png" alt="" /></span>
                    <span class="desc">
                      <span class="name">Nusja Nawancali</span>
                      <span class="msg">Lorem ipsum dolor sit amet...</span>
                    </span>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                    <span class="thumb"><img src="images/photos/user3.png" alt="" /></span>
                    <span class="desc">
                      <span class="name">Weno Carasbong</span>
                      <span class="msg">Lorem ipsum dolor sit amet...</span>
                    </span>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                    <span class="thumb"><img src="images/photos/user4.png" alt="" /></span>
                    <span class="desc">
                      <span class="name">Zaham Sindilmaca</span>
                      <span class="msg">Lorem ipsum dolor sit amet...</span>
                    </span>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                    <span class="thumb"><img src="images/photos/user5.png" alt="" /></span>
                    <span class="desc">
                      <span class="name">Veno Leongal</span>
                      <span class="msg">Lorem ipsum dolor sit amet...</span>
                    </span>
                    </a>
                  </li>
                  <li class="new"><a href="#">Read All Messages</a></li>
                </ul>
              </div>-->
            </div>
          </li>
          <!--<li>
            <div class="btn-group">
              <button class="btn btn-default dropdown-toggle tp-icon" data-toggle="dropdown">
                <i class="glyphicon glyphicon-globe"></i>
                <span class="badge">5</span>
              </button>
              <div class="dropdown-menu dropdown-menu-head pull-right">
                <h5 class="title">You Have 5 New Notifications</h5>
                <ul class="dropdown-list gen-list">
                  <li class="new">
                    <a href="#">
                    <span class="thumb"><img src="images/photos/user4.png" alt="" /></span>
                    <span class="desc">
                      <span class="name">Zaham Sindilmaca <span class="badge badge-success">new</span></span>
                      <span class="msg">is now following you</span>
                    </span>
                    </a>
                  </li>
                  <li class="new">
                    <a href="#">
                    <span class="thumb"><img src="images/photos/user5.png" alt="" /></span>
                    <span class="desc">
                      <span class="name">Weno Carasbong <span class="badge badge-success">new</span></span>
                      <span class="msg">is now following you</span>
                    </span>
                    </a>
                  </li>
                  <li class="new">
                    <a href="#">
                    <span class="thumb"><img src="images/photos/user3.png" alt="" /></span>
                    <span class="desc">
                      <span class="name">Veno Leongal <span class="badge badge-success">new</span></span>
                      <span class="msg">likes your recent status</span>
                    </span>
                    </a>
                  </li>
                  <li class="new">
                    <a href="#">
                    <span class="thumb"><img src="images/photos/user3.png" alt="" /></span>
                    <span class="desc">
                      <span class="name">Nusja Nawancali <span class="badge badge-success">new</span></span>
                      <span class="msg">downloaded your work</span>
                    </span>
                    </a>
                  </li>
                  <li class="new">
                    <a href="#">
                    <span class="thumb"><img src="images/photos/user3.png" alt="" /></span>
                    <span class="desc">
                      <span class="name">Nusja Nawancali <span class="badge badge-success">new</span></span>
                      <span class="msg">send you 2 messages</span>
                    </span>
                    </a>
                  </li>
                  <li class="new"><a href="#">See All Notifications</a></li>
                </ul>
              </div>
            </div>
          </li>-->
          <li>
            <div class="btn-group">
            	 
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <?php
					 if($teacher->photo_file_name!=NULL)
					 {
						$path = Employees::model()->getProfileImagePath($teacher->id);   
						echo '<img  src="'.$path.'" alt="'.$teacher->photo_file_name.'"  width="40" height="41" />';
					}
					elseif($teacher->gender=='M')
					{
						echo '<img  src="images/portal/p-small-male_img.png" alt='.$teacher->first_name.'   />'; 
					}
					elseif($teacher->gender=='F')
					{
						echo '<img  src="images/portal/p-small-female_img.png" alt='.$teacher->first_name.'   />';
					}
					?>
              
                        <strong><?php echo ucfirst($teacher->last_name.' '.$teacher->first_name);?></strong>
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                <li>
                <?php echo CHtml::link('<i class="glyphicon glyphicon-user"></i> '.Yii::t('app','My Profile'),array('/teachersportal/default/profile'),array('class'=>'profile')); ?>
                </li>
                <li>
                <?php echo CHtml::link('<i class="glyphicon glyphicon-cog"></i> '.Yii::t('app','Account Settings'),array('/user/accountprofile'),array('class'=>'profile')); ?>
                </li>
                <li>
                <?php echo CHtml::link('<i class="glyphicon glyphicon-log-out"></i> '.Yii::t('app','Log Out'), array('/user/logout'));?>
                </li>
              </ul>
            </div>
          </li>
         
        </ul>
      </div><!-- header-right -->
      
    </div><!-- headerbar -->
    
    

  	<?php echo $content;?>
    </div><!-- mainpanel -->
</section>


 
<script src="portal_js/jquery-1.10.2.min.js"></script>
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
