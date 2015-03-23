<?php
/**
 * Created by PhpStorm.
 * User: Sergio Zambrano Delfa <sergio.zambrano@gmail.com>
 * Date: 22/2/15
 * Time: 18:22
 */

namespace Skilla\Matrix;

class MatrixBase
{
    private $matriz = array();
    private $m;
    private $n;
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
        if ($m>0 && $n>0) {
            for ($i = 1; $i <= $m; $i++) {
                $this->matriz[$i] = array();
                for ($j = 1; $j <= $n; $j++) {
                    $this->matriz[$i][$j] = $this->valueZero;
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
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        $this->matriz[$row][$col] = bcadd($this->valueZero, $value, $precision);
    }

    /**
     * @param int $row
     * @param int|null $precision
     * @return MatrixBase
     */
    public function getRow($row, $precision = null)
    {
        $precision = is_null($precision) ? $this->precision : (int)$precision;
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
        $precision = is_null($precision) ? $this->precision : (int)$precision;
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
        $precision = is_null($precision) ? $this->precision : (int)$precision;
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
        $precision = is_null($precision) ? $this->precision : (int)$precision;
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
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        if ($this->getNumRows() != $base->getNumRows()) {
            return false;
        }
        if ($this->getNumCols() != $base->getNumCols()) {
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
        $precision = is_null($precision) ? $this->precision : (int)$precision;
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
     * @param $i
     * @param $j
     * @param int|null $precision
     * @return MatrixBase
     */
    public function getAdjunto($i, $j, $precision = null)
    {
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows() - 1, $this->getNumCols() - 1, $precision);
        for ($m=1; $m<=$this->getNumRows(); $m++) {
            for ($n=1; $n<=$this->getNumCols(); $n++) {
                if ($m==$i || $n==$j) {
                    continue;
                }
                $row = ($m<$i) ? $m : $m-1;
                $col = ($n<$j) ? $n : $n-1;
                $tr->setPoint($row, $col, $this->getPoint($m, $n, $precision), $precision);
            }
        }
        return $tr;
    }

    public function determinant($precision = null)
    {
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        $determinante = $this->valueZero;
        if ($this->isSquare()) {
            if ($this->getNumRows()==1) {
                $determinante = $this->getPoint(1, 1, $precision);
            }
            if ($this->getNumRows()==2) {
                $determinante = bcsub(
                    bcmul(
                        $this->getPoint(1, 1, $precision),
                        $this->getPoint(2, 2, $precision),
                        $precision
                    ),
                    bcmul(
                        $this->getPoint(1, 2, $precision),
                        $this->getPoint(2, 1, $precision),
                        $precision
                    ),
                    $precision
                );
            }
            if ($this->getNumRows()==3) {
                $determinante = bcadd(0, 0, $precision);
                $punto1 = bcmul(
                    $this->getPoint(1, 1, $precision),
                    $this->getPoint(2, 2, $precision),
                    $precision
                );
                $punto1 = bcmul(
                    $punto1,
                    $this->getPoint(3, 3, $precision),
                    $precision
                );
                $determinante = bcadd($determinante, $punto1, $determinante);
                $punto1 = bcmul(
                    $this->getPoint(1, 2, $precision),
                    $this->getPoint(2, 3, $precision),
                    $precision
                );
                $punto1 = bcmul(
                    $punto1,
                    $this->getPoint(3, 1, $precision),
                    $precision
                );
                $determinante = bcadd($determinante, $punto1, $determinante);
                $punto1 = bcmul(
                    $this->getPoint(1, 3, $precision),
                    $this->getPoint(2, 1, $precision),
                    $precision
                );
                $punto1 = bcmul(
                    $punto1,
                    $this->getPoint(3, 2, $precision),
                    $precision
                );
                $determinante = bcadd($determinante, $punto1, $determinante);
                $punto1 = bcmul(
                    $this->getPoint(3, 1, $precision),
                    $this->getPoint(2, 2, $precision),
                    $precision
                );
                $punto1 = bcmul(
                    $punto1,
                    $this->getPoint(1, 3, $precision),
                    $precision
                );
                $determinante = bcsub($determinante, $punto1, $determinante);
                $punto1 = bcmul(
                    $this->getPoint(3, 2, $precision),
                    $this->getPoint(2, 3, $precision),
                    $precision
                );
                $punto1 = bcmul(
                    $punto1,
                    $this->getPoint(1, 1, $precision),
                    $precision
                );
                $determinante = bcsub($determinante, $punto1, $determinante);
                $punto1 = bcmul(
                    $this->getPoint(3, 3, $precision),
                    $this->getPoint(2, 1, $precision),
                    $precision
                );
                $punto1 = bcmul(
                    $punto1,
                    $this->getPoint(1, 2, $precision),
                    $precision
                );
                $determinante = bcsub($determinante, $punto1, $determinante);
            }
            if ($this->getNumRows()>3) {
                $determinante = $this->valueZero;
                for ($j=1; $j<=$this->getNumCols(); $j++) {
                    $determinante = bcadd(
                        $determinante,
                        bcmul(
                            bcmul(
                                $this->getPoint(1, $j, $precision),
                                pow(-1, 1+$j),
                                $precision
                            ),
                            $this->getAdjunto(1, $j)->determinant(),
                            $precision
                        ),
                        $precision
                    );
                }
            }
        }
        return $determinante;
    }

    /**
     * @param int|null $precision
     * @return string
     */
    public function trace($precision = null)
    {
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        $trace = bcadd(0, 0, $precision);
        for ($i=1; $i<=$this->getNumRows(); $i++) {
            $trace = bcadd(
                $trace,
                $this->getPoint($i, $i, $precision),
                $precision
            );
        }
        return $trace;
    }

    /**
     * @param MatrixBase $base
     * @param int|null $precision
     * @return MatrixBase
     */
    public function summation(MatrixBase $base, $precision = null)
    {
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), $this->getNumCols(), $precision);
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->getNumCols(); $j++) {
                $tr->setPoint(
                    $i,
                    $j,
                    bcadd(
                        $this->getPoint($i, $j, $precision),
                        $base->getPoint($i, $j, $precision),
                        $precision
                    )
                );
            }
        }
        return $tr;
    }

    /**
     * @param MatrixBase $base
     * @param int|null $precision
     * @return MatrixBase
     */
    public function subtraction(MatrixBase $base, $precision = null)
    {
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), $this->getNumCols(), $precision);
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->getNumCols(); $j++) {
                $tr->setPoint(
                    $i,
                    $j,
                    bcsub(
                        $this->getPoint($i, $j, $precision),
                        $base->getPoint($i, $j, $precision),
                        $precision
                    )
                );
            }
        }
        return $tr;
    }

    /**
     * @param $scalar
     * @param int|null $precision
     * @return MatrixBase
     */
    public function multiplicationScalar($scalar, $precision = null)
    {
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), $this->getNumCols(), $precision);
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->getNumCols(); $j++) {
                $tr->setPoint(
                    $i,
                    $j,
                    bcmul(
                        $this->getPoint($i, $j, $precision),
                        $scalar,
                        $precision
                    )
                );
            }
        }
        return $tr;
    }

    /**
     * @param $scalar
     * @param int|null $precision
     * @return MatrixBase
     */
    public function divisionScalar($scalar, $precision = null)
    {
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), $this->getNumCols(), $precision);
        for ($i = 1; $i <= $this->getNumRows(); $i++) {
            for ($j = 1; $j <= $this->getNumCols(); $j++) {
                $tr->setPoint(
                    $i,
                    $j,
                    bcdiv(
                        $this->getPoint($i, $j, $precision),
                        $scalar,
                        $precision
                    )
                );
            }
        }
        return $tr;
    }

    /**
     * @param MatrixBase $base
     * @param int|null $precision
     * @return MatrixBase
     */
    public function multiplicationMatrix(MatrixBase $base, $precision = null)
    {
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        $class = get_class($this);
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), $base->getNumCols(), $precision);
        for ($i = 1; $i <= $tr->getNumRows(); $i++) {
            for ($j = 1; $j <= $tr->getNumCols(); $j++) {
                $suma = bcadd(0, 0, $precision);
                for ($c=1; $c<=$tr->getNumCols(); $c++) {
                    $suma = bcadd(
                        $suma,
                        bcmul(
                            $this->getPoint($i, $c, $precision),
                            $base->getPoint($c, $j, $precision),
                            $precision
                        ),
                        $precision
                    );
                }
                $tr->setPoint($i, $j, $suma, $precision);
            }
        }
        return $tr;
    }

    /**
     * @return MatrixBase
     */
    public function inversa($precision = null)
    {
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        $class = get_class($this);
        /**
         * @var MatrixBase $inversa
         */
        $inversa = new $class($this->getNumRows(), $this->getNumCols(), $precision);
        for ($i=1; $i<=$this->getNumRows(); $i++) {
            $inversa->setPoint($i, $i, 1, $precision);
        }
        /**
         * @var MatrixBase $copia
         */
        $copia = new $class($this->getNumRows(), $this->getNumCols(), $precision);
        for ($i=1; $i<=$this->getNumRows(); $i++) {
            for ($j=1; $j<=$this->getNumRows(); $j++) {
                $copia->setPoint($i, $j, $this->getPoint($i, $j, $precision), $precision);
            }
        }
        for ($j=1; $j<$copia->getNumCols(); $j++) {
            for ($i=$j+1; $i<=$copia->getNumRows(); $i++) {
                if ($copia->getPoint($i, $j)!=0) {
                    $opivote  = $copia->getRow($j, $precision);
                    $ocambio  = $copia->getRow($i, $precision);
                    $ipivote  = $inversa->getRow($j, $precision);
                    $icambio  = $inversa->getRow($i, $precision);
                    $opivote1 = $opivote->getPoint(1, $j, $precision);
                    $ocambio1 = $ocambio->getPoint(1, $j, $precision);
                    if ($ocambio1==$opivote1) {
                        $ocambio = $ocambio->subtraction($opivote, $precision);
                        $icambio = $icambio->subtraction($ipivote, $precision);
                    } elseif (abs($ocambio1)==abs($opivote1)) {
                        $ocambio = $ocambio->summation($opivote, $precision);
                        $icambio = $icambio->summation($ipivote, $precision);
                    } else {
                        $opivote  = $opivote->multiplicationScalar($this->bcabs($ocambio1), $precision);
                        $ocambio  = $ocambio->multiplicationScalar($this->bcabs($opivote1), $precision);
                        $ipivote  = $ipivote->multiplicationScalar($this->bcabs($ocambio1), $precision);
                        $icambio  = $icambio->multiplicationScalar($this->bcabs($opivote1), $precision);
                        if ($copia->bcsign($ocambio1)!=$copia->bcsign($opivote1)) {
                            $ocambio = $ocambio->summation($opivote, $precision);
                            $icambio = $icambio->summation($ipivote, $precision);
                        } else {
                            $ocambio = $ocambio->subtraction($opivote, $precision);
                            $icambio = $icambio->subtraction($ipivote, $precision);
                        }
                    }
                    $copia->setRow($i, $ocambio, $precision);
                    $inversa->setRow($i, $icambio, $precision);
                }
            }
        }
        for ($j=$copia->getNumCols(); $j>1; $j--) {
            for ($i=$j-1; $i>=1; $i--) {
                if (bccomp($copia->getPoint($i, $j, $precision), bcadd(0, 0, $precision), $precision)!=0) {
                    $opivote  = $copia->getRow($j, $precision);
                    $ocambio  = $copia->getRow($i, $precision);
                    $ipivote  = $inversa->getRow($j, $precision);
                    $icambio  = $inversa->getRow($i, $precision);
                    $opivote1 = $opivote->getPoint(1, $j, $precision);
                    $ocambio1 = $ocambio->getPoint(1, $j, $precision);
                    if (bccomp($ocambio1, $opivote1, $precision)==0) {
                        $ocambio = $ocambio->subtraction($opivote, $precision);
                        $icambio = $icambio->subtraction($ipivote, $precision);
                    } elseif (bccomp($this->bcabs($ocambio1), $this->bcabs($opivote1))==0) {
                        $ocambio = $ocambio->summation($opivote, $precision);
                        $icambio = $icambio->summation($ipivote, $precision);
                    } else {
                        $opivote  = $opivote->multiplicationScalar($this->bcabs($ocambio1), $precision);
                        $ocambio  = $ocambio->multiplicationScalar($this->bcabs($opivote1), $precision);
                        $ipivote  = $ipivote->multiplicationScalar($this->bcabs($ocambio1), $precision);
                        $icambio  = $icambio->multiplicationScalar($this->bcabs($opivote1), $precision);
                        if ($copia->bcsign($ocambio1)!=$copia->bcsign($opivote1)) {
                            $ocambio = $ocambio->summation($opivote, $precision);
                            $icambio = $icambio->summation($ipivote, $precision);
                        } else {
                            $ocambio = $ocambio->subtraction($opivote, $precision);
                            $icambio = $icambio->subtraction($ipivote, $precision);
                        }
                    }
                    $copia->setRow($i, $ocambio, $precision);
                    $inversa->setRow($i, $icambio, $precision);
                }
            }
        }
        for ($i=1; $i<=$copia->getNumRows(); $i++) {
            $irow = $inversa->getRow($i);
            $orow = $copia->getRow($i);
            $irow = $irow->divisionScalar($orow->getPoint(1, $i, $precision), $precision);
            $orow = $orow->divisionScalar($orow->getPoint(1, $i, $precision), $precision);
            $inversa->setRow($i, $irow, $precision);
            $copia->setRow($i, $orow, $precision);
        }
        return $inversa;
    }

    /**
     * @param string $number
     * @return int
     */
    private function bcsign($number)
    {
        return bccomp($number, $this->valueZero, $this->precision);
    }

    /**
     * @param string $number
     * @return string
     */
    private function bcabs($number)
    {
        return bcmul($number, $this->bcsign($number), $this->precision);
    }

    /**
     * @param int|null $precision
     * @return string
     */
    public function bcpi($precision = null)
    {
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        $limit = ceil(log($precision)/log(2))-1;
        $precision = $precision+6;
        $a = 1;
        $b = bcdiv(1, bcsqrt(2, $precision), $precision);
        $t = 1/4;
        $p = 1;
        $n = 0;
        while ($n < $limit) {
            $x = bcdiv(bcadd($a, $b, $precision), 2, $precision);
            $y = bcsqrt(bcmul($a, $b, $precision), $precision);
            $t = bcsub($t, bcmul($p, bcpow(bcsub($a, $x, $precision), 2, $precision), $precision), $precision);
            $a = $x;
            $b = $y;
            $p = bcmul(2, $p, $precision);
            ++$n;
        }
        return bcdiv(bcpow(bcadd($a, $b, $precision), 2, $precision), bcmul(4, $t, $precision), $precision);
    }

    public function pretty($precision = null)
    {
        $precision = is_null($precision) ? $this->precision : (int)$precision;
        for ($j=1; $j<=$this->getNumCols(); $j++) {
            for ($i = 1; $i <= $this->getNumRows(); $i++) {
                echo str_pad($this->getPoint($i, $j), $precision+10, ' ', STR_PAD_LEFT)."  ";
            }
            echo "\n";
        }
    }
}
