<?php
class numerical {
  // Setup
  function __construct () {
  
  }
  
  // Pricing
  
  function roundInt ($number) {
    $number = number_format(round($number,0),0);
    return $number;
  }
  
  function round2DP ($number) {
    $number = number_format(round($number,2),2);
    return $number;
  }
  
  function costSaving ($current, $original) {
    $costSaving = $original - $current;
    return $costSaving;
  }
  
  function percentageSaving ($current, $original) {
    $original != 0 ? $percanetageSaving = $this->costSaving($current, $original)*(100/$original) : $percanetageSaving = 0;
    // 2 dp
    $percanetageSaving = $this->round2DP($percanetageSaving);
    return $percanetageSaving;
  }
  
  
  // Monograph Stock
  
  
  function totalStock ($original,$reprints) {
    $original == '' ? $original = 0 : null;
    $reprints == '' ? $reprints = 0 : null;
    $totalStock = $original+$reprints;
    // Interger
    $totalStock = $this->roundInt($totalStock);
    return $totalStock;
  }
  
  
  
  function __destruct() {
    
  }
}

// Start class on include
$num = new numerical ();
?>
