<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . '/style.css', array( 'hello-elementor','hello-elementor','hello-elementor-theme-style' ) );

        if ( is_product() ){
            wp_register_script( 'custom_script_js', get_stylesheet_directory_uri() . '/js/custom.js', array(), '1.2.0', 'true'  );
            wp_enqueue_script('custom_script_js');
        }
        
        if ( is_product_category() ){
            wp_register_script( 'ct_product_archive_js', get_stylesheet_directory_uri() . '/js/product_archive.js', array(), '1.0.42', 'true'  );
            wp_enqueue_script('ct_product_archive_js');
        }

    }

endif;

add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION


// DISPLAY THE CONTENT 
add_shortcode('ct_the_content','ct_the_content_func');

function ct_the_content_func() {
    $content = apply_filters( 'the_content', get_the_content() );
    return $content;
}

// Filter WP Query by Letters
function wpse_298888_posts_where( $where, $query ) {
    global $wpdb;

    $starts_with = esc_sql( $query->get( 'starts_with' ) );

    if ( $starts_with ) {
        $where .= " AND $wpdb->posts.post_title LIKE '$starts_with%'";
    }

    return $where;
}
add_filter( 'posts_where', 'wpse_298888_posts_where', 10, 2 );


// Display Treatments

add_shortcode('ct_treatments','ct_treatments_func');

function ct_treatments_func($atts) {

    $letter = $atts['letter'];
    $thumb = '';

    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'starts_with' => $letter
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {

        $content = '<div class="ct-treatment">';
        $content .= '<div class="ct-treatment-letter"><h3>List of <strong>'.$letter.'</strong></h3></div>';
        $content .= '<div class="ct-treatment-col">';

        while ($query->have_posts()) {
            $query->the_post();
            $price = get_post_meta( get_the_ID(), '_price', true );

            if (has_post_thumbnail()) {
                $thumb = get_the_post_thumbnail(get_the_ID(), array( 300, 300));
            } else {
                $thumb = '<img src="/wp-content/uploads/woocommerce-placeholder-300x300.png">';
            }

            $content .= '<div class="ct-treatment-list">';
                $content .= '<div class="ct-treatment-image">'.$thumb.'</div>';
                $content .= '<h6 class="ct-tr-price">from '.wc_price( $price ).'</h6>';
                $content .= '<h4>'.get_the_title().'</h4>';
                $content .= '<p>'.wp_trim_words(get_the_excerpt(), 30 ).'</p>';
                $content .= '<a href="' . get_the_permalink() .'" class="ct-btn-accent elementor-button-link elementor-button elementor-size-sm" role="button">
                                <span class="elementor-button-content-wrapper">
                                    <span class="elementor-button-text">View Product</span>
                                </span>
                            </a>';
            $content .= '</div>';

        }

        $content .= '</div>';
        $content .= '</div>';

    } 

    wp_reset_postdata();

    return $content;

}

// FILTER GET TERMS BY LETTERS

add_filter( 'terms_clauses', 'terms_clauses_47840519', 10, 3 );

function terms_clauses_47840519( $clauses, $taxonomies, $args ){
    global $wpdb;

    if( !isset( $args['__first_letter'] ) ){
        return $clauses;
    }

    $clauses['where'] .= ' AND ' . $wpdb->prepare( "t.name LIKE %s", $wpdb->esc_like( $args['__first_letter'] ) . '%' );

    return $clauses;

}


// Display Conditions

add_shortcode('ct_conditions','ct_conditions_func');

function ct_conditions_func($atts) {

    $content = '';

    $letter = $atts['letter'];

    $terms = get_terms( 'product_cat', 
        array(
            'orderby' => 'title',
            'order' => 'ASC',
            'hide_empty' => 0,
            '__first_letter' => $letter
        ) );

    if ( !empty( $terms ) ) {

        $content .= '<div class="ct-cl-container">';

        foreach ( $terms as $term ) {

            if( 0 != $term->parent ) {
                
                $content .= '<div class="ct-cl ct-min-desktop-display-flex">
                        <div class="ct-cl-content ct-min-desktop-display-flex">
                            <div>
                                <h4>'.$term->name.'</h4>
                                <p>'.$term->description.'</p>
                            </div>
                        </div>
                        <div class="ct-cl-btn">
                            <a href="' . get_term_link( $term ) .'" class="ct-btn-accent elementor-button-link elementor-button elementor-size-sm" role="button">
                                <span class="elementor-button-content-wrapper">
                                    <span class="elementor-button-text">View Products</span>
                                </span>
                            </a>
                        </div>
                    </div>';
            }
        }

        $content .= '</div>';

    }

    return $content;

}



