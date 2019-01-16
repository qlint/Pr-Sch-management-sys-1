<?php
/**
 * ImportCSV Module
 *
 * @author Artem Demchenkov <lunoxot@mail.ru>
 * @version 0.0.3
 *
 *
 *
 *
 * Result of choosing table and delimiter
 */

if($error==1) {
    
    // first error: Delimiter can not be empty
    
    echo("<span class='importCsvError'>".Yii::t('app', 'Error').": ".Yii::t('app', 'Fields Delimiter can not be empty')."</span>");
}
elseif($error==0){
	
    //making options width csv columns for $csvKey
	
    $lengthCsv       = sizeof($fromCsv);
    $optionsContent4 = '<option value=\"\"></option>';

    for($i=0; $i<$lengthCsv; $i++) {
        $valOpt = $i+1;
        $selected4 = ($paramsArray['csvKey']==$valOpt) ? 'selected=\"selected\"' : '';
        $optionsContent4 = $optionsContent4.'<option value=\"'.$valOpt.'\" '.$selected4.'>'.trim($fromCsv[$i]).'</option>';
    }
    $optionsContent4 = trim($optionsContent4);


    //making options width table rows for $tableKey
    
    $length = sizeof($tableColumns);
    $optionsContent2 = '<option value=\"\"></option>';
    $optionsContent3 = '<option value=\"\"></option>';
    for($i=0; $i<$length ; $i++) {
        $valOpt2 = $i+1;

        $selected3 = ($paramsArray['tableKey']==trim($tableColumns[$i])) ? 'selected=\"selected\"' : '';

        $optionsContent2 = $optionsContent2.'<option value=\"'.$valOpt2.'\">'.trim($tableColumns[$i]).'</option>';
        $optionsContent3 = $optionsContent3.'<option value=\"'.trim($tableColumns[$i]).'\" '.$selected3.'>'.trim($tableColumns[$i]).'</option>';
    }

    $optionsContent2 = trim($optionsContent2);
    $optionsContent3 = trim($optionsContent3);

    /*
     * making table width columns for third step
     */

    $selected1   = ($paramsArray['mode']==1) ? 'selected=\"selected\"' : '';
    $selected2   = ($paramsArray['mode']==2) ? 'selected=\"selected\"' : '';
    $selected3   = ($paramsArray['mode']==3) ? 'selected=\"selected\"' : '';

    $thirdContent = '<input type=\"hidden\" name=\"Tablekey\" value=\"\"/><input type=\"hidden\" name=\"academic_yr_id\" value=\"'.$academicYrId.'\"/><input type=\"hidden\" name=\"CSVkey\" value=\"\"/><input type=\"hidden\" name=\"hmodel\" id=\"hmodel\" value=\"'.$selectedmodel.'\"/><input type=\"hidden\" name=\"perRequest\" id=\"perRequest\" value=\"'.$paramsArray['perRequest'].'\"/><div class=\"formCon\"><div class=\"formConInner\"><table  cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr><th align=\"left\">'.Yii::t('app', 'Table column').'</th><th align=\"left\">'.Yii::t('app', 'CSV column').'</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th></tr>';
	
	//model title
	$thirdContent .= '<tr><td colspan=\"2\"><h3 style=\"font-size:14px; color:#777;\">'.Yii::app()->controller->module->models[$selectedmodel]['label'].'</h3></td></tr>';
	//model description
	if(isset(Yii::app()->controller->module->models[$selectedmodel]['description'])){
		$thirdContent .= '<tr><td colspan=\"2\"><span style=\"font-size:10px; color:#777;\">'.Yii::app()->controller->module->models[$selectedmodel]['description'].'</span></td></tr>';
	}
	
	$compare_entry	=	(isset(Yii::app()->controller->module->models[$selectedmodel]['compare']))?Yii::app()->controller->module->models[$selectedmodel]['compare']:array();
	
	//auto select
	$autoselect			= -1;
	$table_field_count	= 0;
    for($i=0; $i<$length; $i++) {
		if(array_key_exists($tableColumns[$i], $compare_entry)){
			$field		= $tableColumns[$i];
			$entry 		= $compare_entry[$tableColumns[$i]];
			$extmodel	= $entry['model'];
			
			foreach($entry['allowedColumns'] as $key=>$column){
				$autoselect++;
				$optionsContent  = '<option value=\"\"></option>';
				for($n=0; $n<$lengthCsv; $n++) {
					$valOpt 	= $n+1;
					$selected	= '';
					if($autoselect==$n){
						$selected = 'selected=\"selected\"';
					}
					$optionsContent  = $optionsContent.'<option value=\"'.$valOpt.'\" '.$selected.'>'.trim($fromCsv[$n]).'</option>';
				}
				$optionsContent  = trim($optionsContent);
				
				$reqfield	=	'';
				if($entry['requiredColumns']=='all' or (is_array($entry['requiredColumns']) and in_array($column, $entry['requiredColumns']))){
					$reqfield	=	'<span style=\"color:#F00; font-weight:600; font-size:15px\"> *</span>';
				}
		
				$thirdContent = $thirdContent.'<tr><td height=\"50\">'.$selectedmodel::model()->getAttributeLabel($field).$reqfield.'</td><td><select name=\"CompColumns['.$field.']['.$column.']\" id=\"select_cmp_'.$key.'\">'.$optionsContent.'</select></td></tr>';
			}
		}
		else{
			$autoselect++;
			$optionsContent  = '<option value=\"\"></option>';
			for($n=0; $n<$lengthCsv; $n++) {
				$valOpt = $n+1;
				$selected = (isset($paramsArray['columns'][$tableColumns[$i]]) && $paramsArray['columns'][$tableColumns[$i]]==$valOpt) ? 'selected=\"selected\"' : '';
				if($selected=="" and $autoselect==$n){
					$selected = 'selected=\"selected\"';
				}
				$optionsContent  = $optionsContent.'<option value=\"'.$valOpt.'\" '.$selected.'>'.trim($fromCsv[$n]).'</option>';
			}
			$optionsContent  = trim($optionsContent);
			
			$reqfield	=	'';
			if($requiredColumns=='all' or (is_array($requiredColumns) and in_array($tableColumns[$i], $requiredColumns))){
				$reqfield	=	'<span style=\"color:#F00; font-weight:600; font-size:15px\"> *</span>';
			}
	
			$thirdContent = $thirdContent.'<tr><td height=\"50\">'.$selectedmodel::model()->getAttributeLabel($tableColumns[$i]).$reqfield.'</td><td><select name=\"Columns['.$table_field_count.']\" id=\"select_'.$i.'\">'.$optionsContent.'</select></td></tr>';	
			
			$table_field_count++;
		}
    }
	
	//dynamic fields for students
	
	//for external entries
	if(isset(Yii::app()->controller->module->models[$selectedmodel]['external'])){
		$external_entry	=	Yii::app()->controller->module->models[$selectedmodel]['external'];
		foreach($external_entry as $field=>$entry){			
			$extmodel	=	$entry['model'];
			
			//model title
			$thirdContent .= '<tr><td colspan=\"2\"><h3 style=\"font-size:14px; color:#777;\">'.$entry['label'].'</h3></td></tr>';
			
			//model description
			if(isset($entry['description'])){
				$thirdContent .= '<tr><td colspan=\"2\"><span style=\"font-size:10px; color:#777;\">'.$entry['description'].'</span></td></tr>';
			}
			
			foreach($entry['allowedColumns'] as $key=>$column){
				$autoselect++;
				$optionsContent  = '<option value=\"\"></option>';
				for($n=0; $n<$lengthCsv; $n++) {
					$valOpt 	= $n+1;
					$selected	= '';
					if($autoselect==$n){
						$selected = 'selected=\"selected\"';
					}
					$optionsContent  = $optionsContent.'<option value=\"'.$valOpt.'\" '.$selected.'>'.trim($fromCsv[$n]).'</option>';
				}
				$optionsContent  = trim($optionsContent);
				
				$reqfield	=	'';
				if($entry['requiredColumns']=='all' or (is_array($entry['requiredColumns']) and in_array($column, $entry['requiredColumns']))){
					$reqfield	=	'<span style=\"color:#F00; font-weight:600; font-size:15px\"> *</span>';
				}
				$thirdContent = $thirdContent.'<tr><td height=\"50\">'.addslashes($extmodel::model()->getAttributeLabel($column)).$reqfield.'</td><td><select name=\"ExtColumns['.$field.']['.$column.']\" id=\"select_ext_'.$key.'\">'.$optionsContent.'</select></td></tr>';
			}
			
			// for external compare entries
			if(isset($entry['compare']))
			{
				$ext_compare_entry = $entry['compare'];
				foreach($ext_compare_entry as $extfield=>$extentry){	
					$extmodel	=	$extentry['model'];					
					foreach($extentry['allowedColumns'] as $key=>$column){	
						$autoselect++;
						$optionsContent  = '<option value=\"\"></option>';
						for($n=0; $n<$lengthCsv; $n++) {
							$valOpt 	= $n+1;
							$selected	= '';
							if($autoselect==$n){
								$selected = 'selected=\"selected\"';
							}
							$optionsContent  = $optionsContent.'<option value=\"'.$valOpt.'\" '.$selected.'>'.trim($fromCsv[$n]).'</option>';
						}
						$optionsContent  = trim($optionsContent);
						
						$reqfield	=	'';
						if($extentry['requiredColumns']=='all' or (is_array($entry['requiredColumns']) and in_array($column, $extentry['requiredColumns']))){
							$reqfield	=	'<span style=\"color:#F00; font-weight:600; font-size:15px\"> *</span>';
						}
				
						$thirdContent = $thirdContent.'<tr><td height=\"50\">'.$extmodel::model()->getAttributeLabel($column).$reqfield.'</td><td><select name=\"ExtCompColumns['.$field.']['.$extfield.']['.$column.']\" id=\"select_ext_cmp_'.$key.'\">'.$optionsContent.'</select></td></tr>';
					}					
				}
			}
		}
	}	
    
    $thirdContent = $thirdContent.'</table></div></div>';
    $thirdContent = trim($thirdContent);

    // Going to third step
    
    ?>
    <script type="text/javascript">
        toThirdStep("<?php echo ($thirdContent);?>", "<?php echo addslashes($delimiter);?>", "<?php echo($selectedmodel);?>", "<?php echo($table);?>", "<?php echo addslashes($textDelimiter);?>");
    </script>
    <?php
}
?>
