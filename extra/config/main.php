<?php

/*
 * Config override to use the findcontact-hostbill
 */

return [
    'external' => [
        'prefer_local'                      => true,
        'findcontact'                       => [
            'id' => [
                [
                    'class'                     => 'Hostbill',
                    'method'                    => 'getContactById',
                ],
            ],
            'ip' => [
                [
                    'class'                     => 'Hostbill',
                    'method'                    => 'getContactByIp',
                ],
            ],
            'domain' => [
                [
                    'class'                     => 'Hostbill',
                    'method'                    => 'getContactByDomain',
                ],
            ],
        ],
    ],
];