// CUSTOM SINGLE PRODUCT ACCORDION 

function ct_product_accordion_function() {

    $content = '';

    $content .= '<div class="ct-tab-desc">'.get_the_content().'</div><br>';
    
    if( have_rows('faq') ):

        $count = 1;
        $active = 'elementor-active';
        $style = 'display: block;'; 

        $content .= '<style>.elementor-accordion{text-align:left}.elementor-accordion .elementor-accordion-item{border:1px solid #d4d4d4}.elementor-accordion .elementor-accordion-item+.elementor-accordion-item{border-top:none}.elementor-accordion .elementor-tab-title{margin:0;padding:15px 20px;font-weight:700;line-height:1;cursor:pointer;outline:none}.elementor-accordion .elementor-tab-title .elementor-accordion-icon{display:inline-block;width:1.5em}.elementor-accordion .elementor-tab-title .elementor-accordion-icon svg{width:1em;height:1em}.elementor-accordion .elementor-tab-title .elementor-accordion-icon.elementor-accordion-icon-right{float:right;text-align:right}.elementor-accordion .elementor-tab-title .elementor-accordion-icon.elementor-accordion-icon-left{float:left;text-align:left}.elementor-accordion .elementor-tab-title .elementor-accordion-icon .elementor-accordion-icon-closed{display:block}.elementor-accordion .elementor-tab-title .elementor-accordion-icon .elementor-accordion-icon-opened,.elementor-accordion .elementor-tab-title.elementor-active .elementor-accordion-icon-closed{display:none}.elementor-accordion .elementor-tab-title.elementor-active .elementor-accordion-icon-opened{display:block}.elementor-accordion .elementor-tab-content{display:none;padding:15px 20px;border-top:1px solid #d4d4d4}@media (max-width:767px){.elementor-accordion .elementor-tab-title{padding:12px 15px}.elementor-accordion .elementor-tab-title .elementor-accordion-icon{width:1.2em}.elementor-accordion .elementor-tab-content{padding:7px 15px}}</style>';

        $content .= '<div class="ct-product-accordion elementor-widget-container">';

        $content .= '<div class="elementor-accordion" role="tablist">';

        while( have_rows('faq') ) : the_row();

            $accordion_title = get_sub_field('title');
            $accordion_content = get_sub_field('content');

            $content .= '<div class="elementor-accordion-item">';

            $content .= '<div id="elementor-tab-title-'.$count.'" class="elementor-tab-title '.$active.'" data-tab="'.$count.'" role="tab" aria-controls="elementor-tab-content-'.$count.'" aria-expanded="true" tabindex="0" aria-selected="true">';

            $content .= '<span class="elementor-accordion-icon elementor-accordion-icon-left" aria-hidden="true">
            <span class="elementor-accordion-icon-closed"><i class="fas fa-plus"></i></span>
            <span class="elementor-accordion-icon-opened"><i class="fas fa-minus"></i></span>
            </span>
            <span class="elementor-accordion-title" href="">'.$accordion_title.'</span>';

            $content .= '</div>';


            $content .= '<div id="elementor-tab-content-'.$count.'" class="elementor-tab-content elementor-clearfix '.$active.'" data-tab="'.$count.'" role="tabpanel" aria-labelledby="elementor-tab-title-'.$count.'" style="'.$style.'">'.$accordion_content.'</div>';

            $content .= '</div>';

            $count++;
            $active = $style = '';

        endwhile;

        $content .= '</div>';

        $content .= '</div>';


    endif;

    echo $content;

}

add_filter( 'woocommerce_product_tabs', 'ct_update_woocommerce_description_tab' );

