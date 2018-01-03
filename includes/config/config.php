<?php

/*
 * DB
 * Add your domain name to the DB array and set your database params
 * 
 * You can set the token_expire in hours
 */

return [
  'DB' => [
    'development' => [
      'npapi.loc' => [
        'server' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'db_name' => 'navipark',
      ],
      'api.local' => [
        'server' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'db_name' => 'valpiok_parkapi',
      ],
    ],
    'staging' => [
      'naviapi.valpio-k.com' => [
        'server' => 'valpiok.mysql.ukraine.com.ua',
        'user' => 'valpiok_parkapi',
        'pass' => 'qynabqbx',
        'db_name' => 'valpiok_parkapi',
      ],
    ],
    'production' => [
    ],
  ],
  'token_expire' => 1,
  'user_name' => 'Sasha_Grey',
  'user_pass' => 'egor007'
];
