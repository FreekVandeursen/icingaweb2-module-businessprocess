<?php

namespace Icinga\Module\Businessprocess;

use Icinga\Module\Businessprocess\Html\Link;
use Icinga\Module\Monitoring\Backend;

abstract class MonitoredNode extends Node
{
    /**
     * Cache for {@link createBackend()}
     *
     * @var Backend
     */
    protected static $backend;

    abstract public function getUrl();

    public function getLink()
    {
        if ($this->isMissing()) {
            return Link::create($this->getAlias(), '#');
        } else {
            return Link::create($this->getAlias(), $this->getUrl());
        }
    }

    /**
     * Create and return this business process' monitoring backend
     *
     * @return Backend
     */
    protected function createBackend()
    {
        if (static::$backend === null) {
            static::$backend = Backend::createBackend($this->bp->hasBackendName() ? $this->bp->getBackendName() : null);
        }

        return static::$backend;
    }
}
