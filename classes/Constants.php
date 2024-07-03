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
    ];
}
