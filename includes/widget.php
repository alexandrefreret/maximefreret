<?php


    class Widget
    {
        public $widget_before;
        public $widget_after;

        /**
         * Widget constructor.
         *
         * @param Request $requete
         */
        function __construct()
        {
            global $requete;

            //Je selectionne les widget qui sont sur le topic
            $sql = "SELECT *
            FROM widget
            INNER JOIN topic_widget ON topicwidget_widget = widget_id 
            WHERE topicwidget_topic = ?";

            $list_widgets = $requete->querySecure($sql, [$requete->topic["topic_id"]]);

            if(!empty($list_widgets))
            {
                foreach ($list_widgets as $list_widget)
                {
                    if($list_widget["widget_before"] == 1)
                    {
                        $this->widget_before[] = $list_widget;
                    }
                    else
                    {
                        $this->widget_after[] = $list_widget;
                    }
                }
            }
        }

    }