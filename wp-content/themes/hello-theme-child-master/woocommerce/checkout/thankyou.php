<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$order_id = apply_filters( 'woocommerce_thankyou_order_id', absint( get_query_var( 'order-received' ) ) );
$order = wc_get_order( $order_id );

// Obtén el email del comprador
$email = '';
if ( $order ) {
    $email = $order->get_billing_email();
}
?>
<style>
body {
  font-family: 'Neue Montreal', Arial, sans-serif !important;
}
.custom-thankyou-container,
.custom-thankyou-form input,
.custom-thankyou-form button,
.custom-thankyou-title,
.custom-thankyou-message,
.custom-thankyou-success {
  font-family: 'Neue Montreal', Arial, sans-serif !important;
  font-weight: 300 !important;
}
.custom-thankyou-container {
  max-width: 480px;
  margin: 40px auto;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 6px 24px rgba(119, 119, 119, 0.09);
  padding: 50px;
  text-align: center;
}
.custom-thankyou-title {
  font-size: 35px;
  color: #151515;
  font-weight: 300;
  margin-bottom: 10px;
}
.custom-thankyou-message {
  font-size: 1.13em;
  margin-bottom: 22px;
  color:rgb(75, 75, 75);
  font-weight: 300;
}
.custom-thankyou-form input {
  width: 100%;
  margin-bottom: 15px;
  padding: 10px 14px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  background: #fafafa;
  font-size: 1.08em;
  color: #222;
}
.custom-thankyou-form button {
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
.custom-thankyou-form button:hover {
  background-color:rgb(55, 143, 0);
}
.custom-thankyou-success {
  color: #0071a1;
  font-weight: 300;
  font-size: 1.13em;
  margin: 20px 0 18px 0;
}
</style>

<style>
.password-wrapper {
  position: relative;
}
.password-wrapper input[type="password"],
.password-wrapper input[type="text"] {
  padding-right: 42px !important;
}
.toggle-password-btn {
  position: absolute;
  right: 16px;
  top: 40% !important;
  transform: translateY(-50%);
  cursor: pointer;
  display: flex;
  align-items: center;
  height: 24px;
  z-index: 2;
  user-select: none;
}
.toggle-password-btn svg {
  display: block;
  pointer-events: none;
}
</style>

<div class="custom-thankyou-container">
  <div class="custom-thankyou-title">¡Gracias por tu compra!</div>
  <div class="custom-thankyou-message">
    Para poder descargar el plugin, termina de configurar tu cuenta.
  </div>

  <form class="custom-thankyou-form" id="custom-register-form" method="post">
    <input type="email" name="user_email" id="user_email" placeholder="Correo electrónico" value="<?php echo esc_attr($email); ?>" readonly />
    <div class="password-wrapper" style="position: relative;">
      <input type="password" name="user_pass" id="user_pass" placeholder="Contraseña" required autocomplete="new-password" />
      <span class="toggle-password-btn" data-target="user_pass" tabindex="-1" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer; display: flex; align-items: center; height: 24px;">
        <svg class="eye-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#232323" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <ellipse cx="12" cy="12" rx="8" ry="5"/>
          <circle cx="12" cy="12" r="2.5"/>
        </svg>
      </span>
    </div>
    <div class="password-wrapper" style="position: relative;">
      <input type="password" name="user_pass_confirm" id="user_pass_confirm" placeholder="Confirmar contraseña" required autocomplete="new-password" />
      <span class="toggle-password-btn" data-target="user_pass_confirm" tabindex="-1" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer; display: flex; align-items: center; height: 24px;">
        <svg class="eye-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#232323" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <ellipse cx="12" cy="12" rx="8" ry="5"/>
          <circle cx="12" cy="12" r="2.5"/>
        </svg>
      </span>
    </div>
    <button type="submit" id="custom-register-btn">Descargar plugin</button>
  </form>
  
  <div id="custom-thankyou-success" class="custom-thankyou-success" style="display:none;">
    ¡Registro exitoso!  
    <br>
    <a href="#" class="button" id="download-plugin-btn">Descargar plugin</a>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  var form = document.getElementById('custom-register-form');
  if(form) {
    form.addEventListener('submit', function(e){
      e.preventDefault();
      var email = form.user_email.value.trim();
      var pass = form.user_pass.value;
      var pass2 = form.user_pass_confirm.value;
      if(pass !== pass2){
        alert('Las contraseñas no coinciden.');
        return;
      }
      // AJAX para registrar usuario
      var data = new FormData();
      data.append('action', 'custom_thankyou_register');
      data.append('user_email', email);
      data.append('user_pass', pass);
      data.append('order_id', '<?php echo esc_js($order_id); ?>');
      fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: data,
        credentials: 'same-origin'
      })
      .then(response => response.json())
      .then(resp => {
        if(resp.success){
          // Redirige automáticamente al home en vez de mostrar el mensaje
          window.location.href = '/';
        }else{
          alert(resp.data || 'Ocurrió un error inesperado.');
        }
      })
      .catch(() => alert('Ocurrió un error, intenta nuevamente.'));
    });
  }
  // Botón ver contraseña con SVG
  var toggleBtns = document.querySelectorAll('.toggle-password-btn');
  toggleBtns.forEach(function(btn){
    btn.addEventListener('click', function(){
      var input = document.getElementById(btn.getAttribute('data-target'));
      var svg = btn.querySelector('.eye-icon');
      if(input.type === 'password'){
        input.type = 'text';
        // Cambia a ojo cerrado: añade una línea sobre el SVG
        if (!svg.querySelector('.eye-strike')) {
          var line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
          line.setAttribute('x1', '4');
          line.setAttribute('y1', '20');
          line.setAttribute('x2', '20');
          line.setAttribute('y2', '4');
          line.setAttribute('stroke', '#C53B3B');
          line.setAttribute('stroke-width', '2');
          line.setAttribute('class', 'eye-strike');
          svg.appendChild(line);
        }
      } else {
        input.type = 'password';
        // Quita la línea (ojo abierto)
        var strike = svg.querySelector('.eye-strike');
        if (strike) svg.removeChild(strike);
      }
    });
  });
});
</script>

