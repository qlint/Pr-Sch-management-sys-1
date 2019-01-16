<?php
$this->breadcrumbs=array(
	Yii::t('app', 'Notify')=>array('/notifications/default/sendmails'),
	Yii::t('app', 'Gateway')
);

?>
<style>
.listbx_subhdng
{
    width: 25%;
}
    </style>
<?php 
    $name="";
    $url="";
    $method="";
    $response="";
    $status= "Add";
    $first = SmsGateway::model()->find(array('order'=>'id ASC'));
    if($first)
    {
        $status= "Update";
        $name= $first->name;
        $url= $first->url;
        if($first->method==1)
        {
            $method= "GET";
        }
        else if($first->method==2)
        {
            $method= "POST";
        }
        $response= $first->responds_format;
        $parameters= SmsGatewayParameter::model()->findAllByAttributes(array('gateway_id'=>$first->id));
        
    }

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top" id="port-left">
    
     <?php $this->renderPartial('/default/left_side');?>
    
    </td>
    <td valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="75%">
        <div class="cont_right formWrapper">

            <h1><?php echo Yii::t('app','SMS Gateway Settings');?></h1>
            
                            
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php echo CHtml::link('<span>'.Yii::t('app',$status).'</span>', array('create'),array('class'=>'a_tag-btn')); ?></li>                                   
</ul>
</div> 
</div>      
                            
            <div class="emp_right_contner">
                <div class="emp_tabwrapper">
                    <div class="emp_cntntbx">
                        <div class="table_listbx">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr class="listbxtop_hdng">
                                <td><?php echo Yii::t('app','General');?></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="listbx_subhdng"><?php echo Yii::t('app','Name');?></td>                                
                                <td>:</td>
                                <td class="subhdng_nrmal"><?php echo $name; ?></td>
                              </tr>
                              <tr>
                                <td class="listbx_subhdng"><?php echo Yii::t('app','URL');?></td>
                                <td>:</td>
                                <td class="subhdng_nrmal"><?php echo $url; ?></td>
                              </tr>
                              <tr>
                                <td class="listbx_subhdng"><?php echo Yii::t('app','HTTP Method');?></td>
                                <td>:</td>
                                <td class="subhdng_nrmal"><?php echo $method; ?></td>
                                
                              </tr>
                              <tr>
                                <td class="listbx_subhdng"><?php echo Yii::t('app','Response Format');?></td>
                                <td>:</td>
                                <td class="subhdng_nrmal"><?php echo $response; ?></td>
                                
                              </tr>
                              <tr class="listbxtop_hdng">
                                <td><?php echo Yii::t('app','Request Parameters');?></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <?php 
                              if($parameters!=NULL)
                              {
                                  foreach ($parameters as $data)
                                  {
                                      ?>
                                        <tr>
                                            <td class="listbx_subhdng"><?php echo $data->name;?></td>
                                            <td>:</td>
                                            <td class="subhdng_nrmal"><?php echo $data->value; ?></td>

                                          </tr>  
                                          <?php
                                  }
                              }
                              
                              ?>
                              
                            </table>
                        </div>
                </div>
            </div>

        </div>
        </div>
      </tr>
    </table>
   
    </td>
  </tr>
</table>
    