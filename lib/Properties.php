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
        for ($row=1; $row<=$this->matrix->getNumRows(); $row++)
        {
            for ($col=1; $col<=$this->matrix->getNumCols(); $col++)
            {
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
        for ($row=1; $row<=$this->matrix->getNumCols(); $row++)
        {
            for ($col=$row+1; $col<=$this->matrix->getNumRows(); $col++)
            {
                if (
                    $this->matrix->getPoint($row, $col, $this->precision)!==$this->valueZero
                    ||
                    $this->matrix->getPoint($col, $row, $this->precision)!==$this->valueZero
                ) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isScalar()
    {
        if (!$this->isDiagonal()) {
            return false;
        }
        $dato = $this->matrix->getPoint(1, 1, $this->precision);
        for ($m=2; $m<=$this->matrix->getNumRows(); $m++) {
            if ($dato !== $this->matrix->getPoint($m, $m)) {
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
        return $this->getNumRows() == 1 && $this->getNumCols() > 1;
    }

    /**
     * @return bool
     */
    public function isColVector()
    {
        return $this->getNumRows() > 1 && $this->getNumCols() == 1;
    }

    /**
     * @param $functionName
     * @return string
     */
    private function privateSumDiagonal($functionName)
    {
        $sum = $this->valueZero;
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            $sum = bcadd(
                $sum,
                call_user_func(
                    array($this, $functionName),
                    $this->getPoint($i, $i, $this->precision)
                ),
                $this->precision
            );
        }
        return $sum;
    }

    private function equalZero($a)
    {
        return bccomp($a, $this->valueZero, $this->precision) == 0;
    }

    private function equalUnit($a)
    {
        return bccomp($a, $this->valueOne, $this->precision) == 0;
    }

    private function greaterZero($a)
    {
        return bccomp($a, $this->valueZero, $this->precision) >= 0;
    }

    private function privateIsTriangularUpperZero()
    {
        $isZero = true;
        for ($i = 1; $i <= $this->getNumRows() && $isZero; $i++) {
            for ($j = $i + 1; $j <= $this->getNumCols() && $isZero; $j++) {
                if (bccomp($this->valueZero, $this->getPoint($i, $j, $this->precision), $this->precision) != 0) {
                    $isZero = false;
                }
            }
        }
        return $isZero;
    }

    private function privateIsTriangularLowerZero()
    {
        $isZero = true;
        for ($i = 2; $i <= $this->getNumRows() && $isZero; $i++) {
            for ($j = 1; $j < $i && $isZero; $j++) {
                if (bccomp($this->valueZero, $this->getPoint($i, $j, $this->precision), $this->precision) != 0) {
                    $isZero = false;
                }
            }
        }
        return $isZero;
    }

    public function isTriangularUpper()
    {
        return
            $this->isSquare() &&
            $this->getNumRows() > 1 &&
            !$this->privateIsDiagonalZero() &&
            $this->privateIsTriangularLowerZero() &&
            !$this->privateIsTriangularUpperZero();
    }

    public function isTriangularLower()
    {
        return
            $this->isSquare() &&
            $this->getNumRows() > 1 &&
            !$this->privateIsDiagonalZero() &&
            !$this->privateIsTriangularLowerZero() &&
            $this->privateIsTriangularUpperZero();
    }

    public function isDiagonalScalar()
    {
        $isScalar = $this->isDiagonal();

        if ($isScalar) {
            $origin = $this->getPoint(1, 1);
            if (
                bccomp($this->valueZero, $origin, $this->precision)==0 ||
                bccomp($this->valueOne, $origin, $this->precision)==1
            ) {
                $isScalar = false;
            }
            for ($i = 2; $i <= $this->getNumRows() && $isScalar; $i++) {
                if (bccomp($this->getPoint($i - 1, $i - 1), $origin, $this->precision)==0) {
                    $isScalar = false;
                }
            }
        }
        return $isScalar;
    }

    /**
     * @param MatrixBase $base
     * @param int|null $precision
     * @return bool
     */
    public function isMatrixEquals(MatrixBase $base, $precision = null)
    {
        $precision = $this->getPrecision($precision);
        if ($this->getNumCols() != $base->getNumCols()) {
            return false;
        }
        if ($this->getNumRows() != $base->getNumRows()) {
            return false;
        }
        $equals = true;
        for ($i = 1; $i <= $this->getNumRows() && $equals; $i++) {
            for ($j = 1; $j <= $this->getNumCols() && $equals; $j++) {
                $equals = bccomp(
                    $this->getPoint($i, $j, $precision),
                    $base->getPoint($i, $j, $precision),
                    $this->precision
                ) == 0;
            }
        }
        return $equals;
    }

    /**
     * @param int|null $precision
     * @return MatrixBase
     */
    public function transposed($precision = null)
    {
        $precision = $this->getPrecision($precision);
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumCols(), $this->getNumRows(), $precision);
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->getNumCols(); $j++) {
                $tr->setPoint($j, $i, $this->getPoint($i, $j, $precision), $precision);
            }
        }
        return $tr;
    }

    public function isSymmetric()
    {
        $tr = $this->transposed();
        return $this->isMatrixEquals($tr);
    }

    /**
     * @param $precision
     * @return int
     */
    private function getPrecision($precision)
    {
        return is_null($precision) ? $this->precision : (int)$precision;
    }
}
