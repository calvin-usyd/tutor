<?php
class courseCasual extends Main {
 
    public function __construct(DB\SQL $db) {
        parent::__construct($db,'tutor_course_casual', null); 
    }
}