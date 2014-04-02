<?php namespace MX\Push;

/**
 * TODO
 * - Write this header
 * - Merge same messages to one pushObject
 */
class UrbanAirshipService extends PushServiceProvider
{
    /**
     * Constructor
     *
     * Initializes api url and keys
     *
     * @param   string  $baseUrl Base API URL
     * @param   string  $apiKey API Key
     * @param   string  $apiMasterSecret API Master Secret
     * \exception PushServiceException If a parameter is empty
     */
    public function __construct($baseUrl, $apiKey, $apiMasterSecret)
    {
        if (empty($baseUrl) || empty($apiKey) || empty($apiMasterSecret)) {
            throw new PushServiceException('Every arguments must be non-empty');
        }

        parent::__construct($baseUrl, null, $apiKey, $apiMasterSecret);

        $this->m_restManager->setHeaders(array(
            'Accept'        => 'application/vnd.urbanairship+json; version=3;',
            'Content-Type'  => 'application/json',
        ));
    }

    /**
     * Prepare a push message for iOS
     *
     * Prepares a push message and returns the payload
     *
     * @param   array   $tokens         Array of push tokens to send the push to
     * @param   string  $message        Message to send
     * @param   bool    $toJson=false   Json encoded
     * @return  array   Prepared payload
     */
    public function prepareIOS(array $tokens, $message, $extra = null, $toJson = false)
    {
        $pushObject = array();

        $pushObject['audience'] = array(
            'device_token'      => count($tokens) > 1 ? $tokens : $tokens[0], // iOS Specific
        );
        $pushObject['notification'] = array(
            'alert'             => $message,
            'ios'               => array(
                // 'alert'             => array(           // Optional if alert, else override
                //     'body'              => $message,
                //     'action-loc-key'    => $message,    // Lock key
                //     'loc-key'           => $message,
                //     'loc-args'          => array(),
                //     'launch-image'      => $message,
                // ),
                'badge'             => 'auto',
                'sound'             => 'default',
                // 'content_available' => true,            // Newsstand / backgroung downloads
                // 'extra'             => 'arbitrary',     // Arbitrary data to send
                // 'expiry'            => ?,               // 0 === "now or never"
                // 'priority'          => 10,              // 10 === 'immediate delivery'
            ),
        );
        $pushObject['device_types'] = array(  // "all" as value is available
            'ios',
        );
        // $pushObject['options'] = ; // Options
        // $pushObject['message'] = ; // Rich Push

        return $toJson ? json_encode($pushObject) : $pushObject;
    }

    /**
     * Prepare a push message for Windows Phone
     *
     * Prepares a push message and returns the payload
     *
     * @param   array   $tokens         Array of push tokens to send the push to
     * @param   string  $message        Message to send
     * @param   bool    $toJson=false   Json encoded
     * @return  array   Prepared payload
     */
    public function prepareWP(array $tokens, $message, $extra = null, $toJson = false)
    {
        $pushObject = array();

        $pushObject['audience'] = array(
            'mpns'      => count($tokens) > 1 ? $tokens : $tokens[0], // WP Specific
        );
        $pushObject['notification'] = array(
            'alert'             => $message,
            // 'mpns'               => array(   // If an override is needed
            //     'alert' => $message,         //
            //     'toast' => $message,         // this object must contain
            //     'tile'  => $message,         //
            // ),                               // ONE of these attributes
        );
        $pushObject['device_types'] = array(  // "all" as value is available
            'mpns',
        );
        // $pushObject['options'] = ; // Options
        // $pushObject['message'] = ; // Rich Push

        return $toJson ? json_encode($pushObject) : $pushObject;
    }

    /**
     * Prepare a push message for Windows 8
     *
     * Prepares a push message and returns the payload
     *
     * @param   array   $tokens         Array of push tokens to send the push to
     * @param   string  $message        Message to send
     * @param   bool    $toJson=false   Json encoded
     * @return  array   Prepared payload
     */
    public function prepareWin8(array $tokens, $message, $extra = null, $toJson = false)
    {
        $pushObject = array();

        $pushObject['audience'] = array(
            'wns'      => count($tokens) > 1 ? $tokens : $tokens[0],    // Win8 Specific
        );
        $pushObject['notification'] = array(
            'alert'             => $message,
            // 'wns'               => array(    // If an override is needed
            //     'alert' => $message,         //
            //     'toast' => $message,         // this object must contain
            //     'tile'  => $message,         //
            //     'badge'  => $message,        //
            // ),                               // ONE of these attributes
        );
        $pushObject['device_types'] = array(  // "all" as value is available
            'wns',
        );
        // $pushObject['options'] = ; // Options
        // $pushObject['message'] = ; // Rich Push

        return $toJson ? json_encode($pushObject) : $pushObject;
    }

    /**
     * Validate a payload
     *
     * @param   array   $payload    The payload to validate
     * @return  bool    Payload validity
     */
    public function validate($payload)
    {
        // returns directly the response of RestManager
        // Because: true = OK but can't parse (Content-Type != JSON)
        // false = Server error (Code != 200)
        return $this->m_restManager->post('/api/push/validate/', $payload);
    }

    /**
     * Sends a payload
     *
     * @param   array   $payload    The payload to send
     * @return  bool    Payload sent
     */
    public function send($payload)
    {
        return $this->m_restManager->post('/api/push/', $payload);
    }
}