<?php
// AJAX handler para registrar usuario desde la página de gracias
add_action('wp_ajax_nopriv_custom_thankyou_register', 'custom_thankyou_register');
add_action('wp_ajax_custom_thankyou_register', 'custom_thankyou_register');
function custom_thankyou_register() {
    $email = isset($_POST['user_email']) ? sanitize_email($_POST['user_email']) : '';
    $pass = isset($_POST['user_pass']) ? $_POST['user_pass'] : '';
    $order_id = isset($_POST['order_id']) ? absint($_POST['order_id']) : 0;

    if (empty($email) || empty($pass)) {
        wp_send_json_error('Faltan campos obligatorios');
    }
    if (!is_email($email)) {
        wp_send_json_error('El correo no es válido');
    }
    if ($order_id <= 0) {
        wp_send_json_error('ID de pedido inválido');
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        wp_send_json_error('Pedido no encontrado');
    }

    if (username_exists($email) || email_exists($email)) {
        // Usuario existe, actualiza contraseña y vincula pedido
        $user = get_user_by('email', $email);
        if(!$user) {
            wp_send_json_error('No se pudo obtener el usuario existente');
        }
        $user_id = $user->ID;

        $password_set = wp_set_password($pass, $user_id);
        if (is_wp_error($password_set)) {
            wp_send_json_error('No se pudo actualizar la contraseña');
        }

        wp_set_auth_cookie($user_id);

        // Vincula el pedido al usuario
        $order->set_customer_id($user_id);
        $order->save();

        wp_send_json_success();
    }

    // Crea nuevo usuario
    $user_id = wp_create_user($email, $pass, $email);
    if (is_wp_error($user_id)) {
        wp_send_json_error('No se pudo crear el usuario: ' . $user_id->get_error_message());
    }

    wp_set_auth_cookie($user_id);

    // Vincula el pedido al usuario
    $order->set_customer_id($user_id);
    $order->save();

    wp_send_json_success();
}
?>