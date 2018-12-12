<?php
namespace Classes\Dao;
use Classes\Models\News;
use Exception;
use PDO;

class NewsDao {

    private $conn;
    private $error;

    /**
     * NewsDao constructor.
     * @param $conn
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getConn() {
        return $this->conn;
    }

    public function getError() {
        return $this->error;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function add($title, $subtitle, $content, $img, $autor, $category_id, $keywords) {
        $db = $this->getConn();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare("INSERT INTO table_news (title, subtitle, date_created, content, img, autor,category_id)
                               VALUES (:title, :subtitle, CURRENT_DATE, :content, :img, :autor, :category_id)");
            $stmt->bindParam('title', $title);
            $stmt->bindParam('subtitle', $subtitle);
            $stmt->bindParam('content', $content);
            $stmt->bindParam('img', $img);
            $stmt->bindParam('autor', $autor);
            $stmt->bindParam('category_id', $category_id, PDO::PARAM_INT);
            $result = $stmt->execute();

            // Obtener nuevo id
            $id = $db->lastInsertId();
            // Insertar palabras clave
            foreach ($keywords as $keyword) {
                if (!$result) throw new Exception($db->errorInfo());

                $stmt = $db->prepare("INSERT INTO table_keyword (news_id, name)
                                  VALUES (:id, :name)");
                $stmt->bindParam('id', $id, PDO::PARAM_INT);
                $stmt->bindParam('name', $keyword);
                $result = $stmt->execute();
            }
            $db->commit();
            return $result;
        } catch (Exception $e) {
            $db->rollBack();
            $this->setError($e->getMessage());
        }
    }

    public function listAll() {
        $db = $this->getConn();
        $list = array();
        try {
            $sql = "SELECT n.*, GROUP_CONCAT(k.name SEPARATOR ', ') 'keywords'
                FROM table_news n,table_keyword k
                WHERE n.id = k.news_id
                GROUP BY n.id
                ORDER BY n.date_created DESC";

            $stmt = $db->query($sql)->fetchAll();

            foreach ($stmt as $row) {
                $news = new News();
                $news->fill($db, $row);
                array_push($list, $news);
            }

            return $list;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function getById($id) {
        $db = $this->getConn();
        $news = new News();
        try {
            $stmt = $db->prepare("SELECT n.*, GROUP_CONCAT(k.name SEPARATOR ', ') 'keywords'
                                  FROM table_news n,table_keyword k
                                  WHERE n.id = k.news_id
                                  AND n.id = :id");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();
            $news->fill($db, $row);
            return $news;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function edit($id, $title, $subtitle, $content, $img, $category_id, $keywords) {
        $db = $this->getConn();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare("UPDATE table_news
                                  SET title = :title,
                                      subtitle = :subtitle,
                                      date_modified = CURRENT_DATE,
                                      content = :content,
                                      img = :img,
                                      category_id = :category_id
                                  WHERE id = :id");
            $stmt->bindParam();

//            // Insertar palabras clave
//            foreach ($keywords as $keyword) {
//                if (!$result) throw new \Exception($db->errorInfo());
//
//                $stmt = $db->prepare("INSERT INTO table_keyword (news_id, name)
//                                  VALUES (:id, :name)");
//                $stmt->bindParam('id', $id, PDO::PARAM_INT);
//                $stmt->bindParam('name', $keyword);
//                $result = $stmt->execute();
//            }
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }


    public function listAllInCategory($categoryId) {
        $db = $this->getConn();
        $list = array();
        try {
            $sql = "SELECT n.*, GROUP_CONCAT(k.name SEPARATOR ', ') 'keywords'
                FROM table_news n,table_keyword k
                WHERE n.id = k.news_id
                AND n.category_id = $categoryId
                GROUP BY n.id
                ORDER BY n.date_created DESC";

            $stmt = $db->query($sql)->fetchAll();

            foreach ($stmt as $row) {
                $news = new News();
                $news->fill($db, $row);
                array_push($list, $news);
            }

            return $list;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function getCategories() {
        $db = $this->getConn();
        try {
            $sql = "SELECT id, name FROM table_category";
            $result = $db->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);
            return $result;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }
}