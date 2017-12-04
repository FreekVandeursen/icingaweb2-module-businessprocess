<?php

namespace Icinga\Module\Businessprocess;

use Icinga\Application\Icinga;
use Icinga\Module\Businessprocess\Web\Url;

class ServiceNode extends MonitoredNode
{
    protected $hostname;

    protected $service;

    protected $className = 'service';

    public function __construct(BpConfig $bp, $object)
    {
        $this->name = $object->hostname . ';' . $object->service;
        $this->hostname = $object->hostname;
        $this->service  = $object->service;
        $this->bp       = $bp;
        if (isset($object->state)) {
            $this->setState($object->state);
        } else {
            $this->setState(0)->setMissing();
        }
    }

    public function getHostname()
    {
        return $this->hostname;
    }

    public function getServiceDescription()
    {
        return $this->service;
    }

    public function getAlias()
    {
        return $this->hostname . ': ' . $this->service;
    }

    public function getUrl()
    {
        $params = array(
            'host'    => $this->getHostname(),
            'service' => $this->getServiceDescription()
        );

        if ($this->bp->hasBackendName()) {
            $params['backend'] = $this->bp->getBackendName();
        }

        if (Icinga::app()->isCli()) {
            $accessible = true;
        } else {
            $query = $this->createBackend()->select()
                ->from('servicestatus', array('host_name', 'service_description'))
                ->where('host_name', $this->hostname)
                ->where('service_description', $this->service);

            $accessible = Monitoring::restrict($query)->fetchRow() !== false;
        }

        return Url::fromPath(
            $accessible ? 'monitoring/service/show' : 'businessprocess/monitored-object/access-denied',
            $params
        );
    }
}
