<?php

namespace XiaoYun\Pay\Gateways\Wechat;

use Symfony\Component\HttpFoundation\RedirectResponse;
use XiaoYun\Pay\Events;
use XiaoYun\Pay\Exceptions\GatewayException;
use XiaoYun\Pay\Exceptions\InvalidArgumentException;
use XiaoYun\Pay\Exceptions\InvalidSignException;

class WapGateway extends Gateway
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
     * @return RedirectResponse
     */
    public function pay($endpoint, array $payload): RedirectResponse
    {
        $payload['trade_type'] = $this->getTradeType();

        Events::dispatch(Events::PAY_STARTED, new Events\PayStarted('Wechat', 'Wap', $endpoint, $payload));

        $mweb_url = $this->preOrder($payload)->get('mweb_url');

        $url = is_null(Support::getInstance()->return_url) ? $mweb_url : $mweb_url.
                        '&redirect_url='.urlencode(Support::getInstance()->return_url);

        return RedirectResponse::create($url);
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
        return 'MWEB';
    }
}
