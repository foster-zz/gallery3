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
class Photos_Controller_Test extends Unit_Test_Case {
  public function setup() {
    $this->_save = array($_POST, $_SERVER);
    $_SERVER["HTTP_REFERER"] = "HTTP_REFERER";
  }

  public function teardown() {
    list($_POST, $_SERVER) = $this->_save;
  }

  public function change_photo_test() {
    $controller = new Photos_Controller();
    $root = ORM::factory("item", 1);
    $photo = photo::create(
      $root, MODPATH . "gallery/tests/test.jpg", "test.jpeg",
      "test", "test", identity::active_user()->id, "slug");
    $orig_name = $photo->name;

    $_POST["filename"] = "test.jpeg";
    $_POST["name"] = "new name";
    $_POST["title"] = "new title";
    $_POST["description"] = "new description";
    $_POST["slug"] = "new-slug";
    $_POST["csrf"] = access::csrf_token();
    access::allow(identity::everybody(), "edit", $root);

    ob_start();
    $controller->update($photo->id);
    $photo->reload();
    $results = ob_get_contents();
    ob_end_clean();

    $this->assert_equal(json_encode(array("result" => "success")), $results);
    $this->assert_equal("new-slug", $photo->slug);
    $this->assert_equal("new title", $photo->title);
    $this->assert_equal("new description", $photo->description);

    // We don't change the name, yet.
    $this->assert_equal($orig_name, $photo->name);
  }

  public function change_photo_no_csrf_fails_test() {
    $controller = new Photos_Controller();
    $root = ORM::factory("item", 1);
    $photo = photo::create(
      $root, MODPATH . "gallery/tests/test.jpg", "test.jpg", "test", "test");
    $_POST["name"] = "new name";
    $_POST["title"] = "new title";
    $_POST["description"] = "new description";
    access::allow(identity::everybody(), "edit", $root);

    try {
      $controller->_update($photo);
      $this->assert_true(false, "This should fail");
    } catch (Exception $e) {
      // pass
    }
  }
}
