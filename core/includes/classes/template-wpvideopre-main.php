<?php 

 global $wpdb;

        // Define your table name with the $wpdb->prefix to ensure it respects the WordPress prefix
        $table_name = $wpdb->prefix . 'wpvideopre_videos';
 
        // Perform the query to get all rows from the table
        $results = $wpdb->get_results("SELECT * FROM $table_name");
?>

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
