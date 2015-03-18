<?php
/**
 * Created by PhpStorm.
 * User: sergio zambrano <sergio.zambrano@gmail.com>
 * Date: 24/02/15
 * Time: 16:01
 */

namespace Skilla\Matrix\Test;

include_once "lib/MatrixBase.php";

class MatrixBaseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $matriz = new \skilla\matrix\MatrixBase(3, 3);
        $this->assertEquals(3, $matriz->getNumRows());
        $this->assertEquals(3, $matriz->getNumCols());
        return $matriz;
    }

    /**
     * @depends testConstruct
     */
    public function testIsSquare(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertTrue($matriz->isSquare());
        return $matriz;
    }

    /**
     * @depends testIsSquare
     */
    public function testIsZero(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertTrue($matriz->isZero());
        return $matriz;
    }

    /**
     * @depends testIsZero
     */
    public function testIsNull(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isNull());
        return $matriz;
    }

    /**
     * @depends testIsNull
     */
    public function testIsDiagonalUnit(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isDiagonalUnit());
        $matriz->setPoint(1, 1, 1);
        $matriz->setPoint(2, 2, 1);
        $matriz->setPoint(3, 3, 1);
        $this->assertTrue($matriz->isDiagonalUnit());
        return $matriz;
    }

    /**
     * @depends testIsDiagonalUnit
     */
    public function testIsScalar(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isScalar());
        $tmp = new \skilla\matrix\MatrixBase(1, 1);
        $this->assertTrue($tmp->isScalar());
        return $matriz;
    }

    /**
     * @depends testIsScalar
     */
    public function testIsRowVector(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isRowVector());
        $tmp = new \skilla\matrix\MatrixBase(1, 3);
        $this->assertTrue($tmp->isRowVector());
        return $matriz;
    }

    /**
     * @depends testIsRowVector
     */
    public function testIsColVector(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isColVector());
        $tmp = new \skilla\matrix\MatrixBase(3, 1);
        $this->assertTrue($tmp->isColVector());
        return $matriz;
    }

    /**
     * @depends testIsColVector
     */
    public function testIsTriangularUpper(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isTriangularUpper());
        $tmp = new \skilla\matrix\MatrixBase(3, 3);
        $tmp->setPoint(1, 1, 1);
        $tmp->setPoint(1, 2, 1);
        $tmp->setPoint(1, 3, 1);
        $tmp->setPoint(2, 2, 1);
        $tmp->setPoint(2, 3, 1);
        $tmp->setPoint(3, 3, 1);
        $this->assertTrue($tmp->isTriangularUpper());
        return $matriz;
    }

    /**
     * @depends testIsTriangularUpper
     */
    public function testIsTriangularLower(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isTriangularLower());
        $tmp = new \skilla\matrix\MatrixBase(3, 3);
        $tmp->setPoint(1, 1, 1);
        $tmp->setPoint(2, 1, 1);
        $tmp->setPoint(2, 2, 1);
        $tmp->setPoint(3, 1, 1);
        $tmp->setPoint(3, 2, 1);
        $tmp->setPoint(3, 3, 1);
        $this->assertTrue($tmp->isTriangularLower());
        return $matriz;
    }

    /**
     * @depends testIsTriangularLower
     */
    public function testIsDiagonal(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertTrue($matriz->isDiagonal());
        return $matriz;
    }

    /**
     * @depends testIsDiagonal
     */
    public function testIsMatrixEquals(\skilla\matrix\MatrixBase $matriz)
    {
        $tmp = new \skilla\matrix\MatrixBase(3, 3);
        $this->assertFalse($matriz->isMatrixEquals($tmp));
        $tmp->setPoint(1, 1, 1);
        $tmp->setPoint(2, 2, 1);
        $tmp->setPoint(3, 3, 1);
        $this->assertTrue($matriz->isMatrixEquals($tmp));
        return $matriz;
    }

    /**
     * @depends testIsMatrixEquals
     */
    public function testTransposed(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertTrue($matriz->isMatrixEquals($matriz->transposed()));
        $tmp1 = new \skilla\matrix\MatrixBase(2, 3);
        $tmp2 = new \skilla\matrix\MatrixBase(3, 2);
        $tmp1->setPoint(1, 1, 1);
        $tmp1->setPoint(1, 2, 2);
        $tmp1->setPoint(1, 3, 3);
        $tmp1->setPoint(2, 1, 4);
        $tmp1->setPoint(2, 2, 5);
        $tmp1->setPoint(2, 3, 6);
        $tmp2->setPoint(1, 1, 1);
        $tmp2->setPoint(1, 2, 4);
        $tmp2->setPoint(2, 1, 2);
        $tmp2->setPoint(2, 2, 5);
        $tmp2->setPoint(3, 1, 3);
        $tmp2->setPoint(3, 2, 6);
        $this->assertTrue($tmp1->isMatrixEquals($tmp2->transposed()));
        return $matriz;
    }

    /**
     * @depends testTransposed
     */
    public function testIsSymmetric(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertTrue($matriz->isSymmetric());
        return $matriz;
    }

    public function testAdjuntoOrden2()
    {
        $matriz = new \skilla\matrix\MatrixBase(2, 2);
        $matriz->setPoint(1, 1, 7);
        $matriz->setPoint(1, 2, -3);
        $matriz->setPoint(2, 1, -4);
        $matriz->setPoint(2, 2, 4);
        $tmp = new \skilla\matrix\MatrixBase(1, 1);
        $tmp->setPoint(1, 1, 4);
        $this->assertTrue($tmp->isMatrixEquals($matriz->getAdjunto(1, 1)));
        return $matriz;
    }

    public function testAdjuntoOrden3()
    {
        $matriz = new \skilla\matrix\MatrixBase(3, 3);
        $matriz->setPoint(1, 1, 1);
        $matriz->setPoint(1, 2, 2);
        $matriz->setPoint(1, 3, 3);
        $matriz->setPoint(2, 1, 4);
        $matriz->setPoint(2, 2, 5);
        $matriz->setPoint(2, 3, 6);
        $matriz->setPoint(3, 1, 7);
        $matriz->setPoint(3, 2, 8);
        $matriz->setPoint(3, 3, 9);
        $tmp = new \skilla\matrix\MatrixBase(2, 2);
        $tmp->setPoint(1, 1, 1);
        $tmp->setPoint(1, 2, 3);
        $tmp->setPoint(2, 1, 7);
        $tmp->setPoint(2, 2, 9);
        $this->assertTrue($tmp->isMatrixEquals($matriz->getAdjunto(2, 2)));
        return $matriz;
    }

    public function testAdjuntoOrden4()
    {
        $matriz = new \skilla\matrix\MatrixBase(4, 4);
        $matriz->setPoint(1, 1, 1);
        $matriz->setPoint(1, 2, 2);
        $matriz->setPoint(1, 3, 3);
        $matriz->setPoint(1, 4, 4);
        $matriz->setPoint(2, 1, 5);
        $matriz->setPoint(2, 2, 6);
        $matriz->setPoint(2, 3, 7);
        $matriz->setPoint(2, 4, 8);
        $matriz->setPoint(3, 1, 9);
        $matriz->setPoint(3, 2, 0);
        $matriz->setPoint(3, 3, 1);
        $matriz->setPoint(3, 4, 2);
        $matriz->setPoint(4, 1, 3);
        $matriz->setPoint(4, 2, 4);
        $matriz->setPoint(4, 3, 5);
        $matriz->setPoint(4, 4, 6);
        $tmp = new \skilla\matrix\MatrixBase(3, 3);
        $tmp->setPoint(1, 1, 1);
        $tmp->setPoint(1, 2, 3);
        $tmp->setPoint(1, 3, 4);
        $tmp->setPoint(2, 1, 9);
        $tmp->setPoint(2, 2, 1);
        $tmp->setPoint(2, 3, 2);
        $tmp->setPoint(3, 1, 3);
        $tmp->setPoint(3, 2, 5);
        $tmp->setPoint(3, 3, 6);
        $this->assertTrue($tmp->isMatrixEquals($matriz->getAdjunto(2, 2)));
        $this->assertFalse($tmp->isMatrixEquals($matriz->getAdjunto(3, 2)));
        return $matriz;
    }

    public function testDeterminateOrden1()
    {
        $matriz = new \skilla\matrix\MatrixBase(1, 1);
        $matriz->setPoint(1, 1, 8);
        $this->assertEquals(8, $matriz->determinant());
    }

    /**
     * @depends testAdjuntoOrden2
     */
    public function testDeterminateOrden2(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertEquals(16, $matriz->determinant());
    }

    /**
     * @depends testAdjuntoOrden3
     */
    public function testDeterminateOrden3(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertEquals(0, $matriz->determinant());
    }

    /**
     * @depends testAdjuntoOrden4
     */
    public function testDeterminateOrden4(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertEquals(0, $matriz->determinant());
    }

    /**
     * @depends testAdjuntoOrden4
     */
    public function testTrazaOrden4(\skilla\matrix\MatrixBase $matriz)
    {
        $this->assertEquals(14, $matriz->trace());
    }

    public function testMultiplica()
    {
        $tmp1 = new \skilla\matrix\MatrixBase(3, 3);
        $tmp1->setPoint(1, 1, 2);
        $tmp1->setPoint(1, 2, 0);
        $tmp1->setPoint(1, 3, 1);
        $tmp1->setPoint(2, 1, 3);
        $tmp1->setPoint(2, 2, 0);
        $tmp1->setPoint(2, 3, 0);
        $tmp1->setPoint(3, 1, 5);
        $tmp1->setPoint(3, 2, 1);
        $tmp1->setPoint(3, 3, 1);

        $tmp2 = new \skilla\matrix\MatrixBase(3, 3);
        $tmp2->setPoint(1, 1, 1);
        $tmp2->setPoint(1, 2, 0);
        $tmp2->setPoint(1, 3, 1);
        $tmp2->setPoint(2, 1, 1);
        $tmp2->setPoint(2, 2, 2);
        $tmp2->setPoint(2, 3, 1);
        $tmp2->setPoint(3, 1, 1);
        $tmp2->setPoint(3, 2, 1);
        $tmp2->setPoint(3, 3, 0);

        $tmp3 = new \skilla\matrix\MatrixBase(3, 3);
        $tmp3->setPoint(1, 1, 3);
        $tmp3->setPoint(1, 2, 1);
        $tmp3->setPoint(1, 3, 2);
        $tmp3->setPoint(2, 1, 3);
        $tmp3->setPoint(2, 2, 0);
        $tmp3->setPoint(2, 3, 3);
        $tmp3->setPoint(3, 1, 7);
        $tmp3->setPoint(3, 2, 3);
        $tmp3->setPoint(3, 3, 6);

        $tmp4 = $tmp1->multiplicationMatrix($tmp2);
        $this->assertTrue($tmp3->isMatrixEquals($tmp4));
    }

    /**
     * @depends testMultiplica
     */
    public function testInversa()
    {
        $tmp1 = new \skilla\matrix\MatrixBase(3, 3);
        $tmp1->setPoint(1, 1, 2);
        $tmp1->setPoint(1, 2, -2);
        $tmp1->setPoint(1, 3, 2);
        $tmp1->setPoint(2, 1, 2);
        $tmp1->setPoint(2, 2, 1);
        $tmp1->setPoint(2, 3, 0);
        $tmp1->setPoint(3, 1, 3);
        $tmp1->setPoint(3, 2, -2);
        $tmp1->setPoint(3, 3, 2);

        $inversa = $tmp1->inversa();

        $tmp2 = new \skilla\matrix\MatrixBase(3, 3);
        $tmp2->setPoint(1, 1, -1);
        $tmp2->setPoint(1, 2, 0);
        $tmp2->setPoint(1, 3, 1);
        $tmp2->setPoint(2, 1, 2);
        $tmp2->setPoint(2, 2, 1);
        $tmp2->setPoint(2, 3, -2);
        $tmp2->setPoint(3, 1, 7/2);
        $tmp2->setPoint(3, 2, 1);
        $tmp2->setPoint(3, 3, -3);

        $this->assertTrue($tmp2->isMatrixEquals($inversa));

        $tmp3 = new \skilla\matrix\MatrixBase(3, 3);
        $tmp3->setPoint(1, 1, 1);
        $tmp3->setPoint(1, 2, 0);
        $tmp3->setPoint(1, 3, 0);
        $tmp3->setPoint(2, 1, 0);
        $tmp3->setPoint(2, 2, 1);
        $tmp3->setPoint(2, 3, 0);
        $tmp3->setPoint(3, 1, 0);
        $tmp3->setPoint(3, 2, 0);
        $tmp3->setPoint(3, 3, 1);

        $multiplicada = $tmp1->multiplicationMatrix($inversa);
        $this->assertTrue($tmp3->isMatrixEquals($multiplicada));
    }
}
