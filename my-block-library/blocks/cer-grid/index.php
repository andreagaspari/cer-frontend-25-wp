<?php
// Blocca l'esecuzione del file se acceduto in maniera diretta
defined('ABSPATH') || exit;

/* Funzione di registrazione anonima */
add_action('init', function() {
    register_block_type(__DIR__);
});

/* Funzione di registrazione esplicita
function register_my_block() {
    register_block_type(__DIR__);
}
add_action('init', 'register_my_block');
*/