<?php
    function get_city_addr($ip) {
        //百度api的接口
     
        $url = "https://opendata.baidu.com/api.php?query=".$ip."&co=&resource_id=6006&format=json&oe=utf8";
        $ipaddr = json_decode(file_get_contents($url),true);
        if($ipaddr['status']=='0'){
            return $ipaddr['data'][0]['location'];
        }else{
            return "Location Unknow";
        }
    }