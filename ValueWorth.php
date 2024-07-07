<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Db;

class ValueWorth
{
    public function calculateValue($siteId)
    {
        $db = Db::get();
        $uniqueVisitors = $db->fetchOne('SELECT COUNT(DISTINCT idvisitor) FROM matomo_log_visit WHERE idsite = ?', [$siteId]);
        $actions = $db->fetchOne('SELECT COUNT(*) FROM matomo_log_link_visit_action WHERE idsite = ?', [$siteId]);

        $value = $uniqueVisitors + ($actions * 0.001);
        return $value;
    }

    public function displayValue($siteId)
    {
        $value = $this->calculateValue($siteId);
        echo "Value: $" . number_format($value, 2);
    }
}

