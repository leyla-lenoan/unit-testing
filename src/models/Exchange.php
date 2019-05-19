<?php

final class Exchange
{
    private $receiver, $product, $startDate, $endDate, $emailSender, $dbConnection;

    public function __construct(
        User $receiver,
        Product $product,
        DateTime $startDate,
        DateTime $endDate,
        EmailSender $emailSender,
        DatabaseConnection $dbConnection
    ) {
        $this->receiver = $receiver;
        $this->product = $product;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->emailSender = $emailSender;
        $this->dbConnection = $dbConnection;
    }
    
    public function isValidDate(): bool {
        $now = new DateTime();
        $nowTimestamp = $now->getTimestamp();
        $endDateTimestamp = $this->endDate->getTimestamp();
        $startDateTimestamp = $this->startDate->getTimestamp();
        
        if ($endDateTimestamp - $startDateTimestamp <= 0 || $this->startDate->getTimestamp() < $nowTimestamp){
            return false;
        }
        return true;
    }

    public function isValid()
    {
        return (!$this->receiver || !$this->receiver->isValid() || !$this->product || !$this->product->isValid() || !$this->isValidDate()) ? false : true;
    }
    
    public function save()
    {
        if (!$this->isValid()) {
            return false;
        }
        try {
            $this->dbConnection->saveUser($this->receiver);
            $this->dbConnection->saveProduct($this->product);
            $this->dbConnection->saveExchange($this);
            if ($this->receiver->getAge() < 18) {
                $this->emailSender->sendEmail($this->receiver->getEmail(), "Vous êtes mineur, vous n\'êtes pas autorisé.");
            }
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Get the value of receiver
     */ 
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Set the value of receiver
     *
     * @return  self
     */ 
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get the value of product
     */ 
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set the value of product
     *
     * @return  self
     */ 
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get the value of startDate
     */ 
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set the value of startDate
     *
     * @return  self
     */ 
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get the value of endDate
     */ 
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set the value of endDate
     *
     * @return  self
     */ 
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

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
    public function getdbConnection()
    {
        return $this->dbConnection;
    }

    /**
     * Set the value of dbConnection
     *
     * @return  self
     */ 
    public function setdbConnection($dbConnection)
    {
        $this->dbConnection = $dbConnection;

        return $this;
    }
}