<?php

class Item {
    private $id;
    private $user_full_name;
    private $price;
    private $title;
    private $phone_number;
    private $photo_path;
    private $description;
    private $created_at;

    public function __construct($id, $user_full_name, $title, $phone_number, $photo_path, $description, $created_at, $price) {
        $this->id = $id;
        $this->user_full_name = $user_full_name;
        $this->title = $title;
        $this->phone_number = $phone_number;
        $this->photo_path = $photo_path;
        $this->description = $description;
        $this->price = $price;

        $date = new DateTime($created_at);

        $days = [
            'Sunday' => 'niedziela',
            'Monday' => 'poniedziałek',
            'Tuesday' => 'wtorek',
            'Wednesday' => 'środa',
            'Thursday' => 'czwartek',
            'Friday' => 'piątek',
            'Saturday' => 'sobota',
        ];

        $months = [
            'January' => 'stycznia',
            'February' => 'lutego',
            'March' => 'marca',
            'April' => 'kwietnia',
            'May' => 'maja',
            'June' => 'czerwca',
            'July' => 'lipca',
            'August' => 'sierpnia',
            'September' => 'września',
            'October' => 'października',
            'November' => 'listopada',
            'December' => 'grudnia',
        ];

        $dayName = $days[$date->format('l')];
        $monthName = $months[$date->format('F')];

        $this->created_at = "{$dayName}, {$date->format('d')} {$monthName}";
    }

    public function getId() { return $this->id; }
    public function getUserFullName() { return $this->user_full_name; }
    public function getTitle() { return $this->title; }
    public function getPhoneNumber() { return $this->phone_number; }
    public function getPhotoPath() { return $this->photo_path; }
    public function getDescription() { return $this->description; }
    public function getCreatedAt() { return $this->created_at; }
    public function getPrice() { return $this->price; }
}