<?php

namespace CustomerCategory\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class CustomerCategoryHook
 * @package CustomerCategory\Hook
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class CustomerCategoryHook extends BaseHook
{
    public function onAddCss(HookRenderEvent $event)
    {
        $event->add($this->addCSS('assets/css/style.css'));
    }
}