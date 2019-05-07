<?php
declare(strict_types=1);

require '../models/User.php';
require '../models/Product.php';

use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase {

    private $user, $product;

    protected function setUp(): void {
        $this->user = new User("leyla.lenoan@outlook.fr", "LE NOAN", "Leyla", 22);
        $this->product = new Product("Ordinateur", $this->user);
    }

    public function testIsValid() {
        $result = $this->product->isValid();
        $this->assertTrue($result);
    }

    public function testIsNomEmpty() {
        $this->product->setNom("");
        $result = $this->product->isValid();
        $this->assertFalse($result);
    }
}