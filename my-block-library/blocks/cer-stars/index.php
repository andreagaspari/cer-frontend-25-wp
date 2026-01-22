<?php
// Esci se accesso diretto
defined('ABSPATH') || exit;

/**
 * Registra il blocco su Wordpress
 */
add_action('init', function() {
	register_block_type(__DIR__);
});