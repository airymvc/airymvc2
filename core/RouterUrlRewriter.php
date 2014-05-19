<?php
class RouterUrlRewriter {
	
	public function remapGetAndPost($actionKeyword) {
		
		//Deal with $_GET for parsing module/controller/action/querystring 
		$actionWithQueryPath = $_GET[$actionKeyword];
		$params = explode("/", $actionWithQueryPath);
		$GETParams = array();
		if (count($params) < 2) {
			for ($i=1; $i<count($params); $i=$i+2) {
				 $GETParams[$params[$i]] = $params[$i+1];
			}
		}
		//deal with action
		$GETParams[$actionKeyword] = $params[0];
		foreach ($GETParams as $key=>$value) {
			$_GET[$key] = $value;
		}
			
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$qstringPieces = explode('&', $_SERVER['QUERY_STRING']);
		    $newQueryString = "";
			foreach ($qstringPieces as $piece) {
				$kv = explode("=", $piece);
				if ($kv[0] == $actionKeyword) {
					$params = explode("/", $kv[1]);
					$newQueryString .= $actionKeyword . "=" . $params[0] . "&";;
					for ($i=1; $i<count($params); $i=$i+2) {
						$newQueryString .= $params[$i] . "=" . $params[$i+1] . "&";
					}
				} else {
					$newQueryString .= $piece . "&";					
				}
			}
			$_SERVER['QUERY_STRING'] = rtrim($newQueryString, "&");
		}
		
	}
	
}