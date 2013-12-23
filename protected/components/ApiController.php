<?php

class ApiController extends Controller
{

    public static function valuesToJson($values, $from, $to, $timestampKey = 'timestamp', $valueKey = 'value')
    {
        $tickInterval = 24 * 3600;
        $allValues = array();
        $valuesHistory = array();

        $start = $from;
        $start = floor($start / $tickInterval) * $tickInterval;

        $i = 0;

        while ($start < $to) {
            if (isset($values[$i])) {
                $timestamp = $values[$i][$timestampKey];
                if ($timestamp >= $start && $timestamp < $start + $tickInterval) {
                    $allValues[] = $values[$i][$valueKey];
                    $valuesCell = array(
                        $timestampKey => date('Y-m-d', $values[$i][$timestampKey]) . 'T' . '00:00:00' . date('P', $values[$i][$timestampKey]),
                        $valueKey => $values[$i][$valueKey],
                    );
                    $valuesHistory[] = $valuesCell;
                    $i++;
                } else {
                    $allValues[] = 0;
                    $valuesHistory[] = array($timestampKey => date('Y-m-d', $start) . 'T' . '00:00:00' . date('P', $start), $valueKey => 0);
                }
            } else {
                $allValues[] = 0;
                $valuesHistory[] = array($timestampKey => date('Y-m-d', $start) . 'T' . '00:00:00' . date('P', $start), $valueKey => 0);
            }

            $start += $tickInterval;
        }
        $total = array_sum($allValues);
        $average = 0;
        if (count($allValues)) {
            $average = $total / count($allValues);
        }
        return array(
            'total' => $total,
            'history' => $valuesHistory,
            'average' => $average,
        );
    }

    /**
     * Converts numeric variables to integers, encodes and outputs them as json.
     * @param type $output
     */
    public function outputJson($output)
    {
        $this->toInteger($output);
        header('Content-Type: application/json');
        echo CJSON::encode($output);
    }

    /**
     * Converts numeric variables to integers.
     * @param type $array
     */
    protected function toInteger(&$array)
    {
        foreach ($array as &$value) {
            if (is_array($value))
                $this->toInteger($value);
            if (is_numeric($value)) {
                $value = (int) $value;
            }
        }
    }

}