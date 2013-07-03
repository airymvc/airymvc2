<?php
/**
 * AiryMVC Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license.
 *
 * It is also available at this URL: http://opensource.org/licenses/BSD-3-Clause
 *
 * @author: Hung-Fu Aaron Chang
 */

class AppController extends AbstractController
{
        public function initial($params)
        {
            $this->setDefaultModel();
            $this->view = new AppView();
            $this->setDefaultView();
            $this->setParams($params);
            $this->layout = new Layout();
            $this->layout->setView($this->view);
        }

}
?>
