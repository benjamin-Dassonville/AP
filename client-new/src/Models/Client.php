<?php

class Client {
    public $Numero_Client;
    public $Raison_Sociale;
    public $Siren;
    public $Code_Ape;
    public $Adresse;
    public $Telephone_Client;
    public $Email;
    public $Duree_Deplacement;
    public $Distance_KM;
    public $Numero_Agence;

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
