<?php

/**
 * AiryMVC Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license.
 *
 * It is also available at this URL: http://opensource.org/licenses/BSD-3-Clause
 * The project website URL: https://code.google.com/p/airymvc/
 *
 * @author: Hung-Fu Aaron Chang
 */

class Paginator{
    //put your code here
    private $_linkAction;
    private $_params;
    private $_totalPage;
    private $_numberItemsOnPage;
    private $_currentPage;
    private $_numberItemsOnPageKey;
    private $_pageKey;
    private $_pageHtml;
    
    private $db;

    public function __construct() {
        $this->_pageKey = "page";
        $this->_numberItemsOnPageKey = "items_per_page";
        $this->_currentPage = 1;
        $this->_numberItemsOnPage = 10;
        $this->initialDB();
    }
    
    public function setPageItemKeys($pageKey, $numberItemsOnPageKey) {
        $this->_pageKey = $pageKey;
        $this->_numberItemsOnPageKey = $numberItemsOnPageKey;
    }
    
    
    public function setPreviousNext($previousText, $nextText)
    {
        
    }
    
    public function initialDB() {
        $Config = Config::getInstance();
        $dbConfigArray = $Config->getDBConfig();

        //Check if the dbtype is "MySQL"
        if (strtolower($dbConfigArray['dbtype']) == "mysql") {
            $this->db = new MysqlAccess();
        }
        //Check if the dbtype is others....... 
    }
    
    public function setLinkAction($linkAction) {
        $this->_linkAction = $linkAction;
    }
    /**
     *
     * @param array $params 
     */
    public function setParams($params) {
        $this->_params = $params;
    }
    
