<?php
/*
 * Plugin Name: CommentAddr
 * Plugin URI: https://github.com/iGocsen/wp-hold
 * Description: 1.0.1为Argon主题增加评论区显示IP属地的功能 2.0.1 方便修改IP属地展示图标，在设置界面内增加设置界面，支持fa、svg、内置Icon；
 * Version: 2.0.1
 * Author: 小崽安
 * Author URI: https://bfzw.top/home
 * License: GPL v2 or later
 *  Copyright 2024  小崽安  Gocscn.wp@gmail.com
 
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 * 
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, write to the Free Software
 *   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
  
if (!defined('ABSPATH')) {  
    exit; // 如果直接访问PHP文件，则退出  
}  

include (WP_PLUGIN_DIR . '/comment_ipaddr/getaddr/ipaddr.php');

/** * 根据百度IP查询接口获取IP所在地 */ 
function show_city_addr($r_addr) {  
    global $comment;  
    if (isset($comment->comment_ID)) {  
        $comment_ID = $comment->comment_ID;  
    } else {  
        global $wp_query;  
        $comment_ID = $wp_query->comment->comment_ID;  
    }  
  
    $options = get_option('c_ipa_iconset_options');  
    // $ipaddr_type 来自wordpress设置界面，在设置界面进行选择，有四种类型：fa、svg、Icon_1和Icon_2，默认为Icon_1；
    $ipaddr_type = isset($options['ipaddr_type']) ? $options['ipaddr_type'] : 'Icon_1';  
    //$ipaddr_icon  来自wordpress设置界面，在设置界面进行填写，为文本格式
    $ipaddr_icon = isset($options['ipaddr_icon']) ? $options['ipaddr_icon'] : '';  
  
    // 获取评论作者的IP地址  
    $ip = get_comment_author_ip($comment_ID);  
    
    $r_addr .= " ";  
    $r_addr .= "<div class='comment-useragent'>";  
    
    //自定义图标格式
    if ($ipaddr_type == 'fa') {  
        $r_addr .= "<i class='" . esc_attr($ipaddr_type) . " " . esc_attr($ipaddr_icon) . "' style='color:blue;'></i>";  
    } elseif ($ipaddr_type == 'svg') {  
        $r_addr .= $ipaddr_icon;  
    } elseif ($ipaddr_type == 'Icon_1') {  
        $r_addr .= '<svg t="1701513946989" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3823" width="200" height="200"><path d="M554.3 475.1m-434.1 0a434.1 434.1 0 1 0 868.2 0 434.1 434.1 0 1 0-868.2 0Z" fill="#44C0C6" p-id="3824"></path><path d="M554.3 919.3c-60 0-118.1-11.7-172.9-34.9C328.5 862 281 830 240.2 789.2c-40.8-40.8-72.8-88.3-95.2-141.2-23.2-54.8-34.9-112.9-34.9-172.9S121.9 357 145 302.3c22.4-52.9 54.4-100.4 95.2-141.2s88.3-72.8 141.2-95.2C436.2 42.7 494.3 31 554.3 31s118.1 11.7 172.9 34.9c52.9 22.4 100.4 54.4 141.2 95.2 40.8 40.8 72.8 88.3 95.2 141.2 23.2 54.8 34.9 112.9 34.9 172.9s-11.8 118.1-35 172.8c-22.4 52.9-54.4 100.4-95.2 141.2C827.5 830 780 862 727.2 884.4c-54.8 23.1-113 34.9-172.9 34.9z m0-868.3C497 51 441.5 62.2 389.2 84.3c-50.5 21.4-95.9 51.9-134.8 90.9s-69.5 84.3-90.9 134.8c-22.1 52.3-33.3 107.8-33.3 165.1s11.2 112.8 33.3 165.1c21.4 50.5 51.9 95.9 90.9 134.8s84.3 69.5 134.8 90.9c52.3 22.1 107.8 33.3 165.1 33.3s112.8-11.2 165.1-33.3c50.5-21.4 95.9-51.9 134.8-90.9s69.5-84.3 90.9-134.8c22.1-52.3 33.3-107.8 33.3-165.1S967.2 362.3 945.1 310c-21.4-50.5-51.9-95.9-90.9-134.8s-84.3-69.5-134.8-90.9C667.1 62.2 611.5 51 554.3 51z" fill="" p-id="3825"></path><path d="M971.9 357.6C921.6 178.7 760 46.9 566.3 41.6c-53.4 63.2-101.6 146.3 13.6 146.3 185.8 0 137.5 136.9-70.6 146.7-208.1 9.8-149.4 156.5-32.1 171.2s112.5 102.7 112.5 136.9 102.7 14.7 151.6-58.7c48.9-73.4 102.7-151.6 97.8-200.5-3.1-31.2 75.5-30.5 132.8-25.9z" fill="#60C13D" p-id="3826"></path><path d="M615.7 669.1c-6.1 0-11.6-0.7-16.3-2.1-12.5-3.8-19.7-12.6-19.7-24.2v-4c0.1-18.1 0.2-45.5-12.6-70.2-15.3-29.4-45.9-47.1-91.1-52.8-31.5-3.9-60.8-17.1-82.5-37-21.5-19.7-33.3-44.4-32.4-67.7 0.6-16.4 7.7-39.8 37.6-58.7 25.6-16.1 62.7-25.5 110.2-27.7 53.3-2.5 101-14.1 134.3-32.7 27.1-15.1 43-34 42.6-50.5-0.3-14.2-12.8-23.5-23.3-28.8-18.9-9.6-47.4-14.7-82.5-14.7-41.1 0-66.8-10.6-76.5-31.4-7.6-16.4-4.6-38.1 8.9-64.7 9.9-19.4 25.5-41.9 46.4-66.7 2-2.3 4.9-3.6 7.9-3.5 48.5 1.3 96 10.4 141.2 27.1 43.8 16.1 84.6 39 121.3 68 36.4 28.7 67.9 62.8 93.8 101.3 26.2 39.1 46 81.8 58.7 127 0.9 3.1 0.2 6.5-1.9 9.1-2.1 2.5-5.3 3.9-8.5 3.6-64.1-5.1-109-1.4-120.2 10-1.5 1.6-2.1 3-1.9 5 5.1 50.5-45.1 125.6-93.6 198.2l-5.9 8.8c-18.2 27.3-45.6 50.4-77.2 65.1-20.2 9.2-40.5 14.2-56.8 14.2zM570.8 51.8c-40.9 49.2-58.4 86.9-49.3 106.4 7.6 16.4 35.9 19.8 58.4 19.8 38.7 0 69.5 5.7 91.5 16.9 21.6 11 33.8 27.4 34.2 46.1 0.6 24.4-18.7 49.4-52.8 68.5-25.4 14.2-70.9 31.9-143.1 35.3-79.1 3.7-127.3 28.8-128.7 67.2-0.7 17.5 8.8 36.5 26 52.2 18.7 17.1 44.1 28.4 71.5 31.9 52.1 6.5 87.8 27.8 106.4 63.4 15.2 29.1 15 60.7 14.9 79.6v3.9c0 1.2 0 3.4 5.5 5 10.4 3.2 32.7 0.9 58.8-11.2 28.2-13.1 52.7-33.7 68.9-58l5.9-8.8c44.4-66.4 94.6-141.8 90.3-185.2-0.8-7.9 1.8-15.2 7.5-21 15.3-15.6 55.1-21.2 121.5-17-12.1-38.1-29.5-74.2-51.9-107.5-24.7-36.7-54.8-69.3-89.6-96.7C745.9 86.5 661 55.2 570.8 51.8z" fill="" p-id="3827"></path><path d="M123.8 916.3h89.3s152 68.4 281.8 69.5C672.2 987.2 868.3 871 877.4 864c41.1-31.6 57.3-122.4-103.3-84.2-160.5 38.3-270.3 37.2-313.7-11.2-32-35.7 70.6-45.9 126.6-48.9 26.6-1.4 85.1-23.1 85.1-54.7 0-38.5-54.5-49.7-131.4-49.7-137.7 0-99.7-27.2-179.8-23-80.1 4.3-156.8 89.3-156.8 89.3h-80.3v234.7z" fill="#F7DDAD" p-id="3828"></path><path d="M498.1 995.7h-3.4c-68.3-0.6-142.4-19.7-192.5-35.6-47.8-15.2-82.7-30.1-91.3-33.9h-87.1c-5.5 0-10-4.5-10-10V681.7c0-5.5 4.5-10 10-10h76c15.5-16.3 85.6-85.2 160.6-89.2 38.1-2 51.5 2.9 67.1 8.6 17.5 6.4 39.2 14.3 113.2 14.3 43.3 0 75.2 3.6 97.7 10.9 29 9.5 43.7 25.9 43.7 48.8 0 17.9-13.1 34.3-37.9 47.5-17.7 9.4-40.5 16.4-56.7 17.2-113.6 5.9-122.7 24.8-123 25.6-0.6 1.5 1.3 4.4 3.4 6.6 19.3 21.6 54.9 33.3 105.7 34.8 51.1 1.5 117.8-7.5 198.2-26.6 38.9-9.3 70.6-11.8 94.3-7.4 27.8 5.1 39.6 18.7 44.6 29.3 12.9 27.1-3.7 61.9-27.2 79.9-5.8 4.4-58.3 35.7-130.5 65.4-65.1 26.6-161.8 58.3-254.9 58.3z m-364.3-89.4h79.3c1.4 0 2.8 0.3 4.1 0.9 1.5 0.7 151.7 67.5 277.8 68.6 49.9 0.4 133.8-9.1 249.8-56.7 73.3-30.1 123.2-60.5 126.6-63 20.9-16 27.9-41.6 21.3-55.5-5-10.4-26.2-32.4-116.2-11-82.1 19.6-150.5 28.7-203.4 27.2-56.5-1.6-96.9-15.6-120-41.4-10.4-11.6-9.5-21.5-6.9-27.7 5.6-13.4 24.5-22.7 59.5-29.2 27.1-5.1 59-7.5 80.9-8.6 10.9-0.6 30.7-5.5 48.3-14.9 17.4-9.2 27.3-20.1 27.3-29.8 0-26.7-39.7-39.7-121.4-39.7-77.6 0-101.2-8.6-120.1-15.6-14.6-5.3-25.2-9.2-59.1-7.4-75.1 4-149.2 85.1-149.9 86-1.9 2.1-4.6 3.3-7.4 3.3H134v214.5z" fill="" p-id="3829"></path><path d="M137.9 916.8H54.1l0.4-232.9h83.8z" fill="#44C0C6" p-id="3830"></path><path d="M137.9 926.8H54.1c-2.7 0-5.2-1.1-7.1-2.9s-2.9-4.4-2.9-7.1l0.4-232.9c0-5.5 4.5-10 10-10h83.8c2.7 0 5.2 1.1 7.1 2.9s2.9 4.4 2.9 7.1l-0.4 232.9c0 5.5-4.5 10-10 10z m-73.8-20h63.8l0.4-212.9H64.5l-0.4 212.9z" fill="" p-id="3831"></path></svg>';
    }else if($ipaddr_type =='Icon_2'){
        $r_addr .= '<svg t="1701513629027" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1546" width="200" height="200"><path d="M371.488422 87.88386a255.249904 255.249904 0 1 0 130.620843 336.53841A255.549493 255.549493 0 0 0 371.488422 87.88386z" fill="#F8C44F" p-id="1547"></path><path d="M532.168037 862.022066A390.963761 390.963761 0 0 1 373.485682 828.86754a394.858419 394.858419 0 0 1 159.780848-756.16286 395.058145 395.058145 0 0 1 360.805126 553.640636 395.257871 395.257871 0 0 1-361.803756 235.67675z" fill="#FFFFFF" p-id="1548"></path><path d="M533.26653 92.277833a374.885813 374.885813 0 1 1-343.628685 223.79305A372.78869 372.78869 0 0 1 533.26653 92.277833z m0-39.945211a414.831025 414.831025 0 0 0-167.76989 794.4104 410.736641 410.736641 0 0 0 166.671397 35.25165A414.831025 414.831025 0 0 0 700.137652 87.584271a410.337189 410.337189 0 0 0-166.871122-35.251649z" fill="#282D33" p-id="1549"></path><path d="M903.858233 515.197764l-52.128501 40.54439-94.070974 79.09152-4.194248-76.694807-60.916448 11.084796-6.890549-70.703025-41.942472 22.369319-12.083427-9.287262-36.150417-36.25028-11.484248 49.132611 27.662059 27.861785L490.42529 669.885597l-32.854937 5.192877v-63.413023l23.667538-95.868509-43.040965-21.071099-4.393974-41.243431 54.525215-57.221516 40.444527 7.489727 16.17781 24.965757 59.917818 9.986303-45.038226-58.619598-45.837131-29.958909 3.994521-16.077948 59.917818-6.59096-6.59096-39.945212 67.806997-22.668907 43.740007 40.843979 97.166728 10.984933s43.340555-3.29548 91.674261-6.59096a374.985676 374.985676 0 0 1 32.155896 205.118663zM434.601857 105.859205L400.548563 131.024689l-61.116174 47.035487a5.891919 5.891919 0 0 0-2.196986 5.392603l7.090275 48.43357a6.491097 6.491097 0 0 0 3.395343 4.493836 5.59233 5.59233 0 0 0 5.592329-0.599178l34.552609-24.166853 18.774249 67.008093-109.350017 35.551238L250.754019 298.894442a5.792056 5.792056 0 0 0-5.991782 1.497945l-21.470551 22.968497a5.991782 5.991782 0 0 0-1.398083 5.392604L235.275249 379.483907l-38.147677-43.040966-6.291371-22.469182A374.086909 374.086909 0 0 1 434.601857 105.859205zM240.767716 520.98982l-4.393973-72.50056 63.013571-57.421242 55.723571 125.228239-149.794544 126.826048V599.182572l34.153156-75.79604a5.192878 5.192878 0 0 0 1.298219-2.396712zM461.564875 231.986212l-3.195617-97.965632c68.506038-3.794795 108.850702 6.291371 123.930019 11.284522l-49.332336 67.307682z" fill="#34CA9D" p-id="1550"></path><path d="M960.680297 76.299749a65.709874 65.709874 0 0 0-9.187399-12.283153l28.860416-27.562196a109.849333 109.849333 0 0 1 14.779728 19.972606zM159.279484 966.878247l-2.696302-39.945212a285.208813 285.208813 0 0 0 37.24891-5.29274l7.989042 39.146307a310.773748 310.773748 0 0 1-42.54165 6.091645z m-44.439048-1.398082a137.511392 137.511392 0 0 1-44.838501-14.280413l18.874113-35.151787a98.065495 98.065495 0 0 0 31.756443 9.986303z m127.924541-15.179181L230.781413 912.053444c11.783838-3.595069 24.166853-7.889179 36.649732-12.782468l14.480139 37.24891c-13.18192 5.192878-26.463703 9.786577-39.146307 13.781098z m77.593574-29.958909l-16.577263-36.350142c11.584111-5.292741 23.467812-11.084796 35.351512-17.276305l18.374798 35.551239c-12.582742 6.491097-25.06562 12.482879-36.849458 18.075208zM33.152477 918.744267a120.834266 120.834266 0 0 1-18.774249-44.239322l39.046444-8.388495a78.392478 78.392478 0 0 0 12.383016 29.958909z m360.505537-36.050554l-19.972605-34.752334c11.284522-6.391234 22.768771-13.18192 34.153156-19.972606l20.971236 33.95343c-11.683974 6.890549-23.567675 14.080687-35.151787 20.77151z m69.904121-43.040966l-22.069729-33.254388c10.88507-7.190138 21.870003-14.679865 32.854936-22.469182l23.06836 32.655211Q480.438987 828.86754 463.462272 839.652747zM51.02796 831.16439L11.082748 828.86754a267.33333 267.33333 0 0 1 5.692192-43.040966l39.146308 8.088906A224.392228 224.392228 0 0 0 51.02796 831.16439z m479.342542-38.646993l-23.967127-31.956169c9.986303-7.889179 21.071099-15.978085 31.556717-24.266716l24.766032 31.356991q-16.177811 12.782468-32.355622 24.865894zM65.807688 757.465474l-38.047814-12.283153c4.194247-12.682605 9.087536-25.864525 14.580002-39.24617l36.949321 15.279043c-5.192878 12.383016-9.686714 24.666168-13.481509 36.25028z m528.674879-15.578633l-25.564935-30.65795c9.986303-8.488358 19.972606-17.076578 29.958908-25.864524l26.263977 29.958908c-9.986303 9.187399-20.272195 17.975345-30.65795 26.563566z m61.515626-53.626447l-26.963018-29.958908c8.787947-8.088905 17.575893-16.277674 26.263977-24.666169l2.796165-2.696302 27.562196 28.960279-2.696302 2.596439c-8.987673 9.087536-17.975345 17.47603-26.963018 25.764661z m-560.531185-2.696301l-35.950691-17.476031c5.792056-11.883701 12.283153-24.266716 19.173702-36.649731l34.95206 19.273564a791.114921 791.114921 0 0 0-18.175071 34.852198z m619.150784-53.72631l-28.261238-28.261238c9.986303-9.486988 18.77425-18.973976 27.761923-28.560826l29.060141 27.562196c-9.187399 9.786577-18.674387 19.473291-28.3611 29.259868z m56.322749-59.218777l-29.958909-26.763292c8.987673-9.986303 17.775619-19.972606 26.363839-29.958909l29.958909 26.064251q-12.383016 15.378907-26.164113 30.65795z m53.226994-61.915078l-30.957539-25.265347c8.488358-9.986303 16.776989-20.77151 24.666168-31.157265L849.932197 478.647895c-8.388494 10.685344-16.876852 21.270825-25.564936 32.056033z m49.931515-64.91097l-32.255758-23.367949c7.889179-10.88507 15.47877-21.77014 22.76877-32.555347l33.154526 22.369318c-7.889179 11.084796-15.378907 22.269456-23.467812 33.553978zM919.836318 377.786235l-33.95343-21.170962c7.090275-11.284522 13.880961-22.768771 19.972606-33.95343l34.852197 19.972606c-6.191508 11.384385-13.281783 23.268086-20.871373 35.151786z m39.945212-71.901381l-34.952061-17.875482c5.991782-11.983564 11.584111-23.967127 16.577263-35.451376l36.649732 15.678496c-5.292741 12.283153-11.184659 24.965757-17.575893 37.648362zM993.135782 229.689362l-37.748226-12.982194a376.683348 376.683348 0 0 0 10.685344-36.949321l38.846719 9.087536c-2.995891 12.682605-6.990412 26.36384-11.783837 40.843979z m18.674386-83.38563l-39.945212-3.195617c0-4.993151 0.599178-9.986303 0.599178-14.580002a139.1092 139.1092 0 0 0-1.497945-20.971236l39.945212-6.091645a168.269205 168.269205 0 0 1 1.99726 27.062881c-0.399452 5.392604-0.599178 11.284522-1.098493 17.376167zM133.614685 616.458876l-34.053293-20.971236c3.495206-5.692193 7.090275-11.384385 10.785207-17.176441l33.653841 21.470551z" fill="#282D33" p-id="1551"></path><path d="M142.302769 966.678521c-42.042335 0-76.095629-11.584111-99.263852-35.950691l28.860416-27.562196c69.904121 72.700286 320.360599-17.975345 583.399819-269.031002 119.835636-114.143443 216.003733-238.173326 271.028263-349.520603 51.329597-103.458099 59.917818-183.947701 25.165483-220.996885C909.850015 19.977 801.298902 37.153441 668.980387 108.455644L650.206138 73.303858C803.495888-9.382731 923.830839-22.764377 980.353314 36.4544c48.333706 50.630556 41.842609 145.200845-18.274935 266.234837-57.02179 115.04221-156.185778 243.066614-279.616483 360.405674-198.727429 190.039345-408.439791 303.58361-540.159127 303.58361z" fill="#282D33" p-id="1552"></path><path d="M298.488547 920.84139a83.185904 83.185904 0 1 1-83.185904-83.285766 83.185904 83.185904 0 0 1 83.185904 83.285766z" fill="#FFFFFF" p-id="1553"></path><path d="M215.302643 1023.9999a103.258373 103.258373 0 1 1 103.15851-103.15851 103.258373 103.258373 0 0 1-103.15851 103.15851z m0-166.47167a63.313161 63.313161 0 1 0 63.213298 63.31316 63.413024 63.413024 0 0 0-63.213298-63.31316z" fill="#282D33" p-id="1554"></path></svg>'; 
    }  
  
    $r_addr .= " ";  
    $r_addr .= get_city_addr($ip); 
    $r_addr .= "</div>";  
    return $r_addr;  
}  
add_filter("argon_comment_ua_icon", "show_city_addr");

