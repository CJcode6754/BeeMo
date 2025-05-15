<?php

use Core\Validator;

it('validates a string', function (){
    expect(Validator::string('foorbar'))->toBeTrue();
});

it('validates a string with a minimum length', function (){
    expect(Validator::string('foorbar', 20))->toBeFalse();
});

it('validates a string with a maximum length', function (){
    expect(Validator::string('foorbar', 4, 5))->toBeFalse();
});

it('validates an email', function (){
    expect(Validator::email('foorbar'))->toBeFalse();
    expect(Validator::email('example@gmail.com'))->toBeTrue();
});