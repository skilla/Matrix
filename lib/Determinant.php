<?php
/**
 * Created by PhpStorm.
 * User: Skilla <sergio.zambrano@gmail.com>
 * Date: 6/11/16
 * Time: 19:00
 */

namespace Skilla\Matrix;

class Determinant
{
    /**
     * Properties constructor.
     * @param Matrix $matrix
     * @param int|null $precision
     * @throws NotSquareException
     */
    public function __construct(Matrix $matrix, $precision = null)
    {
        $this->precision = is_null($precision) ? $matrix->getPrecision() : (int)$precision;
        $this->valueZero = bcadd(0, 0, $this->precision);
        $this->valueOne = bcadd(0, 1, $this->precision);
        $this->matrix = $matrix;
        $this->properties = new Properties($matrix, $precision);

        if (!$this->properties->isSquare()) {
            throw new NotSquareException();
        }
    }

    /**
     * @return string
     * @throws OperationNotAllowedException
     */
    public function retrieve()
    {
        $functions = array(
            '',
            'forOrderOne',
            'forOrderTwo',
            'forOrderThree',
            'forOrderN',
        );
        if ($this->matrix->getNumRows() > 3) {
            $order = 4;
        } else {
            $order = $this->matrix->getNumRows();
        }
        $function = $functions[$order];
        return $this->$function();
    }

    /**
     * @return string
     * @throws OperationNotAllowedException
     */
    public function forOrderOne()
    {
        if ($this->matrix->getNumRows()!==1) {
            throw new OperationNotAllowedException('determinantOrderOne only allowed on 1x1 matrix');
        }
        return $this->matrix->getPoint(1, 1);
    }

    /**
     * Resolution by Sarrus method
     * @return string
     * @throws OperationNotAllowedException
     */
    public function forOrderTwo()
    {
        if ($this->matrix->getNumRows()!==2) {
            throw new OperationNotAllowedException('determinantOrderTwo only allowed on 2x2 matrix');
        }
        $primaryDiagonal = bcmul($this->matrix->getPoint(1, 1), $this->matrix->getPoint(2, 2), $this->precision);
        $secondaryDiagonal = bcmul($this->matrix->getPoint(1, 2), $this->matrix->getPoint(2, 1), $this->precision);
        return bcsub($primaryDiagonal, $secondaryDiagonal, $this->precision);
    }

    /**
     * Resolution by Sarrus method
     * @return string
     * @throws OperationNotAllowedException
     */
    public function forOrderThree()
    {
        if ($this->matrix->getNumRows()!==3) {
            throw new OperationNotAllowedException('determinantOrderThree only allowed on 3x3 matrix');
        }
        $additions = array(
            array(array(1,1), array(2,2), array(3,3)),
            array(array(1,2), array(2,3), array(3,1)),
            array(array(1,3), array(2,1), array(3,2)),
        );
        $subtractions = array(
            array(array(1,3), array(2,2), array(3,1)),
            array(array(1,2), array(2,1), array(3,3)),
            array(array(1,1), array(2,3), array(3,2)),
        );
        $result = $this->valueZero;
        foreach ($additions as $addition) {
            $partial = $this->valueOne;
            foreach ($addition as $point) {
                $partial = bcmul($partial, $this->matrix->getPoint($point[0], $point[1]), $this->precision);
            }
            $result = bcadd($result, $partial, $this->precision);
        }
        foreach ($subtractions as $subtraction) {
            $partial = $this->valueOne;
            foreach ($subtraction as $point) {
                $partial = bcmul($partial, $this->matrix->getPoint($point[0], $point[1]), $this->precision);
            }
            $result = bcsub($result, $partial, $this->precision);
        }
        return $result;
    }

    /**
     * @return string
     * @throws OperationNotAllowedException
     */
    public function forOrderN()
    {
        if ($this->matrix->getNumRows()<=3) {
            throw new OperationNotAllowedException('determinantOrderN only allowed on 4x4 or greater matrix');
        }
        $this->gaussReduction();
        $determinant = new Determinant($this->cofactor(1, 1));
        return bcmul($this->matrix->getPoint(1, 1), $determinant->retrieve(), $this->precision);
    }

    public function gaussReduction()
    {
        $this->sortRows();
        $this->reduceToOne();
        for ($row=2; $row<=$this->matrix->getNumRows(); $row++) {
            $multiplier = $this->matrix->getPoint($row, 1);
            $multiplier = bcmul($multiplier, '-1', $this->precision);
            for ($col=1; $col<=$this->matrix->getNumCols(); $col++) {
                $oldValue = $this->matrix->getPoint($row, $col);
                $addition = bcmul($this->matrix->getPoint(1, $col), $multiplier, $this->precision);
                $newValue = bcadd($oldValue, $addition, $this->precision);
                $this->matrix->setPoint($row, $col, $newValue);
            }
        }
    }

    private function sortRows()
    {
        $interChangeRow = 1;
        while ($this->sign($this->matrix->getPoint(1, 1))==0 && $interChangeRow<$this->matrix->getNumRows()) {
            $interChangeRow++;
            for ($col=1; $col<=$this->matrix->getNumCols(); $col++) {
                $value = $this->matrix->getPoint(1, $col);
                $this->matrix->setPoint(1, $col, $this->matrix->getPoint($interChangeRow, $col));
                $this->matrix->setPoint($interChangeRow, $col, $value);
            }
        }
        if ($this->sign($this->matrix->getPoint(1, 1))==0) {
            throw new OperationNotAllowedException();
        }
    }

    private function reduceToOne()
    {
        $divisor = $this->matrix->getPoint(1, 1);
        for ($col=1; $col<=$this->matrix->getNumRows(); $col++) {
            $newValue = bcdiv($this->matrix->getPoint(1, $col), $divisor, $this->precision);
            $this->matrix->setPoint(1, $col, $newValue);
            $newValue = bcmul($this->matrix->getPoint(2, $col), $divisor, $this->precision);
            $this->matrix->setPoint(2, $col, $newValue);
        }
    }

    private function sign($value)
    {
        return bccomp($value, $this->valueZero, $this->precision);
    }

    /**
     * @param $i
     * @param $j
     * @return Matrix
     */
    public function cofactor($i, $j)
    {
        $cofactor = new Matrix($this->matrix->getNumRows() - 1, $this->matrix->getNumCols() - 1, $this->precision);
        for ($m=1; $m<=$this->matrix->getNumRows(); $m++) {
            for ($n=1; $n<=$this->matrix->getNumCols(); $n++) {
                if ($m==$i || $n==$j) {
                    continue;
                }
                $row = ($m<$i) ? $m : $m-1;
                $col = ($n<$j) ? $n : $n-1;
                $cofactor->setPoint($row, $col, $this->matrix->getPoint($m, $n));
            }
        }
        return $cofactor;
    }
}
