<?php
class casualAcademic extends Main {
 
    public function __construct(DB\SQL $db) {
        parent::__construct($db,'tutor_casual_academic', null); 
    }
}