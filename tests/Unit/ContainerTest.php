<?php

use Core\Container;

test('it can resolve something out of container', function () {
    $container = new Container();

    //arrange
    $container->bind('foo', function (){
        return 'bar';
    });

    //act
    $result = $container->resolve('foo');

    //expect
    expect($result)->toEqual('bar');
});
