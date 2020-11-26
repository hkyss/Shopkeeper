<?php
/**
 * Shopkeeper Ajax
 *
 * plugin
 *
 * @category        plugin
 * @version         0.1
 * @author          hkyss
 * @documentation   empty
 * @lastupdate      05.11.2020
 * @internal    	@modx_category Ajax
 * @internal    	@events OnPageNotFound
 * @internal    	@properties &test1=test 1;string;empty &test2=test 2;string;empty
 *
 */

if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
  return;
}

switch ($_GET['q']) {
  case 'shk-purchase-edit':
    if(!empty($_GET['id']) && !empty($_GET['name']) && !empty($_GET['count']) && !empty($_GET['price']) && !empty($_GET['delete']) && !empty($_GET['order_id'])) {

      $shk_content = $modx->db->getValue(
        $modx->db->query('Select content From '.$modx->getFullTableName('manager_shopkeeper').' Where id = '.(int)$_GET['order_id'])
      );

      $shk_content = unserialize($shk_content);

      foreach($shk_content as $item_key => $item) {
        if((int)$item[0] === (int)$_GET['id']) {
          if($_GET['delete'] === 'false') {
            $shk_content[$item_key][1] = (int)$_GET['count'];
            $shk_content[$item_key][2] = str_replace(',','.',sprintf("%01.2f", $_GET['price']));;
            $shk_content[$item_key][3] = htmlspecialchars($_GET['name']);
          }
          if($_GET['delete'] === 'true') {
            unset($shk_content[$item_key]);
          }
        }
      }

      $total = 0;

      foreach($shk_content as $item_key => $item) {
        $total += $item[2] * $item[1];
      }

      $total = $total + $shk_content[0]['delivery_price'];

      $shk_content = serialize($shk_content);

      $modx->db->update(array(
        'content' => $shk_content,
        'price' => $total
      ), $modx->getFullTableName('manager_shopkeeper'), 'id = '.(int)$_GET['order_id'] );

      echo json_encode(array('status' => true));
    }
    else {
      echo json_encode(array('status' => false));
    }

    die();
    break;
  case 'shk-contact-save':
    if(!empty($_GET['order_id'])) {
      $track = empty($_GET['track']) ? '' : $_GET['track'];
      $note = empty($_GET['note']) ? '' : $_GET['note'];

      $modx->db->update(array(
        'tracking_num' => $track,
        'note' => $note
      ), $modx->getFullTableName('manager_shopkeeper'), 'id = '.(int)$_GET['order_id'] );

      echo json_encode(array('status' => true));
    }
    else {
      echo json_encode(array('status' => false));
    }
    die();
    break;
  case 'shk-purchase-add':
    if(!empty($_GET['order_id']) && !empty($_GET['id']) && !empty($_GET['count'])) {
      $resource['content'] = $modx->getDocument((int)$_GET['id']);
      $resource['tvs'] = $modx->getTemplateVars(array('product_image','product_brand','product_code','product_price','product_availability'), '*', (int)$_GET['id']);

      $shk_content = $modx->db->getValue(
        $modx->db->query('Select content From '.$modx->getFullTableName('manager_shopkeeper').' Where id = '.(int)$_GET['order_id'])
      );
      $shk_content = unserialize($shk_content);

      $delivery_title = '';
      $delivery_price = 0;
      $purchase_find = false;
      foreach($shk_content as $item_key => $item) {
        if(!empty($item['delivery_title'])) {
          $delivery_title = $item['delivery_title'];
        }
        if(!empty($item['delivery_price'])) {
          $delivery_price = $item['delivery_price'];
        }

        if((int)$item[0] === (int)$_GET['id']) {
          $shk_content[$item_key][1] = (int)$shk_content[$item_key][1] + (int)$_GET['count'];
          $purchase_find = true;
        }
      }

      if(!empty($resource['tvs'])) {
        foreach($resource['tvs'] as $item_key => $item) {
          $resource['tvs'][ $item['name'] ] = $item['value'];
          unset($resource['tvs'][$item_key]);
        }
      }

      if($purchase_find === false) {
        $shk_content[] = array(
          0 => (int)$_GET['id'],
          'catalog' => 0,
          1 => (int)$_GET['count'],
          2 => str_replace(',','.',sprintf("%01.2f", $resource['tvs']['product_price'])),
          'delivery_title' => $delivery_title,
          'delivery_price' => $delivery_price,
          3 => $resource['content']['pagetitle'],
          'tv' => $resource['tvs']
        );
      }

      $total = 0;

      foreach($shk_content as $item_key => $item) {
        $total += $item[2] * $item[1];
      }

      $total = $total + $shk_content[0]['delivery_price'];

      $shk_content = serialize($shk_content);

      $modx->db->update(array(
        'content' => $shk_content,
        'price' => $total
      ), $modx->getFullTableName('manager_shopkeeper'), 'id = '.(int)$_GET['order_id'] );

      unset($delivery_title);
      unset($delivery_price);
      unset($purchase_find);

      echo json_encode(array('status' => true));
    }
    else {
      echo json_encode(array('status' => false));
    }
    die();
    break;
  default:
    break;
}
?>