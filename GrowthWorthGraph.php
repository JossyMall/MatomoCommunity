<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\View;
use Piwik\API\Request;

class GrowthWorthGraph
{
    public function renderGrowthGraph($siteId, $months = 4)
    {
        $endDate = date("Y-m-d");
        $startDate = date("Y-m-d", strtotime("-$months months"));

        $view = new View('@MatomoCommunity/growth_worth_graph.twig');
        $view->growthData = $this->getGrowthData($siteId, $startDate, $endDate);
        return $view->render();
    }

    private function getGrowthData($siteId, $startDate, $endDate)
    {
        $request = new Request("method=VisitsSummary.get&format=JSON&idSite=$siteId&period=day&date=$startDate,$endDate");
        $result = $request->process();
        return $result;
    }
}

