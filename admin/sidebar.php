<nav class="sidebar" id="sidebar"><ul class="nav flex-column"><?php if(is_array($allocated_menu)){echo get_menu_tree_admin(0,$allocated_menu); } ?></ul></nav>

<?php

function get_menu_tree_admin($parent_id,$allocated_menu){
    $menu = "";
     if($parent_id==26||$parent_id==1){$str="menu_name";  }else{$str="priority=0,priority";}
    $prod_cat = selectQuery(ADMINMENU,"id,link,menu_name,icon","parent_id=".$parent_id." and isActive='1' order by ".$str." ASC");
    if(count($prod_cat)){
        for($i=0;$i<count($prod_cat);$i++){
            $menurow=$prod_cat[$i];  $menuname=$menurow['menu_name'];
            if(in_array($menurow['id'], $allocated_menu)){
                if($menurow['link'] == ""){ $link = "javascript:void(0);"; } else{ $link = SITEURL."/".$menurow['link']; }
                $menu .="<li class='nav-item nav-list-".$i."'><a class='nav-link' href='".$link."'> <span class='".$menurow['icon']."'></span> <span class='title'>".$menuname."</span></a>";
                $prod_cat_2=selectQuery(ADMINMENU,"id","parent_id=".$menurow['id']." and isActive='1'");
                if(count($prod_cat_2)){
                    $menu .= "<ul class='dropdownMenu list-unstyled'>".get_menu_tree_admin($menurow['id'],$allocated_menu)."</ul>";
                } 
                $menu .= "</li>";
            } 
        }      
    }
    return $menu;
} ?>