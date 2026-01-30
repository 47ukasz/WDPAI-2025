<?php 

class User {
    private $id;
    private $firstname;
    private $lastname;
    private $email;

    public function __construct($id, $firstname, $lastname, $email) {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public function getId() { return $this->id; }
    public function getFirstName() { return $this->firstname; }
    public function getLastName() { return $this->lastname; }
    public function getEmail() { return $this->email; }
    public function getFullName() {
        return $this->firstname . " " . $this->lastname;
    }
}