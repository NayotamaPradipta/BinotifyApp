<?php
    function paginate($pdo, $table, $orderBy, $current_page) { 
        $current_page = max($current_page, 1); 
        $items_per_page = 5; 
        $offset = ($current_page - 1) * $items_per_page;

        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $total_items = $stmt->fetchColumn();

        $total_pages = ceil($total_items / $items_per_page);

        $stmt = $pdo->prepare("SELECT * FROM $table ORDER BY $orderBy LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'items' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total_pages' => $total_pages,
            'current_page' => $current_page
        ];
    }