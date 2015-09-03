<?php

class Recaptcha2
{
    private $ci;
    private $siteKey;
    private $secretKey;
    private $url;

    public function __construct()
    {
        $this->ci =& get_instance();

        $this->config->load('recaptcha2');

        $this->siteKey = $this->ci->config->item('recaptcha2-site-key');
        $this->secretKey = $this->ci->config->item('recaptcha2-secret-key');
        $this->url = $this->ci->config->item('recaptcha2-verify-url');
    }

    /**
     * Verify whether the submitted recaptcha response was valid
     *
     * @param string $recaptchaResponse
     * @return bool
     */
    public function verify($recaptchaResponse)
    {
        $responseArray = json_decode($this->sendRequest($recaptchaResponse), true);

        return $responseArray['success'];
    }

    private function sendRequest($recaptchaResponse)
    {
        $parameters = array(
            'secret' => $this->secretKey,
            'response' => $recaptchaResponse,
            'remoteIp' => $this->ci->input->ip_address(),
        );

        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->url . '?' . http_build_query($parameters),
        ));
        // Send the request & save response to $resp
        $response = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        return $response;
    }
}

/**
 * End of recaptcha2.php library
 */
