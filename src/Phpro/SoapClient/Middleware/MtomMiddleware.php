<?php

namespace Phpro\SoapClient\Middleware;

use Zend\Mime\Message;
use Zend\Mime\Mime;

/**
 * Class MtomMiddleware
 *
 * @package Phpro\SoapClient\Middleware
 */
class MtomMiddleware extends Middleware
{

    public function testsomething()
    {
        $response = <<<RESPONSEEE
Accept: text/xml, multipart/related
Content-Type: multipart/related;start="<rootpart*8b39dc38-7f35-437f-920f-b99b6b2c9888@example.jaxws.sun.com>";
    type="application/xop+xml";boundary="uuid:8b39dc38-7f35-437f-920f-b99b6b2c9888";start-info="text/xml"
SOAPAction: "http://server.mtom.ws.codejava.net/FileTransfererImpl/uploadRequest"
User-Agent: JAX-WS RI 2.2.4-b01
Host: localhost:8787
Connection: keep-alive
 
--uuid:8b39dc38-7f35-437f-920f-b99b6b2c9888
Content-Id: <rootpart*8b39dc38-7f35-437f-920f-b99b6b2c9888@example.jaxws.sun.com>
Content-Type: application/xop+xml;charset=utf-8;type="text/xml"
Content-Transfer-Encoding: binary
 
<?xml version="1.0" ?>
<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
    <S:Body>
        <ns2:upload xmlns:ns2="http://server.mtom.ws.codejava.net/">
            <arg0>binary.png</arg0>
            <arg1>
                <xop:Include xmlns:xop="http://www.w3.org/2004/08/xop/include"
                    href="cid:187eff8e-fc5c-4aa5-8a89-1e09e153ade6@example.jaxws.sun.com">
                </xop:Include>
            </arg1>
        </ns2:upload>
    </S:Body>
</S:Envelope>
 
--uuid:8b39dc38-7f35-437f-920f-b99b6b2c9888
Content-Id: <187eff8e-fc5c-4aa5-8a89-1e09e153ade6@example.jaxws.sun.com>
Content-Type: application/octet-stream
Content-Transfer-Encoding: binary
 
c29tZXRoaW5n
RESPONSEEE;

        $message = Message::createFromMessage($response);

        var_dump($message);

    }
}
