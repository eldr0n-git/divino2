<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// Если есть ошибки, показываем их
if ( ! empty( $checkout->checkout_fields ) || ! empty( $checkout->shipping_methods ) ) {
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">
			<div class="col-1">
				
				<?php if ( ! is_user_logged_in() ) : ?>
					<!-- Форма для неавторизованных пользователей -->
					<div class="checkout-login-register" style="background: #f9f9f9; padding: 20px; margin-bottom: 20px; border-radius: 5px;">
						<h3><?php esc_html_e( 'Уже есть аккаунт?', 'woocommerce' ); ?></h3>
						<p><?php esc_html_e( 'Если у вас уже есть аккаунт, пожалуйста войдите в систему.', 'woocommerce' ); ?></p>
						
						<div class="login-register-buttons" style="margin: 15px 0;">
							<button type="button" class="button" id="show-login-form"><?php esc_html_e( 'Войти', 'woocommerce' ); ?></button>
							<button type="button" class="button" id="show-register-form"><?php esc_html_e( 'Регистрация', 'woocommerce' ); ?></button>
							<button type="button" class="button" id="continue-as-guest"><?php esc_html_e( 'Продолжить как гость', 'woocommerce' ); ?></button>
						</div>

						<!-- Форма входа -->
						<div id="login-form" style="display: none; margin-top: 20px;">
							<h4><?php esc_html_e( 'Вход в аккаунт', 'woocommerce' ); ?></h4>
							<p class="form-row form-row-wide">
								<label for="login_username"><?php esc_html_e( 'Email или имя пользователя', 'woocommerce' ); ?> <span class="required">*</span></label>
								<input type="text" class="input-text" name="login_username" id="login_username" autocomplete="username" />
							</p>
							<p class="form-row form-row-wide">
								<label for="login_password"><?php esc_html_e( 'Пароль', 'woocommerce' ); ?> <span class="required">*</span></label>
								<input class="input-text" type="password" name="login_password" id="login_password" autocomplete="current-password" />
							</p>
							<p class="form-row">
								<button type="button" class="button" id="login-submit"><?php esc_html_e( 'Войти', 'woocommerce' ); ?></button>
								<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
									<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
									<span><?php esc_html_e( 'Запомнить меня', 'woocommerce' ); ?></span>
								</label>
							</p>
							<p class="lost_password">
								<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Забыли пароль?', 'woocommerce' ); ?></a>
							</p>
						</div>

						<!-- Форма регистрации -->
						<div id="register-form" style="display: none; margin-top: 20px;">
							<h4><?php esc_html_e( 'Регистрация', 'woocommerce' ); ?></h4>
							<p class="form-row form-row-wide">
								<label for="reg_username"><?php esc_html_e( 'Имя пользователя', 'woocommerce' ); ?> <span class="required">*</span></label>
								<input type="text" class="input-text" name="reg_username" id="reg_username" autocomplete="username" />
							</p>
							<p class="form-row form-row-wide">
								<label for="reg_email"><?php esc_html_e( 'Email', 'woocommerce' ); ?> <span class="required">*</span></label>
								<input type="email" class="input-text" name="reg_email" id="reg_email" autocomplete="email" />
							</p>
							<p class="form-row form-row-wide">
								<label for="reg_password"><?php esc_html_e( 'Пароль', 'woocommerce' ); ?> <span class="required">*</span></label>
								<input type="password" class="input-text" name="reg_password" id="reg_password" autocomplete="new-password" />
							</p>
							<p class="form-row">
								<button type="button" class="button" id="register-submit"><?php esc_html_e( 'Зарегистрироваться', 'woocommerce' ); ?></button>
							</p>
						</div>
					</div>
				<?php endif; ?>

				<!-- Кастомная форма контактной информации -->
				<div class="woocommerce-billing-fields">
					<h3><?php esc_html_e( 'Контактная информация', 'woocommerce' ); ?></h3>

					<div class="woocommerce-billing-fields__field-wrapper">
						<!-- Имя -->
						<p class="form-row form-row-first validate-required" id="billing_first_name_field">
							<label for="billing_first_name"><?php esc_html_e( 'Имя', 'woocommerce' ); ?> <abbr class="required" title="обязательное поле">*</abbr></label>
							<input type="text" class="input-text" name="billing_first_name" id="billing_first_name" placeholder="<?php esc_attr_e( 'Введите ваше имя', 'woocommerce' ); ?>" value="<?php echo esc_attr( $checkout->get_value( 'billing_first_name' ) ); ?>" autocomplete="given-name" required />
						</p>

						<!-- Фамилия -->
						<p class="form-row form-row-last validate-required" id="billing_last_name_field">
							<label for="billing_last_name"><?php esc_html_e( 'Фамилия', 'woocommerce' ); ?> <abbr class="required" title="обязательное поле">*</abbr></label>
							<input type="text" class="input-text" name="billing_last_name" id="billing_last_name" placeholder="<?php esc_attr_e( 'Введите вашу фамилию', 'woocommerce' ); ?>" value="<?php echo esc_attr( $checkout->get_value( 'billing_last_name' ) ); ?>" autocomplete="family-name" required />
						</p>

						<!-- Телефон -->
						<p class="form-row form-row-first validate-required validate-phone" id="billing_phone_field">
							<label for="billing_phone"><?php esc_html_e( 'Номер телефона', 'woocommerce' ); ?> <abbr class="required" title="обязательное поле">*</abbr></label>
							<input type="tel" class="input-text" name="billing_phone" id="billing_phone" placeholder="<?php esc_attr_e( 'Введите ваш номер телефона', 'woocommerce' ); ?>" value="<?php echo esc_attr( $checkout->get_value( 'billing_phone' ) ); ?>" autocomplete="tel" required />
						</p>

						<!-- WhatsApp -->
						<p class="form-row form-row-last" id="billing_whatsapp_field">
							<label for="billing_whatsapp"><?php esc_html_e( 'WhatsApp', 'woocommerce' ); ?> <span class="optional">(необязательно)</span></label>
							<input type="tel" class="input-text" name="billing_whatsapp" id="billing_whatsapp" placeholder="<?php esc_attr_e( 'Введите номер WhatsApp', 'woocommerce' ); ?>" value="<?php echo esc_attr( $checkout->get_value( 'billing_whatsapp' ) ); ?>" autocomplete="tel" />
						</p>

						<!-- Email -->
						<p class="form-row form-row-wide validate-required validate-email" id="billing_email_field">
							<label for="billing_email"><?php esc_html_e( 'Email адрес', 'woocommerce' ); ?> <abbr class="required" title="обязательное поле">*</abbr></label>
							<input type="email" class="input-text" name="billing_email" id="billing_email" placeholder="<?php esc_attr_e( 'Введите ваш email', 'woocommerce' ); ?>" value="<?php echo esc_attr( $checkout->get_value( 'billing_email' ) ); ?>" autocomplete="email" required />
						</p>

						<!-- Адрес -->
						<p class="form-row form-row-wide validate-required" id="billing_address_1_field">
							<label for="billing_address_1"><?php esc_html_e( 'Адрес доставки', 'woocommerce' ); ?> <abbr class="required" title="обязательное поле">*</abbr></label>
							<input type="text" class="input-text" name="billing_address_1" id="billing_address_1" placeholder="<?php esc_attr_e( 'Улица, дом, квартира', 'woocommerce' ); ?>" value="<?php echo esc_attr( $checkout->get_value( 'billing_address_1' ) ); ?>" autocomplete="address-line1" required />
						</p>

						<!-- Дополнительная информация (необязательно) -->
						<p class="form-row form-row-wide" id="order_comments_field">
							<label for="order_comments"><?php esc_html_e( 'Примечания к заказу', 'woocommerce' ); ?> <span class="optional">(необязательно)</span></label>
							<textarea name="order_comments" class="input-text" id="order_comments" placeholder="<?php esc_attr_e( 'Примечания к заказу, например особые требования к доставке.', 'woocommerce' ); ?>" rows="2" cols="5"><?php echo esc_textarea( $checkout->get_value( 'order_comments' ) ); ?></textarea>
						</p>
					</div>
				</div>
			</div>

			<div class="col-2">
				<h3 id="order_review_heading"><?php esc_html_e( 'Ваш заказ', 'woocommerce' ); ?></h3>
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>
				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>
	
</form>

<script>
jQuery(document).ready(function($) {
	// Показать/скрыть формы входа и регистрации
	$('#show-login-form').on('click', function() {
		$('#register-form').hide();
		$('#login-form').toggle();
	});
	
	$('#show-register-form').on('click', function() {
		$('#login-form').hide();
		$('#register-form').toggle();
	});
	
	$('#continue-as-guest').on('click', function() {
		$('#login-form, #register-form').hide();
		$('.checkout-login-register').fadeOut();
	});
	
	// AJAX вход
	$('#login-submit').on('click', function(e) {
		e.preventDefault();
		
		var username = $('#login_username').val();
		var password = $('#login_password').val();
		var remember = $('#rememberme').is(':checked');
		
		if (!username || !password) {
			alert('Пожалуйста, заполните все поля');
			return;
		}
		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: wc_checkout_params.ajax_url,
			data: {
				'action': 'custom_user_login',
				'username': username,
				'password': password,
				'remember': remember,
				'security': '<?php echo wp_create_nonce("custom_login_nonce"); ?>'
			},
			success: function(data) {
				if (data.success) {
					location.reload();
				} else {
					alert(data.data || 'Ошибка входа');
				}
			}
		});
	});
	
	// AJAX регистрация
	$('#register-submit').on('click', function(e) {
		e.preventDefault();
		
		var username = $('#reg_username').val();
		var email = $('#reg_email').val();
		var password = $('#reg_password').val();
		
		if (!username || !email || !password) {
			alert('Пожалуйста, заполните все поля');
			return;
		}
		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: wc_checkout_params.ajax_url,
			data: {
				'action': 'custom_user_register',
				'username': username,
				'email': email,
				'password': password,
				'security': '<?php echo wp_create_nonce("custom_register_nonce"); ?>'
			},
			success: function(data) {
				if (data.success) {
					alert('Регистрация прошла успешно! Сейчас страница обновится.');
					location.reload();
				} else {
					alert(data.data || 'Ошибка регистрации');
				}
			}
		});
	});
});
</script>

<style>
.checkout-login-register {
	border: 1px solid #ddd;
}

.login-register-buttons button {
	margin-right: 10px;
	margin-bottom: 10px;
}

.form-row-first {
	float: left;
	width: 48%;
}

.form-row-last {
	float: right;
	width: 48%;
}

.form-row-wide {
	width: 100%;
	clear: both;
}

.form-row {
	margin-bottom: 1rem;
	overflow: hidden;
}

.required {
	color: red;
}

.optional {
	color: #999;
	font-size: 0.9em;
}
</style>

<?php } ?>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>