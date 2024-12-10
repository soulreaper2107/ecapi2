<?php

// Import dependencies

require_once "C:/xampp/htdocs/ecAPI/config/database.php";
require_once "C:/xampp/htdocs/ecAPI/modules/Get.php";
require_once "C:/xampp/htdocs/ecAPI/modules/Post.php";
require_once "C:/xampp/htdocs/ecAPI/modules/patch.php";
require_once "C:/xampp/htdocs/ecAPI/modules/archive.php";
require_once "C:/xampp/htdocs/ecAPI/modules/Auth.php";
require_once "C:/xampp/htdocs/ecAPI/modules/Common.php";

$db = new Connection();
$pdo = $db->connect();

// Class instantiation
$post = new Post($pdo);
$get = new Get($pdo);
$patch = new Patch($pdo);
$archive = new Archive($pdo);
$auth = new Authentication($pdo);

// Retrieve request and split
if (isset($_REQUEST['request'])) {
    $request = explode("/", $_REQUEST['request']);
} else {
    echo "URL does not exist.";
    exit;
}

// Handle different HTTP methods
switch ($_SERVER['REQUEST_METHOD']) {

    case "GET":
        if ($auth->isAuthorized()) {
            switch ($request[0]) {
                case "users":
                    echo $get->prettyPrint($get->getUsers());
                    break;

                case "sellers":
                    echo $get->prettyPrint($get->getSellers());
                    break;

                case "products":
                    echo $get->prettyPrint($get->getProducts());
                    break;

                case "cart":
                    if (isset($request[1])) {
                        echo $get->prettyPrint($get->getCart($request[1]));
                    } else {
                        http_response_code(400);
                        echo $get->prettyPrint(["error" => "Username is required"]);
                    }
                    break;

                case "product":
                    if (isset($request[1])) {
                        echo $get->prettyPrint($get->getProductById($request[1]));
                    } else {
                        http_response_code(400);
                        echo $get->prettyPrint(["error" => "Product ID is required"]);
                    }
                    break;

                default:
                    http_response_code(404);
                    echo $get->prettyPrint(["error" => "Invalid endpoint"]);
                    break;
            }
        } else {
            echo $get->prettyPrint(["error" => "Unauthorized"]);
        }
        break;

    case "POST":
        $body = json_decode(file_get_contents("php://input"), true);
        if ($request[0] === "login" || $request[0] === "signup") {
            if ($request[0] === "login") {
                echo $get->prettyPrint($auth->login($body));
            } elseif ($request[0] === "signup") {
                echo $get->prettyPrint($auth->addAccount($body));
            }
        } else if ($auth->isAuthorized()) {
            switch ($request[0]) {
                case "user":
                    echo $get->prettyPrint($post->postUser($body));
                    break;

                case "seller":
                    echo $get->prettyPrint($post->postSeller($body));
                    break;

                case "product":
                    echo $get->prettyPrint($post->postProduct($body));
                    break;

                case "cart":
                    echo $get->prettyPrint($post->postCart($body));
                    break;

                default:
                    http_response_code(404);
                    echo $get->prettyPrint(["error" => "Invalid endpoint"]);
                    break;
            }
        } else {
            echo $get->prettyPrint(["error" => "Unauthorized"]);
        }
        break;

    case "DELETE":
        if ($auth->isAuthorized()) {
            switch ($request[0]) {
                case "user":
                    echo $get->prettyPrint($archive->deleteUser($request[1]));
                    break;

                case "seller":
                    echo $get->prettyPrint($archive->deleteSeller($request[1]));
                    break;

                case "product":
                    echo $get->prettyPrint($archive->deleteProduct($request[1]));
                    break;

                case "cart":
                    echo $get->prettyPrint($archive->deleteCartItem($request[1]));
                    break;

                default:
                    http_response_code(404);
                    echo $get->prettyPrint(["error" => "Invalid endpoint"]);
                    break;
            }
        } else {
            echo $get->prettyPrint(["error" => "Unauthorized"]);
        }
        break;

    case "PATCH":
        $body = json_decode(file_get_contents("php://input"));
        if ($auth->isAuthorized()) {
            switch ($request[0]) {
                case "user":
                    if (isset($request[1])) {
                        echo $get->prettyPrint($patch->updateUser($request[1], $body));
                    } else {
                        http_response_code(400);
                        echo $get->prettyPrint(["error" => "User ID is required"]);
                    }
                    break;

                case "seller":
                    if (isset($request[1])) {
                        echo $get->prettyPrint($patch->updateSeller($request[1], $body));
                    } else {
                        http_response_code(400);
                        echo $get->prettyPrint(["error" => "Seller ID is required"]);
                    }
                    break;

                case "product":
                    if (isset($request[1])) {
                        echo $get->prettyPrint($patch->updateProduct($request[1], $body));
                    } else {
                        http_response_code(400);
                        echo $get->prettyPrint(["error" => "Product ID is required"]);
                    }
                    break;

                case "cart":
                    if (isset($request[1])) {
                        echo $get->prettyPrint($patch->updateCart($request[1], $body));
                    } else {
                        http_response_code(400);
                        echo $get->prettyPrint(["error" => "Cart ID is required"]);
                    }
                    break;

                default:
                    http_response_code(404);
                    echo $get->prettyPrint(["error" => "Invalid endpoint"]);
                    break;
            }
        } else {
            echo $get->prettyPrint(["error" => "Unauthorized"]);
        }
        break;

    default:
        http_response_code(400);
        echo $get->prettyPrint(["error" => "Invalid Request Method"]);
        break;
}

$pdo = null;

?>