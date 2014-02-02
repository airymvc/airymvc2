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
 *
 * The Paginator creates a piece of html that contains links to each page. It takes the SQL, converts it, query partial of results, and then compose the pagination html.
 * The composed html is attached to a URL like http://URL&pagination_query_string_key_value. The URL should contain complete domain and/or query string.
 * 
 */

class Paginator{

    private $_URL;
    private $_params;
    
    private $_navPrefix;
    private $_navPostfix;
    private $_totalPage;
    private $_numberOfItems; //per page
    private $_currentPage;
    private $_numberOfItemsKey;
    private $_pageParameterKey;
    private $_pageHtml;
    private $_databaseId;
    private $_numberOfPages; //in the pagination list
    
    private $_dbSetting;
    
    //Below is all the css id and selector for pagination html
    private $_paginatorCssId;
    private $_paginatorCssSelector;
    private $_nextCssId;
    private $_nextCssSelector;
    private $_prevCssId;
    private $_prevCssSelector;
    private $_firstCssId;
    private $_firstCssSelector; 
    private $_lastCssId;
    private $_lastCssSelector;
    private $_currentCssId;
    private $_currentCssSelector;
    private $_pageCssId;
    private $_pageCssSelector;
    
    private $_previousLabel;
    private $_nextLabel;
    private $_firstLabel;
    private $_lastLabel;
    
    
    public function __construct($URL = "", $params = array(), $pageParameterKey = "page", $numberOfItemsKey = "items_per_page", $numberOfItems = 10, $numberOfPages = 10, $currentPage = 1, $databaseId = 0, $navPrefix = "<span class='page_number'>&nbsp;", $navPostfix = '&nbsp;</span>') {
    	$this->setURL($URL);
    	$this->setPageParameterKey($pageParameterKey);
    	$this->setNumberOfItemsKey($numberOfItemsKey);
    	$this->setCurrentPage($currentPage);
    	$this->setNumberOfItems($numberOfItems);
    	$this->setNumberOfPages($numberOfPages);
    	$this->setParams($params);
    	$this->setNavPostfix($navPostfix);
    	$this->setNavPrefix($navPrefix);
    	$this->setDatabaseId($databaseId);
        $this->configdbSetting();
        
        $this->setDefaultValue();
    }
    
    public function setDefaultValue() {
    	$this->setPaginatorCssIdSelector("paginator", "paginator");
    	$this->setCurrentCssIdSelector("current_page", "current_page");
    	$this->setPrevCssIdSelector("previous_page", "previous_page");
    	$this->setNextCssIdSelector("next_page", "next_page");
    	$this->setFirstCssIdSelector("first_page", "first_page"); 
    	$this->setLastCssIdSelector("last_page", "last_page"); 
    	$this->setPageCssIdSelector("page_link page_link_item_", "page_link page_link_item_");

    	$this->setPreviousLabel("previous");
    	$this->setNextLabel("next");
    	$this->setFirstLabel("first");
    	$this->setLastLabel("last");
    }
    
    /**
     * 
     * Ex: $dbSettings = array("dbtype"   => "mysql",
	 * 						   "host"     => "localhost:3036",
	 * 						   "id"       => "root",
	 *                         "database" => "demo",
	 *                         "pwd"      => "root", 
	 * 						   "encoding" => "utf8"
	 * 						  )
     * @param array $dbSetting
     */
    public function configDbSetting($dbSetting = NULL) {
    	if (is_null($dbSetting)) {
    		$config = Config::getInstance();
        	$dbSettings = $config->getDBConfig();
    		$this->_dbSetting = $dbSettings[$this->_databaseId];    		
    	} else {
    		$this->_dbSetting = $dbSettings; 
    	}
    }
    
    public function getDbSetting() {
    	return $this->_dbSetting;
    }
    
    public function setDatabaseId($databaseId) {
        $this->_databaseId = $databaseId;
    }
        
    public function setURL($URL) {
        $this->_URL = $URL;
    }
    /**
     *
     * @param array $params 
     */
    public function setParams($params) {
        $this->_params = $params;
    }
    
