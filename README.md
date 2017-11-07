# EzCFBypass - Cloudflare UAM Bypasser
Cloudflare UAM Bypasser PHP Free, Open-Source.

# Features

- [x] Bypass on 8 seconds UAM Cloudflare 95% Success.
- [x] GET and POST Requests allowed.
- [x] SSL Support.
- [x] Cookies allowed.
- [x] Lightweight.

# Sample Usage
```php
// Include the class..
require_once "EzCFBypass.php";

$cf = new \Alemalakra\Bypass\CloudFlare("nstress.com", array('ssl' => true));
// Simple GET Request.
echo htmlentities($cf->get("/"));
```
# Advanced Usage
```php
// Include the class..
require_once "EzCFBypass.php";

$cf = new \Alemalakra\Bypass\CloudFlare("nstress.com", array('ssl' => true));
// Simple POST Request.
echo htmlentities($cf->post("/login.php", array('username' => 'test', 'password' => 'test')));
// Cookie ARRAY List.
print_r($cf->getCookies());
```

# Requirements

- [x] PHP5.x+ (With common functions, like curl)
- [x] Brain
