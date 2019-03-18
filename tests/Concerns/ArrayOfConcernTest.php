<?php

use PHPUnit\Framework\TestCase;
use Pre\PropTypes;
use Pre\PropTypes\Definition;

final class ArrayOfConcernTest extends TestCase
{
    public function test_it_can_tell_when_isnt_array()
    {
        $this->expectException(InvalidArgumentException::class, "arrayOf expects string[] but integer provided");

        $definitions = [
            "arrayOf" => PropTypes::arrayOf(PropTypes::string())->isRequired(),
        ];

        $properties = [
            "arrayOf" => 1,
        ];

        PropTypes::validate($definitions, $properties);
    }

    public function test_it_can_tell_when_has_a_bad_type_definition()
    {
        $this->expectException(InvalidArgumentException::class, "arrayOf expects string[] but integer provided");

        $definitions = [
            "arrayOf" => PropTypes::arrayOf("foo")->isRequired(),
        ];

        $properties = [
            "arrayOf" => [1, 2, 3],
        ];

        PropTypes::validate($definitions, $properties);
    }

    public function test_it_can_tell_items_types()
    {
        $this->expectException(InvalidArgumentException::class, "arrayOf has an unexpected integer in arrayOf(string)");

        $definitions = [
            "arrayOf" => PropTypes::arrayOf(PropTypes::int())->isRequired(),
        ];

        $properties = [
            "arrayOf" => [1, "2", 3],
        ];

        PropTypes::validate($definitions, $properties);
    }

    public function test_it_can_handle_recursion()
    {
        $definitions = [
            "arrayOf" => PropTypes::arrayOf(PropTypes::arrayOf(PropTypes::int()))
                ->isRequired(),
        ];

        $properties = [
            "arrayOf" => [[1, 3], [2, 2], [3, 1]],
        ];

        PropTypes::validate($definitions, $properties);

        $this->addToAssertionCount(1);
    }

    public function test_it_can_handle_arrays_of_objects()
    {
        $definitions = [
            "stdClass" => PropTypes::arrayOf(PropTypes::objectOfType(stdClass::class)),
            "Definition" => PropTypes::arrayOf(PropTypes::objectOfType(Definition::class)),
        ];

        $properties = [
            "stdClass" => [new stdClass, new stdClass],
            "Definition" => [new Definition, new Definition],
        ];

        PropTypes::validate($definitions, $properties);

        $this->addToAssertionCount(1);
    }
}
