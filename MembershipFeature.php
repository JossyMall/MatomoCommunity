<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Db;
use Piwik\Piwik;

class MembershipFeature
{
    public function createGroup($groupName, $features)
    {
        $db = Db::get();
        $db->insert('matomo_membership_groups', [
            'group_name' => $groupName,
            'features' => json_encode($features)
        ]);
    }

    public function assignUserToGroup($userId, $groupId)
    {
        $db = Db::get();
        $db->update('matomo_user', ['group_id' => $groupId], 'login = ?', [$userId]);
    }

    public function setDefaultGroup($groupId)
    {
        $db = Db::get();
        $db->update('matomo_membership_groups', ['is_default' => 0]);
        $db->update('matomo_membership_groups', ['is_default' => 1], 'id = ?', [$groupId]);
    }

    public function getUserGroup($userId)
    {
        $db = Db::get();
        return $db->fetchOne('SELECT group_id FROM matomo_user WHERE login = ?', [$userId]);
    }

    public function getGroupFeatures($groupId)
    {
        $db = Db::get();
        $features = $db->fetchOne('SELECT features FROM matomo_membership_groups WHERE id = ?', [$groupId]);
        return json_decode($features, true);
    }

    public function getDefaultGroup()
    {
        $db = Db::get();
        return $db->fetchOne('SELECT id FROM matomo_membership_groups WHERE is_default = 1');
    }

    public function displayGroupFeatures($userId)
    {
        $groupId = $this->getUserGroup($userId);
        $features = $this->getGroupFeatures($groupId);

        echo "Features available: " . implode(', ', $features);
    }
}

