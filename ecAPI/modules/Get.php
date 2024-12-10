<?php
include_once "Common.php";

class Get extends Common {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getUsers() {
        $result = $this->getDataByTable('user_tbl', "1=1", $this->pdo);

        if ($result['code'] === 200) {
            return $this->generateResponse($result['data'], "success", "Successfully retrieved users.", $result['code']);
        }

        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);
    }

    public function getSellers() {
        $result = $this->getDataByTable('seller_tbl', "1=1", $this->pdo);

        if ($result['code'] === 200) {
            return $this->generateResponse($result['data'], "success", "Successfully retrieved sellers.", $result['code']);
        }

        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);
    }

    public function getProducts() {
        $sqlString = "SELECT 
                        p.productid, 
                        p.productname, 
                        p.productprize, 
                        s.username AS productowner
                    FROM 
                        product_tbl p
                    JOIN 
                        seller_tbl s ON p.productowner = s.username";

        $result = $this->getDataBySQL($sqlString, $this->pdo);

        if ($result['code'] === 200) {
            return $this->generateResponse($result['data'], "success", "Successfully retrieved products.", $result['code']);
        }

        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);
    }

    public function getCart($username) {
        $sqlString = "SELECT 
                        c.cartid, 
                        c.username, 
                        c.productid, 
                        p.productname, 
                        p.productprize, 
                        c.sellername, 
                        c.quantity
                    FROM 
                        cart_tbl c
                    JOIN 
                        product_tbl p ON c.productid = p.productid
                    JOIN 
                        seller_tbl s ON c.sellername = s.username
                    WHERE 
                        c.username = :username";

        $stmt = $this->pdo->prepare($sqlString);
        $stmt->execute([':username' => $username]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $this->generateResponse($result, "success", "Successfully retrieved cart for user.", 200);
        }

        return $this->generateResponse(null, "failed", "No records found.", 404);
    }

    public function getProductById($productId) {
        $sqlString = "SELECT 
                        p.productid, 
                        p.productname, 
                        p.productprize, 
                        s.username AS productowner
                    FROM 
                        product_tbl p
                    JOIN 
                        seller_tbl s ON p.productowner = s.username
                    WHERE 
                        p.productid = :productId";

        $stmt = $this->pdo->prepare($sqlString);
        $stmt->execute([':productId' => $productId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $this->generateResponse($result, "success", "Successfully retrieved product details.", 200);
        }

        return $this->generateResponse(null, "failed", "No product found.", 404);
    }
}
?>
