<?php namespace MX\Push;

use MX\RestManager;

abstract class PushServiceProvider
{
    /**
     * Base API URL
     *
     * @var string
     */
    protected $m_baseUrl;

    /**
     * API Key
     *
     * @var string
     */
    protected $m_apiKey;

    /**
     * API Username (when using HTTP Auth)
     *
     * @var string
     */
    protected $m_apiUser;

    /**
     * API Password (when using HTTP Auth)
     *
     * @var string
     */
    protected $m_apiPass;

    /**
     * MXRestManager Resource
     *
     * @var RestManager
     */
    protected $m_restManager;

    /**
     * Constructor
     *
     * Initializes api url and keys
     *
     * @param   string  $baseUrl Base API URL
     * @param   string  $apiKey=null API Key
     * @param   string  $apiUser=null API Username (if HTTP Auth)
     * @param   string  $apiPass=null API Password (if HTTP Auth)
     */
    public function __construct(
        $baseUrl,
        $apiKey = null,
        $apiUser = null,
        $apiPass = null
    ) {
        $this->m_baseUrl = $baseUrl;
        $this->m_apiKey = $apiKey;
        $this->m_apiUser = $apiUser;
        $this->m_apiPass = $apiPass;

        $this->m_restManager = new RestManager($baseUrl, $apiUser, $apiPass);
    }

    /**
     * Last API HTTP Code
     *
     * @return int  The last HTTP code returned by the API
     */
    public function lastApiCode()
    {
        return $this->m_restManager->response('headers', 'Code');
    }

    /**
     * Last API raw body
     *
     * @return string   The last raw body returned by the API
     */
    public function lastApiBody()
    {
        return $this->m_restManager->response('raw_body');
    }

    /**
     * Prepare a push message for Android
     *
     * Prepares a push message and returns the payload
     *
     * @param   array   $tokens         Array of push tokens to send the push to
     * @param   string  $message        Message to send
     * @param   bool    $toJson=false   Json encoded
     * @return  array|string   Prepared payload
     */
    protected function prepareAndroid(
        array $tokens,
        $message,
        $extra = null,
        $toJson = false
    ) {
        return $toJson ? '{}' : array();
    }

    /**
     * Prepare a push message for Blackberry
     *
     * Prepares a push message and returns the payload
     *
     * @param   array   $tokens         Array of push tokens to send the push to
     * @param   string  $message        Message to send
     * @param   bool    $toJson=false   Json encoded
     * @return  array|string   Prepared payload
     */
    protected function prepareBB(
        array $tokens,
        $message,
        $extra = null,
        $toJson = false
    ) {
        return $toJson ? '{}' : array();
    }

    /**
     * Prepare a push message for iOS
     *
     * Prepares a push message and returns the payload
     *
     * @param   array   $tokens         Array of push tokens to send the push to
     * @param   string  $message        Message to send
     * @param   bool    $toJson=false   Json encoded
     * @return  array|string   Prepared payload
     */
    protected function prepareIOS(
        array $tokens,
        $message,
        $extra = null,
        $toJson = false
    ) {
        return $toJson ? '{}' : array();
    }

    /**
     * Prepare a push message for Windows 8
     *
     * Prepares a push message and returns the payload
     *
     * @param   array   $tokens         Array of push tokens to send the push to
     * @param   string  $message        Message to send
     * @param   bool    $toJson=false   Json encoded
     * @return  array|string   Prepared payload
     */
    protected function prepareWin8(
        array $tokens,
        $message,
        $extra = null,
        $toJson = false
    ) {
        return $toJson ? '{}' : array();
    }

    /**
     * Prepare a push message for Windows Phone
     *
     * Prepares a push message and returns the payload
     *
     * @param   array   $tokens         Array of push tokens to send the push to
     * @param   string  $message        Message to send
     * @param   bool    $toJson=false   Json encoded
     * @return  array|string   Prepared payload
     */
    protected function prepareWP(
        array $tokens,
        $message,
        $extra = null,
        $toJson = false
    ) {
        return $toJson ? '{}' : array();
    }

    /**
     * Prepare a push message
     *
     * Prepares a push message and return the payload
     *
     * @param   array   $dest Associative array of 'platorm' => array('tokens')
     *                        to send the push to
     * @param   string  $message    Message to send
     * @param   bool    $toJson=false   Json encoded
     * @return  string  Prepared payload, JSON format
     */
    protected function prepare(array $dest, $message, $extra = null, $toJson = false)
    {
        $platforms = array_unique(array_keys($dest));
        $pushObject = array();

        foreach ($platforms as $platform) {
            $platformFunc = 'prepare'.ucfirst($platform);
            $pushObject = array_merge_recursive(
                $pushObject,
                $this->$platformFunc($dest[$platform], $message)
            );
        }

        return $toJson ? json_encode($pushObject) : $pushObject;
    }

    /**
     * Validate a payload
     *
     * @param   array   $payload    The payload to validate
     * @return  bool    Payload validity
     */
    protected function validate($payload)
    {
        return false;
    }

    /**
     * Sends a payload
     *
     * @param   array   $payload    The payload to send
     * @return  bool    Payload sent
     */
    protected function send($payload)
    {
        return false;
    }
}
