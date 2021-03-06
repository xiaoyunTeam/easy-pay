<?php

namespace XiaoYun\Pay\Gateways\Alipay;

use XiaoYun\Pay\Contracts\GatewayInterface;
use XiaoYun\Pay\Events;
use XiaoYun\Pay\Exceptions\GatewayException;
use XiaoYun\Pay\Exceptions\InvalidConfigException;
use XiaoYun\Pay\Exceptions\InvalidSignException;
use XiaoYun\Supports\Collection;

class ScanGateway implements GatewayInterface
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
     * @throws InvalidConfigException
     * @throws InvalidSignException
     *
     * @return Collection
     */
    public function pay($endpoint, array $payload): Collection
    {
        $payload['method'] = 'alipay.trade.precreate';
        $payload['biz_content'] = json_encode(array_merge(
            json_decode($payload['biz_content'], true),
            ['product_code' => '']
        ));
        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(Events::PAY_STARTED, new Events\PayStarted('Alipay', 'Scan', $endpoint, $payload));

        return Support::requestApi($payload);
    }
}
