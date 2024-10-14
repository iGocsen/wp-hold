<!-- 在你的WordPress页面或模板文件中添加以下HTML和JavaScript -->
<?php
/*
Template Name: Auto Apply Links
* 提示：友情链接，需在后台审核
*/
?>

<?php get_header(); ?>

<div class="page-information-card-container"></div>

<?php get_sidebar(); ?>

<div id="primary" class="content-area content content-link-application form-header">
	<main id="main" class="site-main" role="main">
	<?php if(function_exists('cmp_breadcrumbs')) cmp_breadcrumbs();?>
    <?php if (have_posts()) : while ( have_posts() ) :
			the_post();
/** 在下面自定义代码 */


?>
<script>
//点击申请友链按钮后，弹出友链信息填写表
document.getElementById('apply-link-button').addEventListener('click', function() {
    document.getElementById('frdlink-form-modal').style.display = 'block';
});
//点击信息表关闭按钮后，关闭信息填写表
document.getElementsByClassName('close-apply-link')[0].addEventListener('click', function() {
    document.getElementById('frdlink-form-modal').style.display = 'none';
    document.getElementById('message').style.display = 'none';
});

window.onclick = function(event) {
    if (event.target == document.getElementById('frdlink-form-modal')) {
        document.getElementById('frdlink-form-modal').style.display = 'none';
        document.getElementById('message').style.display = 'none';
    }
}

jQuery(document).ready(function($) {
    $('#friend-link-form').on('submit', function(e) {
        e.preventDefault();

        var webTitle = $('#friend-web-name').val();
        var webUrl = $('#friend-web-url').val();
        var webImg = $('#friend-web-image').val();
        var webDsc = $('#friend-web-motto').val();
        var webRss = $('#friend-web-rss').val();
        
        $.ajax({
            url: ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'submit_friend_link', // 后台处理函数的钩子
                link_title: webTitle,
                link_url: webUrl,
                security: my_ajax_nonce // AJAX安全令牌
            },
            success: function(response) {
                $('#response-message').html(response);
            }
        });
    });
});

document.getElementById('friend-link-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    var formData = new FormData(this);
    
    fetch(ajax_object.ajax_url + '?action=submit_link', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('frdlink-form-modal').style.display = 'none';
            document.getElementById('message').style.display = 'block';
        } else {
            alert('提交失败：' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('提交失败，请稍后再试。');
    });
});
</script>
<? php 

    if( isset($_POST['friend-web-form']) && $_POST['friend-web-form'] == 'send'){
        global $wpdb;
        
        // 表单变量初始化
        $link_name = isset( $_POST['friend-web-name'] ) ? trim(htmlspecialchars($_POST['friend-web-name'], ENT_QUOTES)) : '';
        $link_url = isset( $_POST['friend-web-url'] ) ? trim(htmlspecialchars($_POST['friend-web-url'], ENT_QUOTES)) : '';
        $link_motto = isset( $_POST['friend-web-motto'] ) ? trim(htmlspecialchars($_POST['friend-web-motto'], ENT_QUOTES)) : ''; // 简介
        $link_image = isset( $_POST['friend-web-image'] ) ? trim(htmlspecialchars($_POST['friend-web-image'], ENT_QUOTES)) : ''; // 图像链接
        /*$link_image = '图片链接：'.$_POST['friend-web-image']."\n".'简介：'.$_POST['friend-web-motto']; */
        $link_rss = isset( $_POST['friend-web-rss'] ) ? trim(htmlspecialchars($_POST['friend-web-rss'], ENT_QUOTES)) : '';
        $link_target = "_blank";
        $link_visible = "N"; // 表示链接默认不可见
        
        // 表单项数据验证
        if ( empty($link_name) || mb_strlen($link_name) > 30 ) {
            wp_die('必须填写网站名称，且长度不得超过30字');
        }
        
        if ( empty($link_url) || strlen($link_url) > 100 /*|| !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $link_url)*/) {
            //验证url
            wp_die('必须填写链接地址');
        }
        
        if ( empty($link_image) || strlen($link_image) > 100 /*|| !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $link_url)*/) {
            //验证url
            wp_die('必须填写站点Logo地址');
        }
        
        $sql_link = $wpdb->insert(
            $wpdb->links,
            array(
                'link_name' => '【待审核】--- '.$link_name,
                'link_url' => $link_url,
                'link_target' => $link_target,
                'link_image' => $link_image,
                'link_motto' => $link_motto,
                'link_rss' => $link_rss,
                'link_visible' => $link_visible
            )
        );
        
        $result = $wpdb->get_results($sql_link);
        
        // 定义接收邮件的邮箱地址和邮件主题  
        $admin_email = get_option( 'admin_email' ); // 获取管理员邮箱  
        //$subject = '新提交的友情链接等待审核';
        // 检查是否成功插入记录  
        if ($sql_link) {  
            
            // 准备邮件内容  
            // 发送邮件通知  
            $headers = 'From: Website Link Submission <'.$admin_email.'>' . "\r\n"; // 发送者信息  
            wp_mail($admin_email, $subject, $message, $headers);  */
            require_once dirname(__FILE__) . '/email-argon.php';
            appr_link_mail_notify($link_name,$link_url,$link_image,$link_motto,$link_rss,$admin_email); 
            
            //刷新缓存
            //wp_cache_flush();
            // 输出提示信息  
            wp_die('友情链接已成功提交，【等待站长审核中】！<a href="/neighbors">点此返回</a>', '提交成功'); 
        } else {  
            // 插入失败处理  
            wp_die('友情链接提交失败，请稍后再试！');  
        } 
        //wp_die('友情链接已成功提交，【等待站长审核中】！<a href="/neighbors">点此返回</a>', '提交成功');
    
    }

/** 在上方面自定义代码 */
        get_template_part( 'template-parts/content', 'aplfrdlinks' );

        if (comments_open() || get_comments_number()) {
            comments_template();
        }

    ?>
    
    <?php endwhile; else:
    endif; ?>
    
<?php get_footer(); ?>