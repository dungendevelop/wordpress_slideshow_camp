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
            add_shortcode('campslider',array($this,'render_campslider'));
        }

        function render_campslider($atts,$content=null){
            $defaults = array(
                'id' => '',
                'design' => '',
                'dots' => '',
                'arrows' => 0,
                'sliderheight' => '',
                'autoplay' => 0,
                'autoplay_interval' => 5000,
                'speed' => 3000,
            );

            $atts = wp_parse_args( $atts, $defaults);
            if(!empty($atts['id'])){
                $content = '';
                $image_ids = get_post_meta($atts['id'],'_listing_image_id',true);
                if(!empty($image_ids) && count($image_ids)){
                    wp_enqueue_script( 'wp_slide_js', plugins_url( '../assets/js/lib/slick.min.js' , __FILE__ ) );
                    wp_enqueue_style( 'wp_slide_js', plugins_url( '../assets/css/lib/slick-theme.css' , __FILE__ ) );
                    wp_enqueue_script( 'wp-s-camp-js-front', plugins_url( '../assets/js/wp_slide_front.js' , __FILE__ ));
                    $content .= '<ul class="campslider" '.(!empty($atts['dots'])?'data-dots="1"':'').' '.(!empty($atts['arrows'])?'data-arrows="1"':'').' '.(!empty($atts['sliderheight'])?'data-sliderheight="'.$atts['sliderheight'].'"':'').' '.(!empty($atts['autoplay'])?'data-autoplay="1"':'').' '.(!empty($atts['autoplay_interval'])?'data-autoplay_interval="'.$atts['autoplay_interval'].'"':'').' '.(!empty($atts['speed'])?'data-speed="'.$atts['speed'].'"':'').'>';
                    foreach($image_ids as $image){
                        $url = wp_get_attachment_url($image);
                        $content .= '<li><img src="'.$url.'"></li>';
                    }
                    $content .= '</ul>';
                }
                return $content;
            }
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
            wp_enqueue_script( 'wp_slide_js', plugins_url( '../assets/js/wp_slide.js' , __FILE__ ) );
            wp_enqueue_style( 'wp_slide_css', plugins_url( '../assets/css/wp_slide.css' , __FILE__ ) );
            wp_nonce_field( 'campslider_security', 'campslider_security' );
            echo '<div id="camp_slider_images_box"><a class="button button-primary add_image">Add image</a>';
            echo '<li class="listingimagediv tobecloned"><span class="handle dashicons dashicons-menu"></span>';
            $content1 = '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:50x;border:0;display:none;" />';
            $content1 .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set listing image', 'text-domain' ) . '" href="javascript:;" class="button upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__( 'Choose an image', 'text-domain' ) . '" data-uploader_button_text="' . esc_attr__( 'Set listing image', 'text-domain' ) . '">' . esc_html__( 'Set listing image', 'text-domain' ) . '</a></p>';
            $content1 .= '<input type="hidden" class="upload_listing_image" name="_listing_cover_image[]" value="" /><span class="remove_slide dashicons dashicons-no"></span></li>';
            echo $content1;
            $content_width = 50;
            $image_ids = get_post_meta( $post->ID, '_listing_image_id', true );
            if(!empty($image_ids)){
               foreach($image_ids as $image_id){
                    
                    $content_width = 50;
                    if ( $image_id && get_post( $image_id ) ) {
                        echo '<li class="listingimagediv cloned"><span class="handle dashicons dashicons-menu"></span>';
                        $thumbnail_url = wp_get_attachment_url( $image_id, 'thumbnail' );
                        if ( ! empty( $thumbnail_url ) ) {
                            $content = '<img src="'.$thumbnail_url.'" width="50px">';
                            $content .= '<p class="hide-if-no-js"><a href="javascript:;" class="remove_listing_image_button button" >' . esc_html__( 'Remove listing image', 'text-domain' ) . '</a></p>';
                            $content .= '<input type="hidden" class="upload_listing_image" name="_listing_cover_image[]" value="' . esc_attr( $image_id ) . '" /><span class="remove_slide dashicons dashicons-no"></span></li>';
                        }
                    } 
                    echo $content;
                }
            }
            echo '</div>'; 
        }
         
      
        function wp_slide_save_meta_box( $post_id ) {
            // Save logic goes here. Don't forget to include nonce checks!
            if ( ! isset( $_POST['campslider_security'] ) || ! wp_verify_nonce( $_POST['campslider_security'], 'campslider_security' ) ) {
                return;
            }
            elseif(isset( $_POST['campslider_security'] ) && wp_verify_nonce( $_POST['campslider_security'], 'campslider_security' ) && !isset( $_POST['_listing_cover_image'] ) ) {
                update_post_meta( $post_id, '_listing_image_id', '' );

            }elseif(isset( $_POST['campslider_security'] ) && wp_verify_nonce( $_POST['campslider_security'], 'campslider_security' )  && isset( $_POST['_listing_cover_image'] )){
                $data = $_POST['_listing_cover_image'];
            
                $image_ids = array();
                foreach($data as $id){
                    if(!empty($id)){
                        $image_ids[]=$id;
                    }
                }
                update_post_meta( $post_id, '_listing_image_id', $image_ids );
            }
        }
        
        public function activate(){
        	// ADD Custom Code which you want to run when the plugin is activated
        }
        public function deactivate(){
               	
        }
        
        
        // ADD custom Code in clas
        
    } // END class Wp_Slideshow_Class
} // END if(!class_exists('Wp_Slideshow_Class'))
