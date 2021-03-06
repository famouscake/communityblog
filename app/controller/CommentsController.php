<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - add()
 * Classes list:
 * - CommentsController extends core
 */
namespace app\controller;
use app\model\Ubermodel as Ubermodel;

class CommentsController extends core\controller\Controller
{

    function __construct()
    {
        parent::__construct();
        $this->tableName = "comments";
    }

    public function add()
    {
        if (!empty($_POST)) {

            $stmt = Ubermodel::$pdo->prepare("INSERT INTO comments (user_id, post_id, text, created, modified)
                    VALUES ( :user_id, :post_id, :text, :created, :modified)
                    ");

            $stmt->execute(array(
                ':user_id' => $_SESSION['Auth']['id'],
                ':post_id' => $_POST['post_id'],
                ':text' => $_POST['text'],
                ':created' => date('Y-m-d H:i:s'),
                ':modified' => date('Y-m-d H:i:s')
            ));

            header('Location: /index.php?page=Posts');
            die();
        }

        echo $this->renderView('Comments/add');
    }
}
