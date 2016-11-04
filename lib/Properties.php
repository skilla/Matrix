<?php
/**
 * Created by PhpStorm.
 * User: Sergio Zambrano Delfa <sergio.zambrano@gmail.com>
 * Date: 02/11/16
 * Time: 11:19
 */

namespace Skilla\Matrix;

class Properties
{
    private $matrix;
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
    }

    /**
     * @return bool
     */
    public function isSquare()
    {
        return $this->matrix->getNumRows() === $this->matrix->getNumCols();
    }

    /**
     * @return bool
     */
    public function isZero()
    {
        for ($row=1; $row<=$this->matrix->getNumRows(); $row++) {
            for ($col=1; $col<=$this->matrix->getNumCols(); $col++) {
                if ($this->matrix->getPoint($row, $col)!==$this->valueZero) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @see isZero
     * @return bool
     */
    public function isNull()
    {
        return $this->isZero();
    }

    /**
     * @return bool
     */
    public function isDiagonal()
    {
        if (!$this->isSquare()) {
            return false;
        }
        for ($row=1; $row<=$this->matrix->getNumRows(); $row++) {
            for ($col=$row+1; $col<=$this->matrix->getNumCols(); $col++) {
                if ( !$this->pointIsZero($row, $col) || !$this->pointIsZero($col, $row)) {
                    return false;
                }
            }
        }
        return true;
    }

    private function pointIsZero($row, $col)
    {
        return $this->matrix->getPoint($row, $col, $this->precision)===$this->valueZero;
    }

    /**
     * @return bool
     */
    public function isScalar()
    {
        if (!$this->isDiagonal()) {
            return false;
        }
        $value = $this->matrix->getPoint(1, 1, $this->precision);
        for ($m=2; $m<=$this->matrix->getNumRows(); $m++) {
            if ($value !== $this->matrix->getPoint($m, $m, $this->precision)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isDiagonalUnit()
    {
        return $this->checkDiagonal($this->valueOne);
    }

    /**
     * @return bool
     */
    public function isDiagonalZero()
    {
        return $this->checkDiagonal($this->valueZero);
    }

    /**
     * @return bool
     */
    private function checkDiagonal($value)
    {
        if (!$this->isDiagonal()) {
            return false;
        }
        for ($m=1; $m<=$this->matrix->getNumRows(); $m++) {
            if ($value !== $this->matrix->getPoint($m, $m, $this->precision)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isRowVector()
    {
        return $this->matrix->getNumRows() === 1 && $this->matrix->getNumCols() >= 1;
    }

    /**
     * @return bool
     */
    public function isColVector()
    {
        return $this->matrix->getNumRows() >= 1 && $this->matrix->getNumCols() === 1;
    }

    /**
     * @param Matrix $base
     * @return bool
     */
    public function isEquals(Matrix $base)
    {
        $local = $this->matrix;
        $precision = $this->precision;

        if ($local->getNumCols() !== $base->getNumCols() || $local->getNumRows() !== $base->getNumRows()) {
            return false;
        }

        for ($i = 1; $i <= $local->getNumRows(); $i++) {
            for ($j = 1; $j <= $local->getNumCols(); $j++) {
                if ($local->getPoint($i, $j, $precision) !== $base->getPoint($i, $j, $precision)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isSymmetric()
    {
        if (!$this->isSquare()) {
            return false;
        }

        $local = $this->matrix;
        $precision = $this->precision;

        for ($i = 1; $i <= $local->getNumRows(); $i++) {
            for ($j = $i; $j <= $local->getNumCols(); $j++) {
                if ($local->getPoint($i, $j, $precision) !== $local->getPoint($j, $i, $precision)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function isTriangularUpper()
    {
        if (!$this->isSquare()) {
            return false;
        }

        $local = $this->matrix;
        $precision = $this->precision;

        $valuesOnUpper = false;
        for ($i=1; $i<=$local->getNumRows(); $i++) {
            for ($j=1; $j<=$local->getNumCols(); $j++) {
                $hasValue = $local->getPoint($i, $j, $precision) > $this->valueZero;
                if ($i <= $j) {
                    $valuesOnUpper = $valuesOnUpper || $hasValue;
                } else {
                    if ($hasValue) {
                        return false;
                    }
                }
            }
        }
        return $valuesOnUpper;
    }

    public function isTriangularLower()
    {
        if (!$this->isSquare()) {
            return false;
        }

        $local = $this->matrix;
        $precision = $this->precision;

        $valuesOnLower = false;
        for ($i=1; $i<=$local->getNumRows(); $i++) {
            for ($j=1; $j<=$local->getNumCols(); $j++) {
                $hasValue = $local->getPoint($i, $j, $precision) > $this->valueZero;
                if ($i >= $j) {
                    $valuesOnLower = $valuesOnLower || $hasValue;
                } else {
                    if ($hasValue) {
                        return false;
                    }
                }
            }
        }
        return $valuesOnLower;
    }
}
