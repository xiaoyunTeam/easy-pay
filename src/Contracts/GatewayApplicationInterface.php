<?php

namespace XiaoYun\Pay\Contracts;

use Symfony\Component\HttpFoundation\Response;
use XiaoYun\Supports\Collection;

interface GatewayApplicationInterface
{
    /**
     * To pay.
     *
     * @author XiaoYun <shustudio@yeah.net>
     *
     * @param string $gateway
     * @param array  $params
     *
     * @return Collection|Response
     */
    public function pay($gateway, $params);

    /**
     * Query an order.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @param string|array $order
     * @param bool         $refund
     *
     * @return Collection
     */
    public function find($order, $refund);

    /**
     * Refund an order.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @param array $order
     *
     * @return Collection
     */
    public function refund($order);

    /**
     * Cancel an order.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @param string|array $order
     *
     * @return Collection
     */
    public function cancel($order);

    /**
     * Close an order.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @param string|array $order
     *
     * @return Collection
     */
    public function close($order);

    /**
     * Verify a request.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @param string|null $content
     * @param bool        $refund
     *
     * @return Collection
     */
    public function verify($content, $refund);

    /**
     * Echo success to server.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @return Response
     */
    public function success();
}
