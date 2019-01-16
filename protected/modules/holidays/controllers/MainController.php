<?php

class MainController extends RController
{
	
    public function init()
    {
        $this->layout = $this->module->layout;
        $this->defaultAction = 'browse';
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', 
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * Render event calendar page.
     */
    public function actionBrowse()
    {
        $userId = (isset($_POST['currentUser'])) ? $_POST['currentUser'] : Yii::app()->user->getId();
        Yii::app()->user->setState('calUserId', $userId);
		
		/*$criteria = new CDbCriteria(array(
		'condition' => 'user_id=:user_id',
		'params'=>array(':user_id'=>1),
		));
		//$criteria->addBetweenCondition('start',0,0,'AND');
		$events = Event::model()->findAll($criteria);
		foreach($events as $event)
		{
			echo $event->start.'-'.$event->end.'/////';
		}*/
					
		$this->render('browse', array('userId' => $userId));
           
    }

    /**
     * Return events as JSON-string for AJAX call
     * @param <int> $start unix time
     * @param <int> $end   unix time
     */
    public function actionList($start = 0, $end = 0)
    {
		//echo $_REQUEST['type'];exit;
        if ((Yii::app()->request->isAjaxRequest) and (Yii::app()->user->hasState('calUserId')) )
        {
			
				 /*$criteria = new CDbCriteria(array(
                        'condition' => 'user_id=:user_id',
                        'params'=>array(':user_id'=> Yii::app()->user->getState('calUserId')),
                    ));*/
				
			$criteria = new CDbCriteria();
            $criteria->addBetweenCondition('start', $start, $end);
			
            $events = Holidays::model()->findAll($criteria);
			
            echo CJSON::encode($events);
			
            Yii::app()->end();
        }
    }

    /**
     *  Update or create new event via AJAX
     */
    public function actionUpdate()
    {
        if (!Yii::app()->user->hasState('calUserId'))
            Yii::app()->end();
        $user_id = Yii::app()->user->getState('calUserId');
        $title = $_POST['title'];
		$desc = $_POST['desc']; 
		
        $start = $_POST['start'];
        $end = $_POST['end'];
        $allDay = 1;
        $editable = ($_POST['editable'] == 'true') ? 1 : 0;
        $eventId = $_POST['eventId'];
		
        if (Yii::app()->request->isAjaxRequest)
        {
            $event = ($eventId == 0) ? new Holidays : Holidays::model()->findByPk($eventId);
            if ($title == '')
            {
               // $event->delete();
                echo 0;
            } else
            {
                $event->title = $title;
				$event->desc = $desc;
				
                $event->user_id = $user_id;
                $event->start = $start;
                $event->end = $end;
                $event->allDay = $allDay;
                $event->editable = $editable;
				
             	$event->save();
                $event->id;
                Yii::app()->end();
            }
        }
    }

    /**
     *  Move event via AJAX
     */
    public function actionMove()
    {
        if (!Yii::app()->user->hasState('calUserId'))
            Yii::app()->end();
		
        $delta = $_POST['delta'];
        $allDay = ($_POST['allDay'] == 'true') ? 1 : 0;
        $eventId = $_POST['eventId'];
        if ((Yii::app()->request->isAjaxRequest) and !empty($eventId))
        {
            $event = Holidays::model()->findByPk($eventId);
            $event->start += $delta;
            $event->end += $delta;
            $event->allDay = $allDay;
            $event->save();
            Yii::app()->end();
        }
    }

    /**
     *  Resize event via AJAX
     */
    public function actionResize()
    {
        if (!Yii::app()->user->hasState('calUserId'))
            Yii::app()->end();

        $delta = $_POST['delta'];
        $eventId = $_POST['eventId'];
        if ((Yii::app()->request->isAjaxRequest) and !empty($eventId))
        {
            $event = Holidays::model()->findByPk($eventId);
            $event->end += $delta;
            $event->save();
            Yii::app()->end();
        }
    }

    /**
     *  Add new record in the list
     */
    public function actionCreateHelper()
    {
        if (!Yii::app()->user->hasState('calUserId'))
            Yii::app()->end();

        $user_id = Yii::app()->user->getState('calUserId');
        $title = $_POST['title'];

        if (Yii::app()->request->isAjaxRequest)
        {
            $ev = new EventsHelper;
            $ev->title = $title;
            $ev->user_id = $user_id;
            $ev->save();
            Yii::app()->end();
        }
    }
	
	
	public function actionDelete()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            if (!Yii::app()->user->hasState('calUserId'))
                Yii::app()->end();

            //$user_id = $_POST['ui'];					
            $user_id = Yii::app()->user->getState('calUserId');
            $title = $_POST['title'];
            $criteria = new CDbCriteria;
            $criteria->condition = 'id=:id';
            $criteria->params = array(':id' => $_POST['eventId']);
            $criteria->addSearchCondition('title', $title);
            $eventsDelete = Holidays::model()->find($criteria);
            $eventsDelete->delete();
			echo 0;
            Yii::app()->end();
        }
    }

	

    /**
     *  Remove record from table
     */
    public function actionRemoveHelper()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            if (!Yii::app()->user->hasState('calUserId'))
                Yii::app()->end();

            //$user_id = $_POST['ui'];
            $user_id = Yii::app()->user->getState('calUserId');
            $title = $_POST['title'];
            $criteria = new CDbCriteria;
            $criteria->condition = 'user_id=:user_id';
            $criteria->params = array(':user_id' => $user_id);
            $criteria->addSearchCondition('title', $title);
            $eventsHelper = EventsHelper::model()->find($criteria);
            $eventsHelper->delete();
            Yii::app()->end();
        }
    }

    /**
     *  Store preference (e-mail, mobile) for current user
     */
    public function actionUserpreference()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            if (!Yii::app()->user->hasState('userId'))
                Yii::app()->end();

            $userPref = EventsUserPreference::model();
            $userPref->attributes = $_POST['EventsUserpreference'];
            $userPref->updateByPk(Yii::app()->user->getState('calUserId') /*$userPref->attributes['user_id']*/, $_POST['EventsUserpreference']);
            Yii::app()->end();
        }
    }

}