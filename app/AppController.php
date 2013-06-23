<?php
class AppController extends AbstractController
{
    // @TODO: Need to further refactoring to extract the common feature from acl and app controllers
    // Currently, the way to implement AclController need to be change
        public function setParams($params)
        {
            $this->params = $params;
        }

        public function getParams()
        {
            return $this->params;
        }

        /**
            * @return the $model
            */
        public function getModel() {
            return $this->model;
        }

        /**
            * @return the $view
            */
        public function getView() {
            return $this->view;
        }



        /**
            * @param field_type $view
            */
        public function setView($view) {
            $this->view = $view;
        }


}
?>
