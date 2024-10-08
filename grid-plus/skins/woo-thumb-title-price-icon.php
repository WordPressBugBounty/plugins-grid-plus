<?php
/**
 * Created by PhpStorm.
 * User: phuongth
 * Date: 12/17/2016
 * Time: 2:51 PM
 * @var $thumbnail
 * @var $post_link
 * @var $title
 * @var $ico_gallery
 * @var $disable_link
 * @var $price
 */

global $post;
$regular_price = $sale_price = $is_sale = $is_feature = 0;
$rating = '';
$sale_text = apply_filters('grid-plus-woocommerce-sale-text', esc_html__('Sale', 'grid-plus'));
$feature_text = apply_filters('grid-plus-woocommerce-feature-text', esc_html__('Hot', 'grid-plus'));
if (!isset($is_backend) && 'product' == get_post_type($post->ID)) {
    if (!isset($post->ID) || !function_exists('wc_get_product')) {
        return;
    }
    $product = wc_get_product($post->ID);
    $is_sale = $product->is_on_sale();
    $is_feature = $product->is_featured();
    $price = apply_filters('grid-plus-price', $product->get_price_html());
}
?>
<div class="grid-post-item thumbnail woocommerce thumb-title-price-inline" data-thumbnail-only="1">
    <div class="thumbnail-image" data-img="<?php echo esc_url($thumbnail); ?>">
        <?php if(!empty($thumbnail)): ?>
            <img src="<?php echo esc_attr($thumbnail); ?>" alt="<?php echo esc_html($title); ?>" >
        <?php endif; ?>
        <div class="flag-wrap">
            <?php if ($is_sale): ?>
                <div class="flag on-sale">
                    <?php echo esc_html($sale_text); ?>
                </div>
            <?php endif; ?>
            <?php if ($is_feature): ?>
                <div class="flag on-feature">
                    <?php echo esc_html($feature_text); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="post-info">
            <?php if(!empty($title)): ?>
                <div class="title">
                    <?php if($disable_link != 'true'): ?>
                        <a href="<?php echo esc_attr($post_link); ?>" title="<?php echo esc_html($title); ?>"><?php echo esc_html($title); ?></a>
                    <?php else: ?>
                        <?php echo esc_html($title); ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($price)): ?>
                <div class="price"><?php echo sprintf('%s', $price); ?></div>
            <?php endif; ?>
        </div>

        <div class="hover-outer transition-30">
            <a href="<?php echo esc_attr($post_link); ?>" title="<?php echo esc_html($title); ?>"></a>
            <div class="hover-inner transition-50">
                <div class="icon-groups">
                    <a href="javascript:;" class="view-gallery" data-src="<?php echo esc_url($thumbnail); ?>"
                       data-post-id="<?php echo get_the_ID(); ?>" title="<?php echo esc_attr($title); ?>"
                       data-ajax-url="<?php echo esc_url(wp_nonce_url(admin_url('admin-ajax.php'), 'grid_plus_view_gallery')) ?>"><i
                            class="<?php echo esc_attr($ico_gallery); ?>"></i></a>
                    <?php
                    if(isset($is_backend)){?>
                        <a href="javascript:;" class="add-to-cart-view"><i
                                    class="fa fa-cart-plus"></i></a>
                        <?php
                    } elseif ('product' == get_post_type($post->ID)) {
                        global $product;
                        $quantity = apply_filters('woocommerce_quantity_input_min', 1);
                        $add_to_cart_text = apply_filters('grid-plus-add-to-cart-text', '<i class="fa fa-shopping-cart"></i>');
                        $icon_added_to_cart = apply_filters('grid-plus-icon-added-to-cart', 'fa fa-cart-plus');
                        if (isset($product->id) && class_exists('WC_AJAX')) {
                            echo apply_filters('grid-plus-add-to-cart-link',
                                sprintf('<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s"
                                        data-product_sku="%s"
                                        data-cart_url="%s"
                                        data-cart_redirect_after_add = "%s"
                                        data-icon_added_to_cart="%s"
                                        data-style="zoom-in"
                                        data-spinner-size="20"
                                        data-spinner-color="#fff"
                                        class="ladda-button add-to-cart">%s</a>',
                                    esc_url(WC_AJAX::get_endpoint("%%endpoint%%")),
                                    esc_attr($quantity),
                                    esc_attr($product->id),
                                    esc_attr($product->get_sku()),
                                    apply_filters('woocommerce_add_to_cart_redirect', wc_get_cart_url()),
                                    get_option('woocommerce_cart_redirect_after_add'),
                                    $icon_added_to_cart,
                                    $add_to_cart_text
                                ),
                                $product);
                        }
                    }
                    ?>
                    <?php
                    if (!isset($is_backend) && 'product' == get_post_type($post->ID)) {
                        if (!shortcode_exists('yith_compare_button') && class_exists('YITH_Woocompare') && function_exists('yith_woocompare_constructor')) {
                            $context = isset($_REQUEST['context']) ? $_REQUEST['context'] : null;
                            $_REQUEST['context'] = 'frontend';
                            yith_woocompare_constructor();
                            $_REQUEST['context'] = $context;
                        }
                        if (shortcode_exists('yith_compare_button')) {
                            echo do_shortcode('[yith_compare_button]');
                        }
                        if (shortcode_exists('yith_wcwl_add_to_wishlist')) {
                            echo do_shortcode('[yith_wcwl_add_to_wishlist]');
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
