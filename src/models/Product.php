<?php

class Product
{
    private $nom, $owner;

    public function __construct(
        string $nom,
        User $owner
    ) {
        $this->nom = $nom;
        $this->owner = $owner;
    }
    
    public function isValid()
    {
        return !empty($this->nom)
            && isset($this->owner)
            && $this->owner->isValid();
    }

    /**
     * Get the value of nom
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNom($nom)
    {
        $this->nom = $nom;

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
}