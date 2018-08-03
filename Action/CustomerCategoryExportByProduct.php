<?php

namespace CustomerCategory\Action;


use CustomerCategory\Model\CustomerCustomerCategory;
use CustomerCategory\Model\CustomerCustomerCategoryQuery;
use CustomerCategory\Model\CustomerCategoryQuery;
use CustomerCategory\Model\Map\CustomerCustomerCategoryTableMap;
use CustomerCategory\Model\Map\CustomerCategoryTableMap;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Model\CustomerQuery;
use Thelia\Model\Map\CustomerTableMap;
use Thelia\Model\ModuleHook;
use Thelia\Model\Order;
use Thelia\Model\OrderProduct;
use Thelia\Model\OrderQuery;
use Thelia\Model\ProductQuery;

class CustomerCategoryExportByProduct
{
    /**
     * @param $customerId
     * @param $productReference
     * @return int
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function checkCustomerCollection($customerId, $productReference)
    {
        $search = OrderQuery::create();
        $search->filterByCustomerId($customerId);

        /** @var Order $order */
        foreach ($search as $order) {

            if (in_array((int) $order->getStatusId(), [2, 3, 4])) {
                /** @var OrderProduct $product */
                foreach ($order->getOrderProducts()->getData() as $product) {
                    if ($product->getProductRef() == $productReference) {
                        return 0;
                    }
                }
            }
        }
        return $customerId;
    }

    /**
     * @param $categoryId
     * @param $productReference
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function exportCategoryAction($categoryId, $productReference)
    {
        $customers = CustomerCustomerCategoryQuery::create()->where("customer_category_id REGEXP ?", '^' . $categoryId . ',|,' . $categoryId . ',|,' . $categoryId . '$|^' . $categoryId . '$', PDO::PARAM_STR);

        $customersToExport[] = array('First Name' => 'First Name', 'Last Name' => 'Last Name', 'Email' => 'Email', 'Category' => 'Category', 'Product' => 'Product reference');


        /** @var CustomerCustomerCategory $customer */
        foreach ($customers as $customer) {
            if (($checkCustomer = self::checkCustomerCollection($customer->getCustomerId(), $productReference)) !== 0) {
                $thiscustomer = CustomerQuery::create()->filterById($checkCustomer)->findOne();
                $customerMail = $thiscustomer->getEmail();
                $customerFirstName = $thiscustomer->getFirstname();
                $customerLastName = $thiscustomer->getLastname();
                $categoryName = CustomerCategoryQuery::create()->filterById($categoryId)->findOne()->getCode();
                $customersToExport[] = array('First Name' => $customerFirstName, 'Last Name' => $customerLastName, 'Email' => $customerMail, 'Category' => $categoryName, 'Product' => $productReference);
            }
        }


        $fp = fopen(__DIR__ . '/../export-data/' . 'exportCategory.csv', 'w');

        foreach ($customersToExport as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);

        $file = __DIR__ . '/../export-data/' . 'exportCategory.csv';

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }
}