function ct_update_woocommerce_description_tab( $tabs ) {

    unset( $tabs['description'] );

    if (!empty(get_the_content()) || !empty(get_field('faq'))) {
        
        $tabs['attrib_desc_tab'] = array(
            'title'     => __( 'Description', 'woocommerce' ),
            'priority'  => 1,
            'callback'  => 'ct_product_accordion_function'
        );

    }

    return $tabs;
    
}

// END CUSTOM SINGLE PRODUCT ACCORDION 


// CUSTOM WIDGET SIDEBAR FOR CONSULTATION PAGE SINGLE PRODUCT DETAILS

add_action( 'woocommerce_before_single_variation', 'action_wc_before_single_variation' );

function action_wc_before_single_variation() {
    ?>
    <script type="text/javascript">

        jQuery(document).ready(function($) {

            // POPULATE VALUES TO CONSULTATION FORM FIELDS     

            $('form.variations_form').on('show_variation', function(event, data) {

                var variation_id = data.variation_id;
                var var_attr_obj = data.attributes;        // The variation attributes
                var var_price = data.display_price;   
                var image_id = data.image_id;   
                var attr_ext_obj = [];


                Object.entries(var_attr_obj).forEach(([key, value]) => {
                    var key = `${key}`;
                    var value = `${value}`;

                    var remove_text_attribute = key.replace('attribute_','');
                    var remove_pa_attribute = remove_text_attribute.replace('pa_','');
                    var remove_key_hypens = remove_pa_attribute.replace('-',' ');
                    
                    const lower = remove_key_hypens.toLowerCase();
                    var attribute_name = remove_key_hypens.charAt(0).toUpperCase() + lower.slice(1);

                    var remove_value_hypens = value.replace('-',' ');
                    const lower_value = remove_value_hypens.toLowerCase();
                    var value_name = remove_value_hypens.charAt(0).toUpperCase() + lower_value.slice(1);

                    attr_ext_obj.push('<li>'+attribute_name+'</li> <li><b>'+value_name+'</b></li>');
                });

                var selected_attributes = attr_ext_obj.join(' ');

                $(".var_id input").attr('value',variation_id);
                $('.ct_selected_attr').html(selected_attributes);
                $('.ct_order_total').html('<span class=\"price\"><span class=\"woocommerce-Price-amount amount\"><bdi><span class=\"woocommerce-Price-currencySymbol\">&pound;</span>'+var_price+'</bdi></span></span>');

            });

            var prod_id = $('.ct_product_id').html();
            $(".prod_id input").attr('value',prod_id);

        });
    </script>
    <?php
}

 
add_shortcode('ct_product_details','ct_product_details_func');

function ct_product_details_func() {
    $content = '';
    $content .= '<div class="ct_pop_attributes"><ul class="ct_selected_attr"></ul></div>';
    $content .= '<div class="ct_pop_order ct-flex"><h6 style="padding-right: 10px;">Total Order</h6><div class="ct_order_total"></div></div>';
    return $content;
}


// CONSULTATIONS HEADINGS

add_shortcode('ct_consultation_headings_two','ct_consultation_headings_two_func');

function ct_consultation_headings_two_func() {

    $content = '<div class="medsforless_popup_progress" id="medsforless_popup_progress">';

    if ( !is_user_logged_in() ) {
        $content .= '<div class="medsforless_popup_progress_item active">
            <div class="medsforless_popup_progress_icon"><div class="the_bubble">1</div></div>
            <div class="medsforless_popup_progress_text">Account Creation</div>
        </div>';
        // $content .= '<div class="medsforless_popup_progress_item">
        //     <div class="medsforless_popup_progress_icon"><div class="the_bubble">2</div></div>
        //     <div class="medsforless_popup_progress_text">General Questions</div>
        // </div>';
        $content .= '<div class="medsforless_popup_progress_item">
            <div class="medsforless_popup_progress_icon"><div class="the_bubble">2</div></div>
            <div class="medsforless_popup_progress_text">Condition Specific Questions</div>
        </div>';
        $content .= '<div class="medsforless_popup_progress_item">
            <div class="medsforless_popup_progress_icon"><div class="the_bubble">3</div></div>
            <div class="medsforless_popup_progress_text">Added to Basket</div>
        </div>';
    } else {
        $content .= '<div class="medsforless_popup_progress_item active">
            <div class="medsforless_popup_progress_icon"><div class="the_bubble">1</div></div>
            <div class="medsforless_popup_progress_text">Account Creation</div>
        </div>';
        // $content .= '<div class="medsforless_popup_progress_item active">
        //     <div class="medsforless_popup_progress_icon"><div class="the_bubble">2</div></div>
        //     <div class="medsforless_popup_progress_text">General Questions</div>
        // </div>';
        $content .= '<div class="medsforless_popup_progress_item active">
            <div class="medsforless_popup_progress_icon"><div class="the_bubble">2</div></div>
            <div class="medsforless_popup_progress_text">Condition Specific Questions</div>
        </div>';
        $content .= '<div class="medsforless_popup_progress_item">
            <div class="medsforless_popup_progress_icon"><div class="the_bubble">3</div></div>
            <div class="medsforless_popup_progress_text">Added to Basket</div>
        </div>';
    }
    
    $content .= '</div>';

    return $content;

}


