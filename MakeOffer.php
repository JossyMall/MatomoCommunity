<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Db;

class MakeOffer
{
    public function makeOffer($fromUserId, $toUserId, $siteId, $message)
    {
        $db = Db::get();
        $db->insert('matomo_offers', [
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'site_id' => $siteId,
            'message' => $message,
            'timestamp' => time()
        ]);
    }

    public function getOffers($userId)
    {
        $db = Db::get();
        return $db->fetchAll('SELECT * FROM matomo_offers WHERE to_user_id = ?', [$userId]);
    }
}

