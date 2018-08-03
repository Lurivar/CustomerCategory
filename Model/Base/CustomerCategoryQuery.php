<?php

namespace CustomerCategory\Model\Base;

use \Exception;
use \PDO;
use CustomerCategory\Model\CustomerCategory as ChildCustomerCategory;
use CustomerCategory\Model\CustomerCategoryI18nQuery as ChildCustomerCategoryI18nQuery;
use CustomerCategory\Model\CustomerCategoryQuery as ChildCustomerCategoryQuery;
use CustomerCategory\Model\Map\CustomerCategoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'customer_category' table.
 *
 *
 *
 * @method     ChildCustomerCategoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCustomerCategoryQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildCustomerCategoryQuery orderByIsDefault($order = Criteria::ASC) Order by the is_default column
 * @method     ChildCustomerCategoryQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildCustomerCategoryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildCustomerCategoryQuery groupById() Group by the id column
 * @method     ChildCustomerCategoryQuery groupByCode() Group by the code column
 * @method     ChildCustomerCategoryQuery groupByIsDefault() Group by the is_default column
 * @method     ChildCustomerCategoryQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildCustomerCategoryQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildCustomerCategoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCustomerCategoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCustomerCategoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCustomerCategoryQuery leftJoinCustomerCategoryPrice($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomerCategoryPrice relation
 * @method     ChildCustomerCategoryQuery rightJoinCustomerCategoryPrice($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomerCategoryPrice relation
 * @method     ChildCustomerCategoryQuery innerJoinCustomerCategoryPrice($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomerCategoryPrice relation
 *
 * @method     ChildCustomerCategoryQuery leftJoinCustomerCategoryOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomerCategoryOrder relation
 * @method     ChildCustomerCategoryQuery rightJoinCustomerCategoryOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomerCategoryOrder relation
 * @method     ChildCustomerCategoryQuery innerJoinCustomerCategoryOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomerCategoryOrder relation
 *
 * @method     ChildCustomerCategoryQuery leftJoinCustomerCategoryI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomerCategoryI18n relation
 * @method     ChildCustomerCategoryQuery rightJoinCustomerCategoryI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomerCategoryI18n relation
 * @method     ChildCustomerCategoryQuery innerJoinCustomerCategoryI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomerCategoryI18n relation
 *
 * @method     ChildCustomerCategory findOne(ConnectionInterface $con = null) Return the first ChildCustomerCategory matching the query
 * @method     ChildCustomerCategory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCustomerCategory matching the query, or a new ChildCustomerCategory object populated from the query conditions when no match is found
 *
 * @method     ChildCustomerCategory findOneById(int $id) Return the first ChildCustomerCategory filtered by the id column
 * @method     ChildCustomerCategory findOneByCode(string $code) Return the first ChildCustomerCategory filtered by the code column
 * @method     ChildCustomerCategory findOneByIsDefault(int $is_default) Return the first ChildCustomerCategory filtered by the is_default column
 * @method     ChildCustomerCategory findOneByCreatedAt(string $created_at) Return the first ChildCustomerCategory filtered by the created_at column
 * @method     ChildCustomerCategory findOneByUpdatedAt(string $updated_at) Return the first ChildCustomerCategory filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildCustomerCategory objects filtered by the id column
 * @method     array findByCode(string $code) Return ChildCustomerCategory objects filtered by the code column
 * @method     array findByIsDefault(int $is_default) Return ChildCustomerCategory objects filtered by the is_default column
 * @method     array findByCreatedAt(string $created_at) Return ChildCustomerCategory objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildCustomerCategory objects filtered by the updated_at column
 *
 */
abstract class CustomerCategoryQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \CustomerCategory\Model\Base\CustomerCategoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\CustomerCategory\\Model\\CustomerCategory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCustomerCategoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCustomerCategoryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \CustomerCategory\Model\CustomerCategoryQuery) {
            return $criteria;
        }
        $query = new \CustomerCategory\Model\CustomerCategoryQuery();
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
     * @return ChildCustomerCategory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CustomerCategoryTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerCategoryTableMap::DATABASE_NAME);
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
     * @return   ChildCustomerCategory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, CODE, IS_DEFAULT, CREATED_AT, UPDATED_AT FROM customer_category WHERE ID = :p0';
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
            $obj = new ChildCustomerCategory();
            $obj->hydrate($row);
            CustomerCategoryTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildCustomerCategory|array|mixed the result, formatted by the current formatter
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
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CustomerCategoryTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CustomerCategoryTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CustomerCategoryTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CustomerCategoryTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CustomerCategoryTableMap::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the is_default column
     *
     * Example usage:
     * <code>
     * $query->filterByIsDefault(1234); // WHERE is_default = 1234
     * $query->filterByIsDefault(array(12, 34)); // WHERE is_default IN (12, 34)
     * $query->filterByIsDefault(array('min' => 12)); // WHERE is_default > 12
     * </code>
     *
     * @param     mixed $isDefault The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByIsDefault($isDefault = null, $comparison = null)
    {
        if (is_array($isDefault)) {
            $useMinMax = false;
            if (isset($isDefault['min'])) {
                $this->addUsingAlias(CustomerCategoryTableMap::IS_DEFAULT, $isDefault['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($isDefault['max'])) {
                $this->addUsingAlias(CustomerCategoryTableMap::IS_DEFAULT, $isDefault['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryTableMap::IS_DEFAULT, $isDefault, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CustomerCategoryTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CustomerCategoryTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CustomerCategoryTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CustomerCategoryTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \CustomerCategory\Model\CustomerCategoryPrice object
     *
     * @param \CustomerCategory\Model\CustomerCategoryPrice|ObjectCollection $customerCategoryPrice  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByCustomerCategoryPrice($customerCategoryPrice, $comparison = null)
    {
        if ($customerCategoryPrice instanceof \CustomerCategory\Model\CustomerCategoryPrice) {
            return $this
                ->addUsingAlias(CustomerCategoryTableMap::ID, $customerCategoryPrice->getCustomerCategoryId(), $comparison);
        } elseif ($customerCategoryPrice instanceof ObjectCollection) {
            return $this
                ->useCustomerCategoryPriceQuery()
                ->filterByPrimaryKeys($customerCategoryPrice->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCustomerCategoryPrice() only accepts arguments of type \CustomerCategory\Model\CustomerCategoryPrice or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CustomerCategoryPrice relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function joinCustomerCategoryPrice($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CustomerCategoryPrice');

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
            $this->addJoinObject($join, 'CustomerCategoryPrice');
        }

        return $this;
    }

    /**
     * Use the CustomerCategoryPrice relation CustomerCategoryPrice object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CustomerCategory\Model\CustomerCategoryPriceQuery A secondary query class using the current class as primary query
     */
    public function useCustomerCategoryPriceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCustomerCategoryPrice($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomerCategoryPrice', '\CustomerCategory\Model\CustomerCategoryPriceQuery');
    }

    /**
     * Filter the query by a related \CustomerCategory\Model\CustomerCategoryOrder object
     *
     * @param \CustomerCategory\Model\CustomerCategoryOrder|ObjectCollection $customerCategoryOrder  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByCustomerCategoryOrder($customerCategoryOrder, $comparison = null)
    {
        if ($customerCategoryOrder instanceof \CustomerCategory\Model\CustomerCategoryOrder) {
            return $this
                ->addUsingAlias(CustomerCategoryTableMap::ID, $customerCategoryOrder->getCustomerCategoryId(), $comparison);
        } elseif ($customerCategoryOrder instanceof ObjectCollection) {
            return $this
                ->useCustomerCategoryOrderQuery()
                ->filterByPrimaryKeys($customerCategoryOrder->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCustomerCategoryOrder() only accepts arguments of type \CustomerCategory\Model\CustomerCategoryOrder or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CustomerCategoryOrder relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function joinCustomerCategoryOrder($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CustomerCategoryOrder');

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
            $this->addJoinObject($join, 'CustomerCategoryOrder');
        }

        return $this;
    }

    /**
     * Use the CustomerCategoryOrder relation CustomerCategoryOrder object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CustomerCategory\Model\CustomerCategoryOrderQuery A secondary query class using the current class as primary query
     */
    public function useCustomerCategoryOrderQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCustomerCategoryOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomerCategoryOrder', '\CustomerCategory\Model\CustomerCategoryOrderQuery');
    }

    /**
     * Filter the query by a related \CustomerCategory\Model\CustomerCategoryI18n object
     *
     * @param \CustomerCategory\Model\CustomerCategoryI18n|ObjectCollection $customerCategoryI18n  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function filterByCustomerCategoryI18n($customerCategoryI18n, $comparison = null)
    {
        if ($customerCategoryI18n instanceof \CustomerCategory\Model\CustomerCategoryI18n) {
            return $this
                ->addUsingAlias(CustomerCategoryTableMap::ID, $customerCategoryI18n->getId(), $comparison);
        } elseif ($customerCategoryI18n instanceof ObjectCollection) {
            return $this
                ->useCustomerCategoryI18nQuery()
                ->filterByPrimaryKeys($customerCategoryI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCustomerCategoryI18n() only accepts arguments of type \CustomerCategory\Model\CustomerCategoryI18n or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CustomerCategoryI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function joinCustomerCategoryI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CustomerCategoryI18n');

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
            $this->addJoinObject($join, 'CustomerCategoryI18n');
        }

        return $this;
    }

    /**
     * Use the CustomerCategoryI18n relation CustomerCategoryI18n object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CustomerCategory\Model\CustomerCategoryI18nQuery A secondary query class using the current class as primary query
     */
    public function useCustomerCategoryI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinCustomerCategoryI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomerCategoryI18n', '\CustomerCategory\Model\CustomerCategoryI18nQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCustomerCategory $customerCategory Object to remove from the list of results
     *
     * @return ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function prune($customerCategory = null)
    {
        if ($customerCategory) {
            $this->addUsingAlias(CustomerCategoryTableMap::ID, $customerCategory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the customer_category table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerCategoryTableMap::DATABASE_NAME);
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
            CustomerCategoryTableMap::clearInstancePool();
            CustomerCategoryTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildCustomerCategory or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildCustomerCategory object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerCategoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CustomerCategoryTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        CustomerCategoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CustomerCategoryTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CustomerCategoryTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CustomerCategoryTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CustomerCategoryTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CustomerCategoryTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CustomerCategoryTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CustomerCategoryTableMap::CREATED_AT);
    }

    // i18n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function joinI18n($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $relationName = $relationAlias ? $relationAlias : 'CustomerCategoryI18n';

        return $this
            ->joinCustomerCategoryI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildCustomerCategoryQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = 'en_US', $joinType = Criteria::LEFT_JOIN)
    {
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('CustomerCategoryI18n');
        $this->with['CustomerCategoryI18n']->setIsWithOneToMany(false);

        return $this;
    }

    /**
     * Use the I18n relation query object
     *
     * @see       useQuery()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildCustomerCategoryI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomerCategoryI18n', '\CustomerCategory\Model\CustomerCategoryI18nQuery');
    }

} // CustomerCategoryQuery