add_shortcode('ct_checkout_button_two','ct_checkout_button_two_function');

function ct_checkout_button_two_function() {
    
    global $product;

    $product_id = get_the_ID();
    $content = '';

    if ( $product->is_type( 'variable' ) ) {
            
            $content .= '<div id="yesforms_checkout_wrapper" class="ct-quick-btn ct-quick-btn-two elementor-element elementor-element-7d10c1a elementor-widget elementor-widget-button yesforms_checkout_wrapper" data-id="7d10c1a" data-element_type="widget" data-widget_type="button.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-button-wrapper">
                                <button type="submit" href="#" class="elementor-button-link elementor-button elementor-size-sm" role="button">
                                        <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">Quick Checkout</span>
                                </span>
                                      </button>
                                </div>
                            </div>
                        </div>';
    } 

    return $content;

}

add_filter( 'woocommerce_product_single_add_to_cart_text', 'product_single_add_to_cart_text_filter_callback', 20, 2 );
function product_single_add_to_cart_text_filter_callback( $button_text, $product ) {
    $button_text = __("Quick Checkout", "woocommerce");
    
    return $button_text;
}


add_filter( 'wc_stripe_hide_payment_request_on_product_page', '__return_true' );

// View More Text button post count

add_shortcode('ct_view_more_product_text','ct_view_more_product_text_func');

function ct_view_more_product_text_func($atts) {

    $category_id = $atts['category_id'];
    $text = $atts['text'];
    $term = get_term( $category_id , 'product_cat' );
    $count = $term->count;
    $content = '';

    if ($count > 1) {
        $content .= $text.' ('.$count.')';
    } else {
        $content .= $text;
    }

    return $content;

}


// Filter view more top products

function my_query_by_post_meta( $query ) {

    $meta_query = $query->get( 'meta_query' );

    if ( ! $meta_query ) {
        $meta_query = [];
    }

    $meta_query[] = [
        'key' => 'total_sales',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
    ];

    $query->set( 'meta_query', $meta_query );

}

add_action( 'elementor/query/top_product', 'my_query_by_post_meta' );

// CONSULTATION PAGE FORM SHORTCODES CONDITIONS
add_shortcode('ct_consultation_forms','ct_consultation_forms_function');

