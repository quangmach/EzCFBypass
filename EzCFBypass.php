<?php
/**
 *  EzCFBypass 1.0 - Cloudflare UAM Bypasser PHP Free, Open-Source.
 *
 *  @author Alemalakra
 *  @version 1.0
 */
namespace Alemalakra\Bypass;
require "vendor/autoload.php";

class CloudFlare {
  private $target;
  private $client;
  private $request;
  private $cookieJar; // Cookies for bypass
  private $cookieExists = false;
  const WAIT_RESPONSE_CODE = 503; // Cloudflare
  const SERVER_NAME = "cloudflare-nginx"; // Cloudflare
  const USER_AGENT = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/50.0.2661.102 Chrome/50.0.2661.102 Safari/537.36";  // You can change, IMPORTANT: put a valid user agent
  const REFRESH_EXPRESSION = "/8;URL=(\/cdn-cgi\/l\/chk_jschl\?pass=[0-9]+\.[0-9]+-.*)/"; // Never change this... or code will break
  public function __construct($targetSite, $configuration = ['ssl' => false]) // Initial
  {
    if (substr($targetSite, 0, 7) == "http://") {
      // okay.
    } elseif (substr($targetSite, 0, 8) == "https://") {
      // okay!
    } else {
      if ($configuration["ssl"] == true) {
        $targetSite = "https://" . $targetSite;
      } else {
        $targetSite = "http://" . $targetSite;
      }
    }
    $this->target = $targetSite;
    $jar = new \GuzzleHttp\Cookie\FileCookieJar("/tmp/ezcfbypass");
    $config = [
      "cookies" => $jar,
      "http_errors" => false
    ];
    $this->client = new \GuzzleHttp\Client($config);
    $this->initialRequest();
  }
  private function verifyPage($response) // Check if is cloudflare.
  {
    return ($response->getHeader("Server")[0] == self::SERVER_NAME && $response->getStatusCode() == self::WAIT_RESPONSE_CODE);
  }
  function getCookies() {
    $cookieJar = $this->client->getConfig('cookies');
    return $cookieJar->toArray();
  }

  private function getCookie() // Bypass Cloudflare
  {
    $refreshHeader = $this->request->getHeader("Refresh")[0];
    $followLocation = $this->target.$this->parseRefresh($refreshHeader);
    $data = $this->getHeaderData();
    $data['Referer'] = $this->target;
    $this->request = $this->client->request(
      "GET",
      $followLocation,
      $data
    );
  }
  private function initialRequest() // INIT Bypass
  {
    $this->request = $this->client->request(
      "GET",
      $this->target,
      $this->getHeaderData(),
      [
        'allow_redirects' => true
      ]
    );
    if(!$this->cookieExists)
    {
      if($this->verifyPage($this->request))
      {
        sleep(8); // 8 Seconds, due cloudflare use 5-7 seconds
        $this->getCookie();
      }
    }
  }
  private function parseRefresh($header) // parse header
  {
    $matchURL = preg_match(self::REFRESH_EXPRESSION, $header, $match);
    if($matchURL)
    {
      return $match[1];
    }
    throw new \Exception("EzCFBypass: Can not seem to parse the refresh header", 2);
  }
  public function get($uri) // Requests of user
  {
    $this->request = $this->client->request(
      "GET",
      $this->target . $uri,
      $this->getHeaderData(),
      [
        'allow_redirects' => true
      ]
    );
    if (strpos($this->request->getBody(), '<form id="challenge-form" action="/cdn-cgi/l/chk_jschl" method="get">') !== false) {
        return "This website cant be bypassed correctly, try again.";
    }
    return $this->request->getBody();
  }

  public function post($uri, $data) // Send post data on requeest
  {
    $header = $this->getHeaderData();
    $header['form_params'] = $data;
    $this->request = $this->client->request(
      "POST",
      $this->target . $uri,
      $header, [
    'form_params' => [
        $data
    ]
]);
    if (strpos($this->request->getBody(), '<form id="challenge-form" action="/cdn-cgi/l/chk_jschl" method="get">') !== false) {
        return "This website cant be bypassed correctly, try again.";
    }
    return $this->request->getBody();
  }
  private function getHeaderData() { // Header for requests
    return [
        "User-Agent" => self::USER_AGENT,
        "Accept" => "*/*",
        "Alemalakra-Love-you" => "Of Course",
        "Accept-Encoding" => "gzip, deflate, sdch",
        "Accept-Language" => "en-GB,en-US;q=0.8,en;q=0.6"
      ];
  }

}
