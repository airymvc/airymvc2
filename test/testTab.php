<?php

require_once '../app/coreLib/ui/jquery/JUIComponent.php';
require_once '../app/coreLib/ui/html/UIComponent.php';
require_once '../app/coreLib/ui/jquery/Tab.php';
require_once '../app/coreLib/ui/html/components/TextElement.php';
require_once '../app/coreLib/ui/html/components/HtmlScript.php';

//put your code here
$sf = new Tab('test');
$sf->addTab("x1", "x1");
$sf->addTab("d1", "d1");
$e = new TextElement("xy");
$e->setAttribute("name", "dddddddd");
$e->setLabel('ddd', 'sdfdsfdsfs', 'css');
//$sf->setAttribute("id", "test");
//$sf->setAttribute("target", "_blank");
$sf->setElement("x1", $e);
$e1 = new HtmlScript();
$e1->setScript("<a herf='aa.html' target='_blank'>");
$sf->setElement('d1', $e1);
$e2 = new TextElement("xy1");
$e2->setAttribute("name", "dddddddd11");
$e2->setLabel('ddd', 'sdfdsfdsfs1', 'css1');
$sf->setElement("d1", $e2);
$e3 = new HtmlScript();
$e3->setScript("</a>");
$sf->setElement("d1", $e3);
echo $sf->render();

?>
