<?php

namespace Phpmng\Database;

use PDO;
use Exception;
use PDOException;
use Phpmng\URL\URL;
use Phpmng\File\File;
use Phpmng\Http\Request;

class Database {
    /**
     * Database instance : store object of this class after calling methods like select()
     */
    protected static $instance;

    /**
     * Database connection : contain the PDO connection so we use it to make queries & insert & ..
     */
    protected static $connection;

    /**
     * Select data : Contain the columns wanted to be selected
     *
     * @var array
     */
    protected static $select;

    /**
     * Table name
     *
     * @var string
     */
    protected static $table;

    /**
     * Join data
     *
     * @var string
     */
    protected static $join;

    /**
     * where data
     *
     * @var string
     */
    protected static $where;

    /**
     * Where binding : store parameters used in where to pass it in PDO execute for security reasons
     *
     * @var array
     */
    protected static $where_binding = [];

    /**
     * Group by data
     *
     * @var string
     */
    protected static $group_by;

    /**
     * having data
     *
     * @var string
     */
    protected static $having;

    /**
     * having binding
     *
     * @var array
     */
    protected static $having_binding = [];

    /**
     * Order by data
     *
     * @var string
     */
    protected static $order_by;

    /**
     * limit
     *
     * @var string
     */
    protected static $limit;

    /**
     * Offset
     *
     * @var string
     */
    protected static $offset;

    /**
     * query
     *
     * @var string
     */
    protected static $query;

    /**
     * Setter
     *
     * @var string
     */
    protected static $setter;

    /**
     * all binding
     *
     * @var array
     */
    protected static $binding = []; // Merge (where, having) binding

    /**
     * Database constructor
     */
    public function __construct($table) {
        static::$table = $table;
    }

