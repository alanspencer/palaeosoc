/*********************
//* jQuery Multi Level CSS Menu #2- By Dynamic Drive: http://www.dynamicdrive.com/
//* Last update: Nov 7th, 08': Limit # of queued animations to minmize animation stuttering
//* Menu avaiable at DD CSS Library: http://www.dynamicdrive.com/style/
*********************/

//Specify full URL to down and right arrow images (23 is padding-right to add to top level LIs with drop downs):
var arrowimages={down:['downarrowclass', 'http://www.palaeosoc.org/site/_img/pageSet/images/down.png', 23], right:['rightarrowclass', 'http://www.palaeosoc.org/site/_img/pageSet/rightArrowWhite.png']}

var jqueryslidemenu={

  animateduration: {over: 400, out: 1}, //duration of slide in/ out animation, in milliseconds
  
  buildmenu:function(menuid, arrowsvar){
  	jQuery(document).ready(function($){
  		var $mainmenu=$("#"+menuid+">ul")
  		var $headers=$mainmenu.find("ul").parent()
  		$headers.each(function(i){
  			var $curobj=$(this)
  			var $subul=$(this).find('ul:eq(0)')
  			this._dimensions={w:this.offsetWidth, h:this.offsetHeight, subulw:$subul.outerWidth(), subulh:$subul.outerHeight()}
  			this.istopheader=$curobj.parents("ul").length==1? true : false
  			$subul.css({top:this.istopheader? this._dimensions.h+"px" : 0})
  			$curobj.children("a:eq(0)").append(
  				'<img src="'+ (arrowsvar.right[1])
  				+'" class="' + (arrowsvar.right[0])
  				+ '" />'
  			)
  			$curobj.hover(
  				function(e){
  					var $targetul=$(this).children("ul:eq(0)")
  					var menuleft= this.istopheader? 180 : 196
            if ($targetul.queue().length<=1) //if 1 or less queued animations
  						$targetul.css({left:menuleft+"px", top:0}).slideDown(jqueryslidemenu.animateduration.over)
  				},
  				function(e){
  					var $targetul=$(this).children("ul:eq(0)")
  					$targetul.slideUp(jqueryslidemenu.animateduration.out)
  				}
  			) //end hover
  		}) //end $headers.each()
  		$mainmenu.find("ul").css({display:'none', visibility:'visible'})
  	}) //end document.ready
  }
}

//build menu with ID="myslidemenu" on page:

jqueryslidemenu.buildmenu("navigationMenu", arrowimages);


