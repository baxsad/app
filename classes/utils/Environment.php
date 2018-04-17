<?php

namespace Buff\classes\utils;

class Environment
{
	/** BASE64
     *（不要想象自己说的每句话，都是真理，但要保证自己说的每句话都是真话。） 
     */
	public static $jwtSecretKey = 
<<<EOD
-----BEGIN SECRET KEY-----
5LiN6KaB5oOz6LGh6Ieq5bex6K+055qE5q+P5Y+l6K+d77yM6YO95piv55yf55CG
77yM5L2G6KaB5L+d6K+B6Ieq5bex6K+055qE5q+P5Y+l6K+d6YO95piv55yf6K+d
44CC
-----END SECRET KEY-----
EOD;

    /** BASE64
     *（世界上一成不变的东西，只有“任何事物都是在不断变化的”这条真理。） 
     */
    public static $pmsSecretKey = 
<<<EOD
-----BEGIN SECRET KEY-----
5LiW55WM5LiK5LiA5oiQ5LiN5Y+Y55qE5Lic6KW/77yM5Y+q5pyJ4oCc5Lu75L2V
5LqL54mp6YO95piv5Zyo5LiN5pat5Y+Y5YyW55qE4oCd6L+Z5p2h55yf55CG44CC
-----END SECRET KEY-----
EOD;

	public static $jwtAlgorithm = 'HS256';
}