<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Db;

class BusinessPage
{
    public function updateUserRole($userId, $role)
    {
        $db = Db::get();
        $db->update('matomo_user', ['role' => $role], 'login = ?', [$userId]);
    }

    public function getUserRole($userId)
    {
        $db = Db::get();
        return $db->fetchOne('SELECT role FROM matomo_user WHERE login = ?', [$userId]);
    }

    public function displayUserRole($userId)
    {
        $role = $this->getUserRole($userId);
        echo "Role: " . htmlspecialchars($role);
    }
}

