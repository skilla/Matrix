<?php
/**
 * Created by PhpStorm.
 * User: sergio zambrano <sergio.zambrano@gmail.com>
 * Date: 24/02/15
 * Time: 16:01
 */

namespace Skilla\Matrix\Test;

use Skilla\Matrix\Matrix;
use Skilla\Matrix\Properties;

class PropertiesTest extends \PHPUnit_Framework_TestCase
{
    public function testIsSquare()
    {
        $matrix = new Matrix(3, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isSquare());

        $matrix = new Matrix(2, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isSquare());
    }

    public function testIsZeroAndIsNull()
    {
        $matrix = new Matrix(3, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isZero());
        $this->assertTrue($sut->isNull());

        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 3, -5, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isZero());
        $this->assertFalse($sut->isNull());
    }

    public function testIsDiagonal()
    {
        $matrix = new Matrix(3, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isDiagonal());

        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 3, -5, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isDiagonal());

        $matrix = new Matrix(3, 8, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isDiagonal());
    }

    public function testIsScalar()
    {
        $matrix = new Matrix(3, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isScalar());

        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(3, 3, -5, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isScalar());

        $matrix = new Matrix(3, 8, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isScalar());
    }

    public function testIsDiagonalUnit()
    {
        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 1, 1, 2);
        $matrix->setPoint(2, 2, 1, 2);
        $matrix->setPoint(3, 3, 1, 2);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isDiagonalUnit());

        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 1, 1, 2);
        $matrix->setPoint(2, 2, 1, 2);
        $matrix->setPoint(3, 3, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isDiagonalUnit());

        $matrix = new Matrix(1, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isDiagonalUnit());
    }

    public function testIsDiagonalZero()
    {
        $matrix = new Matrix(3, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isDiagonalZero());

        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(3, 3, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isDiagonalZero());

        $matrix = new Matrix(1, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isDiagonalZero());
    }

    public function testIsRowVector()
    {
        $matrix = new Matrix(1, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isRowVector());

        $matrix = new Matrix(1, 1, 2);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isRowVector());

        $matrix = new Matrix(2, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isRowVector());

        $matrix = new Matrix(3, 1, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isRowVector());
    }

    public function testIsColVector()
    {
        $matrix = new Matrix(3, 1, 2);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isColVector());

        $matrix = new Matrix(1, 1, 2);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isColVector());

        $matrix = new Matrix(3, 2, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isColVector());

        $matrix = new Matrix(1, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isColVector());
    }

    public function testIsEquals()
    {
        $matrix = new Matrix(2, 3, 2);
        $sut = new Properties($matrix, 2);

        $base = new Matrix(2, 3, 2);
        $this->assertTrue($sut->isEquals($base));

        $matrix = new Matrix(2, 3, 2);
        $sut = new Properties($matrix, 2);

        $base = new Matrix(2, 3, 2);
        $base->setPoint(1, 1, -3);
        $this->assertFalse($sut->isEquals($base));

        $base = new Matrix(2, 2, 2);
        $this->assertFalse($sut->isEquals($base));
    }

    public function testIsSymmetric()
    {
        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 1, 3);
        $matrix->setPoint(1, 2, 7);
        $matrix->setPoint(1, 3, 11);
        $matrix->setPoint(2, 1, 7);
        $matrix->setPoint(2, 2, 4);
        $matrix->setPoint(2, 3, 16);
        $matrix->setPoint(3, 1, 11);
        $matrix->setPoint(3, 2, 16);
        $matrix->setPoint(3, 3, 8);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isSymmetric());

        $matrix = new Matrix(1, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isSymmetric());

        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 1, 3);
        $matrix->setPoint(1, 2, 7);
        $matrix->setPoint(1, 3, 11);
        $matrix->setPoint(2, 1, 7);
        $matrix->setPoint(2, 2, 4);
        $matrix->setPoint(2, 3, 5);
        $matrix->setPoint(3, 1, 11);
        $matrix->setPoint(3, 2, 16);
        $matrix->setPoint(3, 3, 8);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isSymmetric());
    }

    public function testIsTriangularUpper()
    {
        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 1, 0);
        $matrix->setPoint(1, 2, 0);
        $matrix->setPoint(1, 3, 1);
        $matrix->setPoint(2, 1, 0);
        $matrix->setPoint(2, 2, 0);
        $matrix->setPoint(2, 3, 0);
        $matrix->setPoint(3, 1, 0);
        $matrix->setPoint(3, 2, 0);
        $matrix->setPoint(3, 3, 0);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isTriangularUpper());

        $matrix = new Matrix(1, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isTriangularUpper());

        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 1, 0);
        $matrix->setPoint(1, 2, 0);
        $matrix->setPoint(1, 3, 1);
        $matrix->setPoint(2, 1, 0);
        $matrix->setPoint(2, 2, 0);
        $matrix->setPoint(2, 3, 0);
        $matrix->setPoint(3, 1, 1);
        $matrix->setPoint(3, 2, 0);
        $matrix->setPoint(3, 3, 0);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isTriangularUpper());
    }

    public function testIsTriangularLower()
    {
        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 1, 0);
        $matrix->setPoint(1, 2, 0);
        $matrix->setPoint(1, 3, 0);
        $matrix->setPoint(2, 1, 0);
        $matrix->setPoint(2, 2, 0);
        $matrix->setPoint(2, 3, 0);
        $matrix->setPoint(3, 1, 1);
        $matrix->setPoint(3, 2, 0);
        $matrix->setPoint(3, 3, 0);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isTriangularLower());

        $matrix = new Matrix(1, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isTriangularLower());

        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 1, 0);
        $matrix->setPoint(1, 2, 0);
        $matrix->setPoint(1, 3, 1);
        $matrix->setPoint(2, 1, 0);
        $matrix->setPoint(2, 2, 0);
        $matrix->setPoint(2, 3, 0);
        $matrix->setPoint(3, 1, 1);
        $matrix->setPoint(3, 2, 0);
        $matrix->setPoint(3, 3, 0);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isTriangularLower());
    }
}
