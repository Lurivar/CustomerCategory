<?php

namespace CustomerCategory\Model\Base;

use \Exception;
use \PDO;
use CustomerCategory\Model\CustomerCategoryPrice as ChildCustomerCategoryPrice;
use CustomerCategory\Model\CustomerCategoryPriceQuery as ChildCustomerCategoryPriceQuery;
use CustomerCategory\Model\Map\CustomerCategoryPriceTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'customer_category_price' table.
 *
 *
 *
 * @method     ChildCustomerCategoryPriceQuery orderByCustomerCategoryId($order = Criteria::ASC) Order by the customer_category_id column
 * @method     ChildCustomerCategoryPriceQuery orderByPromo($order = Criteria::ASC) Order by the promo column
 * @method     ChildCustomerCategoryPriceQuery orderByUseEquation($order = Criteria::ASC) Order by the use_equation column
 * @method     ChildCustomerCategoryPriceQuery orderByAmountAddedBefore($order = Criteria::ASC) Order by the amount_added_before column
 * @method     ChildCustomerCategoryPriceQuery orderByAmountAddedAfter($order = Criteria::ASC) Order by the amount_added_after column
 * @method     ChildCustomerCategoryPriceQuery orderByMultiplicationCoefficient($order = Criteria::ASC) Order by the multiplication_coefficient column
 * @method     ChildCustomerCategoryPriceQuery orderByIsTaxed($order = Criteria::ASC) Order by the is_taxed column
 *
 * @method     ChildCustomerCategoryPriceQuery groupByCustomerCategoryId() Group by the customer_category_id column
 * @method     ChildCustomerCategoryPriceQuery groupByPromo() Group by the promo column
 * @method     ChildCustomerCategoryPriceQuery groupByUseEquation() Group by the use_equation column
 * @method     ChildCustomerCategoryPriceQuery groupByAmountAddedBefore() Group by the amount_added_before column
 * @method     ChildCustomerCategoryPriceQuery groupByAmountAddedAfter() Group by the amount_added_after column
 * @method     ChildCustomerCategoryPriceQuery groupByMultiplicationCoefficient() Group by the multiplication_coefficient column
 * @method     ChildCustomerCategoryPriceQuery groupByIsTaxed() Group by the is_taxed column
 *
 * @method     ChildCustomerCategoryPriceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCustomerCategoryPriceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCustomerCategoryPriceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCustomerCategoryPriceQuery leftJoinCustomerCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomerCategory relation
 * @method     ChildCustomerCategoryPriceQuery rightJoinCustomerCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomerCategory relation
 * @method     ChildCustomerCategoryPriceQuery innerJoinCustomerCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomerCategory relation
 *
 * @method     ChildCustomerCategoryPrice findOne(ConnectionInterface $con = null) Return the first ChildCustomerCategoryPrice matching the query
 * @method     ChildCustomerCategoryPrice findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCustomerCategoryPrice matching the query, or a new ChildCustomerCategoryPrice object populated from the query conditions when no match is found
 *
 * @method     ChildCustomerCategoryPrice findOneByCustomerCategoryId(int $customer_category_id) Return the first ChildCustomerCategoryPrice filtered by the customer_category_id column
 * @method     ChildCustomerCategoryPrice findOneByPromo(int $promo) Return the first ChildCustomerCategoryPrice filtered by the promo column
 * @method     ChildCustomerCategoryPrice findOneByUseEquation(int $use_equation) Return the first ChildCustomerCategoryPrice filtered by the use_equation column
 * @method     ChildCustomerCategoryPrice findOneByAmountAddedBefore(string $amount_added_before) Return the first ChildCustomerCategoryPrice filtered by the amount_added_before column
 * @method     ChildCustomerCategoryPrice findOneByAmountAddedAfter(string $amount_added_after) Return the first ChildCustomerCategoryPrice filtered by the amount_added_after column
 * @method     ChildCustomerCategoryPrice findOneByMultiplicationCoefficient(string $multiplication_coefficient) Return the first ChildCustomerCategoryPrice filtered by the multiplication_coefficient column
 * @method     ChildCustomerCategoryPrice findOneByIsTaxed(int $is_taxed) Return the first ChildCustomerCategoryPrice filtered by the is_taxed column
 *
 * @method     array findByCustomerCategoryId(int $customer_category_id) Return ChildCustomerCategoryPrice objects filtered by the customer_category_id column
 * @method     array findByPromo(int $promo) Return ChildCustomerCategoryPrice objects filtered by the promo column
 * @method     array findByUseEquation(int $use_equation) Return ChildCustomerCategoryPrice objects filtered by the use_equation column
 * @method     array findByAmountAddedBefore(string $amount_added_before) Return ChildCustomerCategoryPrice objects filtered by the amount_added_before column
 * @method     array findByAmountAddedAfter(string $amount_added_after) Return ChildCustomerCategoryPrice objects filtered by the amount_added_after column
 * @method     array findByMultiplicationCoefficient(string $multiplication_coefficient) Return ChildCustomerCategoryPrice objects filtered by the multiplication_coefficient column
 * @method     array findByIsTaxed(int $is_taxed) Return ChildCustomerCategoryPrice objects filtered by the is_taxed column
 *
 */
