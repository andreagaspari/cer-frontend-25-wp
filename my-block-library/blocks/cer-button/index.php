<?php
/**
 * Gutenberg Boilerplate Block
 * 
 * @package Immaginificio
 * @since 1.0.0
 */
defined('ABSPATH') || exit;

add_action('init', function() {
	register_block_type(__DIR__);
});