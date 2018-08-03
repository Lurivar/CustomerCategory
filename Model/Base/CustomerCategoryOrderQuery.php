<?php

namespace CustomerCategory\Model\Base;

use \Exception;
use \PDO;
use CustomerCategory\Model\CustomerCategoryOrder as ChildCustomerCategoryOrder;
use CustomerCategory\Model\CustomerCategoryOrderQuery as ChildCustomerCategoryOrderQuery;
use CustomerCategory\Model\Map\CustomerCategoryOrderTableMap;
use CustomerCategory\Model\Thelia\Model\Order;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'customer_category_order' table.
 *
 *
 *
 * @method     ChildCustomerCategoryOrderQuery orderByOrderId($order = Criteria::ASC) Order by the order_id column
 * @method     ChildCustomerCategoryOrderQuery orderByCustomerCategoryId($order = Criteria::ASC) Order by the customer_category_id column
 *
 * @method     ChildCustomerCategoryOrderQuery groupByOrderId() Group by the order_id column
 * @method     ChildCustomerCategoryOrderQuery groupByCustomerCategoryId() Group by the customer_category_id column
 *
 * @method     ChildCustomerCategoryOrderQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCustomerCategoryOrderQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCustomerCategoryOrderQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCustomerCategoryOrderQuery leftJoinOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the Order relation
 * @method     ChildCustomerCategoryOrderQuery rightJoinOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Order relation
 * @method     ChildCustomerCategoryOrderQuery innerJoinOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the Order relation
 *
 * @method     ChildCustomerCategoryOrderQuery leftJoinCustomerCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomerCategory relation
 * @method     ChildCustomerCategoryOrderQuery rightJoinCustomerCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomerCategory relation
 * @method     ChildCustomerCategoryOrderQuery innerJoinCustomerCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomerCategory relation
 *
 * @method     ChildCustomerCategoryOrder findOne(ConnectionInterface $con = null) Return the first ChildCustomerCategoryOrder matching the query
 * @method     ChildCustomerCategoryOrder findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCustomerCategoryOrder matching the query, or a new ChildCustomerCategoryOrder object populated from the query conditions when no match is found
 *
 * @method     ChildCustomerCategoryOrder findOneByOrderId(int $order_id) Return the first ChildCustomerCategoryOrder filtered by the order_id column
 * @method     ChildCustomerCategoryOrder findOneByCustomerCategoryId(int $customer_category_id) Return the first ChildCustomerCategoryOrder filtered by the customer_category_id column
 *
 * @method     array findByOrderId(int $order_id) Return ChildCustomerCategoryOrder objects filtered by the order_id column
 * @method     array findByCustomerCategoryId(int $customer_category_id) Return ChildCustomerCategoryOrder objects filtered by the customer_category_id column
 *
 */
