<?php
/**
 * Created by PhpStorm.
 * User: Skilla <sergio.zambrano@gmail.com>
 * Date: 6/11/16
 * Time: 19:06
 */

namespace Skilla\Matrix\Test;

use Skilla\Matrix\Determinant;
use Skilla\Matrix\Matrix;

class DeterminantTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Skilla\Matrix\NotSquareException
     */
    public function testConstructDeterminantThrowNotSquareException()
    {
        $matrix = new Matrix(2, 1);
        return new Determinant($matrix);
    }

    public function testConstructDeterminant()
    {
        $matrix = new Matrix(2, 2);
        $sut = new Determinant($matrix);
        $this->assertInstanceOf('Skilla\\Matrix\\Determinant', $sut);
    }

    /**
     * @expectedException \Skilla\Matrix\OperationNotAllowedException
     */
    public function testDeterminantOrderOneThrowOperationNotAllowedException()
    {
        $matrix = new Matrix(2, 2);
        $sut = new Determinant($matrix);
        $sut->determinantOrderOne();
    }

    public function testDeterminantOrderOne()
    {
        $matrix = new Matrix(1, 1, 6);
        $matrix->setPoint(1, 1, 23);
        $sut = new Determinant($matrix, 6);

        $result = $this->checkAbsoluteEquals('23.000000', $sut->determinantOrderOne());
        $this->assertTrue($result);

        $result = $this->checkAbsoluteEquals('23.000000', $sut->determinant());
        $this->assertTrue($result);
    }

    /**
     * @expectedException \Skilla\Matrix\OperationNotAllowedException
     */
    public function testDeterminantOrderTwoThrowOperationNotAllowedException()
    {
        $matrix = new Matrix(1, 1);
        $sut = new Determinant($matrix);
        $sut->determinantOrderTwo();
    }

    public function testDeterminantOrderTwo()
    {
        $matrix = new Matrix(2, 2, 6);
        $matrix->setPoint(1, 1, 23);
        $matrix->setPoint(1, 2, -7);
        $matrix->setPoint(2, 1, 3.5);
        $matrix->setPoint(2, 2, -4.7);
        $sut = new Determinant($matrix);

        $result = $this->checkAbsoluteEquals('-83.600000', $sut->determinantOrderTwo());
        $this->assertTrue($result);

        $result = $this->checkAbsoluteEquals('-83.600000', $sut->determinant());
        $this->assertTrue($result);
    }

    /**
     * @expectedException \Skilla\Matrix\OperationNotAllowedException
     */
    public function testDeterminantOrderThreeThrowOperationNotAllowedException()
    {
        $matrix = new Matrix(1, 1);
        $sut = new Determinant($matrix);
        $sut->determinantOrderThree();
    }

    public function testDeterminantOrderThree()
    {
        $matrix = new Matrix(3, 3, 6);
        $matrix->setPoint(1, 1, 23);
        $matrix->setPoint(1, 2, -7);
        $matrix->setPoint(1, 3, -2.8);
        $matrix->setPoint(2, 1, 3.5);
        $matrix->setPoint(2, 2, -4.7);
        $matrix->setPoint(2, 3, 9);
        $matrix->setPoint(3, 1, 5);
        $matrix->setPoint(3, 2, 0.6);
        $matrix->setPoint(3, 3, 4);
        $sut = new Determinant($matrix);

        $result = $this->checkAbsoluteEquals('-845.280000', $sut->determinantOrderThree());
        $this->assertTrue($result);

        $result = $this->checkAbsoluteEquals('-845.280000', $sut->determinant());
        $this->assertTrue($result);
    }

    /**
     * @expectedException \Skilla\Matrix\OperationNotAllowedException
     */
    public function testDeterminantOrderNThrowOperationNotAllowedException()
    {
        $matrix = new Matrix(1, 1);
        $sut = new Determinant($matrix);
        $sut->determinantOrderN();
    }

    public function testDeterminantOrderN()
    {
        $matrix = new Matrix(4, 4, 6);
        $matrix->setPoint(1, 1, 2);
        $matrix->setPoint(1, 2, 3);
        $matrix->setPoint(1, 3, -2);
        $matrix->setPoint(1, 4, 4);
        $matrix->setPoint(2, 1, 3);
        $matrix->setPoint(2, 2, -2);
        $matrix->setPoint(2, 3, 1);
        $matrix->setPoint(2, 4, 2);
        $matrix->setPoint(3, 1, 3);
        $matrix->setPoint(3, 2, 2);
        $matrix->setPoint(3, 3, 3);
        $matrix->setPoint(3, 4, 4);
        $matrix->setPoint(4, 1, -2);
        $matrix->setPoint(4, 2, 4);
        $matrix->setPoint(4, 3, 0);
        $matrix->setPoint(4, 4, 5);
        $sut = new Determinant($matrix);

        $result = $this->checkAbsoluteEquals('-286.000000', $sut->determinantOrderN());
        $this->assertTrue($result);


        $result = $this->checkAbsoluteEquals('-286.000000', $sut->determinant());
        $this->assertTrue($result);
    }

    /**
     * @expectedException \Skilla\Matrix\OperationNotAllowedException
     */
    public function testDeterminant()
    {
        $matrix = new Matrix(4, 4, 6);
        $sut = new Determinant($matrix);
        return $sut->determinant();
    }

    /**
     * @param $expected
     * @param $actual
     * @return bool
     */
    private function checkAbsoluteEquals($expected, $actual)
    {
        return $expected === $actual;
    }
}
