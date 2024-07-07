<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Plugin;
use Piwik\Tracker\Visit;

class DeviceOsTrigger extends Plugin
{
    public function registerEvents()
    {
        return [
            'Tracker.newVisitorInformation' => 'addDeviceOsTrigger'
        ];
    }

    public function addDeviceOsTrigger(&$visitorInfo)
    {
        $visitorInfo['device_os'] = $this->getDeviceOs();
    }

    private function getDeviceOs()
    {
        $os = Visit::getDeviceType();
        return $os;
    }
}

