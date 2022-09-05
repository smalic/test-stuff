<?php
$client = new Q\Api\Client();

if ( ! $client->has_valid_token() ) {
    $client->logout();
}

get_header();
?>

<p>
    Hello and welcome back!
</p>

<p>
    <a href="https://q.agency/" target="_blank">Visit Q Agency</a> | <a href="<?php echo home_url( '/?q-says=goodbye' ); ?>">Log out</a>
</p>

<?php
get_footer();
