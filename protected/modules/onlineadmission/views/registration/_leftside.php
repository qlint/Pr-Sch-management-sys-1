<style type="text/css">
.online-back {
	text-align:center;
}
.online-back a:hover{
	text-decoration:none;
	color:#36363;
	transition: 0.1s ease-in all;
}
</style>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

<?php 
	$logo 			= Logo::model()->findAll();
	$configuration	= Configurations::model()->findByAttributes(array('id'=>1));
	if($configuration){
		$school_name	= ucfirst($configuration->config_value);
	}
	else{
		$school_name	= Yii::app()->params['app_name'];
	}
?>
<div class="se-info">
    <p><?php echo Yii::t('app','Please like our'); ?> <strong><?php echo $school_name; ?></strong> <?php echo Yii::t('app','Facebook page!'); ?></p>  
    <div class="row" style="padding-bottom:25px; border-bottom:1px #CCCCCC dashed;">
<?php /*?>		<div class="col-md-2" style="padding:12px 0px 0px 0px; ">
        	<?php
				if($logo!=NULL){
					echo '<img src="'.Yii::app()->request->baseUrl.'/uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" width="52" height="52" />';
				}
			?>
        	<img src="<?php echo Yii::app()->request->baseUrl; ?>/uploadedfiles/school_logo/64x64.png" width="52" height="52" />
        </div>	<?php */?>	
        <div class="col-md-12">
            <h4 class="text-success" style="color:#333"><strong><?php echo $school_name; ?></strong></h4>
            <div class="fb-like" data-href="https://www.facebook.com/openschoolv2?ref=br_tf" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>
        </div>
    </div>    
    <br />
    <h5 class="subtitle"><?php echo $school_name; ?></h5>
    <p><?php echo Yii::t('app','Ultimately our aim at'.' '.$school_name.' '.'is to produce successful leaders with competency and exellence. The School provides quality education to its students with the help of knowledgeable, competent and experienced teachers.'); ?></p>   
</div>
<div class="online-back">
	<?php echo CHtml::link(Yii::t('app','Back To Login'), array('/onlineadmission/registration'), array('confirm'=>Yii::t('app','Are you sure?'))); ?>
</div>