<?php
include_once "Common.php";

class Archive extends Common {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Delete a user by username
    public function deleteUser($username) {
        try {
            $deleteUser = "DELETE FROM user_tbl WHERE username = ?";
            $stmt = $this->pdo->prepare($deleteUser);
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                $this->logger(parent::getLoggedInUsername(), "DELETE", "User '$username' deleted successfully.");
                return $this->generateResponse(null, "success", "User '$username' deleted successfully.", 200);
            } else {
                return $this->generateResponse(null, "failed", "User '$username' not found.", 404);
            }
        } catch (\Exception $e) {
            $this->logger(parent::getLoggedInUsername(), "DELETE", "Error deleting user '$username': " . $e->getMessage());
            return $this->generateResponse(null, "failed", "Error deleting user: " . $e->getMessage(), 500);
        }
    }

    // Delete a seller by username
    public function deleteSeller($username) {
        try {
            $deleteSeller = "DELETE FROM seller_tbl WHERE username = ?";
            $stmt = $this->pdo->prepare($deleteSeller);
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                $this->logger(parent::getLoggedInUsername(), "DELETE", "Seller '$username' deleted successfully.");
                return $this->generateResponse(null, "success", "Seller '$username' deleted successfully.", 200);
            } else {
                return $this->generateResponse(null, "failed", "Seller '$username' not found.", 404);
            }
        } catch (\Exception $e) {
            $this->logger(parent::getLoggedInUsername(), "DELETE", "Error deleting seller '$username': " . $e->getMessage());
            return $this->generateResponse(null, "failed", "Error deleting seller: " . $e->getMessage(), 500);
        }
    }

    // Delete a product by product ID
    public function deleteProduct($productId) {
        try {
            $deleteProduct = "DELETE FROM product_tbl WHERE productid = ?";
            $stmt = $this->pdo->prepare($deleteProduct);
            $stmt->execute([$productId]);

            if ($stmt->rowCount() > 0) {
                $this->logger(parent::getLoggedInUsername(), "DELETE", "Product with ID '$productId' deleted successfully.");
                return $this->generateResponse(null, "success", "Product with ID '$productId' deleted successfully.", 200);
            } else {
                return $this->generateResponse(null, "failed", "Product with ID '$productId' not found.", 404);
            }
        } catch (\Exception $e) {
            $this->logger(parent::getLoggedInUsername(), "DELETE", "Error deleting product with ID '$productId': " . $e->getMessage());
            return $this->generateResponse(null, "failed", "Error deleting product: " . $e->getMessage(), 500);
        }
    }

    // Delete an item from the cart by cart ID
    public function deleteCartItem($cartId) {
        try {
            $deleteCartItem = "DELETE FROM cart_tbl WHERE cartid = ?";
            $stmt = $this->pdo->prepare($deleteCartItem);
            $stmt->execute([$cartId]);

            if ($stmt->rowCount() > 0) {
                $this->logger(parent::getLoggedInUsername(), "DELETE", "Cart item with ID '$cartId' deleted successfully.");
                return $this->generateResponse(null, "success", "Cart item with ID '$cartId' deleted successfully.", 200);
            } else {
                return $this->generateResponse(null, "failed", "Cart item with ID '$cartId' not found.", 404);
            }
        } catch (\Exception $e) {
            $this->logger(parent::getLoggedInUsername(), "DELETE", "Error deleting cart item with ID '$cartId': " . $e->getMessage());
            return $this->generateResponse(null, "failed", "Error deleting cart item: " . $e->getMessage(), 500);
        }
    }
}
?>