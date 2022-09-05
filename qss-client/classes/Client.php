<?php

namespace Q\Api;

Class Client {
    private string $api_login_url;

    private string $api_refresh_url;

    private string $email;

    private string $password;

    private array $api_response;

    public function __construct( string $email = '', string $password = '' ) {
        $this->email = '';
        $this->password = '';

        $this->api_login_url = 'https://symfony-skeleton.q-tests.com/api/v2/token';
        $this->api_refresh_url = 'https://symfony-skeleton.q-tests.com/api/v2/token/refresh/';

        if ( ! empty( $email ) && ! empty( $password ) ) {
            $this->email = $email;
            $this->password = $password;
        }
    }

    private function set_login_cookies(): void {
        $api_response = $this->api_response;

        setcookie( 'qss_email', $api_response['user']['email'], date( 'U', strtotime( $api_response['refresh_expires_at'] ) ) );
        setcookie( 'qss_refresh_token', $api_response['refresh_token_key'], date( 'U', strtotime( $api_response['refresh_expires_at'] ) ) );
        setcookie( 'qss_is_logged_in', true, date( 'U', strtotime( $api_response['expires_at'] ) ) );
    }

    public function login(): array {
        $api = wp_remote_post( $this->api_login_url,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode(
                    [
                        'email' => $this->email,
                        'password' => $this->password,
                    ]
                ),
            ]
        );

        $parse_response = wp_remote_retrieve_body( $api );
        $response = json_decode( $parse_response, true );

        $code = $response['code'] ?? 200;

        if ( $code !== 200 ) {
            return [
                'status' => $code,
                'message' => 'Something\'s not right: ' . $response['message'],
            ];
        }

        $this->api_response = $response;
        $this->set_login_cookies();

        return [
            'status' => 200,
            'message' => 'Login successful'
        ];
    }

    private function refresh_token( string $token ): array {
        $api = wp_remote_get( $this->api_refresh_url . $token,
            [
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]
        );

        $parse_response = wp_remote_retrieve_body( $api );
        $response = json_decode( $parse_response, true );

        $code = $response['code'] ?? 200;

        if ( $code !== 200 ) {
            return [
                'status' => $code,
                'message' => 'The token could not be retrieved.',
            ];
        }

        $this->api_response = $response;
        $this->set_login_cookies();

        return [
            'status' => 200,
            'message' => 'Token refreshed successfully.'
        ];

    }

    public function has_valid_token(): bool {
        $is_logged_in = $_COOKIE['qss_is_logged_in'] ?? false;

        if ( $is_logged_in ) {
            return true;
        }

        $token = $_COOKIE['qss_refresh_token'] ?? '';

        $refresh = $this->refresh_token( $token ) ?? ['status' => 400];

        return $refresh['status'] === 200;
    }

    private function clear_cookies(): void {
        setcookie( 'qss_email', '', 0 );
        setcookie( 'qss_refresh_token', '', 0 );
        setcookie( 'qss_is_logged_in', '', 0 );
    }

    public function logout(): void {
        $this->clear_cookies();

        header( 'Location: ' . home_url('/q-login') );
        die();
    }

    public function redirect_to_profile(): void {
        header( 'Location: ' . home_url('/q-profile') );
        die();
    }
}
