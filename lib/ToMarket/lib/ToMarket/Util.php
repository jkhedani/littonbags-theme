<?php

/**
 * Order Number Generator
 * All random currently.
 */
function generateRandomOrderNumber( $length ) {
  $chars = array_merge(range('A', 'Z'), range(0, 9));
  shuffle($chars);
  return implode(array_slice($chars, 0, $length));
}

/**
 * Cents Prettifier
 * Currently only accepts only cents as input.
 *
 * @return String Money in pretty US Format
 */
function format_money( $amount, $currencyType ) {
  // Format: US
  if ( $currencyType == 'US' ) {
    $nonos = array( '.', '$' ); // Set possible objects to strip
    $priceInPennies = str_replace( $nonos, '', $amount); // ensure cents
    $prettyMoney = money_format( '%n', $priceInPennies/100 ); // Format yo cash
    return $prettyMoney;
  }
}

/**
 * Calculate hand basket cost
 *
 * Calculate true product cost based on
 * server-side calculations.
 *
 * @param   $handbasket array The contents of the handbasket
 * @return  Array of totals
 * @since 1.2.0
 */
function handbasket_totals( $handbasket ) {
  // Determine True Cost of Basket
  $subtotal = 0;
  foreach ( $handbasket as $sku => $data ) {
    $post_id = $data['post_id'];
    if ( have_rows('product_skus', $post_id ) ) {
      while ( have_rows('product_skus', $post_id) ) : the_row();
        // if the sku matches the current product
        if ( get_sub_field('sku') === $sku ) {
          $subtotal = $subtotal + ( get_sub_field('sku_price') * $data['product_qty'] );
        }
      endwhile;
    } else {
      // Exit and send message back (someone fucking with the system)
    }
  }
  $taxtotal   = round( $subtotal * get_field( 'tax_rate', 'option' ) );
  $grandtotal = round( ($subtotal + $taxtotal) );

  // Construct return array
  $handbasket_totals = array();
  $handbasket_totals['taxtotal'] = $taxtotal;
  $handbasket_totals['subtotal'] = $subtotal;
  $handbasket_totals['grandtotal'] = $grandtotal;

  return $handbasket_totals;
}

/**
 * Convert cents to dollars
 *
 * @param  $cents int Amount in cents
 * @return int Amount in dollars
 * @since 1.2.0
 */
function cents_to_dollars( $cents ) {
  $dollars = number_format( $cents / 100, 2, '.', '');
  return $dollars;
}

/**
 * getBaseUrl function (via PayPal)
 *
 * Utility function that returns base url for determining return/cancel urls
 * @return string
 */
function getBaseUrl()
{

    $protocol = 'http';
    if ($_SERVER['SERVER_PORT'] == 443 || (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')) {
        $protocol .= 's';
        $protocol_port = $_SERVER['SERVER_PORT'];
    } else {
        $protocol_port = 80;
    }

    $host = $_SERVER['HTTP_HOST'];
    $port = $_SERVER['SERVER_PORT'];
    $request = $_SERVER['PHP_SELF'];
    return dirname($protocol . '://' . $host . ($port == $protocol_port ? '' : ':' . $port) . $request);
}


?>
