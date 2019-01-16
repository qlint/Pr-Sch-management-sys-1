<style>
#othleft-sidebar ul li{
	position:relative;
}
.count{
	position:absolute;
	top:13px;
	right:19px;
	min-width:40px;
	padding:5px 0px !important;
	background-color:#405875;
	color:#FFF !important;
	text-align:center;
	font-size:12px;
	-webkit-border-radius: 10px;
-moz-border-radius: 10px;
border-radius: 10px;
}
</style>
<div id="othleft-sidebar">
              <h1><?php echo Yii::t('app','Manage Database');?></h1>   
                    <?php
				
			$this->widget('zii.widgets.CMenu',array(
			'encodeLabel'=>false,
			'activateItems'=>true,
			'activeCssClass'=>'list_active',
			'items'=>array(
					array('label'=>''.Yii::t('app','List Backups').'<span>'.Yii::t('app','List All Backups').'</span>', 'url'=>array('default/index') ,'linkOptions'=>array('class'=>'lbook_ico'),'active'=> (Yii::app()->controller->action->id=='index')),  						                
                                        //array('label'=>''.Yii::t('backup','Create Backups').'<span>'.Yii::t('backup','Create New Backup').'</span>',  'url'=>array('default/create'),'linkOptions'=>array('class'=>'sl_ico' )),						   
					array('label'=>''.Yii::t('app','Upload Backup').'<span>'.Yii::t('app','Upload a backup').'</span>',  'url'=>array('default/upload'),'linkOptions'=>array('class'=>'vsd_ico'),'active'=> (Yii::app()->controller->action->id=='upload')),
                                      //  array('label'=>''.Yii::t('app','Clean Database').'<span>'.Yii::t('app','Clean Database Data').'</span>',  'url'=>array('default/clean'),'linkOptions'=>array('class'=>'draft' ,'confirm'=>Yii::t('app','Are you sure you want to procced ? It will lose all data from Database '))),
                                       
                                       
				),
			)); ?>
		
		</div>
        
        <script type="text/javascript">

	$(document).ready(function () {
            //Hide the second level menu
            $('#othleft-sidebar ul li ul').hide();            
            //Show the second level menu if an item inside it active
            $('li.list_active').parent("ul").show();
            
            $('#othleft-sidebar').children('ul').children('li').children('a').click(function () {                    
                
                 if($(this).parent().children('ul').length>0){                  
                    $(this).parent().children('ul').toggle();    
                 }
                 
            });
          
            
        });

    </script>