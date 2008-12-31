<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2008 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class Permissions_Controller extends Controller {
  function form_edit($id) {
    $item = ORM::factory("item", $id);
    access::required("edit", $item);

    if ($item->type != "album") {
      access::forbidden();
    }

    $view = new View("permission_edit.html");
    $view->item = $item;
    $view->groups = ORM::factory("group")->find_all();
    $view->permissions = ORM::factory("permission")->find_all();
    print $view;
  }

  function edit($id)  {
    access::verify_csrf();

    $item = ORM::factory("item", $id);
    access::required("edit", $item);

    foreach (ORM::factory("group")->find_all() as $group) {
      foreach (ORM::factory("permission")->find_all() as $permission) {
        $perm_name = "{$permission->name}_$group->id";
        $value = $this->input->post($perm_name);

        // Set permissions here
      }
    }
    url::redirect("form/edit/permissions/$item->id");
  }
}