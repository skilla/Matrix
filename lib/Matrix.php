<?php
/**
 * Created by PhpStorm.
 * User: Sergio Zambrano Delfa <sergio.zambrano@gmail.com>
 * Date: 02/11/16
 * Time: 11:19
 */

namespace Skilla\Matrix;

class Matrix
{
    private $matriz = array();
    private $rows;
    private $cols;
    private $valueZero;
    private $valueOne;
    private $precision;

    /**
     * Matrix constructor.
     * @param int $rows
     * @param int $columns
     * @param int|null $precision
     * @throws \Skilla\Matrix\ParameterException
     */
    public function __construct($rows = 0, $columns = 0, $precision = 15)
    {
        if (0===(int)$rows || 0===(int)$columns) {
            throw new ParameterException('Rows and columns must be greater than zero');
        }
        if (0>(int)$precision) {
            throw new ParameterException('Precision must be greater or equal to zero');
        }

        $this->precision = (int)$precision;
        $this->valueZero = bcadd(0, 0, $this->precision);
        $this->valueOne = bcadd(0, 1, $this->precision);
        for ($row = 1; $row <= $rows; $row++) {
            $this->matriz[$row] = array();
            for ($column = 1; $column <= $columns; $column++) {
                $this->matriz[$row][$column] = $this->valueZero;
            }
        }
        $this->rows = $rows;
        $this->cols = $columns;
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
     * @throws OutOfRangeException
     */
    public function getPoint($row, $col, $precision = null)
    {
        if ($this->getNumRows()<(int)$row || $this->getNumCols()<(int)$col) {
            throw new OutOfRangeException(
                sprintf(
                    'Maximum row %s, maximum column %s actual parameters (%s, %s)',
                    $this->getNumRows(),
                    $this->getNumCols(),
                    $row,
                    $col
                )
            );
        }
        $precision = $this->getPrecision($precision);
        return bcadd($this->matriz[$row][$col], $this->valueZero, $precision);
    }

    /**
     * @param int $row
     * @param int $col
     * @param int|string $value
     * @param int|null $precision
     * @return Matrix $this
     * @throws OutOfRangeException
     */
    public function setPoint($row, $col, $value, $precision = null)
    {
        if ($this->getNumRows()<(int)$row || $this->getNumCols()<(int)$col) {
            throw new OutOfRangeException(
                sprintf(
                    'Maximum row %s, maximum column %s actual parameters (%s, %s)',
                    $this->getNumRows(),
                    $this->getNumCols(),
                    $row,
                    $col
                )
            );
        }
        $precision = $this->getPrecision($precision);
        $this->matriz[$row][$col] = bcadd($this->valueZero, $value, $precision);
        return $this;
    }

    /**
     * @param int $row
     * @param int|null $precision
     * @return Matrix
     * @throws OutOfRangeException
     */
    public function getRow($row, $precision = null)
    {
        if ($this->getNumRows()<(int)$row) {
            throw new OutOfRangeException(sprintf('Maximum row %s, actual parameter %s', $this->getNumRows(), $row));
        }
        $precision = $this->getPrecision($precision);
        $class = get_class($this);
        /** @var Matrix $tr */
        $tr = new $class(1, $this->getNumCols(), $precision);
        for ($col=1; $col<=$this->getNumCols(); $col++) {
            $tr->setPoint(1, $col, $this->getPoint($row, $col, $precision), $precision);
        }
        return $tr;
    }

    /**
     * @param int $row
     * @param Matrix $base
     * @param int|null $precision
     * @return Matrix $this
     * @throws OutOfRangeException
     */
    public function setRow($row, Matrix $base, $precision = null)
    {
        if ($this->getNumRows()<(int)$row) {
            throw new OutOfRangeException(sprintf('Maximum row %s, actual parameter %s', $this->getNumRows(), $row));
        }
        $precision = $this->getPrecision($precision);
        for ($col=1; $col<=$this->getNumCols(); $col++) {
            $this->setPoint($row, $col, $base->getPoint(1, $col, $precision), $precision);
        }
        return $this;
    }

    /**
     * @param int $col
     * @param int|null $precision
     * @return Matrix
     * @throws OutOfRangeException
     */
    public function getCol($col, $precision = null)
    {
        if ($this->getNumCols()<(int)$col) {
            throw new OutOfRangeException(sprintf('Maximum column %s, actual parameter %s', $this->getNumCols(), $col));
        }
        $precision = $this->getPrecision($precision);
        $class = get_class($this);
        /** @var Matrix $tr */
        $tr = new $class($this->getNumRows(), 1, $precision);
        for ($row=1; $row<=$this->getNumRows(); $row++) {
            $tr->setPoint($row, 1, $this->getPoint($row, $col, $precision), $precision);
        }
        return $tr;
    }

    /**
     * @param int $col
     * @param Matrix $base
     * @param int|null $precision
     * @return Matrix $this
     * @throws OutOfRangeException
     */
    public function setCol($col, Matrix $base, $precision = null)
    {
        if ($this->getNumCols()<(int)$col) {
            throw new OutOfRangeException(sprintf('Maximum column %s, actual parameter %s', $this->getNumCols(), $col));
        }
        $precision = $this->getPrecision($precision);
        for ($row=1; $row<=$this->getNumCols(); $row++) {
            $this->setPoint($row, $col, $base->getPoint($row, 1, $precision), $precision);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->matriz;
    }

    /**
     * @param int|null $precision
     */
    public function printPretty($precision = null)
    {
        $precision = $this->getPrecision($precision);
        for ($row = 1; $row <= $this->getNumRows(); $row++) {
            for ($col=1; $col<=$this->getNumCols(); $col++) {
                echo str_pad($this->getPoint($row, $col), $precision+10, ' ', STR_PAD_LEFT)."  ";
            }
            echo "\n";
        }
    }

    /**
     * @param int|null $precision
     * @return int
     */
    public function getPrecision($precision = null)
    {
        return is_null($precision) ? $this->precision : (int)$precision;
    }
}
