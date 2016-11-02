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
    private $matriz = array();
    private $rows;
    private $cols;
    private $valueZero;
    private $valueOne;
    private $precision;

    /**
     * @param int $m
     * @param int $n
     * @param int|null $precision
     */
    public function __construct($m = 0, $n = 0, $precision = 15)
    {
        $this->precision = (int)$precision;
        $this->valueZero = bcadd(0, 0, $this->precision);
        $this->valueOne = bcadd(0, 1, $this->precision);
        $this->rows = 0;
        $this->cols = 0;
        if ($m>0 && $n>0) {
            for ($i = 1; $i <= $m; $i++) {
                $this->matriz[$i] = array();
                for ($j = 1; $j <= $n; $j++) {
                    $this->matriz[$i][$j] = $this->valueZero;
                }
            }
            $this->rows = $m;
            $this->cols = $n;
        }
    }

    /**
     * @return int
     */
    public function getNumRows()
    {
        return $this->rows;
    }

    /**
     * @return int
     */
    public function getNumCols()
    {
        return $this->cols;
    }

    /**
     * @param int $row
     * @param int $col
     * @param int|null $precision
     * @return string
     */
    public function getPoint($row, $col, $precision = null)
    {
        return is_null($precision) ? $this->matriz[$row][$col] : bcadd($this->matriz[$row][$col], 0, (int)$precision);
    }

    /**
     * @param int $row
     * @param int $col
     * @param int|string $value
     * @param int|null $precision
     */
    public function setPoint($row, $col, $value, $precision = null)
    {
        $precision = $this->getPrecision($precision);
        $this->matriz[$row][$col] = bcadd($this->valueZero, $value, $precision);
    }

    /**
     * @param int $row
     * @param int|null $precision
     * @return MatrixBase
     */
    public function getRow($row, $precision = null)
    {
        $precision = $this->getPrecision($precision);
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class(1, $this->getNumCols(), $precision);
        for ($j=1; $j<=$this->getNumCols(); $j++) {
            $tr->setPoint(1, $j, $this->getPoint($row, $j, $precision), $precision);
        }
        return $tr;
    }

    /**
     * @param int $row
     * @param MatrixBase $base
     * @param int|null $precision
     */
    public function setRow($row, MatrixBase $base, $precision = null)
    {
        $precision = $this->getPrecision($precision);
        for ($j=1; $j<=$this->getNumCols(); $j++) {
            $this->setPoint($row, $j, $base->getPoint(1, $j, $precision), $precision);
        }
    }

    /**
     * @param int $col
     * @param int|null $precision
     * @return MatrixBase
     */
    public function getCol($col, $precision = null)
    {
        $precision = $this->getPrecision($precision);
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), 1, $precision);
        for ($i=1; $i<=$this->getNumRows(); $i++) {
            $tr->setPoint($i, 1, $this->getPoint($i, $col, $precision), $precision);
        }
        return $tr;
    }

    /**
     * @param int $col
     * @param MatrixBase $base
     * @param int|null $precision
     */
    public function setCol($col, MatrixBase $base, $precision = null)
    {
        $precision = $this->getPrecision($precision);
        for ($i=1; $i<=$this->getNumCols(); $i++) {
            $this->setPoint($i, 1, $base->getPoint($i, $col, $precision), $precision);
        }
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return $this->matriz;
    }

    /**
     * @return bool
     */
    public function isNull()
    {
        return $this->getNumRows() == 0;
    }

    /**
     * @return bool
     */
    public function isScalar()
    {
        if ($this->getNumRows()===0 || $this->getNumRows() !== $this->getNumCols()) {
            return false;
        }
        $dato = $this->getPoint(1, 1);
        for ($m=2; $m<=$this->getNumRows(); $m++) {
            if ($dato !== $this->getPoint($m, $m)) {
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
     * @return bool
     */
    public function isSquare()
    {
        $isSquare = false;
        if (!$this->isNull()) {
            $m = $this->getNumRows();
            $n = $this->getNumCols();
            $isSquare = $m > 0 && $m == $n;
        }
        return $isSquare;
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

    private function privateIsDiagonalZero()
    {
        $isDiagonal = $this->isSquare() && $this->getNumRows() > 1;
        if ($isDiagonal) {
            $isDiagonal = $this->privateSumDiagonal('equalZero') == $this->getNumRows();
        }
        return $isDiagonal;
    }

    private function privateIsDiagonalUnit()
    {
        $isDiagonal = $this->isSquare() && $this->getNumRows() > 1;
        if ($isDiagonal) {
            $isDiagonal = $this->privateSumDiagonal('equalUnit') == $this->getNumRows();
        }
        return $isDiagonal;
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

    public function isZero()
    {
        return
            $this->isSquare() &&
            $this->getNumRows() > 1 &&
            $this->privateIsDiagonalZero() &&
            $this->privateIsTriangularLowerZero() &&
            $this->privateIsTriangularUpperZero();
    }

    public function isDiagonal()
    {
        return
            $this->isSquare() &&
            $this->getNumRows() > 1 &&
            $this->privateIsTriangularUpperZero() &&
            $this->privateIsTriangularLowerZero() &&
            !$this->privateIsDiagonalZero();
    }

    public function isDiagonalUnit()
    {
        return $this->isDiagonal() && $this->privateIsDiagonalUnit();
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