    public function getPageHtmlBySQL($sql, $linkAction,  $currentPage = null, $numberItemsOnPage = null, $navPrefix = "<span class='page_number'>&nbsp;", $navPostfix = '&nbsp;</span>') {
        $search = "/^SELECT(.*)FROM/i";
        $replace = "SELECT COUNT(*) FROM";
        $sql = preg_replace($search, $replace, $sql);
        
        $search1 = "/LIMIT?((\s)+(\d)+,(\s)+(\d)+)/";
        $replace1 = "";
        $countSql = trim(preg_replace($search1, $replace1, $sql));
        
        
        $this->db->setStatement($countSql);
        $rows = mysql_fetch_array($this->db->execute());
        $this->_totalPage = $rows['COUNT(*)'];
        
        $pageHtml = '<div id="paginator" class="paginator">';
        
        $this->setLinkAction($linkAction);
        $currentPage = (is_null($currentPage)) ? $this->_currentPage : $currentPage;
        $numberItemsOnPage = (is_null($numberItemsOnPage)) ? $this->_numberItemsOnPage : $numberItemsOnPage;
       
        
        $end = ceil($this->_totalPage/$numberItemsOnPage); 
        ////xyz
        
        if ($currentPage > $end){
            $currentPage = $end;
            }
        if ($currentPage < 1){
            $currentPage = 1;
            }
        if ($end < 10){
            $currentend = $end;
            $currentstart = 1;  
            }
        else if ($end>=10){
            if ($currentPage <=5){
                $currentstart = 1;
                $currentend = 10;   
                }
            else if (($currentPage >5)&&($currentPage <= $end -5)){
                $currentstart = $currentPage-5+1;
                $currentend = $currentPage+5;
            }
            else if (($currentPage >$end -5)&&($currentPage <= $end)){
                $currentstart = $end - 9;
                $currentend = $end;
            }
     }            
        
        
        $navPrefix = str_ireplace("'", '"', $navPrefix);
        $navPostfix = str_ireplace("'", '"', $navPostfix);
        $curNavPrefix = str_replace('class="', 'class="current_page ', $navPrefix);

        $paramsString = "";
        
        foreach ($this->_params as $key=>$value){
            if (($key != $this->_pageKey) && ($key != $this->_numberItemsOnPageKey)){
                $paramsString = $paramsString . "&" .$key . "=" . $value;
            } 
        }
         if ($paramsString == "") {
            $link = $this->_linkAction . '&' 
                          . $this->_pageKey . '=' . ($currentPage-1) . '&'
                          . $this->_numberItemsOnPageKey . '=' . $numberItemsOnPage ;
            $link1 = $this->_linkAction . '&' 
                          . $this->_pageKey . '=' . 1 . '&'
                          . $this->_numberItemsOnPageKey . '=' . $numberItemsOnPage ;
         }else {
                    $link = $this->_linkAction . '&' 
                          . $this->_pageKey . '=' . ($currentPage-1) . '&'
                          . $this->_numberItemsOnPageKey . '=' . $numberItemsOnPage . '&'
                          . $paramsString;
                    $link1 = $this->_linkAction . '&' 
                          . $this->_pageKey . '=' . 1 . '&'
                          . $this->_numberItemsOnPageKey . '=' . $numberItemsOnPage . '&'
                          . $paramsString;
         } 
         
        
         if (($currentPage > 1)&&(($currentPage < 5))){
             $pageHtml = $pageHtml . '<a id="previous"  href="'. $link .'" class="previous">上一頁  </a>';
         }
        else if ($currentPage >= 5){
            $pageHtml = $pageHtml. '<a id="first"  href="'. $link1 .'" class="first"> 首頁 </a> <a id="previous"  href="'. $link .'" class="previous">上一頁  </a>';
        }
        
        for ($i = $currentstart; $i<=$currentend; $i++) {
            
            //echo 'This is current end:'.$currentEnd;
             if ($paramsString == "") {
                    $link = $this->_linkAction . '&' 
                          . $this->_pageKey . '=' . $i . '&'
                          . $this->_numberItemsOnPageKey . '=' . $numberItemsOnPage;
                    
             } else {
                    $link = $this->_linkAction . '&' 
                          . $this->_pageKey . '=' . $i . '&'
                          . $this->_numberItemsOnPageKey . '=' . $numberItemsOnPage . '&'
                          . $paramsString;
                    
             }
             
//             echo 'This is:'.$i;
//             echo '---'.$end;
             
             if ($i < $end - 5)
             {
                if ($i == $currentPage) {
                    $pageHtml = $pageHtml . '<a id="page_link page_link_item_'. $i .'" href="'. $link .'" class="page_link page_link_'. $i .'">'.$curNavPrefix 
                            .'<bold>'. $i .'</bold>'. $navPostfix .'</a>';                 
                } else {
                    $pageHtml = $pageHtml . '<a href="'. $link .'" class="page_link page_link_'. $i .'">' .$navPrefix 
                            . $i . $navPostfix .'</a>'; 
                }
             }
             else
             {
                if ($i == $currentPage) {
                    $pageHtml = $pageHtml . '<a id="page_link page_link_item_'. $i .'" href="'. $link .'" class="page_link page_link_'. $i .'">'.$curNavPrefix 
                            . $i .'</a>';                 
                } else {
                    $pageHtml = $pageHtml . '<a href="'. $link .'" class="page_link page_link_'. $i .'">' .$navPrefix 
                            . $i .'</a>'; 
                } 
             }
        }
        
        if ($paramsString == "") {
                    $link = $this->_linkAction . '&' 
                                . $this->_pageKey . '=' . ($currentPage+1) . '&'
                                . $this->_numberItemsOnPageKey . '=' . $numberItemsOnPage ;
                    $link2 = $this->_linkAction . '&' 
                                . $this->_pageKey . '=' . $end . '&'
                                . $this->_numberItemsOnPageKey . '=' . $numberItemsOnPage;
         }else {
                    $link = $this->_linkAction . '&' 
                          . $this->_pageKey . '=' . ($currentPage+1) . '&'
                          . $this->_numberItemsOnPageKey . '=' . $numberItemsOnPage . '&'
                          . $paramsString;
                    $link2 = $this->_linkAction . '&' 
                          . $this->_pageKey . '=' . $end . '&'
                          . $this->_numberItemsOnPageKey . '=' . $numberItemsOnPage . '&'
                          . $paramsString;
         }  
       
        if (($currentPage<($end-5))&&($currentPage>=1))
        {
            $pageHtml = $pageHtml . '<a id="next"  href="'. $link .'" class="next">  下一頁</a>'.'<a id="last"  href="'. $link2 .'" class="last">  末頁</a>';
        }
        $pageHtml = $pageHtml . '</div>';
        $this->_pageHtml = $pageHtml;
        

        return $this->_pageHtml;
        
    }
    
    private function getPreviousPage() {
        $prev = (($this->_currentPage - 1) < 1) ? 1 : ($this->_currentPage - 1);
        return $prev;
    }
    
    private function getNextPage()
    {
        $next = (($this->_currentPage + 1) > $this->_totalPage) ? $this->_totalPage : ($this->_currentPage + 1);
        return $next;
    }
    
    private function getTotalPage()
    {
        return $this->_totalPage;
    }
}

?>
