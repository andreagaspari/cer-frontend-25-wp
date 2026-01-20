<?php
    /**
     * Plugin Name: I Miei Shortcodes
     * Plugin URI: https://...
     * Description: Una libreria di Shortcode
     * Version: 0.0.01
     * Requires at least: 6.5
     * Requires PHP: 7.4
     * Author: Andrea Gaspari
     * Author URI: https://...
     * License: GPL2
     * Text Domain: myshortcodes
     */

    function stampa_nome_utente() {
        if (is_user_logged_in()) :
            $user = wp_get_current_user();
            ob_start();
                echo "Bentornato, ";
                echo $user->data->user_nicename;
            return ob_get_clean();
        endif;
    }
    add_shortcode('nome_utente', 'stampa_nome_utente');

    function countdown_shortcode_assets() {
        /*
         * Registra CSS passando il percorso:
         *  - get_stylesheet_directory_uri() -> Percorso al tema attivo
         *  - get_template_directory_uri() -> Percorso al tema genitore
         *  - plugin_dir_url() -> Percorso alla cartella del plugin
         */
        wp_register_style(
            'countdown', 
            plugin_dir_url(__FILE__) . "assets/css/countdown.css",
            array(),
            null
        );
        wp_register_script(
            'countdown', 
            plugin_dir_url(__FILE__) . "assets/js/countdown.js",
            array(
                'jquery'
            ),
            null,
            array(
                'strategy' => 'defer', // async
                'in_footer' => true
            )
        );
    }
    add_action('wp_enqueue_scripts', 'countdown_shortcode_assets');

    function countdown_shortcode($attributes) {
        if (!array_key_exists('date', $attributes))
            return; 

        wp_enqueue_style('countdown');
        wp_enqueue_script('countdown');
        $format = (array_key_exists('format', $attributes)) ? $attributes['format'] : '%a giorni';

        $event_date = $attributes['date'];

        $today = new DateTime();
        $event = new DateTime($event_date);

        $diff = $event->diff($today);

        return "<div class='countdown'>Mancano " . $diff->format($format) . " all'evento.</div>";
    }
    add_shortcode('countdown', 'countdown_shortcode');

    function wrap_in_code($attributes, $content) {
        extract(shortcode_atts(array(
            'class' => ''
        ), $attributes));

        return "<code class='".$class."'>".$content."</code>";
    }
    add_shortcode('wrap_code', 'wrap_in_code');