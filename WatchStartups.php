
<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Db;

class WatchStartups
{
    public function addToWatchlist($userId, $siteId)
    {
        $db = Db::get();
        $db->insert('matomo_watchlist', [
            'user_id' => $userId,
            'site_id' => $siteId
        ]);
    }

    public function getWatchlist($userId)
    {
        $db = Db::get();
        return $db->fetchAll('SELECT * FROM matomo_watchlist WHERE user_id = ?', [$userId]);
    }

    public function displayWatchlist($userId)
    {
        $watchlist = $this->getWatchlist($userId);
        foreach ($watchlist as $item) {
            echo "Watched Site ID: " . htmlspecialchars($item['site_id']) . "<br>";
        }
    }
}

