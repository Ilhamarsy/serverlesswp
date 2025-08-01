<?php

use \Essential_Addons_Elementor\Classes\Helper;

/**
 * Template Name: Preset 4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$product = wc_get_product( get_the_ID() );
if ( ! $product ) {
	error_log( '$product not found in ' . __FILE__ );
	return;
}

// Improvement
$sale_badge_align = isset( $settings['eael_product_sale_badge_alignment'] ) ? $settings['eael_product_sale_badge_alignment'] : '';
$sale_badge_preset = isset($settings['eael_product_sale_badge_preset']) ? $settings['eael_product_sale_badge_preset'] : '';
$sale_text = !empty($settings['eael_product_carousel_sale_text']) ? $settings['eael_product_carousel_sale_text'] : 'Sale!';
$stockout_text = !empty($settings['eael_product_carousel_stockout_text']) ? $settings['eael_product_carousel_stockout_text'] : 'Stock Out';
$title_tag = isset( $settings['eael_product_carousel_title_tag'] ) ? Helper::eael_validate_html_tag($settings['eael_product_carousel_title_tag'])  : 'h2';

// should print vars
$should_print_rating = isset( $settings['eael_product_carousel_rating'] ) && 'yes' === $settings['eael_product_carousel_rating'];
$should_print_quick_view = isset( $settings['eael_product_carousel_quick_view'] ) && 'yes' === $settings['eael_product_carousel_quick_view'];
$should_print_image_clickable = isset( $settings['eael_product_carousel_image_clickable'] ) && 'yes' === $settings['eael_product_carousel_image_clickable'];
$should_print_title_clickable = isset( $settings['eael_product_carousel_title_clickable'] ) && 'yes' === $settings['eael_product_carousel_title_clickable'];
$should_print_price = isset( $settings['eael_product_carousel_price'] ) && 'yes' === $settings['eael_product_carousel_price'];
$should_print_excerpt = isset( $settings['eael_product_carousel_excerpt'] ) && ('yes' === $settings['eael_product_carousel_excerpt'] && has_excerpt());
$widget_id = isset($settings['eael_widget_id']) ? $settings['eael_widget_id'] : null;
$quick_view_setting = [
	'widget_id' => $widget_id,
	'product_id' => $product->get_id(),
	'page_id' => $settings['eael_page_id'],
];

$product_details_wrap_show = ! empty( $settings['eael_product_carousel_show_title'] ) || $should_print_price || $should_print_rating || $should_print_excerpt;
$product_details_none_class = $product_details_wrap_show ? '' : 'product-details-none-overlay';

if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {
	?>
    <div <?php post_class( ['product', 'swiper-slide'] ); ?>>
        <div class="eael-product-carousel <?php echo esc_attr( $product_details_none_class ); ?>">
            <div class="carousel-overlay <?php echo $should_print_image_clickable ? 'eael-img-clickable' : ''; ?>"></div>
            <div class="product-image-wrap">
                <div class="image-wrap">
					<?php
					echo ( ! $product->is_in_stock() ? '<span class="eael-onsale outofstock ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">' . esc_html( $stockout_text ) . '</span>' : ( $product->is_on_sale() ? '<span class="eael-onsale ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">' . esc_html( $sale_text ) . '</span>' : '' ) );
					if( $should_print_image_clickable ) {
						echo '<a href="' . esc_attr( $product->get_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
					}
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $product->get_image($settings['eael_product_carousel_image_size_size'], ['loading' => 'eager']);

					if( $should_print_image_clickable ) {
						echo '</a>';
					}
					?>
                </div>
            </div>
            <div class="product-overlay-content">
                <div class="product-details-wrap">
                    <div class="product-details">
                        <div class="eael-product-title-wrap">
	                        <?php
	                        if ( $settings['eael_product_carousel_show_title'] ) {
		                        echo '<div class="eael-product-title">';		                        
		                        if( $should_print_title_clickable ) {
		                            echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
		                        }
		                        echo '<' . esc_html( $title_tag ) . '>';
		                        if ( empty( $settings['eael_product_carousel_title_length'] ) ) {
			                        echo wp_kses( $product->get_title(), Helper::eael_allowed_tags() );
		                        } else {
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			                        echo implode( " ", array_slice( explode( " ", Helper::eael_wp_kses($product->get_title()) ), 0, $settings['eael_product_carousel_title_length'] ) );
		                        }
		                        echo '</' . esc_html( $title_tag )  . '>';

		                        if( $should_print_title_clickable ) {
		                            echo '</a>';
		                        }
		                        echo '</div>';
	                        }
	                        if ( $should_print_rating ) {
								$avg_rating = $product->get_average_rating();
								$rating_count = $product->get_rating_count();
								echo '<div class="eael-star-rating">';
								$rating_text = '';
								if( 'yes' === $settings['eael_rating_count'] && ! empty( $settings['eael_rating_text'] ) ) {
									$rating_text = str_replace( [ '[avg_user_rating]', '[max_rating]', '[total_rating]' ], [ $avg_rating, '5', $rating_count ], $settings['eael_rating_text'] );
									$rating_text = '<span class="eael-star-rating-text">' . esc_html( $rating_text ) . '</span>';
								}
								if( $avg_rating > 0 ){
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo wc_get_rating_html( $avg_rating, $product->get_rating_count() ) . $rating_text;
								} else {
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo Helper::eael_rating_markup( $avg_rating, $product->get_rating_count() );
								}
								echo '</div>';
							}
							?>
                        </div>
                        <?php if($should_print_price ){
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                        } ?>
					</div>

                    <?php if ( $should_print_excerpt ) {
						echo '<div class="eael-product-excerpt">';
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	                    echo '<p>' . wp_trim_words( strip_shortcodes( get_the_excerpt() ), $settings['eael_product_carousel_excerpt_length'], esc_html( $settings['eael_product_carousel_excerpt_expanison_indicator'] ) ) . '</p>';
						echo '</div>';
					}
					?>

                    <div class="buttons-wrap">
                        <ul class="icons-wrap box-style">
						<?php if( $settings[ 'eael_product_carousel_show_add_to_cart' ] ) { ?>
                            <li class="add-to-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
                        <?php
                    		}
                        ?>
			                <?php if( $should_print_quick_view ){?>
                                <li class="eael-product-quick-view">
                                    <a id="eael_quick_view_<?php echo esc_attr( uniqid() ); ?>" data-quickview-setting="<?php echo esc_attr( htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES) ); ?>"
                                       class="open-popup-link">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </li>
			                <?php } ?>
                            <li class="view-details" title="Details"><?php echo '<a href="' . esc_url( $product->get_permalink() ) . '"><i class="fas fa-link"></i></a>'; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
	</div>
	<?php
}
