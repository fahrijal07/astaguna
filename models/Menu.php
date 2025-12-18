<?php
// models/Menu.php
require_once __DIR__ . '/../config/database.php';

class Menu {
    private $conn;
    private $table_menu = "menu_items";
    private $table_paket = "menu_paket";
    private $table_paket_items = "paket_items";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get all menu items by category
    public function getMenuItems($category = null) {
        $query = "SELECT * FROM " . $this->table_menu . " WHERE is_active = 1";
        
        if ($category) {
            $query .= " AND category = :category";
        }
        
        $query .= " ORDER BY category, name";
        
        $stmt = $this->conn->prepare($query);
        
        if ($category) {
            $stmt->bindParam(':category', $category);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all paket menus
    public function getPaketMenus() {
        $query = "SELECT * FROM " . $this->table_paket . " WHERE is_available = 1 ORDER BY id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get paket items by paket_id
    public function getPaketItems($paket_id) {
        $query = "SELECT mi.*, pi.quantity 
                  FROM " . $this->table_paket_items . " pi
                  JOIN " . $this->table_menu . " mi ON pi.menu_item_id = mi.id
                  WHERE pi.paket_id = :paket_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':paket_id', $paket_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>