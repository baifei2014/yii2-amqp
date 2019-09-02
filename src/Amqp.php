<?php

namespace Longhao\Amqp;

use Yii;
use Closure;

class Amqp
{
	/**
     * @param string $routing
     * @param mixed  $message
     * @param array  $properties
     */
    public function publish($routing, $message, array $properties = [])
    {
        $properties['routing'] = $routing;

        /* @var Publisher $publisher */
        $publisher = Yii::$container->get('Longhao\Amqp\Publisher');
        $publisher
            ->mergeProperties($properties)
            ->setup();

        if (is_string($message)) {
            $message = new Message($message, ['content_type' => 'text/plain', 'delivery_mode' => 2]);
        }

        $publisher->publish($routing, $message);
        Request::shutdown($publisher->getChannel(), $publisher->getConnection());
    }

    /**
     * @param string  $queue
     * @param Closure $callback
     * @param array   $properties
     * @throws Exception\Configuration
     */
    public function consume($queue, Closure $callback, $properties = [])
    {
        $properties['queue'] = $queue;

        /* @var Consumer $consumer */
        $consumer = Yii::$container->get('Longhao\Amqp\Consumer');
        $consumer
            ->mergeProperties($properties)
            ->setup();

        $consumer->consume($queue, $callback);
        Request::shutdown($consumer->getChannel(), $consumer->getConnection());
    }

    /**
     * @param string $body
     * @param array  $properties
     * @return \Longhao\Amqp\Message
     */
    public function message($body, $properties = [])
    {
        return new Message($body, $properties);
    }
}