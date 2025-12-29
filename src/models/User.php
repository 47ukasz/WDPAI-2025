<?php 

class User {
    private $id;
    private $fistname;
    private $lastname;
    private $email;

    public function __construct($id, $fistname, $lastname, $email) {
        $this->id = $id;
        $this->fistname = $fistname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public function getId() { return $this->id; }
    public function getFirstName() { return $this->fistname; }
    public function getLastName() { return $this->lastname; }
    public function getEmail() { return $this->email; }
    public function getFullName() {
        return $this->fistname . " " . $this->lastname;
    }
}