    /**
     * Connect to database
     */
    private static function connect() {
        // If there's No connection
        if (! static::$connection) {
            $database_data = File::require_file('config/database.php');
            extract($database_data);
            $dsn = 'mysql:dbname='.$database.';host='.$host.'';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_PERSISTENT => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "set NAMES " . $charset . " COLLATE " . $collation,
            ];
            try {
                static::$connection = new PDO($dsn, $username, $password, $options);
            }
            catch (PDOException $e) {
                throw new Exception($e->getMessage());
            }
        }
    }

    /**
     * Get the instance of the class : Used in methods to allow like : DB::select()->join()->orderBy()->get()
     */
    private static function instance() {
        static::connect(); // Connect to DB IF not connected
        $table = static::$table;
        if (! self::$instance) {
            self::$instance = new Database($table);
        }

        return self::$instance;
    }

    /**
     * query function
     *
     * @param string $query
     * @return string
     */
    public static function query($query = null) {
        static::instance();
        if ($query == null) {
            if (! static::$table) {
                throw new Exception("Unknown table");
            }
            $query = "SELECT ";
            $query .= static::$select ?: '*';
            $query .= " FROM " . static::$table . " ";
            $query .= static::$join . " ";
            $query .= static::$where . " ";
            $query .= static::$group_by . " ";
            $query .= static::$having . " ";
            $query .= static::$order_by . " ";
            $query .= static::$limit . " ";
            $query .= static::$offset . " ";
            rtrim(static::$query, ' ');
        }

        static::$query = $query;
        static::$binding = array_merge(static::$where_binding, static::$having_binding);

        return static::$instance;
    }

    /**
     * Select Columns from table
     *
     * @return object $instance
     */
    public static function select() {
        $select = func_get_args(); // Used to get arguments with any number of it
        $select = implode(', ', $select);
        

        static::$select = $select;

        return static::instance();
    }

    /**
     * Define table
     * @param string $table
     *
     * @return object $instance
     */
    public static function table($table) {
        static::$table = $table;

        return static::instance();
    }

    /**
     * Join table
     *
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @param string $type
     *
     * @return object $type
     */
    public static function join($table, $first, $operator, $second, $type = "INNER") {
        static::$join .= " " . $type . " JOIN " . $table . " ON " . $first . $operator . $second . " ";

        return static::instance();
    }

    /**
     * Right Join table
     *
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     *
     * @return object $type
     */
    public static function rightJoin($table, $first, $operator, $second) {
        static::join($table, $first, $operator, $second, "RIGHT");

        return static::instance();
    }

    /**
     * Right Join table
     *
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     *
     * @return object $type
     */
    public static function leftJoin($table, $first, $operator, $second) {
        static::join($table, $first, $operator, $second, "LEFT");

        return static::instance();
    }

    /**
     * Where data
     *
     * @param string $column
     * @param string $operator
     * @param string $value
     * @param string $type
     *
     * @return object $instance
     */
    public static function where($column, $operator, $value, $type = null) {
        $where = '`' . $column . '`' . $operator . ' ? ';
        if (! static::$where) {
            $statement = " WHERE " . $where;
        } else {
            if ($type == null) {
                $statement = " AND " . $where;
            } else {
                $statement = " " . $type . " " . $where;
            }
        }

        static::$where .= $statement;
        static::$where_binding[] = htmlspecialchars($value);

        return static::instance();
    }

    /**
     * Or where
     *
     * @param string $column
     * @param string $operator
     * @param string $value
     *
     * @return object $value
     */
    public static function orWhere($column, $operator, $value) {
        static::where($column, $operator, $value, "OR");

        return static::instance();
    }

    /**
     * Group by
     *
     * @return object $instance
     */
    public static function groupBy() {
        $group_by = func_get_args();
        $group_by = "GROUP BY " . implode(', ', $group_by) . " ";

        static::$group_by = $group_by;

        return static::instance();
    }

    /**
     * having data
     *
     * @param string $column
     * @param string $operator
     * @param string $value
     *
     * @return object $instance
     */
    public static function having($column, $operator, $value) {
        $having = '`' . $column . '`' . $operator . ' ? ';
        if (! static::$where) {
            $statement = " HAVING " . $having;
        } else {
            $statement = " AND " . $having;
        }

        static::$having .= $statement;
        static::$having_binding[] = htmlspecialchars($value);

        return static::instance();
    }

    /**
     * Order by
     *
     * @param string $column
     * @param string $type
     *
     * @return object $instance
     */
    public static function orderBy($column, $type = null) {
        $sep = static::$order_by ? ", " : " ORDER BY ";
        $type = strtoupper($type);
        $type = ($type != null && in_array($type, ['ASC', 'DESC'])) ? $type : "ASC";
        $statement = $sep . $column . " " . $type . " ";

        static::$order_by .= $statement;

        return static::instance();
    }

    /**
     * Limit
     *
     * @param string $limit
     *
     * @return object $instance
     */
    public static function limit($limit) {
        static::$limit = "LIMIT " . $limit . " ";

        return static::instance();
    }

    /**
     * Offset
     *
     * @param string $offset
     *
     * @return object $instance
     */
    public static function offset($offset) {
        static::$offset = "OFFSET " . $offset . " ";

        return static::instance();
    }

    /**
     * Fecth The query string & Execute it
     *
     * @return object $data
     */
    private static function fetchExecute() {
        static::query();
        $query = static::$query;
        $data = static::$connection->prepare($query);
        $data->execute(static::$binding);

        static::clear();

        return $data;
    }

    /**
     * Get records
     *
     * @return object $result
     */
    public static function get() {
        $data = static::fetchExecute();
        $result = $data->fetchAll();

        return $result;
    }

    /**
     * Get record
     *
     * @return object $result
     */
    public static function first() {
        $data = static::fetchExecute(); // NEED EDIT , put limit 1 instead
        $result = $data->fetch();

        return $result;
    }

    /**
     * Execute : used in insert method or in update or in delete method
     *
     * @param array $data
     * @param string $query
     * @param bool $where : check if there's where in update like : 
     * Database::table('users')->where('id',1)->update($data);
     *
     * @return void
     */
    private static function execute(Array $data, $query, $where = null) {
        //static::instance();
        if (! static::$table) {
            throw new Exception("Unknow table");
        }

        foreach($data as $key => $value) {
            static::$setter .= '`' . $key . '` = ?, ';
            static::$binding[] = filter_var($value, FILTER_SANITIZE_STRING);
        }
        static::$setter = rtrim(static::$setter, ', ');

        $query .= static::$setter;
        $query .= $where != null ? static::$where . " " : '';

        static::$binding = $where != null ? array_merge(static::$binding, static::$where_binding) : static::$binding;

        $data = static::$connection->prepare($query);
        $data->execute(static::$binding);

        static::clear();
    }

    /**
     * Insert to table
     *
     * @param array $data
     *
     * @return object
     */
    public static function insert($data) {
        $table = static::$table;
        $query = "INSERT INTO " . $table . " SET ";
        static::execute($data, $query);

        $object_id = static::$connection->lastInsertId();
        $object = static::table($table)->where('id', '=', $object_id)->first();

        return $object;
    }

    /**
     * Update record on table
     *
     * @param array $data
     *
     * @return bool
     */
    public static function update($data) {
        $query = "UPDATE " . static::$table . " SET ";
        static::execute($data, $query, true);

        return true;
    }

    /**
     * Delete record on table
     *
     * @return bool
     */
    public static function delete() {
        $query = "DELETE FROM " . static::$table . " ";
        static::execute([], $query, true);

        return true;
    }

    /**
     * Pagination
     *
     * @return mixed $result
     */
    public static function paginate($items_per_page = 15) {
        # 1. give value to static::query
        static::query();
        $query = static::$query;
        $data = static::$connection->prepare($query);
        $data->execute(static::$binding);
        static::clear();


        # 2. Get Number of pages and Current Page
        $pages = ceil($data->rowCount() / $items_per_page); // Number of pages
        $page = Request::get('page'); // EX: ?page=1
        $current_page = (! is_numeric($page) || Request::get('page') < 1) ? "1" : $page;

        # 3. Get offset of item that we will start show items from it
        $offset = ($current_page - 1) * $items_per_page; // start from 0, we need to increase it by the number of items per page


        # 4. Get the data & show just the data for required page
        $result = $data->fetchAll();
        $result = array_slice($result, $offset, $items_per_page);
        $response = ['data' => $result, 'items_per_page' => $items_per_page, 'pages' => $pages, 'current_page' => $current_page];

        return $response;
    }


    /**
     * Show links of page & next 2 pages & previous two pages
     * i will try in the logic below to show 5 links as much as possible
     * 
     * @param number_of_links : Should be odd, in case of 5 we will have current_link then 2 after and 2 before it
     * if there's no 2 after we will increase the links shown before to reach to 5 links if possible
     * 
     *
     * @param int $current_page
     * @param int $pages
     * @param int $number_of_links : except the arrows shown with links
     * @return string $result
     */
    public static function links($current_page, $pages, $number_of_links){
        $html = '';
        
        $half = ($number_of_links - 1) / 2;
        $from = 0;
        $to = 0;

        // Get the value of From (the first page shown in the links)
        for($from = $current_page - $half; $from <= $current_page; $from++){
            if($from > 0)
                break;
        }

        // Get the value of To (the last page shown in the links)
        for($to = $from + $number_of_links - 1; $to>=$current_page; $to--){
            if($to <= $pages)
            break; //
        }

        // If to - from < number_of_pages - 1 : decrease the value of from as possible to reach number_of_pages
        while( (($to - $from) < ($number_of_links -1) ) && $from > 1 ){
            $from--;
        }



       // return "from = " . $from . " To " . $to;

        if($pages > 1 && $current_page <= $pages && $current_page > 0){
            $html .= "<ul class='pagination'>";
            // Get Full link
            $full_link = URL::path(Request::fullURL());
            
            // Replace like : ( ?page=4 ) or (&page=4) with empty string to add it with our data
            $full_link = preg_replace('#\?page=(.*)#', '', $full_link);
            $full_link = preg_replace('#\&page=(.*)#', '', $full_link);

            # Show arrow of first page
            $active_class = $current_page == 1 ? 'active' : '';
            $href = strpos($full_link, '?') ? ($full_link.'&page=1') : ($full_link.'?page=1');
            $html .= "<li class='link $active_class'><a href='$href'>&#8594;</a></li>";
            

            # Show the rest of pages
            for($i = $from; $i<= $to; $i++) {
                $active_class = $current_page == $i ? 'active' : '';
                $href = strpos($full_link, '?') ? ($full_link . '&page=' . $i) : ($full_link . '?page='.$i);
                $html .= "<li class='link $active_class'><a href='$href'>$i</a></li>";
            }

            # Show arrow of Last page
                $active_class = $current_page == 1 ? 'active' : '';
                $href = strpos($full_link, '?') ? ($full_link."&page=$pages") : ($full_link."?page=$pages");
                $html .= "<li class='link $active_class'><a href='$href'>&#8592;</a></li>";


            
        }
        return $html;
       
    }

    /**
     * Clear the properities
     *
     * @return void
     */
    private static function clear() {
        static::$select = '';
        static::$join = '';
        static::$where = '';
        static::$where_binding = [];
        static::$group_by = '';
        static::$having = '';
        static::$having_binding = [];
        static::$order_by = '';
        static::$limit = '';
        static::$offset = '';
        static::$query = '';
        static::$binding = [];
        static::$instance = '';
    }

    /**
     * Get Query : used for test purpose
     */
    public static function getQuery(){
        static::query();
       // return static::$select;
        return static::$query;
    }
}