<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    private PDO $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function createOrder(array $data): array {
        // data minimal: session_id, order_code, customer_name, customer_phone, ...
        $sql = "INSERT INTO orders
                (session_id, order_code, customer_name, customer_phone, customer_email,
                 delivery_address, delivery_date, delivery_time, notes,
                 payment_method, bank, cart_total, service_fee, grand_total, status)
                VALUES
                (:session_id, :order_code, :customer_name, :customer_phone, :customer_email,
                 :delivery_address, :delivery_date, :delivery_time, :notes,
                 :payment_method, :bank, :cart_total, :service_fee, :grand_total, :status)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':session_id'        => $data['session_id'],
            ':order_code'        => $data['order_code'],
            ':customer_name'     => $data['customer_name'],
            ':customer_phone'    => $data['customer_phone'],
            ':customer_email'    => $data['customer_email'] ?? null,
            ':delivery_address'  => $data['delivery_address'],
            ':delivery_date'     => $data['delivery_date'] ?? null,
            ':delivery_time'     => $data['delivery_time'] ?? null,
            ':notes'             => $data['notes'] ?? null,
            ':payment_method'    => $data['payment_method'] ?? 'cod',
            ':bank'              => $data['bank'] ?? null,
            ':cart_total'        => $data['cart_total'],
            ':service_fee'       => $data['service_fee'],
            ':grand_total'       => $data['grand_total'],
            ':status'            => $data['status'] ?? 'Menunggu Konfirmasi',
        ]);

        $orderId = (int)$this->conn->lastInsertId();
        return ['id' => $orderId, 'order_code' => $data['order_code']];
    }

    public function addOrderItem(int $orderId, array $it): void {
        $sql = "INSERT INTO order_items
                (order_id, item_type, item_id, item_name, item_price, qty, subtotal)
                VALUES
                (:order_id, :item_type, :item_id, :item_name, :item_price, :qty, :subtotal)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':order_id'   => $orderId,
            ':item_type'  => $it['item_type'],
            ':item_id'    => (int)$it['item_id'],
            ':item_name'  => $it['item_name'],
            ':item_price' => (float)$it['item_price'],
            ':qty'        => (int)$it['quantity'],
            ':subtotal'   => (float)$it['item_price'] * (int)$it['quantity'],
        ]);
    }

    public function getOrdersBySession(string $sessionId): array {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE session_id = :sid ORDER BY id DESC");
        $stmt->execute([':sid' => $sessionId]);
        return $stmt->fetchAll();
    }

    public function getOrderByCode(string $orderCode, string $sessionId): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE order_code = :code AND session_id = :sid LIMIT 1");
        $stmt->execute([':code' => $orderCode, ':sid' => $sessionId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getOrderItems(int $orderId): array {
        $stmt = $this->conn->prepare("SELECT * FROM order_items WHERE order_id = :oid ORDER BY id ASC");
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetchAll();
    }

    public function getSalesTopItems(int $limit = 10): array {
        $sql = "SELECT item_name,
                       SUM(qty) as total_qty,
                       SUM(subtotal) as total_revenue
                FROM order_items
                GROUP BY item_name
                ORDER BY total_qty DESC
                LIMIT :lim";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
