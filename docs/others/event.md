# 事件系统

> v2.6.0-beta.1 及以上可用

在支付过程中，可能会想监听一些事件，好同时处理一些其它任务。

SDK 使用 [symfony/event-dispatcher](https://github.com/symfony/event-dispatcher) 组件进行事件的相关操作。

## 所有事件说明

- XiaoYun.pay.starting (XiaoYun\Pay\Events\PayStarting)
    
    - 事件类：XiaoYun\Pay\Events\PayStarting::class
    - 别名： XiaoYun\Pay\Events::PAY_STARTING
    - 说明：此事件将在最开始进行支付时进行抛出。此时 SDK 只进行了相关初始化操作，其它所有操作均未开始。
    - 额外数据：
        - $driver (支付机构)
        - $gateway (支付网关)
        - $params (传递的原始参数)
    
- XiaoYun.pay.started (XiaoYun\Pay\Events\PayStarted)

    - 事件类：XiaoYun\Pay\Events\PayStarted
    - 别名： XiaoYun\Pay\Events::PAY_STARTED
    - 说明：此事件将在所有参数处理完毕时抛出。
    - 额外数据：
        - $driver (支付机构)
        - $gateway (支付网关)
        - $endpoint (支付的 url endpoint)
        - $payload (数据)

- XiaoYun.pay.api.requesting (XiaoYun\Pay\Events\ApiRequesting)

    - 事件类：XiaoYun\Pay\Events\ApiRequesting
    - 别名： XiaoYun\Pay\Events::API_REQUESTING
    - 说明：此事件将在请求支付方的 API 前抛出。
    - 额外数据：
        - $driver (支付机构)
        - $gateway (支付网关)
        - $endpoint (支付的 url endpoint)
        - $payload (数据)
        
- XiaoYun.pay.api.requested (XiaoYun\Pay\Events\ApiRequested)

    - 事件类：XiaoYun\Pay\Events\ApiRequested
    - 别名： XiaoYun\Pay\Events::API_REQUESTED
    - 说明：此事件将在请求支付方的 API 完成之后抛出。
    - 额外数据：
        - $driver (支付机构)
        - $gateway (支付网关)
        - $endpoint (支付的 url endpoint)
        - $result (请求后的返回数据)
        
- XiaoYun.pay.sign.failed (XiaoYun\Pay\Events\SignFailed)
    
    - 事件类：XiaoYun\Pay\Events\SignFailed
    - 别名： XiaoYun\Pay\Events::SIGN_FAILED
    - 说明：此事件将在签名验证失败时抛出。
    - 额外数据：
        - $driver (支付机构)
        - $gateway (支付网关)
        - $data (验签数据)
    
- XiaoYun.pay.request.received (XiaoYun\Pay\Events\RequestReceived)
    
    - 事件类：XiaoYun\Pay\Events\RequestReceived
    - 别名： XiaoYun\Pay\Events::REQUEST_RECEIVED
    - 说明：此事件将在收到支付方的请求（通常在异步通知或同步通知）时抛出
    - 额外数据：
        - $driver (支付机构)
        - $gateway (支付网关)
        - $data (收到的数据)
    
- XiaoYun.pay.method.called (XiaoYun\Pay\Events\MethodCalled)
    
    - 事件类：XiaoYun\Pay\Events\MethodCalled
    - 别名： XiaoYun\Pay\Events::METHOD_CALLED
    - 说明：此事件将在调用除 PAYMETHOD 方法（例如，查询订单，退款，取消订单）时抛出
    - 额外数据：
        - $driver (支付机构)
        - $gateway (调用方法)
        - $endpoint (支付的 url endpoint)
        - $payload (数据)

## 使用

```php
<?php

use XiaoYun\Pay\Events;
use XiaoYun\Pay\Events\PayStarting;

// 1. 新建一个监听器
class PayStartingListener
{
    public function sendEmail(PayStarting $event)
    {
        // 可以直接通过 $event 获取事件的额外数据，例如：
        //      支付提供商： $event->driver   // alipay/wechat
        //      支付 gateway：$event->gateway  // app/web/pos/scan ...
        //      支付传递的参数：$event->params
        
        // coding to send email...
    }
}

// 2. 添加监听器
Events::addListener(Events::PAY_STARTING, [new PayStartingListener(), 'sendEmail']);

// 3. 喝杯咖啡

```

