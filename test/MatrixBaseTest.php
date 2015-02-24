<?php
/**
 * Created by PhpStorm.
 * User: szambrano
 * Date: 24/02/15
 * Time: 16:00
 */

include_once "lib/MatrixBase.php";

class MatrixBaseTest extends PHPUnit_Framework_TestCase
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
}
