<?php
declare(strict_types=1);

require '../models/BankAccount.php';
require '../models/User.php';

require '../components/DatabaseConnection.php';
require '../components/EmailSender.php';

use PHPUnit\Framework\TestCase;

class BankAccountTest extends TestCase
{
    private $amount, $owner, $emailSender, $dbConnection, $bankAccount;

    protected function setUp(): void
    {
        $this->amount = 50;
        $this->owner = new User("leyla.lenoan@outlook.fr", "LE NOAN", "Leyla", 22);

        $this->emailSender = $this->createMock(EmailSender::class);
        $this->emailSender->expects($this->any())->method('sendEmail')->will($this->returnValue(true));

        $this->dbConnection = $this->createMock(DatabaseConnection::class);
        $this->dbConnection->expects($this->any())->method('saveBankAccount')->will($this->returnValue(true));

        $this->bankAccount = new BankAccount(
            $this->amount,
            $this->owner,
            $this->emailSender,
            $this->dbConnection
        );
    }

    protected function tearDown(): void
    {
        $this->amount = null;
        $this->owner = null;
        $this->emailSender = null;
        $this->dbConnection = null;
    }

    public function testEmailSenderMock()
    {
        $emailSenderMock = $this->getMockBuilder(EmailSender::class)->disableOriginalConstructor()->getMock();
        $emailSenderMock->method('sendEmail')->willReturn(true);
        $this->assertEquals(true, $emailSenderMock->sendEmail("toto.test@outlook.fr", "Contenu de l'e-mail"));
    }

    public function testIsCreditWithoutRest(): void
    {
        $result = $this->bankAccount->credit(50);
        $this->assertNotNull($result);
        $this->assertEquals($result['amount'], 100);
        $this->assertEquals($result['add'], 50);
        $this->assertEquals($result['rest'], 0);
    }

    public function testIsCreditWithRest(): void
    {
        $result = $this->bankAccount->credit(960);
        $this->assertNotNull($result);
        $this->assertEquals($result['amount'], 1000);
        $this->assertEquals($result['add'], 950);
        $this->assertEquals($result['rest'], 10);
    }

    public function testIsValidAmountNominal(): void
    {
        $result = $this->bankAccount->isValidAmount();
        $this->assertTrue($result);
    }

    public function testIsValidNominal(): void
    {
        $result = $this->bankAccount->isValid();
        $this->assertTrue($result);
    }

    public function testIsNotValid(): void
    {
        $owner = $this->createMock(User::class);
        $owner
            ->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(false));
        $this->bankAccount->setOwner($owner);
        $result = $this->bankAccount->isValid();
        $this->assertFalse($result);
    }

    public function testSaveNominal(): void
    {
        $result = $this->bankAccount->save();
        $this->assertTrue($result);
    }
}