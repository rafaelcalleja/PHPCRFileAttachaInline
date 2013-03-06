PHPCRFileAttachaInline
======================

# Installation


## Step 1: Installation

...
php composer.phar install rc/phpcr-fai-bundle
...


## Step 2: Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...

        new RC\PHPCR\FileAttachInlineBundle\RCPHPCRFileAttachInlineBundle(),
    );
}
```

## Step 3: Register the bundle's routes

add the following to your routing file:

``` yaml
# app/config/routing.yml

_rcfia:
    resource: .
    type: rcfia
```


# Configuration

The default configuration for the bundle looks like this:

``` yaml
rcphpcr_file_attach_inline:
    web_root:             %kernel.root_dir%/../web
    controller_action:    rc_phpcr.controller:FindAction
    extensions:
        - pdf
    preference:
        - phpcr
        - filesystem
    max_filesize: 3072k
    mimetypes:
        - application/pdf
        - application/x-pdf
    providers:
        phpcr:
            locales: %locales%
            field: filePath
            multilang: true
            field_title: phpcr_locale:{_locale}-title
```

## Apache Rewrite rule
run:
``` php
app/console rc:apache:dump
```

paste result like your first rule in web/.htaccess

example:
```
# web/.htacess
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} ^/(.*\.(pdf|zip|doc|txt|PDF|ZIP|DOC|TXT))$
    RewriteRule .* app.php [QSA,L,E=_ROUTING__route:rcphpcr_file_attach_inline_homepage,E=_ROUTING_file:%1,E=_ROUTING_DEFAULTS__controller:RC\\PHPCR\\FileAttachInlineBundle\\Controller\\DefaultController\:\:FindAction]
    
    RewriteCond %{SCRIPT_FILENAME} !-d
    RewriteRule ^(.*)/$ /$1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ app.php [QSA,L]
</IfModule>
```
