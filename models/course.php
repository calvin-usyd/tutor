<?php
class course extends Main {
 
    public function __construct(DB\SQL $db) {
        parent::__construct($db,'tutor_course', null); 
    }
}