function ct_consultation_forms_function($atts) {

    // $product_id = $_POST['ct_product_id'];
    $product_id = get_the_ID();
    $content .= '<div style="display: none;"> 
                    <span class="ct_product_id">'.$product_id.'</span>
                </div>';
    $terms  = get_the_terms( $product_id, 'product_cat' );
    $shortcode_arr = array();
    
    $productspecific = get_field( "condition_specific_questionaire_thisproduct", $product_id );
    $ignorecatform = get_field( "ignore_category_form", $product_id );
    $productspecific_exists = false;
    
    if(empty($ignorecatform) == true || isset($ignorecatform) == false || $ignorecatform == '') {
        $ignorecatform = "No";
    }
    
    // d($ignorecatform);
    
    if( $productspecific ) {
      if(empty($productspecific) == false && isset($productspecific) == true && $productspecific != '') {
          if(str_starts_with($productspecific, '[') && str_ends_with($productspecific, ']')) {
              $productspecific_exists = true;
          }
      }
    } else {
        $productspecific_exists = false;
    }

    if (!empty($terms)) {

       foreach ( $terms as $term ) {
           
           $conditionspecific = get_field('condition_specific_questionaire', $term);
           
           if(empty($conditionspecific) == false && isset($conditionspecific) == true && $conditionspecific != '') {
               
               if(str_starts_with($conditionspecific, '[') && str_ends_with($conditionspecific, ']') && $ignorecatform == "No") {
                  array_push($shortcode_arr, $conditionspecific);
               }

           }

        }

    } else {

        // $content .= '<h3>No specific question found!</h3>';

    }
    
    if($productspecific_exists == false) {
        if(empty($shortcode_arr) == false && isset($shortcode_arr) == true) {
            if(count($shortcode_arr) > 0) {
    
                if(count($shortcode_arr) > 1) {
                    $thelength = count($shortcode_arr);
                    $content .= do_shortcode($shortcode_arr[$thelength - 1]);
                } else {
                    $content .= do_shortcode($shortcode_arr[0]);
                }
                
                // d($shortcode_arr);
            } else {
                $content .= '<h3>No specific question found!</h3>';
            }
        } else {
            $content .= '<h3>No specific question found!</h3>';
        } 
    } else {
         $content .= do_shortcode($productspecific);
        //  d($productspecific);
    }
    
    return $content;

}

add_shortcode('medsforless_showfield','medsforless_showfield_func');

function medsforless_showfield_func($atts) {
    $result = '';
    $field_param = $atts['field'];
    $field_param_uid = $atts['uid'];
    
    if(empty($field_param_uid) == true) {
        if ( is_user_logged_in() ) {
            $the_userid = get_current_user_id();
            
            if($the_userid != 0) {
                $the_agefield = get_user_meta( $the_userid, 'user_registration_date_box_1655237392', true );
                $the_phonefield = get_user_meta( $the_userid, 'user_registration_number_box_1657308556', true );
                // echo d(get_user_meta($the_userid));
                
                if($field_param == 'birthday') {
                    if($the_agefield != false && $the_agefield != '' && empty($the_agefield) == false) {
                        $result = $the_agefield;
                    }
                } else if($field_param == 'phone') {
                    if($the_phonefield != false && $the_phonefield != '' && empty($the_phonefield) == false) {
                        $result = $the_phonefield;
                    }
                }
                
            }
        }
    } else {
        if($field_param_uid != '' && is_numeric($field_param_uid) == true) {
            $the_userid = intval($field_param_uid);
            
            if($the_userid != 0) {
                $the_agefield = get_user_meta( $the_userid, 'user_registration_date_box_1655237392', true );
                $the_phonefield = get_user_meta( $the_userid, 'user_registration_number_box_1657308556', true );
                    // echo d(get_user_meta($the_userid));
                    
                    if($field_param == 'birthday') {
                        if($the_agefield != false && $the_agefield != '' && empty($the_agefield) == false) {
                            $result = $the_agefield;
                        }
                    } else if($field_param == 'phone') {
                        if($the_phonefield != false && $the_phonefield != '' && empty($the_phonefield) == false) {
                            $result = $the_phonefield;
                        }
                    }
                    
            }
        }
        
    }
    
    
    
    return $result;
}

/** Add the text field as item data to the cart object **/

// add_action( 'gform_after_submission_2', 'set_post_content', 10, 2 );
// add_action( 'gform_after_submission_4', 'set_post_content', 10, 2 );
// add_action( 'gform_after_submission_5', 'set_post_content', 10, 2 );
add_action( 'gform_after_submission', 'set_post_content', 10, 2 );

