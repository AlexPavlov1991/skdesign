Заказ с id = 1:
http://127.0.0.1/order/1

Спиcок заказов:
http://127.0.0.1/orders

Цена за доставку:
http://127.0.0.1/order?action=calculate_delivery_price&place_of_departure=London street 17&place_of_destination=Green street 55

Создание заказа:
http://127.0.0.1/order?action=create
POST:
{
    'client_id' => 1,
    'seller_id' => 2,
    'place_of_departure' => 'Red street 22',
    'place_of_destination' => 'Blue street 77',
    'delivery_price' => 355
}