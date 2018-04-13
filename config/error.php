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
    5001 => 'The `uid` or `account` field must be of type: string',
    5002 => 'The `uid` field must be of type: string',
    5003 => 'The `account` field must be of type: string',
    5004 => 'The `identity_type` field is required',
    5005 => 'The `identifier` field is required',
    5006 => 'The `credential` field is required',
    5007 => 'The `credential` not allow',
    5008 => 'The `identity_type` not allow',
    5009 => 'The `identifier` length must min 6',
    5010 => 'The `credential` length must min 6',
    5011 => 'Member create field',
    5012 => 'Member already exist',

    /* sql error */ 
    6001 => 'data not found',

    /* bussiness error */
    7001 => 'private of user information',

    /* custom error */
    8000 => 'custom Error',

    /* un authorized */
    9001 => 'un authorized',

    /* unknown error */
    0 => 'no error',
);