<?php
/**
 * Admin new order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/admin-new-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails\HTML
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
$body_content = '';

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

$body_content .= '> ' . esc_html( wp_strip_all_tags( $email_heading ) );
$body_content .= '<br>';
$body_content .= '> Patient First Name: ' . esc_html($order_fname);
$body_content .= "<br>";
$body_content .= '> Patient Last Name: ' . esc_html($order_lname);
$body_content .= '<br>';
$body_content .= '> Date Of Birth: ' . esc_html( do_shortcode('[medsforless_showfield field="birthday" uid="' . $order_uid . '"]') );
$body_content .= '<br>';
$body_content .= '> [ORDER #' . $order->get_id() . ']';
$body_content .= '<br>';

if(empty($payment_date) == false && isset($payment_date)) {
    $body_content .= '> DATE: (' . $payment_date->format ('Y-m-d') . ')';
} else {
    $body_content .= '> DATE: Payment Completion Date Unvailable.  This could be a bank transfer.';
}

$body_content .= '<br>' . '>' . '<br>' . '>' . '<br>';

foreach ( $order_items as $item_id => $item ) {
    $current_product = $item->get_product();
    $current_product_name = $item->get_name();
    $current_quantity = $item->get_quantity();
    $current_total = $item->get_total();
    $current_allmeta = $item->get_meta_data();
    $formatted_allmeta = $item->get_formatted_meta_data();
    $meta_to_remove = array();
    $separated_meta = array();
    
    $body_content .= '> Product: ' . $current_product_name . '<br>';
    $body_content .= '> Quantity: ' . $current_quantity . '<br>';
    $body_content .= '> Price: ' . $currency_symbol . $current_total . '<br>';
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
             
            $body_content .= '>' . $label . ': ' . $name . '<br>';
            array_push($meta_to_remove, $attribute);
        }
    }
    
    $body_content .= '>' . '<br>' . '>' . '<br>';
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
          array_push($separated_meta, '-' . esc_html($current_meta_item["key"]) . ': ' . esc_html($current_meta_item["value"]) . '<br><br>');
        }
    }
    
    
    if(empty($separated_meta) == false) {
        if(count($separated_meta) > 0) {
            $body_content .= '> Questionnaire:';
            $body_content .= '<br><br>';
            
            for($v = 0; $v < count($separated_meta); $v++) {
                $body_content .= $separated_meta[$v];
            }
        }
    }
    
    $body_content .= '<br><br>';
}

$body_content .= '<br>';

$body_content .= '> Billing Address: ' . $order_billingAddress;
$body_content .= '<br>';
$body_content .= '> Shipping Method: ' . $order_shippingMethod;
$body_content .= '<br>';
$body_content .= '> Delivery Address: ' . $order_shippingAddress;
$body_content .= '<br>';
$body_content .= '> Phone: ' . esc_html( do_shortcode('[medsforless_showfield field="phone" uid="' . $order_uid . '"]') );
$body_content .= '<br>';
$body_content .= '> Email: ' . $order_billingEmail;
$body_content .= '<br>';
$body_content .= '>';

?>


<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
	</head>
	<body style="padding: 0; margin: 0;">
		<?php
            echo $body_content;
        ?>
	</body>
</html>