abstract class CustomerCategoryPriceQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \CustomerCategory\Model\Base\CustomerCategoryPriceQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\CustomerCategory\\Model\\CustomerCategoryPrice', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCustomerCategoryPriceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCustomerCategoryPriceQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \CustomerCategory\Model\CustomerCategoryPriceQuery) {
            return $criteria;
        }
        $query = new \CustomerCategory\Model\CustomerCategoryPriceQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$customer_category_id, $promo] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCustomerCategoryPrice|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CustomerCategoryPriceTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerCategoryPriceTableMap::DATABASE_NAME);
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
     * @return   ChildCustomerCategoryPrice A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT CUSTOMER_CATEGORY_ID, PROMO, USE_EQUATION, AMOUNT_ADDED_BEFORE, AMOUNT_ADDED_AFTER, MULTIPLICATION_COEFFICIENT, IS_TAXED FROM customer_category_price WHERE CUSTOMER_CATEGORY_ID = :p0 AND PROMO = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildCustomerCategoryPrice();
            $obj->hydrate($row);
            CustomerCategoryPriceTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildCustomerCategoryPrice|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return ChildCustomerCategoryPriceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(CustomerCategoryPriceTableMap::CUSTOMER_CATEGORY_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(CustomerCategoryPriceTableMap::PROMO, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildCustomerCategoryPriceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(CustomerCategoryPriceTableMap::CUSTOMER_CATEGORY_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(CustomerCategoryPriceTableMap::PROMO, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return ChildCustomerCategoryPriceQuery The current query, for fluid interface
     */
    public function filterByCustomerCategoryId($customerCategoryId = null, $comparison = null)
    {
        if (is_array($customerCategoryId)) {
            $useMinMax = false;
            if (isset($customerCategoryId['min'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::CUSTOMER_CATEGORY_ID, $customerCategoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerCategoryId['max'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::CUSTOMER_CATEGORY_ID, $customerCategoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryPriceTableMap::CUSTOMER_CATEGORY_ID, $customerCategoryId, $comparison);
    }

    /**
     * Filter the query on the promo column
     *
     * Example usage:
     * <code>
     * $query->filterByPromo(1234); // WHERE promo = 1234
     * $query->filterByPromo(array(12, 34)); // WHERE promo IN (12, 34)
     * $query->filterByPromo(array('min' => 12)); // WHERE promo > 12
     * </code>
     *
     * @param     mixed $promo The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryPriceQuery The current query, for fluid interface
     */
    public function filterByPromo($promo = null, $comparison = null)
    {
        if (is_array($promo)) {
            $useMinMax = false;
            if (isset($promo['min'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::PROMO, $promo['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($promo['max'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::PROMO, $promo['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryPriceTableMap::PROMO, $promo, $comparison);
    }

    /**
     * Filter the query on the use_equation column
     *
     * Example usage:
     * <code>
     * $query->filterByUseEquation(1234); // WHERE use_equation = 1234
     * $query->filterByUseEquation(array(12, 34)); // WHERE use_equation IN (12, 34)
     * $query->filterByUseEquation(array('min' => 12)); // WHERE use_equation > 12
     * </code>
     *
     * @param     mixed $useEquation The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryPriceQuery The current query, for fluid interface
     */
    public function filterByUseEquation($useEquation = null, $comparison = null)
    {
        if (is_array($useEquation)) {
            $useMinMax = false;
            if (isset($useEquation['min'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::USE_EQUATION, $useEquation['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($useEquation['max'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::USE_EQUATION, $useEquation['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryPriceTableMap::USE_EQUATION, $useEquation, $comparison);
    }

    /**
     * Filter the query on the amount_added_before column
     *
     * Example usage:
     * <code>
     * $query->filterByAmountAddedBefore(1234); // WHERE amount_added_before = 1234
     * $query->filterByAmountAddedBefore(array(12, 34)); // WHERE amount_added_before IN (12, 34)
     * $query->filterByAmountAddedBefore(array('min' => 12)); // WHERE amount_added_before > 12
     * </code>
     *
     * @param     mixed $amountAddedBefore The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryPriceQuery The current query, for fluid interface
     */
    public function filterByAmountAddedBefore($amountAddedBefore = null, $comparison = null)
    {
        if (is_array($amountAddedBefore)) {
            $useMinMax = false;
            if (isset($amountAddedBefore['min'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::AMOUNT_ADDED_BEFORE, $amountAddedBefore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amountAddedBefore['max'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::AMOUNT_ADDED_BEFORE, $amountAddedBefore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryPriceTableMap::AMOUNT_ADDED_BEFORE, $amountAddedBefore, $comparison);
    }

    /**
     * Filter the query on the amount_added_after column
     *
     * Example usage:
     * <code>
     * $query->filterByAmountAddedAfter(1234); // WHERE amount_added_after = 1234
     * $query->filterByAmountAddedAfter(array(12, 34)); // WHERE amount_added_after IN (12, 34)
     * $query->filterByAmountAddedAfter(array('min' => 12)); // WHERE amount_added_after > 12
     * </code>
     *
     * @param     mixed $amountAddedAfter The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryPriceQuery The current query, for fluid interface
     */
    public function filterByAmountAddedAfter($amountAddedAfter = null, $comparison = null)
    {
        if (is_array($amountAddedAfter)) {
            $useMinMax = false;
            if (isset($amountAddedAfter['min'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::AMOUNT_ADDED_AFTER, $amountAddedAfter['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amountAddedAfter['max'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::AMOUNT_ADDED_AFTER, $amountAddedAfter['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryPriceTableMap::AMOUNT_ADDED_AFTER, $amountAddedAfter, $comparison);
    }

    /**
     * Filter the query on the multiplication_coefficient column
     *
     * Example usage:
     * <code>
     * $query->filterByMultiplicationCoefficient(1234); // WHERE multiplication_coefficient = 1234
     * $query->filterByMultiplicationCoefficient(array(12, 34)); // WHERE multiplication_coefficient IN (12, 34)
     * $query->filterByMultiplicationCoefficient(array('min' => 12)); // WHERE multiplication_coefficient > 12
     * </code>
     *
     * @param     mixed $multiplicationCoefficient The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryPriceQuery The current query, for fluid interface
     */
    public function filterByMultiplicationCoefficient($multiplicationCoefficient = null, $comparison = null)
    {
        if (is_array($multiplicationCoefficient)) {
            $useMinMax = false;
            if (isset($multiplicationCoefficient['min'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::MULTIPLICATION_COEFFICIENT, $multiplicationCoefficient['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($multiplicationCoefficient['max'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::MULTIPLICATION_COEFFICIENT, $multiplicationCoefficient['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryPriceTableMap::MULTIPLICATION_COEFFICIENT, $multiplicationCoefficient, $comparison);
    }

    /**
     * Filter the query on the is_taxed column
     *
     * Example usage:
     * <code>
     * $query->filterByIsTaxed(1234); // WHERE is_taxed = 1234
     * $query->filterByIsTaxed(array(12, 34)); // WHERE is_taxed IN (12, 34)
     * $query->filterByIsTaxed(array('min' => 12)); // WHERE is_taxed > 12
     * </code>
     *
     * @param     mixed $isTaxed The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryPriceQuery The current query, for fluid interface
     */
    public function filterByIsTaxed($isTaxed = null, $comparison = null)
    {
        if (is_array($isTaxed)) {
            $useMinMax = false;
            if (isset($isTaxed['min'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::IS_TAXED, $isTaxed['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($isTaxed['max'])) {
                $this->addUsingAlias(CustomerCategoryPriceTableMap::IS_TAXED, $isTaxed['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerCategoryPriceTableMap::IS_TAXED, $isTaxed, $comparison);
    }

    /**
     * Filter the query by a related \CustomerCategory\Model\CustomerCategory object
     *
     * @param \CustomerCategory\Model\CustomerCategory|ObjectCollection $customerCategory The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerCategoryPriceQuery The current query, for fluid interface
     */
    public function filterByCustomerCategory($customerCategory, $comparison = null)
    {
        if ($customerCategory instanceof \CustomerCategory\Model\CustomerCategory) {
            return $this
                ->addUsingAlias(CustomerCategoryPriceTableMap::CUSTOMER_CATEGORY_ID, $customerCategory->getId(), $comparison);
        } elseif ($customerCategory instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CustomerCategoryPriceTableMap::CUSTOMER_CATEGORY_ID, $customerCategory->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ChildCustomerCategoryPriceQuery The current query, for fluid interface
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
     * @param   ChildCustomerCategoryPrice $customerCategoryPrice Object to remove from the list of results
     *
     * @return ChildCustomerCategoryPriceQuery The current query, for fluid interface
     */
    public function prune($customerCategoryPrice = null)
    {
        if ($customerCategoryPrice) {
            $this->addCond('pruneCond0', $this->getAliasedColName(CustomerCategoryPriceTableMap::CUSTOMER_CATEGORY_ID), $customerCategoryPrice->getCustomerCategoryId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(CustomerCategoryPriceTableMap::PROMO), $customerCategoryPrice->getPromo(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the customer_category_price table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerCategoryPriceTableMap::DATABASE_NAME);
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
            CustomerCategoryPriceTableMap::clearInstancePool();
            CustomerCategoryPriceTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildCustomerCategoryPrice or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildCustomerCategoryPrice object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerCategoryPriceTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CustomerCategoryPriceTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        CustomerCategoryPriceTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CustomerCategoryPriceTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // CustomerCategoryPriceQuery
