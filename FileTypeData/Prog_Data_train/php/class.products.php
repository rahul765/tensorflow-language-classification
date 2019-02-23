<?php
class products{

private $name,$brand,$price,$visible,$year,$desc,$image;

public function getName(){
return $this->name;
}

public function getBrand(){
return $this->brand;
}

public function getPrice(){
return $this->price;
}

public function getVisible(){
return $this->visible;
}

public function getYear(){
return $this->year;
}

public function getDesc(){
return $this->desc;
}

public function getImage(){
return $this->image;
}

public function insert($fields,$values){
if(!is_array($fields) || !is_array($values))
            return 'Error - Fields and values must be sent as an array';
                        
        $field_ct  = count($fields);
        $value_ct = count($values);
        if($field_ct != $value_ct)
            return 'Error - Field count and value count do not match.';
                        
        $query1 = "INSERT INTO `products` (`";
        $query1 .= implode('`, `', $fields) . "`) VALUES ('";
        $query1 .= implode("', '", $values) . "')";
        $exec=dbquery($query1);
        return $exec;

}

public function view($id){
$sql=mysql_query("SELECT * FROM products WHERE ProductID=$id");
$row=mysql_fetch_assoc($sql);
$this->name=$row['ProductName'];
$this->brand=$row['ProductBrand'];
$this->price=$row['ProductPrice'];
$this->visible=$row['ProductVisibility'];
$this->year=$row['ProductYear'];
$this->image=$row['ProductImage'];
$this->desc=$row['ProductDesc'];
}
}
?>
