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

    public function generarMatrix()
    {
        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 1, 0);
        $matrix->setPoint(1, 2, 1);
        $matrix->setPoint(1, 3, 1);
        $matrix->setPoint(2, 1, 1);
        $matrix->setPoint(2, 2, 0);
        $matrix->setPoint(2, 3, 0);
        $matrix->setPoint(3, 1, 0);
        $matrix->setPoint(3, 2, 0);
        $matrix->setPoint(3, 3, 1);
        return $matrix;
    }

    public function testAdjugateMatrix()
    {
        $operations = new Operations($this->generarMatrix(), 2);
        $transposed = $operations->transposed();
        $operations = new Operations($transposed, 2);
        $adjugate   = $operations->adjugateMatrix();

        $expected = new Matrix(3, 3, 2);
        $expected->setPoint(1, 1, 0);
        $expected->setPoint(1, 2, -1);
        $expected->setPoint(1, 3, 0);
        $expected->setPoint(2, 1, -1);
        $expected->setPoint(2, 2, 0);
        $expected->setPoint(2, 3, 1);
        $expected->setPoint(3, 1, 0);
        $expected->setPoint(3, 2, 0);
        $expected->setPoint(3, 3, -1);

        $this->assertMatrixEquals($expected, $adjugate);
    }

    private function assertMatrixEquals(Matrix $expected, Matrix $actual)
    {
        $properties = new Properties($actual, 2);
        $this->assertTrue($properties->isEquals($expected));
    }
}
