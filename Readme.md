CDN plugin for Koken
====================

This [Koken](http://koken.me) plugin allows to use an alternate domain name for different asset types (images, scripts, stylesheets) and thereofre route them through a CDN instead of serving them directly.

CDN requirement
---------------

The source for your CDN edges remains your website. Create an endpoint cdn.yoursite.com pointing to yoursite.com.

Due to the way `i.php` is written, the CDN edges need to able to rewrite the host header and call your website with yoursite.com and not cdn.yoursite.com.

If your CDN can't rewrite headers or force TTL's, your site needs to provide correct `Cache-Control` headers. Koken's default is to not cache scripts and stylesheets. An Apache and Nginx sample can be found inside the `server` folder.

Configuration
-------------

Once installed, you need to define the alternate domain name to use. As long as the domain is not defined, the plugin will skip rewriting the URL's.
By default all assets types are routed using the alternate domain name. You can disable depending on the type.