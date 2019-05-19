<?php

final class BankAccount
{
    private $amount, $owner, $emailSender, $dbConnection;

    public function __construct(
        int $amount,
        User $owner,
        EmailSender $emailSender,
        DatabaseConnection $dbConnection
    ) {
        $this->amount = $amount;
        $this->owner = $owner;
        $this->emailSender = $emailSender;
        $this->dbConnection = $dbConnection;
    }

    public function credit(int $amount)
    {
        $newAmount = $this->amount + $amount;
        if ($newAmount > 1000) {
            $this->amount = 1000;
            $rest = $newAmount - 1000;
            $add = $amount - $rest;
            $transaction = array('amount' => $this->amount, 'add' => $add, 'rest' => $rest);
        } else {
            $this->amount = $newAmount;
            $transaction = array('amount' => $this->amount, 'add' => $amount, 'rest' => 0);
        }

        $this->dbConnection->saveBankAccount($this);
        $this->emailSender->sendEmail($this->owner->getEmail(), "Le solde de votre compte a été modifié ! Un crédit de " . $transaction['add'] . "€ a été fait ce jour. Votre nouveau solde est de " . $transaction['amount'] . "€.");

        return $transaction;
    }

    public function isValidAmount(): bool
    {
        return ($this->amount >= 0 && $this->amount <= 1000) ? true : false;
    }

    public function isValid()
    {
        return (!$this->owner || !$this->owner->isValid() || !$this->isValidAmount()) ? false : true;
    }

    public function save()
    {
        if (!$this->isValid()) {
            return false;
        }
        try {
            $this->dbConnection->saveUser($this->owner);
            $this->dbConnection->saveExchange($this);
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Get the value of amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set the value of amount
     *
     * @return  self
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the value of owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set the value of owner
     *
     * @return  self
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get the value of emailSender
     */
    public function getEmailSender()
    {
        return $this->emailSender;
    }

    /**
     * Set the value of emailSender
     *
     * @return  self
     */
    public function setEmailSender($emailSender)
    {
        $this->emailSender = $emailSender;

        return $this;
    }

    /**
     * Get the value of dbConnection
     */
    public function getDbConnection()
    {
        return $this->dbConnection;
    }

    /**
     * Set the value of dbConnection
     *
     * @return  self
     */
    public function setDbConnection($dbConnection)
    {
        $this->dbConnection = $dbConnection;

        return $this;
    }
}
