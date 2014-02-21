<?php

class ApiController extends Controller
{

    public static function valuesToJson($values, $from, $to, $timestampKey = 'timestamp', $valueKey = 'value', $interval)
    {
        $start = $from;

        $tickInterval = 24 * 3600;
        if ($interval == 'hour') {
            $tickInterval = 3600;
        } else {

            $start = floor($start / $tickInterval) * $tickInterval;
        }
        $allValues = array();
        $valuesHistory = array();



        $i = 0;

        while ($start < $to) {
            if (isset($values[$i])) {
                $timestamp = $values[$i][$timestampKey];
                if ($timestamp >= $start && $timestamp < $start + $tickInterval + 2 * 3600) {
                    $allValues[] = $values[$i][$valueKey];
                    $valuesCell = array(
                        $valueKey => $values[$i][$valueKey],
                    );
                    if ($interval == 'hour') {
                        $valuesCell[$timestampKey] = date('Y-m-d', $values[$i][$timestampKey]) . 'T' . date('H', $values[$i][$timestampKey]) . ':00:00' . date('P', $values[$i][$timestampKey]);
                    } else {
                        $valuesCell[$timestampKey] = date('Y-m-d', $values[$i][$timestampKey]) . 'T' . '00:00:00' . date('P', $values[$i][$timestampKey]);
                    }
                    $valuesHistory[] = $valuesCell;
                    $i++;
                } else {
                    $allValues[] = 0;
                    if ($interval == 'hour') {
                        $valuesHistory[] = array($timestampKey => date('Y-m-d', $start) . 'T' . date('H', $start + 3600) . ':00:00' . date('P', $start), $valueKey => 0);
                    } else {
                        $valuesHistory[] = array($timestampKey => date('Y-m-d', $start) . 'T' . '00:00:00' . date('P', $start), $valueKey => 0);
                    }
                }
            } else {
                $allValues[] = 0;
                if ($interval == 'hour') {
                    $valuesHistory[] = array($timestampKey => date('Y-m-d', $start) . 'T' . date('H', $start + 3600) . ':00:00' . date('P', $start), $valueKey => 0);
                } else {
                    $valuesHistory[] = array($timestampKey => date('Y-m-d', $start) . 'T' . '00:00:00' . date('P', $start), $valueKey => 0);
                }
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
        if (isset($_GET['callback'])) {
            header('Content-Type: text/javascript');
            $output = $_GET['callback'] . '(' . $output . ');';
        } else {
            header('Content-Type: application/json');
        }
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
