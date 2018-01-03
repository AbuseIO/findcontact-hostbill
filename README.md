# findcontact-hostbill
findcontact module for IP lookups using the hostbill Api

## Beta
This software is in beta. Please test and report back to us.

## Installation
    
    composer require abuseio/findcontact-plesk
     
## Use the findcontact-plesk module
copy the ```extra/config/main.php``` to the config override directory of your environment (e.g. production)

#### production

    cp vendor/abuseio/findcontact-plesk/extra/config/main.php config/production/main.php
    
#### development

    cp vendor/abuseio/findcontact-plesk/extra/config/main.php config/development/main.php
    
add the following line to providers array in the file config/app.php:

    'AbuseIO\FindContact\Hostbill\HostbillServiceProvider'
    
## Configuration


Replace the null value in ````'appid' => null,```` with your application id, e.g.
    
    <?php
    
    return [
        'findcontact-hostbill' => [           
            'appid'          => 'MyAppId,
            'enabled'        => true,
            'auto_notify'    => false,
        ],
    ];

