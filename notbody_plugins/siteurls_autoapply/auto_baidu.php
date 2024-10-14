<?php  
/**  
 * Plugin Name: Siteurls Auto to Baidu  
 * Plugin URI: https://github.com/iGocsen/wp-hold
 * Description: Execute tasks on a schedule using WordPress Cron.  
 * Version: 1.1.0  
 * Author: 小崽安  
 * Author URI: https://bfzw.top/home  
 */  
  
// 防止直接访问文件  
if ( ! defined( 'ABSPATH' ) ) {  
    exit;  
}  
  
// 插件的主要功能代码  
class SiteUrlsToBaiduTasks {  
  
    public function __construct() {  
        // 初始化插件  
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );  
        add_action( 'init', array( $this, 'autoupload_tasks' ) );  
    }  
  
    public function load_textdomain() {  
        // 加载插件的本地化文本  
        load_plugin_textdomain( 'upload-siteurls-task', false, basename( dirname( __FILE__ ) ) . '/languages/' );  
    }  
  
    public function autoupload_tasks() {  
        // 注册任务到WordPress Cron  
        if ( ! wp_next_scheduled( 'upload_siteurls_to_baidu_hook' ) ) {  
            wp_schedule_event( time(), 'daily', 'upload_siteurls_to_baidu_hook' );  
        }  
    }  
  
    public function do_upload_task() {  
        // 这里执行你的任务代码  
        // 例如，你可以在这里调用其他函数或方法来完成特定的工作  
        // WordPress的根目录  
        define('ABSPATH', __DIR__ . '/');  
        
        // 引入WordPress  
        require_once(ABSPATH . 'wp-load.php');  
        
        // 百度站长平台的API地址  
        $api = 'http://data.zz.baidu.com/urls?site=https://example.com&token=yourbaidutoken';  
        
        // 读取已推送的URL列表  
        $pushed_urls = file_get_contents(WP_PLUGIN_DIR . '/siteurls_autoapply/log/urls.txt');  
        //$pushed_urls = explode("\n", $pushed_urls);  将文件内容分割成数组  
        $pushed_urls = explode("\n", trim($pushed_urls)); // 去除尾部空白并分割成数组  
        
        // 获取所有文章链接  
        $posts = get_posts(array(  
            'numberposts' => -1, // 获取所有文章  
            'post_type' => 'post', // 只获取文章类型  
            'post_status' => 'publish' // 只获取已发布的文章  
        ));  
        
        $urls_to_push = array();  
        $urls_failed = array();  
        
        // 获取当前北京时间的时间戳  
        date_default_timezone_set('Asia/Shanghai');  
        $nowTimeUrl = date('Y_m_d_H_i_s', time());  
        
        // 遍历所有文章并收集URL  
        foreach ($posts as $post) {  
            $url = get_permalink($post->ID); // 获取文章的URL  
            
            // 假设你有一个地方存储已经推送的URL，比如一个数据库或文件  
            // 这里我们用一个数组来模拟这个存储  
            //$pushed_urls = get_pushed_urls();  
            
            // 检查URL是否已经被推送过  
            if (!in_array($url, $pushed_urls)) {  
                $urls_to_push[] = $url;  
            }  
        }  
        
        // 限制每天推送的URL数量  
        $urls_to_push = array_slice($urls_to_push, 0, 10); // 只取前10条  
        
        // 如果有URL需要推送  
        if (!empty($urls_to_push)) {  
            $ch = curl_init();  
            $options = array(  
                CURLOPT_URL => $api,  
                CURLOPT_POST => true,  
                CURLOPT_RETURNTRANSFER => true,  
                CURLOPT_POSTFIELDS => implode("\n", $urls_to_push),  
                CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),  
            );  
            curl_setopt_array($ch, $options);  
            $result = curl_exec($ch);  
            
            // 检查推送结果  
            $result_data = json_decode($result, true);  
            if (isset($result_data['error'])) {  
                // 推送失败，记录失败URL  
                //$urls_failed = array_merge($urls_failed, $urls_to_push);  
                $fail_message = $nowTimeUrl . "_URLs: \n " . implode("\n ", $urls_to_push) . "\nError: " . $result . "\n";  
                //file_put_contents(ABSPATH . 'urls_fail.txt', implode("\n", $urls_to_push) . PHP_EOL, FILE_APPEND);  
                file_put_contents(WP_PLUGIN_DIR . '/siteurls_autoapply/log/urls_fail.txt', $fail_message, FILE_APPEND);  
            } elseif (isset($result_data['success'])) {
                // 推送成功，记录成功URL到urls.txt  
                file_put_contents(WP_PLUGIN_DIR . '/siteurls_autoapply/log/urls.txt', $nowTimeUrl . "\n" . implode("\n", array_merge($urls_to_push)) . "\n" . PHP_EOL, FILE_APPEND);  
            } else {  
                // 无法判断推送结果，可能需要记录日志或采取其他措施  
                file_put_contents(WP_PLUGIN_DIR . '/siteurls_autoapply/log/urls_fail.txt', $nowTimeUrl . "\n " . "no_Urls" . "\nError: " . 'result' . "\n", FILE_APPEND);  
            }  
            
            // 输出或处理推送结果  
            //echo $result;  
            
            // 关闭cURL资源，并释放系统资源  
            curl_close($ch);  
        
            // 将新推送的URL追加到urls.txt文件中  
            //file_put_contents(ABSPATH . 'urls.txt', implode("\n", $urls_to_push) . PHP_EOL, FILE_APPEND);  
        } 
        
            // 打印一条日志消息，以便知道任务已经执行  
            error_log( 'My SiteUrls to Baidu Task Executed!' );  
    }  
  
    public function register_hooks() {  
        // 注册任务执行时的钩子  
        add_action( 'upload_siteurls_to_baidu_hook', array( $this, 'do_upload_task' ) );  
    }  
}  
  
// 实例化插件类  
$upload_siteurls_task = new SiteUrlsToBaiduTasks();  
  
// 注册钩子  
$upload_siteurls_task->register_hooks();