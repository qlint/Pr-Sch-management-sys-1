<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo Yii::app()->params['app_name'].' '.Yii::app()->params['version'];?></title>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/styles/reset.css" media="screen" />
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/styles/welcome.css" media="screen" />
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/scripts/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/scripts/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>
<!--[if IE 6]>
<script src="scripts/DD_belatedPNG.js" type="text/javascript"></script>
<![endif]-->

</head>

<body>
<div id="welcome">
  
    <?php echo CHtml::beginForm(); ?>
        <fieldset>
        <h2><?php echo Yii::app()->params['app_name']; ?> Setup</h2>
        <?php
		if  (in_array  ('curl', get_loaded_extensions())) {
	    ?>
		<div>
            <p><?php echo Yii::app()->params['app_name']; ?> Installation Serial No. <br/>
            <input type="text" id="date" name="serial" style="height:30px;font-size:16px;font-weight:bold;color:#060;" size="24" maxlength="24" /><br/></p>
            <br/> <p>Registered E-mail Id:<br/>
            <input type="text" id="date" name="email" style="height:30px;font-size:16px;font-weight:bold;color:#060;" size="24" maxlength="50" /><br/></p>
            <input type="submit" style="border: none; color: #000; margin-top: 10px;letter-spacing:2px;" value="Start" class="button" /><br/><br/>
            <span style="font-size:11px;color:#999;font-style:italic;">Tip : Go to Licence Center > Manage Licences to access your Licence Key</span>
        </div>
	    <?php }else{ ?>
		  Installation Error <br/>
          Please Enable Curl For Continuing <?php echo Yii::app()->params['app_name']; ?> Installation <br/>
          
           Tips: <br/>
          
            1) Navigate to path\to\php\(your version of php)\<br />
            2) edit php.ini<br />
            3) Search for curl, uncomment extension=php_curl.dll<br />
            4) Navigate to path\to\apache\(your version of apache)\bin\<br />
            5) edit php.ini<br />
            6) Search for curl, uncomment extension=php_curl.dll<br />
            7) Save both<br />
            8) Restart Apache<br/>
            
            <input type="button" value="I have Enabled Curl Extension" onClick="window.location.reload()">
<?php } ?>
        </fieldset>
    <?php echo CHtml::endForm(); ?>
</div>

</body>
</html>