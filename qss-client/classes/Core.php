<?php
namespace Q;

use Q\Api\Client;

Class Core {

    public function __construct() {
        add_filter( 'page_template', [ $this, 'set_template' ] );
        add_action( 'wp', [ $this, 'do_login' ] );
        add_action( 'wp', [ $this, 'do_logout' ] );
    }

    public function set_template( string $page_template ): string {
        global $post;

        $templates = [
            'q-login' => 'templates/login.php',
            'q-profile' => 'templates/profile.php',
        ];

        if ( isset( $templates[$post->post_name] ) ) {
            $page_template = QSS_CLIENT_PLUGIN . $templates[$post->post_name];
        }

        return $page_template;
    }

    public function do_login(): void {
        global $post;

        if ( $post->post_name !== 'q-login' ) {
            return;
        }

        $nonce = $_POST['qss-nonce'] ?? '';

        if ( ! wp_verify_nonce( $nonce, 'qss-login' ) ) {
            return;
        }

        $client = new Client( sanitize_email( $_POST['email'] ), sanitize_text_field( $_POST['password'] ) );
        $login = $client->login();

        if ( $login['status'] === 200 ) {
            $this->flash_notice( 'Login successful! Now redirecting... <meta http-equiv="refresh" content="3;URL=' . home_url('/q-profile') . '" />' );
        }
        else {
            $this->flash_notice( 'Your credentials seem to be incorrect, please try again.' );
        }
    }

    public function flash_notice( string $notice ): void {
        add_filter( 'qss_notices', function() use ( $notice ) {
            return $notice;
        } );
    }

    public function do_logout(): void {
        $logout = $_GET['q-says'] ?? '';

        if ( $logout !== 'goodbye' ) {
            return;
        }

        $client = new Client();
        $client->logout();
    }
}
