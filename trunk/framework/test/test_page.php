<?php

require_once '../app/coreLib/page/Paginator.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor. 
 */
 $p = new Paginator();
 $sql = "SELECT * FROM admin WHERE isdelete = 0";
 $x = $p->getPageHtmlBySQL($sql, "http://test:10088/history/index.php?cl=admin&at=getAdm");
 echo $x;
         

?>