function set_post_content( $entry, $form ) {

    global $woocommerce;
    session_start();

    $form_id = $form['fields']['0']['formId'];
    $form_title = $form['title'];
    $form_fields = $form['fields'];
    $form_confirmations = $form['confirmations'];
    $form_currentconfirmation = $form['confirmation'];
    $form_defaultconfirmation_id = '';
    $form_current_is_default = false;
    $product_id = -1;
    $variationid = -1;
    $count = 1;

    // echo $form_title;
    // d($form);
    // d($entry);
    // d($form_confirmations);
    // d($form_currentconfirmation);
    
    if(empty($form_confirmations) == false && isset($form_confirmations) == true) {
          if(count($form_confirmations) > 0) {
              foreach ($form_confirmations as $item) {
                // d($item);
                $current_isdefault = $item['isDefault'];
                $current_id = $item['id'];
                
                if($current_isdefault == true) {
                     $form_defaultconfirmation_id = $current_id;
                     //  echo 'Default ID: ' . $form_defaultconfirmation_id;
                }
              }
    
                   
          }
          
          if($form_defaultconfirmation_id != '' && empty($form_defaultconfirmation_id) == false && isset($form_defaultconfirmation_id) == true) {
              if(empty($form_currentconfirmation) == false && isset($form_currentconfirmation) == true) {
                  
                  if (strcmp($form_currentconfirmation['id'], $form_defaultconfirmation_id) !== 0) {
                      //not equal
                      // echo 'Message is not default';
                      $form_current_is_default = false;
                  } else {
                      //equal
                      // echo 'Message is default';
                      $form_current_is_default = true;
                  }
              }
          }
      
    }
    
    if($form_title != 'General Questions' && $form_title != 'Registration Form') {
        
        for($x = 0; $x < count($form_fields); $x++) {
            if($form_fields[$x]['label'] == 'prod_id') {
              $thefieldid = $form_fields[$x]['id'];
              $productid_field = $form_fields[$x]['id'];
              $product_id = rgar( $entry, strval($thefieldid));
              
            } else if($form_fields[$x]['label'] == 'var_id') {   
              $thefieldid = $form_fields[$x]['id'];
              $variationid_field = $form_fields[$x]['id'];
              $variationid = rgar( $entry, strval($thefieldid) );
            }
        }
        
        $quantity = 1;
        $found = false;
        
        $cart_item_data = $array_entry = array();
        
        for($y = 0; $y < count($form_fields); $y++) {
            if($form_fields[$y]['label'] != 'prod_id' && $form_fields[$y]['label'] != 'var_id' && $form_fields[$y]['label'] != 'Total') {
                $temp_field_id = $form_fields[$y]['id'];
                $temp_field_label = $form_fields[$y]['label'];
                
                $array_entry[$temp_field_label] = strval($temp_field_id);
            }
        }
        
        // d($array_entry);
        $combined_arr = array();
        $session_var = $_SESSION['medsforless_general_questions'];
        $session_var_count = $_SESSION['medsforless_general_count'];
        
        // d($session_var);
        // d($session_var_count);
        
        if(!empty($session_var) && !empty($session_var_count)) {
            $count = $session_var_count + 1;
        }
        
    
        foreach ($array_entry as $entry_key => $entry_val) {
    
            $q_item_count = 'q'.$count;
            $a_item_count = 'a'.$count;
        
            $field       = GFFormsModel::get_field( $form, $entry_val );
            $field_value = is_object( $field ) ? $field->get_value_export( $entry ) : '';
                
            $cart_item_data[$q_item_count] = $entry_key;
            $cart_item_data[$a_item_count] = $field_value;
            
            $count++;
        }
        
        if(!empty($session_var) && !empty($session_var_count)) {
            $combined_arr = array_merge($session_var, $cart_item_data);
        } else {
            $combined_arr = $cart_item_data;
        }
        
        // d($combined_arr);
    
        // Add variation and product to cart
        
        if($form_current_is_default == true) {
            WC()->cart->empty_cart();
          
             if ( ! WC()->cart->is_empty() ) {
                 
        
                foreach($woocommerce->cart->get_cart() as $cart_item_key => $values) {
                
                    if ($values['data']->id == $product_id) {
                        wc_add_notice( __('This product is already in cart (only one item is allowed).', 'woocommerce' ), 'error' );
        
                    } else {
                        $woocommerce->cart->add_to_cart( $product_id, $quantity, $variationid, null, $combined_arr ); 
                    }
        
                }
        
            } else {
                $woocommerce->cart->add_to_cart( $product_id, $quantity, $variationid, null, $combined_arr ); 
            }
        }
        
         
    } else {
        if($form_title == 'General Questions') {
            $cart_item_data = $array_entry = array();
            $quantity = 1;
            
            for($y = 0; $y < count($form_fields); $y++) {
                if($form_fields[$y]['label'] != 'prod_id' && $form_fields[$y]['label'] != 'var_id' && $form_fields[$y]['label'] != 'Total') {
                    $temp_field_id = $form_fields[$y]['id'];
                    $temp_field_label = $form_fields[$y]['label'];
                    
                    $array_entry[$temp_field_label] = strval($temp_field_id);
                }
            }
            
            // d($array_entry);
            
            foreach ($array_entry as $entry_key => $entry_val) {
        
                $q_item_count = 'q'.$count;
                $a_item_count = 'a'.$count;
        
                $field       = GFFormsModel::get_field( $form, $entry_val );
                $field_value = is_object( $field ) ? $field->get_value_export( $entry ) : '';
                
                $cart_item_data[$q_item_count] = $entry_key;
                $cart_item_data[$a_item_count] = $field_value;
                
                $count++;
            }
            
            unset($_SESSION['medsforless_general_questions']);
            unset($_SESSION['medsforless_general_count']);
            $_SESSION['medsforless_general_questions'] = $cart_item_data;
            $_SESSION['medsforless_general_count'] = count($array_entry);
            // d($_SESSION['medsforless_general_questions']);
            
            echo '<script>
                let medsforless_general = document.getElementById("medsforless_general");
	            let medsforless_condspec = document.getElementById("medsforless_condspec");
	            let medsforless_popup_progress = document.getElementById("medsforless_popup_progress");
	
                if(medsforless_general != null && medsforless_condspec != null) {
        	        if(medsforless_general.classList.contains("hidecheckoutstep") == false) {
        	            medsforless_general.classList.add("hidecheckoutstep");
        	        }
        	        
        	        if(medsforless_condspec.classList.contains("hidecheckoutstep") == true) {
        	            medsforless_condspec.classList.remove("hidecheckoutstep");
        	        }
    	        }
    	        
    	        if(medsforless_popup_progress != null) {
    	            let progressitems = medsforless_popup_progress.getElementsByClassName("medsforless_popup_progress_item");
    	            
    	            if(progressitems != null) {
    	              if(progressitems.length == 4) {
    	                progressitems[2].classList.add("active");
    	              }
    	            }
    	        }
            </script>';   
        }
    }
    

}


