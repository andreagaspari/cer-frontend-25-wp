<?php 

if (!function_exists('cer_theme_setup')) :
    /**
     * Theme Setup: Set language domain and load translations from theme_folder/languages.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    function cer_theme_setup() {

        /*** Set and load language domain ***/
            if (!defined('LANG_DOMAIN')) {
                define('LANG_DOMAIN', 'ilmiotema');
            }

            load_theme_textdomain(LANG_DOMAIN, get_template_directory() . '/languages');
        /*** END - Set and load language domain ***/
    }
    add_action('after_setup_theme', 'cer_theme_setup');

endif;

/*** SVG SUPPORT ***/
	
    if (!function_exists('cer_add_svg_mime_type')) :
        
        /**
         * Add image/svg+xml Mime Type.
         * 
         * @since 1.0.0
         * 
         * @param array $mimes The core mime types.
         * 
         * @return array $mimes Updated
         */
        function cer_add_svg_mime_type($mimes) {
            $mimes['svg'] = 'image/svg+xml';
            return $mimes;
        }
        add_filter('upload_mimes', 'cer_add_svg_mime_type', 10, 1);

    endif;

    if (!function_exists('cer_svg_secondary_mime_type')) :

        /**
         * Support for 'image/svg' as the secondary mime type of .svg files, in addition to the default 'image/svg+xml' support.
         * 
         * @since 1.0.1
         * 
         * @param array $check The checked mime types.
         * @param string $file The file path.
         * @param string $filename The file name.
         * @param array $mimes The mime types.
         * 
         * @return array $check Updated
         */
        function cer_svg_secondary_mime_type( $check, $file, $filename, $mimes ) {
            if ( empty( $check['ext'] ) && empty( $check['type'] ) ) :
                // Adjust to your needs!
                $secondary_mime = [ 'svg' => 'image/svg' ];
                
                // Run another check, but only for our secondary mime and not on core mime types.
                remove_filter( 'wp_check_filetype_and_ext', 'cer_svg_secondary_mime_type', 99, 4 );
                $check = wp_check_filetype_and_ext( $file, $filename, $secondary_mime );
                add_filter( 'wp_check_filetype_and_ext', 'cer_svg_secondary_mime_type', 99, 4 );
            endif;
            return $check;
        }
        add_filter( 'wp_check_filetype_and_ext', 'cer_svg_secondary_mime_type', 99, 4 );    

    endif;

    if (!function_exists('cer_fix_wp_get_attachment_image_svg')) :
        
        /**
         * Fix Width/Height for SVG Rendering.
         * 
         * @since 1.0.0
         * 
         * @param array $image  The image source.
         * @param int $attachment_id The attachment ID.
         * @param string|array $size The size of the image.
         * @param bool $icon Whether the image is an icon.
         * 
         * @return array $image Updated
         */
        function cer_fix_wp_get_attachment_image_svg($image, $attachment_id, $size, $icon) {
            if (is_array($image) && preg_match('/\.svg$/i', $image[0]) && $image[1] <= 1) { 
                if (is_array($size)) { 
                    $image[1] = $size[0]; 
                    $image[2] = $size[1]; 
                } elseif (($xml = simplexml_load_file($image[0])) !== false) {
                    $attr = $xml->attributes();
                    $viewbox = explode(' ', $attr->viewBox);
                    $image[1] = isset($attr->width) && preg_match('/\d+/', $attr->width, $value) ? (int) $value[0] : (count($viewbox) == 4 ? (int) $viewbox[2] : null);
                    $image[2] = isset($attr->height) && preg_match('/\d+/', $attr->height, $value) ? (int) $value[0] : (count($viewbox) == 4 ? (int) $viewbox[3] : null);
                } else {
                    $image[1] = $image[2] = null;
                }
            }
            return $image;
        }
        add_filter( 'wp_get_attachment_image_src', 'cer_fix_wp_get_attachment_image_svg', 10, 4 );

    endif;

/*** END - SVG Support ***/



