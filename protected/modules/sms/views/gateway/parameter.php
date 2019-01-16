<?php
$configuration	= Configurations::model()->findByPk(5);
?>
<?php 
    $first = SmsGateway::model()->find(array('order'=>'id ASC'));
    if($first)
    {
        $parameters= SmsGatewayParameter::model()->findAllByAttributes(array('gateway_id'=>$first->id));
        
    }

?>
  
<?php 
            if($parameters!=NULL)
            {
                foreach ($parameters as $data)
                {
                    
                    ?>
                     <div class="gateway-parameter" id="gateway-parameter-<?php echo $ptrow;?>"  data-row="<?php echo $ptrow;?>">
                        <div class="gatewayParameter inputstyle"> 
                       <table width="100%" class="parameter-block">
                        <tr>
                            
                            <td width="45%" valign="top">
                                <?php echo CHtml::activeTextField($parameter,'name['.$ptrow.']',array('value'=>$data->name,'placeholder'=>Yii::t('app', 'Name'), 'style'=>'')); ?>
                            </td>
                            <td width="45%" valign="top">
                                <?php echo CHtml::activeTextField($parameter,'value['.$ptrow.']',array('value'=>$data->value,'placeholder'=>Yii::t('app', 'Value'), 'style'=>'')); ?>
                            </td> 
                            <td>
                                <a href="javascript:void(0);" title="<?php echo Yii::t("app", "Click to remove particular");?>" class="remove-particular fees-trash"><?php echo Yii::t("app", "");?></a>
                            </td>
                        </tr>
                       </table>
                       </div>
        
</div>
                        <?php
                        $ptrow++;
                }
                $ptrow= count($parameters);
            }
            
            ?> 
        
<?php
if($parameters==NULL)
{
?>
<div class="gateway-parameter" id="gateway-parameter-<?php echo $ptrow;?>"  data-row="<?php echo $ptrow;?>">		                             
        <div class="gatewayParameter">   
            <table width="100%" class="parameter-block">
                <tr>
                    <td width="45%" valign="top">
                        <?php echo CHtml::activeTextField($parameter,'name['.$ptrow.']',array('placeholder'=>Yii::t('app', 'Name'), 'style'=>'')); ?>
                    </td>
                    <td width="45%" valign="top">
                        <?php echo CHtml::activeTextField($parameter,'value['.$ptrow.']',array('placeholder'=>Yii::t('app', 'Value'), 'style'=>'')); ?>
                    </td> 
                    <td>
                        <a href="javascript:void(0);" title="<?php echo Yii::t("app", "Click to remove particular");?>" class="remove-particular fees-trash"><?php echo Yii::t("app", "");?></a>
                    </td>
                </tr>
             
            </table>	
         
        </div>            
</div>
<?php } ?>