abstract class CustomerCategoryOrderQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \CustomerCategory\Model\Base\CustomerCategoryOrderQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\CustomerCategory\\Model\\CustomerCategoryOrder', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCustomerCategoryOrderQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCustomerCategoryOrderQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \CustomerCategory\Model\CustomerCategoryOrderQuery) {
            return $criteria;
        }
        $query = new \CustomerCategory\Model\CustomerCategoryOrderQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCustomerCategoryOrder|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CustomerCategoryOrderTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerCategoryOrderTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildCustomerCategoryOrder A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ORDER_ID, CUSTOMER_CATEGORY_ID FROM customer_category_order WHERE ORDER_ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildCustomerCategoryOrder();
            $obj->hydrate($row);
            CustomerCategoryOrderTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildCustomerCategoryOrder|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildCustomerCategoryOrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CustomerCategoryOrderTableMap::ORDER_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildCustomerCategoryOrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CustomerCategoryOrderTableMap::ORDER_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the order_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderId(1234); // WHERE order_id = 1234
     * $query->filterByOrderId(array(12, 34)); // WHERE order_id IN (12, 34)
     * $query->filterByOrderId(array('min' => 12)); // WHERE order_id > 12
     * </code>
     *
     * @see       filterByOrder()
     *
     * @param     mixed $orderId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryOrderQuery The current query, for fluid interface
     */
    public function filterByOrderId($orderId = null, $comparison = null)
    {
        if (is_array($orderId)) {
            $useMinMax = false;
            if (isset($orderId['min'])) {
                $this->addUsingAlias(CustomerCategoryOrderTableMap::ORDER_ID, $orderId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderId['max'])) {
                $this->addUsingAlias(CustomerCategoryOrderTableMap::ORDER_ID, $orderId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryOrderTableMap::ORDER_ID, $orderId, $comparison);
    }

    /**
     * Filter the query on the customer_category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerCategoryId(1234); // WHERE customer_category_id = 1234
     * $query->filterByCustomerCategoryId(array(12, 34)); // WHERE customer_category_id IN (12, 34)
     * $query->filterByCustomerCategoryId(array('min' => 12)); // WHERE customer_category_id > 12
     * </code>
     *
     * @see       filterByCustomerCategory()
     *
     * @param     mixed $customerCategoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryOrderQuery The current query, for fluid interface
     */
    public function filterByCustomerCategoryId($customerCategoryId = null, $comparison = null)
    {
        if (is_array($customerCategoryId)) {
            $useMinMax = false;
            if (isset($customerCategoryId['min'])) {
                $this->addUsingAlias(CustomerCategoryOrderTableMap::CUSTOMER_CATEGORY_ID, $customerCategoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerCategoryId['max'])) {
                $this->addUsingAlias(CustomerCategoryOrderTableMap::CUSTOMER_CATEGORY_ID, $customerCategoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryOrderTableMap::CUSTOMER_CATEGORY_ID, $customerCategoryId, $comparison);
    }

    /**
     * Filter the query by a related \CustomerCategory\Model\Thelia\Model\Order object
     *
     * @param \CustomerCategory\Model\Thelia\Model\Order|ObjectCollection $order The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryOrderQuery The current query, for fluid interface
     */
    public function filterByOrder($order, $comparison = null)
    {
        if ($order instanceof \CustomerCategory\Model\Thelia\Model\Order) {
            return $this
                ->addUsingAlias(CustomerCategoryOrderTableMap::ORDER_ID, $order->getId(), $comparison);
        } elseif ($order instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CustomerCategoryOrderTableMap::ORDER_ID, $order->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByOrder() only accepts arguments of type \CustomerCategory\Model\Thelia\Model\Order or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Order relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCustomerCategoryOrderQuery The current query, for fluid interface
     */
    public function joinOrder($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Order');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Order');
        }

        return $this;
    }

    /**
     * Use the Order relation Order object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CustomerCategory\Model\Thelia\Model\OrderQuery A secondary query class using the current class as primary query
     */
    public function useOrderQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Order', '\CustomerCategory\Model\Thelia\Model\OrderQuery');
    }

    /**
     * Filter the query by a related \CustomerCategory\Model\CustomerCategory object
     *
     * @param \CustomerCategory\Model\CustomerCategory|ObjectCollection $customerCategory The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryOrderQuery The current query, for fluid interface
     */
    public function filterByCustomerCategory($customerCategory, $comparison = null)
    {
        if ($customerCategory instanceof \CustomerCategory\Model\CustomerCategory) {
            return $this
                ->addUsingAlias(CustomerCategoryOrderTableMap::CUSTOMER_CATEGORY_ID, $customerCategory->getId(), $comparison);
        } elseif ($customerCategory instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CustomerCategoryOrderTableMap::CUSTOMER_CATEGORY_ID, $customerCategory->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCustomerCategory() only accepts arguments of type \CustomerCategory\Model\CustomerCategory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CustomerCategory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCustomerCategoryOrderQuery The current query, for fluid interface
     */
    public function joinCustomerCategory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CustomerCategory');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CustomerCategory');
        }

        return $this;
    }

    /**
     * Use the CustomerCategory relation CustomerCategory object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CustomerCategory\Model\CustomerCategoryQuery A secondary query class using the current class as primary query
     */
    public function useCustomerCategoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCustomerCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomerCategory', '\CustomerCategory\Model\CustomerCategoryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCustomerCategoryOrder $customerCategoryOrder Object to remove from the list of results
     *
     * @return ChildCustomerCategoryOrderQuery The current query, for fluid interface
     */
    public function prune($customerCategoryOrder = null)
    {
        if ($customerCategoryOrder) {
            $this->addUsingAlias(CustomerCategoryOrderTableMap::ORDER_ID, $customerCategoryOrder->getOrderId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the customer_category_order table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerCategoryOrderTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CustomerCategoryOrderTableMap::clearInstancePool();
            CustomerCategoryOrderTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildCustomerCategoryOrder or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildCustomerCategoryOrder object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerCategoryOrderTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CustomerCategoryOrderTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        CustomerCategoryOrderTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CustomerCategoryOrderTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // CustomerCategoryOrderQuery
