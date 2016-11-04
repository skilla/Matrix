<?php
/**
 * Created by PhpStorm.
 * User: sergio zambrano <sergio.zambrano@gmail.com>
 * Date: 03/11/16
 * Time: 20:24
 */

namespace Skilla\Matrix\Test;

use Skilla\Matrix\Matrix;
use Skilla\Matrix\Operations;
use Skilla\Matrix\Properties;

class OperationsTest extends \PHPUnit_Framework_TestCase
{
    public function testTransposed()
    {
        $matrix = new Matrix(2, 3, 2);
        $matrix->setPoint(1, 1, 2);
        $matrix->setPoint(1, 2, 3);
        $matrix->setPoint(1, 3, 4);
        $matrix->setPoint(2, 1, 3);
        $matrix->setPoint(2, 2, 4);
        $matrix->setPoint(2, 3, 5);
        $sut = new Operations($matrix, 2);

        $expected = new Matrix(3, 2, 2);
        $expected->setPoint(1, 1, 2);
        $expected->setPoint(1, 2, 3);
        $expected->setPoint(2, 1, 3);
        $expected->setPoint(2, 2, 4);
        $expected->setPoint(3, 1, 4);
        $expected->setPoint(3, 2, 5);
        $this->assertMatrixEquals($expected, $sut->transposed());
    }

    private function assertMatrixEquals(Matrix $expected, Matrix $actual)
    {
        $properties = new Properties($actual, 2);
        return $properties->isEquals($expected);
    }
}
