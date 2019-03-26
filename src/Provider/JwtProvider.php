<?php

namespace App\Provider;


use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

/**
 * Class JwtProvider
 * @package App\Provider
 */
class JwtProvider
{

    private $secret;

    /**
     * JwtProvider constructor.
     * @param String $secret
     */
    public function __construct(String $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function __invoke(): string
    {
        return (new Builder())
            ->set('mercure', ['publish' => ['*']])
            ->sign(new Sha256(), $this->secret)
            ->getToken();
    }
}