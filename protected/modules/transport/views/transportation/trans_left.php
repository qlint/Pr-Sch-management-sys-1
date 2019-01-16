<div id="othleft-sidebar">
             <!--<div class="lsearch_bar">
             	<input type="text" value="Search" class="lsearch_bar_left" name="">
                <input type="button" class="sbut" name="">
                <div class="clear"></div>
  </div>-->       
                    <?php 
			
			function t($message, $category = 'cms', $params = array(), $source = null, $language = null) 
			{
				return $message;
			}

			$this->widget('zii.widgets.CMenu',array(
			'encodeLabel'=>false,
			'activateItems'=>true,
			'activeCssClass'=>'list_active',
			'items'=>array(
			array('label'=>''.'<h1>'.Yii::t('app','Mange Route').'</h1>'),  
					array('label'=>''.Yii::t('app','List All Routes').'<span>'.Yii::t('app','All Route Details').'</span>', 'url'=>array('/transport/RouteDetails/manage') ,'linkOptions'=>array('class'=>'t-listrouter_ico'),
                                   'active'=> (Yii::app()->controller->id=='routeDetails' or Yii::app()->controller->id=='stopDetails')
					    ),
						array('label'=>''.Yii::t('app','Driver - Vehicle Association').'<span>'.Yii::t('app','Assign driver to vehicle').'</span>', 'url'=>array('/transport/DriverDetails/assign') ,'linkOptions'=>array('class'=>'t-driverassociate_ico'),
                                   'active'=> (Yii::app()->controller->id=='driverDetails' and in_array(Yii::app()->controller->action->id, array('assign', 'reallot')))
					    ),        
						
						array('label'=>''.Yii::t('app','Search Students').'<span>'.Yii::t('app','Search All Students').'</span>', 'url'=>array('/transport/Transportation/studentsearch') ,'linkOptions'=>array('class'=>'t-searchstudent_ico'),
                                   'active'=> (Yii::app()->controller->id=='transportation' and Yii::app()->controller->action->id=='studentsearch' or Yii::app()->controller->action->id=='reallot')
					    ),						
						array(
							'label'=>''.Yii::t('app','Attendance Log').'<span>'.Yii::t('app','Display attendance log').'</span>',
							'url'=>array('/transport/transportation/attendanceLog'),
							'linkOptions'=>array('class'=>'set-timetable_ico'),
							'active'=> (Yii::app()->controller->id=='transportation' and Yii::app()->controller->action->id=='attendanceLog')
					    ),              
					array('label'=>''.'<h1>'.Yii::t('app','Routes').'</h1>'), 
					array('label'=>''.Yii::t('app','Allotment').'<span>'.Yii::t('app','Allot Students').'</span>', 'url'=>array('/transport/Transportation/create') ,'linkOptions'=>array('class'=>'t-allotment_ico'),
                             'active'=> (Yii::app()->controller->id=='transportation' and  (Yii::app()->controller->action->id=='create' or
							 Yii::app()->controller->action->id=='view'  or Yii::app()->controller->action->id=='error'  or Yii::app()->controller->action->id=='warning'))
					    ),  
						
						
						array('label'=>''.'<h1>'.Yii::t('app','Settings').'</h1>'),  
						array(
							'label'=>''.Yii::t('app','Devices').'<span>'.Yii::t('app','Assign devices to a Route').'</span>',
							'url'=>array('/transport/devices'),
							'linkOptions'=>array(
								'class'=>'devicess_ico'
							),
							'active'=> (Yii::app()->controller->id=='devices' and in_array(Yii::app()->controller->action->id, array('index', 'assign', 'update')))
					    ),
						array('label'=>''.Yii::t('app','Vehicle Details').'<span>'.Yii::t('app','All Vehicle Details').'</span>', 'url'=>array('/transport/VehicleDetails/manage') ,'linkOptions'=>array('class'=>'t-vehicle-details_ico'),
                                   'active'=> (Yii::app()->controller->id=='vehicleDetails' and (Yii::app()->controller->action->id=='manage' or Yii::app()->controller->action->id=='create' or Yii::app()->controller->action->id=='update' or Yii::app()->controller->action->id=='view'))
					    ), 
						//array('label'=>''.Yii::t('transport','Route Details').'<span>'.Yii::t('transport','All Route Details').'</span>', 'url'=>array('/transport/RouteDetails/manage') ,'linkOptions'=>array('class'=>'rdetail_ico'),
//                                   'active'=> (Yii::app()->controller->id=='routeDetails' and (Yii::app()->controller->action->id=='create' or Yii::app()->controller->action->id=='update' or Yii::app()->controller->action->id=='view' ) or Yii::app()->controller->id=='stopDetails')
//					    ), 
						array('label'=>''.Yii::t('app','Driver Details').'<span>'.Yii::t('app','All Driver Details').'</span>', 'url'=>array('/transport/DriverDetails/manage') ,'linkOptions'=>array('class'=>'t-driver-details_ico'),
                                   'active'=> (Yii::app()->controller->id=='driverDetails' and (Yii::app()->controller->action->id=='manage' or Yii::app()->controller->action->id=='create' or Yii::app()->controller->action->id=='update' or Yii::app()->controller->action->id=='view'))
					    ),
						array('label'=>''.Yii::t('app','Transport Fee Management').'<span>'.Yii::t('app','Fee Payment Details').'</span>', 'url'=>array('/transport/Transportation/viewall') ,'linkOptions'=>array('class'=>'t-transportation-fee_ico'),
                                   'active'=> (Yii::app()->controller->id=='transportation' and (Yii::app()->controller->action->id=='viewall'or Yii::app()->controller->action->id=='Payfees'))
					    ),  
						array('label'=>''.Yii::t('app','Bus Log').'<span>'.Yii::t('app','Bus Log').'</span>', 'url'=>array('/transport/BusLog/manage') ,'linkOptions'=>array('class'=>'t-buslog_ico'),
                                   'active'=> ((Yii::app()->controller->id=='busLog' and (Yii::app()->controller->action->id=='manage' or Yii::app()->controller->action->id=='create' or  Yii::app()->controller->action->id=='view' or Yii::app()->controller->action->id=='update')) or
								    (Yii::app()->controller->id=='fuelConsumption' and  (Yii::app()->controller->action->id=='create' or Yii::app()->controller->action->id=='view' or Yii::app()->controller->action->id=='update')))
					    ), 
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