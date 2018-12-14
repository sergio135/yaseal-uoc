<?php
/**
 * Created by PhpStorm.
 * User: Yanniel Fandiño
 * Date: 15/11/2018
 * Time: 10:57
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Classes\Dao\NewsDao;
use Slim\Http\UploadedFile;

class NewsController {
    protected $container;

    /**
     * AdminPanelController constructor.
     * @param $container
     */
    public function __construct($container) {
        $this->container = $container;
    }

    public function addNews($request, $response, $args) {
        $filename = "";
        $title = trim($request->getParam('title'));
        $subtitle = trim($request->getParam('subtitle'));
        $content = trim($request->getParam('content'));
        $img = $request->getUploadedFiles()['img'];
        $autor = $_SESSION['user']->getId();
        $category_id = trim($request->getParam('category_id'));
        $keywords = array_map('trim', explode(",",$request->getParam('keywords')));

        if (empty($title) || empty($subtitle) || empty($content) || empty($category_id) || empty($keywords)) {
            // Si no se rellena alguno de los campos del formulario se devuelve error
            $args = array("notification" => array("type" => "error",
                                           "msg" => "Debe rellenar todos los campos"),
                          "title" => $title,
                          "subtitle" => $subtitle,
                          "content" => $content,
                          "category_id" => $category_id,
                          "keywords" => implode(',',$keywords));
            return $args;
        }
        if ($img->getError() === UPLOAD_ERR_OK) {
            // Si se ha subido la img correctamente
            $filename = $this->generateFileName($img);
            $img->moveTo(__DIR__ . '/../../../../public/img/news' . DIRECTORY_SEPARATOR . $filename);
        }
        // Si toda la info ok
        // Insertar la nueva noticia
        $newsDao = new NewsDao($this->container['db']);
        $isInserted = $newsDao->add($title, $subtitle, $content, $filename, $autor, $category_id, $keywords);

        if ($isInserted) {
            return true;
        } else {
            unlink(__DIR__ . '/../../../../public/img/news' . DIRECTORY_SEPARATOR . $filename);
            $args = array("notification" => array("type" => "error",
                                                  "msg" => $newsDao->getError()),
                "title" => $title,
                "subtitle" => $subtitle,
                "content" => $content,
                "category_id" => $category_id,
                "keywords" => implode(',',$keywords));
            return $args;
        }
    }

    public function getNewsById($id) {
        $newsDao = new NewsDao($this->container['db']);
        $news = $newsDao->getById($id);

        if ($newsDao->getError() != null) {
            return;
        }

        $args = array("title" => $news->getTitle(),
            "subtitle" => $news->getSubtitle(),
            "date_created" => $news->getDateCreated(),
            "date_modified" => $news->getDateModified(),
            "date_published" => $news->getDatePublished(),
            "content" => $news->getContent(),
            "img" => $news->getImg(),
            "autor" => $news->getAutor(),
            "category_id" => $news->getCategory(),
            "keywords" => $news->getKeywords(),
            "action" => "edit");

        return $args;
    }

    public function updateNews($request, $id) {
        $filename = "";

        $title = trim($request->getParam('title'));
        $subtitle = trim($request->getParam('subtitle'));
        $content = trim($request->getParam('content'));
        $img = $request->getUploadedFiles()['img'];
        $category_id = trim($request->getParam('category_id'));
        $keywords = array_map('trim', explode(",",$request->getParam('keywords')));

        if ($img->getError() === UPLOAD_ERR_OK) {
            // Si se ha subido la img correctamente
            $filename = $this->generateFileName($img);
            $img->moveTo(__DIR__ . '/../../../../public/img/news' . DIRECTORY_SEPARATOR . $filename);
        }

        // Si toda la info ok
        // Editar la noticia
        $newsDao = new NewsDao($this->container['db']);
        $isEdited = $newsDao->edit($id, $title, $subtitle, $content, $filename, $category_id, $keywords);

        if ($isEdited) {
            return true;
        } else {
            if ($filename) {
                chmod(__DIR__ . '/../../../../public/img/news' . DIRECTORY_SEPARATOR . $filename, 0777);
                unlink(__DIR__ . '/../../../../public/img/news' . DIRECTORY_SEPARATOR . $filename);
            }
            $args = array("notification" => array("type" => "error",
                                                        "msg" => $newsDao->getError()),
                          "title" => $title,
                          "subtitle" => $subtitle,
                          "content" => $content,
                          "category_id" => $category_id,
                          "keywords" => implode(',',$keywords));
            return $args;
        }
    }

    public function publishNews($id) {
        $newsDao = new NewsDao($this->container['db']);
        $isEdited = $newsDao->publish($id);

        if ($isEdited) {
            return true;
        } else {
            $news = $newsDao->getById($id);
            $args = array("notification" => array("type" => "error",
                "msg" => $newsDao->getError()),
                "title" => $news->getTitle(),
                "subtitle" => $news->getSubtitle(),
                "content" => $news->getContent(),
                "img" => $news->getImg(),
                "category_id" => $news->getCategory(),
                "keywords" => $news->getKeywords());
            return $args;
        }
    }

    function generateFileName(UploadedFile $uploadedFile) {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        return $filename;
    }

    public function getNewsByCategoryId($categoryId) {
        $newsDao = new NewsDao($this->container['db']);
        $news = $newsDao->listAllInCategory($categoryId);

        if ($newsDao->getError() != null) {
            return;
        }

        return $news;
    }

    public function listAllNews() {
        $newsDao = new NewsDao($this->container['db']);
        $news = $newsDao->listAll();

        if ($newsDao->getError()) {
            return $newsDao->getError();
        }

        return $news;
    }

    public function listAllPublishedNews() {
        $newsDao = new NewsDao($this->container['db']);
        $news = $newsDao->listAllPublished();

        if ($newsDao->getError()) {
            return $newsDao->getError();
        }

        return $news;
    }

    public function getCategories() {
        $newsDao = new NewsDao($this->container['db']);
        $categories = $newsDao->getCategories();
        if ($newsDao->getError()) {
            return $newsDao->getError();
        }
        return $categories;
    }
}