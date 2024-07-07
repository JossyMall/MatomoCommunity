<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\View;

class CommunityController extends \Piwik\Plugin\Controller
{
    public function communityOverview()
    {
        return $this->renderTemplate('community_overview');
    }

    public function userProfile()
    {
        return $this->renderTemplate('user_profile');
    }

    public function messagingInbox()
    {
        return $this->renderTemplate('messaging_inbox');
    }

    public function valueWorth()
    {
        return $this->renderTemplate('value_worth');
    }

    public function growthWorthGraph()
    {
        return $this->renderTemplate('growth_worth_graph');
    }

    public function membershipPlans()
    {
        return $this->renderTemplate('membership_plans');
    }

    public function trendingStartups()
    {
        return $this->renderTemplate('trending_startups');
    }

    public function makeOffer()
    {
        return $this->renderTemplate('make_offer');
    }

    public function watchlist()
    {
        return $this->renderTemplate('watchlist');
    }

    public function communityExperiments()
    {
        return $this->renderTemplate('community_experiments');
    }
}
