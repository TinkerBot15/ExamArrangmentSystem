<?php

namespace App\Helpers;

class GraphColoring
{
    /**
     * Generate a seating arrangement for the given examination timetable.
     *
     * @param  array  $seats
     * @param  int  $rows
     * @param  int  $cols
     * @return array
     */
    public static function generateSeatingArrangement(array $seats, int $rows, int $cols)
    {
        // Create a graph with nodes representing seats and edges representing adjacent seats
        $graph = [];
        foreach ($seats as $seat) {
            $graph[$seat['id']] = [];
            for ($i = 1; $i <= $rows; $i++) {
                for ($j = 1; $j <= $cols; $j++) {
                    // Check if seat is adjacent to current row and column
                    if (abs($i - $seat['row']) <= 1 && abs($j - $seat['column']) <= 1) {
                        $adjacentSeat = self::getSeatByRowAndColumn($seats, $i, $j);
                        if ($adjacentSeat) {
                            $graph[$seat['id']][] = $adjacentSeat['id'];
                        }
                    }
                }
            }
        }

        // Use graph coloring algorithm to assign seats to students
        $colors = [];
        foreach ($graph as $node => $neighbors) {
            $usedColors = [];
            foreach ($neighbors as $neighbor) {
                if (isset($colors[$neighbor])) {
                    $usedColors[$colors[$neighbor]] = true;
                }
            }
            for ($i = 1; $i <= count($neighbors) + 1; $i++) {
                if (!isset($usedColors[$i])) {
                    $colors[$node] = $i;
                    break;
                }
            }
        }

        // Generate seating arrangement from colors assigned to seats
        $seatingArrangement = [];
        foreach ($seats as $seat) {
            $seatingArrangement[] = [
                'seat_id' => $seat['id'],
                'student_id' => null,
                'color' => $colors[$seat['id']],
            ];
        }

        return $seatingArrangement;
    }

    /**
     * Get the seat with the given row and column from the list of seats.
     *
     * @param  array  $seats
     * @param  int  $row
     * @param  int  $column
     * @return array|null
     */
    private static function getSeatByRowAndColumn(array $seats, int $row, int $column)
    {
        foreach ($seats as $seat) {
            if ($seat['row'] == $row && $seat['column'] == $column) {
                return $seat;
            }
        }
        return null;
    }
}