/*** CUSTOM SCRIPTS AND STYLES ***/
if (!function_exists('cer_enqueue_scripts')) :    

    /**
     * Enqueue Script and Styles.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    function cer_enqueue_scripts() {

        /**
         * Register style.css file in theme root to be used with wp_enqueue_style() function.
         * @see https://developer.wordpress.org/reference/functions/wp_register_style/
         */
        /*
            wp_register_style(LANG_DOMAIN, get_stylesheet_uri(), array(), false, 'all');
        */

        /**
         * Enqueue style.css file in theme root in all pages.
         * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
         */
        wp_enqueue_style(LANG_DOMAIN, get_stylesheet_uri(), array(), false, 'all');
        

        /**
         * Enqueue home.css file in the home page. 
         * @see https://developer.wordpress.org/reference/functions/is_home/
         */
        /*
            if (is_home()) {
                wp_enqueue_style( 'home', get_theme_file_uri( 'assets/css/home.css'), false, null, 'all' );
            }
        */

        /**
         * Enqueue a css file in a specific page, in a list of pages or in all pages.
         * @see https://developer.wordpress.org/reference/functions/is_page/
         * 
         * @param int|string|array[int|string] $page Page ID, title, slug, or array of such. Default is ''. (Optional)
         */
        /*
            if (is_page($page)) {
                wp_enqueue_style( 'page', get_theme_file_uri( 'assets/css/page.css'), false, null, 'all' );
            }
        */

        /** */

        /**
         * Enqueue a CSS file in a specific post, in a list of posts or in all posts (post and custom post types).
         * @see https://developer.wordpress.org/reference/functions/is_single/
         * 
         * @param int|string|array[int|string] $post Post ID, title, slug, or array of such. Default is ''. (Optional)
         */
        /*
            if (is_single($post)) {
                wp_enqueue_style( 'single', get_theme_file_uri( 'assets/css/single.css'), false, null, 'all' );
            }
        */

        /**
         * Enqueue a CSS file in a single post of a specific post type, a list of post types or in all posts (post, attachment, page and custom post types).
         * @see https://developer.wordpress.org/reference/functions/is_singular/
         * 
         * @param string|array[string] $post_types Post type or array of post types. Default is ''. (Optional)
         */
        /*
            if (is_singular($post_types)) {
                wp_enqueue_style( 'post-types', get_theme_file_uri( 'assets/css/templates/post-type.css'), false, null, 'all' );
            }
        */

        /**
         * Enqueue a CSS file in an archive page.
         * @see https://developer.wordpress.org/reference/functions/is_archive/
         */
        /*
            if (is_archive()) {
                wp_enqueue_style( 'archive', get_theme_file_uri( 'assets/css/archive.css'), false, null, 'all' );
            }
        */

        /**
         * Enqueue a CSS file in a specific post type archive, in a list of post type archives or in all post type archives.
         * @see https://developer.wordpress.org/reference/functions/is_post_type_archive/
         * 
         * @param string|array[string] $post_types Post type or array of post types. Default is ''. (Optional)
         */
        /*
            if (is_post_type_archive($post_types)) {
                wp_enqueue_style( 'post-type-archive', get_theme_file_uri( 'assets/css/post-type-archive.css'), false, null, 'all' );
            }
        */

        /**
         * Enqueue a CSS file in all archives of a specific post type (All, Category, Tag, Author, Date, etc.).
         * 
         * @param string $post_types Post type or array of post types. 
         */
        /*
            if (get_post_type() == 'post' && !is_single()) {
                wp_enqueue_style( 'post-type-archives', get_theme_file_uri( 'assets/css/post-type-archives.css'), false, null, 'all' );
            }
        */

        /**
         * Enqueue a CSS file in a specific category, in a list of categories or in all categories.
         * @see https://developer.wordpress.org/reference/functions/is_category/
         * 
         * @param int|string|array[int|string] $category Category ID, name, slug, or array of such. Default is ''. (Optional)
         */
        /*
            if (is_category($category)) {
                wp_enqueue_style( 'category', get_theme_file_uri( 'assets/css/category.css'), false, null, 'all' );
            }
        */

        /**
         * Enqueue a CSS file in a specific tag, in a list of tags or in all tags.
         * @see https://developer.wordpress.org/reference/functions/is_tag/
         * 
         * @param int|string|array[int|string] $tag Tag ID, name, slug, or array of such. Default is ''. (Optional)
         */
        /*
            if (is_tag($tag)) {
                wp_enqueue_style( 'tag', get_theme_file_uri( 'assets/css/tag.css'), false, null, 'all' );
            }
        */

        /**
         * Enqueue a CSS file in a specific author, in a list of authors or in all authors.
         * @see https://developer.wordpress.org/reference/functions/is_author/
         * 
         * @param int|string|array[int|string] $author Author ID, name, nicename, or array of such. Default is ''. (Optional)
         */
        /*
            if (is_author($author)) {
                wp_enqueue_style( 'author', get_theme_file_uri( 'assets/css/author.css'), false, null, 'all' );
            }
        */

        /**
         * Enqueue a CSS file in a specific term of a taxonomy (or a list of taxonomies), in a list of terms of a taxonomy (or a list of taxonomies) or in all terms of a taxonomy (or a list of taxonomies).
         * @see https://developer.wordpress.org/reference/functions/is_tax/
         * 
         * @param string|array[string] $taxonomy Taxonomy key or array of taxonomy keys. Default is ''. (Optional)
         * @param int|string|array[int|string] $term Term ID, name, slug, or array of such. Default is ''. (Optional)
         */
        /*
            if (is_tax($taxonomy, $term)) {
                wp_enqueue_style( 'term', get_theme_file_uri( 'assets/css/term.css'), false, null, 'all' );
            }
        */

        /**
         * Enqueue a CSS file in a search result page.
         * @see https://developer.wordpress.org/reference/functions/is_search/
         * 
         * @param string $search Search string. Default is ''. (Optional)
         */
        /*
            if (is_search($search)) {
                wp_enqueue_style( 'search', get_theme_file_uri( 'assets/css/search.css'), false, null, 'all' );
            }
        */

        /**
         * Enqueue a CSS file in a 404 page.
         * @see https://developer.wordpress.org/reference/functions/is_404/
         */
        /*
            if (is_404()) {
                wp_enqueue_style( '404', get_theme_file_uri( 'assets/css/404.css'), false, null, 'all' );
            }
        */

        /**
         * Enqueue a CSS file in a specific page template, in a list of page templates or in all page templates.
         * @see https://developer.wordpress.org/reference/functions/is_page_template/
         * 
         * @param string|array[string] $template Page template or array of page templates. Default is ''. (Optional)
         */
        /*
            if (is_page_template($template) {
                wp_enqueue_style( 'template', get_theme_file_uri( 'assets/css/template.css'), false, null, 'all' );
            }
        */

    }
    add_action( 'wp_enqueue_scripts', 'cer_enqueue_scripts' );

