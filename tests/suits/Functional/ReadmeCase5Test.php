<?php declare(strict_types = 1);

namespace TestsSuites\Popo\Functional;

use App\Popo\Case5\Customer\Customer;
use App\Popo\Case5\Location\Address;
use App\Popo\Case5\Location\Street;
use App\Popo\Case5\Money\Currency;
use App\Popo\Case5\Money\Price;
use App\Popo\Case5\Order\Order;
use App\Popo\Case5\Order\OrderItem;
use App\Popo\Case5\Product\Product;
use PHPUnit\Framework\TestCase;

class ReadmeCase5Test extends TestCase
{
    public function test_customer_address_street(): void
    {
        $customer = (new Customer)
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('test@test.com')
            ->setAddress(
                (new Address)
                    ->setAddressName('home')
                    ->setRecipientName('Tom Doe')
                    ->setNotes('Lorem Ipsum')
                    ->setZipCode('ABC123')
                    ->setCountry('New World')
                    ->setStreet(
                        (new Street)
                            ->setName('Foobara')
                            ->setNumber('1B')
                            ->setFloor('1st')
                    )
            );

        $expected = [
            "id" => null,
            "firstName" => "John",
            "lastName" => "Doe",
            "email" => "test@test.com",
            "address" => [
                "addressName" => "home",
                "recipientName" => "Tom Doe",
                "street" => [
                    "name" => "Foobara",
                    "number" => "1B",
                    "floor" => "1st",
                ],
                "zipCode" => "ABC123",
                "country" => "New World",
                "notes" => "Lorem Ipsum",
            ],
        ];

        $this->assertEquals($expected, $customer->toArray());
        $this->assertEquals($expected, (new Customer())->fromArray($customer->toArray())->toArray());
    }

    public function test_product(): void
    {
        $product = (new Product)
            ->setName('Lorem Ipsum Blue')
            ->setId(1);

        $expected = [
            "id" => 1,
            "name" => "Lorem Ipsum Blue",
        ];

        $this->assertEquals($expected, $product->toArray());
        $this->assertEquals($expected, (new Product())->fromArray($product->toArray())->toArray());
    }

    public function test_order(): void
    {
        $order = (new Order)
            ->setCustomer(
                (new Customer)
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setEmail('test@test.com')
                    ->setAddress(
                        (new Address)
                            ->setAddressName('home')
                            ->setRecipientName('Tom Doe')
                            ->setNotes('Lorem Ipsum')
                            ->setZipCode('ABC123')
                            ->setCountry('New World')
                            ->setStreet(
                                (new Street)
                                    ->setName('Foobara')
                                    ->setNumber('1B')
                                    ->setFloor('1st')
                            )
                    )
            )
            ->setOrderLines(
                [
                    (new OrderItem)
                        ->setProduct(
                            (new Product)
                                ->setName('Lorem Ipsum Blue')
                                ->setId(1)
                        )
                        ->setPrice(
                            (new Price)
                                ->setValue(1234)
                                ->setCurrency(
                                    (new Currency)
                                        ->setName('Euro')
                                        ->setCode('EUR')
                                        ->setSymbol('€')
                                )
                        ),
                ]
            );

        $expected = [
            'id' => null,
            "orderLines" => [
                [
                    "id" => null,
                    "product" => [
                        "id" => 1,
                        "name" => "Lorem Ipsum Blue",
                    ],
                    "price" => [
                        "value" => 1234,
                        "currency" => [
                            "name" => "Euro",
                            "code" => "EUR",
                            "symbol" => "€",
                            "decimalSymbol" => ".",
                        ],
                    ],
                ],
            ],
            'customer' => [
                "id" => null,
                "firstName" => "John",
                "lastName" => "Doe",
                "email" => "test@test.com",
                "address" => [
                    "addressName" => "home",
                    "recipientName" => "Tom Doe",
                    "street" => [
                        "name" => "Foobara",
                        "number" => "1B",
                        "floor" => "1st",
                    ],
                    "zipCode" => "ABC123",
                    "country" => "New World",
                    "notes" => "Lorem Ipsum",
                ],
            ],
        ];

        $this->assertEquals($expected, $order->toArray());
        $this->assertEquals($expected, (new Order())->fromArray($order->toArray())->toArray());
    }
}
