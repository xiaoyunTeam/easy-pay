<?php

namespace XiaoYun\Pay\Gateways\Wechat;

use XiaoYun\Pay\Events;
use XiaoYun\Pay\Exceptions\GatewayException;
use XiaoYun\Pay\Exceptions\InvalidArgumentException;
use XiaoYun\Pay\Exceptions\InvalidSignException;
use XiaoYun\Supports\Collection;

class PosGateway extends Gateway
{
    /**
     * Pay an order.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @param string $endpoint
     * @param array  $payload
     *
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     *
     * @return Collection
     */
    public function pay($endpoint, array $payload): Collection
    {
        unset($payload['trade_type'], $payload['notify_url']);

        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(Events::PAY_STARTED, new Events\PayStarted('Wechat', 'Pos', $endpoint, $payload));

        return Support::requestApi('pay/micropay', $payload);
    }

    /**
     * Get trade type config.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @return string
     */
    protected function getTradeType(): string
    {
        return 'MICROPAY';
    }
}
