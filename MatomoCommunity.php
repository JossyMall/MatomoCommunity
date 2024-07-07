namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Plugins\Plugin;
use Piwik\Db;

class MatomoCommunity extends Plugin
{
    public function install()
    {
        $db = Db::get();

        // Create matomo_membership_groups table
        $db->query("
            CREATE TABLE IF NOT EXISTS `matomo_membership_groups` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `features` TEXT NOT NULL,
                PRIMARY KEY (`id`)
            )
        ");

        // Create matomo_user table
        // Assuming matomo_user already exists, but adding group_id column
        $db->query("
            ALTER TABLE `matomo_user`
            ADD COLUMN `group_id` INT(11) DEFAULT NULL
        ");

        // Insert default membership group
        $db->query("
            INSERT INTO `matomo_membership_groups` (`name`, `features`)
            VALUES ('Default', '[]')
        ");

        // Set default membership group in matomo_option
        $defaultGroupId = $db->lastInsertId();
        $db->query("
            INSERT INTO `matomo_option` (`option_name`, `option_value`)
            VALUES ('default_membership_group', ?)
        ", [$defaultGroupId]);
    }

    public function uninstall()
    {
        $db = Db::get();

        // Drop matomo_membership_groups table
        $db->query("DROP TABLE IF EXISTS `matomo_membership_groups`");

        // Remove group_id column from matomo_user table
        $db->query("ALTER TABLE `matomo_user` DROP COLUMN `group_id`");

        // Remove default membership group from matomo_option
        $db->query("DELETE FROM `matomo_option` WHERE `option_name` = 'default_membership_group'");
    }
}
