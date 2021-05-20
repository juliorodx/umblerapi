<?php

namespace UmblerApi;

/**
 * UmblerApi class for Umbler API v1
 *
 * @link https://api.umbler.com/docs/index.html
 * @todo Full documentation
 * @author Júlio Rodrigues <julio@ametizze.com.br>
 */
class UmblerApi
{

  public string $basicAuthToken;
  public string $domain;
  public array $requestInfo;
  protected string $userId;
  protected string $apiToken;

  /**
   * Defined vars
   */
  public string $endpoint = "https://api.umbler.com/v1";
  public bool $debug = false;

  public function setCredentials(string $userId, string $apiToken)
  {
    if (!$userId || !$apiToken) {
      return false;
    }

    $this->userId = $userId;
    $this->apiToken = $apiToken;

    /**
     * Set a base64 into basicAuthToken
     */
    $this->basicAuthToken = base64_encode($userId . ":" . $apiToken);
  }

  public function getCredentials()
  {
    return $this->basicAuthToken;
  }

  public function getAction(string $action)
  {
    if (!$action) return null;

    $endpoint = $this->endpoint . $action;
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
        die("[GET_ERROR] Endpoint: {$endpoint} | Action: {$action} | Body: {$result} | Error: {$err}");
      }
      return $result;
    }

    return $resultJson;
  }

  public function postAction(string $action, array $data = [], $type = 'POST')
  {

    if (!$action) return null;

    $endpoint = $this->endpoint . $action;
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
        die("[GET_ERROR] Endpoint: {$endpoint} | Action: {$action} | Body: {$result} | Error: {$err}");
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

  public function setDomain(string $url)
  {
    if (filter_var($url, FILTER_VALIDATE_DOMAIN))
      $this->domain = $url;
  }

  public function getEndpoint()
  {
    return $this->endpoint;
  }

  /**
   * Get a list of Email Domains
   * Returns a list of Email Domains linked with your user
   *
   * @endpoint GET /v1/emails
   *
   * @return array
   */
  public function getEmailsDomains()
  {
    return $this->getAction('/emails');
  }

  /**
   * Get a list Available Email Plans
   *
   * @endpoint GET /v1/emails/{domain}/available-email-plans
   *
   * @return array
   */
  public function getAvailableEmailPlans()
  {
    return $this->getAction("/emails/{$this->domain}/available-email-plans");
  }

  /**
   * Get a specific Email Domain
   *
   * @endpoint GET /v1/emails/{domain}/
   *
   * @return array
   */
  public function getEmailDomain()
  {
    return $this->getAction("/emails/{$this->domain}");
  }

  /**
   * Update an Email Domain
   *
   * @endpoint PUT /v1/emails/{domain}
   *
   * @param string $account
   * @param array $data
   *
   * @return array
   */
  public function updateEmailAccount(string $account, array $data)
  {
    return $this->postAction('/emails/accounts/' . $account, $data, 'PUT');
  }

  /**
   * Get a list Email Accounts
   *
   * @endpoint GET /v1/emails/{domain}/accounts
   *
   * @return array
   */
  public function getEmailAccounts()
  {
    return $this->getAction("/emails/{$this->domain}/accounts");
  }

  /**
   * Update an Email Domain
   *
   * @endpoint POST /v1/emails/{domain}/accounts
   *
   * @param array $data
   *
   * @return array
   */
  public function createEmailAccount(array $data)
  {
    return $this->postAction("/emails/{$this->domain}/accounts", $data, 'POST');
  }

  /**
   * Delete a specific Email Account
   *
   * @endpoint DELETE /v1/emails/{domain}/accounts/{emailAccount}
   *
   * @param array $data
   *
   * @return array
   */
  public function deleteEmailAccount(string $account)
  {
    return $this->postAction("/emails/{$this->domain}/accounts/{$account}/", [], 'DELETE');
  }
}
