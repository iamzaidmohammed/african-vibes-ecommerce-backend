<?php

namespace app\Models;

use app\Config\Dbh;

class Payment extends Dbh
{
    public function addPayment($orderId, $reference, $method, $amount, $status, $date)
    {
        $sql = 'INSERT INTO payments (order_id, reference, payment_method, amount, payment_status, payment_date) VALUES (:orderId, :ref, :method, :amount, :status, :date)';
        $params = [
            ':orderId' => $orderId,
            ':ref' => $reference,
            ':method' => $method,
            ':amount' => $amount,
            ':status' => $status,
            ':date' => $date
        ];

        return $this->execute($sql, $params);
    }
}
