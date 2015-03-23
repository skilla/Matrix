<?php
/**
 * Created by PhpStorm.
 * User: sergio zambrano <sergio.zambrano@gmail.com>
 * Date: 24/02/15
 * Time: 16:01
 */

namespace Skilla\Matrix\Test;

use Skilla\Matrix\MatrixBase;

include_once "lib/MatrixBase.php";

class MatrixBaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return MatrixBase
     */
    public function testConstruct()
    {
        $matriz = new MatrixBase(3, 3, 2);
        $this->assertEquals(3, $matriz->getNumRows());
        $this->assertEquals(3, $matriz->getNumCols());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testConstruct
     */
    public function testIsSquare(MatrixBase $matriz)
    {
        $this->assertTrue($matriz->isSquare());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testIsSquare
     */
    public function testIsZero(MatrixBase $matriz)
    {
        $this->assertTrue($matriz->isZero());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testIsZero
     */
    public function testIsNull(MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isNull());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testIsNull
     */
    public function testIsDiagonalUnit(MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isDiagonalUnit());
        $matriz->setPoint(1, 1, 1);
        $matriz->setPoint(2, 2, 1);
        $matriz->setPoint(3, 3, 1);
        $this->assertTrue($matriz->isDiagonalUnit());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testIsDiagonalUnit
     */
    public function testIsScalar(MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isScalar());
        $tmp = new MatrixBase(1, 1);
        $this->assertTrue($tmp->isScalar());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testIsScalar
     */
    public function testIsRowVector(MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isRowVector());
        $tmp = new MatrixBase(1, 3);
        $this->assertTrue($tmp->isRowVector());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testIsRowVector
     */
    public function testIsColVector(MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isColVector());
        $tmp = new MatrixBase(3, 1);
        $this->assertTrue($tmp->isColVector());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testIsColVector
     */
    public function testIsTriangularUpper(MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isTriangularUpper());
        $tmp = new MatrixBase(3, 3);
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
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testIsTriangularUpper
     */
    public function testIsTriangularLower(MatrixBase $matriz)
    {
        $this->assertFalse($matriz->isTriangularLower());
        $tmp = new MatrixBase(3, 3);
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
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testIsTriangularLower
     */
    public function testIsDiagonal(MatrixBase $matriz)
    {
        $this->assertTrue($matriz->isDiagonal());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testIsDiagonal
     */
    public function testIsMatrixEquals(MatrixBase $matriz)
    {
        $tmp = new MatrixBase(3, 3);
        $this->assertFalse($matriz->isMatrixEquals($tmp));
        $tmp->setPoint(1, 1, 1);
        $tmp->setPoint(2, 2, 1);
        $tmp->setPoint(3, 3, 1);
        $this->assertTrue($matriz->isMatrixEquals($tmp));
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testIsMatrixEquals
     */
    public function testTransposed(MatrixBase $matriz)
    {
        $this->assertTrue($matriz->isMatrixEquals($matriz->transposed()));
        $tmp1 = new MatrixBase(2, 3);
        $tmp2 = new MatrixBase(3, 2);
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
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testTransposed
     */
    public function testIsSymmetric(MatrixBase $matriz)
    {
        $this->assertTrue($matriz->isSymmetric());
        return $matriz;
    }

    /**
     * @return MatrixBase
     */
    public function testAdjuntoOrden2()
    {
        $matriz = new MatrixBase(2, 2);
        $matriz->setPoint(1, 1, 7);
        $matriz->setPoint(1, 2, -3);
        $matriz->setPoint(2, 1, -4);
        $matriz->setPoint(2, 2, 4);
        $tmp = new MatrixBase(1, 1);
        $tmp->setPoint(1, 1, 4);
        $this->assertTrue($tmp->isMatrixEquals($matriz->getAdjunto(1, 1)));
        return $matriz;
    }

    /**
     * @return MatrixBase
     */
    public function testAdjuntoOrden3()
    {
        $matriz = new MatrixBase(3, 3);
        $matriz->setPoint(1, 1, 1);
        $matriz->setPoint(1, 2, 2);
        $matriz->setPoint(1, 3, 3);
        $matriz->setPoint(2, 1, 4);
        $matriz->setPoint(2, 2, 5);
        $matriz->setPoint(2, 3, 6);
        $matriz->setPoint(3, 1, 7);
        $matriz->setPoint(3, 2, 8);
        $matriz->setPoint(3, 3, 9);
        $tmp = new MatrixBase(2, 2);
        $tmp->setPoint(1, 1, 1);
        $tmp->setPoint(1, 2, 3);
        $tmp->setPoint(2, 1, 7);
        $tmp->setPoint(2, 2, 9);
        $this->assertTrue($tmp->isMatrixEquals($matriz->getAdjunto(2, 2)));
        return $matriz;
    }

    /**
     * @return MatrixBase
     */
    public function testAdjuntoOrden4()
    {
        $matriz = new MatrixBase(4, 4);
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
        $tmp = new MatrixBase(3, 3);
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

    /**
     * @return MatrixBase
     */
    public function testDeterminateOrden1()
    {
        $matriz = new MatrixBase(1, 1);
        $matriz->setPoint(1, 1, 8);
        $this->assertEquals(8, $matriz->determinant());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testAdjuntoOrden2
     */
    public function testDeterminateOrden2(MatrixBase $matriz)
    {
        $this->assertEquals(16, $matriz->determinant());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testAdjuntoOrden3
     */
    public function testDeterminateOrden3(MatrixBase $matriz)
    {
        $this->assertEquals(0, $matriz->determinant());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testAdjuntoOrden4
     */
    public function testDeterminateOrden4(MatrixBase $matriz)
    {
        $this->assertEquals(0, $matriz->determinant());
        return $matriz;
    }

    /**
     * @param MatrixBase $matriz
     * @return MatrixBase
     * @depends testAdjuntoOrden4
     */
    public function testTrazaOrden4(MatrixBase $matriz)
    {
        $this->assertEquals(14, $matriz->trace());
        return $matriz;
    }

    public function testMultiplica()
    {
        $tmp1 = new MatrixBase(3, 3, 5);
        $tmp1->setPoint(1, 1, 2);
        $tmp1->setPoint(1, 2, 0);
        $tmp1->setPoint(1, 3, 1);
        $tmp1->setPoint(2, 1, 3);
        $tmp1->setPoint(2, 2, 0);
        $tmp1->setPoint(2, 3, 0);
        $tmp1->setPoint(3, 1, 5);
        $tmp1->setPoint(3, 2, 1);
        $tmp1->setPoint(3, 3, 1);

        $tmp2 = new MatrixBase(3, 3, 5);
        $tmp2->setPoint(1, 1, 1);
        $tmp2->setPoint(1, 2, 0);
        $tmp2->setPoint(1, 3, 1);
        $tmp2->setPoint(2, 1, 1);
        $tmp2->setPoint(2, 2, 2);
        $tmp2->setPoint(2, 3, 1);
        $tmp2->setPoint(3, 1, 1);
        $tmp2->setPoint(3, 2, 1);
        $tmp2->setPoint(3, 3, 0);

        $tmp3 = new MatrixBase(3, 3, 5);
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
        $tmp1 = new MatrixBase(3, 3, 5);
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

        $tmp2 = new MatrixBase(3, 3, 5);
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

        $tmp3 = new MatrixBase(3, 3, 5);
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
