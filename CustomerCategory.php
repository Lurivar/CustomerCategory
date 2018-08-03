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

namespace CustomerCategory;

use CustomerCategory\Model\CustomerCategoryQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\Finder\Finder;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;
use CustomerCategory\Model\CustomerCategory as CustomerCategoryModel;

/**
 * Class CustomerCategory
 * @package CustomerCategory
 * @contributor Etienne Perriere <eperriere@openstudio.fr>
 */
class CustomerCategory extends BaseModule
{
    /** @cont string */
    const MODULE_DOMAIN = 'customercategory';

    /** @cont string */
    const MESSAGE_DOMAIN = 'customercategory';

    /** @cont string */
    const CUSTOMER_CATEGORY_PARTICULAR = "particular";

    /** @cont string */
    const CUSTOMER_CATEGORY_PROFESSIONAL = "professional";

    /**
     * @param ConnectionInterface $con
     */
    public function postActivation(ConnectionInterface $con = null)
    {

        try {
            CustomerCategoryQuery::create()->findOne();
        } catch (\Exception $e) {
            $database = new Database($con);
            $database->insertSql(null, [__DIR__ . "/Config/thelia.sql"]);
        }

        //Generate the 2 defaults customer_category

        //Customer
        self::getCustomerCategoryByCode(self::CUSTOMER_CATEGORY_PARTICULAR, "Particulier", "fr_FR");
        self::getCustomerCategoryByCode(self::CUSTOMER_CATEGORY_PARTICULAR, "Private individual", "en_US");

        //Professional
        self::getCustomerCategoryByCode(self::CUSTOMER_CATEGORY_PROFESSIONAL, "Professionnel", "fr_FR");
        self::getCustomerCategoryByCode(self::CUSTOMER_CATEGORY_PROFESSIONAL, "Professional", "en_US");

        /** Check if the path given is a directory, creates it otherwise */
        if (!is_dir(__DIR__ . '/export-data/')) {
            @mkdir(__DIR__ . '/export-data/');
        }
    }

    public function update($currentVersion, $newVersion, ConnectionInterface $con = null)
    {
        $finder = Finder::create()
            ->name('*.sql')
            ->depth(0)
            ->sortByName()
            ->in(__DIR__ . DS . 'Config' . DS . 'update');

        $database = new Database($con);

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            if (version_compare($currentVersion, $file->getBasename('.sql'), '<')) {
                $database->insertSql(null, [$file->getPathname()]);
            }
        }
    }

    /**
     * @param $code
     * @param null $title
     * @param string $locale
     *
     * @return Model\CustomerCategory
     */
    public static function getCustomerCategoryByCode($code, $title = null, $locale = "fr_FR")
    {
        if ($title == null) {
            $title = $code;
        }

        // Set 'particular' as default category
        if ($code == self::CUSTOMER_CATEGORY_PARTICULAR) {
            $isDefault = 1;
        } else {
            $isDefault = 0;
        }

        /** @var CustomerCategoryModel $customerCategory */
        if (null == $customerCategory = CustomerCategoryQuery::create()
                ->useCustomerCategoryI18nQuery()
                ->filterByLocale($locale)
                ->endUse()
                ->filterByCode($code)
                ->findOne()
        ) {
            //Be sure that you don't create it twice
            /** @var CustomerCategoryModel $customerF */
            if (null != $customerF = CustomerCategoryQuery::create()->findOneByCode($code)) {
                $customerF
                    ->setLocale($locale)
                    ->setTitle($title)
                    ->save();
            } else {
                $customerCategory = new CustomerCategoryModel();
                $customerCategory
                    ->setCode($code)
                    ->setIsDefault($isDefault)
                    ->setLocale($locale)
                    ->setTitle($title)
                    ->save();
            }
        }

        return $customerCategory;
    }
}
