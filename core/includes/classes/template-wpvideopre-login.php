<?php

if ( isset($_POST['login']) ) {
    $creds = array(
        'user_login'    => $_POST['username'],
        'user_password' => $_POST['password'],
        'remember'      => isset($_POST['remember']) ? true : false
    );
    $user = wp_signon( $creds, false );

    if ( is_wp_error( $user ) ) {
        $error = $user->get_error_message();
    } else {
        wp_redirect(  '/wpvideopre' );
        exit;
    }
}
 if (is_user_logged_in()) {
 
     include WPVIDEOPRE_PLUGIN_DIR. 'core/includes/classes/template-wpvideopre-main.php';
 } else{?>


<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>

<div class="custom-login-page">
    <h1>Login</h1>

    <?php if ( isset($error) ) : ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <p>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
        </p>
        <p>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <label for="remember">
                <input type="checkbox" name="remember" id="remember"> Remember Me
            </label>
        </p>
        <p>
            <input type="submit" name="login" value="Login">
        </p>
    </form>
</div>

 
    <style>
        .custom-login-page {
            
    max-width: 400px;
    margin: 100px auto;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.custom-login-page h1 {
    text-align: center;
    margin-bottom: 20px;
}

.custom-login-page form p {
    margin-bottom: 15px;
}

.custom-login-page form input[type="text"],
.custom-login-page form input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.custom-login-page form input[type="submit"] {
    width: 100%;
    padding: 10px;
    background: #0073aa;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.custom-login-page form input[type="submit"]:hover {
    background: #005177;
}

.custom-login-page .error-message {
    color: #d9534f;
    margin-bottom: 15px;
    text-align: center;
}

    </style>
</body>
</html>

 <?php } ?>