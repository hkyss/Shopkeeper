<?php if(!$action): ?>
    <div style="clear:both;"></div>
    <div class="search-block">
        <form action="<?php echo $mod_page; ?>" method="get" class="header-form-search">
            <input type="hidden" name="a" value="112" />
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />

            <table>
                <tr>
                    <td>
                        <input style="width:120px;" name="search_orderid" placeholder="<?php echo $langTxt['search_orderid']; ?>" value="<?php echo $search_orderid; ?>" />
                    </td>
                    <td>
                        <select name="search_status">
                            <option value="0"></option>
                            <option value="1" <?php if($search_status == 1) echo 'selected="selected"'; ?>><?php echo $langTxt['phase1']; ?></option>
                            <option value="2" <?php if($search_status == 2) echo 'selected="selected"'; ?>><?php echo $langTxt['phase2']; ?></option>
                            <option value="3" <?php if($search_status == 3) echo 'selected="selected"'; ?>><?php echo $langTxt['phase3']; ?></option>
                            <option value="4" <?php if($search_status == 4) echo 'selected="selected"'; ?>><?php echo $langTxt['phase4']; ?></option>
                            <option value="5" <?php if($search_status == 5) echo 'selected="selected"'; ?>><?php echo $langTxt['phase5']; ?></option>
                            <option value="6" <?php if($search_status == 6) echo 'selected="selected"'; ?>><?php echo $langTxt['phase6']; ?></option>
                        </select>
                    </td>
                    <td>
                        <input class="datepicker" style="width:120px;" name="search_date" placeholder="<?php echo $langTxt['orderDate']; ?>" value="<?php echo $search_date; ?>" />
                    </td>
                    <td>
                        <input style="width:120px;" name="search_username" placeholder="<?php echo $langTxt['user']; ?>" value="<?php echo $search_username; ?>" />
                    </td>
                    <td><button type="submit"><i class="fa fa-search"></i><?php echo $langTxt['search']; ?></button></td>
                </tr>
            </table>

        </form>

    </div>

    <div class="export-wrp">
        <a href="<?php echo $mod_page; ?>" class="btn btn-info"><i class="fa fa-spinner"></i>&nbsp; <?php echo $langTxt['refresh']; ?></a>
        <a href="<?php echo $mod_page; ?>&action=config" class="btn"><i class="fa fa-cog"></i>&nbsp; <?php echo $langTxt['configTitle']; ?></a>
        <a href="#" onclick="postForm('csv_export',null,null);return false;" class="btn btn-success"><i class="fa fa-file-text-o"></i>&nbsp; <?php echo $langTxt['csv_export']; ?></a>
    </div>
<?php endif; ?>


