<?php

namespace CustomerCategory\Model\Map;

use CustomerCategory\Model\CustomerCategoryPrice;
use CustomerCategory\Model\CustomerCategoryPriceQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'customer_category_price' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class CustomerCategoryPriceTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'CustomerCategory.Model.Map.CustomerCategoryPriceTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'customer_category_price';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\CustomerCategory\\Model\\CustomerCategoryPrice';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'CustomerCategory.Model.CustomerCategoryPrice';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the CUSTOMER_CATEGORY_ID field
     */
    const CUSTOMER_CATEGORY_ID = 'customer_category_price.CUSTOMER_CATEGORY_ID';

    /**
     * the column name for the PROMO field
     */
    const PROMO = 'customer_category_price.PROMO';

    /**
     * the column name for the USE_EQUATION field
     */
    const USE_EQUATION = 'customer_category_price.USE_EQUATION';

    /**
     * the column name for the AMOUNT_ADDED_BEFORE field
     */
    const AMOUNT_ADDED_BEFORE = 'customer_category_price.AMOUNT_ADDED_BEFORE';

    /**
     * the column name for the AMOUNT_ADDED_AFTER field
     */
    const AMOUNT_ADDED_AFTER = 'customer_category_price.AMOUNT_ADDED_AFTER';

    /**
     * the column name for the MULTIPLICATION_COEFFICIENT field
     */
    const MULTIPLICATION_COEFFICIENT = 'customer_category_price.MULTIPLICATION_COEFFICIENT';

    /**
     * the column name for the IS_TAXED field
     */
    const IS_TAXED = 'customer_category_price.IS_TAXED';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('CustomerCategoryId', 'Promo', 'UseEquation', 'AmountAddedBefore', 'AmountAddedAfter', 'MultiplicationCoefficient', 'IsTaxed', ),
        self::TYPE_STUDLYPHPNAME => array('customerCategoryId', 'promo', 'useEquation', 'amountAddedBefore', 'amountAddedAfter', 'multiplicationCoefficient', 'isTaxed', ),
        self::TYPE_COLNAME       => array(CustomerCategoryPriceTableMap::CUSTOMER_CATEGORY_ID, CustomerCategoryPriceTableMap::PROMO, CustomerCategoryPriceTableMap::USE_EQUATION, CustomerCategoryPriceTableMap::AMOUNT_ADDED_BEFORE, CustomerCategoryPriceTableMap::AMOUNT_ADDED_AFTER, CustomerCategoryPriceTableMap::MULTIPLICATION_COEFFICIENT, CustomerCategoryPriceTableMap::IS_TAXED, ),
        self::TYPE_RAW_COLNAME   => array('CUSTOMER_CATEGORY_ID', 'PROMO', 'USE_EQUATION', 'AMOUNT_ADDED_BEFORE', 'AMOUNT_ADDED_AFTER', 'MULTIPLICATION_COEFFICIENT', 'IS_TAXED', ),
        self::TYPE_FIELDNAME     => array('customer_category_id', 'promo', 'use_equation', 'amount_added_before', 'amount_added_after', 'multiplication_coefficient', 'is_taxed', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('CustomerCategoryId' => 0, 'Promo' => 1, 'UseEquation' => 2, 'AmountAddedBefore' => 3, 'AmountAddedAfter' => 4, 'MultiplicationCoefficient' => 5, 'IsTaxed' => 6, ),
        self::TYPE_STUDLYPHPNAME => array('customerCategoryId' => 0, 'promo' => 1, 'useEquation' => 2, 'amountAddedBefore' => 3, 'amountAddedAfter' => 4, 'multiplicationCoefficient' => 5, 'isTaxed' => 6, ),
        self::TYPE_COLNAME       => array(CustomerCategoryPriceTableMap::CUSTOMER_CATEGORY_ID => 0, CustomerCategoryPriceTableMap::PROMO => 1, CustomerCategoryPriceTableMap::USE_EQUATION => 2, CustomerCategoryPriceTableMap::AMOUNT_ADDED_BEFORE => 3, CustomerCategoryPriceTableMap::AMOUNT_ADDED_AFTER => 4, CustomerCategoryPriceTableMap::MULTIPLICATION_COEFFICIENT => 5, CustomerCategoryPriceTableMap::IS_TAXED => 6, ),
        self::TYPE_RAW_COLNAME   => array('CUSTOMER_CATEGORY_ID' => 0, 'PROMO' => 1, 'USE_EQUATION' => 2, 'AMOUNT_ADDED_BEFORE' => 3, 'AMOUNT_ADDED_AFTER' => 4, 'MULTIPLICATION_COEFFICIENT' => 5, 'IS_TAXED' => 6, ),
        self::TYPE_FIELDNAME     => array('customer_category_id' => 0, 'promo' => 1, 'use_equation' => 2, 'amount_added_before' => 3, 'amount_added_after' => 4, 'multiplication_coefficient' => 5, 'is_taxed' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('customer_category_price');
        $this->setPhpName('CustomerCategoryPrice');
        $this->setClassName('\\CustomerCategory\\Model\\CustomerCategoryPrice');
        $this->setPackage('CustomerCategory.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('CUSTOMER_CATEGORY_ID', 'CustomerCategoryId', 'INTEGER' , 'customer_category', 'ID', true, null, null);
        $this->addPrimaryKey('PROMO', 'Promo', 'TINYINT', true, null, 0);
        $this->addColumn('USE_EQUATION', 'UseEquation', 'TINYINT', true, null, 0);
        $this->addColumn('AMOUNT_ADDED_BEFORE', 'AmountAddedBefore', 'DECIMAL', false, 16, 0);
        $this->addColumn('AMOUNT_ADDED_AFTER', 'AmountAddedAfter', 'DECIMAL', false, 16, 0);
        $this->addColumn('MULTIPLICATION_COEFFICIENT', 'MultiplicationCoefficient', 'DECIMAL', false, 16, 1);
        $this->addColumn('IS_TAXED', 'IsTaxed', 'TINYINT', true, null, 1);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CustomerCategory', '\\CustomerCategory\\Model\\CustomerCategory', RelationMap::MANY_TO_ONE, array('customer_category_id' => 'id', ), 'CASCADE', 'RESTRICT');
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \CustomerCategory\Model\CustomerCategoryPrice $obj A \CustomerCategory\Model\CustomerCategoryPrice object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize(array((string) $obj->getCustomerCategoryId(), (string) $obj->getPromo()));
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \CustomerCategory\Model\CustomerCategoryPrice object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \CustomerCategory\Model\CustomerCategoryPrice) {
                $key = serialize(array((string) $value->getCustomerCategoryId(), (string) $value->getPromo()));

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize(array((string) $value[0], (string) $value[1]));
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \CustomerCategory\Model\CustomerCategoryPrice object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('CustomerCategoryId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Promo', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize(array((string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('CustomerCategoryId', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Promo', TableMap::TYPE_PHPNAME, $indexType)]));
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return $pks;
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? CustomerCategoryPriceTableMap::CLASS_DEFAULT : CustomerCategoryPriceTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (CustomerCategoryPrice object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CustomerCategoryPriceTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CustomerCategoryPriceTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CustomerCategoryPriceTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CustomerCategoryPriceTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CustomerCategoryPriceTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = CustomerCategoryPriceTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CustomerCategoryPriceTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CustomerCategoryPriceTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(CustomerCategoryPriceTableMap::CUSTOMER_CATEGORY_ID);
            $criteria->addSelectColumn(CustomerCategoryPriceTableMap::PROMO);
            $criteria->addSelectColumn(CustomerCategoryPriceTableMap::USE_EQUATION);
            $criteria->addSelectColumn(CustomerCategoryPriceTableMap::AMOUNT_ADDED_BEFORE);
            $criteria->addSelectColumn(CustomerCategoryPriceTableMap::AMOUNT_ADDED_AFTER);
            $criteria->addSelectColumn(CustomerCategoryPriceTableMap::MULTIPLICATION_COEFFICIENT);
            $criteria->addSelectColumn(CustomerCategoryPriceTableMap::IS_TAXED);
        } else {
            $criteria->addSelectColumn($alias . '.CUSTOMER_CATEGORY_ID');
            $criteria->addSelectColumn($alias . '.PROMO');
            $criteria->addSelectColumn($alias . '.USE_EQUATION');
            $criteria->addSelectColumn($alias . '.AMOUNT_ADDED_BEFORE');
            $criteria->addSelectColumn($alias . '.AMOUNT_ADDED_AFTER');
            $criteria->addSelectColumn($alias . '.MULTIPLICATION_COEFFICIENT');
            $criteria->addSelectColumn($alias . '.IS_TAXED');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(CustomerCategoryPriceTableMap::DATABASE_NAME)->getTable(CustomerCategoryPriceTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(CustomerCategoryPriceTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(CustomerCategoryPriceTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new CustomerCategoryPriceTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a CustomerCategoryPrice or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or CustomerCategoryPrice object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerCategoryPriceTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \CustomerCategory\Model\CustomerCategoryPrice) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CustomerCategoryPriceTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(CustomerCategoryPriceTableMap::CUSTOMER_CATEGORY_ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(CustomerCategoryPriceTableMap::PROMO, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = CustomerCategoryPriceQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { CustomerCategoryPriceTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { CustomerCategoryPriceTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the customer_category_price table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CustomerCategoryPriceQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a CustomerCategoryPrice or Criteria object.
     *
     * @param mixed               $criteria Criteria or CustomerCategoryPrice object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerCategoryPriceTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from CustomerCategoryPrice object
        }


        // Set the correct dbName
        $query = CustomerCategoryPriceQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // CustomerCategoryPriceTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CustomerCategoryPriceTableMap::buildTableMap();
