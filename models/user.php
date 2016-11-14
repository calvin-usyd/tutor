<?php
class user extends Main {
 
    public function __construct(DB\SQL $db) {
        parent::__construct($db,'tutor_users', null); 
    }
}