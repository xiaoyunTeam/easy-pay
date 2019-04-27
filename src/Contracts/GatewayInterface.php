<?php

namespace XiaoYun\Pay\Contracts;

use Symfony\Component\HttpFoundation\Response;
use XiaoYun\Supports\Collection;

interface GatewayInterface
{
    /**
     * Pay an order.
     *
     * @author XiaoYun <me@XiaoYun.cn>
     *
     * @param string $endpoint
     * @param array  $payload
     *
     * @return Collection|Response
     */
    public function pay($endpoint, array $payload);
}
