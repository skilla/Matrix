<?php
/**
 * Created by PhpStorm.
 * User: Sergio Zambrano Delfa <sergio.zambrano@gmail.com>
 * Date: 22/2/15
 * Time: 18:22
 */

namespace skilla\matrix;

class MatrixBase
{
    private $matriz = array();
    private $m;
    private $n;

    public function __construct($m = 0, $n = 0)
    {
        if ($m>0 && $n>0) {
            for ($i = 1; $i <= $m; $i++) {
                $this->matriz[$i] = array();
                for ($j = 1; $j <= $n; $j++) {
                    $this->matriz[$i][$j] == 0;
                }
            }
        }
    }

    public function getNumRows()
    {
        if (is_null($this->m)) {
            $this->m = count($this->matriz);
        }
        return $this->m;
    }

    public function getNumCols()
    {
        if (is_null($this->n)) {
            if ($this->getNumRows() > 0) {
                $this->n = count($this->matriz[1]);
            } else {
                $this->n = 0;
            }
        }
        return $this->n;
    }

    public function isNull()
    {
        return $this->getNumRows() == 0;
    }

    public function isScalar()
    {
        return $this->getNumRows() == 1 && $this->getNumCols() == 1;
    }

    public function isRowVector()
    {
        return $this->getNumRows() == 1 && $this->getNumCols() > 1;
    }

    public function isColVector()
    {
        return $this->getNumRows() > 1 && $this->getNumCols() == 1;
    }

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

    private function _sumDiagonal($functionName)
    {
        $sum = 0;
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            $sum += (int)call_user_func(array($this, $functionName), $this->getPoint($i, $i));
        }
        return $sum;
    }

    private function equalZero($a)
    {
        return $a == 0;
    }

    private function equalUnit($a)
    {
        return $a == 1;
    }

    private function greaterZero($a)
    {
        return $a >= 1;
    }

    private function _isDiagonalZero()
    {
        $isDiagonal = $this->isSquare() && $this->getNumRows() > 1;
        if ($isDiagonal) {
            $isDiagonal = $this->_sumDiagonal('equalZero') == $this->getNumRows();
        }
        return $isDiagonal;
    }

    private function _isDiagonalUnit()
    {
        $isDiagonal = $this->isSquare() && $this->getNumRows() > 1;
        if ($isDiagonal) {
            $isDiagonal = $this->_sumDiagonal('equalUnit') == $this->getNumRows();
        }
        return $isDiagonal;
    }

    private function _isTriangularUpperZero()
    {
        $isZero = true;
        for ($i = 1; $i <= $this->getNumRows() && $isZero; $i++) {
            for ($j = $i + 1; $j <= $this->getNumCols() && $isZero; $j++) {
                if ($this->getPoint($i, $j) != 0) {
                    $isZero = false;
                }
            }
        }
        return $isZero;
    }

    private function _isTriangularLowerZero()
    {
        $isZero = true;
        for ($i = 2; $i <= $this->getNumRows() && $isZero; $i++) {
            for ($j = 1; $j < $i && $isZero; $j++) {
                if ($this->getPoint($i, $j) != 0) {
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
            !$this->_isDiagonalZero() &&
            $this->_isTriangularLowerZero() &&
            !$this->_isTriangularUpperZero();
    }

    public function isTriangularLower()
    {
        return
            $this->isSquare() &&
            $this->getNumRows() > 1 &&
            !$this->_isDiagonalZero() &&
            !$this->_isTriangularLowerZero() &&
            $this->_isTriangularUpperZero();
    }

    public function isDiagonal()
    {
        return
            $this->isSquare() &&
            $this->getNumRows() > 1 &&
            $this->_isTriangularUpperZero() &&
            $this->_isTriangularLowerZero() &&
            !$this->_isDiagonalZero();
    }

    public function isDiagonalUnit()
    {
        return $this->isDiagonal() && $this->_isDiagonalUnit();
    }

    public function isDiagonalScalar()
    {
        $isScalar = $this->isDiagonal();

        if ($isScalar) {
            $origin = $this->getPoint(1, 1);
            if ($origin == 0 || $origin == 1) {
                $isScalar = false;
            }
            for ($i = 2; $i <= $this->getNumRows() && $isScalar; $i++) {
                if ($this->getPoint($i - 1, $i - 1) != $origin) {
                    $isScalar = false;
                }
            }
        }
        return $isScalar;
    }

    public function getPoint($row, $col)
    {
        return $this->matriz[$row][$col];
    }

    public function setPoint($row, $col, $value)
    {
        $this->matriz[$row][$col] = $value;
    }

    public function isMatrixEquals(MatrixBase $base)
    {
        if ($this->getNumRows() != $base->getNumRows()) {
            return false;
        }
        if ($this->getNumCols() != $base->getNumCols()) {
            return false;
        }
        $equals = true;
        for ($i = 1; $i < $this->getNumRows() && $equals; $i++) {
            for ($j = 1; $j < $this->getNumCols() && $equals; $j++) {
                $equals = $this->getPoint($i, $j) == $base->getPoint($i, $j);
            }
        }
        return $equals;
    }

    public function tr()
    {
        $class = get_class($this);
        $tr = new $class($this->getNumRows(), $this->getNumCols());
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->getNumCols(); $j++) {
                $tr->setPoint($j, $i, $this->getPoint($i, $j));
            }
        }
        return $tr;
    }

    public function isSymmetric()
    {
        $tr = $this->tr();
        return $this->isMatrixEquals($tr);
    }

    public function determinante()
    {
        if ($this->isSquare()) {
            if ($this->getNumRows()==2) {
                return
                    ($this->getPoint(1, 1) * $this->getPoint(2, 2)) -
                    ($this->getPoint(1, 2) * $this->getPoint(2, 1));
            }
            if ($this->getNumRows()==3) {
                return
                    ($this->getPoint(1, 1) * $this->getPoint(2, 2) * $this->getPoint(3, 3)) +
                    ($this->getPoint(1, 2) * $this->getPoint(2, 3) * $this->getPoint(3, 1)) +
                    ($this->getPoint(1, 3) * $this->getPoint(2, 1) * $this->getPoint(3, 2)) -
                    ($this->getPoint(1, 3) * $this->getPoint(2, 2) * $this->getPoint(3, 1)) -
                    ($this->getPoint(1, 2) * $this->getPoint(2, 1) * $this->getPoint(3, 3)) -
                    ($this->getPoint(1, 1) * $this->getPoint(2, 3) * $this->getPoint(3, 2));
            }
        }
    }
}