<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotEchoLogger;

use Throwable;

use KnotLib\Kernel\EventStream\Channels;
use KnotLib\Kernel\EventStream\Events;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\ComponentModule;
use KnotLib\Kernel\Module\Components;
use KnotLib\Kernel\Logger\EchoLogger;

final class KnotEchoLoggerModule extends ComponentModule
{

    /**
     * Declare dependent on components
     *
     * @return array
     */
    public static function requiredComponents() : array
    {
        return [
            Components::EVENTSTREAM,
            Components::PIPELINE,
        ];
    }

    /**
     * Declare component type of this module
     *
     * @return string
     */
    public static function declareComponentType() : string
    {
        return Components::ROUTER;
    }

    /**
     * Install module
     *
     * @param ApplicationInterface $app
     *
     * @throws
     */
    public function install(ApplicationInterface $app)
    {
        try{
            $logger = new EchoLogger();

            $app->logger($logger);

            // fire event
            $app->eventstream()->channel(Channels::SYSTEM)->push(Events::LOGGER_ATTACHED, $logger);
        }
        catch(Throwable $e)
        {
            throw new ModuleInstallationException(self::class, $e->getMessage(), 0, $e);
        }
    }

}