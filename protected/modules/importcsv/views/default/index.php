<?php
/**
* ImportCSV Module
*
* @author Artem Demchenkov <lunoxot@mail.ru>
* @version 0.0.3
*
* module form
*/

$this->breadcrumbs=array(
	Yii::t('app','Import')." CSV",
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">        
        	<?php $this->renderPartial('left_side');?>        
        </td>
        <td valign="top">
            <div class="cont_right ">            
            	<h1><?php echo Yii::t('app', 'Import'); ?> CSV</h1>
                <div class="formCon">
                    <div class="formConInner">
                        <?php $form=$this->beginWidget('CActiveForm', array(
                            'method'=>'GET',
                            'action'=>Yii::app()->createUrl('/importcsv'),
                        )); ?>
                        
                        
<div class="text-fild-bg-block">           
<div class="text-fild-block inputstyle">
<?php echo '<label>'.Yii::t("app", "Select fields from").'</label>';?>
<?php echo CHtml::dropDownList('scope', (isset($_GET['scope']) and $_GET['scope']!="" and $_GET['scope']!=NULL)?$_GET['scope']:"", Yii::app()->getModule('importcsv')->scopes, array('onchange'=>'js:this.form.submit();'));?>
</div>
<div class="text-fild-block inputstyle">
<?php echo '<label>'.Yii::t("app", "Action").'</label>';?>
<?php echo CHtml::dropDownList('action', (isset($_GET['action']) and $_GET['action']!="" and $_GET['action']!=NULL)?$_GET['action']:"", Yii::app()->getModule('importcsv')->actions, array('onchange'=>'js:this.form.submit();'));?>
</div>
</div>
                        
                        <?php $this->endWidget(); ?>
                    </div>
                </div>

                <?php
                if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
                {
                ?>
                <div id="importCsvSteps">
                    <div class="inner_new_table" style="padding:0px; display:none" id="delimiters_log">
                     <strong style="display:none;"><?php echo Yii::t('app', 'File'); ?> :</strong> <span id="importCsvForFile" style="display:none;">&nbsp;</span><div class="os-table tablebx">
                     <div class="tbl-grd"></div>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <th width="30%"><?php echo Yii::t('app', 'Fields Delimiter'); ?></th>
                            <th width="30%"><?php echo Yii::t('app', 'Text Delimiter'); ?></th>
                            <th> <?php echo Yii::t('app', 'Model'); ?></th>
                          </tr>
                          <tr>
                            <td><span id="importCsvForDelimiter">&nbsp;</span></td>
                            <td><span id="importCsvForTextDelimiter">&nbsp;</span></td>
                            <td><span id="importCsvForModel">&nbsp;</span></td>
                          </tr>
                        </table>
                        </div>

                    </div>
               
                    <?php echo CHtml::beginForm('','post',array('enctype'=>'multipart/form-data')); ?>
                    <?php echo CHtml::hiddenField("fileName", ""); ?>
                    <?php echo CHtml::hiddenField("thirdStep", "0"); ?>
                    <?php echo CHtml::hiddenField("table", $table); ?>
                    
                    <div id="importCsvFirstStep">
                        <div id="importCsvFirstStepResult">
                            &nbsp;
                        </div>
                        <?php  echo CHtml::button(Yii::t('app', 'Select CSV File'), array("id"=>"importStep1", "class"=>"formbut")); ?>
                    </div> <!-- END div id="importCsvFirstStep" -->
                    
                    <div id="importCsvSecondStep">
                        <div id="importCsvSecondStepResult">
                            &nbsp;
                        </div> <!-- END div id="importCsvSecondStepResult" -->
                        <div class="formCon">
                            <div class="formConInner">
                                
                                <div class="text-fild-bg-block">           
<div class="text-fild-block inputstyle">
<?php echo '<label>'.Yii::t("app", "Fields Delimiter").'</label>';?><span class="require">*</span>
 <?php echo CHtml::textField("delimiter", $delimiter); ?>
</div>
<div class="text-fild-block inputstyle">
<?php echo '<label>'.Yii::t("app", "Text Delimiter").'</label>';?>
<?php echo CHtml::textField("textDelimiter", $textDelimiter); ?>
</div>
<div class="text-fild-block inputstyle">
<?php echo '<label>'.Yii::t("app", "Model").'</label>';?><span class="require">*</span>
 <?php echo CHtml::dropDownList('model', '', $modelsArray);?>
</div>
</div>

                            </div>
                        </div>
                
                    
                        
                        <?php
                        echo CHtml::ajaxSubmitButton(
                            Yii::t('app', 'Next'), 
                            '', 
                            array(
                                'success' => 'function(response){
                                    $("#importCsvSecondStepResult").html(response);
                                    $("html, body").animate({scrollTop:$("#content").position().top}, "slow")
                                }',
                            ), 
                            array(
                                "class"=>"formbut"
                            )
                        );
                        ?>                        
                    </div> <!-- END div id="importCsvSecondStep" -->
                    
                    <?php echo CHtml::endForm(); ?>
                    
                    <div id="importCsvThirdStep">
                        <?php echo CHtml::beginForm('','post'); ?>
                        <?php echo CHtml::hiddenField("thirdStep", "1"); ?>
                        <?php echo CHtml::hiddenField("thirdDelimiter", ""); ?>
                        <?php echo CHtml::hiddenField("thirdTextDelimiter", ""); ?>
                        <?php echo CHtml::hiddenField("thirdTable", ""); ?>
                        <?php echo CHtml::hiddenField("thirdFile", ""); ?>
                        <?php echo CHtml::hiddenField("perRequest", "10000"); ?>
                        <div id="importCsvThirdStepResult">
                            &nbsp;
                        </div> <!-- END div id="importCsvThirdStepResult" -->
                        <div id="importCsvThirdStepColumnsAndForm">
                            <div id="importCsvThirdStepColumns">&nbsp;</div><br/>
                            <?php	
											
                            echo CHtml::ajaxSubmitButton(
                                Yii::t('app', 'Import'), 
                                '', 
                                array(
									'beforeSend'=>'function(){
										var loader	= $("<div class=\"importCsv-loader\" />"),
											img		= $("<img src=\"'.Yii::app()->baseUrl.'/images/loader.gif'.'\" />");
										loader.html(img);
										$("#importCsvThirdStepResult").html(loader);
										$("html, body").animate({scrollTop:$("#content").position().top}, "slow");
									}',
                                    'success' => 'function(response){										
                                        $("#importCsvThirdStepResult").html(response);
                                        $("html, body").animate({scrollTop:$("#content").position().top}, "slow");																		
                                    }',
                                ), 
                                array(
                                    "class"=>"formbut"
                                )
                            );
                            ?>
                        </div> <!-- END div id="importCsvThirdStepColumnsAndForm" -->
                        <?php echo CHtml::endForm(); ?>
                    </div> <!-- END div id="importCsvThirdStep" -->
                    
                    <br/>
                    <div class="csv_links">
                    <span id="importCsvBread1">&laquo; <?php echo CHtml::link(Yii::t('app', 'Start over'), array("/importcsv"));?></span>
                    <span id="importCsvBread2"> &laquo; <a href="javascript:void(0)" id="importCsvA2"><?php echo Yii::t('app', 'Fields Delimiter').", ".Yii::t('app', 'Text Delimiter')." ".Yii::t('app', 'and')." ".Yii::t('app', 'Model');?></a></span>
                    </div>
                </div> <!-- END div id="importCsvSteps" -->

                
                <?php
                }
                ?>
             <div id="importCsvFirstStep">
            <div class="help comn-tooltip">
          	  <a href="" class="help-link" onclick=" return myFunction()"><i class="fa fa-question-circle" aria-hidden="true"></i><span><?php echo Yii::t('app', 'Help') ?></span></a>
            </div>
            
            <div id="helpchld" style="display:none">	
                
                        <div class="yb_import">
                            <div class="head">
                                <b><h2><?php echo Yii::t('fees','Instructions for importing Students and Teachers into your Application:'); ?></h2></b>
                                 </div>
                                 <br />    
                                    1. <?php echo Yii::t('app','Prepare the CSV file that contains the Student or Teacher information you need to import').'<br />'.'&nbsp;&nbsp;&nbsp;&nbsp;('.Yii::t('app','Please take care of spelling errors and that the correct information is entered into the correct columns.').')<br />';?>
                                       <ul id="sublist">
                                        <li><?php echo Yii::t('app','a. Download or open the appropriate sample CSV file: ');?><?php echo CHtml::link('<span>'.Yii::t('app','Student Import Sample1').'</span>', array('default/download','id'=>'stdcsv')); ?>&nbsp; &nbsp;<?php echo Yii::t('app',',');?> &nbsp;  <?php echo CHtml::link('<span>'.Yii::t('app','Student Import Sample2').'</span>', array('default/scsvdownload','id'=>'std1csv')); ?>&nbsp; &nbsp;<?php echo Yii::t('app','and');?> &nbsp;  <?php echo CHtml::link('<span>'.Yii::t('app','Teacher Import').'</span>', array('default/download','id'=>'empcsv')); ?></li>
                                        <li><?php echo Yii::t('app','b. Now capture the information to be imported as indicated in the CSV file');?></li>
                                        <li><?php echo Yii::t('app','c. Save the file to your computer as a comma delimited CSV file - just follow the prompts as given by Excel.');?></li>
                                     </ul>
                                    2. <?php echo Yii::t('app','Now click on "Select CSV File". Browse to the just saved document (csv file) and select it. Click on "Open"');?><br /><br />
                                    3. <?php echo Yii::t('app','Ensure that the first field - "Fields Delimiter" is set as a ";" and that the correct import mode is selected ("Students Details" or "Teacher Details")');?><br /><br />
                                    4. <?php echo Yii::t('app','Click on "Next"');?><br /><br />
                                    5. <?php echo Yii::t('app','Now match the fields on the right (from the csv file) with the fields on the left (from the database). Do not rush. Ensure no mismatches are made!');?><br /><br />
                                    6. <?php echo Yii::t('app','Click on "Import".');?> <br /><br />              
                            
                            
                        </div><br />
    
                    </div>                
                </div>
                <?php
                $current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
				if(Yii::app()->user->year)
				{
					$year = Yii::app()->user->year;
				}
				else
				{
					$year = $current_academic_yr->config_value;
				}
				$is_create = PreviousYearSettings::model()->findByAttributes(array('id'=>1));
				?>
                <?php
				if($year != $current_academic_yr->config_value and $is_create->settings_value==0)
				{
				?>
                	<div>
						<div class="yellow_bx" style="background-image:none;width:690px;padding-bottom:45px;">
							<div class="y_bx_head" style="width:650px;">
							<?php 
								echo Yii::t('app','You are not viewing the current active year. ');
								echo Yii::t('app','To import a CSV file, enable the Create option in Previous Academic Year Settings.');	
								
							?>
							</div>
							<div class="y_bx_list" style="width:650px;">
								<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
							</div>
						</div>
					</div><br />
                <?php
				}
				?>
			</div>  <!--END div class="cont_right "-->              
        </td>
    </tr>
</table>
<script>
function validate()
{
}	
function myFunction() {	
    if ($("#helpchld").is(":visible")) {
        $("#helpchld").hide();
    } else {
       $("#helpchld").show();
    }
	return false;
}
</script>