<form action="<?php echo $mod_page; ?>" name="module" method="post">
    <input name="action" type="hidden" value="" />
    <input name="item_id" type="hidden" value="" />
    <input name="item_val" type="hidden" value="" />

  <?php if($total>0 && isset($data_query)): ?>

      <br />
      <div class="pages"><?php echo $pager; ?></div>

      <table id="ordersTable" class="order-table" width="99%">
          <col width="3%" />
          <col width="3%" />
          <col width="3%" />
          <col width="25%" />
          <col width="9%" />
          <col width="20%" />
          <col width="12%" />
          <col width="15%" />
          <col width="7%" />
          <col width="3%" />
          <thead>
          <tr>
              <th></th>
              <th>№</th>
              <th>ID</th>
              <th><?php echo $langTxt['contact']; ?></th>
              <th><?php echo $langTxt['sumTotal']; ?></th>
              <th><?php echo $langTxt['note']; ?></th>
              <th><?php echo $langTxt['dateTime']; ?></th>
              <th><?php echo $langTxt['phase']; ?></th>
              <th><?php echo $langTxt['user']; ?></th>
              <th class="header"><?php echo $langTxt['delete']; ?></th>
          </tr>
          </thead>
          <tfoot>
          <tr>
              <th  align="center"><input type="checkbox" name="check_all" value="" onclick="checkAll(this)" /></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th align="center">
                  <select onchange="if(confirm('<?php echo $langTxt['confirm'] ?>')){postForm('status_all',null,this.value)}else{this.value='0'};">
                      <option value="0"></option>
                      <option value="1"><?php echo $langTxt['phase1']; ?></option>
                      <option value="2"><?php echo $langTxt['phase2']; ?></option>
                      <option value="3"><?php echo $langTxt['phase3']; ?></option>
                      <option value="4"><?php echo $langTxt['phase4']; ?></option>
                      <option value="5"><?php echo $langTxt['phase5']; ?></option>
                      <option value="6"><?php echo $langTxt['phase6']; ?></option>
                  </select>
              </th>
              <th></th>
              <th  align="center"> <a href="#" title="<?php echo $langTxt['deleteChecked']; ?>" onclick="if(confirm('<?php echo $langTxt['confirm']; ?>')){postForm('delgroup',null,null)};return false" class="btn btn-xs btn-default remove-btn"><i class="fa fa-trash fa-fw"></i></a></th>
          </tr>
          </tfoot>
          <tbody>

          <?php

          $num = 1;

          function is_serial($string){
            return (@unserialize($string) !== false);
          }

          $product_iter = 0;
          while ($data = $modx->db->getRow($data_query)):
            $info = array();
            $pos = $total-($total-($start+$num));
            $user_id = $data['userid'];
            if($shkm->is_serialized($data["content"])){
              $data_content = unserialize($data["content"]);
            }else{
              $data_content = $data["content"];
            }
            if($shkm->is_serialized($data["short_txt"])){
              $contact_fields = unserialize($data["short_txt"]);
              $contactsInfo = $shkm->renderContactInfo($contact_fields,$conf_small_template);
            }else{
              $contactsInfo = $data["short_txt"];
            }
            ?>

              <tr style="background-color:<?php echo $phaseColor[$data['status']-1]; ?>">
                  <td align="center"><input type="checkbox" name="check[]" value="<?php echo $data["id"]; ?>" /></td>
                  <td align="center"><small><?php echo $pos; ?></small></td>
                  <td align="center"><b><?php echo $data['id']; ?></b></td>
                  <td>
                    <?php //echo $contactsInfo;
                    $info[] = '<div>Заказ №'.$data['id'].'</div>';
                    $info[] = '<div><a href="'.$mod_page.'&action=show_descr&item_id='.$data["id"].'">'.$langTxt['description'].'</a></div>';
                    echo implode('',$info);
                    $product_iter = $product_iter +1;
                    ?>
                      <!-- <a href="<?php //echo $mod_page; ?>&action=show_descr&item_id=<?php //echo $data["id"]; ?>"><?php //echo $langTxt['description']; ?></a> -->
                  </td>
                  <td>
                    <?php if(!empty($data['price'])): ?>
                        <b><?php echo $data['price']; ?></b> <?php echo $data['currency']; ?>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php echo $data["note"]; ?>

                      <!--
      <?php if($data["note"]): ?><br /><?php endif; ?>
      <?php if(isset($users_all_purchase[$user_id])): ?>
        <?php echo $langTxt['count_purchase']; ?>: <?php echo $users_all_purchase[$user_id][0]; ?> <?php echo $langTxt['times']; ?>
        <br />
        <?php echo $langTxt['sumTotal']; ?>: <?php echo $users_all_purchase[$user_id][1]; ?> <?php echo $conf_currency; ?>
      <?php endif; ?>
      -->

                  </td>
                  <td><?php echo $data["date"]; ?> <?php if(isset($data["sentdate"])) echo " - ".$data["sentdate"]; ?></td>
                  <td align="center">
                      <span style="display:none"><?php echo $data['status']; ?></span>
                      <select onchange="if(confirm('<?php echo $langTxt['confirm'] ?>')){postForm('status',<?php echo $data["id"]; ?>,this.value)}else{this.value='<?php echo $data['status']; ?>'};">
                          <option value="1"<?php if($data['status']==1): ?> selected<?php endif; ?>><?php echo $langTxt['phase1']; ?></option>
                          <option value="2"<?php if($data['status']==2): ?> selected<?php endif; ?>><?php echo $langTxt['phase2']; ?></option>
                          <option value="3"<?php if($data['status']==3): ?> selected<?php endif; ?>><?php echo $langTxt['phase3']; ?></option>
                          <option value="4"<?php if($data['status']==4): ?> selected<?php endif; ?>><?php echo $langTxt['phase4']; ?></option>
                          <option value="5"<?php if($data['status']==5): ?> selected<?php endif; ?>><?php echo $langTxt['phase5']; ?></option>
                          <option value="6"<?php if($data['status']==6): ?> selected<?php endif; ?>><?php echo $langTxt['phase6']; ?></option>
                      </select>
                  </td>
                  <td align="center">

                    <?php if(isset($data['userid']) && isset($userName[$user_id])): ?>

                        <a class="iframe" href="index.php?a=88&id=<?php echo $user_id; ?>" title="<?php echo $langTxt["userData"]; ?>"><?php echo $userName[$user_id]; ?></a>

                    <?php else:?>

                        <span title="<?php echo $langTxt["unregistered"]; ?>">&mdash;</span>

                    <?php endif; ?>

                  </td>
                  <td align="center"><a href="#" title="<?php echo $langTxt['delete']; ?>" onclick="if(confirm('<?php echo $langTxt['confirm']; ?>')){postForm('delete',<?php echo $data["id"]; ?>,null)};return false" class="btn btn-xs btn-default remove-btn"><i class="fa fa-trash fa-fw"></i></a></td>
              </tr>


            <?php $num++; endwhile; ?>


          </tbody>
      </table>
      <div class="wrapper-foot-pages">
          <div class="pages"><?php echo $pager; ?></div>
      </div>


  <?php else: ?>

      <div style="clear:both; text-align:center; line-height:70px;"><i><?php echo $langTxt['noOrders']; ?></i></div>

  <?php endif;?>

    <br />

</form>

