<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Db;

class CommunityMessaging
{
    public function sendMessage($fromUserId, $toUserId, $message)
    {
        $db = Db::get();
        $db->insert('matomo_messages', [
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'message' => $message,
            'timestamp' => time()
        ]);
    }

    public function getMessages($userId)
    {
        $db = Db::get();
        return $db->fetchAll('SELECT * FROM matomo_messages WHERE to_user_id = ?', [$userId]);
    }
}

