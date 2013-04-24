<?php
class Menu extends CI_Model {

	function recursive($data, $parent) {
		
		write_log($this, __METHOD__, "data : $data | parent : $parent");
		
		if(isset($data[$parent])) {
			if($parent == 0) {
				$str = '<ul id="browser" class="filetree">';
			} else {
				$str = '<ul>';
			}

 	  		foreach($data[$parent] as $value) {
				$menu = $this->recursive($data, $value['id']);
 	  	  		
				if($value['parent'] > 0 or ($value['parent'] == 0 and $menu)) { // yang tidak punya anak tidak ditampilkan
					// set expand folder menu
					$equal = 0;
					$menu_expand = $this->session->userdata("menu_expand");
					$menu_expand_array = explode(",", $menu_expand);
					for($i = 0; $i < sizeof($menu_expand_array); $i++) {
						if($value['id'] == $menu_expand_array[$i]) {
							$str .= '<li>';
							$equal = 1; 
						}
					}
					if($equal == 0) {
						$str .= '<li class="closed">';
					}

					if($value['id'] == '5') { // access menu (pembelian)
						$str .= "<input type='hidden' id='menu_acc' value='1' style='font-size: 90%'/>";
						$str .= "<input type='hidden' id='menu_id_acc' value='".$value['id']."' style='font-size: 90%'/>";
						$str .= "<input type='hidden' id='menu_name_acc' value='".$value['name']."' style='font-size: 90%'/>";
					}
					
					if($menu) {
						$str .= '<span class="folder" id="'.$value['id'].'">'.$value['name'].'</span>';
						$str .= $menu;
					} else {
						$str .= '<span class="file" id="menu_'.$value['id'].'" onclick="'.$value['link'].'set_menu_act(\''.$value['id'].'\', \''.$value['name'].'\');">'.$value['name'].'</span>';
					}
					$str .= '</li>';

 	  	  		}

			}
			
			$str .= '</ul>';
			return $str;
		} else {
			return false;
		}
		
	}

	function get_menu() {

		$user = $this->session->userdata("id");

		$sql = "
		        select distinct m.id, m.name, m.parent, m.link, m.seq
		        from users u
		            , (
		                #select 2 role_id, code user_id
	                    #from users
	                    #union all
	                    select role_id, user_id 
		                from role_user
		                where is_active = 1
		            ) ru
		            , (
		                select 1 role_id, id menu_id
		                from menus
		                union all
		                select role_id, menu_id
		                from role_menu
		                where is_active = 1
		            ) rm
		            , menus m
		        where u.code = '$user' and u.is_active= 1
		            and ru.user_id = u.code and rm.role_id = ru.role_id
		            and m.id = rm.menu_id and m.is_active = 1
		        order by m.parent, m.seq
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$data[$row['parent']][] = $row;
			}
		}
		return $this->recursive($data, 0);
		
	}

}
?>