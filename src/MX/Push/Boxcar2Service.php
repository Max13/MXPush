<?php namespace MX\Push;

/**
 * Boxcar2 service (Hack: Self notification for now)
 *
 * @link    https://boxcar.uservoice.com/knowledgebase/articles/306788-send-boxcar-push-notification
 */
class Boxcar2Service extends PushServiceProvider
{
    /**
     * Constructor
     *
     * Initializes api url and keys
     */
    public function __construct()
    {
        parent::__construct('https://new.boxcar.io');
    }

    /**
     * Prepare a push message
     *
     * Prepares a push message and return the payload
     *
     * @param   array   $dest Associative array of 'platorm' => array('tokens')
     *                        to send the push to
     * @param   string  $title      Title of the push
     * @param   string  $message    Message shown on notification
     * @param   string  $body       Actual content of the message (250 Kb, HTML)
     * @param   bool    $toJson=false   Json encoded
     * @return  string  Prepared payload, JSON format
     */
    public function prepare(
        array $dest,
        $title,
        $message = null,
        $body = null,
        $sound = null
    ) {
        if (is_null($message)) {
            throw new PushServiceException('A message must be provided');
        }

        $pushObjects = array();

        foreach ($dest as $token) {
            $pushObjects[] = array(
                'user_credentials'  => utf8_encode($token),
                'notification'      => array(
                    'title'         => utf8_encode($title),
                    'message'       => utf8_encode($message),
                    'long_message'  => utf8_encode($body),
                    'sound'         => utf8_encode($sound),
                ),
            );
        }

        return $pushObjects;
    }

    /**
     * Sends a payload
     *
     * @param   array   $payload    The payload to send
     * @return  bool    Payload sent
     */
    public function send($payload)
    {
        return $this->m_restManager->post('/api/notifications', $payload);
    }
}
