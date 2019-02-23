<?php
  require("../common.php");

  // If the order form has been submitted
  if (!empty($_POST)) {
    if ($_POST['token'] != $_SESSION['token'] or empty($_POST['token'])) {
      echo "Invalid token.";
      die();
    }

    // Unset the token
    unset($_SESSION['token']);

    // Check if an image was uploaded, if it was make sure it's valid
    if ($_FILES['fileupload']['error'] == 0) {
      // Check file size
      if ($_FILES['fileupload']['size'] > 5242880 {
        header("Location: ../../place-an-order/?e=1");
        die();
      }
      // Check file type
      if ($_FILES['fileupload']['type'] == "image/gif" or 
          $_FILES['fileupload']['type'] == "image/jpeg" or
          $_FILES['fileupload']['type'] == "image/jpg" or
          $_FILES['fileupload']['type'] == "image/png") {
        // All good, let's move the file
        $uploaddir = "/home/ivanrsfr/www/upload/" . $_SESSION['user']['customer_id'] . "/";
        $uploadfile = $uploaddir . basename($_FILES['fileupload']['name']);
        if (!is_dir($uploaddir)) {
          mkdir($uploaddir, 0777, true);
        }
        if (!move_uploaded_file($_FILES['fileupload']['tmp_name'], $uploadfile)) {
          echo "Oops! Something went wrong. Try again.";
          die();
        }
      }
      else {
        header("Location: ../../place-an-order/?e=2");
        die();
      }
    }
    else if ($_FILES['fileupload']['error'] == 1 or $_FILES['fileupload']['error'] == 2) {
      header("Location: ../../place-an-order/?e=1");
      die();
    }
    else if ($_FILES['fileupload']['error'] == 3 or
             $_FILES['fileupload']['error'] == 6 or
             $_FILES['fileupload']['error'] == 7 or
             $_FILES['fileupload']['error'] == 8) {
      header("Location: ../../place-an-order/?e=3");
      die();
    }

    // Get the cake_id of the cake based on the cake_size and cake_type
    $query = "
      SELECT
        cake_id
      FROM
        cakes
      WHERE
        cake_size = :cake_size
      AND
        cake_type = :cake_type
    ";

    $query_params = array(
      ':cake_size'  => $_POST['cake_size'],
      ':cake_type'  => $_POST['cake_type']
    );

    $db->runQuery($query, $query_params);

    $row = $db->fetch();
    $cake_id = $row['cake_id'];
    
    // Generate order number and make sure it is unique
    $order_number_unique  = false;
    
    do {
      $order_number         = $_SESSION['user']['customer_id'] . rand(10000,99999);
      
      $query = "
        SELECT
          *
        FROM
          orders
        WHERE
          order_number = :order_number
      ";

      $query_params = array(
        ':order_number' => $order_number
      );

      $db->runQuery($query, $query_params);

      $row = $db->fetch();

      if (!$row) {
        $order_number_unique = true;
      }
    }
    while ($order_number_unique === false);

    $query = "
      SELECT
        cake_price
      FROM
        cakes
      WHERE
        cake_size = :cake_size
      AND
        cake_type = :cake_type
    ";

    $query_params = array(
      'cake_size' => $_POST['cake_size'],
      'cake_type' => $_POST['cake_type']
    );

    $db->runQuery($query, $query_params);
    $row = $db->fetch();

    $base_price = $row['cake_price'];

    // Insert the order into the DB
    $query = "
      INSERT INTO orders (
        customer_id,
        order_number,
        celebration_date,
        comments,
        decor_id,
        filling_id,
        cake_id,
        order_placed,
        delivery_type,
        status,
        datetime,";

    if (!empty($_FILES)) {
      $query .= "
          image,
      ";
    }

    $query .= "
        base_price
      ) VALUES (
        :customer_id,
        :order_number,
        :celebration_date,
        :comments,
        :decor_id,
        :filling_id,
        :cake_id,
        :order_placed,
        :delivery_type,
        :status,
        :datetime,";

    if (!empty($_FILES)) {
      $query .= "
        :image,
      ";
    }

    $query .= "
        :base_price
      )
    ";

    $order_placed   = date('Y-m-d H:i:s');
    $status         = "Processing";

    $query_params = array(
      ':customer_id'        => $_SESSION['user']['customer_id'],
      ':order_number'       => $order_number,
      ':celebration_date'   => $_POST['celebration_date'],
      ':comments'           => $_POST['comments'],
      ':decor_id'           => $_POST['decoration'],
      ':filling_id'         => $_POST['filling'],
      ':cake_id'            => $cake_id,
      ':order_placed'       => $order_placed,
      ':delivery_type'      => $_POST['delivery'],
      ':status'             => $status,
      ':datetime'           => $_POST['datetime'],
      ':base_price'         => $base_price
     );

    if (!empty($_FILES)) {
      $query_params[':image'] = str_replace("/var/www/ivanbrazza.biz/htdocs/", "../", $uploadfile);
    }

    $db->runQuery($query, $query_params);

    // If the order is to be delivered then calculate the
    // delivery charge and insert the delivery details into
    // the "delivery" DB table.
    if ($_POST['delivery'] === "Deliver To Address") {
      include "../delivery.class.php";
      $delivery = new Delivery;
      $delivery->setAddress($_SESSION['user']['address']);
      $delivery->setPostcode($_SESSION['user']['postcode']);
      $delivery->calculateDistance();
      $delivery->calculateDeliveryCharge();
      $distance = $delivery->getDistance();
      $deliveryCharge = $delivery->getDeliveryCharge();

      $query = "
        INSERT INTO delivery (
          order_number,
          miles,
          delivery_charge
        ) VALUES (
          :order_number,
          :miles,
          :delivery_charge
        )
      ";

      $status = "Processing";

      $query_params = array(
        ':order_number'     => $order_number,
        ':miles'            => $distance,
        ':delivery_charge'  => $deliveryCharge
      );

      $db->runQuery($query, $query_params);
    }

    // Start PayPal payment process
    include "../PayPal/PayWithPayPal.php";

    echo "success";
    die();
  }
