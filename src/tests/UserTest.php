<?php
declare(strict_types=1);

require '../models/User.php';

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase {

    protected $user;

    protected function setUp(): void {
        $this->user = new User("leyla.lenoan@outlook.fr", "LE NOAN", "Leyla", 22);
    }

    public function testIsValid() {
        $result = $this->user->isValid();
        $this->assertTrue($result);
    }

    public function testIsEmailEmpty() {
        $this->user->setEmail("");
        $result = $this->user->isValid();
        $this->assertFalse($result);
    }

    public function testIsNomEmpty() {
        $this->user->setNom("");
        $result = $this->user->isValid();
        $this->assertFalse($result);
    }

    public function testIsPrenomEmpty() {
        $this->user->setPrenom("");
        $result = $this->user->isValid();
        $this->assertFalse($result);
    }

    public function testIsAgeEmpty() {
        $this->user->setAge("");
        $result = $this->user->isValid();
        $this->assertFalse($result);
    }

    public function testIsValidEmail() {
        $this->user->setEmail("test");
        $result = $this->user->isValid();
        $this->assertFalse($result);
    }

    public function testIsValidAgeNoNumber() {
        $this->user->setAge("test");
        $result = $this->user->isValid();
        $this->assertFalse($result);
    }

    public function testIsValidAge() {
        $this->user->setAge(11);
        $result = $this->user->isValid();
        $this->assertFalse($result);
    }
}