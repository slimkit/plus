<?php

/**
 * 阿里大于短信信息配置.
 */

return [
    'app_key'            => env('ALIDATY_APP_KEY', null),
    'app_secret'         => env('ALIDAYU_APP_SECRET', null),
    'sandbox'            => false,
    'sign_name'          => env('ALIDAYU_FREE_SIGN_NAME', 'Seven的代码'),
    'verify_template_id' => env('ALIDAYU_VERIFY_TEMPLATE_ID', null),
];
