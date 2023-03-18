<?php

require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/functions.php');

if (!$db) {
    $response = createResponse(500, $db_exception_message);
    printResponse($response);
    die;
}

$redirect_url_array = !empty($_GET['q']) ? explode('/', $_GET['q']) : [];
$entity = !empty($redirect_url_array[0]) ? trim($redirect_url_array[0]) : null;
$id = !empty($redirect_url_array[1]) ? (int) $redirect_url_array[1] : null;
$action = !empty($_GET['action']) ? trim($_GET['action']) : null;
$method = $_SERVER['REQUEST_METHOD'];

switch ($entity) {
    case 'order':
        switch ($action) {
            case 'create':
                if (!isset($_POST['client_id'], $_POST['seller_id'], $_POST['place_of_departure'], $_POST['place_of_destination'], $_POST['delivery_price'])) {
                    $response = createResponse(400, 'Отсутствуют нужные данные для создания заказа');
                    break;
                }
                $data = [
                    'client_id' => (int) $_POST['client_id'],
                    'seller_id' => (int) $_POST['seller_id'],
                    'place_of_departure' => trim($_POST['place_of_departure']),
                    'place_of_destination' => trim($_POST['place_of_destination']),
                    'delivery_price' => (int) $_POST['delivery_price'],
                    'created_at' => date('Y-m-d H:i:s')
                ];
                try {
                    $data = createOrder($data);
                } catch (Exception $e) {
                    $response = createResponse(500, 'Ошибка создания заказа: ' . $e->getMessage());
                    printResponse($response);
                    die;
                }
                $response = createResponse(201, 'Заказ создан', $data);
                break;
            case "calculate_delivery_price":
                if (!isset($_GET['place_of_departure'], $_GET['place_of_destination'])) {
                    $response = createResponse(400, 'Отсутствуют нужные данные для рассчёта цены доставки');
                    break;
                }
                $place_of_departure = trim($_GET['place_of_departure']);
                $place_of_destination = trim($_GET['place_of_destination']);
                $delivery_price = getDeliveryPrice($place_of_departure, $place_of_destination);
                $response = createResponse(200, null, ['delivery_price' => $delivery_price]);
                break;
            default:
                if (!$id) {
                    $response = createResponse(400, 'Отсутствует id заказа');
                    break;
                }
                try {
                    $data = getOrder($id);
                } catch (Exception $e) {
                    $response = createResponse(500, 'Ошибка получения заказа: ' . $e->getMessage());
                    printResponse($response);
                    die;
                }
                $response = createResponse(200, null, $data);
                break;
        }
        break;
    case 'orders':
        try {
            $data = getOrders();
        } catch (Exception $e) {
            $response = createResponse(500, 'Ошибка получения заказов: ' . $e->getMessage());
            printResponse($response);
            die;
        }
        $response = createResponse(200, null, $data);
        break;
    default:
        $response = createResponse(400, 'Неправильный, некорректный запрос');
}

printResponse($response);
