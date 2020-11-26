<form name="module" action="<?php //echo $mod_page; ?>" method="post">
    <input name="action" type="hidden" value="" />
    <input name="item_id" type="hidden" value="" />
    <input name="item_val" type="hidden" value="" />

    <div class="order-info">
        <h2><?php echo $langTxt['descTitle']; ?></h2>
        <div><?php echo $langTxt['orderDate']; ?>: <b><?php echo $data['date']; ?></b></div>
    </div>

    <br />

    <div class="dynamic-tab-pane-control" id="tabs">

        <div class="tab-row">
            <h2 class="tab selected"><span><?php echo $langTxt['includes']; ?></span></h2>
            <h2 class="tab"><span><?php echo $langTxt['edit']; ?></span></h2>
            <h2 class="tab"><span><?php echo $langTxt['contact']; ?></span></h2>
        </div>

        <!-- \\\tab content 1\\\ -->
        <div class="tab-page">

            <div class="order-tab-1-wrapper">
                <ul class="order-prododucts-listing">
                  <?php //echo $orderDataList; ?>

                  <?php
                  foreach($data['purchases'] as $item_key => $item) {
                    echo '<li><b>'.$item[3].'</b>: <i>'.$item[2].'руб</i>. x <i>'.$item[1].'шт.</i></li>';
                  }
                  ?>
                </ul>
            </div>

        </div>
        <!-- ///tab content 1/// -->

        <!-- \\\tab content 2\\\ -->
        <div class="tab-page" style="display:none;">


            <div class="order-tab-2-wrapper" style="padding:14px;">
                <h3><?php echo $langTxt['includes']; ?></h3>
                <table class="listing-edit-order-products">

                  <?php foreach($data['purchases'] as $i => $dataArray): ?>

                    <?php list($id, $count, $price, $name) = $dataArray; ?>

                      <tr id="purchase-<?php echo $id; ?>">
                          <td class="purchase--id">
                              <input type="hidden" name="p_id[]" value="<?php echo $id; ?>" />
                            <?php echo $id; ?>
                          </td>
                          <td class="purchase--name">
                              <input style="width:270px" type="text" name="p_name[]" value="<?php echo htmlspecialchars($name); ?>" />
                          </td>
                          <td class="purchase--count">
                              x
                              <input style="width:30px" type="text" name="p_count[]" value="<?php echo $count; ?>" />
                          </td>
                          <td class="purchase--price">
                              <input style="width:70px" type="text" name="p_price[]" value="<?php echo $price; ?>" />
                              &nbsp;
                            <?php echo $data['currency']; ?>
                              &nbsp;
                          </td>
                          <!-- <td>
							&nbsp;<label><input type="checkbox" name="allow_<?php //echo $i; ?>" value="1"<?php //if(in_array($i,$p_allowed)): ?> checked="checked"<?php //endif; ?> /> <?php //echo $langTxt['can_order']; ?></label>
						</td>
						 -->
                          <td class="purchase--delete">
                              <label><input type="checkbox" name="delete_<?php echo $i; ?>" value="1" /> <?php echo $langTxt['delete']; ?></label>
                          </td>
                          <td>
                              <label><a href="#" class="btn" onclick="PurchaseItemEdit('<?php echo $id; ?>', '<?php echo $data["id"]; ?>');">Изменить</a></label>
                          </td>
                      </tr>

                    <?php /*if(!empty($data['addit_params'][$i])): ?>
                          <tr>
                              <td></td>
                              <td>
                                <?php for($ii=0;$ii<count($data['addit_params'][$i]);$ii++):
                                  list($a_name,$a_price) = $data['addit_params'][$i][$ii];
                                  ?>
                                    <input style="width:180px" type="text" name="a_name_<?php echo $id."_".$i; ?>[]" value="<?php echo $a_name; ?>" />
                                    <input style="width:80px" type="text" name="a_price_<?php echo $id."_".$i; ?>[]" value="<?php echo $a_price; ?>" />
                                    <br />

                                <?php endfor; ?>
                              </td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                          </tr>
                    <?php endif;*/ ?>

                      <tr><td colspan="6"><span class="splitter"></span></td></tr>

                  <?php endforeach; ?>

                    <script>
                        function PurchaseItemEdit(id, orderID) {
                            const purchaseID = document.querySelector('.listing-edit-order-products #purchase-' + id + ' .purchase--id input').value;
                            const purchaseNAME = document.querySelector('.listing-edit-order-products #purchase-' + id + ' .purchase--name input').value;
                            const purchaseCOUNT = document.querySelector('.listing-edit-order-products #purchase-' + id + ' .purchase--count input').value;
                            const purchasePRICE = document.querySelector('.listing-edit-order-products #purchase-' + id + ' .purchase--price input').value;
                            let purchaseDELETE = document.querySelector('.listing-edit-order-products #purchase-' + id + ' .purchase--delete input');

                            if($(purchaseDELETE).is(':checked')) {
                                purchaseDELETE = 'true';
                            }
                            else {
                                purchaseDELETE = 'false';
                            }


                            let request = $.ajax({
                                url: '/shk-purchase-edit',
                                method: 'POST',
                                data: {
                                    id : purchaseID,
                                    name: purchaseNAME,
                                    count: purchaseCOUNT,
                                    price: purchasePRICE,
                                    delete: purchaseDELETE,
                                    order_id: orderID
                                },
                                dataType: 'json',
                                success: function(data) {
                                    if(data.status === true) {
                                        window.location.reload();
                                    }
                                    else {
                                        alert('Изменение товара не выполнено.');
                                    }
                                }
                            });
                        }
                    </script>

                </table>


                <!-- <div class="add-to-order-splitter"></div>
				<h3><?php //echo $langTxt['add_to_order']; ?></h3>

				<table>
					<tr>
						<td>
							<?php //echo $langTxt['id']; ?>:
							<br />
							<input style="width:80px" type="text" name="add_prod_id" value="" />
						</td>
						<td>
							<?php //echo $langTxt['count']; ?>:
							<br />
							<input style="width:80px" type="text" name="add_prod_count" value="" />
						</td>
						<td>
							<?php //echo $langTxt['prod_price']; ?>:
							<br />
							<input style="width:80px" type="text" name="add_prod_price" value="" />
						</td>
						<td>
							<?php //echo $langTxt['prod_params']; ?>:
							<br />
							<input style="width:270px" type="text" name="add_prod_params" value="" />
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<?php //echo $langTxt['prod_params_help']; ?>
						</td>
					</tr>
				</table> -->

                <div class="add-to-order-splitter"></div>
                <h3>Добавить товар к заказу</h3>

                <table>
                    <tr class="purchase__add">
                        <td class="purchase__add--id">
                            <label><input type="text" name="id" placeholder="ID ресурса" value="" /></label>
                        </td>
                        <td class="purchase__add--count">
                            x
                            <label><input type="text" name="count" placeholder="Количество" value="1" /></label>
                        </td>
                        <td class="purchase__add--add">
                            <label><a href="#" class="btn" onclick="PurchaseItemAdd('<?php echo $data["id"]; ?>');">Добавить</a></label>
                        </td>
                    </tr>
                    <script>
                        function PurchaseItemAdd(orderID) {
                            const purchaseID = document.querySelector('.purchase__add .purchase__add--id input').value;
                            const purchaseCOUNT = document.querySelector('.purchase__add .purchase__add--count input').value;


                            let request = $.ajax({
                                url: '/shk-purchase-add',
                                method: 'POST',
                                data: {
                                    id : purchaseID,
                                    count: purchaseCOUNT,
                                    order_id: orderID
                                },
                                dataType: 'json',
                                success: function(data) {
                                    if(data.status === true) {
                                        window.location.reload();
                                    }
                                    else {
                                        alert('Добавление товара не выполнено.');
                                    }
                                }
                            });
                        }
                    </script>
                </table>

            </div>


        </div>
        <!-- ///tab content 2/// -->

        <!-- \\\tab content 3\\\ -->
        <div class="tab-page" style="display:none;">
            <div class="base--contact-info-wrapper" style="padding:14px;">

                <div class="base--contact-info-wrapper__row">
                    <p class="base--contact-info-wrapper__row__caption"><?php echo $langTxt['email']; ?>:</p>
                    <input type="text" name="email" value="<?php echo $data['email']; ?>" readonly disabled />
                </div>

                <div class="base--contact-info-wrapper__row">
                    <p class="base--contact-info-wrapper__row__caption"><?php echo $langTxt['phone']; ?>:</p>
                    <input type="text" name="phone" value="<?php echo $data['phone']; ?>" readonly disabled />
                </div>

                <div class="base--contact-info-wrapper__row">
                    <p class="base--contact-info-wrapper__row__caption"><?php echo $langTxt['payment']; ?>:</p>
                    <input type="text" name="payment" value="<?php echo $data['payment']; ?>" readonly disabled />
                </div>

                <div class="base--contact-info-wrapper__row">
                    <p class="base--contact-info-wrapper__row__caption"><?php echo $langTxt['tracking_num']; ?>:</p>
                    <input type="text" name="tracking_num" value="<?php echo $data['tracking_num']; ?>" />
                </div>

                <div class="base--contact-info-wrapper__row">
                    <p class="base--contact-info-wrapper__row__caption"><?php echo $langTxt['note']; ?>:</p>
                    <textarea name="note" cols="40" rows="5"  style="he:ight60px"><?php echo $data['note']; ?></textarea>
                </div>

                <div class="base--contact-info-wrapper__row">
                    <p class="base--contact-info-wrapper__row__caption-title"><?php echo $langTxt['contact']; ?>:</p>
                    <div class="wrapper-contacts-info__description">
                      <?php echo $contactsInfo; ?>
                    </div>
                </div>

                <ul class="buttons buttons__flex--justify-end">
                    <li class="buttons__item"><a href="#" class="btn" onclick="ContactSave('<?php echo $data["id"]; ?>');"><i class="fa fa-floppy-o"></i>&nbsp; <?php echo $langTxt['save']; ?></a></li>
                </ul>

                <script>
                    function ContactSave(orderID) {

                        const contactTRACK = $('.base--contact-info-wrapper .base--contact-info-wrapper__row input[name="tracking_num"]').val();
                        const contactNOTE = $('.base--contact-info-wrapper .base--contact-info-wrapper__row textarea[name="note"]').val();

                        let request = $.ajax({
                            url: '/shk-contact-save',
                            method: 'POST',
                            data: {
                                track: contactTRACK,
                                note: contactNOTE,
                                order_id: orderID
                            },
                            dataType: 'json',
                            success: function(data) {
                                if(data.status === true) {
                                    window.location.reload();
                                }
                                else {
                                    alert('Изменение контактной информации не выполнено.');
                                }
                            }
                        });
                    }
                </script>
            </div>
        </div>
        <!-- ///tab content 3/// -->



    </div>

    <div class="total-order-info">
      <?php

      $delivery_title = '';
      $delivery_price = 0;

      if(!empty($data['purchases'])) {
        foreach($data['purchases'] as $item) {
          if(!empty($item['delivery_title'])) {
            switch($item['delivery_title']) {
              case 'belarus':
                $item['delivery_title'] = 'Курьером по Беларуси';
                break;
              case 'minsk':
                $item['delivery_title'] = 'Курьером по Минску';
                break;
              default:
                break;
            }

            $delivery_title = $item['delivery_title'];
          }
          if(!empty($item['delivery_price'])) {
            $delivery_price = $item['delivery_price'];
          }
        }
      }

      echo '<p class="total-order-info-row">Доставка: '.$delivery_title.' <b>'.$delivery_price.'</b> '.$data['currency'].'</p>';

      unset($delivery_title);
      unset($delivery_price);
      ?>

        <p class="total-order-info-row">
          <?php echo $langTxt['sumTotal'].": <b>".sprintf('%01.2F',(float) str_replace(',','.',$data['price']))."</b> ".$data['currency']; ?>
        </p>
    </div>

    <br clear="all" />

    <ul class="buttons buttons__flex--justify-start">
        <!-- <li class="buttons__item"><a href="#" class="btn btn-success" onclick="postForm('save_purchases',<?php //echo $data['id']; ?>,1);return false;" class="primary"><i class="fa fa-check-square-o"></i>&nbsp; <?php //echo $langTxt['accept_to_pay']; ?></a></li> -->
        <!-- <li class="buttons__item"><a href="#" class="btn" onclick="postForm('save_purchases',<?php //echo $data['id']; ?>,null);return false;"><i class="fa fa-floppy-o"></i>&nbsp; <?php //echo $langTxt['save']; ?></a></li> -->
        <li class="buttons__item"><a href="<?php echo $mod_page; ?>" class="btn"><i class="fa fa-arrow-left"></i>&nbsp; <?php echo $langTxt['back']; ?></a></li>

      <?php if(isset($plugin['OnSHKOrderDescRender'])) echo $plugin['OnSHKOrderDescRender']; ?>

    </ul>

</form>