    public function getPageHtmlBySQL($sql) {

        $this->_totalPage = $this->getTotalPageCount($sql);
		
        $pageHtml = "<div id='{$this->getPaginatorCssId()}' class='{$this->getPaginatorCssSelector()}'>";
        
        $numberItemsOnPage = $this->_numberOfItems;
        $end = ceil($this->_totalPage/$numberItemsOnPage); 
        
        $currentPage = ($this->_currentPage > $end) ? $end : $this->_currentPage;
		$currentPage = ($currentPage < 1) ? 1 : $currentPage;
                
        //set currentend and currentstart
        $factor = floor($currentPage/($this->getNumberOfPages()));
        $range = ceil($this->getNumberOfPages()/2);

        $currentstart = $currentPage - $range + 1;
        $currentstart = ($currentstart <= 0) ? 1 : $currentstart;
        $currentend   = $currentstart + $this->getNumberOfPages() - 1;
        $currentend   = ($currentend > $end) ? $end : $currentend;
        
        $paramsString = $this->getParamString();
        
        //set previous and the first parts
        $preLink = $this->composeLink($this->getPreviousPage(), $numberItemsOnPage, $paramsString);
        $firstLink = $this->composeLink(1, $numberItemsOnPage, $paramsString);
        
        $firstNavPrefix = str_replace("class='", "class='{$this->getPaginatorCssSelector()} {$this->getFirstCssSelector()} ", $this->getNavPrefix());
        $prevNavPrefix = str_replace("class='", "class='{$this->getPaginatorCssSelector()} {$this->getPrevCssSelector()} ", $this->getNavPrefix());  
        
        $addHtml= "<a id='{$this->getFirstCssId()}'  href='{$firstLink}' class='{$this->getFirstCssSelector()}'>{$firstNavPrefix}{$this->getFirstLabel()}{$this->getNavPostfix()}</a><a id='{$this->getPrevCssId()}'  href='". $preLink ."' class='{$this->getPrevCssSelector()}'>{$prevNavPrefix}{$this->getPreviousLabel()}{$this->getNavPostfix()}</a>";
        $pageHtml .= $addHtml;
        
        $curNavPrefix = str_replace("class='", "class='{$this->getPaginatorCssSelector()} {$this->getCurrentCssSelector()} ", $this->getNavPrefix());
        
        //set page and current parts
        for ($i = $currentstart; $i<=$currentend; $i++) {
             $iLink = $this->composeLink($i, $numberItemsOnPage, $paramsString);          
             if ($i == $currentPage) {
                 $pageHtml .= "<a id='{$this->getPageCssId()}". $i ."' href='{$iLink}' class='{$this->getPageCssSelector()}{$i}'>{$curNavPrefix}{$i}{$this->getNavPostfix()}</a>";                 
             } else {
                 $pageHtml .= "<a href='{$iLink}' class='{$this->getPageCssSelector()}{$i}'>{$this->getNavPrefix()}{$i}{$this->getNavPostfix()}</a>"; 
             }
        }
        
        //set the last part
        $nextLink = $this->composeLink($this->getNextPage(), $numberItemsOnPage, $paramsString);
        $lastLink = $this->composeLink($end, $numberItemsOnPage, $paramsString);

        $lastNavPrefix = str_replace("class='", "class='{$this->getPaginatorCssSelector()} {$this->getLastCssSelector()} ", $this->getNavPrefix());
        $nextNavPrefix = str_replace("class='", "class='{$this->getPaginatorCssSelector()} {$this->getNextCssSelector()} ", $this->getNavPrefix()); 
		
        $addLast = "<a id='{$this->getNextCssId()}' href='{$nextLink}' class='{$this->getNextCssSelector()}'>{$nextNavPrefix}{$this->getNextLabel()}{$this->getNavPostfix()}</a>"
                 . "<a id='{$this->getLastCssId()}' href='{$lastLink}' class='{$this->getLastCssSelector()}'>{$lastNavPrefix}{$this->getLastLabel()}{$this->getNavPostfix()}</a>";
        
        $pageHtml = $pageHtml . $addLast .'</div>';
        $this->_pageHtml = $pageHtml;
        

        return $this->_pageHtml;
        
    }
    
    protected function composeLink($page, $numberOfItems, $paramsString = "") {
    	$attachParamsString = "";
        if ($paramsString != "") {
        	$attachParamsString = '&' . $paramsString;
        }
        $connect = "?";
		if (strpos($this->_URL, "?") > 0) {
			$connect = '&';
		}
        $link = $this->_URL 
        	  . $connect 
              . $this->_pageParameterKey . '=' . $page . '&'
              . $this->_numberOfItemsKey . '=' . $numberOfItems 
              . $attachParamsString; 
              
        return $link;   	
    }
    
