<?php
/*
Plugin Name: WP ULike Quick Stats
Description: Adds a dashboard widget and admin toolbar menu to quickly check WP ULike likes.
Version: 1.3
Author: 01kawa
License: GPL-2.0+
Text Domain: wp-ulike-quick-stats
Domain Path: /languages
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load plugin text domain for translations
add_action('plugins_loaded', function() {
    load_plugin_textdomain('wp-ulike-quick-stats', false, dirname(plugin_basename(__FILE__)) . '/languages/');
});

// Main plugin logic
add_action('plugins_loaded', function() {
    if (class_exists('WpUlikeInit')) {
        /**
         * Add dashboard widget
         */
        function wpuqs_add_dashboard_widget() {
            wp_add_dashboard_widget(
                'wpuqs_stats_widget',
                esc_html__('Recently Liked Posts (WP ULike)', 'wp-ulike-quick-stats'),
                'wpuqs_display_dashboard_widget'
            );
        }

        function wpuqs_display_dashboard_widget() {
            global $wpdb;
            $table_name = $wpdb->prefix . 'ulike';

            // Fetch likes from the past 30 days (top 5)
            $recent_likes = $wpdb->get_results("
                SELECT post_id, COUNT(*) as like_count
                FROM $table_name
                WHERE status = 'like'
                AND date_time >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY post_id
                ORDER BY like_count DESC
                LIMIT 5
            ");

            echo '<div style="padding: 10px;">';
            if ($recent_likes) {
                echo '<ul>';
                foreach ($recent_likes as $like) {
                    $post_title = get_the_title($like->post_id);
                    if (empty($post_title)) {
                        $post_title = esc_html__('(No Title)', 'wp-ulike-quick-stats');
                    }
                    echo '<li><a href="' . get_permalink($like->post_id) . '">' . esc_html($post_title) . '</a>: ' . $like->like_count . ' ' . esc_html__('Likes', 'wp-ulike-quick-stats') . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>' . esc_html__('No recent likes.', 'wp-ulike-quick-stats') . '</p>';
            }

            // Link to WP ULike statistics page
            echo '<p><a href="' . admin_url('admin.php?page=wp-ulike-statistics') . '">' . esc_html__('View Detailed Statistics', 'wp-ulike-quick-stats') . '</a></p>';
            echo '</div>';
        }

        add_action('wp_dashboard_setup', 'wpuqs_add_dashboard_widget');

        /**
         * Add admin toolbar menu
         */
        function wpuqs_add_to_admin_bar($wp_admin_bar) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'ulike';

            // Fetch today's likes
            $today_likes = $wpdb->get_var("
                SELECT COUNT(*)
                FROM $table_name
                WHERE status = 'like'
                AND DATE(date_time) = CURDATE()
            ");

            // Main toolbar node
            $wp_admin_bar->add_node([
                'id'    => 'wpuqs_stats',
                'title' => esc_html__('Likes', 'wp-ulike-quick-stats') . ': ' . ($today_likes ? $today_likes : '0'),
                'href'  => admin_url('admin.php?page=wp-ulike-statistics'),
                'meta'  => [
                    'title' => esc_html__('Check WP ULike Like Statistics', 'wp-ulike-quick-stats')
                ]
            ]);

            // Submenu: Top posts from the past 7 days (top 3)
            $top_posts = $wpdb->get_results("
                SELECT post_id, COUNT(*) as like_count
                FROM $table_name
                WHERE status = 'like'
                AND date_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY post_id
                ORDER BY like_count DESC
                LIMIT 3
            ");

            foreach ($top_posts as $post) {
                $post_title = get_the_title($post->post_id);
                if (empty($post_title)) {
                    $post_title = esc_html__('(No Title)', 'wp-ulike-quick-stats');
                }
                $wp_admin_bar->add_node([
                    'id'     => 'wpuqs_post_' . $post->post_id,
                    'title'  => esc_html($post_title) . ': ' . $post->like_count,
                    'href'   => get_permalink($post->post_id),
                    'parent' => 'wpuqs_stats'
                ]);
            }
        }

        add_action('admin_bar_menu', 'wpuqs_add_to_admin_bar', 100);
    }
});