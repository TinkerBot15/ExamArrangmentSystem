<?php

// Graph.php

namespace App\Graph;

class Graph
{
    private $adjacencyMatrix;
    private $numColors;
    private $colorsVector;
    private $chromaticNo;

    public function __construct($adjacencyMatrix, $numColors)
    {
        $this->adjacencyMatrix = $adjacencyMatrix;
        $this->numColors = count($numColors);
        $this->colorsVector = [];
        $this->chromaticNo = 0;
        // dd($this->adjacencyMatrix, $this->numColors, $this->colorsVector,  $this->chromaticNo);
    }

    // public function coloringGraph()
    // {
    //     $numSeats = count($this->adjacencyMatrix);
    //     $assignedColors = array_fill(0, $numSeats, -1);

    //     $assignedColors[0] = 0;

    //     for ($i = 1; $i < $numSeats; $i++) {
    //         $availableColors = array_fill(0, $this->numColors, true);

    //         for ($j = 0; $j < $i; $j++) {
    //             if ($this->adjacencyMatrix[$i][$j] && $assignedColors[$j] != -1) {
    //                 $color = $assignedColors[$j];
    //                 $availableColors[$color] = false;
    //             }
    //         }

    //         for ($color = 0; $color < $this->numColors; $color++) {
    //             if ($availableColors[$color]) {
    //                 $assignedColors[$i] = $color;
    //                 break;
    //             }
    //         }
    //     }

    //     $this->colorsVector = $assignedColors;
    //     $this->chromaticNo = max($assignedColors) + 1;
    // }

    public function welshPowellAlgorithm($departments)
{
    $numDepartments = count($departments);
    // Step 1: Find the degree of each vertex
    $degrees = [];
    foreach ($this->adjacencyMatrix as $vertex => $neighbors) {
        $degrees[$vertex] = count($neighbors);
    }

    // Step 2: List the vertices in order of descending degrees
    arsort($degrees);

    // Step 3: Initialize colors and assigned colors array
    $assignedColors = [];

    // Step 4: Color the vertices
    foreach ($degrees as $vertex => $degree) {
        // Check neighbors of the current vertex
        $usedColors = [];
        foreach ($this->adjacencyMatrix[$vertex] as $neighbor => $edge) {
            if (isset($assignedColors[$neighbor])) {
                $color = $assignedColors[$neighbor];
                $usedColors[$color] = true;
            }
        }

        // Find the first available color
        $color = 0;
        while (isset($usedColors[$color])) {
            $color++;
        }

        // Assign the color to the current vertex
        $assignedColors[$vertex] = $color;
        
    }

    // Step 5: Set the colorsVector and chromaticNo properties
    $this->colorsVector = $assignedColors;
    $this->chromaticNo = $numDepartments; // Set the chromatic number to the number of departments

    // If you want to ensure that only the specified number of colors are used, you can add the following code:
    foreach ($this->colorsVector as $vertex => $color) {
        $this->colorsVector[$vertex] = $color % $numDepartments; // Map colors to range 0-(numDepartments-1)
    }
}





    public function getColorsVector()
    {
        return $this->colorsVector;
    }

    public function getChromaticNo()
    {
        return $this->chromaticNo + 1;
    }
}
