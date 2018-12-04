<?php
/**
 * yato db.php.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/12/3
 * Version: 1.0
 */

namespace core\lib;


use core\base\cException;

class db
{
    public $type = '';//数据库类型
    public $prefix = '';//表前缀
    public $dsn = '';//连接字符串
    public $logging = false;//是否记录执行sql
    public $dbname = '';//数据库名
    public $host = '';//数据库地址
    public $port = 3306;//用户库端口
    public $username = '';//数据库用户名
    public $password = '';//数据库密码
    public $charset = '';//数据库编码
    protected $_active = false;//是否初始化pdo
    protected $_pdo = '';//
    protected $_attributes = [];//pdo属性
    protected $_query = [];//sql属性
    protected $_params = [];//sql查询参数
    private $_statement;

    public function __construct($config)
    {
        $this->setAttr($config);
        switch ($this->type) {
            case 'mysql':
                if (empty($this->dsn)) {
                    $this->dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
                    if ($this->port)
                        $this->dsn .= ';port=' . $this->port;
                }
                break;
            default:
                break;
        }
    }

    /*
     *  开启或者关闭数据库连接
     */
    public function setActive($active)
    {
        if ($active) {
            $this->open();
        } else {
            $this->close();
        }
    }

    /**
     * 创建连接对象
     */
    public function createPdo()
    {
        $this->_pdo = new PDO($this->dsn, $this->username,
            $this->password, $this->_attributes);
    }

    /**
     * 打开连接
     */
    public function open()
    {
        if (empty($this->dsn)) {
            throw new cException('数据库连接字符串不能为空', __LINE__);
        }
        try {
            $this->createPdo();
            $this->_active = true;
        } catch (\PDOException $e) {
            throw new cException($e->getMessage(), __LINE__);
        }
    }

    /**
     * 关闭连接
     */
    public function close()
    {
        $this->_pdo = null;
        $this->_active = false;
    }

    /**
     * 设置属性
     * @param $attr
     */
    public function setAttr($attr)
    {
        foreach ($attr as $key => $val) {
            //
            if (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
    }

    /**
     * 连接数据库
     */
    public function connect()
    {
        $this->setActive(true);
        return $this;
    }

    /**
     * 设置查询字段
     * @param string $dbFiled
     */
    public function select($dbFiled = '*')
    {
        $this->_query['select'] = $dbFiled;
        return $this;
    }

    /*
     * 设置查询的from
     */
    public function from($table)
    {
        $this->_query['from'] = $this->prefix . $table;
        return $this;
    }

    /**
     * 设置查询的where条件
     * @param $conditions
     * @param array $param
     */
    public function where($conditions, $param = [])
    {
        $this->_query['where'] = $this->processCondition($conditions);
        foreach ($param as $key => $val) {
            $this->_params[$key] = $val;
        }
        return $this;
    }

    /** 设置group
     * @param $column
     */
    public function group($column)
    {
        $this->_query['group'] = $column;
        return $this;
    }

    /**设置limit
     * @param $offset
     * @param $limt
     */
    public function limit($limit, $offset = 0)
    {
        //
        $this->_query['limit'] = intval($limit);
        if ($offset)
            $this->offset($offset);
        return $this;
    }

    /**
     * 设置offset
     * @param $offset
     */
    public function offset($offset)
    {
        $this->_query['offset'] = intval($offset);
        return $this;
    }

    /**
     * 设置order
     * @param $column
     */
    public function order($column)
    {
        $this->_query['order'] = $column;
    }

    /**
     * 设置having
     * @param $column
     */
    public function having($column)
    {
        $this->_query['having'] = $column;
    }

    /**
     *
     * 获取一行数据
     */
    public function getOne()
    {
        return $this->query('fetch', PDO::FETCH_COLUMN);
    }

    /**
     * 获取一行数据
     * @return mixed
     */
    public function getRow()
    {
        return $this->query('fetch', PDO::FETCH_ASSOC);
    }

    /**
     * 获取所有数据
     * @return mixed
     */
    public function getAll()
    {
        return $this->query('fetchAll', PDO::FETCH_ASSOC);
    }

    /**
     * 查询
     * @param $method
     * @param $mode
     * @return mixed
     */
    public function query($method, $mode)
    {
        $this->prepare();
        $result = call_user_func_array(array($this->_statement, $method), [$mode]);
        $this->_statement->closeCursor();
        return $result;
    }

    /**
     * 预处理
     * @throws cException
     */
    public function prepare()
    {
        try {
            $sql = $this->buildSql();
            $this->_statement = $this->_pdo->prepare($sql);
            if ($this->_params) {
                $this->_statement->execute($this->_params);
            } else {
                $this->_statement->execute($this->_params);
            }
        } catch (Exception $e) {
            throw new cException('statement:' . $e->getMessage() . ',errorcode:' . $e->getCode(), __LINE__);
        }
    }

    public function execute()
    {

    }

    /**
     * 生成sql
     * @return string
     * @throws cException
     */
    private function buildSql()
    {
        //
        $query = $this->_query;
        $sql = 'SELECT';
        $sql .= ' ' . (isset($query['select']) ? $query['select'] : '*');

        if (isset($query['from']))
            $sql .= "\nFROM " . $query['from'];
        else
            throw new cException('The DB query must contain the "from" portion.');

//        if (isset($query['join']))
//            $sql .= "\n" . (is_array($query['join']) ? implode("\n", $query['join']) : $query['join']);

        if (isset($query['where']))
            $sql .= "\nWHERE " . $query['where'];

        if (isset($query['group']))
            $sql .= "\nGROUP BY " . $query['group'];

        if (isset($query['having']))
            $sql .= "\nHAVING " . $query['having'];

        if (isset($query['order']))
            $sql .= "\nORDER BY " . $query['order'];

        $limit = isset($query['limit']) ? (int)$query['limit'] : -1;
        $offset = isset($query['offset']) ? (int)$query['offset'] : -1;
        if ($limit >= 0 || $offset > 0) {
            switch ($this->type) {
                case 'mysql':
                    $sql .= " \nLIMIT " . $limit;
                    if ($offset > 0)
                        $sql .= "\nOFFSET " . $offset;
                    break;
                case 'oracle':
                case 'mssql':
                    $sql .= " \nOFFSET " . $limit;
                    if ($offset > 0)
                        $sql .= "\nROWS FETCH NEXT " . $offset . ' ROWS ONLY';
                    break;
                default:
                    break;
            }
        }
//        if (isset($query['union']))
//            $sql .= "\nUNION (\n" . (is_array($query['union']) ? implode("\n) UNION (\n", $query['union']) : $query['union']) . ')';
        return $sql;
    }

    /** 生成查询条件字符串
     * @param $conditions
     * @return string
     */
    public function processCondition($conditions)
    {
        if (is_string($conditions)) {
            return $conditions;
        } elseif (is_array($conditions) && $conditions) {
            $operator = strtoupper($conditions[0]);
            if (!($operator == 'AND' || $operator == 'OR')) {
                $operator = 'AND';
            }
            return implode($operator, $conditions[1]);
        } else {
            return '';
        }
    }
}