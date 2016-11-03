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
    }

    public function testNotIsSquare()
    {
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
    }

    public function testNotIsZeroAndNotIsNull()
    {
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
    }

    public function testNotIsDiagonal()
    {
        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 3, -5, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isDiagonal());
    }

    public function testIsScalar()
    {
        $matrix = new Matrix(3, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isScalar());
    }

    public function testNotIsScalar()
    {
        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(3, 3, -5, 2);
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
    }

    public function testNotIsDiagonalUnit()
    {
        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(1, 1, 1, 2);
        $matrix->setPoint(2, 2, 1, 2);
        $matrix->setPoint(3, 3, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isDiagonalUnit());
    }

    public function testIsDiagonalZero()
    {
        $matrix = new Matrix(3, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertTrue($sut->isDiagonalZero());
    }

    public function testNotIsDiagonalZero()
    {
        $matrix = new Matrix(3, 3, 2);
        $matrix->setPoint(3, 3, 3, 2);
        $sut = new Properties($matrix, 2);
        $this->assertFalse($sut->isDiagonalZero());
    }












    /**
     * @expectedException \Skilla\Matrix\ParameterException
     */
    public function testConstructColumnsZero()
    {
        $matriz = new Matrix(3, 0, 2);
    }

    /**
     * @expectedException \Skilla\Matrix\ParameterException
     */
    public function testConstructRowsAndColumnsZero()
    {
        $matriz = new Matrix(0, 0, 2);
    }

    /**
     * @expectedException \Skilla\Matrix\ParameterException
     */
    public function testConstructBadPrecision()
    {
        $matriz = new Matrix(3, 3, -1);
    }

    public function testGetNumRows()
    {
        $sut = new Matrix(3, 3, 2);
        $this->assertEquals(3, $sut->getNumRows());
    }

    public function testGetNumCols()
    {
        $sut = new Matrix(3, 3, 2);
        $this->assertEquals(3, $sut->getNumCols());
    }

    public function testGetPoint()
    {
        $sut = new Matrix(3, 3, 2);
        $this->assertEquals("0.00", $sut->getPoint(1, 1));
        $this->assertEquals("0.00", $sut->getPoint(1, 2));
        $this->assertEquals("0.00", $sut->getPoint(1, 3));
        $this->assertEquals("0.00", $sut->getPoint(2, 1));
        $this->assertEquals("0.00", $sut->getPoint(2, 2));
        $this->assertEquals("0.00", $sut->getPoint(2, 3));
        $this->assertEquals("0.00", $sut->getPoint(3, 1));
        $this->assertEquals("0.00", $sut->getPoint(3, 2));
        $this->assertEquals("0.00", $sut->getPoint(3, 3));
    }

    /**
     * @expectedException \Skilla\Matrix\OutOfRangeException
     */
    public function testGetPointOutOfRange()
    {
        $sut = new Matrix(3, 3, 2);
        $sut->getPoint(4, 4);
    }

    public function testSetPointAndGetPoint()
    {
        $sut = new Matrix(3, 3, 2);
        $sut->setPoint(1, 1, 2);
        $sut->setPoint(1, 2, 3);
        $sut->setPoint(1, 3, 4);
        $sut->setPoint(2, 1, 3);
        $sut->setPoint(2, 2, 4);
        $sut->setPoint(2, 3, 5);
        $sut->setPoint(3, 1, 4);
        $sut->setPoint(3, 2, 5);
        $sut->setPoint(3, 3, "6.00");

        $this->assertEquals("2.00", $sut->getPoint(1, 1));
        $this->assertEquals("3.00", $sut->getPoint(1, 2));
        $this->assertEquals("4.00", $sut->getPoint(1, 3));
        $this->assertEquals("3.00", $sut->getPoint(2, 1));
        $this->assertEquals("4.00", $sut->getPoint(2, 2));
        $this->assertEquals("5.00", $sut->getPoint(2, 3));
        $this->assertEquals("4.00", $sut->getPoint(3, 1));
        $this->assertEquals("5.00", $sut->getPoint(3, 2));
        $this->assertEquals("6.00", $sut->getPoint(3, 3));
    }

    /**
     * @expectedException \Skilla\Matrix\OutOfRangeException
     */
    public function testSetPointOutOfRange()
    {
        $sut = new Matrix(3, 3, 2);
        $sut->setPoint(4, 4, 0);
    }

    public function testGetRowAndGetCol()
    {
        $sut = new Matrix(3, 3, 2);
        $sut->setPoint(1, 1, 2);
        $sut->setPoint(1, 2, 3);
        $sut->setPoint(1, 3, 4);
        $sut->setPoint(2, 1, 3);
        $sut->setPoint(2, 2, 4);
        $sut->setPoint(2, 3, 5);
        $sut->setPoint(3, 1, 4);
        $sut->setPoint(3, 2, 5);
        $sut->setPoint(3, 3, "6.00");

        $this->assertEquals("2.00", $sut->getRow(1)->getPoint(1, 1));
        $this->assertEquals("3.00", $sut->getRow(1)->getPoint(1, 2));
        $this->assertEquals("4.00", $sut->getRow(1)->getPoint(1, 3));
        $this->assertEquals("3.00", $sut->getRow(2)->getPoint(1, 1));
        $this->assertEquals("4.00", $sut->getRow(2)->getPoint(1, 2));
        $this->assertEquals("5.00", $sut->getRow(2)->getPoint(1, 3));
        $this->assertEquals("4.00", $sut->getRow(3)->getPoint(1, 1));
        $this->assertEquals("5.00", $sut->getRow(3)->getPoint(1, 2));
        $this->assertEquals("6.00", $sut->getRow(3)->getPoint(1, 3));

        $this->assertEquals("2.00", $sut->getCol(1)->getPoint(1, 1));
        $this->assertEquals("3.00", $sut->getCol(1)->getPoint(2, 1));
        $this->assertEquals("4.00", $sut->getCol(1)->getPoint(3, 1));
        $this->assertEquals("3.00", $sut->getCol(2)->getPoint(1, 1));
        $this->assertEquals("4.00", $sut->getCol(2)->getPoint(2, 1));
        $this->assertEquals("5.00", $sut->getCol(2)->getPoint(3, 1));
        $this->assertEquals("4.00", $sut->getCol(3)->getPoint(1, 1));
        $this->assertEquals("5.00", $sut->getCol(3)->getPoint(2, 1));
        $this->assertEquals("6.00", $sut->getCol(3)->getPoint(3, 1));
    }

    /**
     * @expectedException \Skilla\Matrix\OutOfRangeException
     */
    public function testGetRowOutOfRange()
    {
        $sut = new Matrix(3, 3, 2);
        $sut->getRow(4);
    }

    /**
     * @expectedException \Skilla\Matrix\OutOfRangeException
     */
    public function testGetColOutOfRange()
    {
        $sut = new Matrix(3, 3, 2);
        $sut->getCol(4);
    }

    public function testSetRow()
    {
        $sut = new Matrix(3, 3, 2);

        $tmp = new Matrix(1, 3, 2);
        $tmp->setPoint(1, 1, 2);
        $tmp->setPoint(1, 2, 3);
        $tmp->setPoint(1, 3, 4);
        $sut->setRow(1, $tmp);

        $tmp = new Matrix(1, 3, 2);
        $tmp->setPoint(1, 1, 3);
        $tmp->setPoint(1, 2, 4);
        $tmp->setPoint(1, 3, 5);
        $sut->setRow(2, $tmp);

        $tmp = new Matrix(1, 3, 2);
        $tmp->setPoint(1, 1, 4);
        $tmp->setPoint(1, 2, 5);
        $tmp->setPoint(1, 3, "6.00");
        $sut->setRow(3, $tmp);


        $this->assertEquals("2.00", $sut->getPoint(1, 1));
        $this->assertEquals("3.00", $sut->getPoint(1, 2));
        $this->assertEquals("4.00", $sut->getPoint(1, 3));
        $this->assertEquals("3.00", $sut->getPoint(2, 1));
        $this->assertEquals("4.00", $sut->getPoint(2, 2));
        $this->assertEquals("5.00", $sut->getPoint(2, 3));
        $this->assertEquals("4.00", $sut->getPoint(3, 1));
        $this->assertEquals("5.00", $sut->getPoint(3, 2));
        $this->assertEquals("6.00", $sut->getPoint(3, 3));
    }

    /**
     * @expectedException \Skilla\Matrix\OutOfRangeException
     */
    public function testSetRowOutOfRange()
    {
        $sut = new Matrix(3, 3, 2);
        $sut->setRow(4, new Matrix(1, 3, 2));
    }

    public function testSetCol()
    {
        $sut = new Matrix(3, 3, 2);
        $tmp = new Matrix(3, 1, 2);
        $tmp->setPoint(1, 1, 2);
        $tmp->setPoint(2, 1, 3);
        $tmp->setPoint(3, 1, 4);
        $sut->setCol(1, $tmp);

        $tmp = new Matrix(3, 1, 2);
        $tmp->setPoint(1, 1, 3);
        $tmp->setPoint(2, 1, 4);
        $tmp->setPoint(3, 1, 5);
        $sut->setCol(2, $tmp);

        $tmp = new Matrix(3, 1, 2);
        $tmp->setPoint(1, 1, 4);
        $tmp->setPoint(2, 1, 5);
        $tmp->setPoint(3, 1, "6.00");
        $sut->setCol(3, $tmp);

        $this->assertEquals("2.00", $sut->getPoint(1, 1));
        $this->assertEquals("3.00", $sut->getPoint(1, 2));
        $this->assertEquals("4.00", $sut->getPoint(1, 3));
        $this->assertEquals("3.00", $sut->getPoint(2, 1));
        $this->assertEquals("4.00", $sut->getPoint(2, 2));
        $this->assertEquals("5.00", $sut->getPoint(2, 3));
        $this->assertEquals("4.00", $sut->getPoint(3, 1));
        $this->assertEquals("5.00", $sut->getPoint(3, 2));
        $this->assertEquals("6.00", $sut->getPoint(3, 3));
    }

    /**
     * @expectedException \Skilla\Matrix\OutOfRangeException
     */
    public function testSetColOutOfRange()
    {
        $sut = new Matrix(3, 3, 2);
        $sut->setCol(4, new Matrix(3, 1, 2));
    }


    public function testToArray()
    {
        $sut = new Matrix(3, 3, 2);
        $sut->setPoint(1, 1, 2);
        $sut->setPoint(1, 2, 3);
        $sut->setPoint(1, 3, 4);
        $sut->setPoint(2, 1, 3);
        $sut->setPoint(2, 2, 4);
        $sut->setPoint(2, 3, 5);
        $sut->setPoint(3, 1, 4);
        $sut->setPoint(3, 2, 5);
        $sut->setPoint(3, 3, "6.00");
        $actual = $sut->toArray();

        $expected = array(
            1 => array(
                1 => "2.00",
                2 => "3.00",
                3 => "4.00",
            ),
            2 => array(
                1 => "3.00",
                2 => "4.00",
                3 => "5.00",
            ),
            3 => array(
                1 => "4.00",
                2 => "5.00",
                3 => "6.00",
            ),
        );
        $this->assertEquals($expected, $actual);
        $this->assertTrue($expected[2][2]===$actual[2][2]);
    }

    public function testPrintPretty()
    {
        $sut = new Matrix(3, 3, 2);
        $sut->setPoint(1, 1, 2);
        $sut->setPoint(1, 2, 3);
        $sut->setPoint(1, 3, 4);
        $sut->setPoint(2, 1, 3);
        $sut->setPoint(2, 2, 4);
        $sut->setPoint(2, 3, 5);
        $sut->setPoint(3, 1, 4);
        $sut->setPoint(3, 2, 5);
        $sut->setPoint(3, 3, "6.00");

        ob_start();
        $sut->printPretty();
        $response = ob_get_clean();
        $this->assertContains('2.00', $response);
        $this->assertContains('3.00', $response);
        $this->assertContains('4.00', $response);
        $this->assertContains('5.00', $response);
        $this->assertContains('6.00', $response);

        $actual = "        2.00          3.00          4.00  \n        3.00          4.00          5.00  \n        4.00          5.00          6.00  \n";
        $this->assertEquals($actual, $response);
    }
}
