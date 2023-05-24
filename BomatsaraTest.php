<?php
/**
 * Plugin Name: Bomatsara test
 * Description: This is a custom plugin for test task Cpamatica
 * Version: 1.0
 * Author: Bomatsara
 **/

use BomatsaraTest\Cron\CronScheduler;
use BomatsaraTest\Meta\MetaPost;
use BomatsaraTest\Shortcode\ShortcodePost;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('BomatsaraTest')) {
    require_once(__DIR__ . '/vendor/autoload.php');

    class BomatsaraTest
    {
        public function __construct() {
            add_action('wp_enqueue_scripts', [$this, 'enqueue']);
            new CronScheduler([$this, 'post_fetch'], 'daily', 'daily_post_fetch');
            new MetaPost('site_link', 'Site Link', 'text');
            new MetaPost('rating', 'Rating', 'number');
            new ShortcodePost();
        }

        public function post_fetch(): void
        {
            if (is_admin()) return;

            $api_post = new \BomatsaraTest\API\APIPost();
            $post = new \BomatsaraTest\Post\Post();
            $post_category = new \BomatsaraTest\Post\PostCategory();
            $post_thumbnail = new \BomatsaraTest\Post\PostThumbnail();
            $post_author = new \BomatsaraTest\Post\PostAuthor();

            $post_fetch = new BomatsaraTest\Post\PostFetcher($api_post, $post, $post_category, $post_thumbnail, $post_author);
            $post_fetch->fetch_posts();
        }

        public function enqueue(): void
        {
            wp_enqueue_style('bt-style', plugin_dir_url(__FILE__) . 'assets/dist/main.min.css');
            wp_enqueue_script('bt-script', plugin_dir_url(__FILE__) . 'assets/dist/main.min.js', null, false, true);
        }
    }

    new BomatsaraTest();
}