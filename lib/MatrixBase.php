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

    public function __construct()
    {
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

    public function isDiagonalZero()
    {
        $isDiagonal = $this->isSquare() && $this->getNumRows()>1;
        if ($isDiagonal) {
            for ($i = 1; $i <= $this->getNumRows() && $isDiagonal; $i++) {
                if ($this->matriz[$i][$i] != 0) {
                    $isDiagonal = false;
                }
            }
        }
        return $isDiagonal;
    }

    public function isDiagonalUnit()
    {
        $isDiagonal = $this->isSquare() && $this->getNumRows()>1;
        if ($isDiagonal) {
            for ($i = 1; $i <= $this->getNumRows() && $isDiagonal; $i++) {
                if ($this->matriz[$i][$i] != 0) {
                    $isDiagonal = false;
                }
            }
        }
        return $isDiagonal;
    }

    public function isDiagonalUpper()
    {
        $isDiagonal = $this->isSquare() && $this->getNumRows()>1;
        if ($isDiagonal) {
            for ($i = 1; $i <= $this->getNumRows() && $isDiagonal; $i++) {
                for ($j = $i+1; $j <= $this->getNumCols() && $isDiagonal; $j++) {
                    if ($this->matriz[$i][$j] != 0) {
                        $isDiagonal = false;
                    }
                }
            }
        }
        return $isDiagonal;
    }

    public function isDiagonalLower()
    {
        $isDiagonal = $this->isSquare() && $this->getNumRows()>1;
        if ($isDiagonal) {
            for ($i = 2; $i <= $this->getNumRows() && $isDiagonal; $i++) {
                for ($j = 1; $j <= $i && $isDiagonal; $j++) {
                    if ($this->matriz[$i][$j] != 0) {
                        $isDiagonal = false;
                    }
                }
            }
        }
        return $isDiagonal;
    }

    public function isDiagonal()
    {
        return $this->isDiagonalUpper() && $this->isDiagonalLower();
    }

    public function
}
