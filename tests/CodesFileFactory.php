<?php

namespace Tests;

use Illuminate\Http\Testing\File;
use Illuminate\Http\Testing\FileFactory;

class CodesFileFactory extends FileFactory
{
    /**
     * Generate a fake codes file
     *
     * @param  string $name
     * @param  int $count
     * @return \Illuminate\Http\Testing\File
     */
    public function generate($name, $count)
    {
        return new File($name, tap(tmpfile(), function ($file) use ($count) {
            for ($i=0; $i < $count; $i++) {
                fwrite($file, randomStr() . PHP_EOL);
            }
        }));
    }
}
