namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Db;

class MembershipFeature
{
    public function createMembershipGroup($name, $features)
    {
        $db = Db::get();
        $db->insert('matomo_membership_groups', [
            'name' => $name,
            'features' => json_encode($features)
        ]);
    }

    public function addUserToGroup($userId, $groupId)
    {
        $db = Db::get();
        $db->update('matomo_user', ['group_id' => $groupId], 'user_id = ?', [$userId]);
    }

    public function setDefaultGroup($groupId)
    {
        $db = Db::get();
        $db->update('matomo_option', ['option_value' => $groupId], 'option_name = ?', ['default_membership_group']);
    }

    public function getMembershipGroups()
    {
        $db = Db::get();
        return $db->fetchAll('SELECT * FROM matomo_membership_groups');
    }

    public function getFeaturesForGroup($groupId)
    {
        $db = Db::get();
        $features = $db->fetchOne('SELECT features FROM matomo_membership_groups WHERE id = ?', [$groupId]);
        return json_decode($features, true);
    }

    public function isFeatureAvailable($userId, $feature)
    {
        $db = Db::get();
        $groupId = $db->fetchOne('SELECT group_id FROM matomo_user WHERE user_id = ?', [$userId]);
        $features = $this->getFeaturesForGroup($groupId);
        return in_array($feature, $features);
    }

    public function displayMembershipPlans()
    {
        $groups = $this->getMembershipGroups();
        foreach ($groups as $group) {
            echo "Group: " . htmlspecialchars($group['name']) . "<br>";
            echo "Features: " . htmlspecialchars(implode(', ', json_decode($group['features'], true))) . "<br><br>";
        }
    }
}
