<?php

class xhtml {
  // Setup
  private $xhtmlReturn = '';
  const lineBrake = "\n";
  
  function __construct () {
  
  }
  
  //--- Doctype Declaration
  
  function doctype ($type) {
    switch ($type) {
      default:
      case "strict":
        $this->xhtmlReturn .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'.self::lineBrake;
      break;
      case "transitional":
        $this->xhtmlReturn .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.self::lineBrake;
      break;  
      case"frameset":
        $this->xhtmlReturn .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">'.self::lineBrake;
      break;   
    }
  }
  
  //--- Main Tags
  
  // HTML
  function html () {
    $this->xhtmlReturn .= '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">'.self::lineBrake;
  }
  function _html () {
    $this->xhtmlReturn .= '</html>'.self::lineBrake;
  }
  
  // HEAD
  function head () {
    $this->xhtmlReturn .= '<head>'.self::lineBrake.'<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />'.self::lineBrake;
  }
  function _head () {
    $this->xhtmlReturn .= '</head>'.self::lineBrake;
  }
  
  // BODY
  function body () {
    $this->xhtmlReturn .= '<body>'.self::lineBrake;
  }
  function _body () {
    $this->xhtmlReturn .= '</body>'.self::lineBrake;
  }
  
  //--- Header Tags
  
  function title($title) {
    $this->xhtmlReturn .= '<title>'.$title.'</title>'.self::lineBrake;
  }
  
  function base ($uri) {
    $this->xhtmlReturn .= '<base href="'.$uri.'" />'.self::lineBrake;
  }
  
  function meta ($type, $attr, $content) {
    switch ($type) {
      default:
      case "h":
        $this->xhtmlReturn .= '<meta http-equiv="'.$attr.'" content="'.$content.'" />'.self::lineBrake;
      break;
      case "n":
        $this->xhtmlReturn .= '<meta name="'.$attr.'" content="'.$content.'" />'.self::lineBrake;
      break;
      case "s":
        $this->xhtmlReturn .= '<meta scheme="'.$attr.'" content="'.$content.'" />'.self::lineBrake;
      break;
    }
  }
  
  function link ($type, $href, $title, $media) {
    $title == '' ? $title = 'Undefined Title' : null;
    $media == '' ? $media = 'screen' : null;
    switch ($type) {
      case "css":
        $this->xhtmlReturn .= '<link rel="stylesheet" type="text/css" href="'.$href.'" media="'.$media.'" />'.self::lineBrake;
      break;
      case "js":
        $this->xhtmlReturn .= '<link type="text/javascript" src="'.$href.'" />'.self::lineBrake;
      break;
      case "glossary":
        $this->xhtmlReturn .= '<link rel="glossary" href="'.$href.'" title="'.$title.'" />'.self::lineBrake;
      break;
      case "prev":
        $this->xhtmlReturn .= '<link rel="prev" href="'.$href.'" title="'.$title.'" />'.self::lineBrake;
      break;
      case "next":
        $this->xhtmlReturn .= '<link rel="next" href="'.$href.'" title="'.$title.'" />'.self::lineBrake;
      break;
      case "start":
        $this->xhtmlReturn .= '<link rel="start" href="'.$href.'" title="'.$title.'" />'.self::lineBrake;
      break;
      case "made":
        $this->xhtmlReturn .= '<link rev="made" href="'.$href.'" title="'.$title.'" />'.self::lineBrake;
      break;
    }
  }
  function script ($type, $href, $script) {
    switch ($type) {
      default:
      case "js":
        $this->xhtmlReturn .= '<script type="text/javascript"';
        isset($href) && $href!='' ? $this->xhtmlReturn .= ' src="'.$href.'"' : null;
        $this->xhtmlReturn .='>'.$script.'</script>'.self::lineBrake;
      break;
    }
  }
  //--- Block Tags
  
