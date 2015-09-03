<?php

class Recaptcha2
{
    private $ci;
    private $secretKey;
    private $url;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->config->load('recaptcha2');

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

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $this->url . '?' . http_build_query($parameters),
            )
        );
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}

/**
 * End of recaptcha2.php library
 */
