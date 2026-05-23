<?php
/**
 * Plugin Name: Argon 404 Page
 * Plugin URI: https://github.com/solstice23/argon-theme
 * Description: 仿 Argon 主题风格的 404 错误页面，包含返回上一页和回到首页按钮
 * Version: 1.0.1
 * Author: solstice23
 * Author URI: https://solstice23.top
 * License: GPL-3.0
 * Text Domain: argon-404
 * Requires at least: 5.0
 * Requires PHP: 7.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Argon_404_Page {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {

        // 修复 session 路径
        add_action( 'init', function() {
            if ( ! session_id() ) {
                $session_path = WP_CONTENT_DIR . '/sessions';
                if ( ! file_exists( $session_path ) ) {
                    mkdir( $session_path, 0755, true );
                }
                ini_set( 'session.save_handler', 'files' );
                ini_set( 'session.save_path', $session_path );
                session_start();
            }
        }, 1 );

        // ✅ 仅在 404 页面注入样式
        add_action( 'wp_head', array( $this, 'inject_styles' ), 999 );
        // ✅ 安全的模板替换（不破坏 WordPress 核心）
        add_action( 'template_redirect', array( $this, 'handle_404_template' ) );
        add_filter( 'body_class', array( $this, 'add_body_class' ) );
    }

    /**
     * ✅ 修复1：仅在 404 页面注入样式
     */
    public function inject_styles() {
        if ( ! is_404() ) {
            return; // 非 404 页面直接退出
        }
        ?>
        <style id="argon-404-styles">
        /* ✅ 仅作用于 .a404-page */
        body.a404-page {
            background: #000 !important;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        /* ========== 404 角色头部动画 ========== */
        .a404-head {
            display: block;
            position: relative;
            width: 200px;
            margin: 8% auto 0;
            animation: a404-shvr 1.5s infinite;
        }
        .a404-head::after {
            content: '';
            width: 20px;
            height: 20px;
            background: #000;
            position: absolute;
            top: 30px;
            left: 25px;
            border-radius: 50%;
            box-shadow: 125px 0 0 #000;
            animation: a404-eye 4.5s infinite;
        }

        /* ========== Meta 标签构成的耳朵 ========== */
        .a404-meta {
            position: relative;
            display: inline-block;
            background: #fff;
            width: 75px;
            height: 80px;
            border-radius: 50% 50% 50% 50% / 45px 45px 45% 45%;
            transform: rotate(45deg);
        }
        .a404-meta::after {
            content: '';
            position: absolute;
            border-bottom: 2px solid #fff;
            width: 70px;
            height: 50px;
            left: 0px;
            bottom: -10px;
            border-radius: 50%;
        }
        .a404-meta::before {
            bottom: auto;
            top: -100px;
            transform: rotate(45deg);
            left: 0;
        }
        .a404-meta:nth-of-type(2) {
            float: right;
            transform: rotate(-45deg);
        }
        .a404-meta:nth-of-type(2)::after {
            left: 5px;
        }
        .a404-meta:nth-of-type(3) {
            display: none;
        }

        /* ========== 主体内容 ========== */
        .a404-body {
            margin-top: 100px;
            text-align: center;
            color: #fff;
        }
        .a404-body::before {
            content: '404';
            font-size: 80px;
            font-weight: 800;
            display: block;
            margin-bottom: 10px;
        }
        .a404-search::before {
            content: 'Got lost? How.....? why.....? Ahhhh....';
            color: #1EA7AB;
            width: 125px;
            font-size: 30px;
            overflow: hidden;
            display: inline-block;
            margin-bottom: 60px;
            white-space: nowrap;
            animation: a404-text-show 2s infinite steps(3);
        }

        /* ========== 夜间模式适配 ========== */
        body.night.a404-page .a404-head,
        body.night.a404-page .a404-meta {
            background: #1a1a2e;
        }
        body.night.a404-page .a404-head::after {
            background: #1a1a2e;
        }
        body.night.a404-page .a404-meta::after {
            border-bottom-color: #1a1a2e;
        }
        body.night.a404-page .a404-meta::before {
            background: #1a1a2e;
        }
        body.night.a404-page .a404-body {
            color: #e0e0e0;
        }

        /* ========== 动画 ========== */
        @keyframes a404-eye {
            0%, 30%, 55%, 90%, 100% { transform: translate(0, 0); }
            10%, 25% { transform: translate(0, 20px); }
            65% { transform: translate(-20px, 0); }
            80% { transform: translate(20px, 0); }
        }
        @keyframes a404-shvr {
            0% { transform: translate(1px, 1em); }
            50% { transform: translate(0, 1em); }
            100% { transform: translate(-1px, 1em); }
        }
        @keyframes a404-text-show {
            to { text-indent: -373px; }
        }

        /* ========== 按钮样式 (Argon 风格) ========== */
        .a404-actions {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .a404-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 32px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .a404-btn-primary {
            background: #1EA7AB;
            color: #fff;
            border-color: #1EA7AB;
        }
        .a404-btn-primary:hover {
            background: #178a8e;
            border-color: #178a8e;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 167, 171, 0.4);
        }
        .a404-btn-secondary {
            background: transparent;
            color: #1EA7AB;
            border-color: #1EA7AB;
        }
        .a404-btn-secondary:hover {
            background: rgba(30, 167, 171, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 167, 171, 0.2);
        }

        /* 夜间模式按钮 */
        body.night.a404-page .a404-btn-primary {
            background: #1EA7AB;
            color: #1a1a2e;
        }
        body.night.a404-page .a404-btn-primary:hover {
            background: #178a8e;
        }
        body.night.a404-page .a404-btn-secondary {
            color: #1EA7AB;
            border-color: #1EA7AB;
        }

        /* ========== 搜索框 ========== */
        .a404-search {
            margin-top: 30px;
        }
        .a404-search input[type="search"] {
            width: 300px;
            max-width: 80%;
            padding: 12px 20px;
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 50px;
            background: rgba(255,255,255,0.05);
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: all 0.3s;
        }
        .a404-search input[type="search"]::placeholder {
            color: rgba(255,255,255,0.4);
        }
        .a404-search input[type="search"]:focus {
            border-color: #1EA7AB;
            background: rgba(255,255,255,0.1);
        }
        body.night.a404-page .a404-search input[type="search"] {
            border-color: rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.03);
            color: #e0e0e0;
        }
        body.night.a404-page .a404-search input[type="search"]::placeholder {
            color: rgba(255,255,255,0.3);
        }
        </style>
        <?php
    }

    /**
     * ✅ 修复2：安全的模板替换（不使用 remove_all_actions）
     */
    public function handle_404_template() {
        if ( ! is_404() ) {
            return;
        }

        // 仅替换模板，不破坏 WordPress 核心
        add_filter( 'template_include', array( $this, 'load_404_template' ), 999 );
        
        // 确保发送 404 状态码
        status_header( 404 );
        nocache_headers();
    }

    /**
     * 加载自定义 404 模板
     */
    public function load_404_template( $template ) {
        if ( is_404() ) {
            $custom_template = plugin_dir_path( __FILE__ ) . '404-template.php';
            if ( file_exists( $custom_template ) ) {
                return $custom_template;
            }
        }
        return $template;
    }

    /**
     * ✅ 修复3：仅在 404 页面添加 body class
     */
    public function add_body_class( $classes ) {
        if ( is_404() ) {
            $classes[] = 'a404-page';
        }
        return $classes;
    }
}

// 初始化
Argon_404_Page::get_instance();
