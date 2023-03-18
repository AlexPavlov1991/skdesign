<?php

/**
 * Создание ответа
 */
function createResponse(
    int $status_code,
    ?string $status_message = null,
    ?array $data = null
):array
{
    $response = ['status_code' => $status_code];
    if ($status_message) {
        $response['status_message'] = $status_message;
    }
    if ($data) {
        $response['data'] = $data;
    }
    return $response;
}

/**
 * Печать ответа
 */
function printResponse(
    array $response
):void
{
    header('Content-type: json/application');
    http_response_code($response['status_code']);
    echo json_encode($response);
}

/**
 * Получаем цену доставки
 */
function getDeliveryPrice(
    string $place_of_departure, 
    string $place_of_destination
):int
{
    // Какой-то магический алгоритм вычисляющий цену доставки
    $delivery_price = rand(1, 10) * (strlen($place_of_departure) + strlen($place_of_destination));
    return $delivery_price;
}

/**
 * Создание заказа
 */
function createOrder(
    array $data,
):array
{
    global $db;
    $query = 'INSERT INTO `orders` (`client_id`, `seller_id`, `place_of_departure`, `place_of_destination`, `delivery_price`, `created_at`)'
             . ' VALUES (:client_id, :seller_id, :place_of_departure, :place_of_destination, :delivery_price, :created_at)';
    $sth = $db->prepare($query);
    $sth->execute($data);
    $data['id'] = $db->lastInsertId();
    return $data;
}

/**
 * Получаем заказ по id
 */
function getOrder(
    int $id
):array
{
    $order = getOrders([$id])[0] ?? [];
    return $order;
}

/**
 * Получаем список заказов
 */
function getOrders(
    ?array $ids = null
):array
{
    global $db;
    $query = 'SELECT * FROM `orders`';
    if ($ids) {
        $ids = array_map(function($id){return (int) $id;}, $ids);
        $query .= ' WHERE id in (' . implode(',', $ids) . ')';  
    }
    $orders = $db->query($query)->fetchAll();
    return $orders;
};
