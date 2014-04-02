<?php namespace MX\Push;

/**
 * Push Service Factory
 *
 * Instanciate and return the given Push Service
 */
abstract class PushServiceFactory
{
    /**
     * Push Service Factory
     *
     * Instanciate a PushService class for a given service and returns it
     *
     * @param string $service The PushService name
     * @param array $parameters=null The parameters to send to the PushService constructor
     * @return PushServiceProvider  Instance of PushServiceProvider
     */
    public static function make($pushServiceName, array $parameters = array())
    {
        if (class_exists("{$pushServiceName}Service")) {
            $name = "{$pushServiceName}Service";
        } elseif (class_exists(__NAMESPACE__.'\\'.$pushServiceName.'Service')) {
            $name = __NAMESPACE__.'\\'.$pushServiceName.'Service';
        } else {
            $name = &$pushServiceName;
        }

        $pushServiceRef = new \ReflectionClass($name);
        return $pushServiceRef->newInstanceArgs($parameters);
    }

    /**
     * List available Push Services
     *
     * @return array  List of PushServices name available (in current namespace)
     */
    public static function available()
    {
        $namespaceLength = strlen(__NAMESPACE__);

        return array_filter(get_declared_classes(), function ($name) {
            return (strncmp($name, __NAMESPACE__, $namespaceLength) === 0
                    && substr($name, -7) === 'Service');
        });
    }
}
