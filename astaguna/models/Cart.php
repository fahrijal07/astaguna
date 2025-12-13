<?php
// models/Cart.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session_helper.php';

class Cart {
    private $conn;
    private $table = "cart";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Add item to cart
    public function addToCart($session_id, $item_type, $item_id, $quantity = 1, $notes = '') {
        // Check if item already exists in cart
        $query = "SELECT id, quantity FROM " . $this->table . " 
                  WHERE session_id = :session_id AND item_type = :item_type AND item_id = :item_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':session_id', $session_id);
        $stmt->bindParam(':item_type', $item_type);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update quantity
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $new_quantity = $row['quantity'] + $quantity;
            
            $updateQuery = "UPDATE " . $this->table . " 
                           SET quantity = :quantity, notes = :notes 
                           WHERE id = :id";
            
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':quantity', $new_quantity);
            $updateStmt->bindParam(':notes', $notes);
            $updateStmt->bindParam(':id', $row['id']);
            $result = $updateStmt->execute();
        } else {
            // Insert new item
            $insertQuery = "INSERT INTO " . $this->table . " 
                           (session_id, item_type, item_id, quantity, notes) 
                           VALUES (:session_id, :item_type, :item_id, :quantity, :notes)";
            
            $insertStmt = $this->conn->prepare($insertQuery);
            $insertStmt->bindParam(':session_id', $session_id);
            $insertStmt->bindParam(':item_type', $item_type);
            $insertStmt->bindParam(':item_id', $item_id);
            $insertStmt->bindParam(':quantity', $quantity);
            $insertStmt->bindParam(':notes', $notes);
            $result = $insertStmt->execute();
        }
        
        // Update session cart count
        $cartCount = $this->getCartCount($session_id);
        updateCartCount($cartCount);
        
        return $result;
    }

    // Get cart items
    public function getCartItems($session_id) {
        $query = "SELECT c.*, 
                  CASE 
                    WHEN c.item_type = 'paket' THEN p.name
                    WHEN c.item_type = 'item' THEN mi.name
                  END as item_name,
                  CASE 
                    WHEN c.item_type = 'paket' THEN COALESCE(p.price,0)
                    WHEN c.item_type = 'item' THEN mi.price
                  END as item_price
                  FROM " . $this->table . " c
                  LEFT JOIN menu_paket p ON c.item_type = 'paket' AND c.item_id = p.id
                  LEFT JOIN menu_items mi ON c.item_type = 'item' AND c.item_id = mi.id
                  WHERE c.session_id = :session_id
                  ORDER BY c.added_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':session_id', $session_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update cart item quantity
    public function updateCartItem($cart_id, $quantity, $session_id) {
        $query = "UPDATE " . $this->table . " 
                  SET quantity = :quantity 
                  WHERE id = :id AND session_id = :session_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':id', $cart_id);
        $stmt->bindParam(':session_id', $session_id);
        $result = $stmt->execute();
        
        // Update session cart count
        $cartCount = $this->getCartCount($session_id);
        updateCartCount($cartCount);
        
        return $result;
    }

    // Remove item from cart
    public function removeFromCart($cart_id, $session_id) {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE id = :id AND session_id = :session_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $cart_id);
        $stmt->bindParam(':session_id', $session_id);
        $result = $stmt->execute();
        
        // Update session cart count
        $cartCount = $this->getCartCount($session_id);
        updateCartCount($cartCount);
        
        return $result;
    }

    // Clear cart
    public function clearCart($session_id) {
        $query = "DELETE FROM " . $this->table . " WHERE session_id = :session_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':session_id', $session_id);
        $result = $stmt->execute();
        
        updateCartCount(0);
        return $result;
    }

    // Get cart total
    public function getCartTotal($session_id) {
        $items = $this->getCartItems($session_id);
        $total = 0;
        
        foreach ($items as $item) {
            if (isset($item['item_price'])) {
                $total += $item['item_price'] * $item['quantity'];
            }
        }
        
        return $total;
    }

    // Get cart count
    public function getCartCount($session_id) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE session_id = :session_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':session_id', $session_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
?>