<?php 
$this->pageTitle = Yii::app()->name.' - System Checks';
$next = true;
?>
<?php echo CHtml::beginForm(array('default/step2')); ?>
<h1>System checks</h1>
<div class="form">
    <h3></h3>
    <div class="content">
    <fieldset>
    <div class="emphasize">Check if <?php echo Yii::app()->params['app_name'].' '.Yii::app()->params['version']; ?> is compatible with your environment</div>
    <ol>
       <li>Web server ... <span style="color:green">OK</span></li>
       <li>
            <?php 
                if (defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION >= 5){
                    $phpversion_check = '<span style="color:green">OK</span>';
                }else{
                    $phpversion_check = '<span style="color:red">Fail</span>';
                }
            ?>
            PHP 5 and required modules ... <?php echo $phpversion_check ?>
        </li>
       <?php if (Yii::getVersion() == '1.1.8') {
           $yiiVersion = '<span style="color:green">OK</span>';
       } else {
           $next = false;
           $yiiVersion = '<span style="color:red">Fail</span>';
       }?>
       <li>Yii 1.1.8 or above ... <?php echo $yiiVersion;?></li>
       
    </ol>
    <br/>
    <div class="note">
        <div class="emphasize">The following folders must be writable:</div>
        <ul>
            <?php
            foreach ($folders as $folder => $writable):
                if ($writable === false) $next = false;
            ?>
                <li><?php echo realpath($folder);?> - <?php echo $writable===false ? 'FAIL' : '<span style="color:green">OK</span>';?></li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php if ($next === false) : ?>
    <div class="note">Make sure all folders listed above exist and are writable before start.</div>
    <?php endif; ?>
    </fieldset>
</div>
</div>
<h1 style="margin-top: 15px;">License Agreement</h1>
<div class="form">
    <h3></h3>
    <div class="content">
        <fieldset>
            <div style="height: 100px; width: 524px; overflow: scroll; overflow-x:hidden; border: 1px solid gray; padding: 5px; font-size: 1.2em; margin: 0 auto;">
            <?php
            $copyright = Yii::getPathOfAlias('webroot').'/copyright.txt';
            if (file_exists($copyright)):?>
                <?php echo '<pre>'.file_get_contents($copyright).'</pre>';?>
            <?php endif;?>
            </div>
        <?php if ($next === true) : ?>
        <div class="output" style="padding-left: 0; text-align: center;">        
            <?php echo CHtml::submitButton('Next', array('class'=>'button-2'));?>
        </div>
        <div class="note" style="text-align: center;">By clicking Next, you agree to the terms stated in the <?php echo Yii::app()->params['app_name']; ?> License Agreement above.</div>
        <?php endif; ?>
        </fieldset>
    </div>
</div>
<?php CHtml::endForm(); ?>