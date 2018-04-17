<?php

namespace Buff\extends;

abstract class Const {
	// user identity
    const USER_IDENTITY_PHONE  = 'phone';
    const USER_IDENTITY_WEIBO  = 'weibo';
    const USER_IDENTITY_WEIXIN = 'weixin';

    // time 
    const second = 1;
    const minute = 60;
    const hour   = 3600;
    const day    = 86400;
    const week   = 604800;
    const month  = 2592000;
}