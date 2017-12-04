<?php

namespace Icinga\Module\Businessprocess;

use Icinga\Application\Icinga;
use Icinga\Module\Monitoring\Controller;
use Icinga\Module\Monitoring\DataView\DataView;

class Monitoring extends Controller
{
    /**
     * Cache for {@link getInstance()}
     *
     * @var self
     */
    private static $instance;

    /**
     * Factory
     *
     * @return self
     */
    final private static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new self(Icinga::app()->getRequest(), Icinga::app()->getResponse());
        }

        return static::$instance;
    }

    /**
     * Restrict the given DataView as configured in the current user's roles (if any)
     *
     * @param   DataView    $dataView
     *
     * @return  DataView    The given DataView
     */
    public static function restrict(DataView $dataView)
    {
        return static::getInstance()->applyRestriction('monitoring/filter/objects', $dataView);
    }
}
