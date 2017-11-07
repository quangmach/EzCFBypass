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
// Before of all your CODE.
require('xwaf.php');
$xWAF = new xWAF();
// Cloudflare Option [Optional]
$xWAF->useCloudflare();
// useBlazingfast Option [Optional]
$xWAF->useBlazingfast();

// Check separated types.
$xWAF->checkGET();
$xWAF->checkPOST();
$xWAF->checkCOOKIE();
// Your code below.
```

# Requirements

- [x] PHP5.x+ (With common functions, like curl)
- [x] Brain
