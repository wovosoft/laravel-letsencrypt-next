<?php
return [
    "applications" => [
        "Mahfuz POS"    => [
            "domain"                            => "mahfuj-pos.wovosoft.com",
            "email"                             => "narayanadhikary24@gmail.com",
            "method"                            => "live",
            "http_verification_file_store_path" => base_path("apps/mahfuj-pos/public"),
            "ssl_store_path"                    => base_path("apps/mahfuj-pos/ssl"),
        ],
        "BKB Resources" => [
            "domain"                            => "bkb-resources.bkbjanala.site",
            "email"                             => "narayanadhikary24@gmail.com",
            "method"                            => "live",
            "http_verification_file_store_path" => "/home/wovosoft/Downloads/letsencrypt/bkb-resources",
            "ssl_store_path"                    => "/home/wovosoft/Downloads/letsencrypt/bkb-resources",
        ],
        "HRMS"          => [
            "domain"                            => "hrms.krishibank.org.bd",
            "email"                             => "narayanadhikary24@gmail.com",
            "method"                            => "live",
            "http_verification_file_store_path" => "/home/wovosoft/Downloads/letsencrypt/hrms",
            "ssl_store_path"                    => "/home/wovosoft/Downloads/letsencrypt/hrms",
        ]
    ]
];
