<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>

<div class="custom-page-content">
    <h1><?php the_title(); ?></h1>
    <div><?php the_content(); ?></div>
</div>

<?php
if ( function_exists( 'block_template_part' ) ) {
    block_template_part( 'footer' );
} else {
    get_footer();
}
?>
</body>
</html>
