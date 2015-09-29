<?php

namespace Recca0120\AllPay\Message;

class VoidRequest extends CompleteAuthorizeRequest
{
    // public function getData()
    // {
    //     // $this->validate('transactionReference');
    //     $data['TimeStamp'] = time();
    //     $data['CheckMacValue'] = $this->generateSignature($data);
    //     $response = $this->httpClient->post($this->getEndPoint(), null, $data)->send();
    //     $szResult = (string) $response->getBody();
    //     $szResult = str_replace(' ', '%20', $szResult);
    //     $szResult = str_replace('+', '%2B', $szResult);

    //     return $data;
    // }

    public function sendData($data)
    {
        $this->response = new VoidResponse($this, $data);

        return $this->response;
    }
}