    public function getPreviousPage() {
        $prev = (($this->_currentPage - 1) < 1) ? 1 : ($this->_currentPage - 1);
        return $prev;
    }
    
    public function getNextPage()
    {
        $next = (($this->_currentPage + 1) > $this->_totalPage) ? $this->_totalPage : ($this->_currentPage + 1);
        return $next;
    }
    
    private function getParamString() {
        $paramsString = "";
        
        foreach ($this->_params as $key=>$value){
            if (($key != $this->_pageParameterKey) && ($key != $this->_numberOfItemsKey)){
                $paramsString .= "&" .$key . "=" . $value;
            } 
        } 

        return $paramsString;
    }
    
    private function getTotalPageCount($sql) {
    	
        $search = "/^SELECT(.*)FROM/i";
        $replace = "SELECT COUNT(*) FROM";
        $sql = preg_replace($search, $replace, $sql);
        
        //Need to take out the limit here if using MySQL
        $searchLimit = "/LIMIT?((\s)+(\d)+,(\s)+(\d)+)/i";
        $replace = "";
        $countSql = trim(preg_replace($searchLimit, $replace, $sql));
        $searchLimit = "/LIMIT?((\s)+(\d)+)(\s)+OFFSET?((\s)+(\d)+)(\s)+/i";
        $countSql = trim(preg_replace($searchLimit, $replace, $sql));
        
        //Need to take out the limit here if using MSSQL
        $searchLimit = "/(\bAND\b)?(\s)+row(\s)+>(\s)+(\d)+(\s)+and(\s)+row(\s)+<=(\s)+(\d)+/i";
        $replace = "";
        $countSql = trim(preg_replace($searchLimit, $replace, $sql));
        
        if (empty($this->_dbSetting)) {
        	throw new AiryException("No database setting in Paginator");
        	return NULL;
        }
    	
        // Use PDO connection for both MSSQL and MySQL
        $hostArray = explode(":", $this->_dbSetting['host']);	
		$host = $hostArray[0];
		$port = isset($hostArray[1]) ? $hostArray[1] : "3306";
		$charset = isset($this->dbConfigArray['encoding']) ? "charset={$this->dbConfigArray['encoding']}" : "charset=utf8";
		
		$dsn = "{$this->_dbSetting['dbtype']}:host={$host};port={$port};dbname={$this->_dbSetting['database']};{$charset}";
		if (strtolower($this->_dbSetting['dbtype']) == "mssql") {
			$driver = isset($this->_dbSetting['driver']) ? $this->_dbSetting['driver'] : "dblib";
			$dsn = "{$this->_dbSetting['dbtype']}:host={$this->_dbSetting['host']};dbname={$this->_dbSetting['database']};{$charset}";
		}
		
    	$pdoConn = new PDO($dsn, $this->_dbSetting['id'], $this->_dbSetting['pwd']);

		try {
			$pdoResult = $pdoConn->query($countSql);
		} catch(PDOException $e) {
    		 echo 'PDO ERROR: ' . $e->getMessage();
		}

		$result = NULL;
		$count = 0;
		foreach ($pdoResult as $row) {
			if ($count == 0) {
				$result = $row;
			}
		} 
		$count = $result["COUNT(*)"];
		//close the connection
		$pdoConn = null;

		return $count;
    }
    
    public function getTotalPage()
    {
        return $this->_totalPage;
    }
	/**
	 * @return the $_params
	 */
	public function getParams() {
		return $this->_params;
	}

	/**
	 * @return the $_numberItemsOnPage
	 */
	public function getNumberOfItems() {
		return $this->_numberOfItems;
	}

	/**
	 * @return the $_currentPage
	 */
	public function getCurrentPage() {
		return $this->_currentPage;
	}

	/**
	 * @return the $_numberItemsOnPageKey
	 */
	public function getNumberOfItemsKey() {
		return $this->_numberOfItemsKey;
	}

	/**
	 * @return the $_pageParameterKey
	 */
	public function getPageParameterKey() {
		return $this->_pageParameterKey;
	}

	/**
	 * @return the $_pageHtml
	 */
	public function getPageHtml() {
		return $this->_pageHtml;
	}

	/**
	 * @param field_type $_totalPage
	 */
	public function setTotalPage($totalPage) {
		$this->_totalPage = $totalPage;
	}

	/**
	 * @param field_type $_numberItemsOnPage
	 */
	public function setNumberOFItems($numberOfItems) {
		$this->_numberOfItems = $numberOfItems;
	}

