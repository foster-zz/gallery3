<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2009 Bharat Mediratta
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
class Logout_Controller extends Controller {
  public function index() {
    access::verify_csrf();

    $user = user::active();
    user::logout();
    log::info("user", t("User %name logged out", array("name" => p::clean($user->name))),
              html::anchor("user/$user->id", p::clean($user->name)));
    if ($this->input->get("continue")) {
      $item = url::get_item_from_uri($this->input->get("continue"));
      if (access::can("view", $item)) {
        url::redirect($this->input->get("continue"));
      } else {
        url::redirect("");
      }
    }
  }
}