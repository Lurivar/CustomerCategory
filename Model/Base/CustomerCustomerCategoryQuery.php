<?php

namespace CustomerCategory\Model\Base;

use \Exception;
use \PDO;
use CustomerCategory\Model\CustomerCustomerCategory as ChildCustomerCustomerCategory;
use CustomerCategory\Model\CustomerCustomerCategoryQuery as ChildCustomerCustomerCategoryQuery;
use CustomerCategory\Model\Map\CustomerCustomerCategoryTableMap;
use CustomerCategory\Model\Thelia\Model\Customer;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'customer_customer_category' table.
 *
 *
 *
 * @method     ChildCustomerCustomerCategoryQuery orderByCustomerId($order = Criteria::ASC) Order by the customer_id column
 * @method     ChildCustomerCustomerCategoryQuery orderByCustomerCategoryId($order = Criteria::ASC) Order by the customer_category_id column
 * @method     ChildCustomerCustomerCategoryQuery orderBySiret($order = Criteria::ASC) Order by the siret column
 * @method     ChildCustomerCustomerCategoryQuery orderByVat($order = Criteria::ASC) Order by the vat column
 *
 * @method     ChildCustomerCustomerCategoryQuery groupByCustomerId() Group by the customer_id column
 * @method     ChildCustomerCustomerCategoryQuery groupByCustomerCategoryId() Group by the customer_category_id column
 * @method     ChildCustomerCustomerCategoryQuery groupBySiret() Group by the siret column
 * @method     ChildCustomerCustomerCategoryQuery groupByVat() Group by the vat column
 *
 * @method     ChildCustomerCustomerCategoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCustomerCustomerCategoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCustomerCustomerCategoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCustomerCustomerCategoryQuery leftJoinCustomer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Customer relation
 * @method     ChildCustomerCustomerCategoryQuery rightJoinCustomer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Customer relation
 * @method     ChildCustomerCustomerCategoryQuery innerJoinCustomer($relationAlias = null) Adds a INNER JOIN clause to the query using the Customer relation
 *
 * @method     ChildCustomerCustomerCategory findOne(ConnectionInterface $con = null) Return the first ChildCustomerCustomerCategory matching the query
 * @method     ChildCustomerCustomerCategory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCustomerCustomerCategory matching the query, or a new ChildCustomerCustomerCategory object populated from the query conditions when no match is found
 *
 * @method     ChildCustomerCustomerCategory findOneByCustomerId(int $customer_id) Return the first ChildCustomerCustomerCategory filtered by the customer_id column
 * @method     ChildCustomerCustomerCategory findOneByCustomerCategoryId(string $customer_category_id) Return the first ChildCustomerCustomerCategory filtered by the customer_category_id column
 * @method     ChildCustomerCustomerCategory findOneBySiret(string $siret) Return the first ChildCustomerCustomerCategory filtered by the siret column
 * @method     ChildCustomerCustomerCategory findOneByVat(string $vat) Return the first ChildCustomerCustomerCategory filtered by the vat column
 *
 * @method     array findByCustomerId(int $customer_id) Return ChildCustomerCustomerCategory objects filtered by the customer_id column
 * @method     array findByCustomerCategoryId(string $customer_category_id) Return ChildCustomerCustomerCategory objects filtered by the customer_category_id column
 * @method     array findBySiret(string $siret) Return ChildCustomerCustomerCategory objects filtered by the siret column
 * @method     array findByVat(string $vat) Return ChildCustomerCustomerCategory objects filtered by the vat column
 *
 */
abstract class CustomerCustomerCategoryQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \CustomerCategory\Model\Base\CustomerCustomerCategoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\CustomerCategory\\Model\\CustomerCustomerCategory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCustomerCustomerCategoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCustomerCustomerCategoryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \CustomerCategory\Model\CustomerCustomerCategoryQuery) {
            return $criteria;
        }
        $query = new \CustomerCategory\Model\CustomerCustomerCategoryQuery();
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
     * @return ChildCustomerCustomerCategory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CustomerCustomerCategoryTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerCustomerCategoryTableMap::DATABASE_NAME);
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
     * @return   ChildCustomerCustomerCategory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT CUSTOMER_ID, CUSTOMER_CATEGORY_ID, SIRET, VAT FROM customer_customer_category WHERE CUSTOMER_ID = :p0';
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
            $obj = new ChildCustomerCustomerCategory();
            $obj->hydrate($row);
            CustomerCustomerCategoryTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildCustomerCustomerCategory|array|mixed the result, formatted by the current formatter
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
     * @return ChildCustomerCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CustomerCustomerCategoryTableMap::CUSTOMER_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildCustomerCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CustomerCustomerCategoryTableMap::CUSTOMER_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the customer_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerId(1234); // WHERE customer_id = 1234
     * $query->filterByCustomerId(array(12, 34)); // WHERE customer_id IN (12, 34)
     * $query->filterByCustomerId(array('min' => 12)); // WHERE customer_id > 12
     * </code>
     *
     * @see       filterByCustomer()
     *
     * @param     mixed $customerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByCustomerId($customerId = null, $comparison = null)
    {
        if (is_array($customerId)) {
            $useMinMax = false;
            if (isset($customerId['min'])) {
                $this->addUsingAlias(CustomerCustomerCategoryTableMap::CUSTOMER_ID, $customerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerId['max'])) {
                $this->addUsingAlias(CustomerCustomerCategoryTableMap::CUSTOMER_ID, $customerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCustomerCategoryTableMap::CUSTOMER_ID, $customerId, $comparison);
    }

    /**
     * Filter the query on the customer_category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerCategoryId('fooValue');   // WHERE customer_category_id = 'fooValue'
     * $query->filterByCustomerCategoryId('%fooValue%'); // WHERE customer_category_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $customerCategoryId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByCustomerCategoryId($customerCategoryId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($customerCategoryId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $customerCategoryId)) {
                $customerCategoryId = str_replace('*', '%', $customerCategoryId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CustomerCustomerCategoryTableMap::CUSTOMER_CATEGORY_ID, $customerCategoryId, $comparison);
    }

    /**
     * Filter the query on the siret column
     *
     * Example usage:
     * <code>
     * $query->filterBySiret('fooValue');   // WHERE siret = 'fooValue'
     * $query->filterBySiret('%fooValue%'); // WHERE siret LIKE '%fooValue%'
     * </code>
     *
     * @param     string $siret The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterBySiret($siret = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($siret)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $siret)) {
                $siret = str_replace('*', '%', $siret);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CustomerCustomerCategoryTableMap::SIRET, $siret, $comparison);
    }

    /**
     * Filter the query on the vat column
     *
     * Example usage:
     * <code>
     * $query->filterByVat('fooValue');   // WHERE vat = 'fooValue'
     * $query->filterByVat('%fooValue%'); // WHERE vat LIKE '%fooValue%'
     * </code>
     *
     * @param     string $vat The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByVat($vat = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($vat)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $vat)) {
                $vat = str_replace('*', '%', $vat);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CustomerCustomerCategoryTableMap::VAT, $vat, $comparison);
    }

    /**
     * Filter the query by a related \CustomerCategory\Model\Thelia\Model\Customer object
     *
     * @param \CustomerCategory\Model\Thelia\Model\Customer|ObjectCollection $customer The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByCustomer($customer, $comparison = null)
    {
        if ($customer instanceof \CustomerCategory\Model\Thelia\Model\Customer) {
            return $this
                ->addUsingAlias(CustomerCustomerCategoryTableMap::CUSTOMER_ID, $customer->getId(), $comparison);
        } elseif ($customer instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CustomerCustomerCategoryTableMap::CUSTOMER_ID, $customer->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCustomer() only accepts arguments of type \CustomerCategory\Model\Thelia\Model\Customer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Customer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCustomerCustomerCategoryQuery The current query, for fluid interface
     */
    public function joinCustomer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Customer');

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
            $this->addJoinObject($join, 'Customer');
        }

        return $this;
    }

    /**
     * Use the Customer relation Customer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CustomerCategory\Model\Thelia\Model\CustomerQuery A secondary query class using the current class as primary query
     */
    public function useCustomerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCustomer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Customer', '\CustomerCategory\Model\Thelia\Model\CustomerQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCustomerCustomerCategory $customerCustomerCategory Object to remove from the list of results
     *
     * @return ChildCustomerCustomerCategoryQuery The current query, for fluid interface
     */
    public function prune($customerCustomerCategory = null)
    {
        if ($customerCustomerCategory) {
            $this->addUsingAlias(CustomerCustomerCategoryTableMap::CUSTOMER_ID, $customerCustomerCategory->getCustomerId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the customer_customer_category table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerCustomerCategoryTableMap::DATABASE_NAME);
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
            CustomerCustomerCategoryTableMap::clearInstancePool();
            CustomerCustomerCategoryTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildCustomerCustomerCategory or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildCustomerCustomerCategory object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerCustomerCategoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CustomerCustomerCategoryTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        CustomerCustomerCategoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CustomerCustomerCategoryTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // CustomerCustomerCategoryQuery
