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
                    $this->matriz[$i][$j] = 0;
                }
            }
        }
    }

    /**
     * @return int
     */
    public function getNumRows()
    {
        if (is_null($this->m)) {
            $this->m = count($this->matriz);
        }
        return $this->m;
    }

    /**
     * @return int
     */
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

    /**
     * @param $row
     * @param $col
     * @return int
     */
    public function getPoint($row, $col)
    {
        return $this->matriz[$row][$col];
    }

    /**
     * @param $row
     * @param $col
     * @param $value
     */
    public function setPoint($row, $col, $value)
    {
        $this->matriz[$row][$col] = $value;
    }

    /**
     * @param $row
     * @return MatrixBase
     */
    public function getRow($row)
    {
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class(1, $this->getNumCols());
        for ($j=1; $j<=$this->getNumCols(); $j++) {
            $tr->setPoint(1, $j, $this->getPoint($row, $j));
        }
        return $tr;
    }

    /**
     * @param $row
     * @param MatrixBase $base
     */
    public function setRow($row, MatrixBase $base)
    {
        for ($j=1; $j<=$this->getNumCols(); $j++) {
            $this->setPoint($row, $j, $base->getPoint(1, $j));
        }
    }

    /**
     * @param $col
     * @return MatrixBase
     */
    public function getCol($col)
    {
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), 1);
        for ($i=1; $i<=$this->getNumRows(); $i++) {
            $tr->setPoint($i, 1, $this->getPoint($i, $col));
        }
        return $tr;
    }

    /**
     * @param $col
     * @param MatrixBase $base
     */
    public function setCol($col, MatrixBase $base)
    {
        for ($i=1; $i<=$this->getNumCols(); $i++) {
            $this->setPoint($i, 1, $base->getPoint($i, $col));
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
        return $this->getNumRows() == 1 && $this->getNumCols() == 1;
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
     * @return int
     */
    private function privateSumDiagonal($functionName)
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
                if ($this->getPoint($i, $j) != 0) {
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

    public function transposed()
    {
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumCols(), $this->getNumRows());
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->getNumCols(); $j++) {
                $tr->setPoint($j, $i, $this->getPoint($i, $j));
            }
        }
        return $tr;
    }

    public function isSymmetric()
    {
        $tr = $this->transposed();
        return $this->isMatrixEquals($tr);
    }

    public function getAdjunto($i, $j)
    {
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows() - 1, $this->getNumCols() - 1);
        for ($m=1; $m<=$this->getNumRows(); $m++) {
            for ($n=1; $n<=$this->getNumCols(); $n++) {
                if ($m==$i || $n==$j) {
                    continue;
                }
                $row = ($m<$i) ? $m : $m-1;
                $col = ($n<$j) ? $n : $n-1;
                $tr->setPoint($row, $col, $this->getPoint($m, $n));
            }
        }
        return $tr;
    }

    public function determinant()
    {
        $determinante = 0;
        if ($this->isSquare()) {
            if ($this->getNumRows()==1) {
                $determinante = $this->getPoint(1, 1);
            }
            if ($this->getNumRows()==2) {
                $determinante =
                    ($this->getPoint(1, 1) * $this->getPoint(2, 2)) -
                    ($this->getPoint(1, 2) * $this->getPoint(2, 1));
            }
            if ($this->getNumRows()==3) {
                $determinante =
                    ($this->getPoint(1, 1) * $this->getPoint(2, 2) * $this->getPoint(3, 3)) +
                    ($this->getPoint(1, 2) * $this->getPoint(2, 3) * $this->getPoint(3, 1)) +
                    ($this->getPoint(1, 3) * $this->getPoint(2, 1) * $this->getPoint(3, 2)) -
                    ($this->getPoint(1, 3) * $this->getPoint(2, 2) * $this->getPoint(3, 1)) -
                    ($this->getPoint(1, 2) * $this->getPoint(2, 1) * $this->getPoint(3, 3)) -
                    ($this->getPoint(1, 1) * $this->getPoint(2, 3) * $this->getPoint(3, 2));
            }
            if ($this->getNumRows()>3) {
                $determinante = 0;
                for ($j=1; $j<=$this->getNumCols(); $j++) {
                    $determinante += $this->getPoint(1, $j) * pow(-1, 1+$j) * $this->getAdjunto(1, $j)->determinant();
                }
                return $determinante;
            }
        }
        return $determinante;
    }

    public function trace()
    {
        $trace = 0;
        for ($i=1; $i<=$this->getNumRows(); $i++) {
            $trace += $this->getPoint($i, $i);
        }
        return $trace;
    }

    public function summation(MatrixBase $base)
    {
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), $this->getNumCols());
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->getNumCols(); $j++) {
                $tr->setPoint($i, $j, $this->getPoint($i, $j) + $base->getPoint($i, $j));
            }
        }
        return $tr;
    }

    /**
     * @param MatrixBase $base
     * @return MatrixBase
     */
    public function subtraction(MatrixBase $base)
    {
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), $this->getNumCols());
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->getNumCols(); $j++) {
                $tr->setPoint($i, $j, $this->getPoint($i, $j) - $base->getPoint($i, $j));
            }
        }
        return $tr;
    }

    /**
     * @param $scalar
     * @return MatrixBase
     */
    public function multiplicationScalar($scalar)
    {
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), $this->getNumCols());
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->getNumCols(); $j++) {
                $tr->setPoint($i, $j, $this->getPoint($i, $j) * $scalar);
            }
        }
        return $tr;
    }

    /**
     * @param $scalar
     * @return MatrixBase
     */
    public function divisionScalar($scalar)
    {
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), $this->getNumCols());
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->getNumCols(); $j++) {
                $tr->setPoint($i, $j, $this->getPoint($i, $j) / $scalar);
            }
        }
        return $tr;
    }

    /**
     * @param MatrixBase $base
     * @return MatrixBase
     */
    public function multiplicationMatrix(MatrixBase $base)
    {
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), $this->getNumCols());
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->getNumCols(); $j++) {
                $suma = 0;
                for ($c=1; $c<=$this->getNumCols(); $c++) {
                    $suma += $this->getPoint($i, $c) * $base->getPoint($c, $j);
                }
                $tr->setPoint($i, $j, $suma);
            }
        }
        return $tr;
    }

    /**
     * @return MatrixBase
     */
    public function inversa()
    {
        $class = get_class($this);
        /**
         * @var MatrixBase $inversa
         */
        $inversa = new $class($this->getNumRows(), $this->getNumCols());
        for ($i=1; $i<=$this->getNumRows(); $i++) {
            $inversa->setPoint($i, $i, 1);
        }
        for ($j=1; $j<$this->getNumCols(); $j++) {
            for ($i=$j+1; $i<=$this->getNumRows(); $i++) {
                if ($this->getPoint($i, $j)!=0) {
                    $opivote  = $this->getRow($j);
                    $ocambio  = $this->getRow($i);
                    $ipivote  = $inversa->getRow($j);
                    $icambio  = $inversa->getRow($i);
                    $opivote1 = $opivote->getPoint(1, $j);
                    $ocambio1 = $ocambio->getPoint(1, $j);
                    if ($ocambio1==$opivote1) {
                        $ocambio = $ocambio->subtraction($opivote);
                        $icambio = $icambio->subtraction($ipivote);
                    } elseif (abs($ocambio1)==abs($opivote1)) {
                        $ocambio = $ocambio->summation($opivote);
                        $icambio = $icambio->summation($ipivote);
                    } else {
                        $opivote  = $opivote->multiplicationScalar(abs($ocambio1));
                        $ocambio  = $ocambio->multiplicationScalar(abs($opivote1));
                        $ipivote  = $ipivote->multiplicationScalar(abs($ocambio1));
                        $icambio  = $icambio->multiplicationScalar(abs($opivote1));
                        if ($this->sign($ocambio1)!=$this->sign($opivote1)) {
                            $ocambio = $ocambio->summation($opivote);
                            $icambio = $icambio->summation($ipivote);
                        } else {
                            $ocambio = $ocambio->subtraction($opivote);
                            $icambio = $icambio->subtraction($ipivote);
                        }
                    }
                    $this->setRow($i, $ocambio);
                    $inversa->setRow($i, $icambio);
                }
            }
        }
        for ($j=$this->getNumCols(); $j>1; $j--) {
            for ($i=$j-1; $i>=1; $i--) {
                if ($this->getPoint($i, $j)!=0) {
                    $opivote  = $this->getRow($j);
                    $ocambio  = $this->getRow($i);
                    $ipivote  = $inversa->getRow($j);
                    $icambio  = $inversa->getRow($i);
                    $opivote1 = $opivote->getPoint(1, $j);
                    $ocambio1 = $ocambio->getPoint(1, $j);
                    if ($ocambio1==$opivote1) {
                        $ocambio = $ocambio->subtraction($opivote);
                        $icambio = $icambio->subtraction($ipivote);
                    } elseif (abs($ocambio1)==abs($opivote1)) {
                        $ocambio = $ocambio->summation($opivote);
                        $icambio = $icambio->summation($ipivote);
                    } else {
                        $opivote  = $opivote->multiplicationScalar(abs($ocambio1));
                        $ocambio  = $ocambio->multiplicationScalar(abs($opivote1));
                        $ipivote  = $ipivote->multiplicationScalar(abs($ocambio1));
                        $icambio  = $icambio->multiplicationScalar(abs($opivote1));
                        if ($this->sign($ocambio1)!=$this->sign($opivote1)) {
                            $ocambio = $ocambio->summation($opivote);
                            $icambio = $icambio->summation($ipivote);
                        } else {
                            $ocambio = $ocambio->subtraction($opivote);
                            $icambio = $icambio->subtraction($ipivote);
                        }
                    }
                    $this->setRow($i, $ocambio);
                    $inversa->setRow($i, $icambio);
                }
            }
        }
        for ($i=1; $i<=$this->getNumRows(); $i++) {
            $irow = $inversa->getRow($i);
            $orow = $this->getRow($i);
            $irow = $irow->divisionScalar($orow->getPoint(1, $i));
            $orow = $orow->divisionScalar($orow->getPoint(1, $i));
            $inversa->setRow($i, $irow);
            $this->setRow($i, $orow);
        }
        return $inversa;
    }

    /**
     * @param $number
     * @return int
     */
    private function sign($number)
    {
        return ( $number > 0 ) ? 1 : ( ( $number < 0 ) ? -1 : 0 );
    }
}
