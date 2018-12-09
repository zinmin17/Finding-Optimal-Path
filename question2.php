<?php
/**
 *  Candidate : Zin Min (zm.mgmg@gmail.com)
 * 
 *  Question 2. Finding Optimal Path
 *  Find a path such that the sum of the weights of all vertices on the path is maximized.
 *  Input: n vertices, m edges, origin vertex 
 * 
 *  Step (1) : Find all possible paths
 *  Step (2) : Find weight of the paths
 *  Step (3) : Get Max wight of the path
 *  Step (4) : Set output format
 */


/** Step 1: Find all possible paths
 *  @param array $edges 
 *  @param vchr $origin_vertex
 *  @return array $path
*/
function buildPath(array $edges, $origin_vertex){
    $elements = splitEdges($edges);
    $path = [];
    foreach($elements as $element){
        if ($element[0] == $origin_vertex){
            $children = buildPath($edges, $element[1]);
            if($children){
                $element[1] = $children;
            }
            $path[] = flattenArray($element);
        }

    }
    return $path;
}

/** 
 *  Splitting edges into array
 *  @param array $edges
 *  @return array $data
*/
function splitEdges(array $edges)
{
    $data = [];
    foreach($edges as $edge) {
        $data[] = preg_split("/->/",$edge);
    }
    return $data;
}

/** 
 *  Flattern multidimensional array
 *  @param array $arrayToFlatten
 *  @return array $flatArray
*/
function flattenArray(array $arrayToFlatten) {
	$flatArray = array();
	foreach($arrayToFlatten as $element) {
		if (is_array($element)) {
			$flatArray = array_merge($flatArray, flattenArray($element));
		} else {
			$flatArray[] = $element;
		}
	}
	return $flatArray;
}

/** Step (2) : Find weight of the paths 
 *  @param array $main_paths
 *  @param array $vertices
 *  @return array path with weight
 * 
*/
function addWeightInPath(array $main_paths, $vertices)
{
    $return_path = [];

    foreach($main_paths as $path){

        $weight = '';
        foreach($path as $element){
            
            if(array_key_exists($element, $vertices))
            {
                $weight += $vertices[$element];
            }
        }
        $path['weight']= $weight;
        $return_path[] = $path;
    }

    return $return_path;
}

/** Step (3) : Get Max wight of the path
 *  @param array $weight_paths
 *  @return array $max
 */
function getMaxWeightPath(array $weight_paths)
{
    $max = [];

    foreach($weight_paths as $weight_path){

        if(empty($max)){
            $max = $weight_path;
        }

        if($max['weight'] < $weight_path['weight']){
            $max = $weight_path;
        }
    }

    return $max;
}

/** Step (4) : Set output format
 *  @param array $max_path
 *  @return array $format
 */
function setFormat(array $max_path)
{
    $format = [];
    foreach($max_path as $key => $max){

        if(!is_numeric($max)){
            $format['path'] .=  $max.'->';
        }else{
            $format['weight'] = $max;
        }
        
    }
    $format['path']=rtrim($format['path'],"->");

    return $format;
}

$origin_vertex = 'A';
$vertices = array('A'=>1, 'B'=>2, 'C'=>2);
$edges = array('A->B', 'B->C', 'A->C');

echo '<pre>';

print('origin vertex : '.$origin_vertex.'<br>');

echo '<br><u>vertices</u><br>';
print_r($vertices);

echo '<br><u>edges</u><br>';
print_r($edges);


# Step 1
$path = buildPath($edges, $origin_vertex);
//print_r($path);

# Step 2
$weight_paths = addWeightInPath($path, $vertices);
//print_r($weight_paths);

$max_path = getMaxWeightPath($weight_paths);
//print_r($max_path);

$output = setFormat($max_path);
echo '<br><u>output</u><br>';
print_r ($output);
echo '<br>With origin vertex '.$origin_vertex.', the maximum path is '.$output['path']. ' with total weight = '. $output['weight'];
