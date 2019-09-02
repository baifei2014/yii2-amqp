<?php

namespace Longhao\Amqp;

/**
 * @author jianglonghao <759395919@qq.com>
 */
class Publisher extends Request
{

    /**
     * @param string  $routing
     * @param Message $message
     * @throws Exception\Configuration
     */
    public function publish($routing, $message)
    {
        $this->getChannel()->basic_publish($message, $this->getProperty('exchange'), $routing);
    }
}