function comment_ipaddr_icon_set() {  
    add_menu_page('评论区显示IP归属地', '评论区IP属地设置', 'manage_options', 'c_ipa_iconset_page', 'c_ipa_iconset_page');  
}  
add_action('admin_menu', 'comment_ipaddr_icon_set'); 

// 创建设置页面  
function c_ipa_iconset_page() {  
    ?>  
    <div class="wrap">  
        <h1>评论区IP属地展示图标设置</h1>  
        <form method="post" action="options.php">  
            <?php settings_fields('c_ipa_settings'); ?>  
            <?php do_settings_sections('c_ipa_iconset_page'); ?>  
            <input type="submit" class="button-primary" value="保存">  
        </form>  
    </div>  
    <?php  
}    

// 初始化插件设置  
function c_ipa_iconset_init() {  
    register_setting('c_ipa_settings', 'c_ipa_iconset_options');  
  
    add_settings_section('c_ipa_setion', '', null, 'c_ipa_iconset_page');  
  
    add_settings_field(  
        'c_ipa_init',  
        '内置图标预览',  
        'c_ipa_init_callback',  
        'c_ipa_iconset_page',  
        'c_ipa_setion'  
    );  
    
    add_settings_field(  
        'c_ipa_type',  
        '图标类型',  
        'c_ipa_type_callback',  
        'c_ipa_iconset_page',  
        'c_ipa_setion'  
    );  
  
    add_settings_field(  
        'c_ipa_icon',  
        '图标代码',  
        'c_ipa_icon_callback',  
        'c_ipa_iconset_page',  
        'c_ipa_setion'  
    );  
}  
add_action('admin_init', 'c_ipa_iconset_init');  

