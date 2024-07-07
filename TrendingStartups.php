<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Db;

class TrendingStartups
{
    public function getTrendingStartups()
    {
        $db = Db::get();
        return $db->fetchAll('SELECT site_id, name, growth_rate FROM matomo_trending_startups ORDER BY growth_rate DESC LIMIT 10');
    }

    public function displayTrendingStartups()
    {
        $startups = $this->getTrendingStartups();
        foreach ($startups as $startup) {
            echo "Startup: " . htmlspecialchars($startup['name']) . " - Growth Rate: " . htmlspecialchars($startup['growth_rate']) . "%<br>";
        }
    }
}