	/**
	 * @param field_type $_currentPage
	 */
	public function setCurrentPage($currentPage) {
		$this->_currentPage = $currentPage;
	}

	/**
	 * @param field_type $_numberItemsOnPageKey
	 */
	public function setNumberOfItemsKey($numberOfItemsKey) {
		$this->_numberOfItemsKey = $numberOfItemsKey;
	}

	/**
	 * @param field_type $_pageParameterKey
	 */
	public function setPageParameterKey($pageParameterKey) {
		$this->_pageParameterKey = $pageParameterKey;
	}

	/**
	 * @param field_type $_pageHtml
	 */
	public function setPageHtml($pageHtml) {
		$this->_pageHtml = $pageHtml;
	}
	
	public function getNavPrefix() {
		return $this->_navPrefix;
	}
	
	public function getNavPostfix() {
		return $this->_navPostfix;
	}
	
	
	public function setNavPrefix($navPrefix) {
		$this->_navPrefix = $navPrefix;
	}
	
	public function setNavPostfix($navPostfix) {
		$this->_navPostfix = $navPostfix;
	}
	/**
	 * @return the $_paginatorCssId
	 */
	public function getPaginatorCssId() {
		return $this->_paginatorCssId;
	}

	/**
	 * @return the $_paginatorCssSelector
	 */
	public function getPaginatorCssSelector() {
		return $this->_paginatorCssSelector;
	}

	/**
	 * @return the $_nextCssId
	 */
	public function getNextCssId() {
		return $this->_nextCssId;
	}

	/**
	 * @return the $_nextCssSelector
	 */
	public function getNextCssSelector() {
		return $this->_nextCssSelector;
	}

	/**
	 * @return the $_prevCssId
	 */
	public function getPrevCssId() {
		return $this->_prevCssId;
	}

	/**
	 * @return the $_prevCssSelector
	 */
	public function getPrevCssSelector() {
		return $this->_prevCssSelector;
	}

	/**
	 * @return the $_firstCssId
	 */
	public function getFirstCssId() {
		return $this->_firstCssId;
	}

	/**
	 * @return the $_firstCssSelector
	 */
	public function getFirstCssSelector() {
		return $this->_firstCssSelector;
	}

	/**
	 * @return the $_lastCssId
	 */
	public function getLastCssId() {
		return $this->_lastCssId;
	}

	/**
	 * @return the $_lastCssSelector
	 */
	public function getLastCssSelector() {
		return $this->_lastCssSelector;
	}

	/**
	 * @return the $_currentCssId
	 */
	public function getCurrentCssId() {
		return $this->_currentCssId;
	}

	/**
	 * @return the $_currentCssSelector
	 */
	public function getCurrentCssSelector() {
		return $this->_currentCssSelector;
	}

	/**
	 * @return the $_pageCssId
	 */
	public function getPageCssId() {
		return $this->_pageCssId;
	}

	/**
	 * @return the $_pageCssSelector
	 */
	public function getPageCssSelector() {
		return $this->_pageCssSelector;
	}

	/**
	 * @param field_type $paginatorCssId
	 * @param field_type $paginatorCssSelector
	 */
	public function setPaginatorCssIdSelector($paginatorCssId, $paginatorCssSelector) {
		$this->_paginatorCssId = $paginatorCssId;
		$this->_paginatorCssSelector = $paginatorCssSelector;
	}
	
	/**
	 * @param field_type $paginatorCssId
	 */
	public function setPaginatorCssId($paginatorCssId) {
		$this->_paginatorCssId = $paginatorCssId;
	}

	/**
	 * @param field_type $paginatorCssSelector
	 */
	public function setPaginatorCssSelector($paginatorCssSelector) {
		$this->_paginatorCssSelector = $paginatorCssSelector;
	}

	/**
	 * @param string $nextCssId
	 * @param string $nextCssSelector
	 */
	public function setNextCssIdSelector($nextCssId, $nextCssSelector) {
		$this->_nextCssId = $nextCssId;
		$this->_nextCssSelector = $nextCssSelector;
	}	
	
	/**
	 * @param string $nextCssId
	 */
	public function setNextCssId($nextCssId) {
		$this->_nextCssId = $nextCssId;
	}

	/**
	 * @param string $nextCssSelector
	 */
	public function setNextCssSelector($nextCssSelector) {
		$this->_nextCssSelector = $nextCssSelector;
	}

