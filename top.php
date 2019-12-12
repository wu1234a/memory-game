<?php
    define('ROOT_PATH', dirname(__FILE__));

    $name = $_POST['name'];
    $seconds = $_POST['seconds'];

    $list = read_static_cache('list');
    if(!$list){
        $list[0] = [
            'name' => $name,
            'score' => $seconds
        ];
    }else{
        $len = count($list);
        $list[$len] = [
            'name' => $name,
            'score' => $seconds
        ];
    }
    
   if(write_static_cache('list', $list)) {
        $last_names = array_column($list,'score');
        array_multisort($last_names,SORT_ASC,$list);
        echo json_encode($list);
   }    

    // 存入本地文件
    function write_static_cache($cache_name, $caches) {       
        $cache_file_path = ROOT_PATH.'/'.$cache_name.'.php';
        $content = "<?php\r\n";
        $content .= "\$data = ".var_export($caches, true).";\r\n";
        $content .= "?>";
        file_put_contents($cache_file_path, $content, LOCK_EX);
        return true;
    }

    // 读取本地文件
	function read_static_cache($cache_name) {
		static $result = array();
		if (!empty($result[$cache_name])) {
		    return $result[$cache_name];
		}
		$cache_file_path = ROOT_PATH.'\\'.$cache_name.'.php';
		if (file_exists($cache_file_path)) {
		    include_once($cache_file_path);
		    $result[$cache_name] = $data;
		    return $result[$cache_name];
		}else{
		    return false;
		}
	}
?>