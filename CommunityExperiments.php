<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Db;

class CommunityExperiments
{
    public function shareExperiment($experimentId, $shareWithCommunity)
    {
        $db = Db::get();
        $db->update('matomo_ab_experiments', ['share_with_community' => $shareWithCommunity], 'id = ?', [$experimentId]);
    }

    public function getCommunityExperiments()
    {
        $db = Db::get();
        return $db->fetchAll('SELECT * FROM matomo_ab_experiments WHERE share_with_community = 1');
    }

    public function displayCommunityExperiments()
    {
        $experiments = $this->getCommunityExperiments();
        foreach ($experiments as $experiment) {
            echo "Experiment: " . htmlspecialchars($experiment['name']) . "<br>";
        }
    }
}

