<?php
// Parent Class
// This class is used to create a form data object with name and email properties
class ParentClass {
    public $name;
    public $email;

    public function __construct($name = '', $email = '') {
        $this->name = $name;
        $this->email = $email;
    }

    protected function JSONify() {
        return "\n\t{ \n\t\t\"Name\": \"" . $this->name . "\",\n\t\t\"Email\": \"" . $this->email . "\"\n\t}";
    }
}
?>