<?php
add_filter('woocommerce_checkout_fields', function($fields) {
    // Hacemos nombre y apellido requeridos, pero ocultamos el label.
    $fields['billing']['billing_first_name']['required'] = true;
    $fields['billing']['billing_last_name']['required'] = true;
    $fields['billing']['billing_first_name']['label'] = '';
    $fields['billing']['billing_last_name']['label'] = '';
    $fields['billing']['billing_email']['required'] = true;
    $fields['billing']['billing_email']['label'] = '';
    $fields['billing']['billing_country']['required'] = true;
    $fields['billing']['billing_country']['label'] = '';
    // Forzar el placeholder si no existe
    if (empty($fields['billing']['billing_first_name']['placeholder'])) $fields['billing']['billing_first_name']['placeholder'] = 'Nombre';
    if (empty($fields['billing']['billing_last_name']['placeholder'])) $fields['billing']['billing_last_name']['placeholder'] = 'Apellido';
    if (empty($fields['billing']['billing_email']['placeholder'])) $fields['billing']['billing_email']['placeholder'] = 'Correo electrónico';
    if (empty($fields['billing']['billing_country']['placeholder'])) $fields['billing']['billing_country']['placeholder'] = 'País';
    return $fields;
});
?>
<?php
/**
 * Template personalizado de Checkout para WooCommerce
 * Ubica este archivo en: /wp-content/themes/tu-tema-hijo/woocommerce/checkout/form-checkout.php
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Si no hay productos en el carrito, mostrar mensaje
if ( WC()->cart->is_empty() ) : ?>
    <p class="woocommerce-info">Tu carrito está vacío.</p>
    <?php return;
endif;

$product = null;
$cart_items = WC()->cart->get_cart();
if ($cart_items) {
    // Tomamos el primer producto del carrito (ajusta si quieres soportar varios productos)
    $cart_item = reset($cart_items);
    $product = wc_get_product($cart_item['product_id']);
    $variation = isset($cart_item['variation_id']) ? wc_get_product($cart_item['variation_id']) : null;
    $product_image = $product->get_image('medium');
    $product_title = $product->get_name();
    $product_price = $product->get_price_html();
    $product_qty = $cart_item['quantity'];
    $product_total = wc_price($cart_item['line_total']);
}
?>
<style>
.custom-checkout-container,
.checkout-left,
.checkout-right,
.checkout-product-summary,
.checkout-product-details,
.checkout-product-title,
.checkout-product-variation small,
.checkout-product-price,
.checkout-product-qty,
.checkout-product-total,
.checkout-step,
#checkout-step1-form input,
#checkout-step1-form button.button,
.form-row{
  font-family: 'Neue Montreal', Arial, sans-serif !important;
  font-weight: 300 !important;
}
.checkout-step h3,
.checkout-left h3,
.checkout-product-title {
  font-family: 'Neue Montreal', Arial, sans-serif !important;
  font-weight: 300 !important;
  color: #151515 !important; /* Negro super oscuro */
  font-size: 20px !important;
}
.custom-checkout-container {
  display: flex;
  gap: 20px;
  max-width: 100%;
  margin: 0 auto;
  flex-direction: column;
}
.woocommerce{
    padding: 50px;
    background-color: #F9F9F9;
    border-radius: 30px;
}
@media (max-width:767px){
    .woocommerce{
        padding: 20px;
    }
}
.checkout-left, .checkout-right {
  flex: 1;
}
.checkout-right{
    padding-top: 50px;
    margin-top: 50px;
    border-top: solid 1px rgba(0,0,0,0.2);
}
.woocommerce-notices-wrapper{
    display: none !important;
}
.checkout-product-summary {
  display: flex;
  flex-direction: row;
  gap: 20px;
  align-items: flex-start;
}
.checkout-product-img {
  margin-bottom: 10px;
}
.checkout-product-img img {
  max-width: 100px;
  border-radius: 6px;
  display: block;
  margin: 0 auto 0 0;
}
.checkout-product-details {
  flex-grow: 1;
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding-top: 5px;
}
.checkout-product-title {
  margin-bottom: 8px;
}
.checkout-product-variation,
.checkout-product-price,
.checkout-product-qty,
.checkout-product-total {
  color: #232323 !important; /* Negro más claro */
}
.checkout-product-variation {
  font-size: 1.06em;
  font-weight: 300 !important;
  letter-spacing: 0.01em;
}
.checkout-product-variation small {
  font-weight: 300 !important;
  color: #232323 !important;
  text-transform: none;
  display: inline-block;
  margin-bottom: 5px;
  font-size: 1em !important
}
.checkout-product-variation .freq-label {
  font-weight: 300 !important;
}
.checkout-product-price,
.checkout-product-qty,
.checkout-product-total {
  font-size: 1em;
  font-weight: 300 !important;
}
.checkout-product-total {
  font-size: 1.1em;
}
.checkout-product-details hr {
  border: none;
  border-top: 1.5px solid #ededed;
  margin: 0 0 10px 0;
  width: 100%;
}
.checkout-step {
  margin-bottom: 20px;
}
.checkout-step h3 {
  margin-bottom: 10px;
}
.checkout-step p {
  margin-bottom: 15px;
  color:rgb(91, 90, 90);
  font-weight: 300 !important;
}
#checkout-step1-form input,
#checkout-step1-form select {
  display: block;
  width: 100%;
  margin-bottom: 10px;
  padding: 8px 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-weight: 300 !important;
  color: #232323;
}
#go-to-step-2,
#place_order{
  background-color: #75F425;
  color: black;
  border: none;
  padding: 10px 15px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 300 !important;
  width: 100%;
  padding: 20px;
}
#go-to-step-2:hover,
#place_order:hover {
  background-color:rgb(55, 143, 0);
}
.e-checkout__order_review,
.shop_table.woocommerce-checkout-review-order-table{
    display: none !important;
}
.wc_payment_methods.payment_methods.methods{
    padding: 0 !important;
    background: transparent !important;
}
#payment{
    background: transparent !important; 
}
.wc_payment_method{
    padding: 12px;
    border-radius: 10px;
    background:rgba(0, 0, 0, 0.05);
    margin: 10px 0 0 0 !important;
    display: inline-block;
    width: 100%;
}
.woocommerce-terms-and-conditions-wrapper{
    margin-top: 20px !important;
}
#add_payment_method #payment ul.payment_methods, .woocommerce-cart #payment ul.payment_methods, .woocommerce-checkout #payment ul.payment_methods{
    border-bottom: none !important; 
}
</style>
<style>
/* Fila de campos 50-50 para nombre y apellido */
.checkout-fields-row {
  display: flex;
  gap: 10px;
}
.checkout-fields-row .form-row{
    width: 100% !important;
}
.checkout-field-half {
  flex: 1 1 0;
}
.checkout-field-full {
  width: 100%;
  margin-bottom: 10px;
}
.woocommerce-billing-fields__field-wrapper input,
.woocommerce-billing-fields__field-wrapper .select2-selection__rendered{
    font-weight: 300 !important;
}
/* Oculta los labels de los campos */
.woocommerce-billing-fields__field-wrapper label {
  display: none !important;
}
.form-row{
    padding: 0 !important;
    padding-bottom: 3px !important;
    
}
.form-row.place-order #place_order{
    display: none;
}
</style>