endif;
/*** END - Custom Scripts and Styles***/

/*** CUSTOM POST TYPES AND TAXONOMIES ***/
if (!function_exists('cer_register_post_types_and_taxonomies')) :

    /** 
     * Register custom post types and taxonomies
     * 
     * @since 1.1.0
     * 
     * @return void
     */
    function cer_register_post_types_and_taxonomies() {
        
        /**
         * Register a custom post type.
         * @see https://developer.wordpress.org/reference/functions/register_post_type/
         * 
         * @param string $post_type Post type key, must not exceed 20 characters.
         * @param array $args Array of arguments for registering a post type.
         */
        /* 
            register_post_type('progetto', array(
                'labels' => array(
                    'name' => __('Progetti', LANG_DOMAIN),
                    'singular_name' => __('Progetto', LANG_DOMAIN),
                    'add_new' => __('Aggiungi nuovo', LANG_DOMAIN),
                    'add_new_item' => __('Aggiungi nuovo progetto', LANG_DOMAIN),
                    'edit_item' => __('Modifica progetto', LANG_DOMAIN),
                    'new_item' => __('Nuovo progetto', LANG_DOMAIN),
                    'view_item' => __('Visualizza progetto', LANG_DOMAIN),
                    'view_items' => __('Visualizza progetti', LANG_DOMAIN),
                    'search_items' => __('Cerca progetto', LANG_DOMAIN),
                    'not_found' => __('Nessun progetto trovato', LANG_DOMAIN),
                    'not_found_in_trash' => __('Nessun progetto trovato nel cestino', LANG_DOMAIN),
                    'all_items' => __('Tutti i progetti', LANG_DOMAIN),
                    'archives' => __('Archivio progetti', LANG_DOMAIN),
                    'attributes' => __('Attributi progetto', LANG_DOMAIN),
                    'insert_into_item' => __('Inserisci in progetto', LANG_DOMAIN),
                    'uploaded_to_this_item' => __('Caricato in questo progetto', LANG_DOMAIN),
                    'filter_items_list' => __('Filtra lista progetti', LANG_DOMAIN),
                    'items_list_navigation' => __('Navigazione lista progetti', LANG_DOMAIN),
                    'items_list' => __('Lista progetti', LANG_DOMAIN),
                    'item_published' => __('Progetto pubblicato', LANG_DOMAIN),
                    'item_published_privately' => __('Progetto pubblicato privatamente', LANG_DOMAIN),
                    'item_reverted_to_draft' => __('Progetto ripristinato', LANG_DOMAIN),
                    'item_scheduled' => __('Progetto programmato', LANG_DOMAIN),
                    'item_updated' => __('Progetto aggiornato', LANG_DOMAIN),
                ),
                'public' => true,
                'menu_icon' => 'dashicons-portfolio',
                'has_archive' => true,
                'rewrite' => array('slug' => 'progetti'),
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes'),
                'show_in_rest' => true,
                'taxonomies' => array('progetto_category')
            ) );
        */

        /**
         * Register a custom taxonomy.
         * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
         * 
         * @param string $taxonomy Taxonomy key, must not exceed 32 characters.
         * @param array $object_type Name of the object type for the taxonomy object.
         * @param array $args Array of arguments for registering a taxonomy.
         */
        /* 
            register_taxonomy('progetto_category', 'progetto', array(
                'labels' => array(
                    'name' => __('Categorie progetti', LANG_DOMAIN),
                    'singular_name' => __('Categoria progetto', LANG_DOMAIN),
                    'search_items' => __('Cerca categorie progetti', LANG_DOMAIN),
                    'all_items' => __('Tutte le categorie progetti', LANG_DOMAIN),
                    'parent_item' => __('Categoria progetto padre', LANG_DOMAIN),
                    'parent_item_colon' => __('Categoria progetto padre:', LANG_DOMAIN),
                    'edit_item' => __('Modifica categoria progetto', LANG_DOMAIN),
                    'update_item' => __('Aggiorna categoria progetto', LANG_DOMAIN),
                    'add_new_item' => __('Aggiungi nuova categoria progetto', LANG_DOMAIN),
                    'new_item_name' => __('Nuova categoria progetto', LANG_DOMAIN),
                    'menu_name' => __('Categorie progetti', LANG_DOMAIN),
                ),
                'public' => true,
                'hierarchical' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud' => true,
            ) );
        */    
    }
    add_action( 'init', 'cer_register_post_types_and_taxonomies' );

