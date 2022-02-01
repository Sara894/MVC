<?php

namespace application\core;

use PDO;
use application\models\Account;

use application\lib\Db;

class Model
{

    public $db;

    public function __construct()
    {
        $this->db = new Db; //подключились к базе данных
    }

    static public function getList($params = [], $pagination = [], $onlyCount = false)
    {
        $what = ($onlyCount) ? 'COUNT(*) as count' : '*';

        //$params нам должен задать дочерний контроллер в виде например getList = ['id'=>'2']
        $sql = 'SELECT '.$what.' from ' . static::$table; // static::$table - имя таблицы в account controller
        $where = '';
        foreach ($params as $key => $param) { //перебираем массив c названиями столбцов в виде ключей и значениями
            $where .= $key . ' = :' . $key . ' AND '; // где id = :id
        }
        // var_dump($where);
        $where = trim($where, ' AND '); //стираем последнее AND
        if ($where) { //если $where непустое то присоединяем его к sql запросу
            $sql .= ' WHERE ' . $where;
        }

        if(!empty($pagination)) {
//            $fullCount = static::getList();
            $pagination[1] = (int)($pagination[1]);//выдавало ошибку тк это было строкой
            $start = $pagination[0]*(($pagination[1])-1);
           // debug($pagination[1]);
            $sql .= ' LIMIT '.$start.','.$pagination[0];
        }

        $return = [];
        $connection = new self(); // создаем объект класса Model
        $response = $connection->db->row($sql, $params); // обращаемся к методу row с аргументами $sql b  в классе Db и записываем результат
        // debug($response);//$response содержит данные полученные из таблицы sql запросом
        foreach ($response as $array) {
            if($onlyCount) {
                $return = $array['count'];
            } else {
            $model = new static(); //создаем модель таблицы users из class Account? Или
            $model->load($array); // используем функцию load чтобы подставить значения из массива response в столбцы $model
            $return[] = $model; //приравниваем полученный ассоциативный массив(на самом деле это не ассоциативный массив а обЪект класса Account который символизирует собой строку из таблицы) к массиву $return
            }
        }
        return $return;
    }

    static function getPagination($limit, $page) {
        $data = [];
        $count = self::getList($params = [], $pagination = [], $onlyCount = true);// узнаем сколько записей в базе даннх
        $totalPage = ceil($count / $limit); //ceil -- Округляет дробь в большую сторону
        if ($page > $totalPage) {// если номер запрашиваемой страниц больше количества  старниц
            $page = $totalPage;// то переходим на последнюю страницу
        }

        $start = (int)((int)$page * (int)$limit - (int)$limit);
      // умножаем номер страниц на лимит и отнимаем лимит имеено с этого номера записи м начинаем вводить данне
        $data['page'] = (int)$page;// здесь идет присваивание
        $data['limit'] = (int)$limit;
        $data['totalPage'] = (int)$totalPage;
        $data['start'] = (int)$start;
        return $data;
    }


    public static function getOne($params, $role = false)
    {

        //эта функция может показывать только одну строку из бд тк есть лимит
        $role_sql = ($role) ? 'role_id' : '*';
        $where = '';
        foreach ($params as $key => $value) {

            $where .= $key . '=:' . $key . 'AND';
        }

        $where = trim($where, "AND"); // убираем последнее AND

        $sql = "SELECT ".$role_sql." FROM " . static::$table;

        if ($where) {

            $sql .= ' WHERE ' . $where;
        }

        // выставляем ограничение только на одну строку
        $sql .= ' LIMIT 0,1 ';
        $return = [];
        $connection = new self(); //создаем объект класса Model
        $response = $connection->db->row($sql, $params);

        foreach ($response as $array) {
            $model = new static();
            $model->load($array);
            $return = $model;
        }

        return $return;
    }

    //сохраняем пользователя в бд
    public function save()
    {
        if (method_exists($this, 'beforeSave')) {
            $this->beforeSave();
        }
        $query = ($this->id) ? 'UPDATE ' . static::$table . ' SET ' : 'INSERT ' . static::$table . ' SET ';
        $properties = get_object_vars($this);
        unset($properties['db']);
        foreach ($properties as $property => $value) {
            $query .= $property . ' = :' . $property . ', ';
        }
        $query = trim($query, ', ');
        $query .= ($this->id) ? ' WHERE id=' . $this->id : '';
        $connection = new self();
        $connection->db->row($query, $properties);
        if (!$this->id) {
            $this->id = $connection->db->db->lastInsertId();
            /* PDO::lastInsertId — Возвращает ID последней вставленной
            строки или значение последовательности */
        }
    }

    private function validateItem($rule, $validate, $property, &$return)
    {
        switch ($rule['type']) {
            case 'pattern':
                if (!preg_match($rule['rule'], $validate)) {
                   // debug($rule['rule']);
                    $error = (isset(static::$errorKey[$property]['pattern']))
                        ? static::$errorKey[$property]['pattern']
                        : "invalid field";
                   // var_dump($property);
                    $return[$property] = (isset($return[$property]) && $return[$property])
                        ? $return[$property] . '<br/>' . $error
                        : $error;
                }
                break;

            case 'method':
                $method = $rule['rule'];
                if (method_exists($this, $method)) {
                    if (!$this->$method($validate)) {
                        $error = (isset(static::$errorKey[$property]['method']))
                            ? static::$errorKey[$property]['method']
                            : 'Invalid field';
                        $return[$property] = (isset($return[$property]) && $return[$property])
                            ? $return[$property] . '<br/>' . $error
                            : $error;
                    }
                }
                break;
            default:
                break;
        }
    }

    public function validate($request)
    {
        // debug($request);
        $return = [];
        if (isset(static::$rules)) {
            foreach (static::$rules as $property => $rules) {
                foreach ($rules as $rule) {
                    $this->validateItem($rule, $request[$property], $property, $return);
                }
            }
        }
        return $return;
    }

    public function load($array)
    { //получем значения из таблицы с помощью $response $array функции getList()
        foreach ($array as $property => $value) { //разбираем массив $array на ключи(атрибуты) (id, name ) и значения этих атрибутов
            if (property_exists(get_class($this), $property)) { //get_class — Возвращает имя класса, к которому принадлежит объект
                //property_exists — Проверяет, содержит ли объект или класс указанный атрибут

                $this->$property = $value; // eсли все ок то присваиваем каждому атрибуту($property) значение($value)
            }
        }
        return $this; // возвращаем объект который похож на ассоциативный массив
    }
}
