<?php
/**
 *  EzCFBypass 1.0 - Cloudflare UAM Bypasser PHP Free, Open-Source.
 *
 *  @author Alemalakra
 *  @version 1.0
 */

// Include the class..
require_once "EzCFBypass.php";

$cf = new \Alemalakra\Bypass\CloudFlare("nstress.com", array('ssl' => true));
// Simple GET Request.
echo htmlentities($cf->get("/"));