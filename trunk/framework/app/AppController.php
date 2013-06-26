<?php
class AppController extends AbstractController
{
        public function initial($params)
        {
            $this->setDefaultModel();
            $this->view = new AppView();
            $this->setDefaultView();
            $this->setParams($params);
        }

}
?>
