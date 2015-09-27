<?php

return [
    'class' => 'yii\authclient\Collection',
    'clients' => [
        'facebook' => [
            'class' => 'yii\authclient\clients\Facebook',
            'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
            'clientId' => '1636062856635021',
            'clientSecret' => 'c1d4428e55dfa2677d61466a51e5b02b',
            'scope' => 'public_profile,user_friends'
        ],
    ],
];
