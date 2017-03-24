<?php

if(!class_exists('Wp_Slideshow_Class'))
{   
    class Wp_Slideshow_Class  // We'll use this just to avoid function name conflicts 
    {
            
        public static $instance;
    
        public static function init(){
            if ( is_null( self::$instance ) )
                self::$instance = new Wp_Slideshow_Class();
            return self::$instance;
        }

        private function __construct(){
            add_action('init',array($this,'register_slider_post_type'));
            add_action( 'add_meta_boxes',array($this, 'wp_slide_register_meta_boxes' ));
            add_action( 'save_post', array($this,'wp_slide_save_meta_box' ));
        }

        function register_slider_post_type(){
            register_post_type( 'campslider',
                array(
                    'labels' => array(
                        'name' => __('Slider','wp-slide-camp'),
                        'menu_name' => __('Slider','wp-slide-camp'),
                        'singular_name' => __('Slider','wp-slide-camp'),
                        'add_new_item' => __('Add New Slider','wp-slide-camp'),
                        'all_items' => __('Sliders','wp-slide-camp')
                    ),
                    'publicly_queryable' => true,
                    'show_ui' => true,
                    'exclude_from_search' => true,
                    'has_archive' => false,
                    'query_var'   => false,
                    'show_in_nav_menus' => false,
                    'supports' => array( 'title','editor'),
                    'hierarchical' => false,
                    'rewrite' => array( 'slug' => 'campslider', 'hierarchical' => false, 'with_front' => false )
                )
             );
        }

        function wp_slide_register_meta_boxes() {
            add_meta_box( 'wp-slide', __( 'Choose images', 'wp-slide-camp' ), array
                ($this,'wp_slide_my_display_callback'), 'campslider' );
        }
        
         
     
        function wp_slide_my_display_callback( $post ) {
            // Display code/markup goes here. Don't forget to include nonces!
        }
         
      
        function wp_slide_save_meta_box( $post_id ) {
            // Save logic goes here. Don't forget to include nonce checks!
        }
        
        public function activate(){
        	// ADD Custom Code which you want to run when the plugin is activated
        }
        public function deactivate(){
               	
        }
        
        
        // ADD custom Code in clas
        
    } // END class Wp_Slideshow_Class
} // END if(!class_exists('Wp_Slideshow_Class'))
