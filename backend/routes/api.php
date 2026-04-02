<?php

/*
|--------------------------------------------------------------------------
| API Routes Proxy
|--------------------------------------------------------------------------
| This file proxies all requests to api_v1.php to maintain backward
| compatibility with the /api prefix while using /api/v1 as the
| primary versioned route structure.
*/

require __DIR__.'/api_v1.php';
