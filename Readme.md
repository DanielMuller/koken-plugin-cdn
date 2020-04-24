CDN plugin for Koken
====================
Disclaimer
-----------------
This repo is now archieved.

I am no longer a user of [Koken](http://koken.me/) and have migrated my Gallery to [Yapawa](https://yapawa.net/), a gallery site that I developped.

Thank you Koken Team for your awesome tool that allowed me to host my pictures during many years.

---

This [Koken](http://koken.me) plugin allows to use an alternate domain name for different asset types (images, scripts, stylesheets) and therefore route them through a CDN instead of serving them directly.

CDN requirement
---------------

The source for your CDN edges remains your website. Create an endpoint cdn.yoursite.com pointing to yoursite.com.

Due to the way `i.php` is written, the CDN edges need to able to rewrite the host header and call your website with yoursite.com and not cdn.yoursite.com.

If your CDN can't rewrite headers or force TTL's, your site needs to provide correct `Cache-Control` headers. Koken's default is to not cache scripts and stylesheets. An Apache and Nginx sample can be found inside the `server` folder.

Configuration
-------------

Once installed, you need to define the alternate domain name to use. As long as the domain is not defined, the plugin will skip rewriting the URL's.
By default all assets types are routed using the alternate domain name. You can disable depending on the type.

Compatibility
-------------
For CSS, Javascripts, Stylesheets and Icons, only relative links are rewritten to make use of the CDN. If you use the `HTML Injector` plugin, make sure to use relative links for your icons.
```
<link rel="icon" type="image/png" href="/favicon-160x160.png" sizes="160x160">
```
