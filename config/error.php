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
    5001 => 'Missing important parameters',
    5002 => 'Request parameter <%s> must be of type: number',
    5003 => 'Request parameter <%s> must be of type: string',
    5004 => 'Request parameter <%s> missing or type error',
    5005 => 'Request parameter <%s> value not allow',
    5006 => 'Request parameter <%s> value not null',
    5007 => 'Request parameter <%s> value verification failed',
    5008 => 'Request parameter <%s> value data length failed',

    /* Database error */ 
    6001 => 'Database not found data <%s>',
    6002 => 'Database update value <%s> failed',
    6003 => 'Database insert value <%s> failed',

    /* bussiness error */
    7001 => 'Member information is private',
    7002 => 'Member already exist',
    7003 => 'Member Token Invalid',

    /* custom error */
    8000 => 'custom Error',

    /* un authorized */
    9001 => 'JSON Web Token Invalid',

    /* nothing */
    0 => '',
);