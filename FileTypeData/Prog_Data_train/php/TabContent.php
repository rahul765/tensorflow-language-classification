<?php $types = array('olej', 'maslo', 'wosk', 'kwas'); ?>
<div class="tab-content form_content">
    <?php foreach ($types as $type) { ?>
        <div role="tabpanel" class="tab-pane fade" id="<?php echo $type; ?>">
            <table class="table">
                <tbody>
                <?php foreach (Parser::getTabDataFor($type) as $id => $name) { ?>
                    <tr>
                        <td style="width: 20%; text-align: right">
                            <input class="form-control" value="<?php echo $form_data[$id]['gram']; ?>"
                                   style="width: 110px" type="number"
                                   name="<?php echo $id; ?>"
                                   min="1">
                        </td>
                        <td class="text-left">
                            <label for="<?php echo $id; ?>" class="col-form-label"><?php echo $name; ?></label>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>