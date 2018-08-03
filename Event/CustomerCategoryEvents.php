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

namespace CustomerCategory\Event;

/**
 * Class CustomerCategoryEvents
 * @package CustomerCategory\Event
 */
class CustomerCategoryEvents
{
    const CUSTOMER_CUSTOMER_CATEGORY_UPDATE = "action.front.customer.customer.category.update";
    const CUSTOMER_CATEGORY_CREATE = "action.admin.customer.category.create";
    const CUSTOMER_CATEGORY_UPDATE = "action.admin.customer.category.update";
    const CUSTOMER_CATEGORY_DELETE = "action.admin.customer.category.delete";
}
