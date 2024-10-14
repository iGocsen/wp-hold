<?php
/*
 * Plugin Name: ApplyFriendlink
 * Plugin URI: https://github.com/iGocsen/wp-hold
 * Description: 1.0.0为Argon主题在友链界面增加自助提交友链的功能，无评论区
 * Version: 1.0.1
 * Author: 小崽安
 * Author URI: https://bfzw.top/home
 * License: GPL v2 or later
 *  Copyright 2024  小崽安  Gocscn+wp@gmail.com
 
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

include (WP_PLUGIN_DIR . '/apply_friend_link/aplfrdlinks.php');


/** * 为链接管理页面添加 通过链接 批量操作 */
function add_appr_links_action($actions) {  
    // 检查当前页面是否为链接管理页面  
    //global $pagenow;  
    if (/*'link-manager.php' === $pagenow && */current_user_can('manage_links')) {  
        // 添加 通过 的批量操作选项  
        $actions['approve_links'] = '通过';  
    }  
    return $actions;  
}  
add_filter('bulk_actions-link-manager', 'add_appr_links_action'); 
  
/** * *刷新缓存，保证启用对象存储时不受缓存影响 
/** *  处理自定义 通过链接 批量操作 */
function handle_appr_links_action($redirect_to, $doaction, $bulklinks) {  
    
    global $wpdb;  
    // 检查是否为自定义的 审核通过 操作  
    if ('approve_links' === $doaction && current_user_can('manage_links')) {  
        //刷新Redis缓存，确保正常审核
        usleep(500000);
        wp_cache_flush();
        
        foreach ($bulklinks as $link_id) {  
            
            $link_id = (int) $link_id;  
            // 获取链接详情  
            $bookmark = get_bookmark($link_id);  
            if ($bookmark && strpos($bookmark->link_name, '【待审核】--- ') !== false) {  
                
                // 更新链接名称，删除“【待审核】--- ”  
                $new_name = str_replace('【待审核】--- ', '', $bookmark->link_name);  
                // 更新链接名称  
                wp_update_link(array(  
                    'link_id' => $link_id,  
                    'link_name' => $new_name,  
                    'link_visible' => 'Y' // 设置为公开  
                ));  
                //$wpdb->update($wpdb->links, array('link_name' => $new_name), array('link_id' => $link_id));  // 更新链接名称和状态  
               
                // 假设你有一个字段控制链接的可见性，比如 'link_visible'，你可以在这里更新它  
                //$wpdb->update($wpdb->links, array('link_visible' => 'Y'), array('link_id' => $link_id));  
                
            }  
            
        }    
        // 批量操作完成后，刷新Redis缓存  
        //wp_cache_flush();
        // 通知管理员链接已被审核通过  
        $redirect_to = add_query_arg('approved', count($bulklinks), $redirect_to);  
    }  
  
    return $redirect_to;  
}  
add_filter('handle_bulk_actions-link-manager', 'handle_appr_links_action', 10, 3);

/** *添加批量通过后的提示 */
function show_appr_links_action_message() {  
    
    global $pagenow; 
    if ('link-manager.php' == $pagenow && isset($_REQUEST['approved'])) {  
        
        //审核通过
    	$approved = (int) $_REQUEST['approved'];
    	/* translators: %s: Number of links. */
    	$approved_message = sprintf( _n( '%s link approved.', '%s links approved.', $approved ), $approved );
    	wp_admin_notice(
    		$approved_message,
    		array(
    			'id'                 => 'message',
    			'additional_classes' => array( 'updated' ),
    			'dismissible'        => true,
    		)
    	);
    	$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'approved' ), $_SERVER['REQUEST_URI'] );
        
    }
      
}  
add_action('admin_notices', 'show_appr_links_action_message');