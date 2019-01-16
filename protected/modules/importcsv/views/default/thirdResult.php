<?php
/**
 * ImportCSV Module
 *
 * @author Artem Demchenkov <lunoxot@mail.ru>
 * @version 0.0.3
 *
 * Import result
 */

if($error==1) {

    // first error: No one column is selected

    echo("<span class='importCsvError'>".Yii::t('app', 'Error').": ".Yii::t('app', 'Select all mandatory fields')."</span>");
}
elseif($error==2) {

    // second error: Items per one request must not be empty
    
    echo("<span class='importCsvError'>".Yii::t('app', 'Error').": '".Yii::t('app', 'Items per one request')."' - ".Yii::t('app', 'must not be empty')."</span>");
}
elseif($error==3) {

    // third error: Items per one request must be a number
    
    echo("<span class='importCsvError'>".Yii::t('app', 'Error').": '".Yii::t('app', 'Items per one request')."' - ".Yii::t('app', 'must be a number')."</span>");
}
elseif($error==4) {

    // fourth error: Keys for compare must be selected (only for second and third modes)
    
    echo("<span class='importCsvError'>".Yii::t('app', 'Error').": ".Yii::t('app', 'For this mode')." '".Yii::t('app', 'Keys for compare')."' - ".Yii::t('app', 'must be selected')."</span>");
}
elseif($error==0) {

    // No errors. The End

    if(empty($error_array)) {
        $strings = Yii::t('app', 'No');
    }
    else {
        $errorsLength = sizeof($error_array);
        for($k=0; $k<$errorsLength; $k++) {
           $strings = ($k == 0) ? $errorsLength[$k] : ", ".$errorsLength[$k];
        }
    }
	
    
    //echo "<span class='importCsvNoError'>".Yii::t('app', 'Import was carried out')."<br/><br/>".Yii::t('app', 'Errors in rows').": ".$strings."</span>";
	
	echo "<span class='importCsvNoError'>".Yii::t('app', 'Import was carried out , '.$total_rows_inserted.' of '.$total_rows.' rows inserted')."</span>";
	
	if(count($csv_missing_rows) > 0 and Yii::app()->user->hasState("csv_missing_rows")){
		echo '<div class="red_txt_error">';
		echo Yii::t('app','We can\'t insert some rows. Please ').CHtml::link(Yii::t('app','download'), array('/importcsv/default/download_missing_datas')).Yii::t('app',' it and retry again after editing.');
		echo '</div>';
	}
	
	if(count($exceptions) > 0){
		echo "<span class='importCsvError'>".implode('<br/><br/>', $exceptions)."</span>";
	}
	
	if(count($warnings) > 0){
		echo "<span class='importCsvWarning'>".implode('<br/><br/>', $warnings)."</span>";
	}

    ?>
    <script type="text/javascript">
        toEnd();
    </script>
    <?php
}

?>
<script>
<?php
if($total_rows_inserted !=0)
{
	$url_new	=	Yii::app()->createUrl("/importcsv/users/student");		
	?>
	setTimeout( function(){ 
		window.open("<?php echo $url_new?>", "_blank");	
	}  , 3000 );
	<?php
}
?>
</script>
