<?php

namespace XiaoYun\Pay;

use Exception;
use XiaoYun\Pay\Contracts\GatewayApplicationInterface;
use XiaoYun\Pay\Exceptions\InvalidGatewayException;
use XiaoYun\Pay\Gateways\Alipay;
use XiaoYun\Pay\Gateways\Wechat;
use XiaoYun\Pay\Listeners\KernelLogSubscriber;
use XiaoYun\Supports\Config;
use XiaoYun\Supports\Log;
use XiaoYun\Supports\Str;

/**
 * @method static Alipay alipay(array $config) 支付宝
 * @method static Wechat wechat(array $config) 微信
 */
class Pay
{
    /**
     * Config.
     *
     * @var Config
     */
    protected $config;

    /**
     * Bootstrap.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @param array $config
     *
     * @throws Exception
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);

        $this->registerLogService();
        $this->registerEventService();
    }

    /**
     * Magic static call.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @param string $method
     * @param array  $params
     *
     * @throws InvalidGatewayException
     * @throws Exception
     *
     * @return GatewayApplicationInterface
     */
    public static function __callStatic($method, $params): GatewayApplicationInterface
    {
        $app = new self(...$params);

        return $app->create($method);
    }

    /**
     * Create a instance.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @param string $method
     *
     * @throws InvalidGatewayException
     *
     * @return GatewayApplicationInterface
     */
    protected function create($method): GatewayApplicationInterface
    {
        $gateway = __NAMESPACE__.'\\Gateways\\'.Str::studly($method);

        if (class_exists($gateway)) {
            return self::make($gateway);
        }

        throw new InvalidGatewayException("Gateway [{$method}] Not Exists");
    }

    /**
     * Make a gateway.
     *
     * @author XiaoYun <shustudio@yeah.net>
     *
     * @param string $gateway
     *
     * @throws InvalidGatewayException
     *
     * @return GatewayApplicationInterface
     */
    protected function make($gateway): GatewayApplicationInterface
    {
        $app = new $gateway($this->config);

        if ($app instanceof GatewayApplicationInterface) {
            return $app;
        }

        throw new InvalidGatewayException("Gateway [{$gateway}] Must Be An Instance Of GatewayApplicationInterface");
    }

    /**
     * Register log service.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @throws Exception
     */
    protected function registerLogService()
    {
        $logger = Log::createLogger(
            $this->config->get('log.file'),
            'yansongda.pay',
            $this->config->get('log.level', 'warning'),
            $this->config->get('log.type', 'daily'),
            $this->config->get('log.max_file', 30)
        );

        Log::setLogger($logger);
    }

    /**
     * Register event service.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @return void
     */
    protected function registerEventService()
    {
        Events::setDispatcher(Events::createDispatcher());

        Events::addSubscriber(new KernelLogSubscriber());
    }
}
