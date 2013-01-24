
<!-- Server Name -->
<div class="navbar-search pull-right">
  <select name="search[server_name]" class="nav pull-right span2 change-submit">
    <option value="">All hosts</option>
    <? foreach ($server_name as $value) { ?>
      <option value="<?= $value ?>"<? if (isset($query["server_name"]) && $query["server_name"] == $value) { ?> selected<? } ?>><?= $value ?></option>
    <? } ?>
  </select>
  &nbsp;
</div>

<!-- Server ID -->
<div class="navbar-search pull-right">
  <select name="search[server_id]" class="nav pull-right span2 change-submit">
    <option value="">All servers</option>
    <? foreach ($server_id as $value) { ?>
      <option value="<?= $value ?>"<? if (isset($query["server_id"]) && $query["server_id"] == $value) { ?> selected<? } ?>><?= $value ?></option>
    <? } ?>
  </select>
</div>
