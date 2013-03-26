<?php
// http://www.assemblysys.com/dataServices/php_pointinpolygon.php
class pointLocation 
{
    // var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices


    public function pointInPolygon($point, $vertices/*, $pointOnVertex = true*/) 
    {
        // $this->pointOnVertex = $pointOnVertex;

        // Transform string coordinates into arrays with x and y values
        // $point = $this->pointStringToCoordinates($point);
        // $vertices = array();
        // $vertices = $polygon;
        // foreach ($polygon as $vertex) {
        //     // $vertices[] = $this->pointStringToCoordinates($vertex);
        //     $vertices[] = $vertex;
        // }

        // Check if the point sits exactly on a vertex
        // if ($this->pointOnVertex === true and $this->pointOnVertex($point, $vertices) === true) {
        //     // return "vertex";
        //     return true;
        // }

        // Check if the point is inside the polygon or on the boundary
        $intersections = 0;
        $vertices_count = count($vertices);

        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1];
            $vertex2 = $vertices[$i];

            if (($vertex1[1] === $vertex2[1]) and ($vertex1[1] === $point[1]) and ($point[0] > min($vertex1[0], $vertex2[0])) and ($point[0] < max($vertex1[0], $vertex2[0]))) { 
                // Check if point is on an horizontal polygon boundary
                // return "boundary";
                return true;
            }
            if (($point[1] > min($vertex1[1], $vertex2[1])) and ($point[1] <= max($vertex1[1], $vertex2[1])) and ($point[0] <= max($vertex1[0], $vertex2[0])) and ($vertex1[1] !== $vertex2[1])) {
                $xinters = ($point[1] - $vertex1[1]) * ($vertex2[0] - $vertex1[0]) / ($vertex2[1] - $vertex1[1]) + $vertex1[0];
                if ($xinters === $point[0]) { // Check if point is on the polygon boundary (other than horizontal)
                    // return "boundary";
                    return true;
                }
                if ($vertex1[0] === $vertex2[0] || $point[0] <= $xinters) {
                    $intersections++;
                }
            }
        }
        // If the number of edges we passed through is even, then it's in the polygon.
        if ($intersections % 2 !== 0) {
            // return "inside";
            return true;
        } else {
            // return "outside";
            return false;
        }
    }


    function pointInPolygonDiff($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
      $i = $j = $c = 0;
      for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
        if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
         ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
           $c = !$c;
      }
      return $c;
    }



// function  areIntersecting(
//     float v1x1, float v1y1, float v1x2, float v1y2,
//     float v2x1, float v2y1, float v2x2, float v2y2
// ) {
//     float d1, d2;
//     float a1, a2, b1, b2, c1, c2;

//     // Convert vector 1 to a line (line 1) of infinite length.
//     // We want the line in linear equation standard form: A*x + B*y + C = 0
//     // See: http://en.wikipedia.org/wiki/Linear_equation
//     a1 = v1y2 - v1y1;
//     b1 = v1x1 - v1x2;
//     c1 = (v1x2 * v1y1) - (v1x1 * v1y2);

//     // Every point (x,y), that solves the equation above, is on the line,
//     // every point that does not solve it, is either above or below the line.
//     // We insert (x1,y1) and (x2,y2) of vector 2 into the equation above.
//     d1 = (a1 * v2x1) + (b1 * v2y1) + c1;
//     d2 = (a1 * v2x2) + (b1 * v2y2) + c1;

//     // If d1 and d2 both have the same sign, they are both on the same side of
//     // our line 1 and in that case no intersection is possible. Careful, 0 is
//     // a special case, that's why we don't test ">=" and "<=", but "<" and ">".
//     if (d1 > 0 && d2 > 0) return NO;
//     if (d1 < 0 && d2 < 0) return NO;

//     // We repeat everything above for vector 2.
//     // We start by calculating line 2 in linear equation standard form.
//     a2 = v2y2 - v2y1;
//     b2 = v2x1 - v2x2;
//     c2 = (v2x2 * v1y1) - (v2x1 * v2y2);

//     // Calulate d1 and d2 again, this time using points of vector 1
//     d1 = (a2 * v1x1) + (b2 * v1y1) + c2;
//     d2 = (a2 * v1x2) + (b2 * v1y2) + c2;

//     // Again, if both have the same sign (and neither one is 0),
//     // no intersection is possible.
//     if (d1 > 0 && d2 > 0) return NO;
//     if (d1 < 0 && d2 < 0) return NO;

//     // If we get here, only three possibilities are left. Either the two
//     // vectors intersect in exactly one point or they are collinear
//     // (they both lie both on the same infinite line), in which case they
//     // may intersect in an infinite number of points or not at all.
//     if ((a1 * b2) - (a2 * b1) == 0.0f) return COLLINEAR;

//     // If they are not collinear, they must intersect in exactly one point.
//     return YES;
// }


    function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }

    }


    function pointStringToCoordinates($pointString) {
        $coordinates = explode(" ", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }

// protected function testPolygonContainsPoint(Polygon $polygon, Point $point) {
//     if (!$polygon->getBoundingBox()->contains($point)) {
//     return false;
//     }

//     $length = $polygon->getVertices()->length();
//     $inPolygon = false;

//     $j = $length - 1;
//     for($i = 0; $i getVertex($i);
//     $vertex2 = $polygon->getVertex($j);

//     if ($vertex1->getY() getY() && $vertex2->getY() >= $point->getY() || $vertex2->getY() getY() && $vertex1->getY() >= $point->getY()) {
//     if ($vertex1->getX() + ($point->getY() – $vertex1->getY()) / ($vertex2->getY() – $vertex1->getY()) * ($vertex2->getX() – $vertex1->getX()) getX()) {
//     $inPolygon = !$inPolygon;
//     }
//     }

//     $j = $i;
//     }

//     return $inPolygon;
//     }


}

// /*** Example ***/
// $pointLocation = new pointLocation();
// $points = array("30 19", "0 0", "10 0", "30 20", "11 0", "0 11", "0 10", "30 22", "20 20");
// $polygon = array("10 0", "20 0", "30 10", "30 20", "20 30", "10 30", "0 20", "0 10", "10 0");
// // The last point's coordinates must be the same as the first one's, to "close the loop"
// foreach($points as $key => $point) {
//     echo "$key ($point) is " . $pointLocation->pointInPolygon($point, $polygon) . "<br>";
// }