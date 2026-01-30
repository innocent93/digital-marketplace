<?php

uses(Tests\TestCase::class)->in('Feature', 'Unit');

expect()->extend('toBeValidJson', function () {
    return $this->value = json_decode($this->value, true) !== null;
});