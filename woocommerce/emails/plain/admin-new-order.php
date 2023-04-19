<?php
/**
 * Admin new order email (plain text)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/admin-new-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails\Plain
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$payment_date = $order->get_date_paid();
$order_items = $order->get_items();
$order_uid = $order->get_user_id();
$order_billingAddress = '';
$order_billingEmail = $order->get_billing_email();
$order_shippingAddress = '';
$order_billingFullName = $order->get_formatted_billing_full_name();
$order_shippingFullName = $order->get_formatted_shipping_full_name();
$order_shippingMethod = $order->get_shipping_method();

$order_fname = '';
$order_lname = '';
$order_userdata = '';

$currency_code = $order->get_currency();
$currency_symbol = get_woocommerce_currency_symbol( $currency_code );

// if (strpos($order_billingAddress, $order_billingFullName) !== false) {
//   $order_billingAddress = str_replace($order_billingFullName, "", $order_billingAddress);
// }

// if (strpos($order_shippingAddress, $order_shippingFullName) !== false) {
//   $order_shippingAddress = str_replace($order_shippingFullName, "", $order_shippingAddress);
// }

if(empty($order->get_billing_address_2()) == false) {
    $order_billingAddress = $order->get_billing_address_1() . ', ' . $order->get_billing_address_2() . ', ' . $order->get_billing_city() . ', ' . $order->get_billing_state() . ', ' . $order->get_billing_postcode() . ', ' . $order->get_billing_country();
    
} else {
    $order_billingAddress = $order->get_billing_address_1() . ', ' . $order->get_billing_city() . ', ' . $order->get_billing_state() . ', ' . $order->get_billing_postcode() . ', ' . $order->get_billing_country();
}

if(empty($order->get_shipping_address_2()) == false) {
    $order_shippingAddress = $order->get_shipping_address_1() . ', ' . $order->get_shipping_address_2() . ', ' . $order->get_shipping_city() . ', ' . $order->get_shipping_state() . ', ' . $order->get_shipping_postcode(). ', ' . $order->get_shipping_country();
    
} else {
    $order_shippingAddress = $order->get_shipping_address_1() . ', ' . $order->get_shipping_city() . ', ' . $order->get_shipping_state() . ', ' . $order->get_shipping_postcode(). ', ' . $order->get_shipping_country();
}

if(empty($order_uid) == false && $order_uid != '' && $order_uid != 0) {
    $order_userdata = get_userdata( $order_uid );
    
    if($order_userdata != false) {
        $order_fname = $order_userdata->first_name;
        $order_lname = $order_userdata->last_name;
    } else {
        $order_fname = $order->get_billing_first_name();
        $order_lname = $order->get_billing_last_name();
    }
    
} else {
    $order_fname = $order->get_billing_first_name();
    $order_lname = $order->get_billing_last_name();
}

echo '> ' . esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n";
echo "> Patient First Name: " . esc_html($order_fname);
echo "\n";
echo "> Patient Last Name: " . esc_html($order_lname);
echo "\n";
echo "> Date Of Birth: " . esc_html( do_shortcode('[medsforless_showfield field="birthday" uid="' . $order_uid . '"]') );
echo "\n";
echo '> [ORDER #' . $order->get_id() . ']';
echo "\n";

if(empty($payment_date) == false && isset($payment_date)) {
    echo '> DATE: (' . $payment_date->format ('Y-m-d') . ')';
} else {
    echo '> DATE: No Transaction Date Available.  This could be a bank transfer.';
}

echo "\n" . ">" . "\n" . ">" . "\n";

foreach ( $order_items as $item_id => $item ) {
    $current_product = $item->get_product();
    $current_product_name = $item->get_name();
    $current_quantity = $item->get_quantity();
    $current_total = $item->get_total();
    $current_allmeta = $item->get_meta_data();
    $formatted_allmeta = $item->get_formatted_meta_data();
    $meta_to_remove = array();
    $separated_meta = array();
    
    echo "> Product: " . $current_product_name . "\n";
    echo "> Quantity: " . $current_quantity . "\n";
    echo "> Price: " . $currency_symbol . $current_total . "\n";
    // echo var_dump((array)($current_allmeta[0])) . "\n";
    // echo var_dump($formatted_allmeta);
    
    if ( 'variation' === $current_product->get_type() ) {
        $variation_id = $item->get_variation_id();
        $variation    = new WC_Product_Variation( $variation_id );
        $attributes   = $variation->get_attributes();
        // echo var_dump($attributes);
        foreach ( $attributes as $attribute => $value ) {
            $label = wc_attribute_label($attribute);
            $name = term_exists( $value, $attribute ) ? get_term_by( 'slug', $value, $attribute )->name : $value;
             
            echo ">" . $label . ": " . $name . "\n";
            array_push($meta_to_remove, $attribute);
        }
    }
    
     echo ">" . "\n" . ">" . "\n";
    // echo var_dump($meta_to_remove);
    
    foreach ( $formatted_allmeta as $meta_item) {
        $current_meta_item = (array)$meta_item;
        $contains_variation = false;
        // echo var_dump($current_meta_item);
        for($y = 0; $y < count($meta_to_remove); $y++) {
            if($meta_to_remove[$y] == $current_meta_item["key"]) {
                $contains_variation = true;
            }
        }
        
        if($contains_variation == false) {
          array_push($separated_meta, "-" . esc_html($current_meta_item["key"]) . ": " . esc_html($current_meta_item["value"]) . "\n\n");   
        }
    }
    
    
    if(empty($separated_meta) == false) {
        if(count($separated_meta) > 0) {
            echo '> Questionnaire:';
            echo "\n\n";
            
            for($v = 0; $v < count($separated_meta); $v++) {
                echo $separated_meta[$v];
            }
        }
    }
    
    echo "\n\n";
}

/* translators: %s: Customer billing full name */
// echo sprintf( esc_html__( 'You’ve received the following order from %s:', 'woocommerce' ), esc_html( $order->get_formatted_billing_full_name() ) ) . "\n\n";

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
 
// do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

echo "\n";

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */

// do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
 
// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
// echo "\n\n----------------------------------------\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
// if ( $additional_content ) {
// 	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
// 	echo "\n\n----------------------------------------\n\n";
// }

// echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
echo "> Billing Address: " . $order_billingAddress;
echo "\n";
echo "> Shipping Method: " . $order_shippingMethod;
echo "\n";
echo "> Delivery Address: " . $order_shippingAddress;
echo "\n";
echo "> Phone: " . esc_html( do_shortcode('[medsforless_showfield field="phone" uid="' . $order_uid . '"]') );
echo "\n";
echo "> Email: " . $order_billingEmail;
echo "\n";
echo ">";