	/**
	 * @param string $prevCssId
	 * @param string $prevCssSelector
	 */
	public function setPrevCssIdSelector($prevCssId, $prevCssSelector) {
		$this->_prevCssId = $prevCssId;
		$this->_prevCssSelector = $prevCssSelector;
	}
	
	/**
	 * @param string $prevCssId
	 */
	public function setPrevCssId($prevCssId) {
		$this->_prevCssId = $prevCssId;
	}

	/**
	 * @param string $prevCssSelector
	 */
	public function setPrevCssSelector($prevCssSelector) {
		$this->_prevCssSelector = $prevCssSelector;
	}

	/**
	 * @param string $firstCssId
	 * @param string $firstCssSelector
	 */
	public function setFirstCssIdSelector($firstCssId, $firstCssSelector) {
		$this->_firstCssId = $firstCssId;
		$this->_firstCssSelector = $firstCssSelector;
	}	
	
	/**
	 * @param string $firstCssId
	 */
	public function setFirstCssId($firstCssId) {
		$this->_firstCssId = $firstCssId;
	}

	/**
	 * @param string $firstCssSelector
	 */
	public function setFirstCssSelector($firstCssSelector) {
		$this->_firstCssSelector = $firstCssSelector;
	}
	
	/**
	 * @param string $lastCssId
	 * @param string $lastCssSelector
	 */
	public function setLastCssIdSelector($lastCssId, $lastCssSelector) {
		$this->_lastCssId = $lastCssId;
		$this->_lastCssSelector = $lastCssSelector;
	}

	/**
	 * @param string $lastCssId
	 */
	public function setLastCssId($lastCssId) {
		$this->_lastCssId = $lastCssId;
	}

	/**
	 * @param string $lastCssSelector
	 */
	public function setLastCssSelector($lastCssSelector) {
		$this->_lastCssSelector = $lastCssSelector;
	}
	
	/**
	 * @param string $currentCssId
	 * @param string $currentCssSelector
	 */
	public function setCurrentCssIdSelector($currentCssId, $currentCssSelector) {
		$this->_currentCssId = $currentCssId;
		$this->_currentCssSelector = $currentCssSelector;
	}
	
	/**
	 * @param string $currentCssId
	 */
	public function setCurrentCssId($currentCssId) {
		$this->_currentCssId = $currentCssId;
	}

	/**
	 * @param string $currentCssSelector
	 */
	public function setCurrentCssSelector($currentCssSelector) {
		$this->_currentCssSelector = $currentCssSelector;
	}

	/**
	 * @param string $pageCssId
	 * @param string $pageCssSelector
	 */
	public function setPageCssIdSelector($pageCssId, $pageCssSelector) {
		$this->_pageCssId = $pageCssId;
		$this->_pageCssSelector = $pageCssSelector;
	}
	
	/**
	 * @param string $pageCssId
	 */
	public function setPageCssId($pageCssId) {
		$this->_pageCssId = $pageCssId;
	}

	/**
	 * @param string $pageCssSelector
	 */
	public function setPageCssSelector($pageCssSelector) {
		$this->_pageCssSelector = $pageCssSelector;
	}
	/**
	 * @return the $_previousLabel
	 */
	public function getPreviousLabel() {
		return $this->_previousLabel;
	}

	/**
	 * @return the $_nextLabel
	 */
	public function getNextLabel() {
		return $this->_nextLabel;
	}

	/**
	 * @return the $_firstLabel
	 */
	public function getFirstLabel() {
		return $this->_firstLabel;
	}

	/**
	 * @return the $_lastLabel
	 */
	public function getLastLabel() {
		return $this->_lastLabel;
	}

	/**
	 * @param string $previousLabel
	 */
	public function setPreviousLabel($previousLabel) {
		$this->_previousLabel = $previousLabel;
	}

	/**
	 * @param string $nextLabel
	 */
	public function setNextLabel($nextLabel) {
		$this->_nextLabel = $nextLabel;
	}

	/**
	 * @param string $firstLabel
	 */
	public function setFirstLabel($firstLabel) {
		$this->_firstLabel = $firstLabel;
	}

	/**
	 * @param string $lastLabel
	 */
	public function setLastLabel($lastLabel) {
		$this->_lastLabel = $lastLabel;
	}
	/**
	 * @return int $_numberOfPages
	 */
	public function getNumberOfPages() {
		return $this->_numberOfPages;
	}

	/**
	 * @param int $_numberOfPages
	 */
	public function setNumberOfPages($numberOfPages) {
		$this->_numberOfPages = $numberOfPages;
	}




}

?>
