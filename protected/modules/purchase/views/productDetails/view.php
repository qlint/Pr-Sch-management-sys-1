
<?php
$this->breadcrumbs=array(
	Yii::t('app','Vendors')=>array('/purchase'),
	Yii::t('app','View'),
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top"><?php $this->renderPartial('/default/leftside');?></td>
    <td valign="top"><div class="cont_right formWrapper">
        <h1>
          <?php 
						echo Yii::t('app','Vendor Details');
	$vendor_detail = PurchaseVendors::model()->findByAttributes(array('id'=>$_REQUEST['id']));					
					?>
        </h1>
        <div class="button-bg">
          <div class="top-hed-btn-left"> </div>
          <div class="top-hed-btn-right">
            <ul>
              <li> <?php echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('/purchase/vendorDetails/update', 'id'=>$vendor_detail->id),array('class'=>'a_tag-btn')); ?> </li>
              <li> <?php echo CHtml::link('<span>'.Yii::t('app','Vendor List').'</span>', array('/purchase'),array('class'=>'a_tag-btn'));?> </li>
            </ul>
          </div>
        </div>
        <div class="clear"></div>
        <div class="emp_right_contner">
          <div class="emp_tabwrapper">
            <div class="clear"></div>
            <div class="emp_cntntbx" >
              <div class="table_listbx">
                <div class="listbxtop_hdng"><?php echo Yii::t('app','Vendor Details');?></div>
                <?php ?>
                <div class="prof-view-col">
                  <ul>
                    <li class="l-col"><?php echo Yii::t('app','First Name');?></li>
                    <li class="r-col"><?php echo $vendor_detail->first_name;?></li>
                    <li class="l-col"><?php echo Yii::t('app','Last Name');?></li>
                    <li class="r-col"><?php echo $vendor_detail->last_name;?></li>
                  </ul>
                  <ul>
                    <li class="l-col"><?php echo Yii::t('app','Address 1');?></li>
                    <li class="r-col"><?php echo $vendor_detail->address_1;?></li>
                    <li class="l-col"><?php echo Yii::t('app','Address 2');?></li>
                    <li class="r-col"><?php echo $vendor_detail->address_2;?></li>
                  </ul>
                  <ul>
                    <li class="l-col"><?php echo Yii::t('app','City');?></li>
                    <li class="r-col"><?php echo $vendor_detail->city;?></li>
                    <li class="l-col"><?php echo Yii::t('app','State');?></li>
                    <li class="r-col"><?php echo $vendor_detail->state;?></li>
                  </ul>
                  <ul>
                    <li class="l-col"><?php echo Yii::t('app','Country');?></li>
                    <li class="r-col">
                      <?php if($vendor_detail->country_id){
																$count = Countries::model()->findByAttributes(array('id'=>$vendor_detail->country_id));
																if(count($count)!=0)
																echo $count->name;
																}
																else
																{
																	echo '-';
																}?>
                    </li>
                    <li class="l-col"><?php echo Yii::t('app','Phone');?></li>
                    <li class="r-col"><?php echo $vendor_detail->phone;?></li>
                  </ul>
                  <ul>
                    <li class="l-col"><?php echo Yii::t('app','Office Phone');?></li>
                    <li class="r-col"><?php echo $vendor_detail->office_phone;?></li>
                    <li class="l-col"><?php echo Yii::t('app','Currency');?></li>
                    <li class="r-col"><?php echo $vendor_detail->currency;?></li>
                  </ul>
                  <ul>
                    <li class="l-col"><?php echo Yii::t('app','Company Name');?></li>
                    <li class="r-col"><?php echo $vendor_detail->company_name;?></li>
                    <li class="l-col"><?php echo Yii::t('app','VAT Number');?></li>
                    <li class="r-col"><?php echo $vendor_detail->vat_number;?></li>
                  </ul>
                  <ul>
                    <li class="l-col"><?php echo Yii::t('app','CST Number');?></li>
                    <li class="r-col"><?php echo $vendor_detail->cst_number;?></li>
                    <li class="l-col"></li>
                    <li class="r-col"></li>
                  </ul>
                  <ul>
                    <li class="l-col"><?php echo Yii::t('app','Email');?></li>
                    <li class="r-col"><?php echo $vendor_detail->email;?></li>
                    <li class="l-col"></li>
                    <li class="r-col"></li>
                  </ul>
                  <div class="clear"></div>
                </div>
                <?php $product_details = PurchaseProducts::model()->findAllByAttributes(array('vendor_id'=>$_REQUEST['id'])); ?>
                <div class="listbxtop_hdng"><?php echo Yii::t('app','Product Details');?></div>
                <div class="prof-view-col">
                  <?php foreach($product_details as $product_detail)
								{
									?>
                  <ul>
                    <li class="l-col"><?php echo Yii::t('app','Item Name');?></li>
                    <li class="r-col">
                      <?php
										$item = PurchaseItems::model()->findByAttributes(array('id'=>$product_detail->item_id));
										 		echo $item->name;?>
                    </li>
                    <li class="l-col"><?php echo Yii::t('app','Description');?></li>
                    <li class="r-col"><?php echo $product_detail->description;?></li>
                  </ul>
                  <ul>
                    <li class="l-col"><?php echo Yii::t('app','Price');?></li>
                    <li class="r-col"><?php echo $product_detail->price;?></li>
                    <li class="l-col"></li>
                    <li class="r-col"></li>
                  </ul>
                  <?php } ?>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div></td>
  </tr>
</table>
