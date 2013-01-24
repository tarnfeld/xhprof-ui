<div class="row">
  <div class="span12">
    <div class="page-header">
      <h2>All Sessions</h2>
    </div>

    <div class="navbar">
      <div class="navbar-inner">
        <span class="brand">Filter Sessions</span>

          <!-- Search -->
          <div class="navbar-search pull-left">
            <input type="text" class="search-query" placeholder="Search #" value="<?= (isset($search["id"]) ? $search["id"] : "") ?>" name="search[id]">
          </div>
      </div>
    </div>

    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th width="115px">Identifier</th>
          <th>URL</th>
          <th width="140px">Time</th>
          <th>Type</th>
          <th width="95px">Peak Memory</th>
          <th width="70px">Wall Time</th>
          <th width="70px">CPU Time</th>
        </tr>
      </thead>
      <tbody>
        <? if (count($sessions) > 0) { ?>
          <? foreach ($sessions as $session) { ?>
          <tr>
            <td width="115px"><a href="/session/<?=$session->id?>"><?= $session->id ?></a></td>
            <td><a href="/url/<?=$session->id?>"><?= $session->url ?></a></td>
            <td width="140px"><?= $session->timestamp ?></td>
            <td><?= ($session->isAjax ? '<span class="label label-info">Ajax</span>' : '') ?></td>
            <td width="95px"><?= round($session->peakMemory / 1024 / 1024, 3) ?>MB</td>
            <td width="70px"><?= round($session->wallTime / 1000000, 3) ?>s</td>
            <td width="70px"><?= round($session->cpu / 1000000, 3) ?>s</td>
          </tr>
          <? } ?>
        <? } else { ?>
          <tr class="no-results">
            <td colspan="7">
              <? if (count($search) > 0) { ?>
                <strong class="muted">No sessions matched your search</strong>
              <? } else { ?>
                <strong class="muted">No sessions to display</strong>
              <? } ?>
            </td>
          </tr>
        <? } ?>
      </tbody>
    </table>
  </div>
</div>
