<?php

namespace XiaoYun\Pay\Gateways\Wechat;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use XiaoYun\Pay\Events;
use XiaoYun\Pay\Exceptions\GatewayException;
use XiaoYun\Pay\Exceptions\InvalidArgumentException;
use XiaoYun\Pay\Exceptions\InvalidSignException;
use XiaoYun\Pay\Gateways\Wechat;
use XiaoYun\Supports\Str;

class AppGateway extends Gateway
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
     * @throws Exception
     *
     * @return Response
     */
    public function pay($endpoint, array $payload): Response
    {
        $payload['appid'] = Support::getInstance()->appid;
        $payload['trade_type'] = $this->getTradeType();

        if ($this->mode === Wechat::MODE_SERVICE) {
            $payload['sub_appid'] = Support::getInstance()->sub_appid;
        }

        $pay_request = [
            'appid'     => $this->mode === Wechat::MODE_SERVICE ? $payload['sub_appid'] : $payload['appid'],
            'partnerid' => $this->mode === Wechat::MODE_SERVICE ? $payload['sub_mch_id'] : $payload['mch_id'],
            'prepayid'  => $this->preOrder($payload)->get('prepay_id'),
            'timestamp' => strval(time()),
            'noncestr'  => Str::random(),
            'package'   => 'Sign=WXPay',
        ];
        $pay_request['sign'] = Support::generateSign($pay_request);

        Events::dispatch(Events::PAY_STARTED, new Events\PayStarted('Wechat', 'App', $endpoint, $pay_request));

        return JsonResponse::create($pay_request);
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
        return 'APP';
    }
}
