<?php 
require('glossaryterm.class.php');
class FileDataProvider {
 function __construct($file_path){
    $this->file_path = $file_path;
 }
public function get_terms() {
    $json =  $this->get_data();
    return json_decode($json);
}

function get_term($term) {
    $terms =  $this->get_terms();

    foreach ($terms as $item) {
        if ($item->term == $term) {
            return $item;
        }
    }

    return false;
}

function search_terms($search) {
    $items =  $this->get_terms();

    $results = array_filter($items, function($item) use($search) {
        
        if (strpos($item->term, $search) !== false || 
            strpos($item->definition, $search) !== false) {
            return $item;
        }
    });

    return $results;

}

function add_term($term, $definition) {
$items =  $this->get_terms();
$items[] = new GlossaryTerm($term, $definition); 
 $this->set_data($items);
}

function update_term($original_term, $new_term, $definition) {
    $terms =  $this->get_terms();
    foreach ($terms as $item) {
        if ($item->term == $original_term) {
            $item->term = $new_term;
            $item->definition = $definition;
            break;
        }
    }
     $this->set_data($terms);
}

function delete_term($term) {
    $terms =  $this->get_terms();
    
    for ($i = 0; $i < count($terms); $i++) {
        if ($terms[$i]->term === $term) {
            unset($terms[$i]);
            break;
        }
    }
    $new_terms = array_values($terms);
    set_data($new_terms);
}

private function get_data() {
    $json = '';
    if (!file_exists($this->file_path)) {
        file_put_contents($this->file_path, '');
    } else {
        $json = file_get_contents($this->file_path);
    }


    return $json;
}

private function set_data($arr) {

    $json = json_encode($arr);
    file_put_contents($this->file_path, $json);
}

}