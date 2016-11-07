<?php
/**
 * Created by PhpStorm.
 * User: Sergio Zambrano Delfa <sergio.zambrano@gmail.com>
 * Date: 08/11/16
 * Time: 19:55
 */

namespace Skilla\Matrix;

class Operations
{
    private $matrix;
    private $properties;
    private $valueZero;
    private $valueOne;
    private $precision;

    /**
     * Properties constructor.
     * @param Matrix $matrix
     * @param int|null $precision
     */
    public function __construct(Matrix $matrix, $precision = 15)
    {
        $this->precision = (int)$precision;
        $this->valueZero = bcadd(0, 0, $this->precision);
        $this->valueOne = bcadd(0, 1, $this->precision);
        $this->matrix = $matrix;
        $this->properties = new Properties($matrix, $precision);
    }

    /**
     * @return Matrix
     */
    public function transposed()
    {
        $transposed = new Matrix($this->matrix->getNumCols(), $this->matrix->getNumRows(), $this->precision);
        for ($i = 1; $i <= $this->matrix->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->matrix->getNumCols(); $j++) {
                $transposed->setPoint($j, $i, $this->matrix->getPoint($i, $j));
            }
        }
        return $transposed;
    }

    /**
     * @return Matrix
     * @throws NotSquareException
     */
    public function adjugateMatrix()
    {
        if (!$this->properties->isSquare()) {
            throw new NotSquareException();
        }

        $adjugate = new Matrix($this->matrix->getNumRows(), $this->matrix->getNumCols(), $this->precision);

        for ($i=1; $i<=$this->matrix->getNumRows(); $i++) {
            for ($j=1; $j<=$this->matrix->getNumCols(); $j++) {
                $adjugate->setPoint($j, $i, $this->cofactor($i, $j));
            }
        }
        return $adjugate;
    }

    public function cofactorMatrix()
    {
        if (!$this->properties->isSquare()) {
            throw new NotSquareException();
        }


    }

    public function determinant()
    {
        if (!$this->properties->isSquare()) {
            throw new NotSquareException();
        }
        if ($this->matrix->getNumRows()===1) {
            return $this->matrix->getPoint(1, 1);
        } elseif ($this->matrix->getNumRows()===2) {
            $mainDiagonal = bcmul($this->matrix->getPoint(1, 1), $this->matrix->getPoint(2, 2), $this->precision);
            $secondaryDiagonal = bcmul($this->matrix->getPoint(1, 2), $this->matrix->getPoint(2, 1), $this->precision);
            return bcsub($mainDiagonal, $secondaryDiagonal, $this->precision);
        }
    }
}
