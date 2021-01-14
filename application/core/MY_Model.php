<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    /*
    * Copyright (C) 2014 @avenirer [avenir.ro@gmail.com]
    * Everyone is permitted to copy and distribute verbatim or modified copies of this license document,
    * and changing it is allowed as long as the name is changed.
    * DON'T BE A DICK PUBLIC LICENSE TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
    *
    ***** Do whatever you like with the original work, just don't be a dick.
    ***** Being a dick includes - but is not limited to - the following instances:
    ********* 1a. Outright copyright infringement - Don't just copy this and change the name.
    ********* 1b. Selling the unmodified original with no work done what-so-ever, that's REALLY being a dick.
    ********* 1c. Modifying the original work to contain hidden harmful content. That would make you a PROPER dick.
    ***** If you become rich through modifications, related works/services, or supporting the original work, share the love. Only a dick would make loads off this work and not buy the original works creator(s) a pint.
    ***** Code is provided with no warranty.
    *********** Using somebody else's code and bitching when it goes wrong makes you a DONKEY dick.
    *********** Fix the problem yourself. A non-dick would submit the fix back.
     *
     */

    /** how to extend MY_Model:
     *    class User_model extends MY_Model
     *    {
     *      public $table = 'users'; // Set the name of the table for this model.
     *      public $primary_key = 'id'; // Set the primary key
     *      public $fillable = array(); // You can set an array with the fields that can be filled by insert/update
     *      public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by
     *      insert/update public function __construct()
     *        {
     *          $this->_database_connection  = group_name or array() | OPTIONAL
     *              Sets the connection preferences (group name) set up in the database.php. If not trset, it will use
     *              the
     *              'default' (the $active_group) database connection.
     *          $this->timestamps = TRUE | array('create' => 'create_at','update' => 'update_at','delete' =>
     *          'remove_at') If set to TRUE tells MY_Model that the table has 'created_at','updated_at' (and
     *          'deleted_at' if $this->soft_delete is set to TRUE) If given an array as parameter, it tells MY_Model,
     *          that the first element is a created_at field type, the second element is a updated_at field type (and
     *          the third element is a deleted_at field type)
     *          $this->soft_deletes = FALSE;
     *              Enables (TRUE) or disables (FALSE) the "soft delete" on records. Default is FALSE
     *          $this->timestamps_format = 'Y-m-d H:i:s'
     *              You can at any time change the way the timestamp is created (the default is the MySQL standard
     *              datetime format) by modifying this variable. You can choose between whatever format is acceptable
     *              by the php function date() (default is 'Y-m-d H:i:s'), or 'timestamp' (UNIX timestamp)
     *          $this->return_as = 'object' | 'array'
     *              Allows the model to return the results as object or as array
     *          $this->has_one['phone'] = 'Phone_model' or $this->has_one['phone'] =
     *          array('Phone_model','foreign_key','local_key');
     *          $this->has_one['address'] = 'Address_model' or $this->has_one['address'] =
     *          array('Address_model','foreign_key','another_local_key'); Allows establishing ONE TO ONE or more ONE TO
     *          ONE relationship(s) between models/tables
     *          $this->has_many['posts'] = 'Post_model' or $this->has_many['posts'] =
     *          array('Posts_model','foreign_key','another_local_key'); Allows establishing ONE TO MANY or more ONE TO
     *          MANY relationship(s) between models/tables
     *          $this->has_many_pivot['posts'] = 'Post_model' or $this->has_many_pivot['posts'] =
     *          array('Posts_model','foreign_primary_key','local_primary_key'); Allows establishing MANY TO MANY or
     *          more MANY TO MANY relationship(s) between models/tables with the use of a PIVOT TABLE
     *              !ATTENTION: The pivot table name must be composed of the two table names separated by "_" the table
     *              names having to to be alphabetically ordered (NOT users_posts, but posts_users). Also the pivot
     *              table must contain as identifying columns the columns named by convention as follows:
     *              table_name_singular + _ + foreign_table_primary_key. For example: considering that a post can have
     *              multiple authors, a pivot table that connects two tables (users and posts) must be named
     *              posts_users and must have post_id and user_id as identifying columns for the posts.id and users.id
     *              tables.
     *          $this->cache_driver = 'file'
     *          $this->cache_prefix = 'mm'
     *              If you know you will do some caching of results without the native caching solution, you can at any
     *              time use the MY_Model's caching. By default, MY_Model uses the files to cache result. If you want
     *              to change the way it stores the cache, you can change the $cache_driver property to whatever
     *              CodeIgniter cache driver you want to use. Also, with $cache_prefix, you can prefix the name of the
     *              caches. by default any cache made by MY_Model starts with 'mm' + _ + "name chosen for cache"
     *          $this->delete_cache_on_save = FALSE
     *              If you use caching often and you don't want to be forced to delete cache manually, you can enable
     *              $this->delete_cache_on_save by setting it to TRUE. If set to TRUE the model will auto-delete all
     *              cache related to the model's table whenever you write/update/delete data from that table.
     *          $this->pagination_delimiters = array('<span>','</span>');
     *              If you know you will use the paginate() method, you can change the delimiters between the pages
     *              links
     *          $this->pagination_arrows = array('&lt;','&gt;');
     *              You can also change the way the previous and next arrows look like.
     *
     *
     *            parent::__construct();
     *        }
     *    }
     *
     **/
    class MY_Model extends CI_Model
    {

        /**
         * Select the database connection from the group names defined inside the database.php configuration file or an
         * array.
         */
        protected $_database_connection = NULL;

        /** @var
         * This one will hold the database connection object
         */
        protected $_database;

        /** @var null
         * Sets table name
         */
        public $table = NULL;

        /**
         * @var null
         * Sets PRIMARY KEY
         */
        public $primary_key = 'id';

        /**
         * @var null|array
         * Sets fillable fields.
         * If value is set as null, the $fillable property will be set as an array with all the table fields (except
         * the primary key) as elements. If value is set as an array, there won't be any changes done to it (ie: no
         * field of the table will be updated or inserted).
         */
        public $fillable = NULL;

        /**
         * @var null|array
         * Sets protected fields.
         * If value is set as null, the $protected will be set as an array with the primary key as single element.
         * If value is set as an array, there won't be any changes done to it (if set as empty array, the primary key
         * won't be inserted here).
         */
        public $protected = NULL;


        /** @var bool | array
         * Enables created_at and updated_at fields
         */
        protected $timestamps        = TRUE;
        protected $timestamps_format = 'Y-m-d H:i:s';

        protected $_created_at_field;
        protected $_updated_at_field;
        protected $_deleted_at_field;

        /** @var bool
         * Enables soft_deletes
         */
        protected $soft_deletes = FALSE;

        /**
         * 缓存数据
         * 此缓存与查询缓存不同，
         *
         */
        protected $data_cache = FALSE;

        /** relationships variables */
        private $_relationships      = [];
        public  $has_one             = [];
        public  $has_many            = [];
        public  $has_many_pivot      = [];
        public  $separate_subqueries = TRUE;
        private $_requested          = [];
        /** end relationships variables */

        /*caching*/
        public    $cache_driver         = 'file';
        public    $cache_prefix         = 'mm';
        protected $_cache               = [];
        public    $delete_cache_on_save = FALSE;

        /*pagination*/
        public $next_page;
        public $previous_page;
        public $all_pages;
        public $pagination_delimiters;
        public $pagination_arrows;

        /* validation */
        private $validated            = TRUE;
        private $row_fields_to_update = [];


        /**
         * The various callbacks available to the model. Each are
         * simple lists of method names (methods will be run on $this).
         */
        protected $before_create      = [];
        protected $after_create       = [];
        protected $before_update      = [];
        protected $after_update       = [];
        protected $before_get         = [];
        protected $after_get          = [];
        protected $before_delete      = [];
        protected $after_delete       = [];
        protected $before_soft_delete = [];
        protected $after_soft_delete  = [];

        protected $callback_parameters = [];

        protected $return_as          = 'object';
        protected $return_as_dropdown = NULL;
        protected $_dropdown_field    = '';

        private $_trashed = 'without';

        private $_select = '*';

        public $is_convert = true;

        public function __construct()
        {
            parent::__construct();
            $this->_set_connection();
            $this->_set_timestamps();
            $this->_fetch_table();
            $this->pagination_delimiters = (isset($this->pagination_delimiters)) ? $this->pagination_delimiters : ['<span>', '</span>'];
            $this->pagination_arrows     = (isset($this->pagination_arrows)) ? $this->pagination_arrows : ['&lt;', '&gt;'];
            /* These below are implementation examples for before_create and before_update triggers.
            Their respective functions - add_creator() and add_updater() - can be found at the end of the model.
            They add user id on create and update. If you comment this out don't forget to do the same for the methods()
            $this->before_create[]='add_creator';
            $this->before_update[]='add_updater';
            */
        }

        /*
         * public function _get_rules($action=NULL)
         * This function returns the rules. If action is given and rules are
         * stored in an associative array, only the rules for this action are
         * returned, all otherwise.
         * This should be used by any method utilizing the rules.
         */
        public function _get_rules($action = NULL)
        {
            if (isset($action) && $this->is_assoc($this->rules)) {
                return $this->rules[$action];
            }
            return $this->rules;
        }


        public function _prep_before_write($data)
        {

            // Let's make sure we receive an array...
            $data_as_array = (is_object($data)) ? (array)$data : $data;

            $new_data = [];
            $multi    = $this->is_multidimensional($data);
            if ($multi === FALSE) {
                foreach ($data_as_array as $field => $value) {
                    if (in_array($field, $this->fillable)) {
                        $new_data[$field] = $value;
                    } else {
                        show_error('MY_Model: Unknown column (' . $field . ') in table: (' . $this->table . ').');
                    }
                }
            } else {
                foreach ($data_as_array as $key => $row) {
                    foreach ($row as $field => $value) {
                        if (in_array($field, $this->fillable)) {
                            $new_data[$key][$field] = $value;
                        } else {
                            show_error('MY_Model: Unknown column ' . $field . ' in table: ' . $this->table);
                        }
                    }
                }
            }
            return $new_data;
        }

        /*
         * public function _prep_after_write()
         * this function simply deletes the cache related to the model's table if $this->delete_cache_on_save is set to TRUE
         * It should be called by any "save" method
         */
        public function _prep_after_write()
        {
            if ($this->delete_cache_on_save === TRUE) {
                $this->delete_cache('*');
            }
            return TRUE;
        }

        public function _prep_before_read()
        {

        }

        public function _prep_after_read($data, $multi = TRUE)
        {
            // let's join the subqueries...
            $data = $this->join_temporary_results($data);
            $this->_database->reset_query();
            $this->_requested = [];
            if (isset($this->return_as_dropdown) && $this->return_as_dropdown == 'dropdown') {
                foreach ($data as $row) {
                    $dropdown[$row[$this->primary_key]] = $row[$this->_dropdown_field];
                }
                $data                     = $dropdown;
                $this->return_as_dropdown = NULL;
            } else if ($this->return_as == 'object') {
                $data = $this->array_to_object($data);
            }
            if (isset($this->_select)) {
                $this->_select = '*';
            }
            return $data;
        }

        /**
         * public function from_form($rules = NULL,$additional_values = array(), $row_fields_to_update = array())
         * Gets data from form, after validating it and waits for an insert() or update() method in the query chain
         * @param null  $rules                Gets the validation rules. If nothing is passed (NULL), will look for the
         *                                    validation rules inside the model $rules public property
         * @param array $additional_values    Accepts additional fields to be filled, fields that are not to be found
         *                                    inside the form. The values are inserted as an array with "field_name" =>
         *                                    "field_value"
         * @param array $row_fields_to_update You can mention the fields from the form that can be used to identify
         *                                    the row when doing an update
         * @return $this
         */
        public function from_form($rules = NULL, $additional_values = NULL, $row_fields_to_update = [])
        {
            $this->load->library('form_validation');
            if (!isset($rules)) {
                if (empty($row_fields_to_update)) {
                    $rules = $this->_get_rules('insert');
                } else {
                    $rules = $this->_get_rules('update');
                }
            }
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run()) {
                $this->validated = [];
                foreach ($rules as $rule) {
                    if (in_array($rule['field'], $this->fillable)) {
                        $this->validated[$rule['field']] = $this->input->post($rule['field']);
                    }
                }
                if (isset($additional_values) && is_array($additional_values) && !empty($additional_values)) {
                    foreach ($additional_values as $field => $value) {
                        if (in_array($field, $this->fillable)) {
                            $this->validated[$field] = $value;
                        }
                    }
                }

                if (!empty($row_fields_to_update)) {
                    foreach ($row_fields_to_update as $key => $field) {
                        if (in_array($field, $this->fillable)) {
                            $this->row_fields_to_update[$field] = $this->input->post($field);
                        } else if (in_array($key, $this->fillable)) {
                            $this->row_fields_to_update[$key] = $field;
                        } else {
                            continue;
                        }
                    }
                }
                return $this;
            } else {
                $this->validated = FALSE;
                return $this;
            }

        }

        /**
         * public function insert($data)
         * Inserts data into table. Can receive an array or a multidimensional array depending on what kind of insert
         * we're talking about.
         * @param $data
         * @return int/array Returns id/ids of inserted rows
         */
        public function insert($data = NULL)
        {
            if (!isset($data) && $this->validated != FALSE) {
                $data            = $this->validated;
                $this->validated = FALSE;
            } else if (!isset($data)) {
                return FALSE;
            }
            $data = $this->_prep_before_write($data);

            //now let's see if the array is a multidimensional one (multiple rows insert)
            $multi = $this->is_multidimensional($data);

            // if the array is not a multidimensional one...
            if ($multi === FALSE) {
                if ($this->timestamps !== FALSE && $this->_created_at_field) {
                    $data[$this->_created_at_field] = $this->_the_timestamp();
                }
                $data = $this->trigger('before_create', $data);
                if ($this->_database->insert($this->table, $data)) {
                    $this->_prep_after_write();
                    $id     = $this->_database->insert_id();
                    $return = $this->trigger('after_create', $id);

                    /**
                     * 如果设置data_cahe就缓存数据用主键作为键
                     */
                    if ($this->data_cache) {
                        $this->create_cache();
                    }

                    return $return;
                }
                return FALSE;
            } // else...
            else {
                $return = [];
                foreach ($data as $row) {
                    if ($this->timestamps !== FALSE && $this->_created_at_field) {
                        $row[$this->_created_at_field] = $this->_the_timestamp();
                    }
                    $row = $this->trigger('before_create', $row);
                    if ($this->_database->insert($this->table, $row)) {
                        $return[] = $this->_database->insert_id();
                    }
                }
                $this->_prep_after_write();
                $after_create = [];
                foreach ($return as $id) {
                    $after_create[] = $this->trigger('after_create', $id);
                }

                /**
                 * 如果设置data_cahe就缓存数据用主键作为键
                 */
                if ($this->data_cache) {
                    $this->create_cache();
                }

                return $after_create;
            }
            return FALSE;
        }

        /*
         * public function is_multidimensional($array)
         * Verifies if an array is multidimensional or not;
         * @param array $array
         * @return bool return TRUE if the array is a multidimensional one
         */
        public function is_multidimensional($array)
        {
            if (is_array($array)) {
                foreach ($array as $element) {
                    if (is_array($element)) {
                        return TRUE;
                    }
                }
            }
            return FALSE;
        }


        /**
         * public function update($data)
         * Updates data into table. Can receive an array or a multidimensional array depending on what kind of update
         * we're talking about.
         * @param array     $data
         * @param array|int $column_name_where
         * @param bool      $escape should the values be escaped or not - defaults to true
         * @return str/array Returns id/ids of inserted rows
         */
        public function update($data = NULL, $column_name_where = NULL, $escape = TRUE)
        {
            if (!isset($data) && $this->validated != FALSE) {
                $data            = $this->validated;
                $this->validated = FALSE;
            } else if (!isset($data)) {
                $this->_database->reset_query();
                return FALSE;
            }
            // Prepare the data...
            $data = $this->_prep_before_write($data);

            //now let's see if the array is a multidimensional one (multiple rows insert)
            $multi = $this->is_multidimensional($data);

            // if the array is not a multidimensional one...
            if ($multi === FALSE) {
                if ($this->timestamps !== FALSE && $this->_updated_at_field) {
                    $data[$this->_updated_at_field] = $this->_the_timestamp();
                }
                $data = $this->trigger('before_update', $data);
                if ($this->validated === FALSE && count($this->row_fields_to_update)) {
                    $this->where($this->row_fields_to_update);
                    $this->row_fields_to_update = [];
                }
                if (isset($column_name_where)) {
                    if (is_array($column_name_where)) {
                        $this->where($column_name_where);
                    } else if (is_numeric($column_name_where)) {
                        $this->_database->where($this->primary_key, $column_name_where);
                    } else {
                        $column_value = (is_object($data)) ? $data->{$column_name_where} : $data[$column_name_where];
                        $this->_database->where($column_name_where, $column_value);
                    }
                }
                if ($escape) {
                    if ($this->_database->update($this->table, $data)) {
                        $this->_prep_after_write();
                        $affected = $this->_database->affected_rows();
                        $return   = $this->trigger('after_update', $affected);

                        /**
                         * 如果设置data_cahe就缓存数据用主键作为键
                         */
                        if ($this->data_cache) {
                            $this->create_cache();
                        }

                        return $return;
                    }
                } else {
                    if ($this->_database->set($data, NULL, FALSE)->update($this->table)) {

                        $this->_prep_after_write();
                        $affected = $this->_database->affected_rows();
                        $return   = $this->trigger('after_update', $affected);
                        /**
                         * 如果设置data_cahe就缓存数据用主键作为键
                         */
                        if ($this->data_cache) {
                            $this->create_cache();
                        }

                        return $return;
                    }
                }
                return FALSE;
            } // else...
            else {
                $rows = 0;
                foreach ($data as $row) {
                    if ($this->timestamps !== FALSE && $this->_updated_at_field) {
                        $row[$this->_updated_at_field] = $this->_the_timestamp();
                    }
                    $row = $this->trigger('before_update', $row);
                    if (is_array($column_name_where)) {
                        $this->_database->where($column_name_where[0], $column_name_where[1]);
                    } else {
                        $column_value = (is_object($row)) ? $row->{$column_name_where} : $row[$column_name_where];
                        $this->_database->where($column_name_where, $column_value);
                    }
                    if ($escape) {
                        if ($this->_database->update($this->table, $row)) {
                            $rows++;
                        }
                    } else {
                        if ($this->_database->set($row, NULL, FALSE)->update($this->table)) {
                            $rows++;
                        }
                    }
                }
                $affected = $rows;
                $this->_prep_after_write();
                $return = $this->trigger('after_update', $affected);

                /**
                 * 如果设置data_cahe就缓存数据用主键作为键
                 */
                if ($this->data_cache) {
                    $this->create_cache();
                }

                return $return;
            }
            return FALSE;
        }

        /**
         * public function where($field_or_array = NULL, $operator_or_value = NULL, $value = NULL, $with_or = FALSE,
         * $with_not = FALSE, $custom_string = FALSE) Sets a where method for the $this object
         * @param null $field_or_array    - can receive a field name or an array with more wheres...
         * @param null $operator_or_value - can receive a database operator or, if it has a field, the value to equal
         *                                with
         * @param null $value             - a value if it received a field name and an operator
         * @param bool $with_or           - if set to true will create a or_where query type pr a or_like query type,
         *                                depending on the operator
         * @param bool $with_not          - if set to true will also add "NOT" in the where
         * @param bool $custom_string     - if set to true, will simply assume that $field_or_array is actually a
         *                                string and pass it to the where query
         * @return $this
         */
        public function where($field_or_array = NULL, $operator_or_value = NULL, $value = NULL, $with_or = FALSE, $with_not = FALSE, $custom_string = FALSE)
        {

            if (!$field_or_array) {
                return $this;
            };
            if (is_array($field_or_array)) {
                $multi = $this->is_multidimensional($field_or_array);
                if ($multi === TRUE) {
                    foreach ($field_or_array as $where) {
                        $field             = $where[0];
                        $operator_or_value = isset($where[1]) ? $where[1] : NULL;
                        $value             = isset($where[2]) ? $where[2] : NULL;
                        $with_or           = (isset($where[3])) ? TRUE : FALSE;
                        $with_not          = (isset($where[4])) ? TRUE : FALSE;
                        $this->where($field, $operator_or_value, $value, $with_or, $with_not);
                    }
                    return $this;
                }
            }

            if ($with_or === TRUE) {
                $where_or = 'or_where';
            } else {
                $where_or = 'where';
            }

            if ($with_not === TRUE) {
                $not = '_not';
            } else {
                $not = '';
            }

            if ($custom_string === TRUE) {
                $this->_database->{$where_or}($field_or_array, NULL, FALSE);
            } else if (is_numeric($field_or_array)) {
                $this->_database->{$where_or}([$this->table . '.' . $this->primary_key => $field_or_array]);
            } else if (is_array($field_or_array) && !isset($operator_or_value)) {
                $this->_database->where($field_or_array);
            } else if (!isset($value) && isset($field_or_array) && isset($operator_or_value) && !is_array($operator_or_value)) {
                $this->_database->{$where_or}([$this->table . '.' . $field_or_array => $operator_or_value]);
            } else if (!isset($value) && isset($field_or_array) && isset($operator_or_value) && is_array($operator_or_value) && !is_array($field_or_array)) {
                //echo $field_or_array;
                //exit;
                $this->_database->{$where_or . $not . '_in'}($this->table . '.' . $field_or_array, $operator_or_value);
            } else if (isset($field_or_array) && isset($operator_or_value) && isset($value)) {
                if (strtolower($operator_or_value) == 'like') {
                    if ($with_not === TRUE) {
                        $like = 'not_like';
                    } else {
                        $like = 'like';
                    }
                    if ($with_or === TRUE) {
                        $like = 'or_' . $like;
                    }

                    $this->_database->{$like}($field_or_array, $value);
                } else {
                    $this->_database->{$where_or}($field_or_array . ' ' . $operator_or_value, $value);
                }

            }
            return $this;
        }


        public function where_str($where_str = NULL)
        {
            if ($where_str) {
                $this->where()
            }
        }
        /**
         * public function limit($limit, $offset = 0)
         * Sets a rows limit to the query
         * @param     $limit
         * @param int $offset
         * @return $this
         */
        public function limit($limit, $offset = 0)
        {
            $this->_database->limit($limit, $offset);
            return $this;
        }

        /**
         * public function group_by($grouping_by)
         * A wrapper to $this->_database->group_by()
         * @param $grouping_by
         * @return $this
         */
        public function group_by($grouping_by)
        {
            $this->_database->group_by($grouping_by);
            return $this;
        }

        /**
         * public function delete($where)
         * Deletes data from table.
         * @param $where primary_key(s) Can receive the primary key value or a list of primary keys as array()
         * @return Returns affected rows or false on failure
         */
        public function delete($where = NULL)
        {
            if (!empty($this->before_delete) || !empty($this->before_soft_delete) || !empty($this->after_delete) || !empty($this->after_soft_delete) || ($this->soft_deletes === TRUE)) {
                $to_update = [];
                if (isset($where)) {
                    $this->where($where);
                }
                $query = $this->_database->get($this->table);
                foreach ($query->result() as $row) {
                    $to_update[] = [$this->primary_key => $row->{$this->primary_key}];
                }
                if (!empty($this->before_soft_delete)) {
                    foreach ($to_update as &$row) {
                        $row = $this->trigger('before_soft_delete', $row);
                    }
                }
                if (!empty($this->before_delete)) {
                    foreach ($to_update as &$row) {
                        $row = $this->trigger('before_delete', $row);
                    }
                }
            }
            if (isset($where)) {
                $this->where($where);
            }
            $affected_rows = 0;
            if ($this->soft_deletes === TRUE) {
                if (isset($to_update) && count($to_update) > 0) {

                    foreach ($to_update as &$row) {
                        //$row = $this->trigger('before_soft_delete',$row);
                        $row[$this->_deleted_at_field] = $this->_the_timestamp();
                    }
                    $affected_rows              = $this->_database->update_batch($this->table, $to_update, $this->primary_key);
                    $to_update['affected_rows'] = $affected_rows;
                    $this->_prep_after_write();
                    $this->trigger('after_soft_delete', $to_update);
                }

                /**
                 * 如果设置data_cahe就缓存数据用主键作为键
                 */
                if ($this->data_cache) {
                    $this->create_cache();
                }
                return $affected_rows;
            } else {
                if ($this->_database->delete($this->table)) {
                    $affected_rows = $this->_database->affected_rows();
                    if (!empty($this->after_delete)) {
                        $to_update['affected_rows'] = $affected_rows;
                        $to_update                  = $this->trigger('after_delete', $to_update);
                        $affected_rows              = $to_update;
                    }
                    $this->_prep_after_write();

                    /**
                     * 如果设置data_cahe就缓存数据用主键作为键
                     */
                    if ($this->data_cache) {
                        $this->create_cache();
                    }

                    return $affected_rows;
                }
            }
            return FALSE;
        }

        /**
         * public function force_delete($where = NULL)
         * Forces the delete of a row if soft_deletes is enabled
         * @param null $where
         * @return bool
         */
        public function force_delete($where = NULL)
        {
            if (isset($where)) {
                $this->where($where);
            }
            if ($this->_database->delete($this->table)) {
                $this->_prep_after_write();
                return $this->_database->affected_rows();
            }
            return FALSE;
        }

        /**
         * public function restore($where = NULL)
         * "Un-deletes" a row
         * @param null $where
         * @return bool
         */
        public function restore($where = NULL)
        {
            $this->with_trashed();
            if (isset($where)) {
                $this->where($where);
            }
            if ($affected_rows = $this->_database->update($this->table, [$this->_deleted_at_field => 0])) {
                $this->_prep_after_write();
                return $affected_rows;
            }
            return FALSE;
        }

        /**
         * public function trashed($where = NULL)
         * Verifies if a record (row) is soft_deleted or not
         * @param null $where
         * @return bool
         */
        public function trashed($where = NULL)
        {
            $this->only_trashed();
            if (isset($where)) {
                $this->where($where);
            }
            $this->limit(1);
            $query = $this->_database->get($this->table);
            if ($query->num_rows() == 1) {
                return TRUE;
            }
            return FALSE;
        }

        public function _get_joined($requested)
        {
            $this->_database->join($this->_relationships[$requested['request']]['foreign_table'], $this->table . '.' . $this->_relationships[$requested['request']]['local_key'] . ' = ' . $this->_relationships[$requested['request']]['foreign_table'] . '.' . $this->_relationships[$requested['request']]['foreign_key']);
            $the_select = '';
            if (!empty($requested['parameters'])) {
                if (array_key_exists('fields', $requested['parameters'])) {
                    $fields     = explode(',', $requested['parameters']['fields']);
                    $sub_select = [];
                    foreach ($fields as $field) {
                        $sub_select[] = ((strpos($field, '.') === FALSE) ? '`' . $this->_relationships[$requested['request']]['foreign_table'] . '`.`' . trim($field) . '`' : trim($field)) . ' AS ' . $requested['request'] . '_' . trim($field);
                    }
                    $the_select = implode(',', $sub_select);

                } else {
                    $the_select = $this->_relationships[$requested['request']]['foreign_table'] . '.*';
                }
            }
            $this->_database->select($the_select);
            unset($this->_requested[$requested['request']]);
        }


        /**
         * public function get()
         * Retrieves one row from table.
         * @param null $where
         * @return mixed
         */
        public function get($where = NULL)
        {
            $data = $this->_get_from_cache();

            if (isset($data) && $data !== FALSE) {
                $this->_database->reset_query();
                if (isset($this->_cache)) unset($this->_cache);
                return $data;
            } else {
                $this->trigger('before_get');
                if ($this->_select) {
                    $this->_database->select($this->_select);
                }
                if (!empty($this->_requested)) {

                    foreach ($this->_requested as $requested) {
                        if (isset($requested['parameters']['join'])) {
                            $this->_get_joined($requested);
                        } else {
                            $this->_database->select($this->table . '.' . $this->_relationships[$requested['request']]['local_key']);
                        }
                    }
                }
                if (isset($where)) {
                    $this->where($where);
                }
                if ($this->soft_deletes === TRUE) {
                    $this->_where_trashed();
                }
                $this->limit(1);
                $query = $this->_database->get($this->table);
                $this->_reset_trashed();
                if ($query->num_rows() == 1) {
                    $row = $query->row_array();
                    $row = $this->trigger('after_get', $row);
                    $row = $this->_prep_after_read([$row], FALSE);
                    $row = is_array($row) ? $row[0] : $row->{0};
                    $this->_write_to_cache($row);
                    if (!isset($row['counted_rows']) && method_exists($this, 'convert')) {
                        if ($this->is_convert) $row = $this->convert($row);
                    }
                    return $row;
                } else {
                    return [];
                }
            }
        }

        /**
         * public function get_all()
         * Retrieves rows from table.
         * @param null $where
         * @return mixed
         */
        public function get_all($where = NULL)
        {

            $data = $this->_get_from_cache();

            if (isset($data) && $data !== FALSE) {
                $this->_database->reset_query();
                if (isset($this->_cache)) unset($this->_cache);
                return $data;

            } else {
                $this->trigger('before_get');
                if (isset($where)) {
                    $this->where($where);
                }
                if ($this->soft_deletes === TRUE) {
                    $this->_where_trashed();
                }
                if (isset($this->_select)) {
                    $this->_database->select($this->_select);
                }
                if (!empty($this->_requested)) {
                    foreach ($this->_requested as $requested) {
                        if (isset($requested['parameters']['join'])) {
                            $this->_get_joined($requested);
                        } else {
                            $this->_database->select($this->table . '.' . $this->_relationships[$requested['request']]['local_key']);
                        }
                    }
                }
                $query = $this->_database->get($this->table);
                $this->_reset_trashed();
                if ($query->num_rows() > 0) {
                    $data = $query->result_array();
                    $data = $this->trigger('after_get', $data);
                    $data = $this->_prep_after_read($data, TRUE);
                    $this->_write_to_cache($data);

                    if (method_exists($this, 'convert') && $this->is_convert) {
                        foreach ($data as $key => $row) {
                            $data[$key] = $this->convert($row);
                        }
                    }
                    return $data;
                } else {
                    return [];
                }
            }
        }

        /**
         * public function count_rows()
         * Retrieves number of rows from table.
         * @param null $where
         * @return integer
         */
        public function count_rows($where = NULL)
        {
            if (isset($where)) {
                $this->where($where);
            }
            if ($this->soft_deletes === TRUE) {
                $this->_where_trashed();
            }
            $this->trigger('before_get');
            $this->_database->from($this->table);
            $number_rows = $this->_database->count_all_results();
            $this->_reset_trashed();
            return $number_rows;
        }

        /** RELATIONSHIPS */

        /**
         * public function with($requests)
         * allows the user to retrieve records from other interconnected tables depending on the relations defined
         * before the constructor
         * @param string $request
         * @param array  $arguments
         * @return $this
         */
        public function with($request, $arguments = [])
        {
            $this->_set_relationships();
            if (array_key_exists($request, $this->_relationships)) {
                $this->_requested[$request] = ['request' => $request];
                $parameters                 = [];

                if (isset($arguments)) {
                    foreach ($arguments as $argument) {
                        if (is_array($argument)) {
                            foreach ($argument as $k => $v) {
                                $parameters[$k] = $v;
                            }
                        } else {
                            $requested_operations = explode('|', $argument);
                            foreach ($requested_operations as $operation) {
                                $elements = explode(':', $operation, 2);
                                if (sizeof($elements) == 2) {
                                    $parameters[$elements[0]] = $elements[1];
                                } else {
                                    show_error('MY_Model: Parameters for with_*() method must be of the form: "...->with_*(\'where:...|fields:...\')"');
                                }
                            }
                        }
                    }
                }
                $this->_requested[$request]['parameters'] = $parameters;
            }


            /*
            if($separate_subqueries === FALSE)
            {
                $this->separate_subqueries = FALSE;
                foreach($this->_requested as $request)
                {
                    if($this->_relationships[$request]['relation'] == 'has_one') $this->_has_one($request);
                }
            }
            else
            {
                $this->after_get[] = 'join_temporary_results';
            }
            */
            return $this;
        }

        /**
         * protected function join_temporary_results($data)
         * Joins the subquery results to the main $data
         * @param $data
         * @return mixed
         */
        protected function join_temporary_results($data)
        {
            foreach ($this->_requested as $requested_key => $request) {
                $order_by           = [];
                $order_inside_array = [];
                $pivot_table        = NULL;
                $relation           = $this->_relationships[$request['request']];
                $this->load->model($relation['foreign_model'], $relation['foreign_model_name']);
                $foreign_key   = $relation['foreign_key'];
                $local_key     = $relation['local_key'];
                $foreign_table = $relation['foreign_table'];
                $type          = $relation['relation'];
                $relation_key  = $relation['relation_key'];
                if ($type == 'has_many_pivot') {
                    $pivot_table       = $relation['pivot_table'];
                    $pivot_local_key   = $relation['pivot_local_key'];
                    $pivot_foreign_key = $relation['pivot_foreign_key'];
                    $get_relate        = $relation['get_relate'];
                }

                if (array_key_exists('order_inside', $request['parameters'])) {
                    //$order_inside = $request['parameters']['order_inside'];
                    $elements = explode(',', $request['parameters']['order_inside']);
                    foreach ($elements as $element) {
                        $order = explode(' ', $element);
                        if (sizeof($order) == 2) {
                            $order_inside_array[] = [trim($order[0]), trim($order[1])];
                        } else {
                            $order_inside_array[] = [trim($order[0]), 'desc'];
                        }
                    }

                }


                $local_key_values = [];
                foreach ($data as $key => $element) {
                    if (isset($element[$local_key]) and !empty($element[$local_key])) {
                        $id                     = $element[$local_key];
                        $local_key_values[$key] = $id;
                    }
                }
                if (!$local_key_values) {
                    $data[$key][$relation_key] = NULL;
                    continue;
                }
                if (!isset($pivot_table)) {
                    $sub_results = $this->{$relation['foreign_model_name']};
                    $select      = [];
                    $select[]    = '`' . $foreign_table . '`.`' . $foreign_key . '`';
                    if (!empty($request['parameters'])) {
                        if (array_key_exists('fields', $request['parameters'])) {
                            if ($request['parameters']['fields'] == '*count*') {
                                $the_select  = '*count*';
                                $sub_results = (isset($the_select)) ? $sub_results->fields($the_select) : $sub_results;
                                $sub_results = $sub_results->fields($foreign_key);
                            } else {
                                $fields = explode(',', $request['parameters']['fields']);
                                foreach ($fields as $field) {
                                    $select[] = (strpos($field, '.') === FALSE) ? '`' . $foreign_table . '`.`' . trim($field) . '`' : trim($field);
                                }
                                $the_select  = implode(',', $select);
                                $sub_results = (isset($the_select)) ? $sub_results->fields($the_select) : $sub_results;
                            }

                        }
                        if (array_key_exists('fields', $request['parameters']) && ($request['parameters']['fields'] == '*count*')) {
                            $sub_results->group_by('`' . $foreign_table . '`.`' . $foreign_key . '`');
                        }
                        if (array_key_exists('where', $request['parameters']) || array_key_exists('non_exclusive_where', $request['parameters'])) {
                            $the_where = array_key_exists('where', $request['parameters']) ? 'where' : 'non_exclusive_where';
                        }
                        $sub_results = isset($the_where) ? $sub_results->where($request['parameters'][$the_where], NULL, NULL, FALSE, FALSE, TRUE) : $sub_results;

                        if (isset($order_inside_array)) {
                            foreach ($order_inside_array as $order_by_inside) {
                                $sub_results = $sub_results->order_by($order_by_inside[0], $order_by_inside[1]);
                            }
                        }

                        //Add nested relation
                        if (array_key_exists('with', $request['parameters'])) {
                            // Do we have many nested relation
                            if (is_array($request['parameters']['with']) && isset($request['parameters']['with'][0]) && is_array($request['parameters']['with'][0])) {
                                foreach ($request['parameters']['with'] as $with) {
                                    $with_relation = array_shift($with);
                                    $sub_results->with($with_relation, [$with]);
                                }
                            } else // single nested relation
                            {
                                $with_relation = array_shift($request['parameters']['with']);
                                $sub_results->with($with_relation, [$request['parameters']['with']]);
                            }
                        }
                    }

                    $sub_results = $sub_results->where($foreign_key, $local_key_values)->get_all();
                } else {
                    $this->_database->join($pivot_table, $foreign_table . '.' . $foreign_key . ' = ' . $pivot_table . '.' . $pivot_foreign_key, 'left');
                    $this->_database->join($this->table, $pivot_table . '.' . $pivot_local_key . ' = ' . $this->table . '.' . $local_key, 'left');
                    $this->_database->select($foreign_table . '.' . $foreign_key);
                    $this->_database->select($pivot_table . '.' . $pivot_local_key);
                    if (!empty($request['parameters'])) {
                        if (array_key_exists('fields', $request['parameters'])) {
                            if ($request['parameters']['fields'] == '*count*') {
                                $this->_database->select('COUNT(`' . $foreign_table . '`.`' . $foreign_key . '`) as counted_rows, `' . $foreign_table . '`.`' . $foreign_key . '`', FALSE);
                            } else {

                                $fields = explode(',', $request['parameters']['fields']);
                                $select = [];
                                foreach ($fields as $field) {
                                    $select[] = (strpos($field, '.') === FALSE) ? '`' . $foreign_table . '`.`' . trim($field) . '`' : trim($field);
                                }
                                $the_select = implode(',', $select);
                                $this->_database->select($the_select);
                            }
                        }

                        if (array_key_exists('where', $request['parameters']) || array_key_exists('non_exclusive_where', $request['parameters'])) {
                            $the_where = array_key_exists('where', $request['parameters']) ? 'where' : 'non_exclusive_where';

                            $this->_database->where($request['parameters'][$the_where], NULL, NULL, FALSE, FALSE, TRUE);
                        }
                    }
                    $this->_database->where_in($pivot_table . '.' . $pivot_local_key, $local_key_values);

                    if (!empty($order_inside_array)) {
                        $order_inside_str = '';
                        foreach ($order_inside_array as $order_by_inside) {
                            $order_inside_str .= (strpos($order_by_inside[0], '.') === FALSE) ? '`' . $foreign_table . '`.`' . $order_by_inside[0] . ' ' . $order_by_inside[1] : $order_by_inside[0] . ' ' . $order_by_inside[1];
                            $order_inside_str .= ',';
                        }
                        $order_inside_str = rtrim($order_inside_str, ",");
                        $this->_database->order_by($order_inside_str);
                    }
                    $sub_results = $this->_database->get($foreign_table)->result_array();
                    $this->_database->reset_query();
                }

                if (isset($sub_results) && !empty($sub_results)) {
                    $subs = [];

                    foreach ($sub_results as $result) {
                        $result_array    = (array)$result;
                        $the_foreign_key = $result_array[$foreign_key];
                        if (isset($pivot_table)) {
                            $the_local_key = $result_array[$pivot_local_key];
                            if (isset($get_relate) and $get_relate === TRUE) {
                                $subs[$the_local_key][$the_foreign_key] = $this->{$relation['foreign_model']}->where($foreign_key, $result[$foreign_key])->get();
                            } else {
                                $subs[$the_local_key][$the_foreign_key] = $result;
                            }
                        } else {
                            if ($type == 'has_one') {
                                $subs[$the_foreign_key] = $result;
                            } else {
                                $subs[$the_foreign_key][] = $result;
                            }
                        }


                    }
                    $sub_results = $subs;

                    foreach ($local_key_values as $key => $value) {
                        if (array_key_exists($value, $sub_results)) {
                            $data[$key][$relation_key] = $sub_results[$value];
                        } else {
                            if (array_key_exists('where', $request['parameters'])) {
                                unset($data[$key]);
                            }
                        }
                    }
                } else {
                    $data[$key][$relation_key] = NULL;
                }
                if (array_key_exists('order_by', $request['parameters'])) {
                    $elements = explode(',', $request['parameters']['order_by']);
                    if (sizeof($elements) == 2) {
                        $order_by[$relation_key] = [trim($elements[0]), trim($elements[1])];
                    } else {
                        $order_by[$relation_key] = [trim($elements[0]), 'desc'];
                    }
                }
                unset($this->_requested[$requested_key]);
            }
            if (!empty($order_by)) {
                foreach ($order_by as $field => $row) {
                    [$key, $value] = $row;
                    $data = $this->_build_sorter($data, $field, $key, $value);
                }
            }
            return $data;
        }


        /**
         * private function _has_one($request)
         *
         * returns a joining of two tables depending on the $request relationship established in the constructor
         * @param $request
         * @return $this
         */
        private function _has_one($request)
        {
            $relation = $this->_relationships[$request];
            $this->_database->join($relation['foreign_table'], $relation['foreign_table'] . '.' . $relation['foreign_key'] . ' = ' . $this->table . '.' . $relation['local_key'], 'left');
            return TRUE;
        }

        /**
         * private function _set_relationships()
         *
         * Called by the public method with() it will set the relationships between the current model and other models
         */
        private function _set_relationships()
        {
            if (empty($this->_relationships)) {
                $options = ['has_one', 'has_many', 'has_many_pivot'];
                foreach ($options as $option) {
                    if (isset($this->{$option}) && !empty($this->{$option})) {
                        foreach ($this->{$option} as $key => $relation) {
                            $single_query = FALSE;
                            if (!is_array($relation)) {
                                $foreign_model = $relation;
                                $model         = $this->_parse_model_dir($foreign_model);
                                $foreign_model = $model['foreign_model'];
                                //$model_dir = $model['model_dir'];
                                $foreign_model_name = $model['foreign_model_name'];

                                $this->load->model($foreign_model, $foreign_model_name);
                                $foreign_table     = $this->{$foreign_model_name}->table;
                                $foreign_key       = $this->{$foreign_model_name}->primary_key;
                                $local_key         = $this->primary_key;
                                $pivot_local_key   = $this->table . '_' . $local_key;
                                $pivot_foreign_key = $foreign_table . '_' . $foreign_key;
                                $get_relate        = FALSE;

                            } else {
                                if ($this->is_assoc($relation)) {
                                    $foreign_model      = $relation['foreign_model'];
                                    $model              = $this->_parse_model_dir($foreign_model);
                                    $foreign_model      = $model['model_dir'] . $model['foreign_model'];
                                    $foreign_model_name = $model['foreign_model_name'];

                                    if (array_key_exists('foreign_table', $relation)) {
                                        $foreign_table = $relation['foreign_table'];
                                    } else {
                                        $this->load->model($foreign_model, $foreign_model_name);
                                        $foreign_table = $this->{$foreign_model_name}->table;
                                    }

                                    $foreign_key = $relation['foreign_key'];
                                    $local_key   = $relation['local_key'];
                                    if ($option == 'has_many_pivot') {
                                        $pivot_table       = $relation['pivot_table'];
                                        $pivot_local_key   = (array_key_exists('pivot_local_key', $relation)) ? $relation['pivot_local_key'] : $this->table . '_' . $this->primary_key;
                                        $pivot_foreign_key = (array_key_exists('pivot_foreign_key', $relation)) ? $relation['pivot_foreign_key'] : $foreign_table . '_' . $foreign_key;
                                        $get_relate        = (array_key_exists('get_relate', $relation) && ($relation['get_relate'] === TRUE)) ? TRUE : FALSE;
                                    }
                                    if ($option == 'has_one' && isset($relation['join']) && $relation['join'] === TRUE) {
                                        $single_query = TRUE;
                                    }
                                } else {
                                    $foreign_model      = $relation[0];
                                    $model              = $this->_parse_model_dir($foreign_model);
                                    $foreign_model      = $model['model_dir'] . $model['foreign_model'];
                                    $foreign_model_name = $model['foreign_model_name'];

                                    $this->load->model($foreign_model);
                                    $foreign_table = $this->{$foreign_model}->table;
                                    $foreign_key   = $relation[1];
                                    $local_key     = $relation[2];
                                    if ($option == 'has_many_pivot') {
                                        $pivot_local_key   = $this->table . '_' . $this->primary_key;
                                        $pivot_foreign_key = $foreign_table . '_' . $foreign_key;
                                        $get_relate        = (isset($relation[3]) && ($relation[3] === TRUE())) ? TRUE : FALSE;
                                    }
                                }

                            }

                            if ($option == 'has_many_pivot' && !isset($pivot_table)) {
                                $tables = [$this->table, $foreign_table];
                                sort($tables);
                                $pivot_table = $tables[0] . '_' . $tables[1];
                            }

                            $this->_relationships[$key] = ['relation' => $option, 'relation_key' => $key, 'foreign_model' => strtolower($foreign_model), 'foreign_model_name' => strtolower($foreign_model_name), 'foreign_table' => $foreign_table, 'foreign_key' => $foreign_key, 'local_key' => $local_key];
                            if ($option == 'has_many_pivot') {
                                $this->_relationships[$key]['pivot_table']       = $pivot_table;
                                $this->_relationships[$key]['pivot_local_key']   = $pivot_local_key;
                                $this->_relationships[$key]['pivot_foreign_key'] = $pivot_foreign_key;
                                $this->_relationships[$key]['get_relate']        = $get_relate;
                            }
                            if ($single_query === TRUE) {
                                $this->_relationships[$key]['joined'] = TRUE;
                            }
                        }
                    }
                }
            }

        }

        /** END RELATIONSHIPS */

        /**
         * public function on($connection_group = NULL)
         * Sets a different connection to use for a query
         * @param $connection_group = NULL - connection group in database setup
         * @return obj
         */
        public function on($connection_group = NULL)
        {
            if (isset($connection_group)) {
                $this->_database->close();
                $this->load->database($connection_group);
                $this->_database = $this->db;
            }
            return $this;
        }

        /**
         * public function reset_connection()
         * Resets the connection to the default used for all the model
         * @return obj
         */
        public function reset_connection()
        {
            $this->_database->close();
            $this->_set_connection();
            return $this;
        }

        /**
         * Trigger an event and call its observers. Pass through the event name
         * (which looks for an instance variable $this->event_name), an array of
         * parameters to pass through and an optional 'last in interation' boolean
         */
        public function trigger($event, $data = [], $last = TRUE)
        {
            if (isset($this->$event) && is_array($this->$event)) {
                foreach ($this->$event as $method) {
                    if (strpos($method, '(')) {
                        preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);
                        $method                    = $matches[1];
                        $this->callback_parameters = explode(',', $matches[3]);
                    }
                    $data = call_user_func_array([$this, $method], [$data, $last]);
                }
            }
            return $data;
        }

        /**
         * private function _reset_trashed()
         * Sets $_trashed to default 'without'
         */
        private function _reset_trashed()
        {
            $this->_trashed = 'without';
            return $this;
        }

        /**
         * public function with_trashed()
         * Sets $_trashed to with
         */
        public function with_trashed()
        {
            $this->_trashed = 'with';
            return $this;
        }

        /**
         * public function without_trashed()
         * Sets $_trashed to without
         */
        public function without_trashed()
        {
            $this->_trashed = 'without';
            return $this;
        }

        /**
         * public function with_trashed()
         * Sets $_trashed to only
         */
        public function only_trashed()
        {
            $this->_trashed = 'only';
            return $this;
        }

        private function _where_trashed()
        {
            switch ($this->_trashed) {
                case 'only' :
                    $this->_database->where($this->table . '.' . $this->_deleted_at_field . ' > 0', NULL, FALSE);
                    break;
                case 'without' :
                    $this->_database->where($this->table . '.' . $this->_deleted_at_field . ' = 0', NULL, FALSE);
                    break;
                case 'with' :
                    break;
            }
            //$this->_trashed = ''; issue #208...
            return $this;
        }

        /**
         * public function fields($fields)
         * does a select() of the $fields
         * @param $fields the fields needed
         * @return $this
         */
        public function fields($fields = NULL)
        {
            if (isset($fields)) {
                if ($fields == '*count*') {
                    $this->_select = '';
                    $this->_database->select('COUNT(*) AS counted_rows', FALSE);
                } else {
                    $this->_select = [];
                    $fields        = (!is_array($fields)) ? explode(',', $fields) : $fields;
                    if (!empty($fields)) {
                        foreach ($fields as &$field) {
                            $exploded = explode('.', $field);
                            if (sizeof($exploded) < 2) {
                                $field = $this->table . '.' . $field;
                            }
                        }
                    }
                    $this->_select = $fields;
                }
            } else {
                $this->_select = NULL;
            }
            return $this;
        }

        /**
         * public function order_by($criteria, $order = 'ASC'
         * A wrapper to $this->_database->order_by()
         * @param        $criteria
         * @param string $order
         * @return $this
         */
        public function order_by($criteria, $order = NULL)
        {
            if (is_array($criteria)) {
                foreach ($criteria as $key => $value) {
                    $this->_database->order_by($key, $value);
                }
            } else {
                if ($order) {
                    $this->_database->order_by($criteria, $order);
                } else {
                    $this->_database->order_by($criteria);
                }

            }
            return $this;
        }

        /**
         * Return the next call as an array rather than an object
         */
        public function as_array()
        {
            $this->return_as = 'array';
            return $this;
        }

        /**
         * Return the next call as an object rather than an array
         */
        public function as_object()
        {
            $this->return_as = 'object';
            return $this;
        }

        public function as_dropdown($field = NULL)
        {
            if (!isset($field)) {
                show_error('MY_Model: You must set a field to be set as value for the key: ...->as_dropdown(\'field\')->...');
                exit;
            }
            $this->return_as_dropdown = 'dropdown';
            $this->_dropdown_field    = $field;
            $this->_select            = [$this->primary_key, $field];
            return $this;
        }

        protected function _get_from_cache($cache_name = NULL)
        {
            if (isset($cache_name) || (isset($this->_cache) && !empty($this->_cache))) {
                $this->load->driver('cache');
                $cache_name = isset($cache_name) ? $cache_name : $this->_cache['cache_name'];
                $data       = $this->cache->{$this->cache_driver}->get($cache_name);
                return $data;
            }
        }

        protected function _write_to_cache($data, $cache_name = NULL)
        {
            if (isset($cache_name) || (isset($this->_cache) && !empty($this->_cache))) {
                $this->load->driver('cache');
                $cache_name = isset($cache_name) ? $cache_name : $this->_cache['cache_name'];
                $seconds    = $this->_cache['seconds'];
                if (isset($cache_name) && isset($seconds)) {
                    $this->cache->{$this->cache_driver}->save($cache_name, $data, $seconds);
                    $this->_reset_cache($cache_name);
                    return TRUE;
                }
                return FALSE;
            }
        }

        public function set_cache($string, $seconds = 86400)
        {
            $prefix       = (strlen($this->cache_prefix) > 0) ? $this->cache_prefix . '_' : '';
            $prefix       .= $this->table . '_';
            $this->_cache = ['cache_name' => $prefix . $string, 'seconds' => $seconds];
            return $this;
        }

        private function _reset_cache($string)
        {
            if (isset($string)) {
                $this->_cache = [];
            }
            return $this;
        }

        public function delete_cache($string = NULL)
        {
            $this->load->driver('cache');
            $prefix = (strlen($this->cache_prefix) > 0) ? $this->cache_prefix . '_' : '';
            $prefix .= $this->table . '_';
            if (isset($string) && (strpos($string, '*') === FALSE)) {
                $this->cache->{$this->cache_driver}->delete($prefix . $string);
            } else {
                $cached = $this->cache->file->cache_info();
                foreach ($cached as $file) {
                    if (array_key_exists('relative_path', $file)) {
                        $path = $file['relative_path'];
                        break;
                    }
                }
                if (isset($path)) {
                    $mask = (isset($string)) ? $path . $prefix . $string : $path . $this->cache_prefix . '_*';
                    array_map('unlink', glob($mask));
                }
            }
            return $this;
        }

        /**
         * private function _set_timestamps()
         *
         * Sets the fields for the created_at, updated_at and deleted_at timestamps
         * @return bool
         */
        private function _set_timestamps()
        {
            if ($this->timestamps !== FALSE) {
                $this->_created_at_field = (is_array($this->timestamps) && isset($this->timestamps['create'])) ? $this->timestamps['create'] : '';
                $this->_updated_at_field = (is_array($this->timestamps) && isset($this->timestamps['update'])) ? $this->timestamps['update'] : '';
                $this->_deleted_at_field = (is_array($this->timestamps) && isset($this->timestamps['delete'])) ? $this->timestamps['delete'] : '';
            }
            return TRUE;
        }

        /**
         * private function _the_timestamp()
         *
         * returns a value representing the date/time depending on the timestamp format choosed
         * @return string
         */
        private function _the_timestamp()
        {
            if ($this->timestamps_format == 'timestamp') {
                return time();
            } else {
                return date($this->timestamps_format);
            }
        }

        /**
         * private function _set_connection()
         *
         * Sets the connection to database
         */
        private function _set_connection()
        {
            if (isset($this->_database_connection)) {
                $this->_database = $this->load->database($this->_database_connection, TRUE);
            } else {
                $this->load->database();
                $this->_database = $this->db;
            }
            // This may not be required
            return $this;
        }

        /*
         * HELPER FUNCTIONS
         */

        public function paginate($rows_per_page, $total_rows = NULL, $page_number = 1)
        {
            $this->load->helper('url');
            $segments  = $this->uri->total_segments();
            $uri_array = $this->uri->segment_array();
            $page      = $this->uri->segment($segments);
            if (is_numeric($page)) {
                $page_number = $page;
            } else {
                $page_number = $page_number;
                $uri_array[] = $page_number;
                ++$segments;
            }
            $next_page     = $page_number + 1;
            $previous_page = $page_number - 1;

            if ($page_number == 1) {
                $this->previous_page = $this->pagination_delimiters[0] . $this->pagination_arrows[0] . $this->pagination_delimiters[1];
            } else {
                $uri_array[$segments] = $previous_page;
                $uri_string           = implode('/', $uri_array);
                $this->previous_page  = $this->pagination_delimiters[0] . anchor($uri_string, $this->pagination_arrows[0]) . $this->pagination_delimiters[1];
            }
            $uri_array[$segments] = $next_page;
            $uri_string           = implode('/', $uri_array);
            if (isset($total_rows) && (ceil($total_rows / $rows_per_page) == $page_number)) {
                $this->next_page = $this->pagination_delimiters[0] . $this->pagination_arrows[1] . $this->pagination_delimiters[1];
            } else {
                $this->next_page = $this->pagination_delimiters[0] . anchor($uri_string, $this->pagination_arrows[1]) . $this->pagination_delimiters[1];
            }

            $rows_per_page = (is_numeric($rows_per_page)) ? $rows_per_page : 10;

            if (isset($total_rows)) {
                if ($total_rows != 0) {
                    $number_of_pages = ceil($total_rows / $rows_per_page);
                    $links           = $this->previous_page;
                    for ($i = 1; $i <= $number_of_pages; $i++) {
                        unset($uri_array[$segments]);
                        $uri_string = implode('/', $uri_array);
                        $links      .= $this->pagination_delimiters[0];
                        $links      .= (($page_number == $i) ? anchor($uri_string, $i) : anchor($uri_string . '/' . $i, $i));
                        $links      .= $this->pagination_delimiters[1];
                    }
                    $links           .= $this->next_page;
                    $this->all_pages = $links;
                } else {
                    $this->all_pages = $this->pagination_delimiters[0] . $this->pagination_delimiters[1];
                }
            }


            if (isset($this->_cache) && !empty($this->_cache)) {
                $this->load->driver('cache');
                $cache_name = $this->_cache['cache_name'] . '_' . $page_number;
                $seconds    = $this->_cache['seconds'];
                $data       = $this->cache->{$this->cache_driver}->get($cache_name);
            }

            if (isset($data) && $data !== FALSE) {
                return $data;
            } else {
                $this->trigger('before_get');
                $this->where();
                $this->limit($rows_per_page, (($page_number - 1) * $rows_per_page));
                $data = $this->get_all();
                if ($data) {
                    if (isset($cache_name) && isset($seconds)) {
                        $this->cache->{$this->cache_driver}->save($cache_name, $data, $seconds);
                        $this->_reset_cache($cache_name);
                    }
                    return $data;
                } else {
                    return [];
                }
            }
        }

        public function set_pagination_delimiters($delimiters)
        {
            if (is_array($delimiters) && sizeof($delimiters) == 2) {
                $this->pagination_delimiters = $delimiters;
            }
            return $this;
        }

        public function set_pagination_arrows($arrows)
        {
            if (is_array($arrows) && sizeof($arrows) == 2) {
                $this->pagination_arrows = $arrows;
            }
            return $this;
        }

        /**
         * this function verifies if a table name was defined. if not, it calls the _get_table_name in order to
         * retrieve a potential table name, which will be tested if exists. If all went well, the table name will be
         * saved as $this->table
         * @return boolean
         */
        private function _fetch_table()
        {
            if (!isset($this->table)) {
                $this->table = $this->_database->dbprefix($this->_get_table_name(get_class($this)));
                if (!$this->db->table_exists($this->table)) {
                    show_error(
                        sprintf('While trying to figure out the table name, couldn\'t find an existing table named: <strong>"%s"</strong>.<br />You can set the table name in your model by defining the protected variable <strong>$table</strong>.', $this->table),
                        500,
                        sprintf('Error trying to figure out table name for model "%s"', get_class($this))
                    );
                }
            }
            $this->_set_table_fillable_protected();
            return TRUE;
        }

        private function _get_table_name($model_name)
        {
            $this->load->helper('inflector');
            $model_name = $this->uncamelize($model_name);
            $table_name = preg_replace('/(_m|_model|_mdl|model)?$/', '', strtolower($model_name));
            return $table_name;
        }

        private function _set_table_fillable_protected()
        {
            if (is_null($this->fillable)) {

                $table_fields = $this->_database->list_fields($this->table);
                foreach ($table_fields as $field) {
                    if (is_array($this->protected) && !in_array($field, $this->protected)) {
                        $this->fillable[] = $field;
                    } else if (is_null($this->protected) && ($field !== $this->primary_key)) {
                        $this->fillable[] = $field;
                    }
                }
            }
            if (is_null($this->protected)) {
                $this->protected = [$this->primary_key];
            }
            return $this;
        }

        public function __call($method, $arguments)
        {
            if (substr($method, 0, 6) == 'where_') {
                $column = substr($method, 6);
                $this->where($column, $arguments);
                return $this;
            }
            if (($method != 'with_trashed') && (substr($method, 0, 5) == 'with_')) {
                $relation = substr($method, 5);
                $this->with($relation, $arguments);
                return $this;
            }
            if (method_exists($this->_database, $method)) {
                call_user_func_array([$this->_database, $method], $arguments);
                return $this;
            }
            $parent_class = get_parent_class($this);
            if ($parent_class !== FALSE && !method_exists($parent_class, $method) && !method_exists($this, $method)) {
                $msg = 'The method "' . $method . '" does not exist in ' . get_class($this) . ' or MY_Model or CI_Model.';
                show_error($msg, EXIT_UNKNOWN_METHOD, 'Method Not Found');
            }
        }

        private function _build_sorter($data, $field, $order_by, $sort_by = 'DESC')
        {
            usort($data, function ($a, $b) use ($field, $order_by, $sort_by) {
                $array_a = isset($a[$field]) ? $this->object_to_array($a[$field]) : NULL;
                $array_b = isset($b[$field]) ? $this->object_to_array($b[$field]) : NULL;
                return strtoupper($sort_by) == "DESC" ?
                    ((isset($array_a[$order_by]) && isset($array_b[$order_by])) ? ($array_a[$order_by] < $array_b[$order_by]) : (!isset($array_a) ? 1 : -1))
                    : ((isset($array_a[$order_by]) && isset($array_b[$order_by])) ? ($array_a[$order_by] > $array_b[$order_by]) : (!isset($array_b) ? 1 : -1));
            });

            return $data;
        }

        public function object_to_array($object)
        {
            if (!is_object($object) && !is_array($object)) {
                return $object;
            }
            if (is_object($object)) {
                $object = get_object_vars($object);
            }
            return array_map([$this, 'object_to_array'], $object);
        }

        public function array_to_object($array)
        {
            $obj = new stdClass();
            return $this->_array_to_object($array, $obj);
        }

        private function _array_to_object($array, &$obj)
        {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $obj->$key = new stdClass();
                    $this->_array_to_object($value, $obj->$key);
                } else {
                    $obj->$key = $value;
                }
            }
            return $obj;
        }

        /**
         * Verifies if an array is associative or not
         * @param array $array
         * @return bool
         */
        protected function is_assoc(array $array)
        {
            return (bool)count(array_filter(array_keys($array), 'is_string'));
        }

        /**
         * private function _parse_model_dir($foreign_model)
         *
         * Parse model and model folder
         * @param $foreign_model
         * @return $data
         */
        private function _parse_model_dir($foreign_model)
        {
            $data['foreign_model'] = $foreign_model;
            $data['model_dir']     = '';

            $full_model = explode('/', $data['foreign_model']);
            if ($full_model) {

                $data['foreign_model'] = end($full_model);
                $data['model_dir']     = str_replace($data['foreign_model'], NULL, implode('/', $full_model));
            }

            $foreign_model_name = str_replace('/', '_', $data['model_dir'] . $data['foreign_model']);

            $data['foreign_model_name'] = strtolower($foreign_model_name);

            return $data;
        }

        public function to_convert ($c = TRUE) {
            $this->is_convert = $c;
            return $this;
        }

        /**
         * 下划线转驼峰(大驼峰)
         *
         * @param string $uncamelized_words 需要转换的字符串
         * @param string $separator         分割字符串
         * @return string
         */
        private function camelize($uncamelized_words, $separator = '_')
        {
            $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
            return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
        }

        /**
         * 驼峰转下划线
         *
         * @param sring  $camelCaps 需要转换的字符串
         * @param string $separator 分类字符串
         * @return string
         */
        private function uncamelize($camelCaps, $separator = '_')
        {
            return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
        }

        public function select_sum($field, $alias = null) {
            if ($alias) {
                if ('*' != $this->_select) {
                    $this->_database->select_sum($field, $alias);
                } else {
                    $this->fields()->_database->select_sum($field, $alias);
                }

            } else {
                if ('*' != $this->_select) {
                    $this->_database->select_sum($field);
                } else {
                    $this->fields()->_database->select_sum($field);
                }
            }
            return $this;
        }

        /**
         * 创建数据缓存
         */
        public function create_cache()
        {
            $cache_name = str_replace($this->db->dbprefix, '', $this->table);
            $cache_name = camelize($cache_name) . 'Cache';
            $result     = $this->db->get($this->table)->result_array();
            $cache_data = array_column($result, NULL, 'id');
            $this->cache->redis->save($cache_name, $cache_data, 10 * 24 * 3600);
        }

    }
