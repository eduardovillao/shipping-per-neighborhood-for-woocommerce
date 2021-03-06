<?php
/**
 * Shipping Calculator EDITED MODEL
 * 
 * Edited model to replace default model from woocommerce.
 *
 * @since 1.0
 */
defined( 'ABSPATH' ) || exit;

$cities = WSN_Get_Fields::get_global_cities();
$neighborhoods = get_option( 'wsn_global_neighborhoods' );
$options = WSN_Get_Fields::get_global_cities_and_neighborhoods();

do_action( 'woocommerce_before_shipping_calculator' ); ?>

<form class="woocommerce-shipping-calculator" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

	<?php printf( '<a href="#" class="shipping-calculator-button">%s</a>', esc_html( ! empty( $button_text ) ? $button_text : __( 'Calculate shipping', 'woocommerce' ) ) ); ?>

	<section class="shipping-calculator-form" style="display:none;">

		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_country', true ) ) : ?>
			<p class="form-row form-row-wide" id="calc_shipping_country_field">
				<select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state country_select" rel="calc_shipping_state">
					<option value="default"><?php esc_html_e( 'Select a country / region', 'woocommerce' ); ?></option>
					<?php
					foreach ( WC()->countries->get_shipping_countries() as $key => $value ) {
						echo '<option value="' . esc_attr( $key ) . '"' . selected( WC()->customer->get_shipping_country(), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
					}
					?>
				</select>
			</p>
		<?php endif; ?>

		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_state', true ) ) : ?>
			<p class="form-row form-row-wide" id="calc_shipping_state_field">
				<?php
				$current_cc = WC()->customer->get_shipping_country();
				$current_r  = WC()->customer->get_shipping_state();
				$states     = WC()->countries->get_states( $current_cc );

				if ( is_array( $states ) && empty( $states ) ) {
					?>
					<input type="hidden" name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php esc_attr_e( 'State / County', 'woocommerce' ); ?>" />
					<?php
				} elseif ( is_array( $states ) ) {
					?>
					<span>
						<select name="calc_shipping_state" class="state_select" id="calc_shipping_state" data-placeholder="<?php esc_attr_e( 'State / County', 'woocommerce' ); ?>">
							<option value=""><?php esc_html_e( 'Select an option', 'woocommerce' ); ?></option>
							<?php
							foreach ( $states as $ckey => $cvalue ) {
								echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( $current_r, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
							}
							?>
						</select>
					</span>
					<?php
				} else {
					?>
					<input type="text" class="input-text" value="<?php echo esc_attr( $current_r ); ?>" placeholder="<?php esc_attr_e( 'State / County', 'woocommerce' ); ?>" name="calc_shipping_state" id="calc_shipping_state" />
					<?php
				}
				?>
			</p>
		<?php endif; ?>

		<?php
		$current_city = esc_attr( WC()->customer->get_shipping_city() );
		$cities = WSN_Get_Fields::get_global_cities();
        ?>
        <p class="form-row form-row-wide" id="calc_shipping_city_field">
            <select class="neighborhood-select" name="calc_shipping_city" id="calc_shipping_city">
                <option value=""><?php esc_html_e( 'Select City', 'shipping-per-neighborhood-for-woocommerce' ); ?></option>
                <?php foreach( $cities as $c => $tb ) : ?>
                    <option value="<?php echo esc_attr( $tb ); ?>" <?php selected( $current_city, $tb, true ); ?>><?php echo esc_html( $tb ); ?></option>
                <?php endforeach ?>
            </select>
        </p>
        
        <?php
        $current_neig = WSN_Get_Fields::get_neighborhood_option();
        ?>
        <p class="shipping-per-neighborhood" id="calc-shipping-per-neighborhood">
            <select class="neighborhood-select" name="calc-shipping-neighborhood" id="calc-shipping-per-neighborhood__select">
                <option value=""><?php esc_html_e( 'Select Neighborhood', 'shipping-per-neighborhood-for-woocommerce' ); ?></option>
                <?php foreach( $options as $c => $b ) : ?>
                    <optgroup label="<?php echo esc_attr( $c ); ?>">
                        <?php if( is_array( $b ) ) :
                            foreach( $b as $tb ) : ?>
                                <option value="<?php echo esc_attr( $tb ); ?>" <?php selected( $current_neig, $tb, true ); ?>><?php echo esc_html( $tb ); ?></option>
                            <?php endforeach;
                        endif; ?>
                    </optgroup>
                <?php endforeach ?>
            </select>
        </p>

		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ) : ?>
			<p class="form-row form-row-wide" id="calc_shipping_postcode_field">
				<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>" placeholder="<?php esc_attr_e( 'Postcode / ZIP', 'woocommerce' ); ?>" name="calc_shipping_postcode" id="calc_shipping_postcode" />
			</p>
		<?php endif; ?>

		<p><button type="submit" name="calc_shipping" value="1" class="button"><?php esc_html_e( 'Update', 'woocommerce' ); ?></button></p>
		<?php wp_nonce_field( 'woocommerce-shipping-calculator', 'woocommerce-shipping-calculator-nonce' ); ?>
	</section>
</form>

<?php do_action( 'woocommerce_after_shipping_calculator' ); ?>