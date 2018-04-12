<?php
return array(
    /* core error */
    1000 => 'System Error',

    /* controller error */
    2001 => 'Method not exists',

    /* DAO error */
    3001 => 'Connect mysql is fail',

    /* Connect error */
    4001 => 'socket Create Error',
    4002 => 'Socket Connect Error',
    4003 => 'Socket Len Error',
    4004 => 'Memcache Connect Error',
    4005 => 'Redis Connect Error',

    /* request error */
    5001 => 'missing paramter',

    /* sql error */ 
    6001 => 'data not found',

    /* bussiness error */
    7001 => 'private of user information',

    /* custom error */
    8000 => 'custom Error',

    /* unknown error */
    0 => 'no error',
);