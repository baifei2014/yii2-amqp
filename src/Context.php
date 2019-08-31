<?php

namespace Long\Amqp;

use Yii;

/**
 * @author Björn Schmitt <code@bjoern.io>
 */
abstract class Context
{

    const REPOSITORY_KEY = 'amqp';

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * Context constructor.
     *
     * @param Repository $config
     */
    public function __construct()
    {
        $this->extractProperties();
    }

    /**
     * @param Repository $config
     */
    protected function extractProperties()
    {
        if (isset(Yii::$app->params[self::REPOSITORY_KEY])) {
            $data             = Yii::$app->params[self::REPOSITORY_KEY];
            $this->properties = $data['properties'][$data['use']];
        }
    }

    /**
     * @param array $properties
     * @return $this
     */
    public function mergeProperties(array $properties)
    {
        $this->properties = array_merge($this->properties, $properties);
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getProperty($key)
    {
        return array_key_exists($key, $this->properties) ? $this->properties[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConnectOption($key, $default = null)
    {
        $options = $this->getProperty('connect_options');

        if (!is_array($options)) {
            return $default;
        }

        return array_key_exists($key, $options) ? $options[$key] : $default;
    }

    /**
     * @return mixed
     */
    abstract public function setup();
}
