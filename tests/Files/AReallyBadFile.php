<?php declare(strict_types=1);
namespace Instapro;
class AReallyBadFile {
protected string $string='';
public function __construct(Foo $a, \Baz\Bar $bar){}
public function getMethod():string{
return $this->string;   
}
private function ifsAreWrappedAndUselessElseIsRemoved():bool{if (true) return true; else return false;
//There's currently a bug that causes the second return statement to be incorrectly aligned for some reason.
//This doesn't happen when you run the fixer on multiple files...
}
private function bla(){
    if ($this->string == '') return 'Heyo!';
return 'hi!';
}
private function test(): void{
    $lambda = function () {
        echo 'test';
    };
}
public function whoop(?string $string = null, int|null $int = null): void {}

public function missingNullable(string $string = null, int $int = null): void {}

public function query(): void {
    <<<QUERY
                SELECT COUNT(*) 
    QUERY;
}
}