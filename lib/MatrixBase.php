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
    private $matrix;
    private $properties;
    private $operations;

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
        $this->m = 0;
        $this->n = 0;
        if ($m>0 && $n>0) {
            for ($i = 1; $i <= $m; $i++) {
                $this->matriz[$i] = array();
                for ($j = 1; $j <= $n; $j++) {
                    $this->matriz[$i][$j] = $this->valueZero;
                }
            }
            $this->m = $m;
            $this->n = $n;
        }
        $this->matrix = new Matrix($m, $n, $precision);
        $this->properties = new Properties($this->matrix, $precision);
        $this->operations = new Operations($this->matrix, $precision);
    }

    /**
     * @return int
     */
    public function getNumRows()
    {
        return $this->matrix->getNumRows();
    }

    /**
     * @return int
     */
    public function getNumCols()
    {
        return $this->matrix->getNumCols();
    }

    /**
     * @param int $row
     * @param int $col
     * @param int|null $precision
     * @return string
     */
    public function getPoint($row, $col, $precision = null)
    {
        return $this->matrix->getPoint($row, $col, $precision);
    }

    /**
     * @param int $row
     * @param int $col
     * @param int|string $value
     * @param int|null $precision
     */
    public function setPoint($row, $col, $value, $precision = null)
    {
        $this->matrix->setPoint($row, $col, $value, $precision);
    }

    /**
     * @param int $row
     * @param int|null $precision
     * @return MatrixBase
     */
    public function getRow($row, $precision = null)
    {
        return $this->matrix->getRow($row, $precision);
    }

    /**
     * @param int $row
     * @param Matrix $base
     * @param int|null $precision
     */
    public function setRow($row, Matrix $base, $precision = null)
    {
        $this->matrix->setRow($row, $base, $precision);
    }

    /**
     * @param int $col
     * @param int|null $precision
     * @return Matrix
     */
    public function getCol($col, $precision = null)
    {
        return $this->matrix->getCol($col, $precision);
    }

    /**
     * @param int $col
     * @param Matrix $base
     * @param int|null $precision
     */
    public function setCol($col, Matrix $base, $precision = null)
    {
        $this->matrix->setCol($col, $base, $precision);
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return $this->matrix->toArray();
    }

    /**
     * @return bool
     */
    public function isNull()
    {
        return $this->properties->isNull();
    }

    /**
     * @return bool
     */
    public function isScalar()
    {
        return $this->properties->isScalar();
    }

    /**
     * @return bool
     */
    public function isRowVector()
    {
        return $this->properties->isRowVector();
    }

    /**
     * @return bool
     */
    public function isColVector()
    {
        return $this->properties->isColVector();
    }

    /**
     * @return bool
     */
    public function isSquare()
    {
        return $this->properties->isSquare();
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
        return $this->properties->isTriangularUpper();
    }

    public function isTriangularLower()
    {
        return $this->properties->isTriangularLower();
    }

    public function isZero()
    {
        return$this->properties->isZero();
    }

    public function isDiagonal()
    {
        return $this->properties->isDiagonal();
    }

    public function isDiagonalUnit()
    {
        return $this->properties->isDiagonalUnit();
    }

    public function isDiagonalScalar()
    {
        return $this->properties->isScalar();
    }

    /**
     * @param Matrix $base
     * @param int|null $precision
     * @return bool
     */
    public function isMatrixEquals(Matrix $base, $precision = null)
    {
        return $this->properties->isEquals($base);
    }

    /**
     * @param int|null $precision
     * @return Matrix
     */
    public function transposed($precision = null)
    {
        return $this->operations->transposed();
    }

    public function isSymmetric()
    {
        return $this->properties->isSymmetric();
    }

    /**
     * @param $i
     * @param $j
     * @param int|null $precision
     * @return MatrixBase
     */
    public function getAdjunto($i, $j, $precision = null)
    {
        $precision = $this->getPrecision($precision);
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
        $precision = $this->getPrecision($precision);
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
        $precision = $this->getPrecision($precision);
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
        $precision = $this->getPrecision($precision);
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
        $precision = $this->getPrecision($precision);
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
        $precision = $this->getPrecision($precision);
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
        $precision = $this->getPrecision($precision);
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
     * @throws \Exception
     */
    public function multiplicationMatrix(MatrixBase $base, $precision = null)
    {
        $precision = $this->getPrecision($precision);
        $class = get_class($this);

        if ($this->getNumCols()!=$base->getNumRows()) {
            throw new \Exception(
                "El número de columnas de esta matriz de ser igual al número de filas de la matriz parámetro".
                "Yo ".$this->getNumRows()."x".$this->getNumCols().
                " parámertro ".$base->getNumRows()."x".$base->getNumCols()
            );
        }
        /**
         * @var MatrixBase $tr
         */
        $tr = new $class($this->getNumRows(), $base->getNumCols(), $precision);
        for ($i = 1; $i <= $tr->getNumRows(); $i++) {
            for ($j = 1; $j <= $tr->getNumCols(); $j++) {
                $suma = bcadd(0, 0, $precision);
                for ($k=1; $k<=$this->getNumCols(); $k++) {
                    $suma = bcadd(
                        $suma,
                        bcmul(
                            $this->getPoint($i, $k, $precision),
                            $base->getPoint($k, $j, $precision),
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
        $precision = $this->getPrecision($precision);
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
                        /**
                         * @var MatrixBase $opivote
                         */
                        $opivote  = $opivote->multiplicationScalar($this->bcabs($ocambio1), $precision);
                        /**
                         * @var MatrixBase $ocambio
                         */
                        $ocambio  = $ocambio->multiplicationScalar($this->bcabs($opivote1), $precision);
                        /**
                         * @var MatrixBase $ipivote
                         */
                        $ipivote  = $ipivote->multiplicationScalar($this->bcabs($ocambio1), $precision);
                        /**
                         * @var MatrixBase $icambio
                         */
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
        $precision = $this->getPrecision($precision);
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

    /**
     * @param int|null $precision
     */
    public function pretty($precision = null)
    {
        $precision = $this->getPrecision($precision);
        for ($j=1; $j<=$this->getNumCols(); $j++) {
            for ($i = 1; $i <= $this->getNumRows(); $i++) {
                echo str_pad($this->getPoint($i, $j), $precision+10, ' ', STR_PAD_LEFT)."  ";
            }
            echo "\n";
        }
    }

    /**
     * @param $precision
     * @return int
     */
    private function getPrecision($precision)
    {
        return is_null($precision) ? $this->precision : (int)$precision;
    }


    public function adjugate()
    {
        // http://www.ditutor.com/matrices/matriz_inversa.html
        // http://www.ditutor.com/determinantes/matriz_adjunta.html
        // https://es.wikipedia.org/wiki/Matriz_de_adjuntos
    }

    public function cofactores()
    {
        // https://es.wikipedia.org/wiki/Matriz_de_adjuntos
    }
}
