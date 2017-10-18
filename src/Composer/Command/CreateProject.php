<?php

namespace Application\Composer\Command;

use Composer\Script\Event;

/**
 * Class CreateProject
 * @package Application
 */
class CreateProject
{
    /**
     * Execute some scipt after create project command
     *
     * @param Event $event
     */
    public static function postCreateEvent(Event $event)
    {
        // do awesome stuff like
        //... initialize database schema
        //... initialize docker-compose for development
        //... run frontend task runners
        //... verify some path permissions and integrity
        //... run some code test suite
        //... do requests to guarantee some important api requests
        //... be creative
    }

    public static function migrate(Event $event)
    {
        if (false === $event->isDevMode()) {
            return null;
        }

        // @TODO create a cosole command `db migrate` and execute
    }
}