  // DIV
  function div ($id, $class) {
    $this->xhtmlReturn .= '<div';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _div () {
    $this->xhtmlReturn .= '</div>'.self::lineBrake;
  }
  
  // P
  function p ($id, $class) {
    $this->xhtmlReturn .= '<p';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _p () {
    $this->xhtmlReturn .= '</p>'.self::lineBrake;
  }
  
  // BR
  function br ($x) {
    if ((isset($x)) && ($x !=1)) {
      for ($x;$x!=0;$x--) { 
        $this->xhtmlReturn .= '<br />'.self::lineBrake;
      }
    } else {
      $this->xhtmlReturn .= '<br />'.self::lineBrake;
    }
  }
  
  // BLOCKQUOTE
  function blockquote ($id, $class) {
    $this->xhtmlReturn .= '<blockquote';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _blockquote () {
    $this->xhtmlReturn .= '</blockquote>'.self::lineBrake;
  }
  
  //Hx (1-6)
  function hx ($x, $hx, $id, $class) {
    $this->xhtmlReturn .= '<h'.$x;
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
    isset($hx) && $hx!='' ? $this->xhtmlReturn .= $hx.self::lineBrake : null;
  }
  function _hx ($x) {
    $this->xhtmlReturn .= '</h'.$x.'>'.self::lineBrake;
  }
  
  //--- --Lists
  function ul ($id, $class) {
    $this->xhtmlReturn .= '<ul';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _ul () {
    $this->xhtmlReturn .= '</ul>'.self::lineBrake;
  }
  
  function ol ($id, $class) {
    $this->xhtmlReturn .= '<ol';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _ol () {
    $this->xhtmlReturn .= '</ol>'.self::lineBrake;
  }
  
  function li ($li, $id, $class) {
    $this->xhtmlReturn .= '<li';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.$li;
  }
  function _li () {
    $this->xhtmlReturn .= '</li>'.self::lineBrake;
  }
  
  //--- --Table Tags
  
  // TABLE
  function table ($id, $class) {
    $this->xhtmlReturn .= '<table';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _table () {
    $this->xhtmlReturn .= '</table>'.self::lineBrake;
  }
  
  // THEAD
  function thead ($id, $class) {
    $this->xhtmlReturn .= '<thead';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _thead () {
    $this->xhtmlReturn .= '</thead>'.self::lineBrake;
  }
  
  // TH
  function th ($id, $class, $rowspan, $colspan) {
    $this->xhtmlReturn .= '<th';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    isset($rowspan) && $rowspan!='' ? $this->xhtmlReturn .= ' rowspan="'.$rowspan.'"' : null;
    isset($colspan) && $colspan!='' ? $this->xhtmlReturn .= ' colspan="'.$colspan.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _th () {
    $this->xhtmlReturn .= '</th>'.self::lineBrake;
  }
  
  // TBODY
  function tbody ($id, $class) {
    $this->xhtmlReturn .= '<tbody';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _tbody () {
    $this->xhtmlReturn .= '</tbody>'.self::lineBrake;
  }
  
  // TR
  function tr ($id, $class) {
    $this->xhtmlReturn .= '<tr';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _tr () {
    $this->xhtmlReturn .= '</tr>'.self::lineBrake;
  }
  
  // TD
  function td ($id, $class, $rowspan, $colspan) {
    $this->xhtmlReturn .= '<td';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    isset($rowspan) && $rowspan!='' ? $this->xhtmlReturn .= ' rowspan="'.$rowspan.'"' : null;
    isset($colspan) && $colspan!='' ? $this->xhtmlReturn .= ' colspan="'.$colspan.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _td () {
    $this->xhtmlReturn .= '</td>'.self::lineBrake;
  }
  
  //--- --Form Tags
  function form ($action,$method,$enctype,$id, $class) {
    $this->xhtmlReturn .= '<form';
    isset($action) && $action!='' ? $this->xhtmlReturn .= ' action="'.$action.'"' : null;
    isset($method) && $method!='' ? $this->xhtmlReturn .= ' method="'.$method.'"' : null;
    isset($enctype) && $enctype!='' ? $this->xhtmlReturn .= ' enctype="'.$enctype.'"' : null;
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _form () {
    $this->xhtmlReturn .= '</form>'.self::lineBrake;
  }
  
  function input ($type, $name, $value, $checked, $id, $class, $disabled, $readonly) {
    $this->xhtmlReturn .= '<input';
    isset($type) && $type!='' ? $this->xhtmlReturn .= ' type="'.$type.'"' : null;
    isset($name) && $name!='' ? $this->xhtmlReturn .= ' name="'.$name.'"' : null;
    isset($value) && $value!='' ? $this->xhtmlReturn .= ' value="'.$value.'"' : null;
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    isset($checked) && $checked!='' ? $this->xhtmlReturn .= ' checked="checked"' : null;
    isset($disabled) && $disabled!='' ? $this->xhtmlReturn .= ' disabled="disabled"' : null;
    isset($readonly) && $readonly!='' ? $this->xhtmlReturn .= ' readonly="readonly"' : null;
    $this->xhtmlReturn .= ' />'.self::lineBrake;
  }
  
  function textarea ($name, $value, $id, $class, $disabled, $readonly) {
    $this->xhtmlReturn .= '<textarea';
    isset($name) && $name!='' ? $this->xhtmlReturn .= ' name="'.$name.'"' : null;
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    isset($disabled) && $disabled!='' ? $this->xhtmlReturn .= ' disabled="disabled"' : null;
    isset($readonly) && $readonly!='' ? $this->xhtmlReturn .= ' readonly="readonly"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
    isset($value) && $value!='' ? $this->xhtmlReturn .= $value.self::lineBrake : null;
    $this->xhtmlReturn .= '</textarea>'.self::lineBrake;
  }
  
  function select ($name, $id, $class, $disabled, $readonly) {
    $this->xhtmlReturn .= '<select';
    isset($name) && $name!='' ? $this->xhtmlReturn .= ' name="'.$name.'"' : null;
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    isset($disabled) && $disabled!='' ? $this->xhtmlReturn .= ' disabled="disabled"' : null;
    isset($readonly) && $readonly!='' ? $this->xhtmlReturn .= ' readonly="readonly"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  
  function  _select() {
    $this->xhtmlReturn .= '</select>'.self::lineBrake;
  }
  
  function  option($value, $text, $selected) {
    $this->xhtmlReturn .= '<option value="'.$value.'"';
    isset($selected) && $selected!='' ? $this->xhtmlReturn .= ' selected="selected"' : null;
    $this->xhtmlReturn .='>'.$text.'</option>'.self::lineBrake;
  }
  
  //--- Graphic Tags
  
  // IMG
  function img ($src, $id, $class, $alt, $title) {
    $this->xhtmlReturn .= '<img src="'.$src.'" ';
    isset($alt) && $alt!='' ? $this->xhtmlReturn .= 'alt="'.$alt.'" ' : $this->xhtmlReturn .= 'alt="Undefined Image/Picture" ';
    isset($title) && $title!='' ? $this->xhtmlReturn .= 'title="'.$title.'" ' : $this->xhtmlReturn .= 'title="Undefined Title" ';
    isset($id) && $id!='' ? $this->xhtmlReturn .= 'id="'.$id.'" ' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= 'class="'.$class.'" ' : null;
    $this->xhtmlReturn .= ' />'.self::lineBrake;
  }
    
  //--- Text Tags
  function span ($id, $class) {
    $this->xhtmlReturn .= '<span';
    isset($id) && $id!='' ? $this->xhtmlReturn .= ' id="'.$id.'"' : null;
    isset($class) && $class!='' ? $this->xhtmlReturn .= ' class="'.$class.'"' : null;
    $this->xhtmlReturn .= '>'.self::lineBrake;
  }
  function _span () {
    $this->xhtmlReturn .= '</span>'.self::lineBrake;
  }
  //--- Other
  
  function comment ($comment) {
    $this->xhtmlReturn .= '<!-- '.$comment.'-->'.self::lineBrake;
  }
  
  //--- Custom Functions
  
  function add($code) {
    $this->xhtmlReturn .= $code;
  }
  
  //--- End Funtions
  
  function output($method) {
    switch ($method) {
      default:
      case 'r':
        return $this->xhtmlReturn;
      break;
      case 'p':
        print $this->xhtmlReturn;
      break;
      case 'e':
        echo $this->xhtmlReturn;
      break;
    }
  }
  
  function __destruct() {
    
  }
}

?>