// 设置字段回调函数  
function c_ipa_init_callback() {  
      
    ?>
            <tr>
                <td></td>
                <td width="10" height="20">
                    <?php echo '<img src="' . plugins_url('/images/icon1.svg', __FILE__) . '" alt="Prev_Icon_1" style="width: 25px; ">'; ?> 
                </td>
                <td>
                    <?php echo '<img src="' . plugins_url('/images/icon2.svg', __FILE__) . '" alt="Prev_Icon_2" style="width: 25px; ">'; ?> 
                </td>
            </tr>
            <tr> 
                <td></td>
                <td width="10" >Icon01</td> 
                <td>Icon02</td>
            </tr>
    <?php
} 

function c_ipa_type_callback() {  
    $options = get_option('c_ipa_iconset_options');  
    $ipaddr_type = isset($options['ipaddr_type']) ? esc_attr($options['ipaddr_type']) : 'Icon01';  
  
    echo '<select name="c_ipa_iconset_options[ipaddr_type]">';  
    echo '<option value="fa" ' . selected($ipaddr_type, 'fa', false) . '>Font Awesome</option>';  
    echo '<option value="svg" ' . selected($ipaddr_type, 'svg', false) . '>自定义SVG</option>';  
    echo '<option value="Icon01" ' . selected($ipaddr_type, 'Icon01', false) . '>Icon01</option>';  
    echo '<option value="Icon02" ' . selected($ipaddr_type, 'Icon02', false) . '>Icon02</option>';  
    echo '</select>';  
}  
  
function c_ipa_icon_callback() {  
    $options = get_option('c_ipa_iconset_options');  
    $ipaddr_icon = isset($options['ipaddr_icon']) ? esc_textarea($options['ipaddr_icon']) : '';  
  
    echo '<textarea name="c_ipa_iconset_options[ipaddr_icon]" rows="auto" min-cols="180">' . $ipaddr_icon . '</textarea>';  
    echo 'Font Awesome代码填写格式：fa-icon；SVG代码填写格式：<svg…>……</svg>；关闭图标：类型选择FA或者SVG，清空代码框里的内容。';
}  

/*
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
    $r_addr .= " ";
    $r_addr .= get_city_addr($ip);
    $r_addr .= "</div>";
    return $r_addr;
}
add_filter("argon_comment_ua_icon", "show_city_addr");
    */