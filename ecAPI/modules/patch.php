<?php
include_once "Common.php";

class Patch extends Common {

    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function updateUser($id, $body) {
        try {
            $fields = [];
            $values = [];

            if (!empty($body->username)) {
                $fields[] = "username = ?";
                $values[] = $body->username;
            }
            if (!empty($body->password)) {
                $fields[] = "password = ?";
                $values[] = password_hash($body->password, PASSWORD_BCRYPT); // Use secure password hashing
            }
            if (!empty($body->email)) {
                $fields[] = "email = ?";
                $values[] = $body->email;
            }

            $values[] = $id;

            $sql = "UPDATE user_tbl SET " . implode(", ", $fields) . " WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);

            return ["message" => "User updated successfully", "code" => 200];
        } catch (\Exception $e) {
            return ["error" => "An error occurred while updating the user: " . $e->getMessage(), "code" => 400];
        }
    }

    public function updateSeller($id, $body) {
        try {
            $fields = [];
            $values = [];

            if (!empty($body->username)) {
                $fields[] = "username = ?";
                $values[] = $body->username;
            }
            if (!empty($body->password)) {
                $fields[] = "password = ?";
                $values[] = password_hash($body->password, PASSWORD_BCRYPT); // Use secure password hashing
            }
            if (!empty($body->email)) {
                $fields[] = "email = ?";
                $values[] = $body->email;
            }

            $values[] = $id;

            $sql = "UPDATE seller_tbl SET " . implode(", ", $fields) . " WHERE sellerid = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);

            return ["message" => "Seller updated successfully", "code" => 200];
        } catch (\Exception $e) {
            return ["error" => "An error occurred while updating the seller: " . $e->getMessage(), "code" => 400];
        }
    }

    public function updateProduct($id, $body) {
        try {
            $fields = [];
            $values = [];

            if (!empty($body->productname)) {
                $fields[] = "productname = ?";
                $values[] = $body->productname;
            }
            if (!empty($body->productprize)) {
                $fields[] = "productprize = ?";
                $values[] = $body->productprize;
            }
            if (!empty($body->productowner)) {
                $fields[] = "productowner = ?";
                $values[] = $body->productowner;
            }

            $values[] = $id;

            $sql = "UPDATE product_tbl SET " . implode(", ", $fields) . " WHERE productid = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);

            return ["message" => "Product updated successfully", "code" => 200];
        } catch (\Exception $e) {
            return ["error" => "An error occurred while updating the product: " . $e->getMessage(), "code" => 400];
        }
    }

    public function updateCart($id, $body) {
        try {
            $fields = [];
            $values = [];

            if (!empty($body->username)) {
                $fields[] = "username = ?";
                $values[] = $body->username;
            }
            if (!empty($body->productid)) {
                $fields[] = "productid = ?";
                $values[] = $body->productid;
            }
            if (!empty($body->sellername)) {
                $fields[] = "sellername = ?";
                $values[] = $body->sellername;
            }
            if (!empty($body->quantity)) {
                $fields[] = "quantity = ?";
                $values[] = $body->quantity;
            }

            $values[] = $id;

            $sql = "UPDATE cart_tbl SET " . implode(", ", $fields) . " WHERE cartid = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);

            return ["message" => "Cart updated successfully", "code" => 200];
        } catch (\Exception $e) {
            return ["error" => "An error occurred while updating the cart: " . $e->getMessage(), "code" => 400];
        }
    }
}
?>
