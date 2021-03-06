<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) Sandro Groganz <sandro@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CampaignChainCoreBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
