<?php
declare(strict_types=1);

require '../models/Exchange.php';
require '../models/Product.php';
require '../models/User.php';

require '../components/DatabaseConnection.php';
require '../components/EmailSender.php';

use PHPUnit\Framework\TestCase;

class ExchangeTest extends TestCase
{
    private $user, $product, $owner, $emailSender, $dbConnection, $exchange;

    protected function setUp(): void
    {
        $this->user = new User("leyla.lenoan@outlook.fr", "LE NOAN", "Leyla", 22);
        $this->owner = new User("alex.lenoan@outlook.fr", "LE NOAN", "Alexandre", 16);
        $this->product = new Product("Ordinateur", $this->owner);

        $this->emailSender = $this->createMock(EmailSender::class);
        $this->emailSender->expects($this->any())->method('sendEmail')->will($this->returnValue(true));

        $this->dbConnection = $this->createMock(DatabaseConnection::class);
        $this->dbConnection->expects($this->any())->method('saveExchange')->will($this->returnValue(true));

        $this->exchange = new Exchange(
            $this->user,
            $this->product,
            new DateTime('01-08-2019'),
            new DateTime('30-09-2019'),
            $this->emailSender,
            $this->dbConnection
        );
    }

    protected function tearDown(): void
    {
        $this->user = null;
        $this->owner = null;
        $this->product = null;
        $this->emailSender = null;
        $this->dbConnection = null;
    }

    public function testEmailSenderMock()
    {
        $emailSenderMock = $this->getMockBuilder(EmailSender::class)->disableOriginalConstructor()->getMock();
        $emailSenderMock->method('sendEmail')->willReturn(true);
        $this->assertEquals(true, $emailSenderMock->sendEmail("toto.test@outlook.fr", "Contenu de l'e-mail"));
    }

    public function testisValidDateNominal(): void
    {
        $result = $this->exchange->isValidDate();
        $this->assertTrue($result);
    }

    public function testisNotValidDateBecauseOfStartDateInPast(): void
    {
        $this->exchange->setStartDate(new DateTime('01-01-2000'));
        $result = $this->exchange->isValidDate();
        $this->assertFalse($result);
    }

    public function testisNotValidDateBecauseOfInversedDates(): void
    {
        $this->exchange->setStartDate(new DateTime('01-01-2020'));
        $this->exchange->setEndDate(new DateTime('01-01-2000'));
        $result = $this->exchange->isValidDate();
        $this->assertFalse($result);
    }

    public function testIsValidNominal(): void
    {
        $result = $this->exchange->isValid();
        $this->assertTrue($result);
    }

    public function testIsNotValid(): void
    {
        $receiver = $this->createMock(User::class);
        $receiver
            ->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(false));
        $this->exchange->setReceiver($receiver);
        $result = $this->exchange->isValid();
        $this->assertFalse($result);
    }

    public function testSaveNominal(): void
    {
        $result = $this->exchange->save();
        $this->assertTrue($result);
    }
}