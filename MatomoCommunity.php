<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Plugin;
use Piwik\Db;
use Piwik\Option;

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

        // Alter matomo_user table to add group_id
        $db->query("ALTER TABLE `matomo_user` ADD COLUMN `group_id` INT(11) DEFAULT NULL");

        // Insert default membership group
        $db->query("INSERT INTO `matomo_membership_groups` (`name`, `features`) VALUES ('Default', '[]')");
        $defaultGroupId = $db->lastInsertId();
        Option::set('default_membership_group', $defaultGroupId);

        // Create matomo_messages table
        $db->query("
            CREATE TABLE IF NOT EXISTS `matomo_messages` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `sender_id` INT(11) NOT NULL,
                `receiver_id` INT(11) NOT NULL,
                `subject` VARCHAR(255),
                `message` TEXT NOT NULL,
                `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
        ");

        // Create matomo_value_worth table
        $db->query("
            CREATE TABLE IF NOT EXISTS `matomo_value_worth` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `site_id` INT(11) NOT NULL,
                `value` DECIMAL(10, 2) NOT NULL,
                `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
        ");

        // Create matomo_offers table
        $db->query("
            CREATE TABLE IF NOT EXISTS `matomo_offers` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `site_id` INT(11) NOT NULL,
                `sender_id` INT(11) NOT NULL,
                `message` TEXT NOT NULL,
                `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
        ");

        // Create matomo_watchlist table
        $db->query("
            CREATE TABLE IF NOT EXISTS `matomo_watchlist` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `site_id` INT(11) NOT NULL,
                `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
        ");

        // Create matomo_community_experiments table
        $db->query("
            CREATE TABLE IF NOT EXISTS `matomo_community_experiments` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `experiment_id` INT(11) NOT NULL,
                `user_id` INT(11) NOT NULL,
                `result` TEXT NOT NULL,
                `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
        ");
    }

    public function uninstall()
    {
        $db = Db::get();

        // Drop matomo_membership_groups table
        $db->query("DROP TABLE IF EXISTS `matomo_membership_groups`");

        // Remove group_id column from matomo_user table
        $db->query("ALTER TABLE `matomo_user` DROP COLUMN `group_id`");

        // Remove default membership group from matomo_option
        Option::delete('default_membership_group');

        // Drop matomo_messages table
        $db->query("DROP TABLE IF EXISTS `matomo_messages`");

        // Drop matomo_value_worth table
        $db->query("DROP TABLE IF EXISTS `matomo_value_worth`");

        // Drop matomo_offers table
        $db->query("DROP TABLE IF EXISTS `matomo_offers`");

        // Drop matomo_watchlist table
        $db->query("DROP TABLE IF EXISTS `matomo_watchlist`");

        // Drop matomo_community_experiments table
        $db->query("DROP TABLE IF EXISTS `matomo_community_experiments`");
    }

    public function registerEvents()
    {
        return array(
            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
        );
    }

    public function getJsFiles(&$jsFiles)
    {
        $jsFiles[] = 'plugins/MatomoCommunity/js/community.js';
    }

    public function getStylesheetFiles(&$stylesheetFiles)
    {
        $stylesheetFiles[] = 'plugins/MatomoCommunity/styles/community.css';
    }
}