endif;
/*** END - Custom Post Types and Taxonomies ***/

/*** CUSTOM BLOCK STYLES ***/
if (!function_exists('cer_register_block_styles')) :
    
    /**
     * Register block styles
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    function cer_register_block_styles() {

        if ( !function_exists( 'register_block_style' ) ) {
            return;
        }

        /**
         * Register an external CSS for a registered block.
         * @see https://developer.wordpress.org/reference/functions/wp_enqueue_block_style/
         * 
         * @param string $block_name Block name.
         * @param array $args Array of arguments.
         */
        /*
            wp_enqueue_block_style( 'core/button', array(
                'handle'=> 'core-button',
                'src'   => get_theme_file_uri( 'assets/css/blocks/core-button.css' ),
                'deps'  => array(),
                'ver'   => null,
                'media' => 'all'
            ) );
        */

        /**
         * Register a new block style for an existing block with an inline CSS.
         * @see https://developer.wordpress.org/reference/functions/register_block_style/
         * 
         * @param string $block_name Block name.
         * @param array $args Array of arguments.
         * @param string $args['name'] Block style name.
         * @param string $args['label'] Block style label.
         * @param string $args['inline_style'] Block style inline CSS.
         */
        /*
            register_block_style( 'core/button', array(
                'name'         => 'blue',
                'label'        => __( 'Blue', LANG_DOMAIN ),
                'inline_style' => '.wp-block-button.is-style-blue .wp-element-button { background-color: var(--wp--preset--color--blue) } .wp-block-button.is-style-blue .wp-element-button:hover { background-color: var(--wp--preset--color--blue-semitransparent); }'
            ) );
        */
        register_block_style('core/paragraph', array(
            'name' => 'testo-blu',
            'label' => 'Testo Blu',
            'inline_style' => 'p.is-style-testo-blu { color: blue; }'
        ));

        /**
         * Register a new block style for an existing block with an external CSS
         * 
         * @param string $block_name Block name.
         * @param array $args Array of arguments.
         * @param string $args['name'] Block style name.
         * @param string $args['label'] Block style label.
         * @param string $args['style_handle'] Block style handle.
         */
        /*
            wp_enqueue_block_style( 'core/cover', array(
                'handle' => 'core-cover-nobg-mobile',
                'src'    => get_theme_file_uri( 'assets/css/blocks/core-cover-nobg-mobile.css' ),
                'ver'	=> null
            ) );
            register_block_style('core/cover',
                array(
                    'name'	=> 'nobg-mobile',
                    'label' => __( 'Nascondi sfondo da mobile', LANG_DOMAIN ),
                    'style_handle' => 'core-cover-nobg-mobile'
                )
            );
        */
        wp_enqueue_block_style('core/paragraph', array(
            'handle' => 'testo-rosso',
            'src' => get_theme_file_uri('testo-rosso.css'),
            'deps'  => array(),
            'ver'   => null,
            'media' => 'all'
        ));
        register_block_style('core/paragraph', array(
            'name' => 'testo-rosso',
            'label' => 'Testo Rosso'
        ));

    }
    add_action('init','cer_register_block_styles');

endif;
/*** END - Custom Block Styles ***/
