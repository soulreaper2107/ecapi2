<?php

include_once "Common.php";
include_once "Auth.php";

class Post extends Common {
    protected $pdo;
    protected $auth;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
        $this->auth = new Authentication($pdo); // Initialize Authentication instance
    }

    // Add a new user
    public function postUser($body) {
        if (is_array($body)) {
            $body = (object) $body;
        }

        if (empty($body->username) || empty($body->password) || empty($body->email)) {
            return $this->generateResponse(null, "failed", "Username, password, and email are required.", 400);
        }

        try {
            $hashedPassword = password_hash($body->password, PASSWORD_BCRYPT);
            $result = $this->postData("user_tbl", [
                "username" => $body->username,
                "password" => $hashedPassword,
                "email" => $body->email
            ], $this->pdo);

            if ($result['code'] == 200) {
                $this->logger(parent::getLoggedInUsername(), "POST", "New user registered: {$body->username}.");
                return $this->generateResponse($result['data'], "success", "User registered successfully.", 201);
            }

            return $this->generateResponse(null, "failed", $result['errmsg'], 400);
        } catch (\Exception $e) {
            return $this->generateResponse(null, "failed", $e->getMessage(), 500);
        }
    }

    // Add a new seller
    public function postSeller($body) {
        if (is_array($body)) {
            $body = (object) $body;
        }

        if (empty($body->username) || empty($body->password) || empty($body->email)) {
            return $this->generateResponse(null, "failed", "Username, password, and email are required.", 400);
        }

        try {
            $hashedPassword = password_hash($body->password, PASSWORD_BCRYPT);
            $result = $this->postData("seller_tbl", [
                "username" => $body->username,
                "password" => $hashedPassword,
                "email" => $body->email
            ], $this->pdo);

            if ($result['code'] == 200) {
                $this->logger(parent::getLoggedInUsername(), "POST", "New seller registered: {$body->username}.");
                return $this->generateResponse($result['data'], "success", "Seller registered successfully.", 201);
            }

            return $this->generateResponse(null, "failed", $result['errmsg'], 400);
        } catch (\Exception $e) {
            return $this->generateResponse(null, "failed", $e->getMessage(), 500);
        }
    }

    // Add a new product
    public function postProduct($body) {
        if (is_array($body)) {
            $body = (object) $body;
        }

        if (empty($body->productname) || empty($body->productprize) || empty($body->productowner)) {
            return $this->generateResponse(null, "failed", "Product name, price, and owner are required.", 400);
        }

        try {
            $result = $this->postData("product_tbl", [
                "productname" => $body->productname,
                "productprize" => $body->productprize,
                "productowner" => $body->productowner
            ], $this->pdo);

            if ($result['code'] == 200) {
                $this->logger(parent::getLoggedInUsername(), "POST", "New product added: {$body->productname}.");
                return $this->generateResponse($result['data'], "success", "Product added successfully.", 201);
            }

            return $this->generateResponse(null, "failed", $result['errmsg'], 400);
        } catch (\Exception $e) {
            return $this->generateResponse(null, "failed", $e->getMessage(), 500);
        }
    }

    // Add to cart
    public function postCart($body) {
        if (is_array($body)) {
            $body = (object) $body;
        }

        if (empty($body->username) || empty($body->productid) || empty($body->quantity)) {
            return $this->generateResponse(null, "failed", "Username, product ID, and quantity are required.", 400);
        }

        try {
            // Fetch product details
            $productQuery = "SELECT productname, productprize, productowner FROM product_tbl WHERE productid = :productid";
            $stmt = $this->pdo->prepare($productQuery);
            $stmt->execute([':productid' => $body->productid]);
            $product = $stmt->fetch();

            if (!$product) {
                return $this->generateResponse(null, "failed", "Product not found.", 404);
            }

            // Insert into cart
            $result = $this->postData("cart_tbl", [
                "username" => $body->username,
                "productid" => $body->productid,
                "productname" => $product['productname'],
                "productprize" => $product['productprize'],
                "sellername" => $product['productowner'],
                "quantity" => $body->quantity
            ], $this->pdo);

            if ($result['code'] == 200) {
                $this->logger(parent::getLoggedInUsername(), "POST", "Added product to cart for user: {$body->username}.");
                return $this->generateResponse($result['data'], "success", "Product added to cart.", 201);
            }

            return $this->generateResponse(null, "failed", $result['errmsg'], 400);
        } catch (\Exception $e) {
            return $this->generateResponse(null, "failed", $e->getMessage(), 500);
        }
    }
}

?>