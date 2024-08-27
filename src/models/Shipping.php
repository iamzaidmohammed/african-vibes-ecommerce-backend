<?php

namespace app\Models;

use app\Config\Dbh;

class Shipping extends Dbh
{

    // Check if an address already exists for a user
    public function addressExists($userId)
    {
        $sql = "SELECT * FROM addresses 
                 WHERE user_id = :userId";

        $params = [':userId' => $userId];

        return $this->fetch($sql, $params);
    }

    // Insert a new address for a user
    public function createAddress($userId, $detailedAddress, $country, $province, $city, $postalCode)
    {
        $sql = "INSERT INTO addresses (user_id, detailed_address, country, province, city, postal_code) VALUES (:userId, :detailedAddress, :country, :province, :city, :postalCode)";

        $params = [
            ':userId' => $userId,
            ':detailedAddress' => $detailedAddress,
            ':country' => $country,
            ':province' => $province,
            ':city' => $city,
            ':postalCode' => $postalCode
        ];

        return $this->execute($sql, $params);
    }

    // Update an existing address for a user
    public function updateAddress($userId, $detailedAddress, $country, $province, $city, $postalCode)
    {
        $sql = "UPDATE addresses SET detailed_address = :detailedAddress, country = :country, province = :province, city = :city, postal_code = :postalCode WHERE user_id = :userId";

        $params = [
            ':userId' => $userId,
            ':detailedAddress' => $detailedAddress,
            ':country' => $country,
            ':province' => $province,
            ':city' => $city,
            ':postalCode' => $postalCode
        ];

        return $this->execute($sql, $params);
    }

    // Get address of a user
    public function getUserAddress($userId)
    {
        $sql = "SELECT u.user_id, u.first_name, u.last_name, u.email, u.phone, ad.detailed_address, ad.country, ad.province, ad.city, ad.postal_code 
        FROM addresses ad
        JOIN users u ON u.user_id = ad.user_id 
        HAVING user_id = :userId";
        $params = [':userId' => $userId];
        return $this->fetch($sql, $params);
    }
}