/**
 * Add custom field to order object
 */
function cfwc_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {

    
    $length = count($values);

    for ($i=1; $i <= $length ; $i++) { 
        if ( !empty( $values['a'.$i] ) ) {
            $item->add_meta_data( $values['q'.$i], $values['a'.$i] );
        }
     } 
    
}
add_action( 'woocommerce_checkout_create_order_line_item', 'cfwc_add_custom_data_to_order', 10, 4 );




// Different recipients based on product category in WooCommerce email notification

add_filter( 'woocommerce_email_recipient_new_order', 'custom_email_recipient_new_order', 10, 2 );

function custom_email_recipient_new_order( $recipient, $order ) {

    // Not in backend when using $order (avoiding an error)
    if( ! is_a($order, 'WC_Order') ) return $recipient;

    // Define the email recipients / categories pairs in the array
    $recipients_categories = array(
        'lacsamana.kelvin1@gmail.com'   => 'erectile-dysfunction',
        'lacsamana.kelvin.dev@gmail.com'   => 'acne-treatments',
    );

    // Loop through order items
    foreach ( $order->get_items() as $item ) {
        // Loop through defined product categories
        foreach ( $recipients_categories as $email => $category ) {
            if( has_term( $category, 'product_cat', $item->get_product_id() ) && strpos($recipient, $email) === false ) {
                $recipient .= ',' . $email;
            }
        }
    }

    return $recipient;

}

add_filter( 'woocommerce_add_to_cart_validation', 'remove_cart_item_before_add_to_cart', 20, 3 );
function remove_cart_item_before_add_to_cart( $passed, $product_id, $quantity ) {
    if( ! WC()->cart->is_empty() )
       WC()->cart->empty_cart();
       return $passed;
}

add_filter( 'user_registration_account_menu_items', 'ur_custom_menu_items', 10, 1 );
function ur_custom_menu_items( $items ) {
	$items['orders-item'] = __( 'Orders', 'user-registration' );
	return $items;
}

