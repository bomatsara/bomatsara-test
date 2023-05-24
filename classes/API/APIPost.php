<?php

namespace BomatsaraTest\API;

class APIPost
{
    //I understand that storing data here is not secure. The data in the class is only for the test task
    private const API_URL = 'https://my.api.mockaroo.com/posts.json';
    private const API_KEY = '413dfbf0';

    public function get_data(): ?array
    {
        $args = [
            'headers' => [
                'X-API-Key' => self::API_KEY
            ]
        ];

        $response = wp_remote_get(self::API_URL, $args);
        $body = wp_remote_retrieve_body($response);

        if (is_wp_error($body)) {
            return null;
        }

        $data = json_decode($body, true);
        return $data;
    }
}