<?php

namespace app\Controllers;

use app\Models\Shipping;

class ShippingController
{
    private $shippingModel;
    public function __construct()
    {
        $this->shippingModel = new Shipping();
    }

    // Check if address exists
    public function checkAddress($userId)
    {
        return $this->shippingModel->addressExists($userId);
    }

    // Insert a new address for a user
    public function createAddress($userId, $detailedAddress, $country, $province, $city, $postalCode)
    {
        return $this->shippingModel->createAddress($userId, $detailedAddress, $country, $province, $city, $postalCode);
    }

    // Update user address
    public function updateAddress($userId, $detailedAddress, $country, $province, $city, $postalCode)
    {
        return $this->shippingModel->updateAddress($userId, $detailedAddress, $country, $province, $city, $postalCode);
    }

    // Get address of a user
    public function getUserAddress($userId)
    {
        $address = $this->shippingModel->getUserAddress($userId);

        $result = [];
        if ($address) {
            $result[] = [
                'userId' => $address['user_id'],
                'firstName' => $address['first_name'],
                'lastName' => $address['last_name'],
                'email' => $address['email'],
                'phone' => $address['phone'],
                'detailedAddress' => $address['detailed_address'],
                'country' => $address['country'],
                'province' => $address['province'],
                'city' => $address['city'],
                'postalCode' => $address['postal_code'],
            ];

            return $result;
        } else {
            return [];
        }
    }
}
