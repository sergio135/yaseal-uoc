<?php
namespace Classes\Models;
use Classes\Dao\UserDao;

class News {
    private $id;
    private $title;
    private $subtitle;
    private $date_created;
    private $date_modified;
    private $date_published;
    private $content;
    private $img;
    private $autor;
    private $editor;
    private $category;
    private $keywords;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getSubtitle() {
        return $this->subtitle;
    }

    public function setSubtitle($subtitle) {
        $this->subtitle = $subtitle;
    }

    public function getDateCreated() {
        return $this->date_created;
    }

    public function setDateCreated($date_created) {
        $this->date_created = $date_created;
    }

    public function getDateModified() {
        return $this->date_modified;
    }

    public function setDateModified($date_modified) {
        $this->date_modified = $date_modified;
    }

    public function getDatePublished() {
        return $this->date_published;
    }

    public function setDatePublished($date_published) {
        $this->date_published = $date_published;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getImg() {
        return $this->img;
    }

    public function setImg($img) {
        $this->img = $img;
    }

    public function getAutor() {
        return $this->autor;
    }

    public function setAutor($autor) {
        $this->autor = $autor;
    }

    public function getEditor() {
        return $this->editor;
    }

    public function setEditor($editor) {
        $this->editor = $editor;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

    public function getKeywords() {
        return $this->keywords;
    }

    public function setKeywords($keywords) {
        $this->keywords = $keywords;
    }

    public function fill($db, array $row) {
        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->subtitle = $row['subtitle'];
        $this->date_created = $row['date_created'];
        $this->date_modified = $row['date_modified'];
        $this->date_published = $row['date_published'];
        $this->content = $row['content'];
        $this->img = $row['img'];
        $this->keywords = $row['keywords'];
        $this->autor = UserDao::getbyId($db, $row['autor']);
        $this->editor = UserDao::getbyId($db, $row['editor']);
        $this->category = $row['category_id'];
    }
}

?>