<div class="custom-checkout-container">
  <!-- Columna Izquierda: Resumen del pedido -->
  <div class="checkout-left">
    <h3>Resumen del pedido</h3>
    <div class="checkout-product-summary">
      <?php if ($product_image) : ?>
        <div class="checkout-product-img"><?php echo $product_image; ?></div>
      <?php endif; ?>
      <div class="checkout-product-details">
        <div class="checkout-product-title"><?php echo esc_html($product_title); ?></div>
       
        <div class="checkout-product-price">Precio: <?php echo $product_price; ?></div>
        <hr>
        <div class="checkout-product-qty">Cantidad: <?php echo esc_html($product_qty); ?></div>
        <hr>
        <div class="checkout-product-total"><strong>Total: <?php echo $product_total; ?></strong></div>
      </div>
    </div>
  </div>

  <!-- Columna Derecha: Step by step checkout -->
  <div class="checkout-right" id="custom-checkout-step">
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" autocomplete="on">
      <!-- Paso 1: Datos del usuario -->
      <div class="checkout-step" id="step-1">
        <h3>Pagar</h3>
        <p>Recopilamos esta información para ayudar a combatir el fraude y para que su pago se realice de forma segura.</p>
        <div class="woocommerce-billing-fields__field-wrapper">
          <div class="checkout-fields-row">
            <div class="checkout-field-half">
              <?php
                $checkout = WC()->checkout();
                $fields = $checkout->get_checkout_fields('billing');
                // Renderiza campo nombre
                echo woocommerce_form_field( 'billing_first_name', $fields['billing_first_name'], $checkout->get_value( 'billing_first_name' ) );
              ?>
            </div>
            <div class="checkout-field-half">
              <?php
                // Renderiza campo apellido
                echo woocommerce_form_field( 'billing_last_name', $fields['billing_last_name'], $checkout->get_value( 'billing_last_name' ) );
              ?>
            </div>
          </div>
          <div class="checkout-field-full">
            <?php
              // Renderiza campo email
              echo woocommerce_form_field( 'billing_email', $fields['billing_email'], $checkout->get_value( 'billing_email' ) );
            ?>
          </div>
          <div class="checkout-field-full">
            <?php
              // Renderiza campo país
              echo woocommerce_form_field( 'billing_country', $fields['billing_country'], $checkout->get_value( 'billing_country' ) );
            ?>
          </div>
        </div>
        <button type="button" id="go-to-step-2" class="button">Continuar</button>
      </div>
      <!-- Paso 2: Métodos de pago y finalizar compra -->
      <div class="checkout-step" id="step-2" style="display:none;">
        <h3>Elige tu método de pago</h3>
        <?php
          do_action( 'woocommerce_checkout_before_order_review_heading' );
          do_action( 'woocommerce_checkout_before_order_review' );
        ?>
        <div id="order_review" class="woocommerce-checkout-review-order">
          <?php do_action( 'woocommerce_checkout_order_review' ); ?>
        </div>
        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
        <!-- Botón de finalizar compra aquí -->
        <button type="submit" class="button alt" id="place_order" name="woocommerce_checkout_place_order" value="<?php esc_attr_e( 'Finalizar compra', 'woocommerce' ); ?>">
          <?php esc_html_e( 'Finalizar compra', 'woocommerce' ); ?>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- JS step-by-step -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  var step1 = document.getElementById('step-1');
  var step2 = document.getElementById('step-2');
  var continueBtn = document.getElementById('go-to-step-2');
  // Busca los campos del paso 1 dentro del form real
  var form = document.querySelector('form.checkout');
  var firstName = form ? form.querySelector('[name="billing_first_name"]') : null;
  var lastName = form ? form.querySelector('[name="billing_last_name"]') : null;
  var email = form ? form.querySelector('[name="billing_email"]') : null;
  var country = form ? form.querySelector('[name="billing_country"]') : null;

  if (form && step1 && step2 && continueBtn) {
    // Intercepta Enter en campos de step-1 para avanzar
    [firstName, lastName, email, country].forEach(function(input){
      if (input) {
        input.addEventListener('keydown', function(e){
          if (e.key === 'Enter') {
            e.preventDefault();
            continueBtn.click();
          }
        });
      }
    });

    // Botón continuar: pasa de step1 a step2
    continueBtn.addEventListener('click', function() {
      // Validación simple de campos requeridos
      var valid = true;
      [firstName, lastName, email, country].forEach(function(input){
        if (input && !input.value) {
          input.style.borderColor = 'red';
          valid = false;
        } else if (input) {
          input.style.borderColor = '';
        }
      });
      if (!valid) return;
      step1.style.display = 'none';
      step2.style.display = 'block';
      // Enfoca el primer input relevante del paso 2 (por ejemplo método de pago)
      var payInputs = step2.querySelectorAll('input,select,button');
      if (payInputs.length) payInputs[0].focus();
    });
  }
});
</script>