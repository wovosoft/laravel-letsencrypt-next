<?php
return [
    "skip_showing_defaults" => true,
    "applications"          => [
        "Kalam POS, Thakurgaon" => [
            "domain" => "kalamstore-pos.wovosoft.com",
            "email"  => "narayanadhikary24@gmail.com",
            "method" => "live",

            "should_upload_to_host" => true,
            "should_update_ssl"     => true,

            "http_verification_file_store_path" => "pos/kalamstore-pos.wovosoft.com/public/.well-known/acme-challenge",
            "ssl_store_path"                    => "/home/wovosoft/Downloads/letsencrypt/kalam-pos",
        ],

        "Mahfuz POS"            => [
            "domain" => "mahfuj-pos.wovosoft.com",
            "email"  => "narayanadhikary24@gmail.com",
            "method" => "live",

            "should_upload_to_host" => true,
            "should_update_ssl"     => true,

            "http_verification_file_store_path" => "pos/mahfuj-pos.wovosoft.com/public/.well-known/acme-challenge",
            "ssl_store_path"                    => "/home/wovosoft/Downloads/letsencrypt/mahfuz-pos",
        ],
        "BKB Resources"         => [
            "domain"                            => "bkb-resources.bkbjanala.site",
            "email"                             => "narayanadhikary24@gmail.com",
            "method"                            => "live",
            "http_verification_file_store_path" => "/home/wovosoft/Downloads/letsencrypt/bkb-resources",
            "ssl_store_path"                    => "/home/wovosoft/Downloads/letsencrypt/bkb-resources",
        ],
        "HRMS"                  => [
            "domain"                            => "hrms.krishibank.org.bd",
            "email"                             => "narayanadhikary24@gmail.com",
            "method"                            => "live",
            "http_verification_file_store_path" => "/home/wovosoft/Downloads/letsencrypt/hrms",
            "ssl_store_path"                    => "/home/wovosoft/Downloads/letsencrypt/hrms",
        ],
        "Laravel Let's Encrypt" => [
            "domain"                            => "letsencrypt.wovosoft.com",
            "email"                             => "narayanadhikary24@gmail.com",
            "method"                            => "live",
            "should_upload_to_host"             => true,
            "should_update_ssl"                 => true,
            //path on server
            "http_verification_file_store_path" => "letsencrypt.wovosoft.com/public/.well-known/acme-challenge",
            "ssl_store_path"                    => "/home/wovosoft/Downloads/letsencrypt/hrms",
        ]
    ]
];
