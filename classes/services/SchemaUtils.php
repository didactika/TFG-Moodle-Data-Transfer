<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_data_transfer\services;

use \external_multiple_structure;
use \external_single_structure;
use \external_value;

/**
 * Class SchemaUtils
 *
 * @package    local_data_transfer
 * @copyright  2024 ADSDR-FUNIBER Scepter Team <accion.docente@ct.uneatlantico.es>
 * @author     Eduardo Estrada (e2rd0) <eduardo.estrada@ct.uneatlantico.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class SchemaUtils
{
  /**
   * Returns the question structure for reusability.
   *
   * @return external_single_structure
   */
  public static function get_question_structure(): external_single_structure
  {
    return new external_single_structure([
      'id' => new external_value(PARAM_INT, 'Question ID'),
      'name' => new external_value(PARAM_TEXT, 'Question name'),
      'questiontext' => new external_value(PARAM_RAW, 'Question text with format'),
      'qtype' => new external_value(PARAM_TEXT, 'Type of question'),
      'defaultmark' => new external_value(PARAM_FLOAT, 'Default mark for the question'),
      'answers' => new external_multiple_structure(
        new external_single_structure([
          'id' => new external_value(PARAM_INT, 'Answer ID'),
          'text' => new external_value(PARAM_RAW, 'Answer text'),
          'fraction' => new external_value(PARAM_FLOAT, 'Fraction for this answer'),
          'feedback' => new external_value(PARAM_RAW, 'Feedback for this answer')
        ])
      ),
      'hints' => new external_multiple_structure(
        new external_single_structure([
          'id' => new external_value(PARAM_INT, 'Hint ID'),
          'text' => new external_value(PARAM_RAW, 'Hint text')
        ])
      )
    ]);
  }

  /**
   * Returns the category structure with recursive depth.
   *
   * @param int $depth The maximum depth for subcategories.
   * @return external_single_structure
   */
  public static function get_category_structure(int $depth): external_single_structure
  {
    $question_structure = self::get_question_structure();

    return self::build_category_structure($depth, $question_structure);
  }

  /**
   * Helper function to recursively build the category structure.
   *
   * @param int $depth
   * @param external_single_structure $question_structure
   * @return external_single_structure
   */
  private static function build_category_structure($depth, $question_structure)
  {
    // Base case: if depth is zero, return a category structure without subcategories.
    if ($depth === 0) {
      return new external_single_structure([
        'id' => new external_value(PARAM_INT, 'Category ID'),
        'name' => new external_value(PARAM_TEXT, 'Category name'),
        'info' => new external_value(PARAM_RAW, 'Category description/info'),
        'questions' => new external_multiple_structure($question_structure, 'Questions in this category')
      ]);
    }

    // Recursive case: define subcategories and reduce depth.
    $subcategory_structure = self::build_category_structure($depth - 1, $question_structure);

    return new external_single_structure([
      'id' => new external_value(PARAM_INT, 'Category ID'),
      'name' => new external_value(PARAM_TEXT, 'Category name'),
      'info' => new external_value(PARAM_RAW, 'Category description/info'),
      'questions' => new external_multiple_structure($question_structure, 'Questions in this category'),
      'subcategories' => new external_multiple_structure($subcategory_structure, 'List of subcategories within this category')
    ]);
  }

  /**
   * Returns the course structure, including categories and subcategories.
   *
   * @param int $depth Maximum depth for subcategories.
   * @return external_single_structure
   */
  public static function get_basic_course_structure(int $depth, $includeCourseInfo = true): external_single_structure
  {
    $category_structure = self::get_category_structure($depth);

    if ($includeCourseInfo) {
      return new external_single_structure([
        'course' => new external_single_structure([
          'id' => new external_value(PARAM_INT, 'Course ID'),
          'fullname' => new external_value(PARAM_TEXT, 'Full name of the course'),
          'shortname' => new external_value(PARAM_TEXT, 'Short name of the course'),
          'categories' => new external_multiple_structure($category_structure, 'Top-level categories in the course')
        ])
      ]);
    } else {
      return new external_single_structure([
        'categories' => new external_multiple_structure($category_structure, 'Top-level categories in the course')
      ]);
    }
  }

  public static function get_section_structure($includeMods = false): external_single_structure
  {
    $structure = [
      'id' => new external_value(PARAM_INT, 'id'),
      'section' => new external_value(PARAM_INT, 'section'),
      'name' => new external_value(PARAM_TEXT, 'name'),
      'visible' => new external_value(PARAM_INT, 'visible'),
      'availability' => new external_value(PARAM_RAW, 'availability', VALUE_OPTIONAL),
    ];

    if ($includeMods) {
      $structure['modules'] = new external_multiple_structure(
        new external_single_structure(
          [
            'modtype' => new external_value(PARAM_TEXT, 'Module type'),
            'instanceid' => new external_value(PARAM_INT, 'Instance ID'),
            'name' => new external_value(PARAM_TEXT, 'Module name'),
            'intro' => new external_value(PARAM_RAW, 'Module introduction', VALUE_OPTIONAL),
            'externalurl' => new external_value(PARAM_URL, 'External URL', VALUE_OPTIONAL),
            'display' => new external_value(PARAM_INT, 'Display type', VALUE_OPTIONAL),
          ]
        ),
        'List of modules',
        VALUE_OPTIONAL
      );
    }

    return new external_single_structure($structure);
  }
}