add_action( 'init', 'user_orders_my_account' );
function user_orders_my_account() {
	add_rewrite_endpoint( 'orders-item', EP_PAGES );
}

function user_orders_my_account_content() {
    $atts = array(
      "order_count" => "-1"    
    );
    
    
    if ( is_user_logged_in() ) {
        $the_userid = get_current_user_id();
        
        if($the_userid != 0) {
            $args = array(
                'customer_id' => $the_userid,
                'limit' => -1, // to retrieve _all_ orders by this user
            );
            
             $orders = wc_get_orders($args);
             
             if(count($orders) > 0) {
                 extract( shortcode_atts( array(
                    'order_count' => -1
                ), $atts ) );
        
                ob_start();
                wc_get_template( 'myaccount/my-orders.php', array(
                'current_user'  => get_user_by( 'id', get_current_user_id() ),
                'order_count'   => $order_count
                ));
                
                echo ob_get_clean();
             } else {
                 echo '<h2>No orders found...</h2>';
             }
        }
    }
    
	
}
add_action( 'user_registration_account_orders-item_endpoint', 'user_orders_my_account_content' );

add_shortcode('eccent_submenu','eccent_submenu_func');

function eccent_submenu_func($atts) {
    $parent_id = $atts['parent_id'];
    $parent_link_output = '';
    $result = '';
    $inner_items = '';
    
    $terms = get_terms( 'product_cat', 
        array(
            'orderby' => 'title',
            'order' => 'ASC',
            'hide_empty' => 0,
            'parent' => $parent_id
        )
    );
    
    if (!empty( $terms )) {
        foreach ( $terms as $term ) {
            $term_id = $term->term_id;
            $term_count = $term->count;
            $term_link = get_term_link( $term );
            $term_name = $term->name;
            $use_in_menu = get_field('use_in_menu', $term);
            $view_more = '';
            $products_output = '';
            
            if(empty($use_in_menu) == true || isset($use_in_menu) == false && $use_in_menu == '') {
                $use_in_menu = 'no';
            }
            
            if($use_in_menu == 'yes') {
                if($term_count != 0) {
                    
                    $args = array(
                        'posts_per_page' => 3, 
                        'post_type' => 'product',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field'    => 'id',
                                'terms'    => $term_id,
                            )
                        )
                    );
                
                    $products_array = array();
                    $the_query = new WP_Query( $args );

                    if ( $the_query->have_posts() ) {
    
                        while ( $the_query->have_posts() ) {
    
                            $the_query->the_post();
                            $the_product_string = '<a href="' . get_the_permalink() . '">' . get_the_title() . ', </a>';
                            
                            array_push($products_array, $the_product_string);
    
                        }
                    }
                    
                    wp_reset_postdata();
                    
                    if($term_count > 1) {
                        $view_more = '<a class="the_viewmore_button" href="' . $term_link . '">View More (' . $term_count . ')</a>';
                        array_push($products_array, $view_more);
                    } else {
                        $view_more = '<a class="the_viewmore_button" href="' . $term_link . '">View More</a>';
                        array_push($products_array, $view_more);
                    }
                    
                    if (!empty($products_array)) {
                        $products_output .= '<ul>';
                        
                        foreach ($products_array as $val ) {
                            $products_output .= '<li>' . $val . '</li>';
                        }
                        
                        $products_output .= '</ul>';        
                        $inner_items .= '<div class="eccent_megaSubMenu_item"><h5>' . $term_name . '</h5>' . $products_output . '</div>';
                        
                    }
                    
                }
            }
            
        }
        
        $parent_term = get_term_by('term_taxonomy_id', $parent_id, 'product_cat');
        
        if($parent_term != false) {
             $parent_term_count = $parent_term->count;
             $parent_term_link = get_term_link( $parent_term );
             $parent_link_output .= '<div class="eccent_megaSubMenu_viewall"><a href="' . $parent_term_link . '">View All (' . $parent_term_count . ')</a></div>';
        }
    }
    
    $result = '<div class="eccent_megaSubMenu">' . $inner_items . $parent_link_output . '</div>';
    return $result;
}