<?php


class Initializer {

    public static function initialize() {
        set_include_path(get_include_path() . PATH_SEPARATOR . "core");
        set_include_path(get_include_path() . PATH_SEPARATOR . "modules");
        set_include_path(get_include_path() . PATH_SEPARATOR . "config");
        set_include_path(get_include_path() . PATH_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "lib");
        set_include_path(get_include_path() . PATH_SEPARATOR . "app");
        set_include_path(get_include_path() . PATH_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "coreLib");
        set_include_path(get_include_path() . PATH_SEPARATOR . "common");
        set_include_path(get_include_path() . PATH_SEPARATOR . "plugin");


        //set module paths
        $root = PathService::getInstance()->getRootDir();
        $modulePath = $root . DIRECTORY_SEPARATOR . "modules";
        $moduleFolders = Initializer::getDirectory($modulePath, TRUE);
        foreach ($moduleFolders as $i => $mfolder)
        {
            $fd = trim($mfolder);
            $rp = trim($modulePath) . DIRECTORY_SEPARATOR;
            $f = "modules" .DIRECTORY_SEPARATOR .str_replace($rp, "", $fd);
            set_include_path(get_include_path() . PATH_SEPARATOR . $f);
        }
        $Config = Config::getInstance();
        //set time zone
        if (!is_null($Config->getTimezone())) {
            date_default_timezone_set($Config->getTimezone());
        }
        /*
         * include folders under share, coreLib, plug-in
         *  
         */
        $plugIn = $root . DIRECTORY_SEPARATOR . "plugin";
        $coreLib = $root . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "coreLib";
        $moduleLib = $root . DIRECTORY_SEPARATOR . "share";

        $plugInFolders = Initializer::getDirectory($plugIn, TRUE);
        foreach ($plugInFolders as $i => $folder1)
        {
            $fd = trim($folder1);
            $rp = trim($plugIn) . DIRECTORY_SEPARATOR;
            $f = "plugin" .DIRECTORY_SEPARATOR .str_replace($rp, "", $fd);           
            set_include_path(get_include_path() . PATH_SEPARATOR . $f);
        }
        
         
        $coreLibFolders = Initializer::getDirectory($coreLib, TRUE);
        foreach ($coreLibFolders as $i => $folder1)
        {
            $fd = trim($folder1);
            $rp = trim($coreLib) . DIRECTORY_SEPARATOR;
            $f = "app" . DIRECTORY_SEPARATOR . "coreLib" .DIRECTORY_SEPARATOR .str_replace($rp, "", $fd);
            set_include_path(get_include_path() . PATH_SEPARATOR . $f);
        }
        
        $moduleLibFolders = Initializer::getDirectory($moduleLib, TRUE);
        foreach ($moduleLibFolders as $i => $folder1)
        {
            $fd = trim($folder1);
            $rp = trim($moduleLib) . DIRECTORY_SEPARATOR;
            $f = "share" .DIRECTORY_SEPARATOR .str_replace($rp, "", $fd);
            set_include_path(get_include_path() . PATH_SEPARATOR . $f);
        }

    }
        
    public static function getDirectory($directory, $recursive) {
	$array_items = array();
        $ignore = array('.', '..', '.svn', '.DS_Store');
	if ($handle = opendir($directory)) {
		while (false !== ($file = readdir($handle))) {
			if (!in_array($file, $ignore)) {
				if (is_dir($directory. DIRECTORY_SEPARATOR . $file)) {
					if($recursive) {
						$array_items = array_merge($array_items, Initializer::getDirectory($directory. DIRECTORY_SEPARATOR . $file, $recursive));
					}
					$file = $directory . DIRECTORY_SEPARATOR . $file;
                                        if (DIRECTORY_SEPARATOR == "\\") {
                                            $array_items[] = preg_replace("/\\\\/si", DIRECTORY_SEPARATOR, $file);
                                        } else {
                                            $array_items[] = preg_replace("/\/\//si", DIRECTORY_SEPARATOR, $file);
                                        }
                                            
                                                                   
					
				}
			}
		}
		closedir($handle);
	}
	return $array_items;
}

}

?>
