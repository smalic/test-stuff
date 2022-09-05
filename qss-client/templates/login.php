<?php
$client = new Q\Api\Client();

if ( $client->has_valid_token() ) {
    $client->redirect_to_profile();
}

get_header();

$notices = apply_filters( 'qss_notices', '' );

if ( ! empty( $notices ) ) {
    ?>
    <div class="qss-notices">
        <?php echo $notices; ?>
    </div>
    <?php
}
?>

    <form action="<?php echo home_url( '/q-login' ); ?>" method="post">
        <label for="email">
            <span>Email:</span>
            <input type="email" name="email" id="email">
        </label>

        <label for="password">
            <span>Password:</span>
            <input type="password" name="password" id="password">
        </label>

        <input type="hidden" name="qss-nonce" value="<?php echo wp_create_nonce( 'qss-login' ) ?>">

        <input type="submit" value="Log me in!">
    </form>

<?php
get_footer();
