<?php
namespace FutureCMF\Core\Site;

require_once __DIR__ . "/../Application.php";

class Schema {

    public string $name;
    public array $fields;

    public function __construct(string $name) { }

}