<?php

namespace local_data_transfer;
/**
 * Data transfer global constants
 */
class Constants
{
    // Events type to identify what it is in pending commands table
    const EVENT_TYPES = [
        "COURSE_BASE_CREATED" => 1,
        "COURSE_SECTION_CREATED" => 2,
        "COURSE_GROUPS_CREATED" => 3,
        "COURSE_GROUPINGS_CREATED" => 4,
    ];
}
