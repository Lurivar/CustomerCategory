<?php

namespace CustomerCategory\Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use CustomerCategory\Model\CustomerCategory as ChildCustomerCategory;
use CustomerCategory\Model\CustomerCategoryI18n as ChildCustomerCategoryI18n;
use CustomerCategory\Model\CustomerCategoryI18nQuery as ChildCustomerCategoryI18nQuery;
use CustomerCategory\Model\CustomerCategoryOrder as ChildCustomerCategoryOrder;
use CustomerCategory\Model\CustomerCategoryOrderQuery as ChildCustomerCategoryOrderQuery;
use CustomerCategory\Model\CustomerCategoryPrice as ChildCustomerCategoryPrice;
use CustomerCategory\Model\CustomerCategoryPriceQuery as ChildCustomerCategoryPriceQuery;
use CustomerCategory\Model\CustomerCategoryQuery as ChildCustomerCategoryQuery;
use CustomerCategory\Model\Map\CustomerCategoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

abstract class CustomerCategory implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\CustomerCategory\\Model\\Map\\CustomerCategoryTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the is_default field.
     * @var        int
     */
    protected $is_default;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        ObjectCollection|ChildCustomerCategoryPrice[] Collection to store aggregation of ChildCustomerCategoryPrice objects.
     */
    protected $collCustomerCategoryPrices;
    protected $collCustomerCategoryPricesPartial;

    /**
     * @var        ObjectCollection|ChildCustomerCategoryOrder[] Collection to store aggregation of ChildCustomerCategoryOrder objects.
     */
    protected $collCustomerCategoryOrders;
    protected $collCustomerCategoryOrdersPartial;

    /**
     * @var        ObjectCollection|ChildCustomerCategoryI18n[] Collection to store aggregation of ChildCustomerCategoryI18n objects.
     */
    protected $collCustomerCategoryI18ns;
    protected $collCustomerCategoryI18nsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // i18n behavior

    /**
     * Current locale
     * @var        string
     */
    protected $currentLocale = 'en_US';

    /**
     * Current translation objects
     * @var        array[ChildCustomerCategoryI18n]
     */
    protected $currentTranslations;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $customerCategoryPricesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $customerCategoryOrdersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $customerCategoryI18nsScheduledForDeletion = null;

    /**
     * Initializes internal state of CustomerCategory\Model\Base\CustomerCategory object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (Boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (Boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>CustomerCategory</code> instance.  If
     * <code>obj</code> is an instance of <code>CustomerCategory</code>, delegates to
     * <code>equals(CustomerCategory)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        $thisclazz = get_class($this);
        if (!is_object($obj) || !($obj instanceof $thisclazz)) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey()
            || null === $obj->getPrimaryKey())  {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return CustomerCategory The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return CustomerCategory The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return   int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [code] column value.
     *
     * @return   string
     */
    public function getCode()
    {

        return $this->code;
    }

    /**
     * Get the [is_default] column value.
     *
     * @return   int
     */
    public function getIsDefault()
    {

        return $this->is_default;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTime ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \CustomerCategory\Model\CustomerCategory The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[CustomerCategoryTableMap::ID] = true;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [code] column.
     *
     * @param      string $v new value
     * @return   \CustomerCategory\Model\CustomerCategory The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[CustomerCategoryTableMap::CODE] = true;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [is_default] column.
     *
     * @param      int $v new value
     * @return   \CustomerCategory\Model\CustomerCategory The current object (for fluent API support)
     */
    public function setIsDefault($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->is_default !== $v) {
            $this->is_default = $v;
            $this->modifiedColumns[CustomerCategoryTableMap::IS_DEFAULT] = true;
        }


        return $this;
    } // setIsDefault()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \CustomerCategory\Model\CustomerCategory The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($dt !== $this->created_at) {
                $this->created_at = $dt;
                $this->modifiedColumns[CustomerCategoryTableMap::CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \CustomerCategory\Model\CustomerCategory The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[CustomerCategoryTableMap::UPDATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CustomerCategoryTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CustomerCategoryTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CustomerCategoryTableMap::translateFieldName('IsDefault', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_default = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CustomerCategoryTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CustomerCategoryTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = CustomerCategoryTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \CustomerCategory\Model\CustomerCategory object", 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerCategoryTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCustomerCategoryQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCustomerCategoryPrices = null;

            $this->collCustomerCategoryOrders = null;

            $this->collCustomerCategoryI18ns = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see CustomerCategory::setDeleted()
     * @see CustomerCategory::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerCategoryTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildCustomerCategoryQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerCategoryTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(CustomerCategoryTableMap::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(CustomerCategoryTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(CustomerCategoryTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                CustomerCategoryTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->customerCategoryPricesScheduledForDeletion !== null) {
                if (!$this->customerCategoryPricesScheduledForDeletion->isEmpty()) {
                    \CustomerCategory\Model\CustomerCategoryPriceQuery::create()
                        ->filterByPrimaryKeys($this->customerCategoryPricesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->customerCategoryPricesScheduledForDeletion = null;
                }
            }

                if ($this->collCustomerCategoryPrices !== null) {
            foreach ($this->collCustomerCategoryPrices as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->customerCategoryOrdersScheduledForDeletion !== null) {
                if (!$this->customerCategoryOrdersScheduledForDeletion->isEmpty()) {
                    \CustomerCategory\Model\CustomerCategoryOrderQuery::create()
                        ->filterByPrimaryKeys($this->customerCategoryOrdersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->customerCategoryOrdersScheduledForDeletion = null;
                }
            }

                if ($this->collCustomerCategoryOrders !== null) {
            foreach ($this->collCustomerCategoryOrders as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->customerCategoryI18nsScheduledForDeletion !== null) {
                if (!$this->customerCategoryI18nsScheduledForDeletion->isEmpty()) {
                    \CustomerCategory\Model\CustomerCategoryI18nQuery::create()
                        ->filterByPrimaryKeys($this->customerCategoryI18nsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->customerCategoryI18nsScheduledForDeletion = null;
                }
            }

                if ($this->collCustomerCategoryI18ns !== null) {
            foreach ($this->collCustomerCategoryI18ns as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[CustomerCategoryTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CustomerCategoryTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CustomerCategoryTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(CustomerCategoryTableMap::CODE)) {
            $modifiedColumns[':p' . $index++]  = 'CODE';
        }
        if ($this->isColumnModified(CustomerCategoryTableMap::IS_DEFAULT)) {
            $modifiedColumns[':p' . $index++]  = 'IS_DEFAULT';
        }
        if ($this->isColumnModified(CustomerCategoryTableMap::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'CREATED_AT';
        }
        if ($this->isColumnModified(CustomerCategoryTableMap::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'UPDATED_AT';
        }

        $sql = sprintf(
            'INSERT INTO customer_category (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'CODE':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case 'IS_DEFAULT':
                        $stmt->bindValue($identifier, $this->is_default, PDO::PARAM_INT);
                        break;
                    case 'CREATED_AT':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'UPDATED_AT':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CustomerCategoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getCode();
                break;
            case 2:
                return $this->getIsDefault();
                break;
            case 3:
                return $this->getCreatedAt();
                break;
            case 4:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['CustomerCategory'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['CustomerCategory'][$this->getPrimaryKey()] = true;
        $keys = CustomerCategoryTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCode(),
            $keys[2] => $this->getIsDefault(),
            $keys[3] => $this->getCreatedAt(),
            $keys[4] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCustomerCategoryPrices) {
                $result['CustomerCategoryPrices'] = $this->collCustomerCategoryPrices->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCustomerCategoryOrders) {
                $result['CustomerCategoryOrders'] = $this->collCustomerCategoryOrders->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCustomerCategoryI18ns) {
                $result['CustomerCategoryI18ns'] = $this->collCustomerCategoryI18ns->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param      string $name
     * @param      mixed  $value field value
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return void
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CustomerCategoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @param      mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setCode($value);
                break;
            case 2:
                $this->setIsDefault($value);
                break;
            case 3:
                $this->setCreatedAt($value);
                break;
            case 4:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = CustomerCategoryTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCode($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setIsDefault($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setCreatedAt($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setUpdatedAt($arr[$keys[4]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CustomerCategoryTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CustomerCategoryTableMap::ID)) $criteria->add(CustomerCategoryTableMap::ID, $this->id);
        if ($this->isColumnModified(CustomerCategoryTableMap::CODE)) $criteria->add(CustomerCategoryTableMap::CODE, $this->code);
        if ($this->isColumnModified(CustomerCategoryTableMap::IS_DEFAULT)) $criteria->add(CustomerCategoryTableMap::IS_DEFAULT, $this->is_default);
        if ($this->isColumnModified(CustomerCategoryTableMap::CREATED_AT)) $criteria->add(CustomerCategoryTableMap::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(CustomerCategoryTableMap::UPDATED_AT)) $criteria->add(CustomerCategoryTableMap::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(CustomerCategoryTableMap::DATABASE_NAME);
        $criteria->add(CustomerCategoryTableMap::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \CustomerCategory\Model\CustomerCategory (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCode($this->getCode());
        $copyObj->setIsDefault($this->getIsDefault());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCustomerCategoryPrices() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCustomerCategoryPrice($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCustomerCategoryOrders() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCustomerCategoryOrder($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCustomerCategoryI18ns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCustomerCategoryI18n($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return                 \CustomerCategory\Model\CustomerCategory Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('CustomerCategoryPrice' == $relationName) {
            return $this->initCustomerCategoryPrices();
        }
        if ('CustomerCategoryOrder' == $relationName) {
            return $this->initCustomerCategoryOrders();
        }
        if ('CustomerCategoryI18n' == $relationName) {
            return $this->initCustomerCategoryI18ns();
        }
    }

    /**
     * Clears out the collCustomerCategoryPrices collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCustomerCategoryPrices()
     */
    public function clearCustomerCategoryPrices()
    {
        $this->collCustomerCategoryPrices = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCustomerCategoryPrices collection loaded partially.
     */
    public function resetPartialCustomerCategoryPrices($v = true)
    {
        $this->collCustomerCategoryPricesPartial = $v;
    }

    /**
     * Initializes the collCustomerCategoryPrices collection.
     *
     * By default this just sets the collCustomerCategoryPrices collection to an empty array (like clearcollCustomerCategoryPrices());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCustomerCategoryPrices($overrideExisting = true)
    {
        if (null !== $this->collCustomerCategoryPrices && !$overrideExisting) {
            return;
        }
        $this->collCustomerCategoryPrices = new ObjectCollection();
        $this->collCustomerCategoryPrices->setModel('\CustomerCategory\Model\CustomerCategoryPrice');
    }

    /**
     * Gets an array of ChildCustomerCategoryPrice objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCustomerCategory is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildCustomerCategoryPrice[] List of ChildCustomerCategoryPrice objects
     * @throws PropelException
     */
    public function getCustomerCategoryPrices($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCustomerCategoryPricesPartial && !$this->isNew();
        if (null === $this->collCustomerCategoryPrices || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCustomerCategoryPrices) {
                // return empty collection
                $this->initCustomerCategoryPrices();
            } else {
                $collCustomerCategoryPrices = ChildCustomerCategoryPriceQuery::create(null, $criteria)
                    ->filterByCustomerCategory($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCustomerCategoryPricesPartial && count($collCustomerCategoryPrices)) {
                        $this->initCustomerCategoryPrices(false);

                        foreach ($collCustomerCategoryPrices as $obj) {
                            if (false == $this->collCustomerCategoryPrices->contains($obj)) {
                                $this->collCustomerCategoryPrices->append($obj);
                            }
                        }

                        $this->collCustomerCategoryPricesPartial = true;
                    }

                    reset($collCustomerCategoryPrices);

                    return $collCustomerCategoryPrices;
                }

                if ($partial && $this->collCustomerCategoryPrices) {
                    foreach ($this->collCustomerCategoryPrices as $obj) {
                        if ($obj->isNew()) {
                            $collCustomerCategoryPrices[] = $obj;
                        }
                    }
                }

                $this->collCustomerCategoryPrices = $collCustomerCategoryPrices;
                $this->collCustomerCategoryPricesPartial = false;
            }
        }

        return $this->collCustomerCategoryPrices;
    }

    /**
     * Sets a collection of CustomerCategoryPrice objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $customerCategoryPrices A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildCustomerCategory The current object (for fluent API support)
     */
    public function setCustomerCategoryPrices(Collection $customerCategoryPrices, ConnectionInterface $con = null)
    {
        $customerCategoryPricesToDelete = $this->getCustomerCategoryPrices(new Criteria(), $con)->diff($customerCategoryPrices);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->customerCategoryPricesScheduledForDeletion = clone $customerCategoryPricesToDelete;

        foreach ($customerCategoryPricesToDelete as $customerCategoryPriceRemoved) {
            $customerCategoryPriceRemoved->setCustomerCategory(null);
        }

        $this->collCustomerCategoryPrices = null;
        foreach ($customerCategoryPrices as $customerCategoryPrice) {
            $this->addCustomerCategoryPrice($customerCategoryPrice);
        }

        $this->collCustomerCategoryPrices = $customerCategoryPrices;
        $this->collCustomerCategoryPricesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CustomerCategoryPrice objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CustomerCategoryPrice objects.
     * @throws PropelException
     */
    public function countCustomerCategoryPrices(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCustomerCategoryPricesPartial && !$this->isNew();
        if (null === $this->collCustomerCategoryPrices || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCustomerCategoryPrices) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCustomerCategoryPrices());
            }

            $query = ChildCustomerCategoryPriceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCustomerCategory($this)
                ->count($con);
        }

        return count($this->collCustomerCategoryPrices);
    }

    /**
     * Method called to associate a ChildCustomerCategoryPrice object to this object
     * through the ChildCustomerCategoryPrice foreign key attribute.
     *
     * @param    ChildCustomerCategoryPrice $l ChildCustomerCategoryPrice
     * @return   \CustomerCategory\Model\CustomerCategory The current object (for fluent API support)
     */
    public function addCustomerCategoryPrice(ChildCustomerCategoryPrice $l)
    {
        if ($this->collCustomerCategoryPrices === null) {
            $this->initCustomerCategoryPrices();
            $this->collCustomerCategoryPricesPartial = true;
        }

        if (!in_array($l, $this->collCustomerCategoryPrices->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCustomerCategoryPrice($l);
        }

        return $this;
    }

    /**
     * @param CustomerCategoryPrice $customerCategoryPrice The customerCategoryPrice object to add.
     */
    protected function doAddCustomerCategoryPrice($customerCategoryPrice)
    {
        $this->collCustomerCategoryPrices[]= $customerCategoryPrice;
        $customerCategoryPrice->setCustomerCategory($this);
    }

    /**
     * @param  CustomerCategoryPrice $customerCategoryPrice The customerCategoryPrice object to remove.
     * @return ChildCustomerCategory The current object (for fluent API support)
     */
    public function removeCustomerCategoryPrice($customerCategoryPrice)
    {
        if ($this->getCustomerCategoryPrices()->contains($customerCategoryPrice)) {
            $this->collCustomerCategoryPrices->remove($this->collCustomerCategoryPrices->search($customerCategoryPrice));
            if (null === $this->customerCategoryPricesScheduledForDeletion) {
                $this->customerCategoryPricesScheduledForDeletion = clone $this->collCustomerCategoryPrices;
                $this->customerCategoryPricesScheduledForDeletion->clear();
            }
            $this->customerCategoryPricesScheduledForDeletion[]= clone $customerCategoryPrice;
            $customerCategoryPrice->setCustomerCategory(null);
        }

        return $this;
    }

    /**
     * Clears out the collCustomerCategoryOrders collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCustomerCategoryOrders()
     */
    public function clearCustomerCategoryOrders()
    {
        $this->collCustomerCategoryOrders = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCustomerCategoryOrders collection loaded partially.
     */
    public function resetPartialCustomerCategoryOrders($v = true)
    {
        $this->collCustomerCategoryOrdersPartial = $v;
    }

    /**
     * Initializes the collCustomerCategoryOrders collection.
     *
     * By default this just sets the collCustomerCategoryOrders collection to an empty array (like clearcollCustomerCategoryOrders());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCustomerCategoryOrders($overrideExisting = true)
    {
        if (null !== $this->collCustomerCategoryOrders && !$overrideExisting) {
            return;
        }
        $this->collCustomerCategoryOrders = new ObjectCollection();
        $this->collCustomerCategoryOrders->setModel('\CustomerCategory\Model\CustomerCategoryOrder');
    }

    /**
     * Gets an array of ChildCustomerCategoryOrder objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCustomerCategory is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildCustomerCategoryOrder[] List of ChildCustomerCategoryOrder objects
     * @throws PropelException
     */
    public function getCustomerCategoryOrders($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCustomerCategoryOrdersPartial && !$this->isNew();
        if (null === $this->collCustomerCategoryOrders || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCustomerCategoryOrders) {
                // return empty collection
                $this->initCustomerCategoryOrders();
            } else {
                $collCustomerCategoryOrders = ChildCustomerCategoryOrderQuery::create(null, $criteria)
                    ->filterByCustomerCategory($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCustomerCategoryOrdersPartial && count($collCustomerCategoryOrders)) {
                        $this->initCustomerCategoryOrders(false);

                        foreach ($collCustomerCategoryOrders as $obj) {
                            if (false == $this->collCustomerCategoryOrders->contains($obj)) {
                                $this->collCustomerCategoryOrders->append($obj);
                            }
                        }

                        $this->collCustomerCategoryOrdersPartial = true;
                    }

                    reset($collCustomerCategoryOrders);

                    return $collCustomerCategoryOrders;
                }

                if ($partial && $this->collCustomerCategoryOrders) {
                    foreach ($this->collCustomerCategoryOrders as $obj) {
                        if ($obj->isNew()) {
                            $collCustomerCategoryOrders[] = $obj;
                        }
                    }
                }

                $this->collCustomerCategoryOrders = $collCustomerCategoryOrders;
                $this->collCustomerCategoryOrdersPartial = false;
            }
        }

        return $this->collCustomerCategoryOrders;
    }

    /**
     * Sets a collection of CustomerCategoryOrder objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $customerCategoryOrders A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildCustomerCategory The current object (for fluent API support)
     */
    public function setCustomerCategoryOrders(Collection $customerCategoryOrders, ConnectionInterface $con = null)
    {
        $customerCategoryOrdersToDelete = $this->getCustomerCategoryOrders(new Criteria(), $con)->diff($customerCategoryOrders);


        $this->customerCategoryOrdersScheduledForDeletion = $customerCategoryOrdersToDelete;

        foreach ($customerCategoryOrdersToDelete as $customerCategoryOrderRemoved) {
            $customerCategoryOrderRemoved->setCustomerCategory(null);
        }

        $this->collCustomerCategoryOrders = null;
        foreach ($customerCategoryOrders as $customerCategoryOrder) {
            $this->addCustomerCategoryOrder($customerCategoryOrder);
        }

        $this->collCustomerCategoryOrders = $customerCategoryOrders;
        $this->collCustomerCategoryOrdersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CustomerCategoryOrder objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CustomerCategoryOrder objects.
     * @throws PropelException
     */
    public function countCustomerCategoryOrders(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCustomerCategoryOrdersPartial && !$this->isNew();
        if (null === $this->collCustomerCategoryOrders || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCustomerCategoryOrders) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCustomerCategoryOrders());
            }

            $query = ChildCustomerCategoryOrderQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCustomerCategory($this)
                ->count($con);
        }

        return count($this->collCustomerCategoryOrders);
    }

    /**
     * Method called to associate a ChildCustomerCategoryOrder object to this object
     * through the ChildCustomerCategoryOrder foreign key attribute.
     *
     * @param    ChildCustomerCategoryOrder $l ChildCustomerCategoryOrder
     * @return   \CustomerCategory\Model\CustomerCategory The current object (for fluent API support)
     */
    public function addCustomerCategoryOrder(ChildCustomerCategoryOrder $l)
    {
        if ($this->collCustomerCategoryOrders === null) {
            $this->initCustomerCategoryOrders();
            $this->collCustomerCategoryOrdersPartial = true;
        }

        if (!in_array($l, $this->collCustomerCategoryOrders->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCustomerCategoryOrder($l);
        }

        return $this;
    }

    /**
     * @param CustomerCategoryOrder $customerCategoryOrder The customerCategoryOrder object to add.
     */
    protected function doAddCustomerCategoryOrder($customerCategoryOrder)
    {
        $this->collCustomerCategoryOrders[]= $customerCategoryOrder;
        $customerCategoryOrder->setCustomerCategory($this);
    }

    /**
     * @param  CustomerCategoryOrder $customerCategoryOrder The customerCategoryOrder object to remove.
     * @return ChildCustomerCategory The current object (for fluent API support)
     */
    public function removeCustomerCategoryOrder($customerCategoryOrder)
    {
        if ($this->getCustomerCategoryOrders()->contains($customerCategoryOrder)) {
            $this->collCustomerCategoryOrders->remove($this->collCustomerCategoryOrders->search($customerCategoryOrder));
            if (null === $this->customerCategoryOrdersScheduledForDeletion) {
                $this->customerCategoryOrdersScheduledForDeletion = clone $this->collCustomerCategoryOrders;
                $this->customerCategoryOrdersScheduledForDeletion->clear();
            }
            $this->customerCategoryOrdersScheduledForDeletion[]= clone $customerCategoryOrder;
            $customerCategoryOrder->setCustomerCategory(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this CustomerCategory is new, it will return
     * an empty collection; or if this CustomerCategory has previously
     * been saved, it will retrieve related CustomerCategoryOrders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in CustomerCategory.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildCustomerCategoryOrder[] List of ChildCustomerCategoryOrder objects
     */
    public function getCustomerCategoryOrdersJoinOrder($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCustomerCategoryOrderQuery::create(null, $criteria);
        $query->joinWith('Order', $joinBehavior);

        return $this->getCustomerCategoryOrders($query, $con);
    }

    /**
     * Clears out the collCustomerCategoryI18ns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCustomerCategoryI18ns()
     */
    public function clearCustomerCategoryI18ns()
    {
        $this->collCustomerCategoryI18ns = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCustomerCategoryI18ns collection loaded partially.
     */
    public function resetPartialCustomerCategoryI18ns($v = true)
    {
        $this->collCustomerCategoryI18nsPartial = $v;
    }

    /**
     * Initializes the collCustomerCategoryI18ns collection.
     *
     * By default this just sets the collCustomerCategoryI18ns collection to an empty array (like clearcollCustomerCategoryI18ns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCustomerCategoryI18ns($overrideExisting = true)
    {
        if (null !== $this->collCustomerCategoryI18ns && !$overrideExisting) {
            return;
        }
        $this->collCustomerCategoryI18ns = new ObjectCollection();
        $this->collCustomerCategoryI18ns->setModel('\CustomerCategory\Model\CustomerCategoryI18n');
    }

    /**
     * Gets an array of ChildCustomerCategoryI18n objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCustomerCategory is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildCustomerCategoryI18n[] List of ChildCustomerCategoryI18n objects
     * @throws PropelException
     */
    public function getCustomerCategoryI18ns($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCustomerCategoryI18nsPartial && !$this->isNew();
        if (null === $this->collCustomerCategoryI18ns || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCustomerCategoryI18ns) {
                // return empty collection
                $this->initCustomerCategoryI18ns();
            } else {
                $collCustomerCategoryI18ns = ChildCustomerCategoryI18nQuery::create(null, $criteria)
                    ->filterByCustomerCategory($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCustomerCategoryI18nsPartial && count($collCustomerCategoryI18ns)) {
                        $this->initCustomerCategoryI18ns(false);

                        foreach ($collCustomerCategoryI18ns as $obj) {
                            if (false == $this->collCustomerCategoryI18ns->contains($obj)) {
                                $this->collCustomerCategoryI18ns->append($obj);
                            }
                        }

                        $this->collCustomerCategoryI18nsPartial = true;
                    }

                    reset($collCustomerCategoryI18ns);

                    return $collCustomerCategoryI18ns;
                }

                if ($partial && $this->collCustomerCategoryI18ns) {
                    foreach ($this->collCustomerCategoryI18ns as $obj) {
                        if ($obj->isNew()) {
                            $collCustomerCategoryI18ns[] = $obj;
                        }
                    }
                }

                $this->collCustomerCategoryI18ns = $collCustomerCategoryI18ns;
                $this->collCustomerCategoryI18nsPartial = false;
            }
        }

        return $this->collCustomerCategoryI18ns;
    }

    /**
     * Sets a collection of CustomerCategoryI18n objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $customerCategoryI18ns A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildCustomerCategory The current object (for fluent API support)
     */
    public function setCustomerCategoryI18ns(Collection $customerCategoryI18ns, ConnectionInterface $con = null)
    {
        $customerCategoryI18nsToDelete = $this->getCustomerCategoryI18ns(new Criteria(), $con)->diff($customerCategoryI18ns);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->customerCategoryI18nsScheduledForDeletion = clone $customerCategoryI18nsToDelete;

        foreach ($customerCategoryI18nsToDelete as $customerCategoryI18nRemoved) {
            $customerCategoryI18nRemoved->setCustomerCategory(null);
        }

        $this->collCustomerCategoryI18ns = null;
        foreach ($customerCategoryI18ns as $customerCategoryI18n) {
            $this->addCustomerCategoryI18n($customerCategoryI18n);
        }

        $this->collCustomerCategoryI18ns = $customerCategoryI18ns;
        $this->collCustomerCategoryI18nsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CustomerCategoryI18n objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CustomerCategoryI18n objects.
     * @throws PropelException
     */
    public function countCustomerCategoryI18ns(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCustomerCategoryI18nsPartial && !$this->isNew();
        if (null === $this->collCustomerCategoryI18ns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCustomerCategoryI18ns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCustomerCategoryI18ns());
            }

            $query = ChildCustomerCategoryI18nQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCustomerCategory($this)
                ->count($con);
        }

        return count($this->collCustomerCategoryI18ns);
    }

    /**
     * Method called to associate a ChildCustomerCategoryI18n object to this object
     * through the ChildCustomerCategoryI18n foreign key attribute.
     *
     * @param    ChildCustomerCategoryI18n $l ChildCustomerCategoryI18n
     * @return   \CustomerCategory\Model\CustomerCategory The current object (for fluent API support)
     */
    public function addCustomerCategoryI18n(ChildCustomerCategoryI18n $l)
    {
        if ($l && $locale = $l->getLocale()) {
            $this->setLocale($locale);
            $this->currentTranslations[$locale] = $l;
        }
        if ($this->collCustomerCategoryI18ns === null) {
            $this->initCustomerCategoryI18ns();
            $this->collCustomerCategoryI18nsPartial = true;
        }

        if (!in_array($l, $this->collCustomerCategoryI18ns->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCustomerCategoryI18n($l);
        }

        return $this;
    }

    /**
     * @param CustomerCategoryI18n $customerCategoryI18n The customerCategoryI18n object to add.
     */
    protected function doAddCustomerCategoryI18n($customerCategoryI18n)
    {
        $this->collCustomerCategoryI18ns[]= $customerCategoryI18n;
        $customerCategoryI18n->setCustomerCategory($this);
    }

    /**
     * @param  CustomerCategoryI18n $customerCategoryI18n The customerCategoryI18n object to remove.
     * @return ChildCustomerCategory The current object (for fluent API support)
     */
    public function removeCustomerCategoryI18n($customerCategoryI18n)
    {
        if ($this->getCustomerCategoryI18ns()->contains($customerCategoryI18n)) {
            $this->collCustomerCategoryI18ns->remove($this->collCustomerCategoryI18ns->search($customerCategoryI18n));
            if (null === $this->customerCategoryI18nsScheduledForDeletion) {
                $this->customerCategoryI18nsScheduledForDeletion = clone $this->collCustomerCategoryI18ns;
                $this->customerCategoryI18nsScheduledForDeletion->clear();
            }
            $this->customerCategoryI18nsScheduledForDeletion[]= clone $customerCategoryI18n;
            $customerCategoryI18n->setCustomerCategory(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->code = null;
        $this->is_default = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collCustomerCategoryPrices) {
                foreach ($this->collCustomerCategoryPrices as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCustomerCategoryOrders) {
                foreach ($this->collCustomerCategoryOrders as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCustomerCategoryI18ns) {
                foreach ($this->collCustomerCategoryI18ns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        // i18n behavior
        $this->currentLocale = 'en_US';
        $this->currentTranslations = null;

        $this->collCustomerCategoryPrices = null;
        $this->collCustomerCategoryOrders = null;
        $this->collCustomerCategoryI18ns = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CustomerCategoryTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     ChildCustomerCategory The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[CustomerCategoryTableMap::UPDATED_AT] = true;

        return $this;
    }

    // i18n behavior

    /**
     * Sets the locale for translations
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     *
     * @return    ChildCustomerCategory The current object (for fluent API support)
     */
    public function setLocale($locale = 'en_US')
    {
        $this->currentLocale = $locale;

        return $this;
    }

    /**
     * Gets the locale for translations
     *
     * @return    string $locale Locale to use for the translation, e.g. 'fr_FR'
     */
    public function getLocale()
    {
        return $this->currentLocale;
    }

    /**
     * Returns the current translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildCustomerCategoryI18n */
    public function getTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!isset($this->currentTranslations[$locale])) {
            if (null !== $this->collCustomerCategoryI18ns) {
                foreach ($this->collCustomerCategoryI18ns as $translation) {
                    if ($translation->getLocale() == $locale) {
                        $this->currentTranslations[$locale] = $translation;

                        return $translation;
                    }
                }
            }
            if ($this->isNew()) {
                $translation = new ChildCustomerCategoryI18n();
                $translation->setLocale($locale);
            } else {
                $translation = ChildCustomerCategoryI18nQuery::create()
                    ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                    ->findOneOrCreate($con);
                $this->currentTranslations[$locale] = $translation;
            }
            $this->addCustomerCategoryI18n($translation);
        }

        return $this->currentTranslations[$locale];
    }

    /**
     * Remove the translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return    ChildCustomerCategory The current object (for fluent API support)
     */
    public function removeTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!$this->isNew()) {
            ChildCustomerCategoryI18nQuery::create()
                ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                ->delete($con);
        }
        if (isset($this->currentTranslations[$locale])) {
            unset($this->currentTranslations[$locale]);
        }
        foreach ($this->collCustomerCategoryI18ns as $key => $translation) {
            if ($translation->getLocale() == $locale) {
                unset($this->collCustomerCategoryI18ns[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * Returns the current translation
     *
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildCustomerCategoryI18n */
    public function getCurrentTranslation(ConnectionInterface $con = null)
    {
        return $this->getTranslation($this->getLocale(), $con);
    }


        /**
         * Get the [title] column value.
         *
         * @return   string
         */
        public function getTitle()
        {
        return $this->getCurrentTranslation()->getTitle();
    }


        /**
         * Set the value of [title] column.
         *
         * @param      string $v new value
         * @return   \CustomerCategory\Model\CustomerCategoryI18n The current object (for fluent API support)
         */
        public function setTitle($v)
        {    $this->getCurrentTranslation()->setTitle($v);

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
