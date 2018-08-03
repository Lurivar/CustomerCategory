<?php
/*************************************************************************************/
/*      This file is part of the module CustomerCategory                               */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace CustomerCategory\Hook;

use CustomerCategory\CustomerCategory;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class CustomerCategoryUpdateFormHook extends BaseHook
{
    public function onAccountUpdateFormBottom(HookRenderEvent $event)
    {
        $event->add($this->render(
            'account-update.html',
            array(
                'form' => $event->getArgument('form'),
                'messageDomain' => CustomerCategory::MESSAGE_DOMAIN,
            )
        ));
    }

    public function onAccountUpdateAfterJSInclude(HookRenderEvent $event)
    {
        $event->add($this->addJS('assets/js/update.js'));
    }
}
