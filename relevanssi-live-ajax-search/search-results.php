<?php
/**
 * Search results are contained within a div.relevanssi-live-search-results
 * which you can style accordingly as you would any other element on your site.
 *
 * Some base styles are output in wp_footer that do nothing but position the
 * results container and apply a default transition, you can disable that by
 * adding the following to your theme's functions.php:
 *
 * add_filter( 'relevanssi_live_search_base_styles', '__return_false' );
 *
 * There is a separate stylesheet that is also enqueued that applies the default
 * results theme (the visual styles) but you can disable that too by adding
 * the following to your theme's functions.php:
 *
 * wp_dequeue_style( 'relevanssi-live-search' );
 *
 * You can use ~/relevanssi-live-search/assets/styles/style.css as a guide to customize
 *
 * @package Relevanssi Live Ajax Search
 */

	$nopost_error = '<p class="relevanssi-live-search-no-results" role="status"> No results found. </p>';
?>
<div class="medsforless_ajaxresults_wrapper">

	<div class="medsforless_ajaxresults_results">
		<?php if ( have_posts() ) : 
			
			$all_ajax_posts = array();
			$posts_recorded = 0;
			$posts_limit = 3;
			$posts_pages_amt = 0;

			$status_element = '<div class="relevanssi-live-search-result-status" role="status" aria-live="polite"><p>';
			// Translators: %s is the number of results found.
			$status_element .= sprintf( esc_html( _n( '%d result found.', '%d results found.', $wp_query->found_posts, 'relevanssi-live-ajax-search' ) ), intval( $wp_query->found_posts ) );
			if ( $wp_query->found_posts > 7 ) {
				$status_element .= ' ' . sprintf( esc_html( __( 'Press enter to see all the results.', 'relevanssi-live-ajax-search' ) ) );
			}
			$status_element .= '</p></div>';

			/**
			 * Filters the status element location.
			 *
			 * @param string The location. Possible values are 'before' and 'after'. If
			 * the value is 'before', the status element will be added before the
			 * results container. If the value is 'after', the status element will be
			 * added after the results container. Default is 'before'. Any other value
			 * will make the status element disappear.
			 */
			$status_location = apply_filters( 'relevanssi_live_search_status_location', 'before' );

			if ( ! in_array( $status_location, array( 'before', 'after' ), true ) ) {
				// No status element is displayed. Still add one for screen readers.
				$status_location = 'before';
				$status_element  = '<p class="screen-reader-text" role="status" aria-live="polite">';
				// Translators: %s is the number of results found.
				$status_element .= sprintf( esc_html( _n( '%d result found.', '%d results found.', $wp_query->found_posts, 'relevanssi-live-ajax-search' ) ), intval( $wp_query->found_posts ) );
				$status_element .= '</p>';
			}

			if ( 'before' === $status_location ) {
				// Already escaped.
				echo $status_element; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			while ( have_posts() ) :
				the_post();
				$the_id = get_the_ID();
				$the_post_type = get_post_type();
				$the_post_thumbnail_id = get_post_thumbnail_id( $the_id );
				$the_terms = get_the_terms( $the_id, 'product_cat' );
				$the_terms_output = 'No Categories';
				$the_title = get_the_title($the_id);
				$the_price = '';
				$the_image_url = '';
				$the_image_output = '';
				$the_product_output = '';

				if($the_terms != false) {
					$the_terms_output = join(', ', wp_list_pluck($the_terms, 'name'));
				}

				if(!empty($the_post_thumbnail_id)) {
					$the_image_url = wp_get_attachment_image_src( $the_post_thumbnail_id, 'medium' );
					$the_image_output = '<div class="medsforless_ajaxresult_image"><img src="' . $the_image_url[0] . '"></div>';
				} else {
					$the_image_output = '<div class="medsforless_ajaxresult_image"><img src="https://www.medsforless.co.uk/wp-content/uploads/2023/04/AjaxPlaceholder-01.jpg"></div>';
				}
				
				
				if ( $the_post_type == 'product' ) : 
					$posts_recorded += 1;
					$the_product = 	wc_get_product($the_id);
					$the_currency_symbol = get_woocommerce_currency_symbol();

					if($the_product->is_type('variable')) {
						$the_price = $the_product->get_variation_price( 'min' );
					} else {
						$the_price = $the_product->get_price();
					}

					$the_product_output = '<div class="relevanssi-live-search-result" role="option" aria-selected="false">
												<a class="medsforless_ajaxresult_outterlink" href="' . esc_url(get_permalink()) . '">' .
													$the_image_output .
													'<span class="medsforless_ajaxresult_category">' . $the_terms_output . '</span>
													<span class="medsforless_ajaxresult_title">' . $the_title . '</span>
													<div class="medsforless_ajaxresult_price">
														<div class="medsforless_ajaxresult_price_price">
															<p>From</p>
															<p>' . $the_currency_symbol . $the_price . '</p>
														</div>
														<div class="medsforless_ajaxresult_price_button"><span>View More</span></div>
													</div>
												</a>
										  </div>';
					// echo $the_product_output;
					array_push($all_ajax_posts, $the_product_output);
				endif;
			endwhile;

			if(empty($all_ajax_posts) == false) {
				if(count($all_ajax_posts) > 0) {
					$posts_pages_amt = ceil($posts_recorded / $posts_limit);

					echo '<div id="medsforless_ajaxresults_resultsinner" class="medsforless_ajaxresults_resultsinner">';
						//initial item load
						for($x = 0; $x < count($all_ajax_posts); $x++) {
							if($x < $posts_limit) {
								echo $all_ajax_posts[$x];
							}
						}
					echo '</div>';

					echo '<div id="medsforless_ajaxresults_resultshidden" class="medsforless_ajaxresults_resultshidden">';
						//initial item load
						for($z = 0; $z < count($all_ajax_posts); $z++) {
							echo $all_ajax_posts[$z];
						}
					echo '</div>';

					echo '<div id="medsforless_ajaxpagination" class="medsforless_ajaxpagination">';
					for($y = 0; $y < $posts_pages_amt; $y++) {
						if($y == 0) {
							echo '<a class="mfl_ajaxcurrent" href="javascript:void(0)">' . ($y + 1) . '</a>';
						} else {
							echo '<a href="javascript:void(0)">' . ($y + 1) . '</a>';
						}
					}
					echo '</div>';

				} else {
					echo $nopost_error;
				}
			} else {
				echo $nopost_error;
			}

			if ( 'after' === $status_location ) {
				// Already escaped.
				echo $status_element; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
			<?php else : ?>
			<p class="relevanssi-live-search-no-results" role="status">
				<?php esc_html_e( 'No results found.', 'relevanssi-live-ajax-search' ); ?>
			</p>
				<?php
				if ( function_exists( 'relevanssi_didyoumean' ) ) {
					relevanssi_didyoumean(
						$wp_query->query_vars['s'],
						'<p class="relevanssi-live-search-didyoumean" role="status">'
							. __( 'Did you mean', 'relevanssi-live-ajax-search' ) . ': ',
						'</p>'
					);
				}
				?>
		<?php endif; ?>
	</div>

	<div class="medsforless_ajaxresults_side">
			<?php echo $posts_recorded ?>
	</div>

</div>