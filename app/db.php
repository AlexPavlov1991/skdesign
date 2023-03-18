<?php

try {
    $db = new PDO('mysql:host=mariadb;dbname=skdesign', 'skdesign', 'skdesign');
} catch (PDOException $e) {
    $db_exception_message = 'Ошибка бд: ' . $e->getMessage(); // в ответ передавать эту информацию не нужно
    // $db_exception_message = 'Ошибка бд';
    $db = null;
}