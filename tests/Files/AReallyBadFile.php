<?php declare(strict_types=1);
namespace Instapro;
class AReallyBadFile {
protected string $string = '';
public function __construct(Foo $a, Bar $bar){}

public function getMethod():string{
return $this->string;
}
}