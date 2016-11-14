<?php
class casualProfile extends Main {
 
    public function __construct(DB\SQL $db) {
        parent::__construct($db,'tutor_casual_profile', null); 
    }
}