<?php
/*
Plugin Name: CommentAddr
Plugin URI: https://github.com/iGocsen/wp-hold
Description: 为Argon主题增加自定义功能
Version: 1.0.1
Author: 小崽安
Author URI: https://bfzw.top/home
License: GPL v2 or later
    Copyright 2024  小崽安  Gocscn.wp@gmail.com
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
  
if (!defined('ABSPATH')) {  
    exit; // 如果直接访问PHP文件，则退出  
}  

include (WP_PLUGIN_DIR . '/comment_ipaddr/getaddr/ipaddr.php');

function show_city_addr($r_addr) {
    // 获取评论作者的IP地址  
    $ip = get_comment_author_ip($comment_ID);
    $r_addr .= " ";
	$r_addr .= "<div class='comment-useragent'>";
    $r_addr .= "<i class='fa fa-globe'; style='color:blue';></i>" ." ".$ip['data'][0]['location'];
    /*  如果想使用自定义图标库，可以把上面的“$out .=”后面的内容替换成以下代码，在按自定义图标部分的内容进行操作
    $out .= $GLOBALS['UA_ICON']['City2'] ." ".$ip['data'][0]['location'];
    $GLOBALS['UA_ICON']['City2'] = '<svg >……</svg>';
    ”<svg>……</svg>“，替换成自己下载的 svg 代码，格式可参照 useragent-parser.php 文件的原有内容。
    */
    $r_addr .= " ";
    $r_addr .= get_city_addr($ip);
    $r_addr .= "</div>";
    return $r_addr;
}
add_filter("argon_comment_ua_icon", "show_city_addr");