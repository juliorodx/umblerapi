<?php

namespace UmblerApi;

class Email
{

  public string $endpoint = "https://api.umbler.com/v1/emails";
  public string $basicAuth;
  public string $domain;
  public array $requestInfo;
  public bool $debug = false;

  public function getAction(string $action)
  {

    $endpoint = $this->getEndpoint($action);
    $auth = $this->getCredentials();
    $headers = [
      'Authorization: Basic ' . $auth,
      'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    $requestInfo = curl_getinfo($ch);
    curl_close($ch);

    $this->requestInfo = $requestInfo;
    $resultJson = json_decode($result, true);

    if (!$resultJson || $requestInfo['http_code'] != 200) {
      if ($this->debug) {
        $err = $this->getError($requestInfo['http_code']);
        die("[GET_ERROR] Action: {$action} | Body: {$result} | Error: {$err}");
      }
      return $result;
    }

    return $resultJson;
  }

  public function postAction(string $action, array $data = [], $type = 'POST')
  {

    $endpoint = $this->getEndpoint($action);
    $auth = $this->getCredentials();
    $payload = null;
    $headers = [
      'Authorization: Basic ' . $auth,
      'Content-Type: application/json'
    ];

    if ($data)
      $payload = json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    $requestInfo = curl_getinfo($ch);
    curl_close($ch);

    $this->requestInfo = $requestInfo;
    $resultJson = json_decode($result, true);

    if (!$resultJson || $requestInfo['http_code'] != 200) {
      if ($this->debug) {
        $err = $this->getError($requestInfo['http_code']);
        die("[{$type}_ERROR] Action: {$action} | Body: {$result} | Error: {$err}");
      }
      return $result;
    }

    return $resultJson;
  }

  public function getError($code = null)
  {
    $httpCodes = [
      200 => 'OK',
      400 => 'Bad request data, please review it',
      401 => 'Unauthorized',
      403 => 'Forbidden',
      404 => 'Not Found',
      500 => 'Error',
    ];

    return isset($httpCodes[$code]) ? $httpCodes[$code] : "Error code: {$code}";
  }

  public function setCredentials(string $userId, string $apiToken)
  {
    if (!$userId || !$apiToken) {
      return false;
    }

    $this->basicAuth = base64_encode($userId . ":" . $apiToken);
  }

  public function setDomain(string $url)
  {
    if (filter_var($url, FILTER_VALIDATE_DOMAIN))
      $this->domain = $url;
  }

  public function getEndpoint($action = null)
  {
    return $this->endpoint . "/" . $this->domain . ($action ? '/' . $action : null);
  }

  public function getCredentials()
  {
    return $this->basicAuth;
  }

  public function getEmails()
  {
    return $this->getAction('accounts');
  }

  public function getEmailAccount(string $account)
  {
    return $this->getAction('accounts/' . $account);
  }

  public function updateEmailAccount(string $account, array $data)
  {
    return $this->postAction('accounts/' . $account, $data, 'PUT');
  }
}
